<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Parcel Orders (was the Firestore collection 'parcel_orders').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcel_orders', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('adminCommission')->nullable();
            $table->text('adminCommissionType')->nullable();
            $table->json('author')->nullable();
            $table->string('authorID', 191)->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->text('discount')->nullable();
            $table->text('discountType')->nullable();
            $table->text('discountLabel')->nullable();
            $table->text('couponId')->nullable();
            $table->text('distance')->nullable();
            $table->boolean('isSchedule')->nullable();
            $table->text('note')->nullable();
            $table->text('receiverNote')->nullable();
            $table->text('parcelWeight')->nullable();
            $table->text('parcelWeightCharge')->nullable();
            $table->boolean('paymentCollectByReceiver')->nullable();
            $table->text('payment_method')->nullable();
            $table->json('receiver')->nullable();
            $table->json('receiverLatLong')->nullable();
            $table->dateTime('receiverPickupDateTime', 6)->nullable();
            $table->json('sender')->nullable();
            $table->json('senderLatLong')->nullable();
            $table->dateTime('senderPickupDateTime', 6)->nullable();
            $table->text('status')->nullable();
            $table->text('subTotal')->nullable();
            $table->text('sectionId')->nullable();
            $table->text('parcelCategoryID')->nullable();
            $table->text('parcelType')->nullable();
            $table->json('parcelImages')->nullable();
            $table->json('taxSetting')->nullable();
            $table->text('driverId')->nullable();
            $table->json('driver')->nullable();
            $table->json('rejectedByDrivers')->nullable();
            $table->json('sourcePoint')->nullable();
            $table->json('destinationPoint')->nullable();
            $table->text('senderZoneId')->nullable();
            $table->text('receiverZoneId')->nullable();
            $table->bigInteger('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->text('vendorID')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('authorID');
            $table->index('createdAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcel_orders');
    }
};
