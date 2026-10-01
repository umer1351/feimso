<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Favorite Service (was the Firestore collection 'favorite_service').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_service', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('user_id', 191)->nullable();
            $table->string('service_id', 191)->nullable();
            $table->text('service_author_id')->nullable();
            $table->text('section_id')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('user_id');
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_service');
    }
};
