<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
   public function dashboard()
{
    $projectsCount = Project::count();

    // Active projects (not yet ended)
    $activeProjects = Project::whereNull('end_date')
        ->orWhere('end_date','>',now())
        ->count();

    // planning overdue = now > planning_due_at and no tasks created for project
    $planningOverdue = Project::whereNotNull('planning_due_at')
        ->where('planning_due_at', '<', now())
        ->whereDoesntHave('tasks')
        ->count();

    // task workflow snapshot
    $tasksByStatus = [
        'assigned' => Task::where('status','assigned')->count(),
        'in_progress' => Task::where('status','in_progress')->count(),
        'submitted' => Task::where('status','submitted')->count(),
        'approved' => Task::where('status','approved')->count(),
        'rejected' => Task::where('status','rejected')->count(),
        'posted' => Task::where('status','posted')->count(),
    ];

    return view('core.dashboard', compact(
        'projectsCount',
        'activeProjects',
        'planningOverdue',
        'tasksByStatus'
    ));
}

    public function index()
    {
        $projects = Project::with(['client','contract','smm'])->latest()->paginate(10);
        return view('core.projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $contracts = Contract::with('client')->latest()->get();
        $smms = User::role('SOCIAL_MEDIA_MANAGER')->orderBy('name')->get();

        return view('core.projects.create', compact('clients','contracts','smms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'client_id' => ['required','exists:clients,id'],
            'contract_id' => ['nullable','exists:contracts,id'],
            'assigned_smm_id' => ['required','exists:users,id'],
            'planning_due_at' => ['required','date'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'description' => ['nullable','string'],
        ]);

        // Recommended gate: require signed contract before planning.
        // Uncomment if adviser requires strict gate:
        // if (empty($data['contract_id'])) {
        //     return back()->with('error','Contract must be linked before planning.')->withInput();
        // }

        Project::create($data + ['created_by' => auth()->id()]);

        return redirect()->route('core.projects.index')->with('success','Project created.');
    }

    public function show(Project $project)
    {
        $project->load(['client','contract','smm','tasks.assignee']);
        return view('core.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::orderBy('name')->get();
        $contracts = Contract::with('client')->latest()->get();
        $smms = User::role('SOCIAL_MEDIA_MANAGER')->orderBy('name')->get();

        return view('core.projects.edit', compact('project','clients','contracts','smms'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'client_id' => ['required','exists:clients,id'],
            'contract_id' => ['nullable','exists:contracts,id'],
            'assigned_smm_id' => ['required','exists:users,id'],
            'planning_due_at' => ['required','date'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'description' => ['nullable','string'],
        ]);

        $project->update($data);

        return redirect()->route('core.projects.index')->with('success','Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success','Project deleted.');
    }
}