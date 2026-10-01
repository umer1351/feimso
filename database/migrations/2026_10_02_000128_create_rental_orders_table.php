<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Rental Orders (was the Firestore collection 'rental_orders').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_orders', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('adminCommission')->nullable();
            $table->text('adminCommissionType')->nullable();
            $table->json('author')->nullable();
            $table->string('authorID', 191)->nullable();
            $table->dateTime('bookingDateTime', 6)->nullable();
            $table->text('couponId')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->text('discount')->nullable();
            $table->text('discountLabel')->nullable();
            $table->text('discountType')->nullable();
            $table->text('driverId')->nullable();
            $table->json('driver')->nullable();
            $table->text('endKitoMetersReading')->nullable();
            $table->text('startKitoMetersReading')->nullable();
            $table->dateTime('endTime', 6)->nullable();
            $table->dateTime('startTime', 6)->nullable();
            $table->text('otpCode')->nullable();
            $table->text('paymentMethod')->nullable();
            $table->boolean('paymentStatus')->nullable();
            $table->json('rentalPackageModel')->nullable();
            $table->json('rentalVehicleType')->nullable();
            $table->json('rejectedByDrivers')->nullable();
            $table->text('sectionId')->nullable();
            $table->json('sourceLocation')->nullable();
            $table->text('sourceLocationName')->nullable();
            $table->json('sourcePoint')->nullable();
            $table->text('status')->nullable();
            $table->text('subTotal')->nullable();
            $table->json('taxSetting')->nullable();
            $table->text('vehicleId')->nullable();
            $table->text('zoneId')->nullable();
            $table->bigInteger('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('authorID');
            $table->index('createdAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_orders');
    }
};
