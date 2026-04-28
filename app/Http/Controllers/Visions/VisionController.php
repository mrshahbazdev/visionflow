<?php

declare(strict_types=1);

namespace App\Http\Controllers\Visions;

use App\Http\Controllers\Controller;
use App\Models\Vision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VisionController extends Controller
{
    public function index(Request $request): Response
    {
        $visions = $request->user()->organization
            ->visions()
            ->withCount(['drafts', 'approvals', 'missions'])
            ->orderByDesc('is_current')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Visions/Index', [
            'visions' => $visions,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Visions/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $request->user()->organization->visions()->create($validated);

        return redirect()->route('visions.index');
    }

    public function show(Vision $vision): Response
    {
        $vision->load(['drafts.author', 'approvals.user', 'missions']);

        return Inertia::render('Visions/Show', [
            'vision' => $vision,
        ]);
    }

    public function edit(Vision $vision): Response
    {
        return Inertia::render('Visions/Edit', [
            'vision' => $vision,
        ]);
    }

    public function update(Request $request, Vision $vision): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'status' => ['sometimes', 'string'],
        ]);

        $vision->update($validated);

        return redirect()->route('visions.show', $vision);
    }

    public function destroy(Vision $vision): RedirectResponse
    {
        $vision->delete();

        return redirect()->route('visions.index');
    }

    public function setCurrent(Vision $vision): RedirectResponse
    {
        $vision->organization->visions()->update(['is_current' => false]);
        $vision->update(['is_current' => true]);

        return redirect()->route('visions.show', $vision);
    }
}
