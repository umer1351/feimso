<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_verifications', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('user_id', 191)->collation('utf8mb4_bin')->index();
            // role snapshot at submission time
            $table->string('role', 64)->nullable();
            // document fields
            $table->string('credential_type', 128)->nullable()
                ->comment('e.g. trade_licence|professional_licence|id_document|advertiser_permit');
            $table->string('licence_number', 191)->nullable();
            $table->string('issuing_authority', 255)->nullable();
            $table->string('activity', 255)->nullable();
            $table->date('licence_expiry')->nullable();
            $table->json('document_urls')->nullable()->comment('uploaded licence / ID scans');
            // UAE-specific
            $table->string('vat_trn', 64)->nullable();
            $table->string('advertiser_permit_number', 191)->nullable();
            // admin review
            $table->string('status', 32)->default('pending')->index()
                ->comment('pending|verified|rejected|expired|suspended');
            $table->text('admin_notes')->nullable();
            $table->string('reviewed_by', 191)->nullable();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_verifications');
    }
};
