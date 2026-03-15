<x-layouts.app-purple title="Create User">

<div class="bg-white rounded-2xl shadow-md p-8 max-w-xl">

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <div class="mb-4">
        <label class="block text-sm text-purple-600 mb-1">Name</label>
        <input type="text" name="name"
               class="w-full border border-purple-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-400">
    </div>

    <div class="mb-4">
<label class="block text-sm text-purple-600 mb-1">Username</label>
<input type="text" name="username"
class="w-full border border-purple-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-400">
</div>

    <div class="mb-4">
        <label class="block text-sm text-purple-600 mb-1">Email</label>
        <input type="email" name="email"
               class="w-full border border-purple-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-400">
    </div>

    <div class="mb-4">
        <label class="block text-sm text-purple-600 mb-1">Password</label>
        <input type="password" name="password"
               class="w-full border border-purple-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-400">
    </div>

    <div class="mb-6">
        <label class="block text-sm text-purple-600 mb-1">Role</label>
        <select name="role"
                class="w-full border border-purple-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-purple-400">
            @foreach($roles as $role)
                <option value="{{ $role->name }}">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
        Create User
    </button>

</form>

</div>

</x-layouts.app-purple>