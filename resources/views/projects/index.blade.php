<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
            <a href="{{ route('projects.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm">
                + New Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($projects->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-500 text-lg">No projects yet.</p>
                    <a href="{{ route('projects.create') }}"
                       class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Create your first project
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold text-lg text-gray-800">
                                    {{ $project->department }}
                                </h3>
                                <span class="text-xs font-medium px-2 py-1 rounded-full
                                    {{ $project->is_active
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-500' }}">
                                    {{ $project->is_active ? 'Active' : 'Archived' }}
                                </span>
                            </div>

                            @if($project->description)
                                <p class="text-gray-500 text-sm mb-4">
                                    {{ $project->description }}
                                </p>
                            @endif

                            <div class="flex gap-3 mt-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('projects.show', $project) }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    View
                                </a>
                                <a href="{{ route('projects.edit', $project) }}"
                                   class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}"
                                      method="POST"
                                      class="ml-auto"
                                      onsubmit="return confirm('Delete this project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-500 hover:text-red-700 font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>