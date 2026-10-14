<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('feimso_role', 64)->nullable()->index()->after('role')
                ->comment('buyer|individual_seller|licensed_creator|business|advertising_creator');
            $table->string('verification_status', 32)->nullable()->default('unverified')->index()->after('feimso_role')
                ->comment('unverified|pending|verified|expired|suspended|rejected');
            $table->text('verification_notes')->nullable()->after('verification_status');
            $table->dateTime('verification_expires_at', 6)->nullable()->after('verification_notes');
            $table->string('referral_code', 32)->nullable()->unique()->after('verification_expires_at');
            $table->string('referred_by', 191)->nullable()->after('referral_code');
            $table->string('invite_code', 32)->nullable()->after('referred_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['feimso_role', 'verification_status', 'verification_notes',
                'verification_expires_at', 'referral_code', 'referred_by', 'invite_code']);
        });
    }
};
