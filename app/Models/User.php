<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * One row per account: the old Firestore `users` profile document and the
 * login credentials live together. `id` is the string uid the views use
 * everywhere (formerly the Firebase Auth uid).
 *
 * role: customer | vendor | provider | driver | admin
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = '_created';
    const UPDATED_AT = '_updated';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'createdAt' => 'datetime',
        'active' => 'boolean',
        'wallet_amount' => 'float',
        'coordinates' => 'array',
        'g' => 'array',
        'shippingAddress' => 'array',
        'subscription_plan' => 'array',
        'adminCommission' => 'array',
        'extra' => 'array',
    ];

    /** A Firebase-style uid: 28 url-safe characters. */
    public static function newUid(): string
    {
        do {
            $uid = Str::random(28);
        } while (static::whereKey($uid)->exists());
        return $uid;
    }

    /** Create a login account; the profile fields are filled in by the signup page. */
    public static function createAccount(array $attributes): self
    {
        $user = new static();
        $user->forceFill($attributes + [
            'id' => static::newUid(),
            'role' => 'customer',
            'active' => true,
            'wallet_amount' => 0,
        ]);
        $user->save();
        return $user;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Kept for the existing views/controllers: the "vendor id" was the Firebase uid. */
    public function getvendorId(): string
    {
        return (string) $this->id;
    }
}
