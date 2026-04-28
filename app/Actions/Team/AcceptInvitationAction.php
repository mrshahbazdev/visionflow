<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\InvitationStatus;
use App\Models\OrganizationMember;
use App\Models\TeamInvitation;
use App\Models\User;

class AcceptInvitationAction
{
    public function execute(TeamInvitation $invitation, User $user): OrganizationMember
    {
        $invitation->update([
            'status' => InvitationStatus::Accepted->value,
            'accepted_at' => now(),
        ]);

        $user->update([
            'organization_id' => $invitation->organization_id,
        ]);

        return OrganizationMember::updateOrCreate(
            [
                'organization_id' => $invitation->organization_id,
                'user_id' => $user->id,
            ],
            [
                'role' => $invitation->role->value,
                'joined_at' => now(),
            ],
        );
    }
}
