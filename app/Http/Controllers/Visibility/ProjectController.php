<?php

declare(strict_types=1);

namespace App\Http\Controllers\Visibility;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $projects = $request->user()->organization
            ->projects()
            ->with('mission.vision')
            ->get();

        return Inertia::render('Visibility/Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function create(Request $request): Response
    {
        $missions = $request->user()->organization
            ->missions()
            ->where('status', 'active')
            ->get();

        return Inertia::render('Visibility/Projects/Create', [
            'missions' => $missions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mission_id' => ['required', 'ulid', 'exists:missions,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->organization->projects()->create($validated);

        return redirect()->route('projects.index');
    }

    public function show(Project $project): Response
    {
        $project->load('mission.vision');

        return Inertia::render('Visibility/Projects/Show', [
            'project' => $project,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', 'string'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index');
    }
}
