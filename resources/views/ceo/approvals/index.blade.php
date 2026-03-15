<x-layouts.app-purple title="Approval Center">


    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">

        <table class="w-full text-sm">
            <thead class="bg-purple-100">
                <tr>
                    <th class="p-3 text-left">Task</th>
                    <th class="p-3 text-left">Project</th>
                    <th class="p-3 text-left">Creative</th>
                    <th class="p-3 text-left">Deadline</th>
                    <th class="p-3 text-left">Scheduled</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($tasks as $task)

                <tr>
                    <td class="p-3 font-semibold text-purple-700">
                        {{ $task->title }}
                    </td>

                    <td class="p-3">
                        {{ $task->project->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $task->assignee->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ optional($task->due_at)->format('M d Y H:i') }}
                    </td>

                    <td class="p-3">
                        {{ optional($task->scheduled_at)->format('M d Y H:i') }}
                    </td>

                    <td class="p-3 text-right">
<a href="{{ route('ceo.tasks.show', $task) }}"
   class="bg-purple-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-purple-700 transition">
    View
</a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="p-4 text-center text-purple-400">
                        No tasks pending approval.
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>

    </div>

</x-layouts.app-purple>
