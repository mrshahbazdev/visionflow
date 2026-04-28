<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('status')->default('drafting');
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('approved_at')->nullable();
            $table->foreignUlid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_current')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['organization_id', 'is_current']);
        });

        Schema::create('vision_drafts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('vision_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('author_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('vision_comments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('vision_draft_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->foreignUlid('parent_id')->nullable()->constrained('vision_comments')->cascadeOnDelete();
            $table->unsignedTinyInteger('emotional_resonance')->default(3);
            $table->timestamps();
        });

        Schema::create('vision_approvals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('vision_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->string('decision');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['vision_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vision_approvals');
        Schema::dropIfExists('vision_comments');
        Schema::dropIfExists('vision_drafts');
        Schema::dropIfExists('visions');
    }
};
