<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vendors (was the Firestore collection 'vendors').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('photo')->nullable();
            $table->text('location')->nullable();
            $table->text('phonenumber')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->json('coordinates')->nullable();
            $table->json('g')->nullable();
            $table->text('author')->nullable();
            $table->string('section_id', 191)->nullable();
            $table->string('zoneId', 191)->nullable();
            $table->json('categoryID')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->bigInteger('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->boolean('reststatus')->nullable();
            $table->json('workingHours')->nullable();
            $table->boolean('specialDiscountEnable')->nullable();
            $table->json('specialDiscount')->nullable();
            $table->json('deliveryCharge')->nullable();
            $table->boolean('isSelfDelivery')->nullable();
            $table->json('adminCommission')->nullable();
            $table->boolean('enabledDiveInFuture')->nullable();
            $table->text('openDineTime')->nullable();
            $table->text('closeDineTime')->nullable();
            $table->double('restaurantCost')->nullable();
            $table->json('restaurantMenuPhotos')->nullable();
            $table->text('subscriptionPlanId')->nullable();
            $table->json('subscription_plan')->nullable();
            $table->dateTime('subscriptionExpiryDate', 6)->nullable();
            $table->bigInteger('subscriptionTotalOrders')->nullable();
            $table->json('photos')->nullable();
            $table->json('filters')->nullable();
            $table->json('categoryTitle')->nullable();
            $table->text('vendorCuisineID')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('section_id');
            $table->index('zoneId');
            $table->index('createdAt');
            $table->index('reviewsSum');
            $table->index('enabledDiveInFuture');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
