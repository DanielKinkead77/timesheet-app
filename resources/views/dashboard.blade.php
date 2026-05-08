<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-700 text-lg">
                    Hi <span class="font-medium">{{ auth()->user()->name }}</span> — you have logged
                    <span class="font-medium text-indigo-600">{{ $hoursThisWeek }} hours</span>
                    this week across
                    <span class="font-medium">{{ $activeProjects }} active {{ Str::plural('project', $activeProjects) }}</span>
                    with
                    <span class="font-medium">{{ $totalEntries }} total {{ Str::plural('entry', $totalEntries) }}</span>.
                </p>
            </div>

            {{-- Recent Entries --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Recent Time Entries</h3>
                    <a href="{{ route('time-sheets.index') }}"
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        View all →
                    </a>
                </div>

                @if($recentEntries->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-gray-500">No time logged yet.</p>
                        <a href="{{ route('time-sheets.create') }}"
                           class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700
                                  text-white font-semibold py-2 px-6 rounded-lg text-sm">
                            Log your first entry
                        </a>
                    </div>
                @else
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Date</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Project</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Hours</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Rate</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentEntries as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('projects.show', $entry->project_id) }}"
                                           class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            {{ $entry->project->department }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->hour_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="bg-indigo-100 text-indigo-700
                                                     text-xs font-medium px-2 py-1 rounded-full">
                                            {{ $entry->hourlyRate->rate_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $entry->work_description ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Projects Overview --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Your Projects</h3>
                    <a href="{{ route('projects.index') }}"
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        View all →
                    </a>
                </div>
                @if($projects->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-gray-500">No projects yet.</p>
                        <a href="{{ route('projects.create') }}"
                           class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700
                                  text-white font-semibold py-2 px-6 rounded-lg text-sm">
                            Create your first project
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($projects as $project)
                            <div class="px-6 py-4 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="font-medium text-indigo-600 hover:text-indigo-800">
                                        {{ $project->department }}
                                    </a>
                                    @if($project->description)
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            {{ Str::limit($project->description, 60) }}
                                        </p>
                                    @endif
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full
                                    {{ $project->is_active
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-500' }}">
                                    {{ $project->is_active ? 'Active' : 'Archived' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>