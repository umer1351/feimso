<?php

namespace App\Models;

/**
 * Compatibility alias. The old app kept a `vendor_users` table mapping Laravel
 * users to Firebase uids (`uuid`). Accounts now live in `users` with the uid
 * as the primary key, so `uuid` simply reads the id.
 */
class VendorUsers extends User
{
    protected $table = 'users';

    public function getUuidAttribute(): string
    {
        return (string) $this->id;
    }
}
