<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Coupons (was the Firestore collection 'coupons').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('code', 191)->nullable();
            $table->double('discount')->nullable();
            $table->text('discountType')->nullable();
            $table->dateTime('expiresAt', 6)->nullable();
            $table->boolean('isEnabled')->nullable();
            $table->boolean('isPublic')->nullable();
            $table->string('section_id', 191)->nullable();
            $table->string('vendorID', 191)->nullable();
            $table->string('vendor_id', 191)->nullable();
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('code');
            $table->index('expiresAt');
            $table->index('isEnabled');
            $table->index('isPublic');
            $table->index('section_id');
            $table->index('vendorID');
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
