<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function show()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $email = Str::lower(trim($request->input('email')));
        $key = 'admin-login:' . $email . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => __('admin.too_many_attempts', ['seconds' => RateLimiter::availableIn($key)])])->onlyInput('email');
        }

        $user = User::where('email', $email)->first();
        if (!$user || !$user->isAdmin() || !$user->password || !Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($key, 600);
            return back()->withErrors(['email' => __('admin.bad_credentials')])->onlyInput('email');
        }

        RateLimiter::clear($key);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /** EN/AR switch; the site and the admin share the session locale. */
    public function locale(Request $request, string $locale)
    {
        abort_unless(in_array($locale, ['en', 'ar'], true), 404);
        $request->session()->put('locale', $locale);
        return back();
    }
}
