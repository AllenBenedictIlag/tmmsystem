<x-layouts.app-purple title="CORE Dashboard">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-purple-500">Total Projects</p>
            <p class="text-3xl font-bold text-purple-700 mt-2">{{ $projectsCount }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-purple-500">Active Projects</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $activeProjects }}
            </p>
        </div>


        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-purple-500">Planning Overdue (No Calendar Yet)</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $planningOverdue }}</p>
            <p class="text-xs text-purple-500 mt-2">Based on planning_due_at + no tasks created yet.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-purple-500">Tasks Snapshot</p>
            <div class="mt-3 space-y-1 text-sm">
               <div class="flex justify-between"><span>Assigned</span><span class="font-semibold">{{ $tasksByStatus['assigned'] }}</span></div>
<div class="flex justify-between"><span>In Progress</span><span class="font-semibold">{{ $tasksByStatus['in_progress'] }}</span></div>
<div class="flex justify-between"><span>Submitted</span><span class="font-semibold">{{ $tasksByStatus['submitted'] }}</span></div>
<div class="flex justify-between"><span>Approved</span><span class="font-semibold">{{ $tasksByStatus['approved'] }}</span></div>
<div class="flex justify-between"><span>Rejected</span><span class="font-semibold">{{ $tasksByStatus['rejected'] }}</span></div>
<div class="flex justify-between"><span>Posted</span><span class="font-semibold">{{ $tasksByStatus['posted'] }}</span></div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a class="bg-white rounded-2xl shadow p-6 hover:bg-purple-50 transition" href="{{ route('core.clients.index') }}">
            <div class="font-bold text-purple-700">Clients</div>
            <div class="text-sm text-purple-500 mt-1">Manage clients</div>
        </a>

        <a class="bg-white rounded-2xl shadow p-6 hover:bg-purple-50 transition" href="{{ route('core.contracts.index') }}">
            <div class="font-bold text-purple-700">Contracts</div>
            <div class="text-sm text-purple-500 mt-1">Upload draft + signed PDFs</div>
        </a>

        <a class="bg-white rounded-2xl shadow p-6 hover:bg-purple-50 transition" href="{{ route('core.projects.index') }}">
            <div class="font-bold text-purple-700">Projects</div>
            <div class="text-sm text-purple-500 mt-1">Assign SMM + set planning_due_at</div>
        </a>
    </div>

</x-layouts.app-purple>
