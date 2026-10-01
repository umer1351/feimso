<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Providers Workers (was the Firestore collection 'providers_workers').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('providers_workers', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('firstName')->nullable();
            $table->text('lastName')->nullable();
            $table->text('email')->nullable();
            $table->text('phoneNumber')->nullable();
            $table->text('address')->nullable();
            $table->text('profilePictureURL')->nullable();
            $table->bigInteger('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers_workers');
    }
};
