<x-layouts.app-purple>

    <x-slot name="title">
        Task Details
    </x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-purple-700">
            {{ $task->title }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Project: {{ $task->project->name }}
        </p>
    </div>

    <!-- TASK INFO CARD -->
    <div class="bg-white rounded-2xl shadow border border-purple-100 p-6 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">

            <div>
                <p class="text-gray-500">Deadline</p>
                <p class="font-medium text-gray-800">
                    {{ optional($task->due_at)->format('M d, Y h:i A') }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Status</p>

                <span class="
                inline-block mt-1 px-3 py-1 text-xs rounded-full font-medium
                @if(strtolower($task->status) === 'in_progress') bg-yellow-100 text-yellow-700
                @elseif(strtolower($task->status) === 'completed') bg-green-100 text-green-700
                @elseif(strtolower($task->status) === 'assigned') bg-purple-100 text-purple-700
                @elseif(strtolower($task->status) === 'rejected') bg-red-100 text-red-600
                @elseif(strtolower($task->status) === 'submitted') bg-blue-100 text-blue-700
                @else bg-gray-100 text-gray-600
                @endif
            ">
                    {{ ucfirst(str_replace('_',' ',$task->status)) }}
                </span>
            </div>

        </div>

    </div>

    @if($task->inspo_link)
    <div class="mt-6 pt-4 border-t border-purple-100">
        <p class="text-sm text-gray-500">Inspiration / Reference</p>

        <a href="{{ $task->inspo_link }}" target="_blank" class="inline-block mt-1 text-purple-600 font-medium hover:underline break-all">
            View Reference
        </a>
    </div>
    @endif


    {{-- ===============================
    UPLOAD SECTION
================================ --}}
    @php
    $status = strtolower($task->status);
    $editableStatuses = ['assigned','in_progress','rejected'];
    @endphp

    @if(in_array($status, $editableStatuses))

    <div class="bg-white rounded-2xl shadow border border-purple-100 p-6 mb-8">

        <h3 class="text-lg font-semibold text-purple-700 mb-4">
            Upload Deliverable
        </h3>

        <form method="POST" action="{{ route('creative.tasks.upload', $task) }}" enctype="multipart/form-data">
            @csrf

            <input type="file" name="file" accept=".jpg,.jpeg,.png,.psd,.mp4" required class="block w-full mb-4 text-sm border border-purple-200 rounded-xl p-2">

            <div class="mb-3 text-sm text-gray-500">
                Allowed file types: JPG, JPEG, PNG, PSD, MP4, PDF<br>
                Maximum file size: 20MB
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-xl transition">
                Upload File
            </button>
        </form>

    </div>

    @endif


    {{-- ===============================
    UPLOADED FILES
================================ --}}
    <div class="bg-white rounded-2xl shadow border border-purple-100 p-6 mb-8">

        <h3 class="text-lg font-semibold text-purple-700 mb-4">
            Uploaded Files
        </h3>

        @forelse($task->assets as $asset)

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-3 border-b border-purple-50">

            <a href="{{ asset('storage/'.$asset->file_path) }}" target="_blank" class="text-purple-600 font-medium hover:underline">
                {{ $asset->original_name }}
            </a>

            <div class="flex items-center gap-3">

                <!-- DELETE -->
                <form method="POST" action="{{ route('creative.assets.delete', $asset) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 text-sm hover:underline">
                        Delete
                    </button>
                </form>

                <!-- REPLACE -->
                <form method="POST" action="{{ route('creative.assets.replace', $asset) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <input type="file" name="file" required class="text-sm border border-purple-200 rounded-lg p-1">
                    <button class="text-blue-600 text-sm hover:underline">
                        Replace
                    </button>
                </form>

            </div>

        </div>

        @empty
        <p class="text-gray-500 text-sm">
            No files uploaded yet.
        </p>
        @endforelse

    </div>


    {{-- ===============================
    SUBMIT TO CEO
================================ --}}
    @php
    $statusNorm = strtolower(trim($task->status ?? ''));
    $hasAssets = $task->assets()->count() > 0;
    $canSubmit = in_array($statusNorm, ['assigned','in_progress','rejected']) && $hasAssets;
    @endphp

    @if($canSubmit)
    <div class="mt-8 border-t pt-6">
        <form method="POST" action="{{ route('creative.tasks.submit', $task) }}">
            @csrf

            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow transition">
                Submit to CEO
            </button>

            @if(!$hasAssets)
            <p class="text-sm text-gray-500 mt-2">
                Upload at least one file before submitting.
            </p>
            @endif
        </form>
    </div>
    @endif

</x-layouts.app-purple>

