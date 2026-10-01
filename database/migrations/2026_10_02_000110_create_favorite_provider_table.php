<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Favorite Provider (was the Firestore collection 'favorite_provider').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_provider', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('user_id', 191)->nullable();
            $table->string('provider_id', 191)->nullable();
            $table->text('section_id')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('user_id');
            $table->index('provider_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_provider');
    }
};
