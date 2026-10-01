<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Vendor Attributes (was the Firestore collection 'vendor_attributes').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_attributes', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_attributes');
    }
};
