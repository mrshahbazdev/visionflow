<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_members', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignUlid('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('role')->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['organization_id', 'user_id']);
            $table->index(['organization_id', 'role']);
        });

        Schema::create('team_invitations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignUlid('invited_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('email');
            $table->string('role')->default('member');
            $table->string('token', 64)->unique();
            $table->string('status')->default('pending');
            $table->text('personal_message')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
            $table->index(['email', 'status']);
            $table->index('token');
        });

        Schema::create('work_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('organization_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignUlid('assigned_by')
                  ->constrained('users')
                  ->nullOnDelete();
            $table->foreignUlid('assigned_to')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('assignable_type');
            $table->string('assignable_id');
            $table->string('role_in_assignment')->default('responsible');
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['assignable_type', 'assignable_id']);
            $table->index(['assigned_to', 'status']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_assignments');
        Schema::dropIfExists('team_invitations');
        Schema::dropIfExists('organization_members');
    }
};
