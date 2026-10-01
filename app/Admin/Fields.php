<?php

namespace App\Admin;

use App\Firestore\Codec;
use App\Firestore\Geo;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Converts between stored document values and admin form inputs, per field
 * type (see config/admin.php for the list of types).
 */
class Fields
{
    private const IMAGE_EXT = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    public static function label(string $name): string
    {
        $key = "admin.fields.$name";
        $t = __($key);
        return $t === $key ? Str::headline($name) : $t;
    }

    /** Options for select/multiselect as [value => label]. */
    public static function options(array $def, DocRepository $repo): array
    {
        if (isset($def['source'])) {
            $s = $def['source'];
            return $repo->options($s['collection'], $s['label'], $s['where'] ?? []);
        }
        $opts = $def['options'] ?? [];
        return array_is_list($opts) ? array_combine($opts, $opts) : $opts;
    }

    /** Default for a new document. */
    public static function default(array $def)
    {
        $d = $def['default'] ?? null;
        if ($d === 'all_week') {
            return array_map(fn ($day) => ['day' => $day, 'timeslot' => [['from' => '09:00', 'to' => '22:00']]],
                ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
        }
        return $d;
    }

    /** Value to show in the form input. */
    public static function formValue(string $name, array $def, ?array $doc)
    {
        $type = $def['type'];
        $v = $doc === null ? self::default($def) : ($type === 'latlng' ? null : ($doc[$name] ?? null));

        switch ($type) {
            case 'latlng':
                return [
                    'lat' => $doc['latitude'] ?? ($doc['coordinates']['lat'] ?? ''),
                    'lng' => $doc['longitude'] ?? ($doc['coordinates']['lng'] ?? ''),
                ];
            case 'datetime':
                return Codec::isTag($v, 'ts') ? CarbonImmutable::createFromTimestamp($v['s'])->format('Y-m-d\TH:i') : '';
            case 'json':
                return $v === null ? '' : json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            case 'list':
                return is_array($v) ? implode(', ', array_map(fn ($x) => is_scalar($x) ? (string) $x : json_encode($x), $v)) : (string) $v;
            case 'images':
                return is_array($v) ? implode("\n", array_filter($v, 'is_string')) : '';
            case 'polygon':
                return is_array($v) ? implode("\n", array_map(fn ($p) => ($p['lat'] ?? $p['latitude'] ?? '') . ',' . ($p['lng'] ?? $p['longitude'] ?? ''), $v)) : '';
            case 'multiselect':
                return is_array($v) ? array_map('strval', $v) : [];
            case 'bool':
                return (bool) $v;
            case 'password':
            case 'secret':
                return ''; // never echo secrets back into the page
            default:
                return is_scalar($v) ? (string) $v : ($v === null ? '' : json_encode($v));
        }
    }

    /**
     * Read one field from the request. Returns an array of document changes
     * (a latlng field writes several keys).
     */
    public static function fromRequest(string $name, array $def, Request $request, string $collection): array
    {
        $type = $def['type'];
        $raw = $request->input($name);
        $required = !empty($def['required']);
        $fail = fn ($msg) => throw ValidationException::withMessages([$name => $msg]);

        switch ($type) {
            case 'bool':
                return [$name => $request->boolean($name)];

            case 'integer':
                if ($raw === null || $raw === '') {
                    return $required ? $fail(__('admin.required')) : [$name => null];
                }
                if (!is_numeric($raw)) {
                    $fail(__('admin.must_be_number'));
                }
                return [$name => (int) $raw];

            case 'number':
                if ($raw === null || $raw === '') {
                    return $required ? $fail(__('admin.required')) : [$name => null];
                }
                if (!is_numeric($raw)) {
                    $fail(__('admin.must_be_number'));
                }
                return [$name => (float) $raw];

            case 'money': // the views treat prices as numeric strings
                if ($raw === null || $raw === '') {
                    return $required ? $fail(__('admin.required')) : [$name => '0'];
                }
                if (!is_numeric($raw)) {
                    $fail(__('admin.must_be_number'));
                }
                return [$name => rtrim(rtrim(number_format((float) $raw, 2, '.', ''), '0'), '.') ?: '0'];

            case 'datetime':
                if (!$raw) {
                    return $required ? $fail(__('admin.required')) : [$name => null];
                }
                $d = CarbonImmutable::parse($raw, config('app.timezone'));
                return [$name => ['__fs' => 'ts', 's' => $d->getTimestamp(), 'n' => 0]];

            case 'multiselect':
                return [$name => array_values(array_filter((array) $request->input($name, []), fn ($x) => $x !== ''))];

            case 'list':
                $items = array_values(array_filter(array_map('trim', explode(',', (string) $raw)), fn ($x) => $x !== ''));
                return [$name => $items];

            case 'json':
                if ($raw === null || trim($raw) === '') {
                    return [$name => null];
                }
                $decoded = json_decode($raw, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fail(__('admin.invalid_json') . ' ' . json_last_error_msg());
                }
                return [$name => $decoded];

            case 'latlng':
                $lat = $request->input($name . '_lat');
                $lng = $request->input($name . '_lng');
                if ($lat === null || $lat === '' || $lng === null || $lng === '') {
                    return $required ? $fail(__('admin.required')) : [];
                }
                if (!is_numeric($lat) || !is_numeric($lng) || abs($lat) > 90 || abs($lng) > 180) {
                    $fail(__('admin.invalid_coordinates'));
                }
                return ['latitude' => (float) $lat, 'longitude' => (float) $lng] + Geo::fields((float) $lat, (float) $lng);

            case 'polygon':
                $points = [];
                foreach (preg_split('/\r?\n/', trim((string) $raw)) as $line) {
                    if (trim($line) === '') {
                        continue;
                    }
                    $parts = array_map('trim', explode(',', $line));
                    if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
                        $fail(__('admin.invalid_polygon'));
                    }
                    $points[] = Geo::point((float) $parts[0], (float) $parts[1]);
                }
                if ($required && count($points) < 3) {
                    $fail(__('admin.invalid_polygon'));
                }
                return [$name => $points];

            case 'image':
                $file = $request->file($name . '_file');
                if ($file) {
                    return [$name => self::store($file, $collection)];
                }
                if ($required && !$raw) {
                    $fail(__('admin.required'));
                }
                return [$name => (string) $raw];

            case 'images':
                $urls = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $raw))));
                foreach ((array) $request->file($name . '_files', []) as $file) {
                    $urls[] = self::store($file, $collection);
                }
                return [$name => $urls];

            case 'secret':
                if ($request->boolean($name . '_clear')) {
                    return [$name => ''];
                }
                return ($raw === null || $raw === '') ? [] : [$name => trim($raw)]; // blank = keep current

            case 'password':
                if ($raw === null || $raw === '') {
                    return []; // keep the current password
                }
                if (strlen($raw) < 8) {
                    $fail(__('admin.password_min'));
                }
                return [$name => Hash::make($raw)];

            case 'email':
                if ($raw && !filter_var($raw, FILTER_VALIDATE_EMAIL)) {
                    $fail(__('admin.invalid_email'));
                }
                // no break
            default:
                $value = $raw === null ? '' : trim((string) $raw);
                if ($required && $value === '') {
                    $fail(__('admin.required'));
                }
                if (isset($def['options']) || isset($def['source'])) {
                    return [$name => $value];
                }
                return [$name => $type === 'email' ? Str::lower($value) : $value];
        }
    }

    private static function store(UploadedFile $file, string $collection): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: '');
        if (!in_array($ext, self::IMAGE_EXT, true) || $file->getSize() > 8 * 1024 * 1024) {
            throw ValidationException::withMessages(['file' => __('admin.invalid_image')]);
        }
        $dir = 'uploads/admin/' . preg_replace('/[^a-z0-9_]/', '', $collection);
        $name = date('Ymd_His') . '_' . Str::random(8) . '.' . $ext;
        $file->move(public_path($dir), $name);
        return asset("$dir/$name");
    }
}
