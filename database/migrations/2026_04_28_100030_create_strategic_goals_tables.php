<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_goals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('time_horizon')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['organization_id', 'category']);
        });

        Schema::create('goal_value', function (Blueprint $table) {
            $table->foreignUlid('strategic_goal_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('value_id')->constrained()->cascadeOnDelete();
            $table->primary(['strategic_goal_id', 'value_id']);
        });

        Schema::create('goal_principle', function (Blueprint $table) {
            $table->foreignUlid('strategic_goal_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('principle_id')->constrained()->cascadeOnDelete();
            $table->primary(['strategic_goal_id', 'principle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_principle');
        Schema::dropIfExists('goal_value');
        Schema::dropIfExists('strategic_goals');
    }
};
