<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Dynamic Notification (was the Firestore collection 'dynamic_notification').
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dynamic_notification', function (Blueprint $table) {
            $table->string('id', 191)->collation('utf8mb4_bin')->primary();
            $table->string('type', 191)->nullable();
            $table->text('subject')->nullable();
            $table->text('message')->nullable();
            $table->json('extra')->nullable()->comment('fields without a dedicated column');
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_notification');
    }
};
