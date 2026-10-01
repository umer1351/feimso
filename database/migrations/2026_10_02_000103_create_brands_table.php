<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Brands (was the Firestore collection 'brands').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->text('photo')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->boolean('is_publish')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('sectionId');
            $table->index('is_publish');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
