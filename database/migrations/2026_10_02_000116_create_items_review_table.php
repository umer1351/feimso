<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Items Review (was the Firestore collection 'items_review').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items_review', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('CustomerId', 191)->nullable();
            $table->text('VendorId')->nullable();
            $table->string('productId', 191)->nullable();
            $table->string('driverId', 191)->nullable();
            $table->string('orderid', 191)->nullable();
            $table->double('rating')->nullable();
            $table->text('comment')->nullable();
            $table->json('photos')->nullable();
            $table->text('uname')->nullable();
            $table->text('profile')->nullable();
            $table->json('reviewAttributes')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('CustomerId');
            $table->index('productId');
            $table->index('driverId');
            $table->index('orderid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items_review');
    }
};
