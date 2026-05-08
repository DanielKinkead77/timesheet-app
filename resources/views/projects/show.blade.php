<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->department }}
                </h2>
                <span class="text-xs font-medium px-2 py-1 rounded-full mt-1 inline-block
                    {{ $project->is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-500' }}">
                    {{ $project->is_active ? 'Active' : 'Archived' }}
                </span>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('projects.edit', $project) }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700
                          font-semibold py-2 px-4 rounded-lg text-sm">
                    Edit Project
                </a>
                <a href="{{ route('time-sheets.create') }}?project_id={{ $project->id }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white
                          font-semibold py-2 px-4 rounded-lg text-sm">
                    + Log Time
                </a>
                <a href="{{ route('projects.index') }}"
                   class="text-sm text-gray-600 hover:text-gray-800 self-center">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Project Details --}}
            @if($project->description)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                    <p class="text-gray-800">{{ $project->description }}</p>
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('projects.show', $project) }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                            <input type="date"
                                name="from"
                                value="{{ request('from') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                        text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                            <input type="date"
                                name="to"
                                value="{{ request('to') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                        text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sort by</label>
                            <select name="sort"
                                    class="w-full border-gray-300 rounded-lg shadow-sm
                                        text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                                <option value="hour_number" {{ request('sort') == 'hour_number' ? 'selected' : '' }}>Hours</option>
                                <option value="work_description" {{ request('sort') == 'work_description' ? 'selected' : '' }}>Description</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Direction</label>
                            <select name="direction"
                                    class="w-full border-gray-300 rounded-lg shadow-sm
                                        text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Newest first</option>
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Oldest first</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white
                                    font-semibold py-2 px-4 rounded-lg text-sm">
                            Apply
                        </button>
                        <a href="{{ route('projects.show', $project) }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700
                                font-semibold py-2 px-4 rounded-lg text-sm">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Time Entries --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Time Log</h3>
                    <span class="text-sm text-gray-500">
                        {{ $timeSheets->count() }} {{ Str::plural('entry', $timeSheets->count()) }}
                    </span>
                </div>

                @if($timeSheets->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-gray-500">No time logged yet.</p>
                        <a href="{{ route('time-sheets.create') }}?project_id={{ $project->id }}"
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
                                           uppercase tracking-wider px-6 py-3">Start</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">End</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Hours</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Rate</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Description</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($timeSheets as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->start_time }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->end_time ?? '—' }}
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
                                    <td class="px-6 py-4 text-sm text-right">
                                        <a href="{{ route('time-sheets.edit', $entry) }}"
                                           class="text-indigo-600 hover:text-indigo-800
                                                  font-medium mr-3">Edit</a>
                                        <form action="{{ route('time-sheets.destroy', $entry) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Delete this entry?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700
                                                           font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>