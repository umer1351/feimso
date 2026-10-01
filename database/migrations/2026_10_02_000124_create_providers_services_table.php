<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Providers Services (was the Firestore collection 'providers_services').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('providers_services', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('author', 191)->nullable();
            $table->text('authorName')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('price')->nullable();
            $table->text('disPrice')->nullable();
            $table->text('priceUnit')->nullable();
            $table->json('photos')->nullable();
            $table->text('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->json('g')->nullable();
            $table->json('coordinates')->nullable();
            $table->string('categoryId', 191)->nullable();
            $table->text('subCategoryId')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->boolean('publish')->nullable();
            $table->json('days')->nullable();
            $table->text('startTime')->nullable();
            $table->text('endTime')->nullable();
            $table->bigInteger('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('author');
            $table->index('categoryId');
            $table->index('sectionId');
            $table->index('publish');
            $table->index('createdAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers_services');
    }
};
