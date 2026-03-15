<x-layouts.app-purple title="Edit Task">

    <h2 class="text-2xl font-bold text-purple-700 mb-6">
        Edit Content Item
    </h2>

    <div class="bg-white rounded-2xl shadow p-6">

        <form method="POST" action="{{ route('smm.tasks.update', $task) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <input 
                    name="title"
                    value="{{ old('title', $task->title) }}"
                    required
                    class="border p-2 rounded-xl"
                >

                <select name="platform" required class="border p-2 rounded-xl">
                    <option value="">Platform</option>
                    <option value="Facebook" {{ $task->platform == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Instagram" {{ $task->platform == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="TikTok" {{ $task->platform == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                </select>

                <input 
                    type="datetime-local"
                    name="scheduled_at"
                    value="{{ optional($task->scheduled_at)->format('Y-m-d\TH:i') }}"
                    required
                    class="border p-2 rounded-xl"
                >

                <input 
                    type="datetime-local"
                    name="due_at"
                    value="{{ optional($task->due_at)->format('Y-m-d\TH:i') }}"
                    required
                    class="border p-2 rounded-xl"
                >

                <textarea 
                    name="description"
                    class="md:col-span-2 border p-2 rounded-xl"
                >{{ old('description', $task->description) }}</textarea>

                <input 
                    type="url"
                    name="inspo_link"
                    value="{{ old('inspo_link', $task->inspo_link) }}"
                    class="md:col-span-2 border p-2 rounded-xl"
                >

            </div>

            <div class="mt-6 flex gap-3">
                <button class="px-5 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700">
                    Update Task
                </button>

                <a href="{{ route('smm.calendar', $task->project_id) }}"
                   class="px-5 py-2 bg-gray-300 text-gray-700 rounded-xl">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</x-layouts.app-purple>