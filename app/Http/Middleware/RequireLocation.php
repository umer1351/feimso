<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Sends visitors to /set-location until they have picked an address/section.
 *
 * Replaces the `Redirect::to('set-location')->send()` calls that used to sit
 * in 28 controller constructors (send() exits PHP, which also broke
 * `artisan route:list` and tests).
 *
 * location          -> needs the section_id OR address_name cookie
 * location:address  -> needs the address_name cookie
 */
class RequireLocation
{
    public function handle(Request $request, Closure $next, string $mode = 'any')
    {
        $hasAddress = $request->cookies->has('address_name') || isset($_COOKIE['address_name']);
        $hasSection = $request->cookies->has('section_id') || isset($_COOKIE['section_id']);
        $ok = $mode === 'address' ? $hasAddress : ($hasAddress || $hasSection);

        return $ok ? $next($request) : redirect('set-location');
    }
}
