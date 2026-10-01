<?php

namespace App\Admin;

use App\Firestore\Codec;
use App\Firestore\CollectionSchema;
use App\Firestore\Engine;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Admin-side document access. Goes through the same Engine as the website,
 * so whatever the admin saves is stored exactly the way the views expect.
 * No browser access rules apply here; routes are behind the `admin` middleware.
 */
class DocRepository
{
    public function __construct(private Engine $engine)
    {
    }

    /**
     * @param string[] $search fields to LIKE-search
     * @param array<string,mixed> $filters field => exact value
     */
    public function paginate(string $collection, ?string $q, array $search, array $filters, ?string $sort, int $perPage = 20): LengthAwarePaginator
    {
        $c = CollectionSchema::find($collection);
        $query = DB::table($c->table);

        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $expr = $this->expr($c, $field);
            if ($c->type($field) === 'bool') {
                $query->whereRaw("$expr = ?", [$value === '1' || $value === 1 || $value === true ? 1 : 0]);
            } elseif ($c->hasColumn($field) && $c->type($field) !== 'json') {
                $query->whereRaw("$expr = ?", [$value]);
            } else {
                $query->whereRaw("JSON_UNQUOTE($expr) = ?", [$value]);
            }
        }

        if ($q !== null && trim($q) !== '') {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], trim($q)) . '%';
            $query->where(function ($w) use ($c, $search, $like) {
                $w->where('id', 'like', $like);
                foreach ($search as $field) {
                    $expr = $this->expr($c, $field);
                    $w->orWhereRaw(($c->hasColumn($field) && $c->type($field) !== 'json' ? $expr : "JSON_UNQUOTE($expr)") . ' LIKE ?', [$like]);
                }
            });
        }

        [$sortField, $dir] = array_pad(explode(':', $sort ?: '_created:desc'), 2, 'asc');
        $query->orderByRaw($this->expr($c, $sortField) . ' ' . ($dir === 'desc' ? 'desc' : 'asc'))->orderBy('id');

        $page = $query->paginate($perPage)->withQueryString();
        $page->getCollection()->transform(fn ($row) => $this->engine->rowToDoc($c, (array) $row, true));
        return $page;
    }

    public function find(string $collection, string $id): ?array
    {
        $c = CollectionSchema::find($collection);
        $row = DB::table($c->table)->where('id', $id)->first();
        return $row ? $this->engine->rowToDoc($c, (array) $row, true)['data'] : null;
    }

    /** All docs as id => label, for select boxes. */
    public function options(string $collection, string $labelField, array $filters = []): array
    {
        $c = CollectionSchema::find($collection);
        $query = DB::table($c->table);
        foreach ($filters as $field => $value) {
            $query->whereRaw($this->expr($c, $field) . ' = ?', [$value]);
        }
        $out = [];
        foreach ($query->limit(2000)->get() as $row) {
            $doc = $this->engine->rowToDoc($c, (array) $row, true);
            $label = $doc['data'][$labelField] ?? null;
            $out[$doc['id']] = is_scalar($label) && $label !== '' ? (string) $label : $doc['id'];
        }
        asort($out, SORT_NATURAL | SORT_FLAG_CASE);
        return $out;
    }

    /** Merge $changes into the doc (or create it) and save. Returns the id. */
    public function save(string $collection, ?string $id, array $changes): string
    {
        $c = CollectionSchema::find($collection);
        $id = $id ?: self::newId();
        $existing = $this->find($collection, $id);
        $doc = array_merge($existing ?? [], Codec::resolve($changes));
        foreach ($doc as $k => $v) {
            if ($v === null) {
                unset($doc[$k]);
            }
        }
        $this->engine->saveDoc($c, $id, $doc, $existing !== null);
        return $id;
    }

    public function delete(string $collection, string $id): void
    {
        $c = CollectionSchema::find($collection);
        DB::table($c->table)->where('id', $id)->delete();
    }

    public function count(string $collection, array $filters = []): int
    {
        $c = CollectionSchema::find($collection);
        $query = DB::table($c->table);
        foreach ($filters as $field => $value) {
            $query->whereRaw($this->expr($c, $field) . ' = ?', [$c->type($field) === 'bool' ? (int) $value : $value]);
        }
        return $query->count();
    }

    /** Firestore-style 20 character id. */
    public static function newId(): string
    {
        return Str::random(20);
    }

    private function expr(CollectionSchema $c, string $field): string
    {
        if (in_array($field, ['id', '_created', '_updated'], true)) {
            return "`$field`";
        }
        if (!preg_match('/^[A-Za-z0-9_]+$/', $field)) {
            throw new \InvalidArgumentException("Invalid field '$field'");
        }
        return $c->hasColumn($field) ? "`$field`" : "JSON_EXTRACT(`extra`, '$.\"$field\"')";
    }
}
