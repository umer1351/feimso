<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Gift Cards (was the Firestore collection 'gift_cards').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->boolean('isEnable')->nullable();
            $table->text('title')->nullable();
            $table->text('image')->nullable();
            $table->text('message')->nullable();
            $table->bigInteger('expiryDay')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('isEnable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
