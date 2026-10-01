<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Parcel Weight (was the Firestore collection 'parcel_weight').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcel_weight', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->text('delivery_charge')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcel_weight');
    }
};
