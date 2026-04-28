<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TeamRole;
use App\Models\Organization;
use App\Models\OrganizationMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Organizations/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $org = Organization::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'description' => $validated['description'] ?? null,
        ]);

        $request->user()->update(['organization_id' => $org->id]);

        OrganizationMember::create([
            'organization_id' => $org->id,
            'user_id' => $request->user()->id,
            'role' => TeamRole::Owner->value,
            'joined_at' => now(),
        ]);

        return redirect()->route('dashboard');
    }

    public function settings(): Response
    {
        return Inertia::render('Organizations/Settings', [
            'organization' => auth()->user()->organization,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->organization->update($validated);

        return back();
    }
}
