<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\HourlyRateController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    $activeProjects = \App\Models\Project::where('user_id', $user->id)
        ->where('is_active', true)
        ->count();

    $hoursThisWeek = round(\App\Models\TimeSheet::where('user_id', $user->id)
        ->whereBetween('date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
        ->sum('hour_number'), 2);

    $totalEntries = \App\Models\TimeSheet::where('user_id', $user->id)->count();

    $recentEntries = \App\Models\TimeSheet::where('user_id', $user->id)
        ->with(['project', 'hourlyRate'])
        ->orderBy('date', 'desc')
        ->orderBy('start_time', 'desc')
        ->limit(5)
        ->get();

    $projects = \App\Models\Project::where('user_id', $user->id)
        ->orderBy('is_active', 'desc')
        ->orderBy('department')
        ->get();

    return view('dashboard', compact(
        'activeProjects',
        'hoursThisWeek',
        'totalEntries',
        'recentEntries',
        'projects'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::resource('time-sheets', TimeSheetController::class);
    Route::resource('hourly-rates', HourlyRateController::class);

    Route::middleware('can:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');



    });    
});

require __DIR__.'/auth.php';