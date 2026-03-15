<x-layouts.app-purple title="Contracts">

<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('core.contracts.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
            + Upload Draft
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-purple-100 text-purple-700">
            <tr>
                <th class="p-3 text-left">Client</th>
                <th class="p-3 text-left">Draft</th>
                <th class="p-3 text-left">Signed</th>
                <th class="p-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($contracts as $c)
                <tr class="hover:bg-purple-50">
                    <td class="p-3 font-semibold">{{ $c->client->name }}</td>
                    <td class="p-3">
                        @if($c->draft_pdf_path)
                            <a class="text-purple-600 hover:underline" href="{{ asset('storage/'.$c->draft_pdf_path) }}" target="_blank">View Draft</a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="p-3">
                        @if($c->signed_pdf_path)
                            <a class="text-purple-600 hover:underline" href="{{ asset('storage/'.$c->signed_pdf_path) }}" target="_blank">View Signed</a>
                        @else
                            <span class="text-xs text-red-500 font-semibold">Not Signed</span>
                        @endif
                    </td>
                    <td class="p-3 text-right">
                        <a class="text-purple-600 hover:underline" href="{{ route('core.contracts.show',$c) }}">Open</a>
                    </td>
                </tr>
            @empty
                <tr><td class="p-6 text-center text-purple-400" colspan="4">No contracts yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">{{ $contracts->links() }}</div>
</div>

</x-layouts.app-purple>