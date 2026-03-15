<?php

namespace App\Http\Controllers\CEO;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

public function dashboard()
{
    $totalClients = Client::count();

    $activeProjects = Project::whereDate('start_date', '<=', now())
        ->where(function ($query) {
            $query->whereNull('end_date')
                ->orWhereDate('end_date', '>=', now());
        })
        ->count();

    $pendingApprovals = Task::where('status', Task::STATUS_SUBMITTED)->count();

    $overdueTasks = Task::overdueCreative()->count();

    $readyToPost = Task::where('status', Task::STATUS_APPROVED)
        ->whereNull('completed_at')
        ->count();

    $totalTasks = Task::count();

    // CONTRACT ANALYTICS
    $draftContracts = Contract::whereNull('signed_at')->count();
    $signedContracts = Contract::whereNotNull('signed_at')->count();

    // PROJECT PERFORMANCE
    $topProjects = Project::withCount([
        'tasks as completed_tasks' => function ($q) {
            $q->whereNotNull('completed_at');
        },
        'tasks as total_tasks'
    ])
    ->take(5)
    ->get();

    // TEAM PRODUCTIVITY
    $topCreatives = User::role('CREATIVES')
        ->withCount([
            'tasks as completed_tasks' => function ($q) {
                $q->whereNotNull('completed_at');
            }
        ])
        ->orderByDesc('completed_tasks')
        ->take(5)
        ->get();

    // RECENT CEO DECISIONS
    $recentApprovals = Task::where('status', Task::STATUS_APPROVED)
        ->latest('approved_at')
        ->take(5)
        ->get();

    return view('ceo.dashboard', compact(
        'totalClients',
        'activeProjects',
        'pendingApprovals',
        'overdueTasks',
        'readyToPost',
        'totalTasks',
        'draftContracts',
        'signedContracts',
        'topProjects',
        'topCreatives',
        'recentApprovals'
    ));
}
    /*
    |--------------------------------------------------------------------------
    | TASK APPROVAL LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $tasks = Task::with(['project', 'assignee', 'assets'])
            ->where('status', Task::STATUS_SUBMITTED)
            ->latest()
            ->get();

        return view('ceo.approvals.index', compact('tasks'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE TASK
    |--------------------------------------------------------------------------
    */

    public function approve(Request $request, Task $task)
    {
        // Only allow approval if actually submitted
        if ($task->status !== Task::STATUS_SUBMITTED) {

    return redirect()
        ->route('ceo.approvals.index')
        ->with('error','This task has already been reviewed.');

}

        $task->update([
            'status'       => Task::STATUS_APPROVED,
            'approved_at'  => now(),
            'due_at'       => $request->due_at ?? $task->due_at,
            'scheduled_at' => $request->scheduled_at ?? $task->scheduled_at,
        ]);

        return back()->with('success', 'Task approved.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT TASK
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, Task $task)
    {
        if ($task->status !== Task::STATUS_SUBMITTED) {
            return redirect()
    ->route('ceo.approvals.index')
    ->with('error','This task has already been reviewed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $task->update([
            'status'           => Task::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Task rejected.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DEADLINES (OPTIONAL CEO OVERRIDE)
    |--------------------------------------------------------------------------
    */

    public function updateDeadline(Request $request, Task $task)
    {
        $request->validate([
            'due_at'       => 'nullable|date',
            'scheduled_at' => 'nullable|date',
        ]);

        $task->update([
            'due_at'       => $request->due_at,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return back()->with('success', 'Deadline updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CALENDAR APPROVAL LIST
    |--------------------------------------------------------------------------
    */

    public function calendarApprovals()
    {
        $projects = Project::where(
    'calendar_status',
    Project::CALENDAR_SUBMITTED
)
->with(['client', 'smm', 'tasks'])
->get();

        return view('ceo.calendar-approvals', compact('projects'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE CALENDAR
    |--------------------------------------------------------------------------
    */

    public function approveCalendar(Project $project)
    {
        if ($project->calendar_status !== Project::CALENDAR_SUBMITTED) {
            abort(403, 'Calendar is not awaiting approval.');
        }

        $project->update([
            'calendar_status' => Project::CALENDAR_APPROVED
        ]);

        return back()->with('success', 'Calendar approved.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT CALENDAR
    |--------------------------------------------------------------------------
    */

    public function rejectCalendar(Project $project)
    {
        if ($project->calendar_status !== Project::CALENDAR_SUBMITTED) {
            abort(403, 'Calendar is not awaiting approval.');
        }

        $project->update([
            'calendar_status' => Project::CALENDAR_REJECTED
        ]);

        return back()->with('success', 'Calendar rejected.');
    }

    public function show(Task $task)
    {
        if ($task->status !== Task::STATUS_SUBMITTED) {
            abort(403, 'Task is not awaiting approval.');
        }

        $task->load(['project', 'assignee', 'assets']);

        return view('ceo.approvals.show', compact('task'));
    }
}
