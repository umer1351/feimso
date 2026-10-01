<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Parcel Categories (was the Firestore collection 'parcel_categories').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcel_categories', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->text('image')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->boolean('publish')->nullable();
            $table->bigInteger('set_order')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('sectionId');
            $table->index('publish');
            $table->index('set_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcel_categories');
    }
};
