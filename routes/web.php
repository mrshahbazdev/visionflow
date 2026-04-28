<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Goals\StrategicGoalController;
use App\Http\Controllers\Missions\MissionController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\Principles\PrincipleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Values\ValueController;
use App\Http\Controllers\Visibility\DecisionLogController;
use App\Http\Controllers\Visibility\ProjectController;
use App\Http\Controllers\Visions\VisionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Organization
    Route::get('/organization/create', [OrganizationController::class, 'create'])->name('organization.create');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/organization/settings', [OrganizationController::class, 'settings'])->name('organization.settings');
    Route::patch('/organization', [OrganizationController::class, 'update'])->name('organization.update');

    // Values Workshop
    Route::resource('values', ValueController::class);

    // Principles Builder
    Route::resource('principles', PrincipleController::class);

    // Strategic Goals Canvas
    Route::resource('goals', StrategicGoalController::class);

    // Vision Co-Creation
    Route::resource('visions', VisionController::class);
    Route::post('/visions/{vision}/set-current', [VisionController::class, 'setCurrent'])->name('visions.set-current');

    // Mission Generator
    Route::resource('missions', MissionController::class);

    // Visibility & Embedding
    Route::resource('projects', ProjectController::class);
    Route::resource('decisions', DecisionLogController::class)->only(['index', 'create', 'store', 'show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
