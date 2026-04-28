<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $org = $request->user()->organization;

        $stats = [];
        if ($org) {
            $stats = [
                'values' => $org->values()->count(),
                'principles' => $org->principles()->count(),
                'goals' => $org->strategicGoals()->count(),
                'visions' => $org->visions()->count(),
                'missions' => $org->missions()->count(),
                'projects' => $org->projects()->count(),
                'decisions' => $org->decisionLogs()->count(),
            ];
        }

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'hasOrganization' => $org !== null,
        ]);
    }
}
