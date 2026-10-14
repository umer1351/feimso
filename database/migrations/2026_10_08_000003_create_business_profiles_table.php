<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('user_id', 191)->collation('utf8mb4_bin')->unique()->index();
            $table->string('business_name', 255)->nullable();
            $table->string('legal_name', 255)->nullable();
            $table->string('trade_licence_number', 191)->nullable();
            $table->string('trade_licence_authority', 255)->nullable();
            $table->date('trade_licence_expiry')->nullable();
            $table->string('vat_trn', 64)->nullable();
            $table->string('representative_name', 255)->nullable();
            $table->string('representative_email', 191)->nullable();
            $table->string('representative_phone', 64)->nullable();
            $table->json('licence_document_urls')->nullable();
            $table->text('description')->nullable();
            $table->text('logo_url')->nullable();
            $table->text('cover_url')->nullable();
            $table->json('social_links')->nullable();
            $table->string('verification_status', 32)->default('pending')->index();
            $table->text('admin_notes')->nullable();
            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
