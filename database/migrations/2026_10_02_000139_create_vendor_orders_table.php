<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vendor Orders (was the Firestore collection 'vendor_orders').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('authorID')->nullable();
            $table->json('author')->nullable();
            $table->text('vendorID')->nullable();
            $table->json('vendor')->nullable();
            $table->json('products')->nullable();
            $table->json('address')->nullable();
            $table->text('status')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->string('section_id', 191)->nullable();
            $table->text('couponCode')->nullable();
            $table->text('couponId')->nullable();
            $table->double('discount')->nullable();
            $table->json('specialDiscount')->nullable();
            $table->text('deliveryCharge')->nullable();
            $table->text('tip_amount')->nullable();
            $table->text('adminCommission')->nullable();
            $table->text('adminCommissionType')->nullable();
            $table->json('taxSetting')->nullable();
            $table->text('tax_label')->nullable();
            $table->text('tax')->nullable();
            $table->text('payment_method')->nullable();
            $table->boolean('takeAway')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('scheduleTime', 6)->nullable();
            $table->json('driver')->nullable();
            $table->text('driverID')->nullable();
            $table->text('estimatedTimeToPrepare')->nullable();
            $table->json('rejectedByDrivers')->nullable();
            $table->dateTime('triggerDelevery', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('createdAt');
            $table->index('section_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_orders');
    }
};
