<x-layouts.app-purple title="User Management">

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-lg font-semibold text-purple-700">All Users</h2>

    <a href="{{ route('admin.users.create') }}"
       class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
        + Add User
    </a>
</div>

<div class="bg-white rounded-2xl shadow-md overflow-hidden">

    <table class="min-w-full text-sm">
        <thead class="bg-purple-100 text-purple-700">
            <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Email</th>
                <th class="px-6 py-3 text-left">Role</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-purple-100">
            @foreach($users as $user)
                <tr class="hover:bg-purple-50">
                    <td class="px-6 py-3">{{ $user->name }}</td>
                    <td class="px-6 py-3">{{ $user->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">
                            {{ $user->getRoleNames()->first() }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-purple-600 hover:underline">Edit</a>

                        <form action="{{ route('admin.users.destroy', $user) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

<div class="mt-6">
    {{ $users->links() }}
</div>

</x-layouts.app-purple>