<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Organization;
use App\Models\User;
use App\Models\WorkAssignment;
use Illuminate\Database\Eloquent\Model;

class CreateWorkAssignmentAction
{
    public function execute(
        Organization $organization,
        User $assignedBy,
        User $assignedTo,
        Model $assignable,
        string $roleInAssignment = 'responsible',
        ?string $notes = null,
        ?string $dueAt = null,
    ): WorkAssignment {
        return WorkAssignment::create([
            'organization_id' => $organization->id,
            'assigned_by' => $assignedBy->id,
            'assigned_to' => $assignedTo->id,
            'assignable_type' => $assignable->getMorphClass(),
            'assignable_id' => $assignable->getKey(),
            'role_in_assignment' => $roleInAssignment,
            'notes' => $notes,
            'status' => 'active',
            'due_at' => $dueAt,
        ]);
    }
}
