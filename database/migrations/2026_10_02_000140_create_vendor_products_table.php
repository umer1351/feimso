<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vendor Products (was the Firestore collection 'vendor_products').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->double('price')->nullable();
            $table->double('disPrice')->nullable();
            $table->text('photo')->nullable();
            $table->json('photos')->nullable();
            $table->string('vendorID', 191)->nullable();
            $table->string('categoryID', 191)->nullable();
            $table->string('brandID', 191)->nullable();
            $table->string('section_id', 191)->nullable();
            $table->boolean('publish')->nullable();
            $table->dateTime('createdAt', 6)->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->boolean('veg')->nullable();
            $table->boolean('nonveg')->nullable();
            $table->boolean('takeawayOption')->nullable();
            $table->bigInteger('calories')->nullable();
            $table->bigInteger('grams')->nullable();
            $table->bigInteger('proteins')->nullable();
            $table->bigInteger('fats')->nullable();
            $table->json('addOnsTitle')->nullable();
            $table->json('addOnsPrice')->nullable();
            $table->json('sizePrice')->nullable();
            $table->json('size')->nullable();
            $table->json('item_attribute')->nullable();
            $table->json('product_specification')->nullable();
            $table->bigInteger('reviewsCount')->nullable();
            $table->double('reviewsSum')->nullable();
            $table->json('reviewAttributes')->nullable();
            $table->boolean('isDigitalProduct')->nullable();
            $table->text('digitalProduct')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('vendorID');
            $table->index('categoryID');
            $table->index('brandID');
            $table->index('section_id');
            $table->index('publish');
            $table->index('createdAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_products');
    }
};
