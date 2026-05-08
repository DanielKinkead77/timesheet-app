<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin — Edit Entry
            </h2>
            <a href="{{ route('admin.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                ← Back to Admin
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <form method="POST" action="{{ route('admin.update', $timeSheet->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            User <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                       focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $timeSheet->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Project <span class="text-red-500">*</span>
                        </label>
                        <select name="project_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                       focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $timeSheet->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->department }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="date"
                               value="{{ old('date', $timeSheet->date->format('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm
                                      focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                   name="start_time"
                                   value="{{ old('start_time', $timeSheet->start_time) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm
                                          focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                End Time
                            </label>
                            <input type="time"
                                   name="end_time"
                                   value="{{ old('end_time', $timeSheet->end_time) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm
                                          focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rate Type <span class="text-red-500">*</span>
                        </label>
                        <select name="hourly_rate_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                       focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($hourlyRates as $rate)
                                <option value="{{ $rate->id }}"
                                    {{ $timeSheet->hourly_rate_id == $rate->id ? 'selected' : '' }}>
                                    {{ $rate->rate_code }} — {{ $rate->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Work Description
                        </label>
                        <textarea name="work_description"
                                  rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm
                                         focus:ring-indigo-500 focus:border-indigo-500">
{{ old('work_description', $timeSheet->work_description) }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white
                                       font-semibold py-2 px-6 rounded-lg">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.index') }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700
                                  font-semibold py-2 px-6 rounded-lg">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>