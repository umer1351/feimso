<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Users (was the Firestore collection 'users').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('email', 191)->nullable();
            $table->text('firstName')->nullable();
            $table->text('lastName')->nullable();
            $table->string('phoneNumber', 191)->nullable();
            $table->text('countryCode')->nullable();
            $table->string('role', 191)->nullable();
            $table->boolean('active')->nullable();
            $table->text('profilePictureURL')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('coordinates')->nullable();
            $table->json('g')->nullable();
            $table->double('wallet_amount')->nullable();
            $table->json('shippingAddress')->nullable();
            $table->text('fcmToken')->nullable();
            $table->string('vendorID', 191)->nullable();
            $table->double('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->bigInteger('subscriptionTotalOrders')->nullable();
            $table->text('subscriptionPlanId')->nullable();
            $table->dateTime('subscriptionExpiryDate', 6)->nullable();
            $table->json('subscription_plan')->nullable();
            $table->json('adminCommission')->nullable();
            $table->text('carNumber')->nullable();
            $table->text('carName')->nullable();
            $table->string('password', 191)->nullable();
            $table->string('remember_token', 191)->nullable();
            $table->dateTime('email_verified_at', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->unique('email');
            $table->index('phoneNumber');
            $table->index('role');
            $table->index('active');
            $table->index('vendorID');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
