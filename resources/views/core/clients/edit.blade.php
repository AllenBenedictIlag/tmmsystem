<x-layouts.app-purple title="Edit Client">

<div class="bg-white rounded-2xl shadow p-8 max-w-2xl">
    <form method="POST" action="{{ route('core.clients.update', $client) }}">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-purple-600">Name *</label>
                <input name="name" value="{{ $client->name }}" class="w-full mt-1 border border-purple-200 rounded-xl p-2" required />
            </div>

            <div>
                <label class="text-sm text-purple-600">Company</label>
                <input name="company" value="{{ $client->company }}" class="w-full mt-1 border border-purple-200 rounded-xl p-2" />
            </div>

            <div>
                <label class="text-sm text-purple-600">Email</label>
                <input name="email" type="email" value="{{ $client->email }}" class="w-full mt-1 border border-purple-200 rounded-xl p-2" />
            </div>

            <div>
                <label class="text-sm text-purple-600">Phone</label>
                <input name="phone" value="{{ $client->phone }}" class="w-full mt-1 border border-purple-200 rounded-xl p-2" />
            </div>
        </div>

        <button class="mt-6 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">Update</button>
        <a class="ml-2 text-purple-600 hover:underline" href="{{ route('core.clients.index') }}">Cancel</a>
    </form>
</div>

</x-layouts.app-purple>