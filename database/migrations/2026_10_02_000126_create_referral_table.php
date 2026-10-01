<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Referral (was the Firestore collection 'referral').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('referralCode', 191)->nullable();
            $table->text('referralBy')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('referralCode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral');
    }
};
