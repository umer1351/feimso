<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Advertisements (was the Firestore collection 'advertisements').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('status', 191)->nullable();
            $table->boolean('paymentStatus')->nullable();
            $table->text('vendorId')->nullable();
            $table->dateTime('startDate', 6)->nullable();
            $table->dateTime('endDate', 6)->nullable();
            $table->boolean('isPaused')->nullable();
            $table->text('priority')->nullable();
            $table->text('type')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('coverImage')->nullable();
            $table->text('profileImage')->nullable();
            $table->text('video')->nullable();
            $table->boolean('showRating')->nullable();
            $table->boolean('showReview')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('status');
            $table->index('paymentStatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
