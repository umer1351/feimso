<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Parcel Coupons (was the Firestore collection 'parcel_coupons').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcel_coupons', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('code', 191)->nullable();
            $table->text('discount')->nullable();
            $table->text('discountType')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->dateTime('expiresAt', 6)->nullable();
            $table->boolean('isEnabled')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->boolean('isPublic')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('code');
            $table->index('expiresAt');
            $table->index('isEnabled');
            $table->index('sectionId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcel_coupons');
    }
};
