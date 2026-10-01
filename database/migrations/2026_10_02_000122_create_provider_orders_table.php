<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Provider Orders (was the Firestore collection 'provider_orders').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_orders', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->json('address')->nullable();
            $table->text('adminCommission')->nullable();
            $table->text('adminCommissionType')->nullable();
            $table->json('author')->nullable();
            $table->string('authorID', 191)->nullable();
            $table->text('couponCode')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->text('discount')->nullable();
            $table->text('discountLabel')->nullable();
            $table->text('discountType')->nullable();
            $table->text('extraCharges')->nullable();
            $table->text('extraChargesDescription')->nullable();
            $table->boolean('extraPaymentStatus')->nullable();
            $table->dateTime('newScheduleDateTime', 6)->nullable();
            $table->text('notes')->nullable();
            $table->text('otp')->nullable();
            $table->boolean('paymentStatus')->nullable();
            $table->text('payment_method')->nullable();
            $table->json('provider')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->text('reason')->nullable();
            $table->dateTime('scheduleDateTime', 6)->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->text('status')->nullable();
            $table->json('taxSetting')->nullable();
            $table->text('workerId')->nullable();
            $table->dateTime('startTime', 6)->nullable();
            $table->dateTime('endTime', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('authorID');
            $table->index('createdAt');
            $table->index('sectionId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_orders');
    }
};
