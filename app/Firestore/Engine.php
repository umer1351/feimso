<?php

namespace App\Firestore;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Runs Firestore-style queries and writes against MySQL tables.
 *
 * Mirrors the Firestore semantics the views depend on:
 *  - orderBy on a field excludes docs where that field is missing (NULL)
 *  - results are tie-broken by document id, in the direction of the last orderBy
 *  - an inequality filter with no orderBy implies orderBy(thatField)
 *  - cursors (startAt/startAfter/endAt/endBefore) by values or by document id
 *  - set() replaces, set(merge) deep-merges, update() fails on a missing doc
 */
class Engine
{
    private const INEQUALITY = ['<', '<=', '>', '>=', '!=', 'not-in'];

    public function __construct(private Access $access)
    {
    }

    // ---------------------------------------------------------------- reads

    public function getDoc(string $collection, string $id): ?array
    {
        $c = CollectionSchema::find($collection);
        $this->access->assertCanRead($c);

        $row = DB::table($c->table)->where('id', $id)->first();
        if (!$row) {
            return null;
        }
        $doc = $this->rowToDoc($c, (array) $row);
        if (!$this->access->canReadDoc($c, $doc)) {
            throw new AccessDenied("Missing or insufficient permissions.");
        }
        return $doc;
    }

    /**
     * @param array $q {filters:[[field,op,value]], orderBy:[[field,dir]], limit, limitToLast,
     *                  offset, startAt|startAfter|endAt|endBefore: {values:[...]} | {doc:id}}
     * @return array<int, array{id:string,data:array}>
     */
    public function query(string $collection, array $q): array
    {
        $c = CollectionSchema::find($collection);
        $this->access->assertCanRead($c);

        $query = DB::table($c->table);

        $filters = $q['filters'] ?? [];
        foreach ($this->access->readConstraints($c) as $f) {
            $filters[] = $f;
        }
        foreach ($filters as [$field, $op, $value]) {
            $this->applyFilter($query, $c, $field, $op, $value);
        }

        // Ordering (explicit, or implied by an inequality filter) + id tie-break.
        $orders = [];
        foreach ($q['orderBy'] ?? [] as [$field, $dir]) {
            $orders[] = [$field, strtolower($dir ?? 'asc') === 'desc' ? 'desc' : 'asc'];
        }
        if (!$orders) {
            foreach ($filters as [$field, $op]) {
                if (in_array($op, self::INEQUALITY, true) && $field !== '__name__') {
                    $orders[] = [$field, 'asc'];
                    break;
                }
            }
        }
        $lastDir = $orders ? end($orders)[1] : 'asc';
        $hasIdOrder = false;
        foreach ($orders as [$field]) {
            $hasIdOrder = $hasIdOrder || $field === '__name__';
        }

        $orderExprs = [];
        foreach ($orders as [$field, $dir]) {
            [$expr, $kind] = $this->fieldExpr($c, $field);
            $orderExprs[] = [$expr, $kind, $dir, $field];
            if ($field !== '__name__') {
                $query->whereRaw("$expr IS NOT NULL");
            }
        }
        if (!$hasIdOrder) {
            $orderExprs[] = ['`id`', 'string', $lastDir, '__name__'];
        }

        $limitToLast = !empty($q['limitToLast']);
        foreach (['startAt', 'startAfter', 'endAt', 'endBefore'] as $k) {
            if (!empty($q[$k])) {
                $this->applyCursor($query, $c, $orderExprs, $q[$k], $k);
            }
        }

        foreach ($orderExprs as [$expr, , $dir]) {
            $effective = $limitToLast ? ($dir === 'asc' ? 'desc' : 'asc') : $dir;
            $query->orderByRaw("$expr $effective");
        }

        if (isset($q['offset'])) {
            $query->offset((int) $q['offset']);
        }
        if (isset($q['limit'])) {
            $query->limit(max(0, (int) $q['limit']));
        } elseif (isset($q['offset'])) {
            $query->limit(PHP_INT_MAX);
        }

        $rows = $query->get()->map(fn ($r) => $this->rowToDoc($c, (array) $r))->all();
        if ($limitToLast) {
            $rows = array_reverse($rows);
        }
        return array_values(array_filter($rows, fn ($doc) => $this->access->canReadDoc($c, $doc)));
    }

