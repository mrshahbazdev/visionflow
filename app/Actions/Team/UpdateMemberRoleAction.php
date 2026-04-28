<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\TeamRole;
use App\Models\OrganizationMember;

class UpdateMemberRoleAction
{
    public function execute(OrganizationMember $member, TeamRole $newRole): OrganizationMember
    {
        $member->update([
            'role' => $newRole->value,
        ]);

        return $member->fresh();
    }
}
