<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Project
            </h2>
            <a href="{{ route('projects.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                ← Back to Projects
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <form method="POST" action="{{ route('projects.update', $project) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="department"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="department"
                               name="department"
                               value="{{ old('department', $project->department) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm
                                      focus:ring-indigo-500 focus:border-indigo-500
                                      @error('department') border-red-500 @enderror">
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm
                                         focus:ring-indigo-500 focus:border-indigo-500">
{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div class="mb-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ $project->is_active ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600
                                          focus:ring-indigo-500 w-4 h-4">
                            <span class="text-sm font-medium text-gray-700">
                                Active project
                            </span>
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white
                                       font-semibold py-2 px-6 rounded-lg">
                            Save Changes
                        </button>
                        <a href="{{ route('projects.index') }}"
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