<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Currencies (was the Firestore collection 'currencies').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->boolean('isActive')->nullable();
            $table->text('symbol')->nullable();
            $table->text('code')->nullable();
            $table->bigInteger('decimal_degits')->nullable();
            $table->boolean('symbolAtRight')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('isActive');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
