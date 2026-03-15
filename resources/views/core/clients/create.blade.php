<x-layouts.app-purple>

<h1 class="text-2xl font-bold text-purple-700 mb-6">
    Create Client
</h1>

<form method="POST" action="{{ route('core.clients.store') }}"
      class="bg-white p-6 rounded-2xl shadow space-y-4">
    @csrf

    <div>
        <label>Name *</label>
        <input type="text" name="name" required
               class="w-full border rounded-xl p-2">
    </div>

    <div>
        <label>Email</label>
        <input type="email" name="email"
               class="w-full border rounded-xl p-2">
    </div>

    <div>
        <label>Contact Person</label>
        <input type="text" name="contact_person"
               class="w-full border rounded-xl p-2">
    </div>

    <div>
        <label>Phone</label>
        <input type="text" name="phone"
               class="w-full border rounded-xl p-2">
    </div>

    <button class="bg-purple-600 text-white px-4 py-2 rounded-xl">
        Save Client
    </button>

</form>

</x-layouts.app-purple>