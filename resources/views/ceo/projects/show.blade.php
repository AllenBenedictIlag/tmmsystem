<x-layouts.app-purple>

<h1 class="text-3xl font-bold text-purple-700 mb-6">
    Project: {{ $project->name }}
</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Client</p>
        <p class="text-lg font-semibold">
            {{ $project->client->name ?? '-' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Assigned SMM</p>
        <p class="text-lg font-semibold">
            {{ $project->smm->name ?? 'Not Assigned' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Planning Due</p>
        <p class="text-lg font-semibold">
            {{ optional($project->planning_due_at)->format('M d, Y') ?? '-' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Start Date</p>
        <p class="text-lg font-semibold">
            {{ optional($project->start_date)->format('M d, Y') ?? '-' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">End Date</p>
        <p class="text-lg font-semibold">
            {{ optional($project->end_date)->format('M d, Y') ?? '-' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Progress</p>
        <p class="text-2xl font-bold text-green-600">
            {{ $progress }}%
        </p>
        <p class="text-sm text-gray-500">
            {{ $completedTasks }} / {{ $totalTasks }} tasks completed
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Overdue Tasks</p>
        <p class="text-2xl font-bold text-red-600">
            {{ $overdue }}
        </p>
    </div>

</div>

<h2 class="text-2xl font-bold text-purple-700 mb-4">
    Tasks
</h2>

<div class="bg-white rounded-2xl shadow p-6">
    <table class="w-full text-left">
        <thead class="bg-purple-100">
            <tr>
                <th class="p-3">Task</th>
                <th class="p-3">Assigned To</th>
                <th class="p-3">Status</th>
                <th class="p-3">Due Date</th>
                <th class="p-3">Scheduled</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($project->tasks as $task)
                <tr class="border-t">
                    <td class="p-3">{{ $task->title }}</td>
                    <td class="p-3">
                        {{ $task->assignee->name ?? '-' }}
                    </td>
                    <td class="p-3 capitalize">
                        {{ str_replace('_', ' ', $task->status) }}
                    </td>
                    <td class="p-3 {{ $task->due_at && $task->due_at->isPast() ? 'text-red-600' : '' }}">
                        {{ optional($task->due_at)->format('M d, Y') ?? '-' }}
                    </td>
                    <td class="p-3">
                        {{ optional($task->scheduled_at)->format('M d, Y') ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-3 text-center text-gray-500">
                        No tasks yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <h3 class="text-lg font-semibold text-purple-700 mt-10 mb-4">
    Approved & Posted Outputs (Archive)
</h3>

@php
    $archivedTasks = $project->tasks
        ->whereIn('status', [
            \App\Models\Task::STATUS_APPROVED,
            \App\Models\Task::STATUS_POSTED
        ]);
@endphp

@forelse($archivedTasks as $task)

    <div class="bg-white border border-purple-100 rounded-2xl p-5 mb-5 shadow-sm">

        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="font-semibold text-gray-800">
                    {{ $task->title }}
                </p>
                <p class="text-xs text-gray-500">
                    Creative: {{ $task->assignee->name ?? '-' }}
                </p>
            </div>

            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                {{ ucfirst($task->status) }}
            </span>
        </div>

        @if($task->assets->count() > 0)
            <div class="space-y-1">
                @foreach($task->assets as $asset)
                    <a href="{{ asset('storage/'.$asset->file_path) }}"
                       target="_blank"
                       class="text-purple-600 underline text-sm block">
                        {{ $asset->original_name }}
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400">
                No uploaded files found.
            </p>
        @endif

    </div>

@empty

    <p class="text-sm text-gray-400">
        No approved or posted outputs yet.
    </p>

@endforelse
</div>



</x-layouts.app-purple>