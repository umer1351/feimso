<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Laravel-session replacement for Firebase Auth, called by public/js/firebase-local.js.
 * Error codes match Firebase's ("auth/wrong-password", ...) because the views
 * switch on them.
 */
class FsAuthController extends Controller
{
    public function me()
    {
        return response()->json(['user' => Auth::check() ? $this->present(Auth::user()) : null]);
    }

    public function login(Request $request)
    {
        $email = Str::lower(trim((string) $request->input('email')));
        $key = 'fs-login:' . $email . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return $this->error('auth/too-many-requests', 'Too many attempts. Try again later.', 429);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            RateLimiter::hit($key, 300);
            return $this->error('auth/user-not-found', 'There is no user record corresponding to this identifier.');
        }
        if (!$user->password || !Hash::check((string) $request->input('password'), $user->password)) {
            RateLimiter::hit($key, 300);
            return $this->error('auth/wrong-password', 'The password is invalid or the user does not have a password.');
        }
        if ($user->active === false || $user->active === 0) {
            return $this->error('auth/user-disabled', 'The user account has been disabled by an administrator.');
        }

        RateLimiter::clear($key);
        Auth::login($user, true);
        $request->session()->regenerate();
        return response()->json(['user' => $this->present($user), 'csrf' => csrf_token()]);
    }

    public function register(Request $request)
    {
        $email = Str::lower(trim((string) $request->input('email')));
        $password = (string) $request->input('password');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('auth/invalid-email', 'The email address is badly formatted.');
        }
        if (strlen($password) < 6) {
            return $this->error('auth/weak-password', 'Password should be at least 6 characters.');
        }
        if (User::where('email', $email)->exists()) {
            return $this->error('auth/email-already-in-use', 'The email address is already in use by another account.');
        }

        $user = User::createAccount(['email' => $email, 'password' => Hash::make($password)]);
        Auth::login($user, true);
        $request->session()->regenerate();
        return response()->json(['user' => $this->present($user), 'isNewUser' => true, 'csrf' => csrf_token()]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['ok' => true, 'csrf' => csrf_token()]);
    }

    public function passwordReset(Request $request)
    {
        $email = Str::lower(trim((string) $request->input('email')));
        if (!User::where('email', $email)->exists()) {
            return $this->error('auth/user-not-found', 'There is no user record corresponding to this identifier.');
        }
        $status = Password::sendResetLink(['email' => $email]);
        if ($status === Password::RESET_THROTTLED) {
            return $this->error('auth/too-many-requests', __($status), 429);
        }
        return response()->json(['ok' => $status === Password::RESET_LINK_SENT]);
    }

    /**
     * Phone OTP. No SMS gateway is configured yet, so the code is written to
     * the log (storage/logs/laravel.log). Plug a provider into sendSms().
     */
    public function phoneSend(Request $request)
    {
        $phone = preg_replace('/[^\d+]/', '', (string) $request->input('phone'));
        if (strlen($phone) < 8) {
            return $this->error('auth/invalid-phone-number', 'The format of the phone number provided is incorrect.');
        }
        $key = 'fs-otp:' . $phone;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return $this->error('auth/too-many-requests', 'Too many attempts. Try again later.', 429);
        }
        RateLimiter::hit($key, 900);

        $verificationId = Str::random(40);
        $code = (string) random_int(100000, 999999);
        Cache::put('fs-otp:' . $verificationId, ['phone' => $phone, 'code' => Hash::make($code), 'tries' => 0], now()->addMinutes(10));
        $this->sendSms($phone, $code);

        return response()->json(['verificationId' => $verificationId]);
    }

    public function phoneVerify(Request $request)
    {
        $cacheKey = 'fs-otp:' . $request->input('verificationId');
        $otp = Cache::get($cacheKey);
        if (!$otp) {
            return $this->error('auth/code-expired', 'The SMS code has expired. Please re-send the verification code to try again.');
        }
        if (++$otp['tries'] > 5) {
            Cache::forget($cacheKey);
            return $this->error('auth/too-many-requests', 'Too many attempts. Try again later.', 429);
        }
        if (!Hash::check((string) $request->input('code'), $otp['code'])) {
            Cache::put($cacheKey, $otp, now()->addMinutes(10));
            return $this->error('auth/invalid-verification-code', 'The SMS verification code is invalid.');
        }
        Cache::forget($cacheKey);

        $isNew = false;
        $user = User::where('phoneNumber', $otp['phone'])->first();
        if (!$user) {
            $user = User::createAccount(['phoneNumber' => $otp['phone']]);
            $isNew = true;
        }
        Auth::login($user, true);
        $request->session()->regenerate();
        return response()->json(['user' => $this->present($user), 'isNewUser' => $isNew, 'csrf' => csrf_token()]);
    }

    private function sendSms(string $phone, string $code): void
    {
        Log::info("[OTP] Verification code for $phone: $code");
    }

    private function present(User $user): array
    {
        $name = trim(($user->firstName ?? '') . ' ' . ($user->lastName ?? ''));
        return [
            'uid' => (string) $user->id,
            'email' => $user->email,
            'phoneNumber' => $user->phoneNumber,
            'displayName' => $name !== '' ? $name : null,
            'photoURL' => $user->profilePictureURL ?? null,
            'emailVerified' => true,
        ];
    }

    private function error(string $code, string $message, int $status = 400)
    {
        return response()->json(['code' => $code, 'message' => $message], $status);
    }
}
