<?php

namespace App\Firestore;

use Carbon\CarbonImmutable;
use DateTimeInterface;

/**
 * Converts values between the wire format used by public/js/firebase-local.js
 * and what we store in MySQL.
 *
 * Wire format: plain JSON, except for Firestore special types, which are
 * tagged objects:
 *   {"__fs":"ts","s":<seconds>,"n":<nanos>}   Timestamp
 *   {"__fs":"geo","lat":<float>,"lng":<float>} GeoPoint
 *   {"__fs":"now"}                              FieldValue.serverTimestamp()
 *   {"__fs":"del"}                              FieldValue.delete()
 *
 * Inside JSON columns we store the tagged form as-is, so nested timestamps and
 * geopoints survive a round trip unchanged.
 */
class Codec
{
    public static function isTag($v, string $tag = null): bool
    {
        return is_array($v) && isset($v['__fs']) && ($tag === null || $v['__fs'] === $tag);
    }

    public static function now(): array
    {
        $t = microtime(true);
        $s = (int) floor($t);
        return ['__fs' => 'ts', 's' => $s, 'n' => (int) round(($t - $s) * 1e6) * 1000];
    }

    /** Replace serverTimestamp sentinels anywhere in a value. */
    public static function resolve($v)
    {
        if (self::isTag($v, 'now')) {
            return self::now();
        }
        if (is_array($v) && !self::isTag($v)) {
            foreach ($v as $k => $item) {
                $v[$k] = self::resolve($item);
            }
        }
        return $v;
    }

    /** Wire timestamp (or ISO string / millis) -> MySQL DATETIME(6) string in UTC. */
    public static function toDateTime($v): ?string
    {
        if ($v === null) {
            return null;
        }
        if (self::isTag($v, 'ts')) {
            $micro = intdiv((int) ($v['n'] ?? 0), 1000);
            return CarbonImmutable::createFromTimestampUTC((int) $v['s'])
                ->addMicroseconds($micro)->format('Y-m-d H:i:s.u');
        }
        if ($v instanceof DateTimeInterface) {
            return CarbonImmutable::instance($v)->utc()->format('Y-m-d H:i:s.u');
        }
        if (is_numeric($v)) {
            return CarbonImmutable::createFromTimestampMsUTC((float) $v)->format('Y-m-d H:i:s.u');
        }
        if (is_string($v) && $v !== '') {
            try {
                return CarbonImmutable::parse($v)->utc()->format('Y-m-d H:i:s.u');
            } catch (\Throwable $e) {
                return null;
            }
        }
        return null;
    }

    /** MySQL DATETIME string (UTC) -> wire timestamp. */
    public static function fromDateTime(?string $v): ?array
    {
        if ($v === null || $v === '') {
            return null;
        }
        $d = CarbonImmutable::parse($v, 'UTC');
        return ['__fs' => 'ts', 's' => $d->getTimestamp(), 'n' => (int) $d->format('u') * 1000];
    }

    /**
     * Coerce a wire value for a typed column. Returns [ok, dbValue].
     * ok=false means the value doesn't fit the column; the caller keeps it in
     * the row's `extra` JSON instead so nothing is lost.
     */
    public static function toColumn(string $type, $v): array
    {
        if ($v === null) {
            return [true, null];
        }
        switch ($type) {
            case 'string':
            case 'text':
                if (is_bool($v)) {
                    return [false, null];
                }
                return is_scalar($v) ? [true, (string) $v] : [false, null];
            case 'int':
                if (is_bool($v)) {
                    return [true, $v ? 1 : 0];
                }
                if (is_int($v) || (is_numeric($v) && (string) (int) $v === (string) $v)) {
                    return [true, (int) $v];
                }
                if (is_float($v) && floor($v) == $v) {
                    return [true, (int) $v];
                }
                return [false, null];
            case 'float':
                if (is_numeric($v) && !is_bool($v)) {
                    return [true, (float) $v];
                }
                return [false, null];
            case 'bool':
                if (is_bool($v)) {
                    return [true, $v ? 1 : 0];
                }
                if ($v === 0 || $v === 1 || $v === '0' || $v === '1') {
                    return [true, (int) $v];
                }
                if ($v === 'true' || $v === 'false') {
                    return [true, $v === 'true' ? 1 : 0];
                }
                return [false, null];
            case 'timestamp':
                if (self::isTag($v, 'ts')) {
                    return [true, self::toDateTime($v)];
                }
                return [false, null];
            case 'json':
            default:
                return [true, json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION)];
        }
    }

    /** Typed column value from MySQL -> wire value. */
    public static function fromColumn(string $type, $v)
    {
        if ($v === null) {
            return null;
        }
        switch ($type) {
            case 'string':
            case 'text':
                return (string) $v;
            case 'int':
                return (int) $v;
            case 'float':
                return (float) $v;
            case 'bool':
                return (bool) $v;
            case 'timestamp':
                return self::fromDateTime($v);
            case 'json':
            default:
                return is_string($v) ? json_decode($v, true) : $v;
        }
    }

    /** Wire value -> SQL bind value for a comparison against a typed column. */
    public static function bindFor(string $type, $v)
    {
        switch ($type) {
            case 'bool':
                return $v ? 1 : 0;
            case 'timestamp':
                return self::isTag($v, 'ts') ? self::toDateTime($v) : $v;
            case 'string':
            case 'text':
                return is_bool($v) ? ($v ? 'true' : 'false') : (is_scalar($v) ? (string) $v : json_encode($v));
            default:
                return $v;
        }
    }

    public static function jsonLiteral($v): string
    {
        return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
    }
}
