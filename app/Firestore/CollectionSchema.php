<?php

namespace App\Firestore;

use InvalidArgumentException;

/**
 * One Firestore collection mapped onto one MySQL table (see config/firestore.php).
 *
 * Every table has:  id (doc id), the typed columns listed in config, `extra`
 * (JSON, holds any field we have no column for, so unknown fields are never
 * dropped), and `_created` / `_updated` bookkeeping timestamps.
 */
class CollectionSchema
{
    public const SYSTEM_COLUMNS = ['id', 'extra', '_created', '_updated'];

    public function __construct(
        public readonly string $name,
        public readonly string $table,
        /** @var array<string,string> field => string|text|int|float|bool|timestamp|json */
        public readonly array $columns,
        /** @var string[] columns the browser can never read or write */
        public readonly array $hidden,
        public readonly string $read,
        public readonly string $write,
        public readonly ?string $owner,
        /** @var string[] readable, but the browser's writes to them are ignored */
        public readonly array $protected = [],
        /** @var string[] any signed-in user may update just these on an existing doc */
        public readonly array $shared = [],
    ) {
    }

    public static function find(string $name): self
    {
        $cfg = config("firestore.collections.$name");
        if (!$cfg) {
            throw new InvalidArgumentException("Unknown collection '$name'");
        }
        return new self(
            $name,
            $cfg['table'] ?? $name,
            $cfg['columns'] ?? [],
            $cfg['hidden'] ?? [],
            $cfg['read'] ?? 'public',
            $cfg['write'] ?? 'none',
            $cfg['owner'] ?? null,
            $cfg['protected'] ?? [],
            $cfg['shared'] ?? [],
        );
    }

    public function hasColumn(string $field): bool
    {
        return isset($this->columns[$field]);
    }

    public function type(string $field): ?string
    {
        return $this->columns[$field] ?? null;
    }

    /** Fields the browser may never set: hidden ones plus protected ones. */
    public function isClientLocked(string $field): bool
    {
        return in_array($field, $this->hidden, true) || in_array($field, $this->protected, true);
    }

    public function isHidden(string $field): bool
    {
        return in_array($field, $this->hidden, true);
    }
}
