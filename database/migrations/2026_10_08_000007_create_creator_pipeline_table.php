<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_pipeline', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('user_id', 191)->nullable()->index();
            $table->string('email', 191)->index();
            $table->string('name', 255)->nullable();
            $table->string('phone', 64)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('creator_type', 64)->nullable()
                ->comment('stylist|designer|photographer|model|wardrobe_curator|boutique|vintage|other');
            $table->string('cohort', 64)->nullable()
                ->comment('founding|alpha|beta|general');
            $table->string('pipeline_stage', 64)->default('prospecting')->index()
                ->comment('prospecting|contacted|applied|approved|activated|transacting|repeat');
            $table->integer('listing_count')->default(0);
            $table->integer('transaction_count')->default(0);
            $table->text('notes')->nullable();
            $table->string('assigned_to', 191)->nullable();
            $table->dateTime('last_activity_at', 6)->nullable();
            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
        });

        Schema::create('feimso_referrals', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('referrer_id', 191)->index();
            $table->string('referee_id', 191)->nullable()->index();
            $table->string('referee_email', 191)->nullable();
            $table->string('referral_code', 32)->index();
            $table->string('invite_code', 32)->nullable();
            $table->string('status', 32)->default('sent')->index()
                ->comment('sent|registered|activated|transacted');
            $table->string('channel', 64)->nullable();
            $table->dateTime('activated_at', 6)->nullable();
            $table->dateTime('transacted_at', 6)->nullable();
            $table->json('extra')->nullable();
            $table->dateTime('_created', 6)->nullable();
            $table->dateTime('_updated', 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feimso_referrals');
        Schema::dropIfExists('creator_pipeline');
    }
};