    // --------------------------------------------------------------- writes

    /**
     * @param array $ops [{op: set|update|delete, collection, id, data?, merge?}]
     */
    public function write(array $ops): void
    {
        DB::transaction(function () use ($ops) {
            foreach ($ops as $op) {
                $this->writeOne($op);
            }
        });
    }

    private function writeOne(array $op): void
    {
        $c = CollectionSchema::find($op['collection'] ?? '');
        $id = (string) ($op['id'] ?? '');
        if ($id === '' || strlen($id) > 191 || str_contains($id, '/')) {
            throw new InvalidArgumentException('Invalid document id');
        }

        $existingRow = DB::table($c->table)->where('id', $id)->lockForUpdate()->first();
        $existing = $existingRow ? $this->rowToDoc($c, (array) $existingRow, true)['data'] : null;

        $type = $op['op'] ?? '';
        if ($type === 'delete') {
            $this->access->assertCanWrite($c, $id, $existing, null, null);
            DB::table($c->table)->where('id', $id)->delete();
            return;
        }

        $data = Codec::resolve($op['data'] ?? []);
        if (!is_array($data)) {
            throw new InvalidArgumentException('Document data must be an object');
        }
        $locked = $this->access->isAdmin() ? $c->hidden : array_merge($c->hidden, $c->protected);
        foreach (array_keys($data) as $k) {
            if (in_array(explode('.', (string) $k)[0], $locked, true)) {
                unset($data[$k]); // e.g. users.password / users.role are never set from the browser
            }
        }
        $changed = array_values(array_unique(array_map(fn ($k) => explode('.', (string) $k)[0], array_keys($data))));

        if ($type === 'update') {
            if ($existing === null) {
                throw new NotFound("No document to update: {$c->name}/$id");
            }
            $new = $existing;
            foreach ($data as $path => $value) {
                $new = $this->setPath($new, explode('.', (string) $path), $value);
            }
        } elseif ($type === 'set') {
            if (!empty($op['merge'])) {
                $new = $this->deepMerge($existing ?? [], $data);
            } else {
                // Full replace, but locked columns (password, role...) are kept.
                $new = $data;
                foreach ($locked as $h) {
                    if ($existing !== null && array_key_exists($h, $existing)) {
                        $new[$h] = $existing[$h];
                    }
                }
            }
        } else {
            throw new InvalidArgumentException("Unknown write op '$type'");
        }

        $new = $this->stripDeletes($new);
        $this->access->assertCanWrite($c, $id, $existing, $new, $type === 'update' ? $changed : null);
        $this->saveDoc($c, $id, $new, $existingRow !== null);
    }

    /** Persist a full document (used by writes, seeders and the admin panel). */
    public function saveDoc(CollectionSchema $c, string $id, array $doc, bool $exists): void
    {
        $row = ['id' => $id];
        $extra = [];
        foreach ($c->columns as $field => $type) {
            $row[$field] = null;
        }
        foreach ($doc as $field => $value) {
            if ($c->hasColumn($field)) {
                [$ok, $dbValue] = Codec::toColumn($c->type($field), $value);
                if ($ok) {
                    $row[$field] = $dbValue;
                    continue;
                }
            }
            $extra[$field] = $value;
        }
        $row['extra'] = $extra ? Codec::jsonLiteral((object) $extra) : null;
        $now = now()->utc()->format('Y-m-d H:i:s.u');
        $row['_updated'] = $now;

        if ($exists) {
            DB::table($c->table)->where('id', $id)->update($row);
        } else {
            $row['_created'] = $now;
            DB::table($c->table)->insert($row);
        }
    }

    // -------------------------------------------------------------- helpers

