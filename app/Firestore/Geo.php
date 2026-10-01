<?php

namespace App\Firestore;

/** GeoFirestore-compatible location fields (`g` + `coordinates`). */
class Geo
{
    private const BASE32 = '0123456789bcdefghjkmnpqrstuvwxyz';

    public static function point(float $lat, float $lng): array
    {
        return ['__fs' => 'geo', 'lat' => $lat, 'lng' => $lng];
    }

    /** The fields GeoFirestore's near() queries rely on. */
    public static function fields(float $lat, float $lng): array
    {
        return [
            'coordinates' => self::point($lat, $lng),
            'g' => ['geohash' => self::hash($lat, $lng), 'geopoint' => self::point($lat, $lng)],
        ];
    }

    public static function hash(float $lat, float $lng, int $precision = 10): string
    {
        $latR = [-90.0, 90.0];
        $lngR = [-180.0, 180.0];
        $hash = '';
        $bits = 0;
        $bit = 0;
        $even = true;
        while (strlen($hash) < $precision) {
            if ($even) {
                $mid = ($lngR[0] + $lngR[1]) / 2;
                $bits = ($bits << 1) | ($lng > $mid ? 1 : 0);
                $lngR[$lng > $mid ? 0 : 1] = $mid;
            } else {
                $mid = ($latR[0] + $latR[1]) / 2;
                $bits = ($bits << 1) | ($lat > $mid ? 1 : 0);
                $latR[$lat > $mid ? 0 : 1] = $mid;
            }
            $even = !$even;
            if (++$bit === 5) {
                $hash .= self::BASE32[$bits];
                $bits = 0;
                $bit = 0;
            }
        }
        return $hash;
    }
}
