<?php

namespace App\Http\Controllers;

use App\Models\TimeSheet;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TimeSheet::with(['project', 'hourlyRate', 'user'])
            ->orderBy('date', 'desc');

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $sortField = $request->get('sort', 'date');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['date', 'hour_number', 'project_id'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $timeSheets = $query->get();

        if ($sortField === 'user_name') {
            $timeSheets = $sortDirection === 'asc'
                ? $timeSheets->sortBy(fn($t) => $t->user->name)
                : $timeSheets->sortByDesc(fn($t) => $t->user->name);
        }

        if ($sortField === 'department') {
            $timeSheets = $sortDirection === 'asc'
                ? $timeSheets->sortBy(fn($t) => $t->project->department)
                : $timeSheets->sortByDesc(fn($t) => $t->project->department);
        }

        $users = User::orderBy('name')->get();

        return view('admin.index', compact('timeSheets', 'users'));
    }

    public function edit(string $id)
    {
        $timeSheet = TimeSheet::with(['project', 'user'])->findOrFail($id);
        $users = User::orderBy('name')->get();

        $projects = \App\Models\Project::orderBy('department')->get();
        $hourlyRates = \App\Models\HourlyRate::orderBy('rate_code')->get();

        return view('admin.edit', compact('timeSheet', 'users', 'projects', 'hourlyRates'));
    }

    public function update(Request $request, string $id)
    {
        $timeSheet = TimeSheet::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'hourly_rate_id' => 'required|exists:hourly_rates,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'work_description' => 'nullable|string',
        ]);

        if (!empty($validated['end_time']) && !empty($validated['start_time'])) {
            $start = strtotime($validated['start_time']);
            $end = strtotime($validated['end_time']);
            if ($end < $start) {
                $end += 86400;
            }
            $validated['hour_number'] = round(($end - $start) / 3600, 2);
        }

        $timeSheet->update($validated);

        return redirect()->route('admin.index')
            ->with('success', 'Entry updated successfully.');
    }

    public function destroy(string $id)
    {
        TimeSheet::findOrFail($id)->delete();

        return redirect()->route('admin.index')
            ->with('success', 'Entry deleted successfully.');
    }
}