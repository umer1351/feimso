<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Booked Table (was the Firestore collection 'booked_table').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booked_table', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('authorID')->nullable();
            $table->json('author')->nullable();
            $table->text('vendorID')->nullable();
            $table->json('vendor')->nullable();
            $table->dateTime('date', 6)->nullable();
            $table->text('totalGuest')->nullable();
            $table->text('guestFirstName')->nullable();
            $table->text('guestLastName')->nullable();
            $table->text('guestEmail')->nullable();
            $table->text('guestPhone')->nullable();
            $table->text('discount')->nullable();
            $table->text('discountType')->nullable();
            $table->text('specialRequest')->nullable();
            $table->boolean('firstVisit')->nullable();
            $table->text('occasion')->nullable();
            $table->text('status')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booked_table');
    }
};
