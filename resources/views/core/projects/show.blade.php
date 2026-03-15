<x-layouts.app-purple title="Project Details">

<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="text-xs text-purple-500">Project</div>
            <div class="text-2xl font-bold text-purple-800">{{ $project->name }}</div>
            <div class="text-sm text-purple-600 mt-1">
                Client: <span class="font-semibold">{{ $project->client?->name }}</span>
                • SMM: <span class="font-semibold">{{ $project->smm?->name }}</span>
            </div>
            <div class="text-sm text-purple-600 mt-1">
                Planning Due: <span class="font-semibold">{{ optional($project->planning_due_at)->format('M d, Y h:i A') }}</span>
            </div>
        </div>

        <a href="{{ route('core.projects.edit',$project) }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">Edit</a>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 rounded-2xl border border-purple-100">
            <div class="font-semibold text-purple-700">Contract</div>
            @if($project->contract)
                <div class="text-sm text-purple-600">Contract #{{ $project->contract->id }} • {{ $project->contract->signed_pdf_path ? 'SIGNED' : 'DRAFT ONLY' }}</div>
                <a class="text-purple-600 hover:underline text-sm" href="{{ route('core.contracts.show',$project->contract) }}">Open contract</a>
            @else
                <div class="text-sm text-red-500 font-semibold">No contract linked</div>
            @endif
        </div>

        <div class="p-4 rounded-2xl border border-purple-100">
            <div class="font-semibold text-purple-700">Content Calendar</div>
            <div class="text-sm text-purple-600">
                Tasks count: <span class="font-semibold">{{ $project->tasks->count() }}</span>
            </div>
            <div class="text-xs text-purple-500 mt-2">
                Visible to all roles; editing will be SMM-only (next step).
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a class="text-purple-600 hover:underline" href="{{ route('core.projects.index') }}">Back</a>
    </div>
</div>

</x-layouts.app-purple>