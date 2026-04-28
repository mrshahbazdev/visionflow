<?php

declare(strict_types=1);

namespace App\Http\Controllers\Team;

use App\Actions\Team\AcceptInvitationAction;
use App\Actions\Team\SendInvitationAction;
use App\Enums\InvitationStatus;
use App\Enums\TeamRole;
use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamInvitationController extends Controller
{
    public function store(Request $request, SendInvitationAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:' . implode(',', array_column(TeamRole::cases(), 'value'))],
            'personal_message' => ['nullable', 'string', 'max:500'],
        ]);

        $currentUserRole = $request->user()->teamRole();

        if ($currentUserRole === null || ! $currentUserRole->canManageMembers()) {
            abort(403);
        }

        $role = TeamRole::from($validated['role']);

        if ($role->hierarchy() >= $currentUserRole->hierarchy()) {
            abort(403);
        }

        $action->execute(
            organization: $request->user()->organization,
            inviter: $request->user(),
            email: $validated['email'],
            role: $role,
            personalMessage: $validated['personal_message'] ?? null,
        );

        return back();
    }

    public function showAccept(string $token): Response|RedirectResponse
    {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', InvitationStatus::Pending)
            ->where('expires_at', '>', now())
            ->with(['organization', 'inviter'])
            ->first();

        if ($invitation === null) {
            return redirect()->route('login')
                ->with('error', 'This invitation is invalid or has expired.');
        }

        return Inertia::render('Team/AcceptInvitation', [
            'invitation' => $invitation,
        ]);
    }

    public function accept(
        string $token,
        Request $request,
        AcceptInvitationAction $action,
    ): RedirectResponse {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', InvitationStatus::Pending)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $action->execute($invitation, $request->user());

        return redirect()->route('team.index');
    }

    public function revoke(Request $request, TeamInvitation $invitation): RedirectResponse
    {
        $currentUserRole = $request->user()->teamRole();

        if ($currentUserRole === null || ! $currentUserRole->canManageMembers()) {
            abort(403);
        }

        if ($invitation->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        $invitation->update([
            'status' => InvitationStatus::Revoked->value,
        ]);

        return back();
    }
}
