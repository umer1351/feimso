<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Local test accounts. Emails/passwords come from .env
 * (ADMIN_EMAIL / ADMIN_PASSWORD, DEMO_CUSTOMER_EMAIL / DEMO_CUSTOMER_PASSWORD).
 */
class AccountsSeeder extends Seeder
{
    public const ADMIN = 'admin00000000000000000000000';
    public const VENDOR_OWNER = 'vendorowner00000000000000000';
    public const CUSTOMER = 'customer00000000000000000000';

    public function run(): void
    {
        $this->account(self::ADMIN, env('ADMIN_EMAIL', 'admin@feimso.local'), env('ADMIN_PASSWORD'), 'admin', 'Feimso', 'Admin');
        $this->account(self::VENDOR_OWNER, 'vendor@feimso.local', null, 'vendor', 'Demo', 'Vendor');
        $this->account(self::CUSTOMER, env('DEMO_CUSTOMER_EMAIL', 'customer@feimso.local'), env('DEMO_CUSTOMER_PASSWORD'), 'customer', 'Demo', 'Customer');
    }

    private function account(string $id, string $email, ?string $password, string $role, string $first, string $last): void
    {
        $user = User::find($id) ?? new User(['id' => $id]);
        $user->forceFill([
            'id' => $id,
            'email' => $email,
            'password' => $password ? Hash::make($password) : null,
            'role' => $role,
            'active' => true,
            'firstName' => $first,
            'lastName' => $last,
            'phoneNumber' => '',
            'countryCode' => '+971',
            'wallet_amount' => 0,
            'shippingAddress' => [],
            'createdAt' => now()->utc(),
        ])->save();
    }
}
