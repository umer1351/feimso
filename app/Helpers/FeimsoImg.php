<?php

namespace App\Helpers;

class FeimsoImg
{
    private const POOLS = [
        'sale'       => [1, 4, 5, 6, 7, 10],
        'rental'     => [7, 5, 1, 6, 4, 10],
        'service'    => [2, 12, 11, 3],
        'experience' => [3, 11, 12, 2],
        'lookbook'   => [1, 4, 5, 6, 7, 8, 10, 12],
        'avatar'     => [8, 9],
    ];

    /** Returns the real URL when present, else a stock fashion photo. */
    public static function url($url, string $type = 'sale', $seed = 0): string
    {
        return !empty($url) ? $url : self::fallback($type, $seed);
    }

    public static function fallback(string $type = 'sale', $seed = 0): string
    {
        $type = $type === 'for_sale' ? 'sale' : ($type === 'for_rent' ? 'rental' : $type);
        $pool = self::POOLS[$type] ?? self::POOLS['sale'];
        $n = $pool[abs(crc32((string) $seed)) % count($pool)];
        return asset(sprintf('img/feimso/photos/fashion-%02d.jpg', $n));
    }
}
