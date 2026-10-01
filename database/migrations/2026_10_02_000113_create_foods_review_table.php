<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Foods Review (was the Firestore collection 'foods_review').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foods_review', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('CustomerId')->nullable();
            $table->string('VendorId', 191)->nullable();
            $table->string('orderid', 191)->nullable();
            $table->double('rating')->nullable();
            $table->text('comment')->nullable();
            $table->json('photos')->nullable();
            $table->text('uname')->nullable();
            $table->text('profile')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('VendorId');
            $table->index('orderid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods_review');
    }
};
