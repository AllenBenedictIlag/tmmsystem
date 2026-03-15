<x-layouts.app-purple title="Clients">

<!-- Section Container (matches CEO dashboard structure) -->
<div class="bg-white border border-[#a598eb]/20 rounded-2xl shadow-sm p-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <p class="text-sm text-gray-400 mt-1">
                Manage your active client records
            </p>
        </div>

        <a href="{{ route('core.clients.create') }}"
           class="px-5 py-2.5 rounded-lg bg-[#a598eb] text-white text-sm font-medium hover:bg-[#8e80dd] transition shadow-sm">
            + Add Client
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-xl border border-gray-100">
        <table class="w-full text-sm">
            <thead class="bg-[#f5f3ff] text-gray-600">
                <tr>
                    <th class="text-left px-6 py-4 font-medium">Name</th>
                    <th class="text-left px-6 py-4 font-medium">Email</th>
                    <th class="text-left px-6 py-4 font-medium">Contact</th>
                    <th class="text-left px-6 py-4 font-medium">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($clients as $client)
                    <tr class="hover:bg-[#faf8ff] transition">
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            {{ $client->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $client->email }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $client->contact ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <a href="{{ route('core.clients.edit', $client) }}"
                               class="text-[#6f63c8] font-medium hover:underline">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                            No clients found.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

</x-layouts.app-purple>