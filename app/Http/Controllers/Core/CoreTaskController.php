<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class CoreTaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project.client','assignee'])
            ->latest()
            ->paginate(10);

        return view('core.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $projects = Project::orderBy('id','desc')->get();
        $creatives = User::role('CREATIVES')->get();

        return view('core.tasks.create', compact('projects','creatives'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required','exists:projects,id'],
            'title' => ['required','string','max:255'],
            'assigned_to' => ['required','exists:users,id'],
            'due_at' => ['required','date'],
            'scheduled_at' => ['required','date'],
            'platform' => ['required','string']
        ]);

        Task::create($data + [
            'created_by' => auth()->id(),
            'status' => 'assigned',
        ]);

        return redirect()->route('core.tasks.index')
            ->with('success','Task created.');
    }
}