<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin — All Timesheets
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400
                            text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                From
                            </label>
                            <input type="date"
                                   name="from"
                                   value="{{ request('from') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm
                                          text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                To
                            </label>
                            <input type="date"
                                   name="to"
                                   value="{{ request('to') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm
                                          text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                User
                            </label>
                            <select name="user_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm
                                           text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                Sort by
                            </label>
                            <div class="flex gap-2">
                                <select name="sort"
                                        class="flex-1 border-gray-300 rounded-lg shadow-sm
                                               text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                                    <option value="hour_number" {{ request('sort') == 'hour_number' ? 'selected' : '' }}>Hours</option>
                                    <option value="department" {{ request('sort') == 'department' ? 'selected' : '' }}>Project</option>
                                    <option value="user_name" {{ request('sort') == 'user_name' ? 'selected' : '' }}>User name</option>
                                </select>
                                <select name="direction"
                                        class="border-gray-300 rounded-lg shadow-sm
                                               text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>↓</option>
                                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>↑</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white
                                       font-semibold py-2 px-4 rounded-lg text-sm">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.index') }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700
                                  font-semibold py-2 px-4 rounded-lg text-sm">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Results --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">
                        {{ $timeSheets->count() }} {{ Str::plural('entry', $timeSheets->count()) }}
                    </h3>
                </div>

                @if($timeSheets->isEmpty())
                    <div class="p-12 text-center">
                        <p class="text-gray-500">No entries found.</p>
                    </div>
                @else
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">User</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Date</th>
                                <th class="text-left text-xs font-medium text-gray-500
                                           uppercase tracking-wider px-6 py-3">Project</th>
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
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $entry->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $entry->project->department }}
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
                                    <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                                        <a href="{{ route('admin.edit', $entry->id) }}"
                                           class="text-indigo-600 hover:text-indigo-800
                                                  font-medium mr-3">Edit</a>
                                        <form action="{{ route('admin.destroy', $entry->id) }}"
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