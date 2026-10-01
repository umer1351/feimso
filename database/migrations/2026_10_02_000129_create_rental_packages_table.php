<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Rental Packages (was the Firestore collection 'rental_packages').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_packages', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->double('baseFare')->nullable();
            $table->double('includedHours')->nullable();
            $table->double('includedDistance')->nullable();
            $table->double('extraKmFare')->nullable();
            $table->double('extraMinuteFare')->nullable();
            $table->string('vehicleTypeId', 191)->nullable();
            $table->boolean('published')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('vehicleTypeId');
            $table->index('published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_packages');
    }
};
