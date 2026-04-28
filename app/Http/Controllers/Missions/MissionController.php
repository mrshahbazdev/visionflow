<?php

declare(strict_types=1);

namespace App\Http\Controllers\Missions;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MissionController extends Controller
{
    public function index(Request $request): Response
    {
        $missions = $request->user()->organization
            ->missions()
            ->with(['vision', 'owner'])
            ->withCount(['reviews', 'projects'])
            ->get();

        return Inertia::render('Missions/Index', [
            'missions' => $missions,
        ]);
    }

    public function create(Request $request): Response
    {
        $visions = $request->user()->organization
            ->visions()
            ->where('status', 'approved')
            ->orWhere('is_current', true)
            ->get();

        $members = $request->user()->organization->users()->get(['id', 'name', 'email']);

        return Inertia::render('Missions/Create', [
            'visions' => $visions,
            'members' => $members,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vision_id' => ['required', 'ulid', 'exists:visions,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'owner_id' => ['nullable', 'ulid', 'exists:users,id'],
            'review_cadence' => ['required', 'string', 'in:monthly,quarterly,biannually,annually'],
        ]);

        $request->user()->organization->missions()->create($validated);

        return redirect()->route('missions.index');
    }

    public function show(Mission $mission): Response
    {
        $mission->load(['vision', 'owner', 'reviews.reviewer', 'projects']);

        return Inertia::render('Missions/Show', [
            'mission' => $mission,
        ]);
    }

    public function edit(Mission $mission): Response
    {
        $org = auth()->user()->organization;

        return Inertia::render('Missions/Edit', [
            'mission' => $mission,
            'visions' => $org->visions()->get(),
            'members' => $org->users()->get(['id', 'name', 'email']),
        ]);
    }

    public function update(Request $request, Mission $mission): RedirectResponse
    {
        $validated = $request->validate([
            'vision_id' => ['required', 'ulid', 'exists:visions,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'owner_id' => ['nullable', 'ulid', 'exists:users,id'],
            'status' => ['sometimes', 'string'],
            'review_cadence' => ['required', 'string'],
        ]);

        $mission->update($validated);

        return redirect()->route('missions.show', $mission);
    }

    public function destroy(Mission $mission): RedirectResponse
    {
        $mission->delete();

        return redirect()->route('missions.index');
    }
}
