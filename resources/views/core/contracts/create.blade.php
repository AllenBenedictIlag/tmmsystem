<x-layouts.app-purple title="Upload Draft Contract">

    <div class="bg-white rounded-2xl shadow p-8 max-w-2xl">
        <form method="POST" action="{{ route('core.contracts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="text-sm text-purple-600">Client *</label>
                <select name="client_id" class="w-full mt-1 border border-purple-200 rounded-xl p-2" required>
                    <option value="">-- select client --</option>
                    @foreach($clients as $cl)
                    <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="text-sm text-purple-600">Draft PDF *</label>
                <input type="file" name="draft_pdf" accept=".pdf,.doc,.docx" class="w-full mt-1" required />
                <p class="text-xs text-purple-500 mt-1">PDF only, max 20MB</p>
            </div>

            <button class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">Upload</button>
            <a class="ml-2 text-purple-600 hover:underline" href="{{ route('core.contracts.index') }}">Cancel</a>
        </form>
    </div>

</x-layouts.app-purple>
