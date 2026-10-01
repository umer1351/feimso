<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Gift Purchases (was the Firestore collection 'gift_purchases').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_purchases', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('giftId')->nullable();
            $table->text('giftTitle')->nullable();
            $table->double('price')->nullable();
            $table->text('message')->nullable();
            $table->boolean('redeem')->nullable();
            $table->string('userid', 191)->nullable();
            $table->string('giftCode', 191)->nullable();
            $table->text('giftPin')->nullable();
            $table->dateTime('createdDate', 6)->nullable();
            $table->dateTime('expireDate', 6)->nullable();
            $table->text('paymentType')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('userid');
            $table->index('giftCode');
            $table->index('createdDate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_purchases');
    }
};
