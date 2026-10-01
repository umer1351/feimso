<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tax (was the Firestore collection 'tax').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->text('title')->nullable();
            $table->string('country', 191)->nullable();
            $table->boolean('enable')->nullable();
            $table->string('sectionId', 191)->nullable();
            $table->double('tax')->nullable();
            $table->text('type')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('country');
            $table->index('enable');
            $table->index('sectionId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax');
    }
};
