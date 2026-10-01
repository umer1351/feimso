<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vendor Categories (was the Firestore collection 'vendor_categories').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_categories', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->text('photo')->nullable();
            $table->text('description')->nullable();
            $table->string('section_id', 191)->nullable();
            $table->boolean('publish')->nullable();
            $table->boolean('show_in_homepage')->nullable();
            $table->json('review_attributes')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('section_id');
            $table->index('publish');
            $table->index('show_in_homepage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_categories');
    }
};
