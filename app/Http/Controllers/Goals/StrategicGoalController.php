<?php

declare(strict_types=1);

namespace App\Http\Controllers\Goals;

use App\Http\Controllers\Controller;
use App\Models\StrategicGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StrategicGoalController extends Controller
{
    public function index(Request $request): Response
    {
        $goals = $request->user()->organization
            ->strategicGoals()
            ->with(['values', 'principles'])
            ->get()
            ->groupBy('category');

        return Inertia::render('Goals/Index', [
            'goalsByCategory' => $goals,
        ]);
    }

    public function create(Request $request): Response
    {
        $org = $request->user()->organization;

        return Inertia::render('Goals/Create', [
            'values' => $org->values()->where('status', 'approved')->get(),
            'principles' => $org->principles()->where('status', 'approved')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', 'string', 'in:market,impact,organization'],
            'time_horizon' => ['nullable', 'string', 'max:100'],
            'value_ids' => ['nullable', 'array'],
            'value_ids.*' => ['ulid', 'exists:values,id'],
            'principle_ids' => ['nullable', 'array'],
            'principle_ids.*' => ['ulid', 'exists:principles,id'],
        ]);

        $goal = $request->user()->organization->strategicGoals()->create(
            collect($validated)->except(['value_ids', 'principle_ids'])->toArray()
        );

        if (! empty($validated['value_ids'])) {
            $goal->values()->sync($validated['value_ids']);
        }

        if (! empty($validated['principle_ids'])) {
            $goal->principles()->sync($validated['principle_ids']);
        }

        return redirect()->route('goals.index');
    }

    public function show(StrategicGoal $goal): Response
    {
        $goal->load(['values', 'principles']);

        return Inertia::render('Goals/Show', [
            'goal' => $goal,
        ]);
    }

    public function edit(StrategicGoal $goal): Response
    {
        $org = auth()->user()->organization;
        $goal->load(['values', 'principles']);

        return Inertia::render('Goals/Edit', [
            'goal' => $goal,
            'values' => $org->values()->where('status', 'approved')->get(),
            'principles' => $org->principles()->where('status', 'approved')->get(),
        ]);
    }

    public function update(Request $request, StrategicGoal $goal): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', 'string', 'in:market,impact,organization'],
            'time_horizon' => ['nullable', 'string', 'max:100'],
            'value_ids' => ['nullable', 'array'],
            'principle_ids' => ['nullable', 'array'],
        ]);

        $goal->update(collect($validated)->except(['value_ids', 'principle_ids'])->toArray());
        $goal->values()->sync($validated['value_ids'] ?? []);
        $goal->principles()->sync($validated['principle_ids'] ?? []);

        return redirect()->route('goals.show', $goal);
    }

    public function destroy(StrategicGoal $goal): RedirectResponse
    {
        $goal->delete();

        return redirect()->route('goals.index');
    }
}
