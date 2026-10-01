<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Rental Vehicle Type (was the Firestore collection 'rental_vehicle_type').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_vehicle_type', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('name')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('rental_vehicle_icon')->nullable();
            $table->boolean('isActive')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('isActive');
            $table->index('sectionId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_vehicle_type');
    }
};
