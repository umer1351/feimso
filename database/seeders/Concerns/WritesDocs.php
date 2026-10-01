<?php

namespace Database\Seeders\Concerns;

use App\Firestore\Codec;
use App\Firestore\CollectionSchema;
use App\Firestore\Engine;
use Illuminate\Support\Facades\DB;

/** Seeders write through the Engine so rows are stored exactly as the views expect. */
trait WritesDocs
{
    protected function doc(string $collection, string $id, array $data): void
    {
        $c = CollectionSchema::find($collection);
        $exists = DB::table($c->table)->where('id', $id)->exists();
        app(Engine::class)->saveDoc($c, $id, Codec::resolve($data), $exists);
    }

    protected function now(): array
    {
        return Codec::now();
    }
}
