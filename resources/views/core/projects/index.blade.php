<x-layouts.app-purple title="Projects">

<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('core.projects.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
            + New Project
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-purple-100 text-purple-700">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Client</th>
                <th class="p-3 text-left">SMM</th>
                <th class="p-3 text-left">Planning Due</th>
                <th class="p-3 text-left">Contract</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($projects as $p)
                <tr class="hover:bg-purple-50">
                    <td class="p-3 font-semibold">{{ $p->name }}</td>
                    <td class="p-3">{{ $p->client?->name }}</td>
                    <td class="p-3">{{ $p->smm?->name }}</td>
                    <td class="p-3">{{ optional($p->planning_due_at)->format('M d, Y h:i A') }}</td>
                    <td class="p-3">
                        @if($p->contract_id)
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Linked</span>
                        @else
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-100 text-red-700">None</span>
                        @endif
                    </td>
                    <td class="p-3 text-right space-x-2">
                        <a class="text-purple-600 hover:underline" href="{{ route('core.projects.show',$p) }}">View</a>
                        <a class="text-purple-600 hover:underline" href="{{ route('core.projects.edit',$p) }}">Edit</a>
                        <form class="inline" method="POST" action="{{ route('core.projects.destroy',$p) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline" onclick="return confirm('Delete this project?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="p-6 text-center text-purple-400" colspan="6">No projects yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">{{ $projects->links() }}</div>
</div>

</x-layouts.app-purple>