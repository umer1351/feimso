<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Admin panel gate: signed in with users.role = 'admin'. */
class RequireAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->guest(route('admin.login'));
        }
        abort_unless($user->isAdmin(), 403);
        return $next($request);
    }
}
