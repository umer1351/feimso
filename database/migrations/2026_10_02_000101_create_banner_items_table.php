<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Banner Items (was the Firestore collection 'banner_items').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_items', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('sectionId', 191)->nullable();
            $table->boolean('is_publish')->nullable();
            $table->bigInteger('set_order')->nullable();
            $table->text('position')->nullable();
            $table->text('web_banner')->nullable();
            $table->text('redirect_type')->nullable();
            $table->text('redirect_id')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('sectionId');
            $table->index('is_publish');
            $table->index('set_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_items');
    }
};
