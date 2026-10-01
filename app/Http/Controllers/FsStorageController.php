<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Local-disk replacement for Firebase Storage, called by public/js/firebase-local.js.
 * Files live under public/uploads/<path> (served directly by Apache).
 */
class FsStorageController extends Controller
{
    private const ALLOWED = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf'];
    private const MAX_KB = 10240;

    public function upload(Request $request)
    {
        $this->requireUser();
        $request->validate(['file' => 'required|file|max:' . self::MAX_KB]);

        $file = $request->file('file');
        [$dir, $name] = $this->split((string) $request->input('path'), $file->getClientOriginalName());
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION) ?: $file->guessExtension() ?: '');
        if (!in_array($ext, self::ALLOWED, true)) {
            return response()->json(['code' => 'storage/invalid-format', 'message' => 'File type not allowed'], 422);
        }
        $name = pathinfo($name, PATHINFO_FILENAME) . '.' . $ext;

        $file->move(public_path('uploads/' . $dir), $name);
        return response()->json($this->describe($dir, $name));
    }

    public function uploadString(Request $request)
    {
        $this->requireUser();
        $data = (string) $request->input('data');
        $format = (string) $request->input('format', 'raw');
        [$dir, $name] = $this->split((string) $request->input('path'), 'file');

        if ($format === 'data_url' && preg_match('#^data:([\w/+.-]+);base64,(.*)$#s', $data, $m)) {
            $bytes = base64_decode($m[2], true);
            $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'][$m[1]] ?? null;
        } elseif ($format === 'base64' || $format === 'base64url') {
            $bytes = base64_decode(strtr($data, '-_', '+/'), true);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION)) ?: null;
        } else {
            return response()->json(['code' => 'storage/invalid-format', 'message' => 'Unsupported format'], 422);
        }
        if ($bytes === false || !$ext || !in_array($ext, self::ALLOWED, true) || strlen($bytes) > self::MAX_KB * 1024) {
            return response()->json(['code' => 'storage/invalid-format', 'message' => 'Invalid file'], 422);
        }
        $name = pathinfo($name, PATHINFO_FILENAME) . '.' . $ext;
        @mkdir(public_path('uploads/' . $dir), 0775, true);
        file_put_contents(public_path('uploads/' . $dir . '/' . $name), $bytes);
        return response()->json($this->describe($dir, $name));
    }

    public function url(Request $request)
    {
        [$dir, $name] = $this->split((string) $request->input('path'), '');
        $rel = trim($dir . '/' . $name, '/');
        if ($rel === '' || !is_file(public_path('uploads/' . $rel))) {
            return response()->json(['code' => 'storage/object-not-found', 'message' => 'Object does not exist'], 404);
        }
        return response()->json(['url' => asset('uploads/' . $rel)]);
    }

    public function delete(Request $request)
    {
        $this->requireUser();
        if ($request->filled('url')) {
            $path = parse_url((string) $request->input('url'), PHP_URL_PATH) ?: '';
            $pos = strpos($path, '/uploads/');
            if ($pos === false) {
                return response()->json(['ok' => true]); // not one of ours (e.g. an old Firebase URL)
            }
            $rel = substr($path, $pos + strlen('/uploads/'));
        } else {
            $rel = (string) $request->input('path');
        }
        [$dir, $name] = $this->split(rawurldecode($rel), '');
        $full = public_path('uploads/' . trim($dir . '/' . $name, '/'));
        if ($name !== '' && is_file($full)) {
            @unlink($full);
        }
        return response()->json(['ok' => true]);
    }

    /** Sanitise "images/foo bar.png" into ['images', 'foo-bar.png'] with no traversal. */
    private function split(string $path, string $fallbackName): array
    {
        $parts = array_values(array_filter(explode('/', str_replace('\\', '/', $path)), fn ($p) => $p !== '' && $p !== '.' && $p !== '..'));
        $name = $parts ? array_pop($parts) : $fallbackName;
        $clean = fn ($s) => trim(preg_replace('/[^A-Za-z0-9._-]+/', '-', $s), '-.');
        $dir = implode('/', array_filter(array_map($clean, $parts)));
        $base = $clean(pathinfo($name, PATHINFO_FILENAME)) ?: Str::random(12);
        $ext = $clean(pathinfo($name, PATHINFO_EXTENSION));
        return [$dir === '' ? 'misc' : $dir, $ext !== '' ? "$base.$ext" : $base];
    }

    private function describe(string $dir, string $name): array
    {
        return ['url' => asset("uploads/$dir/$name"), 'path' => "$dir/$name"];
    }

    private function requireUser(): void
    {
        abort_unless(Auth::check(), 401, 'Sign in required');
    }
}
