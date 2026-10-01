<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Old Firebase -> Laravel session bridge.
 *
 * The views used to sign in with Firebase Auth and then POST here so Laravel
 * would create a matching session. Signing in now happens directly against
 * Laravel (FsAuthController via public/js/firebase-local.js), so these just
 * report the session state the views expect.
 */
class AjaxController extends Controller
{
    public function checkEmail(Request $request)
    {
        return response()->json(['exist' => User::where('email', $request->email)->exists() ? 'yes' : 'no']);
    }

    public function setToken(Request $request)
    {
        return ['access' => Auth::check()];
    }

    public function newRegister(Request $request)
    {
        return ['access' => Auth::check()];
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return ['logoutuser' => true];
    }
}
