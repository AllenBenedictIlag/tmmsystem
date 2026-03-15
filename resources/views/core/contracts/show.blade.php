<x-layouts.app-purple title="Contract Details">

<div class="bg-white rounded-2xl shadow p-6">
    <div class="mb-4">
        <div class="text-sm text-purple-500">Client</div>
        <div class="text-xl font-bold text-purple-800">{{ $contract->client->name }}</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 rounded-2xl border border-purple-100">
            <div class="font-semibold text-purple-700">Draft PDF</div>
            @if($contract->draft_pdf_path)
                <a class="text-purple-600 hover:underline" target="_blank" href="{{ asset('storage/'.$contract->draft_pdf_path) }}">Open Draft</a>
            @else
                <div class="text-sm text-purple-400">No draft uploaded.</div>
            @endif
        </div>

        <div class="p-4 rounded-2xl border border-purple-100">
            <div class="font-semibold text-purple-700">Signed PDF</div>
            @if($contract->signed_pdf_path)
                <a class="text-purple-600 hover:underline" target="_blank" href="{{ asset('storage/'.$contract->signed_pdf_path) }}">Open Signed</a>
                <div class="text-xs text-purple-500 mt-1">Signed at: {{ optional($contract->signed_at)->format('M d, Y h:i A') }}</div>
            @else
                <div class="text-sm text-red-500 font-semibold">Not signed yet.</div>

                <form class="mt-3" method="POST" action="{{ route('core.contracts.uploadSigned',$contract) }}" enctype="multipart/form-data">
                    @csrf
                    <label class="text-sm text-purple-600">Upload Signed PDF</label>
                    <input type="file" name="signed_pdf" accept="application/pdf" class="w-full mt-1" required />
                    <button class="mt-3 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">Upload Signed</button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a class="text-purple-600 hover:underline" href="{{ route('core.contracts.index') }}">Back</a>
    </div>
</div>

</x-layouts.app-purple>