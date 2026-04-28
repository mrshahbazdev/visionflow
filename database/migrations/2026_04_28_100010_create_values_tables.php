<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('values', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('approved_at')->nullable();
            $table->foreignUlid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        Schema::create('value_cards', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('value_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('author_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
        });

        Schema::create('value_clusters', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('value_card_cluster', function (Blueprint $table) {
            $table->foreignUlid('value_card_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('value_cluster_id')->constrained()->cascadeOnDelete();
            $table->primary(['value_card_id', 'value_cluster_id']);
        });

        Schema::create('value_votes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('value_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->default(1);
            $table->unsignedInteger('round')->default(1);
            $table->timestamps();

            $table->unique(['value_id', 'user_id', 'round']);
        });

        Schema::create('value_versions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('value_id')->constrained()->cascadeOnDelete();
            $table->json('data');
            $table->foreignUlid('changed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('value_versions');
        Schema::dropIfExists('value_votes');
        Schema::dropIfExists('value_card_cluster');
        Schema::dropIfExists('value_clusters');
        Schema::dropIfExists('value_cards');
        Schema::dropIfExists('values');
    }
};
