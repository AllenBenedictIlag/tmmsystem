<x-layouts.app-purple>

    <x-slot name="title">
        Social Media Manager
    </x-slot>
    <!-- =========================
     STATS
========================= -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-2xl shadow border border-purple-100">
            <p class="text-sm text-gray-500">Clients</p>
            <p class="text-2xl font-bold text-purple-700">
                {{ $clients->count() }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border border-purple-100">
            <p class="text-sm text-gray-500">Projects</p>
            <p class="text-2xl font-bold text-purple-700">
                {{ $projects->count() }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border border-purple-100">
            <p class="text-sm text-gray-500">Pending CEO Approval</p>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $pendingApprovals }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow border border-purple-100">
            <p class="text-sm text-gray-500">Ready to Post</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $readyToPost }}
            </p>
        </div>

    </div>

    <!-- =========================
     CLIENT LIST
========================= -->
    <div class="bg-white p-6 rounded-2xl shadow border border-purple-100 mb-10">
        <h2 class="text-lg font-semibold text-purple-700 mb-4">
            Clients
        </h2>

        @forelse($clients as $client)
        <div class="py-3 border-b border-purple-50">
            <p class="font-medium text-purple-700">
                {{ $client->name }}
            </p>
            <p class="text-sm text-gray-500">
                {{ $client->email }}
            </p>
        </div>
        @empty
        <p class="text-gray-500 text-sm">No clients found.</p>
        @endforelse
    </div>


    <!-- =========================
     PROJECT OVERVIEW
========================= -->
    <div class="bg-white p-6 rounded-2xl shadow border border-purple-100">

        <h2 class="text-lg font-semibold text-purple-700 mb-6">
            Project Overview
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-purple-50 text-purple-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Project</th>
                        <th class="px-4 py-3 text-center">Total Tasks</th>
                        <th class="px-4 py-3 text-center">Approved</th>
                        <th class="px-4 py-3 text-center">Rejected</th>
                        <th class="px-4 py-3 text-center">Posted</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-purple-100">
                    @forelse($projects as $project)
                    <tr class="hover:bg-purple-50 transition">

                        <td class="px-4 py-3 font-medium text-purple-700">
                            {{ $project->name }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            {{ $project->tasks->count() }}
                        </td>

                        <td class="px-4 py-3 text-center text-green-500">
                            {{ $project->tasks->where('status','approved')->count() }}
                        </td>

                        <td class="px-4 py-3 text-center text-red-500">
                            {{ $project->tasks->where('status','rejected')->count() }}
                        </td>

                        <td class="px-4 py-3 text-center text-purple-600">
                            {{ $project->tasks->where('status','posted')->count() }}
                        </td>

                        <!-- VIEW CALENDAR BUTTON -->
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('smm.calendar', $project) }}" class="inline-block bg-purple-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-purple-700 transition">
                                View Calendar
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No projects available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-layouts.app-purple>

