<x-layouts.app-purple title="Admin Dashboard">

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm text-purple-500">Total Users</p>
        <p class="text-3xl font-bold text-purple-700">{{ \App\Models\User::count() }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm text-purple-500">CEO Accounts</p>
        <p class="text-3xl font-bold text-purple-700">
            {{ \App\Models\User::role('CEO')->count() }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm text-purple-500">CORE Accounts</p>
        <p class="text-3xl font-bold text-purple-700">
            {{ \App\Models\User::role('CORE')->count() }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6">
        <p class="text-sm text-purple-500">Creatives</p>
        <p class="text-3xl font-bold text-purple-700">
            {{ \App\Models\User::role('CREATIVES')->count() }}
        </p>
    </div>

</div>

<div class="mt-10 bg-white rounded-2xl shadow-md p-6">
    <h2 class="text-lg font-semibold text-purple-700 mb-4">Quick Actions</h2>

    <div class="flex gap-4">
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
            Manage Users
        </a>
    </div>
</div>

</x-layouts.app-purple>