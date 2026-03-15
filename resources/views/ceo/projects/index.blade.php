<x-layouts.app-purple title="Projects">

    <div class="bg-white rounded-2xl shadow p-6">
        <table class="w-full text-left">
            <thead class="bg-purple-100">
                <tr>
                    <th class="p-3">Project</th>
                    <th class="p-3">Client</th>
                    <th class="p-3">Progress</th>
                    <th class="p-3">Overdue Tasks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr class="border-t">

                    <td class="p-3">
                        <a href="{{ route('ceo.projects.show', $project) }}" class="text-purple-700 font-semibold hover:underline">
                            {{ $project->name }}
                        </a>
                    </td>

                    <td class="p-3">{{ $project->client->name ?? '-' }}</td>

                    <td class="p-3">
                        {{ $project->progress }}%
                    </td>

                    <td class="p-3 text-red-600">
                        {{ $project->overdue }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.app-purple>
