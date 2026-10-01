<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// The old code looked accounts up in `vendor_users.uuid` (the Firebase uid).
// The uid is now users.id; this read-only generated column keeps
// VendorUsers::select('uuid') / where('uuid', ...) in the views working.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `users` ADD COLUMN `uuid` VARCHAR(191) COLLATE utf8mb4_bin GENERATED ALWAYS AS (`id`) VIRTUAL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `users` DROP COLUMN `uuid`');
    }
};