    /** DB row -> {id, data}. $includeHidden is only for server-side use. */
    public function rowToDoc(CollectionSchema $c, array $row, bool $includeHidden = false): array
    {
        $data = [];
        foreach ($c->columns as $field => $type) {
            if (!$includeHidden && $c->isHidden($field)) {
                continue;
            }
            $v = Codec::fromColumn($type, $row[$field] ?? null);
            if ($v !== null) {
                $data[$field] = $v; // NULL == "field missing", like Firestore
            }
        }
        if (!empty($row['extra'])) {
            foreach (json_decode($row['extra'], true) ?: [] as $k => $v) {
                if ($includeHidden || !$c->isHidden($k)) {
                    $data[$k] = $v;
                }
            }
        }
        return ['id' => (string) $row['id'], 'data' => $data];
    }

    /**
     * SQL expression for a field path. kind is the column type for typed
     * columns, or 'json' when the expression yields a JSON value.
     *
     * @return array{0:string,1:string}
     */
    private function fieldExpr(CollectionSchema $c, string $path): array
    {
        if ($path === '__name__' || $path === 'id' && !$c->hasColumn('id')) {
            return ['`id`', 'string'];
        }
        $parts = explode('.', $path);
        foreach ($parts as $p) {
            if (!preg_match('/^[A-Za-z0-9_\-]+$/', $p)) {
                throw new InvalidArgumentException("Invalid field path '$path'");
            }
        }
        $top = array_shift($parts);
        if ($c->isHidden($top)) {
            throw new AccessDenied("Field '$top' is not queryable");
        }
        if ($c->hasColumn($top)) {
            $type = $c->type($top);
            if (!$parts) {
                return ["`$top`", $type];
            }
            if ($type === 'json') {
                return ["JSON_EXTRACT(`$top`, '" . $this->jsonPath($parts) . "')", 'json'];
            }
            return ['NULL', 'json'];
        }
        return ["JSON_EXTRACT(`extra`, '" . $this->jsonPath(array_merge([$top], $parts)) . "')", 'json'];
    }

    private function jsonPath(array $parts): string
    {
        return '$' . implode('', array_map(fn ($p) => '."' . $p . '"', $parts));
    }

    private function bind(string $kind, $value)
    {
        return $kind === 'json' ? Codec::jsonLiteral($value) : Codec::bindFor($kind, $value);
    }

    private function placeholder(string $kind): string
    {
        return $kind === 'json' ? 'CAST(? AS JSON)' : '?';
    }

    private function applyFilter(Builder $query, CollectionSchema $c, string $field, string $op, $value): void
    {
        [$expr, $kind] = $this->fieldExpr($c, $field);
        $ph = $this->placeholder($kind);

        switch ($op) {
            case '==':
                if ($value === null) {
                    $query->whereRaw($kind === 'json' ? "($expr IS NULL OR JSON_TYPE($expr) = 'NULL')" : "$expr IS NULL");
                } else {
                    $query->whereRaw("$expr = $ph", [$this->bind($kind, $value)]);
                }
                return;
            case '!=':
                $query->whereRaw("$expr IS NOT NULL AND $expr <> $ph", [$this->bind($kind, $value)]);
                return;
            case '<':
            case '<=':
            case '>':
            case '>=':
                $query->whereRaw("$expr $op $ph", [$this->bind($kind, $value)]);
                return;
            case 'in':
            case 'not-in':
                $values = array_values((array) $value);
                if (!$values) {
                    $query->whereRaw($op === 'in' ? '0 = 1' : '1 = 1');
                    return;
                }
                $list = implode(',', array_fill(0, count($values), $ph));
                $binds = array_map(fn ($v) => $this->bind($kind, $v), $values);
                $sql = $op === 'in' ? "$expr IN ($list)" : "$expr IS NOT NULL AND $expr NOT IN ($list)";
                $query->whereRaw($sql, $binds);
                return;
            case 'array-contains':
            case 'array-contains-any':
                if ($kind !== 'json') {
                    $query->whereRaw('0 = 1'); // typed scalar columns are never arrays
                    return;
                }
                $needles = $op === 'array-contains' ? [$value] : array_values((array) $value);
                if (!$needles) {
                    $query->whereRaw('0 = 1');
                    return;
                }
                $parts = array_fill(0, count($needles), "JSON_CONTAINS($expr, CAST(? AS JSON))");
                $query->whereRaw("(JSON_TYPE($expr) = 'ARRAY' AND (" . implode(' OR ', $parts) . '))',
                    array_map(fn ($v) => Codec::jsonLiteral($v), $needles));
                return;
            default:
                throw new InvalidArgumentException("Unsupported operator '$op'");
        }
    }

