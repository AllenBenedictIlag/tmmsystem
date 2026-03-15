<x-layouts.app-purple title="Content Calendar Tasks">

<div class="bg-white rounded-2xl shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-purple-700">Scheduled Tasks</h2>

        <a href="{{ route('core.tasks.create') }}"
           class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            + Create Task
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-purple-100">
            <tr>
                <th class="p-3 text-left">Title</th>
                <th class="p-3 text-left">Client</th>
                <th class="p-3 text-left">Creative</th>
                <th class="p-3 text-left">Due</th>
                <th class="p-3 text-left">Schedule</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr class="border-t hover:bg-purple-50">
                    <td class="p-3 font-semibold text-purple-800">
                        {{ $task->title }}
                    </td>
                    <td class="p-3">
                        {{ $task->project?->client?->name ?? '-' }}
                    </td>
                    <td class="p-3">
                        {{ $task->assignee?->name ?? '-' }}
                    </td>
                    <td class="p-3">
                        {{ optional($task->due_at)->format('M d, Y h:i A') }}
                    </td>
                    <td class="p-3">
                        {{ optional($task->scheduled_at)->format('M d, Y h:i A') }}
                    </td>
                    <td class="p-3">
                        <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                            {{ $task->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-purple-400">
                        No tasks yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $tasks->links() }}
    </div>

</div>

</x-layouts.app-purple>