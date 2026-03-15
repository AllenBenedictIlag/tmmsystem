<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900">Edit User</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-900">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name', $user->name) }}"
                                   class="mt-2 w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"
                                   required />
                        </div>

                        <div>
<label class="text-sm font-medium text-gray-700">Username</label>
<input name="username" value="{{ old('username', $user->username) }}"
class="mt-2 w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"
required />
</div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="mt-2 w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"
                                   required />
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">New Password (optional)</label>
                            <input type="password" name="password"
                                   class="mt-2 w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500" />
                            <p class="mt-2 text-xs text-gray-500">Leave blank to keep current password.</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Role</label>
                            <select name="role"
                                    class="mt-2 w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"
                                    required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" @selected(old('role', $userRole)===$role->name)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}"
                               class="rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 hover:text-purple-700 transition">
                                Cancel
                            </a>
                            <button class="rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition">
                                Save
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>