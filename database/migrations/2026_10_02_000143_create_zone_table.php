<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Zone (was the Firestore collection 'zone').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zone', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->boolean('publish')->nullable();
            $table->json('area')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('publish');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone');
    }
};
