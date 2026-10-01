<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Wallet (was the Firestore collection 'wallet').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('user_id', 191)->nullable();
            $table->double('amount')->nullable();
            $table->dateTime('date', 6)->nullable();
            $table->boolean('isTopUp')->nullable();
            $table->text('order_id')->nullable();
            $table->text('payment_method')->nullable();
            $table->text('payment_status')->nullable();
            $table->text('serviceType')->nullable();
            $table->text('transactionUser')->nullable();
            $table->text('note')->nullable();
            $table->text('transactionId')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('user_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet');
    }
};
