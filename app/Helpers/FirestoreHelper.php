<?php

namespace App\Helpers;

use App\Firestore\CollectionSchema;
use App\Firestore\Engine;
use Illuminate\Support\Facades\DB;

/**
 * Server-side document access. Used to call the Firestore REST API; now reads
 * and writes the MySQL tables directly (no browser access rules apply here).
 * Values come back as plain PHP: timestamps as {__fs:'ts', s, n} arrays.
 */
class FirestoreHelper
{
    /** @param string $path "collection/docId" */
    public static function getDocument($path)
    {
        [$collection, $id] = array_pad(explode('/', (string) $path, 2), 2, '');
        $c = CollectionSchema::find($collection);
        $row = DB::table($c->table)->where('id', $id)->first();
        return $row ? self::engine()->rowToDoc($c, (array) $row)['data'] : null;
    }

    public static function getCollection($collection)
    {
        $c = CollectionSchema::find($collection);
        return DB::table($c->table)->get()
            ->map(fn ($r) => ['id' => $r->id] + self::engine()->rowToDoc($c, (array) $r)['data'])
            ->all();
    }

    public static function queryCollection($collection, $field, $op, $value)
    {
        $c = CollectionSchema::find($collection);
        return collect(self::getCollection($collection))
            ->filter(function ($doc) use ($field, $op, $value) {
                $v = data_get($doc, $field);
                return match ($op) {
                    '==', 'EQUAL' => $v == $value,
                    '!=', 'NOT_EQUAL' => $v != $value,
                    '<', 'LESS_THAN' => $v < $value,
                    '<=', 'LESS_THAN_OR_EQUAL' => $v <= $value,
                    '>', 'GREATER_THAN' => $v > $value,
                    '>=', 'GREATER_THAN_OR_EQUAL' => $v >= $value,
                    'array-contains', 'ARRAY_CONTAINS' => is_array($v) && in_array($value, $v),
                    default => false,
                };
            })->values()->all();
    }

    /**
     * Firestore-style query, server side. Same semantics as the browser's
     * collection().where()...get(): $filters = [[field, op, value]], $orderBy = [[field, dir]].
     *
     * @return array<int, array{id:string, data:array}>
     */
    public static function query(string $collection, array $filters = [], array $orderBy = [], ?int $limit = null): array
    {
        $q = ['filters' => $filters, 'orderBy' => $orderBy];
        if ($limit !== null) {
            $q['limit'] = $limit;
        }
        return self::engine()->query($collection, $q);
    }

    public static function setDocument($path, array $data)
    {
        [$collection, $id] = array_pad(explode('/', (string) $path, 2), 2, '');
        $c = CollectionSchema::find($collection);
        $exists = DB::table($c->table)->where('id', $id)->exists();
        self::engine()->saveDoc($c, $id, $data, $exists);
        return true;
    }

    private static function engine(): Engine
    {
        return app(Engine::class);
    }
}
