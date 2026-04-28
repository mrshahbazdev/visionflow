<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\InvitationStatus;
use App\Enums\TeamRole;
use App\Models\Organization;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class SendInvitationAction
{
    public function execute(
        Organization $organization,
        User $inviter,
        string $email,
        TeamRole $role,
        ?string $personalMessage = null,
    ): TeamInvitation {
        $existingPending = TeamInvitation::where('organization_id', $organization->id)
            ->where('email', $email)
            ->where('status', InvitationStatus::Pending)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingPending !== null) {
            $existingPending->update([
                'status' => InvitationStatus::Revoked,
            ]);
        }

        $invitation = TeamInvitation::create([
            'organization_id' => $organization->id,
            'invited_by' => $inviter->id,
            'email' => $email,
            'role' => $role->value,
            'token' => Str::random(64),
            'status' => InvitationStatus::Pending->value,
            'personal_message' => $personalMessage,
            'expires_at' => now()->addDays(7),
        ]);

        Notification::route('mail', $email)
            ->notify(new TeamInvitationNotification($invitation));

        return $invitation;
    }
}
