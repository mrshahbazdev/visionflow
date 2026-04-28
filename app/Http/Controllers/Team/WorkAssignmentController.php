<?php

declare(strict_types=1);

namespace App\Http\Controllers\Team;

use App\Actions\Team\CreateWorkAssignmentAction;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Project;
use App\Models\WorkAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkAssignmentController extends Controller
{
    public function index(Request $request): Response
    {
        $organization = $request->user()->organization;

        $assignments = $organization->workAssignments()
            ->with(['assignedBy', 'assignedTo', 'assignable'])
            ->orderBy('created_at', 'desc')
            ->get();

        $members = $organization->members()
            ->with('user')
            ->get();

        $missions = $organization->missions()->get(['id', 'title']);
        $projects = $organization->projects()->get(['id', 'name']);

        return Inertia::render('Team/Assignments', [
            'assignments' => $assignments,
            'members' => $members,
            'missions' => $missions,
            'projects' => $projects,
        ]);
    }

    public function store(Request $request, CreateWorkAssignmentAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
            'assignable_type' => ['required', 'string', 'in:mission,project'],
            'assignable_id' => ['required', 'string'],
            'role_in_assignment' => ['sometimes', 'string', 'in:responsible,accountable,consulted,informed'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'due_at' => ['nullable', 'date', 'after:now'],
        ]);

        $currentUserRole = $request->user()->teamRole();

        if ($currentUserRole === null || ! $currentUserRole->canAssignWork()) {
            abort(403);
        }

        $assignable = match ($validated['assignable_type']) {
            'mission' => Mission::findOrFail($validated['assignable_id']),
            'project' => Project::findOrFail($validated['assignable_id']),
        };

        $assignedTo = \App\Models\User::findOrFail($validated['assigned_to']);

        $action->execute(
            organization: $request->user()->organization,
            assignedBy: $request->user(),
            assignedTo: $assignedTo,
            assignable: $assignable,
            roleInAssignment: $validated['role_in_assignment'] ?? 'responsible',
            notes: $validated['notes'] ?? null,
            dueAt: $validated['due_at'] ?? null,
        );

        return back();
    }

    public function complete(Request $request, WorkAssignment $assignment): RedirectResponse
    {
        if ($assignment->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        $assignment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back();
    }

    public function destroy(Request $request, WorkAssignment $assignment): RedirectResponse
    {
        $currentUserRole = $request->user()->teamRole();

        if ($currentUserRole === null || ! $currentUserRole->canAssignWork()) {
            abort(403);
        }

        if ($assignment->organization_id !== $request->user()->organization_id) {
            abort(403);
        }

        $assignment->delete();

        return back();
    }
}
