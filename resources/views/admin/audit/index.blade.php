<x-layouts.app-purple title="Audit Trails">

{{-- FILTERS --}}
<div class="bg-white p-6 rounded-2xl shadow-md mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">

        <div>
            <label class="block text-sm text-purple-600 mb-1">User</label>
            <select name="user_id"
                class="border border-purple-200 rounded-xl px-3 py-2">
                <option value="">All</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}"
                        {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-purple-600 mb-1">Action</label>
            <select name="action"
                class="border border-purple-200 rounded-xl px-3 py-2">
                <option value="">All</option>
                <option value="created" {{ request('action')=='created'?'selected':'' }}>Created</option>
                <option value="updated" {{ request('action')=='updated'?'selected':'' }}>Updated</option>
                <option value="deleted" {{ request('action')=='deleted'?'selected':'' }}>Deleted</option>
            </select>
        </div>

        <button class="bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700">
            Filter
        </button>

    </form>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow-md overflow-hidden">

<table class="min-w-full text-sm">
    <thead class="bg-purple-100 text-purple-700">
        <tr>
            <th class="px-6 py-3 text-left">User</th>
            <th class="px-6 py-3 text-left">Action</th>
            <th class="px-6 py-3 text-left">Model</th>
            <th class="px-6 py-3 text-left">Time</th>
            <th class="px-6 py-3 text-right">Details</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-purple-100">
        @foreach($logs as $log)
            <tr class="hover:bg-purple-50">
                <td class="px-6 py-3">
                    {{ $log->user?->name ?? 'System' }}
                </td>

                <td class="px-6 py-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($log->action=='created') bg-green-100 text-green-700
                        @elseif($log->action=='updated') bg-yellow-100 text-yellow-700
                        @elseif($log->action=='deleted') bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($log->action) }}
                    </span>
                </td>

                <td class="px-6 py-3">
                    {{ class_basename($log->model_type) }}
                    #{{ $log->model_id }}
                </td>

                <td class="px-6 py-3">
                    {{ $log->created_at->format('M d, Y h:i A') }}
                </td>

                <td class="px-6 py-3 text-right">
                    <button onclick="toggleDetails({{ $log->id }})"
                        class="text-purple-600 hover:underline text-sm">
                        View
                    </button>
                </td>
            </tr>

            <tr id="details-{{ $log->id }}" class="hidden bg-purple-50">
                <td colspan="5" class="p-6 text-xs text-gray-700">

                    <div class="grid grid-cols-2 gap-6">

                        <div>
                            <p class="font-semibold text-purple-700 mb-2">Old Values</p>
                            <pre class="bg-white p-3 rounded-xl overflow-x-auto">
{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}
                            </pre>
                        </div>

                        <div>
                            <p class="font-semibold text-purple-700 mb-2">New Values</p>
                            <pre class="bg-white p-3 rounded-xl overflow-x-auto">
{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}
                            </pre>
                        </div>

                    </div>

                </td>
            </tr>

        @endforeach
    </tbody>
</table>

</div>

<div class="mt-6">
    {{ $logs->links() }}
</div>

<script>
function toggleDetails(id) {
    document.getElementById('details-'+id).classList.toggle('hidden');
}
</script>

</x-layouts.app-purple>