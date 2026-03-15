<x-layouts.app-purple title="Calendar Approvals">



    <div class="bg-white rounded-2xl shadow p-6">

        @if($projects->isEmpty())
        <p class="text-gray-500">No calendars pending approval.</p>
        @else
        <table class="w-full text-sm">
            <thead class="bg-purple-100">
                <tr>
                    <th class="p-3 text-left">Project</th>
                    <th class="p-3 text-left">Client</th>
                    <th class="p-3 text-left">SMM</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($projects as $project)
                <tr class="hover:bg-purple-50">
                    <td class="p-3 font-semibold">{{ $project->name }}</td>
                    <td class="p-3">{{ $project->client->name ?? '-' }}</td>
                    <td class="p-3">{{ $project->smm->name ?? '-' }}</td>
                    <td class="p-3 flex gap-2">

                        <a href="{{ route('ceo.projects.show', $project) }}" class="bg-blue-600 text-white px-3 py-1 rounded-xl">
                            View Calendar
                        </a>
                        <form method="POST" action="{{ route('ceo.calendar.approve', $project) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-3 py-1 rounded-xl">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('ceo.calendar.reject', $project) }}">
                            @csrf
                            <button class="bg-red-600 text-white px-3 py-1 rounded-xl">
                                Reject
                            </button>
                        </form>

                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="p-4 bg-purple-50">

                        <p class="text-sm font-semibold text-purple-700 mb-3">
                            Content Calendar Items
                        </p>

                        @forelse($project->tasks as $task)
                        <div class="mb-3 p-3 bg-white rounded-xl border border-purple-100">

                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $task->title }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Platform: {{ $task->platform }}
                                    </p>

                                    @if($task->description)
                                    <p class="text-xs text-gray-600 mt-1">
                                        Caption: {{ $task->description }}
                                    </p>
                                    @endif

                                    @if($task->inspo_link)
                                    <a href="{{ $task->inspo_link }}" target="_blank" class="text-xs text-blue-600 underline mt-1 block">
                                        View Peg / Reference
                                    </a>
                                    @endif
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ optional($task->scheduled_at)->format('M d Y') }}
                                </div>
                            </div>

                        </div>
                        @empty
                        <p class="text-xs text-gray-400">
                            No calendar items found.
                        </p>
                        @endforelse

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    </div>

</x-layouts.app-purple>
