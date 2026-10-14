<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'firstName'    => 'required|string|max:100',
            'lastName'     => 'nullable|string|max:100',
            'phoneNumber'  => 'nullable|string|max:30',
            'feimso_role'  => 'nullable|in:buyer,individual_seller,licensed_creator,business,advertising_creator',
        ]);

        $id = User::newUid();
        $now = now();

        User::create([
            'id'                  => $id,
            'email'               => $data['email'],
            'password'            => Hash::make($data['password']),
            'firstName'           => $data['firstName'],
            'lastName'            => $data['lastName'] ?? null,
            'phoneNumber'         => $data['phoneNumber'] ?? null,
            'role'                => 'customer',
            'feimso_role'         => $data['feimso_role'] ?? 'buyer',
            'verification_status' => 'unverified',
            'active'              => true,
            'referral_code'       => strtoupper(Str::random(8)),
            'createdAt'           => $now,
            '_created'            => $now,
            '_updated'            => $now,
        ]);

        $user = User::where('email', $data['email'])->first();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $this->userPayload($user)], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        if (! $user->active) {
            return response()->json(['message' => 'Account suspended.'], 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $this->userPayload($user)]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $this->userPayload($request->user())]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'firstName'   => 'nullable|string|max:100',
            'lastName'    => 'nullable|string|max:100',
            'phoneNumber' => 'nullable|string|max:30',
            'feimso_role' => 'nullable|in:buyer,individual_seller,licensed_creator,business,advertising_creator',
        ]);

        $user->update(array_filter($data, fn($v) => $v !== null));

        return response()->json(['user' => $this->userPayload($user->fresh())]);
    }

    private function userPayload(User $user): array
    {
        return [
            'id'                  => $user->id,
            'email'               => $user->email,
            'firstName'           => $user->firstName,
            'lastName'            => $user->lastName,
            'phoneNumber'         => $user->phoneNumber,
            'feimso_role'         => $user->feimso_role,
            'verification_status' => $user->verification_status,
            'profilePictureURL'   => $user->profilePictureURL,
            'referral_code'       => $user->referral_code,
            'wallet_amount'       => $user->wallet_amount,
        ];
    }
}
