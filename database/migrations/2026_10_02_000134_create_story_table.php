<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Story (was the Firestore collection 'story').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('vendorID', 191)->nullable();
            $table->json('videoUrl')->nullable();
            $table->text('videoThumbnail')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('vendorID');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story');
    }
};
