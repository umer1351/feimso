<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('seller_id', 191)->collation('utf8mb4_bin')->index();
            $table->string('listing_type', 32)->index()
                ->comment('sale|rental|service|experience');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->string('category', 128)->nullable();
            $table->string('brand', 128)->nullable();

            // pricing
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 8)->default('AED');
            $table->decimal('deposit_amount', 12, 2)->nullable()->comment('rental deposit / hold');

            // condition / authenticity (sale)
            $table->string('condition', 32)->nullable()->comment('new|like_new|good|fair');
            $table->boolean('authenticity_declared')->default(false);
            $table->boolean('prohibited_check')->default(false);

            // rental-specific
            $table->boolean('rental_available')->default(false);
            $table->decimal('rental_price_per_day', 12, 2)->nullable();
            $table->json('rental_blocked_dates')->nullable();
            $table->string('rental_pickup_type', 32)->nullable()->comment('pickup|delivery');
            $table->text('late_return_rules')->nullable();
            $table->text('damage_loss_policy')->nullable();

            // service / experience
            $table->string('booking_type', 32)->nullable()->comment('fixed|hourly');
            $table->datetime('available_from')->nullable();
            $table->datetime('available_to')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('location_type', 32)->nullable()->comment('in_person|remote|both');
            $table->text('location_address')->nullable();
            $table->boolean('liability_acknowledged')->default(false);
            $table->text('scope_of_work')->nullable();
            $table->integer('max_participants')->nullable();
            $table->text('fulfilment_details')->nullable();

            // cancellation
            $table->string('cancellation_policy', 32)->nullable()->comment('flexible|moderate|strict');

            // delivery (sale)
            $table->string('delivery_option', 32)->nullable()->comment('pickup|courier|both');
            $table->decimal('delivery_charge', 8, 2)->nullable();
            $table->text('return_policy')->nullable();

            // status
            $table->string('status', 32)->default('draft')->index()
                ->comment('draft|active|paused|sold|archived|rejected');
            $table->boolean('is_published')->default(false)->index();
            $table->text('admin_notes')->nullable();

            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();

            $table->foreign('seller_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
