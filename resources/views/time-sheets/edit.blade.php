<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Time Entry
            </h2>
            <a href="{{ url()->previous() }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <form method="POST" action="{{ route('time-sheets.update', $timeSheet) }}">
                    @csrf
                    @method('PUT')

                    {{-- Project --}}
                    <div class="mb-6">
                        <label for="project_id"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Project <span class="text-red-500">*</span>
                        </label>
                        <select id="project_id"
                                name="project_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                       focus:ring-indigo-500 focus:border-indigo-500
                                       @error('project_id') border-red-500 @enderror">
                            <option value="">Select a project...</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $timeSheet->project_id) == $project->id
                                        ? 'selected' : '' }}>
                                    {{ $project->department }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div class="mb-6">
                        <label for="date"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="date"
                               name="date"
                               value="{{ old('date', $timeSheet->date->format('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm
                                      focus:ring-indigo-500 focus:border-indigo-500
                                      @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Start and End Time --}}
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="start_time"
                                   class="block text-sm font-medium text-gray-700 mb-2">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                   id="start_time"
                                   name="start_time"
                                   value="{{ old('start_time', $timeSheet->start_time) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm
                                          focus:ring-indigo-500 focus:border-indigo-500
                                          @error('start_time') border-red-500 @enderror">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_time"
                                   class="block text-sm font-medium text-gray-700 mb-2">
                                End Time
                            </label>
                            <input type="time"
                                   id="end_time"
                                   name="end_time"
                                   value="{{ old('end_time', $timeSheet->end_time) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm
                                          focus:ring-indigo-500 focus:border-indigo-500
                                          @error('end_time') border-red-500 @enderror">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Rate --}}
                    <div class="mb-6">
                        <label for="hourly_rate_id"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Rate Type <span class="text-red-500">*</span>
                        </label>
                        <select id="hourly_rate_id"
                                name="hourly_rate_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm
                                       focus:ring-indigo-500 focus:border-indigo-500
                                       @error('hourly_rate_id') border-red-500 @enderror">
                            <option value="">Select a rate...</option>
                            @foreach($hourlyRates as $rate)
                                <option value="{{ $rate->id }}"
                                    {{ old('hourly_rate_id', $timeSheet->hourly_rate_id) == $rate->id
                                        ? 'selected' : '' }}>
                                    {{ $rate->rate_code }} — {{ $rate->description }}
                                </option>
                            @endforeach
                        </select>
                        @error('hourly_rate_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Work Description --}}
                    <div class="mb-8">
                        <label for="work_description"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Work Description
                        </label>
                        <textarea id="work_description"
                                  name="work_description"
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
                        <a href="{{ url()->previous() }}"
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