<x-layouts.app-purple>

<x-slot name="title">
    Creative Dashboard
</x-slot>

<div class="mb-8">

    <p class="text-sm text-gray-500 mt-1">
        Welcome back, {{ auth()->user()->name }}
    </p>
</div>

<!-- MAIN CARD -->
<div class="bg-white rounded-2xl shadow border border-purple-100 p-8">

    <h2 class="text-lg font-semibold text-purple-700 mb-6">
        My Assigned Tasks
    </h2>

    @if($tasks->count())

        <div class="overflow-hidden rounded-xl border border-purple-100">

            <table class="w-full text-sm">

                <thead class="bg-purple-50 text-purple-700 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="px-6 py-4 text-left">Title</th>
                        <th class="px-6 py-4 text-left">Project</th>
                        <th class="px-6 py-4 text-left">Deadline</th>
                        <th class="px-6 py-4 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-purple-100 bg-white">

                    @foreach($tasks as $task)
                    <tr class="hover:bg-purple-50 transition">

                        <!-- Title -->
                        <td class="px-6 py-4">
                            <a href="{{ route('creative.tasks.show', $task) }}"
                               class="font-medium text-purple-700 hover:underline">
                                {{ $task->title }}
                            </a>
                        </td>

                        <!-- Project -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ $task->project->name }}
                        </td>

                        <!-- Deadline -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ optional($task->due_at)->format('M d, Y h:i A') }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="
                                px-3 py-1 text-xs rounded-full font-medium
                                @if($task->status === 'in_progress') bg-yellow-100 text-yellow-700
                                @elseif($task->status === 'completed') bg-green-100 text-green-700
                                @elseif($task->status === 'assigned') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-600
                                @endif
                            ">
                                {{ ucfirst(str_replace('_',' ',$task->status)) }}
                            </span>
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="bg-purple-50 text-purple-700 px-6 py-4 rounded-xl text-sm">
            You currently have no assigned tasks.
        </div>

    @endif

</div>

</x-layouts.app-purple>