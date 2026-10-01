<?php

namespace App\Http\Middleware;

use App\Helpers\FirestoreHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Shows the maintenance page when settings/maintenance_settings says so. */
class FirebaseMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $settings = FirestoreHelper::getDocument('settings/maintenance_settings');
            if (!empty($settings['isMaintenanceModeForCustomer']) && !$request->is('admin', 'admin/*', 'fs/*')) {
                return response()->view('maintenance');
            }
        } catch (\Throwable $e) {
            // database unavailable - skip the maintenance check
        }
        return $next($request);
    }
}
