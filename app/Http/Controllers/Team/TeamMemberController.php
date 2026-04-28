<?php

declare(strict_types=1);

namespace App\Http\Controllers\Team;

use App\Actions\Team\RemoveMemberAction;
use App\Actions\Team\UpdateMemberRoleAction;
use App\Enums\TeamRole;
use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamMemberController extends Controller
{
    public function index(Request $request): Response
    {
        $organization = $request->user()->organization;

        $members = $organization->members()
            ->with('user')
            ->orderByRaw("CASE role WHEN 'owner' THEN 1 WHEN 'admin' THEN 2 WHEN 'leader' THEN 3 WHEN 'member' THEN 4 WHEN 'viewer' THEN 5 END")
            ->get();

        $pendingInvitations = $organization->invitations()
            ->with('inviter')
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        $currentUserRole = $request->user()->teamRole();

        return Inertia::render('Team/Index', [
            'members' => $members,
            'pendingInvitations' => $pendingInvitations,
            'currentUserRole' => $currentUserRole?->value,
            'availableRoles' => collect(TeamRole::cases())->map(fn (TeamRole $role) => [
                'value' => $role->value,
                'label' => $role->label(),
            ]),
        ]);
    }

    public function updateRole(
        Request $request,
        OrganizationMember $member,
        UpdateMemberRoleAction $action,
    ): RedirectResponse {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:' . implode(',', array_column(TeamRole::cases(), 'value'))],
        ]);

        $currentUserRole = $request->user()->teamRole();

        if ($currentUserRole === null || ! $currentUserRole->canManageMembers()) {
            abort(403);
        }

        $newRole = TeamRole::from($validated['role']);

        if ($newRole->hierarchy() >= $currentUserRole->hierarchy()) {
            abort(403);
        }

        $action->execute($member, $newRole);

        return back();
    }

    public function destroy(
        Request $request,
        OrganizationMember $member,
        RemoveMemberAction $action,
    ): RedirectResponse {
        $currentUserRole = $request->user()->teamRole();

        if ($currentUserRole === null || ! $currentUserRole->canManageMembers()) {
            abort(403);
        }

        if ($member->role === TeamRole::Owner) {
            abort(403);
        }

        if ($member->user_id === $request->user()->id) {
            abort(403);
        }

        $action->execute($member);

        return back();
    }
}
