<x-layouts.app-purple title="My Tasks">

    <p class="text-gray-600 mb-6">My Assigned Tasks</p>

    <div class="bg-white rounded-2xl shadow p-6">
        <table class="w-full text-sm">
            <thead class="bg-purple-100">
                <tr>
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-left">Project</th>
                    <th class="p-3 text-left">Deadline</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($tasks as $task)
                    <tr class="hover:bg-purple-50">
                        <td class="p-3 font-semibold">
                            <a href="{{ route('creative.tasks.show', $task) }}" class="text-purple-700 underline">
                                {{ $task->title }}
                            </a>
                        </td>

                        <td class="p-3">
                            {{ $task->project?->name ?? '—' }}
                        </td>

                        <td class="p-3">
                            @if($task->due_at)
                                {{ \Carbon\Carbon::parse($task->due_at)->format('M d, Y h:i A') }}
                            @else
                                —
                            @endif
                        </td>

                        <td class="p-3">
                            @php
                                $status = $task->status ?? '—';
                            @endphp

                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                {{ str_replace('_',' ', ucfirst($status)) }}
                            </span>
                        </td>

                    <td class="p-3 text-right">

    <div class="flex justify-end gap-2">

        <!-- VIEW BUTTON -->
        <a href="{{ route('creative.tasks.show', $task) }}"
           class="btn btn-soft">
            View
        </a>

        @php
            $canSubmit = in_array($task->status, [
                \App\Models\Task::STATUS_ASSIGNED,
                \App\Models\Task::STATUS_IN_PROGRESS,
                \App\Models\Task::STATUS_REJECTED,
            ]);

            $hasAssets = (($task->assets_count ?? 0) > 0);
        @endphp

        <!-- ONLY SHOW BUTTON IF ALLOWED -->
        @if($canSubmit)

            <form method="POST" action="{{ route('creative.tasks.submit', $task) }}">
                @csrf
                <button type="submit"
                        class="btn btn-success"
                        @disabled(!$hasAssets)
                        title="{{ $hasAssets ? '' : 'Upload at least one file to enable submission.' }}">
                    Submit to CEO
                </button>
            </form>

        @endif

    </div>

</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            No tasks assigned yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.app-purple>