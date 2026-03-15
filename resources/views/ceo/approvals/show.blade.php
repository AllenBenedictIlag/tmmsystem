<x-layouts.app-purple title="Task Review">

<div class="bg-white rounded-2xl shadow p-8 space-y-6">

    <div>
        <h2 class="text-xl font-semibold text-purple-700">
            {{ $task->title }}
        </h2>
        <p class="text-sm text-gray-500">
            Project: {{ $task->project->name ?? '-' }}
        </p>
        <p class="text-sm text-gray-500">
            Creative: {{ $task->assignee->name ?? '-' }}
        </p>
    </div>

    {{-- Caption --}}
    @if($task->description)
    <div>
        <p class="text-sm text-gray-500">Caption</p>
        <div class="mt-1 p-4 bg-purple-50 rounded-xl text-sm">
            {{ $task->description }}
        </div>
    </div>
    @endif

    @if($task->inspo_link)
    <div class="mt-4">
        <p class="text-sm text-purple-600 font-semibold">Inspo / Reference</p>

        <a href="{{ $task->inspo_link }}"
           target="_blank"
           class="text-blue-600 underline break-all">
            {{ $task->inspo_link }}
        </a>
    </div>
@endif

    {{-- Uploaded Files --}}
    <div>
        <p class="text-sm text-gray-500 mb-2">Uploaded Output</p>

        @forelse($task->assets as $asset)
            <a href="{{ asset('storage/'.$asset->file_path) }}"
               target="_blank"
               class="text-blue-600 underline block text-sm">
                {{ $asset->original_name }}
            </a>
        @empty
            <p class="text-gray-400 text-sm">No files uploaded.</p>
        @endforelse
    </div>

    {{-- Deadlines --}}
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-gray-500">Deadline</p>
            <p>{{ optional($task->due_at)->format('M d, Y h:i A') }}</p>
        </div>
        <div>
            <p class="text-gray-500">Scheduled</p>
            <p>{{ optional($task->scheduled_at)->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    {{-- Approval Actions --}}
    <div class="flex gap-4 pt-6 border-t">

        <form method="POST" action="{{ route('ceo.tasks.approve', $task) }}">
            @csrf
            <button type="submit"
                    class="bg-green-600 text-white px-5 py-2 rounded-xl hover:bg-green-700">
                Approve
            </button>
        </form>

        <form method="POST" action="{{ route('ceo.tasks.reject', $task) }}" class="flex gap-2">
            @csrf
            <input type="text"
                   name="rejection_reason"
                   placeholder="Reason..."
                   required
                   class="border px-3 py-2 rounded-xl text-sm">

            <button type="submit"
                    class="bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700">
                Reject
            </button>
        </form>

    </div>

</div>

</x-layouts.app-purple>