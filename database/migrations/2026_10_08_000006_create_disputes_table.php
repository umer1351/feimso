<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('booking_id', 191)->nullable()->index();
            $table->string('complainant_id', 191)->index();
            $table->string('respondent_id', 191)->nullable()->index();
            $table->string('dispute_type', 64)->nullable()
                ->comment('item_not_received|item_not_as_described|damage|payment|other');
            $table->text('description');
            $table->json('evidence_urls')->nullable();
            $table->string('status', 32)->default('open')->index()
                ->comment('open|under_review|resolved|closed|escalated');
            $table->string('resolution', 64)->nullable()
                ->comment('refund_buyer|release_seller|partial_refund|no_action');
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('handled_by', 191)->nullable();
            $table->dateTime('resolved_at', 6)->nullable();
            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
