<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('listing_id', 191)->index();
            $table->string('buyer_id', 191)->collation('utf8mb4_bin')->index();
            $table->string('seller_id', 191)->collation('utf8mb4_bin')->index();
            $table->string('booking_type', 32)->index()
                ->comment('sale|rental|service|experience');

            // dates
            $table->dateTime('start_date', 6)->nullable();
            $table->dateTime('end_date', 6)->nullable();

            // amounts
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('deposit', 12, 2)->nullable();
            $table->decimal('service_fee', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('currency', 8)->default('AED');

            // payment
            $table->string('payment_method', 64)->nullable();
            $table->string('payment_status', 32)->default('pending')
                ->comment('pending|paid|failed|refunded|chargeback');
            $table->string('payment_reference', 191)->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();

            // state
            $table->string('status', 32)->default('pending')->index()
                ->comment('pending|confirmed|in_progress|completed|cancelled|disputed');

            // delivery / logistics
            $table->text('delivery_address')->nullable();
            $table->string('fulfilment_status', 32)->nullable()
                ->comment('awaiting|ready|picked_up|in_transit|delivered|returned');

            // rental condition report
            $table->text('condition_report_pre')->nullable();
            $table->text('condition_report_post')->nullable();

            // notes
            $table->text('buyer_notes')->nullable();
            $table->text('seller_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();

            $table->foreign('listing_id')->references('id')->on('listings')->cascadeOnDelete();
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('seller_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
