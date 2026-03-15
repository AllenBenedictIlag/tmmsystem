<?php

namespace App\Http\Controllers\Creative;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CreativeTaskController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $tasks = Task::with('project')
            ->where('assigned_to', auth()->id())
            ->latest()
            ->get();

        return view('creative.dashboard', compact('tasks'));
    }

    public function index()
    {
        $tasks = Task::with('project')
            ->withCount('assets')
            ->where('assigned_to', auth()->id())
            ->latest()
            ->get();

        return view('creative.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        abort_unless($task->assigned_to === auth()->id(), 403);

        $task->load('project', 'assets');

        return view('creative.tasks.show', compact('task'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD FILE
    |--------------------------------------------------------------------------
    */
public function upload(Request $request, Task $task)
{
    abort_unless($task->assigned_to === auth()->id(), 403);

    if (!in_array($task->status, [
        Task::STATUS_ASSIGNED,
        Task::STATUS_REJECTED,
        Task::STATUS_IN_PROGRESS
    ])) {
        abort(403, 'Task is not editable.');
    }

    $request->validate([
        'file' => 'required|file|mimes:jpg,jpeg,png,psd,mp4,pdf|max:20480'
    ]);

    // FIX: define file before using it
    $file = $request->file('file');

    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

    $path = $file->storeAs(
        'task-assets',
        $filename,
        'public'
    );

    $task->assets()->create([
        'uploaded_by' => auth()->id(),
        'file_path' => $path,
        'original_name' => $file->getClientOriginalName(),
        'mime' => $file->getClientMimeType(),
        'size' => $file->getSize()
    ]);

    if ($task->status === Task::STATUS_ASSIGNED) {
        $task->update([
            'status' => Task::STATUS_IN_PROGRESS
        ]);
    }

    return back()->with('success','File uploaded successfully.');
}

    /*
    |--------------------------------------------------------------------------
    | SUBMIT TO CEO
    |--------------------------------------------------------------------------
    */

 public function submit(Task $task)
{
    if ($task->assigned_to !== auth()->id()) {
        abort(403);
    }

    // Prevent submission if task not started
    if (!in_array($task->status, [
        Task::STATUS_IN_PROGRESS,
        Task::STATUS_REJECTED
    ])) {
        return back()->with('error','You must start working on the task before submitting.');
    }

    if ($task->status === Task::STATUS_AWAITING_SMM) {
        return back()->with('error', 'Task already submitted.');
    }

    if ($task->assets()->count() === 0) {
        return back()->with('error', 'Upload at least one file before submitting.');
    }

    $task->update([
        'status' => Task::STATUS_AWAITING_SMM,
        'submitted_at' => now(),
        'rejection_reason' => null,
    ]);

    return redirect()
        ->route('creative.tasks.index')
        ->with('success', 'Task submitted to SMM for review.');
}
    /*
    |--------------------------------------------------------------------------
    | DELETE ASSET
    |--------------------------------------------------------------------------
    */

    public function deleteAsset(TaskAsset $asset)
    {
      abort_unless($asset->uploaded_by === auth()->id(), 403, 'Unauthorized file access.');

        Storage::disk('public')->delete($asset->file_path);

        $asset->delete();

        return back()->with('success', 'File deleted.');
    }

    /*
    |--------------------------------------------------------------------------
    | REPLACE ASSET
    |--------------------------------------------------------------------------
    */

    public function replace(Request $request, TaskAsset $asset)
    {
        abort_unless($asset->uploaded_by === auth()->id(), 403);

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,psd,mp4,pdf',
                'max:20480'
            ]
        ]);

        Storage::disk('public')->delete($asset->file_path);

        $file = $request->file('file');
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'task-assets',
            $filename,
            'public'
        );
    }
}
