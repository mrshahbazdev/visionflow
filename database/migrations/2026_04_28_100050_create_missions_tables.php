<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mission_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('key')->unique();
            $table->string('pattern_en');
            $table->string('pattern_de');
            $table->timestamps();
        });

        Schema::create('missions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('vision_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('template_key')->nullable();
            $table->foreignUlid('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('active');
            $table->string('review_cadence')->default('quarterly');
            $table->timestamp('next_review_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        Schema::create('mission_reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('mission_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mission_reviews');
        Schema::dropIfExists('missions');
        Schema::dropIfExists('mission_templates');
    }
};
