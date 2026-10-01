<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Provider Categories (was the Firestore collection 'provider_categories').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_categories', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->text('image')->nullable();
            $table->boolean('publish')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->bigInteger('level')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('publish');
            $table->index('sectionId');
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_categories');
    }
};
