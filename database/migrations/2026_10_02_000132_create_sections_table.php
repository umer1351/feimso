<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Sections (was the Firestore collection 'sections').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('name')->nullable();
            $table->boolean('isActive')->nullable();
            $table->text('serviceType')->nullable();
            $table->text('sectionImage')->nullable();
            $table->text('color')->nullable();
            $table->boolean('dine_in_active')->nullable();
            $table->text('nearByRadius')->nullable();
            $table->double('delivery_charge')->nullable();
            $table->boolean('is_product_details')->nullable();
            $table->text('cab_service_template')->nullable();
            $table->json('adminCommision')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('isActive');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
