<?php

namespace App\Http\Controllers\SMM;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = auth()->user();

        $projects = Project::where('assigned_smm_id', $user->id)
            ->with(['client', 'tasks'])
            ->get();

        $clients = $projects->pluck('client')->unique('id');

        $totalPosts = Task::whereHas('project', function ($q) use ($user) {
            $q->where('assigned_smm_id', $user->id);
        })->count();

        $overdue = Task::whereHas('project', function ($q) use ($user) {
            $q->where('assigned_smm_id', $user->id);
        })->overdueCreative()->count();

        $readyToPost = Task::whereHas('project', function ($q) use ($user) {
            $q->where('assigned_smm_id', $user->id);
        })->where('status', Task::STATUS_APPROVED)->count();

        $pendingApprovals = Task::whereHas('project', function ($q) use ($user) {
            $q->where('assigned_smm_id', $user->id);
        })->where('status', Task::STATUS_SUBMITTED)->count();

        return view('smm.dashboard', compact(
            'projects',
            'clients',
            'totalPosts',
            'overdue',
            'readyToPost',
            'pendingApprovals'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW CALENDAR
    |--------------------------------------------------------------------------
    */

    public function calendar(Project $project)
    {
        $this->ensureSmmOwnsProject($project);

        $tasks = $project->tasks()->with('assets')->latest()->get();

        $creatives = User::role('CREATIVES')->get();

        return view('smm.calendar', compact(
            'project',
            'tasks',
            'creatives'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ADD TASK TO CALENDAR (DRAFT ONLY)
    |--------------------------------------------------------------------------
    */

    public function storeTask(Request $request, Project $project)
    {
        $this->ensureSmmOwnsProject($project);
        $this->ensureCalendarEditable($project);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'platform'     => 'required|string',
            'scheduled_at' => 'required',
            'due_at'       => 'required',
            'description'  => 'nullable|string',
            'inspo_link'   => 'nullable|url',
        ]);

        $scheduled = Carbon::createFromFormat('Y-m-d\TH:i', $data['scheduled_at']);
        $due       = Carbon::createFromFormat('Y-m-d\TH:i', $data['due_at']);

        if ($scheduled->lt(now())) {
            return back()->withErrors([
                'scheduled_at' => 'Posting date cannot be in the past.'
            ]);
        }

        if ($due->lt(now())) {
            return back()->withErrors([
                'due_at' => 'Deadline cannot be in the past.'
            ]);
        }

        if ($due->gte($scheduled)) {
            return back()->withErrors([
                'due_at' => 'Creative deadline must be BEFORE posting date.'
            ]);
        }

        Task::create([
            'project_id'   => $project->id,
            'title'        => $data['title'],
            'platform'     => $data['platform'],
            'scheduled_at' => $scheduled,
            'due_at'       => $due,
            'description'  => $data['description'] ?? null,
            'inspo_link'   => $data['inspo_link'] ?? null,
            'created_by'   => auth()->id(),
            'assigned_to'  => null,
            'status'       => Task::STATUS_DRAFT,
        ]);

        return back()->with('success', 'Content added to calendar (Draft).');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT TASK
    |--------------------------------------------------------------------------
    */

    public function edit(Task $task)
    {
        $this->ensureSmmOwnsProject($task->project);
        $this->ensureCalendarEditable($task->project);

        return view('smm.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->ensureSmmOwnsProject($task->project);
        $this->ensureCalendarEditable($task->project);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'platform'     => 'required|string',
            'scheduled_at' => 'required|date',
            'due_at'       => 'required|date',
            'description'  => 'nullable|string',
            'inspo_link'   => 'nullable|url',
        ]);
        $task->update($data);

        return redirect()
            ->route('smm.calendar', $task->project_id)
            ->with('success', 'Task updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TASK (ONLY IF EDITABLE)
    |--------------------------------------------------------------------------
    */

    public function destroy(Task $task)
    {
        $this->ensureSmmOwnsProject($task->project);
        $this->ensureCalendarEditable($task->project);

        $task->delete();

        return back()->with('success', 'Task deleted.');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT CALENDAR TO CEO
    |--------------------------------------------------------------------------
    */

    public function submitCalendar(Project $project)
    {
        $this->ensureSmmOwnsProject($project);

        if (!in_array($project->calendar_status, [
            Project::CALENDAR_DRAFT,
            Project::CALENDAR_REJECTED
        ])) {
            abort(403, 'Calendar already submitted or approved.');
        }

        if ($project->tasks()->count() === 0) {
            return back()->with('error', 'Add at least 1 content item before submitting.');
        }

        $project->update([
            'calendar_status' => Project::CALENDAR_SUBMITTED
        ]);

        return back()->with('success', 'Calendar submitted to CEO.');
    }




    public function reviewTask(Request $request, Task $task)
    {
        $this->ensureSmmOwnsProject($task->project);

        if ($task->status !== Task::STATUS_AWAITING_SMM) {
            abort(403, 'Task is not awaiting SMM review.');
        }

        if ($request->action === 'approve') {
            $task->update([
                'status' => Task::STATUS_SUBMITTED
            ]);

            return back()->with('success', 'Task forwarded to CEO.');
        }

        if ($request->action === 'reject') {

            $request->validate([
                'rejection_reason' => 'required|string|max:1000'
            ]);

            $task->update([
                'status' => Task::STATUS_REJECTED,
                'rejection_reason' => $request->rejection_reason
            ]);

            return back()->with('success', 'Task returned to Creative.');
        }

        abort(400);
    }
    /*
    |--------------------------------------------------------------------------
    | ASSIGN CREATIVE (ONLY AFTER CEO APPROVES CALENDAR)
    |--------------------------------------------------------------------------
    */

    public function assignCreative(Request $request, Task $task)
    {
        $this->ensureSmmOwnsProject($task->project);
        $this->ensureCalendarApproved($task->project);

        $data = $request->validate([
            'assigned_to' => 'required|exists:users,id',

            'due_at' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . $task->project->end_date,
            ],
        ]);

        $task->update([
            'assigned_to' => $data['assigned_to'],
            'due_at'      => $data['due_at'],
            'status'      => Task::STATUS_ASSIGNED,
        ]);

        return back()->with('success', 'Task assigned to creative.');
    }

    /*
    |--------------------------------------------------------------------------
    | MARK AS POSTED (ONLY AFTER CEO APPROVED TASK)
    |--------------------------------------------------------------------------
    */

public function markPosted(Task $task)
{
    $this->ensureSmmOwnsProject($task->project);

    // Task must be CEO approved first
    if ($task->status !== Task::STATUS_APPROVED) {
        abort(403, 'Task must be approved by CEO before posting.');
    }

    // Prevent posting before scheduled date
    if ($task->scheduled_at && $task->scheduled_at->isFuture()) {
        return back()->with(
            'error',
            'This content cannot be posted yet. Scheduled posting date has not been reached.'
        );
    }

    $task->update([
        'status'       => Task::STATUS_POSTED,
        'posted_by'    => auth()->id(),
        'completed_at' => now(),
    ]);

    return back()->with('success', 'Task marked as posted.');
}
    /*
    |--------------------------------------------------------------------------
    | GUARDS
    |--------------------------------------------------------------------------
    */

    private function ensureSmmOwnsProject(Project $project): void
    {
        abort_if($project->assigned_smm_id !== auth()->id(), 403);
    }

    private function ensureCalendarEditable(Project $project): void
    {
        if (!in_array($project->calendar_status, [
            Project::CALENDAR_DRAFT,
            Project::CALENDAR_REJECTED,
            null
        ])) {
            abort(403, 'Calendar is locked (submitted/approved).');
        }
    }

    private function ensureCalendarApproved(Project $project): void
    {
        if ($project->calendar_status !== Project::CALENDAR_APPROVED) {
            abort(403, 'Calendar is not approved by CEO.');
        }
    }
}
