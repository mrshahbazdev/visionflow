<?php

declare(strict_types=1);

namespace App\Http\Controllers\Values;

use App\Http\Controllers\Controller;
use App\Models\Value;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ValueController extends Controller
{
    public function index(Request $request): Response
    {
        $values = $request->user()->organization
            ->values()
            ->withCount(['cards', 'votes', 'principles'])
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Values/Index', [
            'values' => $values,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Values/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->organization->values()->create($validated);

        return redirect()->route('values.index');
    }

    public function show(Value $value): Response
    {
        $value->load(['cards.author', 'votes.user', 'principles', 'versions.changedByUser']);

        return Inertia::render('Values/Show', [
            'value' => $value,
        ]);
    }

    public function edit(Value $value): Response
    {
        return Inertia::render('Values/Edit', [
            'value' => $value,
        ]);
    }

    public function update(Request $request, Value $value): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', 'string'],
        ]);

        $value->update($validated);

        return redirect()->route('values.show', $value);
    }

    public function destroy(Value $value): RedirectResponse
    {
        $value->delete();

        return redirect()->route('values.index');
    }
}
