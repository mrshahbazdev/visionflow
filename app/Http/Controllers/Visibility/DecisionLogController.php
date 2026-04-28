<?php

declare(strict_types=1);

namespace App\Http\Controllers\Visibility;

use App\Http\Controllers\Controller;
use App\Models\DecisionLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DecisionLogController extends Controller
{
    public function index(Request $request): Response
    {
        $decisions = $request->user()->organization
            ->decisionLogs()
            ->with(['user', 'supportingValue', 'supportingMission'])
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Visibility/Decisions/Index', [
            'decisions' => $decisions,
        ]);
    }

    public function create(Request $request): Response
    {
        $org = $request->user()->organization;

        return Inertia::render('Visibility/Decisions/Create', [
            'values' => $org->values()->where('status', 'approved')->get(),
            'missions' => $org->missions()->where('status', 'active')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'decision' => ['required', 'string', 'max:5000'],
            'supporting_value_id' => ['nullable', 'ulid', 'exists:values,id'],
            'supporting_mission_id' => ['nullable', 'ulid', 'exists:missions,id'],
        ]);

        $request->user()->organization->decisionLogs()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('decisions.index');
    }

    public function show(DecisionLog $decision): Response
    {
        $decision->load(['user', 'supportingValue', 'supportingMission']);

        return Inertia::render('Visibility/Decisions/Show', [
            'decision' => $decision,
        ]);
    }
}
