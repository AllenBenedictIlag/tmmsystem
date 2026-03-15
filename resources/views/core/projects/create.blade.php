<x-layouts.app-purple title="Create Project">

<div class="bg-white rounded-2xl shadow p-8 max-w-3xl">
    <form method="POST" action="{{ route('core.projects.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-purple-600">Project Name *</label>
                <input name="name" class="w-full mt-1 border border-purple-200 rounded-xl p-2" required />
            </div>

            <div>
                <label class="text-sm text-purple-600">Client *</label>
                <select name="client_id" class="w-full mt-1 border border-purple-200 rounded-xl p-2" required>
                    <option value="">-- select client --</option>
                    @foreach($clients as $cl)
                        <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-purple-600">Assign SMM *</label>
                <select name="assigned_smm_id" class="w-full mt-1 border border-purple-200 rounded-xl p-2" required>
                    <option value="">-- select SMM --</option>
                    @foreach($smms as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-purple-600">Content Planning Deadline (SMM) *</label>
                <input type="datetime-local" name="planning_due_at" class="w-full mt-1 border border-purple-200 rounded-xl p-2" required />
            </div>

            <div>
                <label class="text-sm text-purple-600">Campaign Start Date</label>
                <input type="date" name="start_date" class="w-full mt-1 border border-purple-200 rounded-xl p-2" />
            </div>

            <div>
                <label class="text-sm text-purple-600">Campaign End Date</label>
                <input type="date" name="end_date" class="w-full mt-1 border border-purple-200 rounded-xl p-2" />
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-purple-600">Contract (optional but recommended)</label>
                <select name="contract_id" class="w-full mt-1 border border-purple-200 rounded-xl p-2">
                    <option value="">-- none --</option>
                    @foreach($contracts as $ct)
                        <option value="{{ $ct->id }}">
                            #{{ $ct->id }} - {{ $ct->client?->name }} {{ $ct->signed_pdf_path ? '(SIGNED)' : '(DRAFT ONLY)' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-purple-600">Description</label>
                <textarea name="description" class="w-full mt-1 border border-purple-200 rounded-xl p-2" rows="3"></textarea>
            </div>
        </div>

        <button class="mt-6 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">Save</button>
        <a class="ml-2 text-purple-600 hover:underline" href="{{ route('core.projects.index') }}">Cancel</a>
    </form>
</div>

</x-layouts.app-purple>