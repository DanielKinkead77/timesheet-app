<?php

namespace App\Http\Controllers;

use App\Models\HourlyRate;
use App\Models\Project;
use App\Models\TimeSheet;
use App\Http\Requests\StoreTimeSheetRequest;
use App\Http\Requests\UpdateTimeSheetRequest;
use Illuminate\Http\Request;

class TimeSheetController extends Controller
{
    public function index(Request $request)
{
    $query = TimeSheet::where('user_id', auth()->id())
        ->with(['project', 'hourlyRate']);

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

    if ($sortField === 'department') {
        $timeSheets = $sortDirection === 'asc'
            ? $timeSheets->sortBy(fn($t) => $t->project->department)
            : $timeSheets->sortByDesc(fn($t) => $t->project->department);
    }

    $timeSheets = $timeSheets->groupBy(fn($entry) => $entry->date->format('Y-m-d'));

    return view('time-sheets.index', compact('timeSheets'));
}

    public function create()
    {
        $projects = Project::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('department')
            ->get();

        $hourlyRates = HourlyRate::orderBy('rate_code')->get();
        $selectedProjectId = request('project_id');

        return view('time-sheets.create', compact('projects', 'hourlyRates', 'selectedProjectId'));
    }

    public function store(StoreTimeSheetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        if (!empty($validated['end_time']) && !empty($validated['start_time'])) {
            $start = strtotime($validated['start_time']);
            $end = strtotime($validated['end_time']);
            if ($end < $start) {
                $end += 86400;
            }
            $validated['hour_number'] = round(($end - $start) / 3600, 2);
        }

        TimeSheet::create($validated);

        return redirect()->route('projects.show', $validated['project_id'])
            ->with('success', 'Time logged successfully.');
    }

    public function show(string $id)
    {
        $timeSheet = TimeSheet::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('time-sheets.show', compact('timeSheet'));
    }

    public function edit(string $id)
    {
        $timeSheet = TimeSheet::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $projects = Project::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('department')
            ->get();

        $hourlyRates = HourlyRate::orderBy('rate_code')->get();

        return view('time-sheets.edit', compact('timeSheet', 'projects', 'hourlyRates'));
    }

    public function update(UpdateTimeSheetRequest $request, string $id)
    {
        $timeSheet = TimeSheet::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validated();

        if (!empty($validated['end_time']) && !empty($validated['start_time'])) {
            $start = strtotime($validated['start_time']);
            $end = strtotime($validated['end_time']);
            if ($end < $start) {
                $end += 86400;
            }
            $validated['hour_number'] = round(($end - $start) / 3600, 2);
        }

        $timeSheet->update($validated);

        return redirect()->route('projects.show', $timeSheet->project_id)
            ->with('success', 'Time entry updated successfully.');
    }

    public function destroy(string $id)
    {
        $timeSheet = TimeSheet::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $projectId = $timeSheet->project_id;
        $timeSheet->delete();

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Time entry deleted successfully.');
    }
}