    /**
     * Lexicographic cursor over the ORDER BY tuple.
     *
     * @param array $orderExprs [[expr, kind, dir, field]] including the id tie-break
     */
    private function applyCursor(Builder $query, CollectionSchema $c, array $orderExprs, array $cursor, string $kind): void
    {
        $forward = in_array($kind, ['startAt', 'startAfter'], true);
        $strict = in_array($kind, ['startAfter', 'endBefore'], true);

        if (isset($cursor['doc'])) {
            // Cursor from a DocumentSnapshot: read that doc's sort values in SQL form.
            $select = implode(', ', array_map(fn ($o, $i) => "{$o[0]} AS v$i", $orderExprs, array_keys($orderExprs)));
            $row = DB::table($c->table)->selectRaw($select)->where('id', (string) $cursor['doc'])->first();
            if (!$row) {
                throw new NotFound('Cursor document not found');
            }
            $row = array_values((array) $row);
            $terms = [];
            foreach ($orderExprs as $i => [$expr, $k, $dir]) {
                $terms[] = [$expr, $dir, $k === 'json' ? 'CAST(? AS JSON)' : '?', $row[$i]];
            }
        } else {
            $values = array_values($cursor['values'] ?? []);
            $terms = [];
            foreach ($values as $i => $v) {
                if (!isset($orderExprs[$i])) {
                    break;
                }
                [$expr, $k, $dir] = $orderExprs[$i];
                $terms[] = [$expr, $dir, $this->placeholder($k), $this->bind($k, $v)];
            }
        }
        if (!$terms) {
            return;
        }

        $ors = [];
        $binds = [];
        $n = count($terms);
        for ($i = 0; $i < $n; $i++) {
            $and = [];
            for ($j = 0; $j < $i; $j++) {
                $and[] = "{$terms[$j][0]} = {$terms[$j][2]}";
                $binds[] = $terms[$j][3];
            }
            [$expr, $dir, $ph, $val] = $terms[$i];
            $gt = ($dir === 'asc') === $forward ? '>' : '<';
            $and[] = "$expr $gt $ph";
            $binds[] = $val;
            $ors[] = '(' . implode(' AND ', $and) . ')';
        }
        if (!$strict) {
            $and = [];
            foreach ($terms as [$expr, , $ph, $val]) {
                $and[] = "$expr = $ph";
                $binds[] = $val;
            }
            $ors[] = '(' . implode(' AND ', $and) . ')';
        }
        $query->whereRaw('(' . implode(' OR ', $ors) . ')', $binds);
    }

    private function setPath(array $doc, array $path, $value): array
    {
        $key = array_shift($path);
        if (!$path) {
            $doc[$key] = $value;
            return $doc;
        }
        $child = isset($doc[$key]) && is_array($doc[$key]) && !Codec::isTag($doc[$key]) ? $doc[$key] : [];
        $doc[$key] = $this->setPath($child, $path, $value);
        return $doc;
    }

    private function deepMerge(array $base, array $patch): array
    {
        foreach ($patch as $k => $v) {
            $isMap = is_array($v) && !Codec::isTag($v) && !array_is_list($v);
            $baseIsMap = isset($base[$k]) && is_array($base[$k]) && !Codec::isTag($base[$k]) && !array_is_list($base[$k]);
            $base[$k] = $isMap && $baseIsMap ? $this->deepMerge($base[$k], $v) : $v;
        }
        return $base;
    }

    private function stripDeletes($v)
    {
        if (!is_array($v) || Codec::isTag($v)) {
            return $v;
        }
        foreach ($v as $k => $item) {
            if (Codec::isTag($item, 'del')) {
                unset($v[$k]);
            } else {
                $v[$k] = $this->stripDeletes($item);
            }
        }
        return $v;
    }
}
