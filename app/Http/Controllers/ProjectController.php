<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
{
    $validated = $request->validated();
    $validated['user_id'] = auth()->id();
    $validated['is_active'] = $request->has('is_active');

    Project::create($validated);

    return redirect()->route('projects.index')
        ->with('success', 'Project created successfully.');
}

    public function show(Request $request, string $id)
{
    $project = Project::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $query = $project->timeSheets()->with(['hourlyRate']);

    if ($request->filled('from')) {
        $query->whereDate('date', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('date', '<=', $request->to);
    }

    $sortField = $request->get('sort', 'date');
    $sortDirection = $request->get('direction', 'desc');

    $allowedSorts = ['date', 'hour_number'];
    if (in_array($sortField, $allowedSorts)) {
        $query->orderBy($sortField, $sortDirection);
    } else {
        $query->orderBy('date', 'desc');
    }

    $timeSheets = $query->get();

    if ($sortField === 'work_description') {
        $timeSheets = $sortDirection === 'asc'
            ? $timeSheets->sortBy(fn($t) => $t->work_description)
            : $timeSheets->sortByDesc(fn($t) => $t->work_description);
    }

    return view('projects.show', compact('project', 'timeSheets'));
}

    public function edit(string $id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, string $id)
{
    $project = Project::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $validated = $request->validated();
    $validated['is_active'] = $request->has('is_active');

    $project->update($validated);

    return redirect()->route('projects.index')
        ->with('success', 'Project updated successfully.');
}
    public function destroy(string $id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}