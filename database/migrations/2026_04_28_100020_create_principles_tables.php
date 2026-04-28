<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('principle_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('key')->unique();
            $table->string('pattern_en');
            $table->string('pattern_de');
            $table->timestamps();
        });

        Schema::create('principles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('value_id')->constrained()->cascadeOnDelete();
            $table->text('statement');
            $table->string('template_key')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('alignment_score', 5, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });

        Schema::create('principle_consensus', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('principle_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->string('vote');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['principle_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('principle_consensus');
        Schema::dropIfExists('principles');
        Schema::dropIfExists('principle_templates');
    }
};
