<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\OrganizationMember;
use App\Models\User;

class RemoveMemberAction
{
    public function execute(OrganizationMember $member): void
    {
        $user = User::find($member->user_id);

        $member->delete();

        if ($user !== null && $user->organization_id === $member->organization_id) {
            $user->update(['organization_id' => null]);
        }
    }
}
