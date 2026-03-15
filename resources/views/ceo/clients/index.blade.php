<x-layouts.app-purple title="Clients">

<div class="bg-white rounded-2xl shadow p-6">
    <table class="w-full text-left">
        <thead class="bg-purple-100">
            <tr>
                <th class="p-3">Client</th>
                <th class="p-3">Email</th>
                <th class="p-3">Projects</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr class="border-t">
                    <td class="p-3">{{ $client->name }}</td>
                    <td class="p-3">{{ $client->email }}</td>
                    <td class="p-3">{{ $client->projects_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</x-layouts.app-purple>