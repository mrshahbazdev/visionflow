<?php

declare(strict_types=1);

namespace App\Http\Controllers\Principles;

use App\Http\Controllers\Controller;
use App\Models\Principle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PrincipleController extends Controller
{
    public function index(Request $request): Response
    {
        $principles = $request->user()->organization
            ->principles()
            ->with('value')
            ->withCount('consensusVotes')
            ->get();

        return Inertia::render('Principles/Index', [
            'principles' => $principles,
        ]);
    }

    public function create(Request $request): Response
    {
        $values = $request->user()->organization->values()->where('status', 'approved')->get();

        return Inertia::render('Principles/Create', [
            'values' => $values,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'value_id' => ['required', 'ulid', 'exists:values,id'],
            'statement' => ['required', 'string', 'max:2000'],
            'template_key' => ['nullable', 'string'],
        ]);

        $request->user()->organization->principles()->create($validated);

        return redirect()->route('principles.index');
    }

    public function show(Principle $principle): Response
    {
        $principle->load(['value', 'consensusVotes.user']);

        return Inertia::render('Principles/Show', [
            'principle' => $principle,
        ]);
    }

    public function edit(Principle $principle): Response
    {
        $values = auth()->user()->organization->values()->where('status', 'approved')->get();

        return Inertia::render('Principles/Edit', [
            'principle' => $principle,
            'values' => $values,
        ]);
    }

    public function update(Request $request, Principle $principle): RedirectResponse
    {
        $validated = $request->validate([
            'value_id' => ['required', 'ulid', 'exists:values,id'],
            'statement' => ['required', 'string', 'max:2000'],
            'status' => ['sometimes', 'string'],
        ]);

        $principle->update($validated);

        return redirect()->route('principles.show', $principle);
    }

    public function destroy(Principle $principle): RedirectResponse
    {
        $principle->delete();

        return redirect()->route('principles.index');
    }
}
