<?php

namespace App\Http\Controllers\CEO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class CEODashboardController extends Controller
{
    public function clients()
    {
        $clients = Client::withCount('projects')->get();

        return view('ceo.clients.index', compact('clients'));
    }

    public function projects()
    {
        $projects = Project::with(['client', 'tasks'])->get();

        $projects = $projects->map(function ($project) {

            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks
                ->whereIn('status', [
                    Task::STATUS_APPROVED,
                    Task::STATUS_POSTED
                ])->count();

            $progress = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;

            $overdue = $project->tasks
                ->whereIn('status', [
                    Task::STATUS_ASSIGNED,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_REJECTED
                ])
                ->where('due_at', '<', now())
                ->count();

            $project->progress = $progress;
            $project->overdue = $overdue;

            return $project;
        });

        return view('ceo.projects.index', compact('projects'));
    }

    public function performance()
    {
        $today = now()->toDateString();

        // Task metrics
        $totalTasks = Task::count();

        $completedTasks = Task::whereNotNull('completed_at')->count();

        $pendingTasks = Task::whereNull('completed_at')->count();

        $taskCompletionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        // Projects
        $projects = Project::count();

        // Creatives
        $creatives = User::role('CREATIVES')->get();

        // Attendance metrics
        $attendanceToday = \App\Models\AttendanceRecord::whereDate('date', $today)->count();

        $lateToday = \App\Models\AttendanceRecord::whereDate('date', $today)
            ->where('status', 'Late')
            ->count();

        $abnormalToday = \App\Models\AttendanceRecord::whereDate('date', $today)
            ->where('status', 'Abnormal')
            ->count();

        $overtimeToday = \App\Models\AttendanceRecord::whereDate('date', $today)
            ->sum('overtime_minutes');

        // Top creatives productivity
        $topCreatives = User::role('CREATIVES')
            ->withCount([
                'tasks as completed_tasks' => function ($q) {
                    $q->whereNotNull('completed_at');
                }
            ])
            ->orderByDesc('completed_tasks')
            ->take(5)
            ->get();

       return view('ceo.performance.index', compact(
            'projects',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'taskCompletionRate',
            'creatives',
            'attendanceToday',
            'lateToday',
            'abnormalToday',
            'overtimeToday',
            'topCreatives'
        ));
    }
    public function showProject(Project $project)
    {
        $project->load(['client', 'smm', 'tasks.assignee', 'tasks.assets']);

        $totalTasks = $project->tasks->count();

        $completedTasks = $project->tasks
            ->whereIn('status', [
                \App\Models\Task::STATUS_APPROVED,
                \App\Models\Task::STATUS_POSTED
            ])->count();

        $progress = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        $overdue = $project->tasks
            ->whereIn('status', [
                \App\Models\Task::STATUS_ASSIGNED,
                \App\Models\Task::STATUS_IN_PROGRESS,
                \App\Models\Task::STATUS_REJECTED
            ])
            ->where('due_at', '<', now())
            ->count();

        return view('ceo.projects.show', compact(
            'project',
            'progress',
            'overdue',
            'totalTasks',
            'completedTasks'
        ));
    }

    public function approve(Task $task)
    {
        $task->update([
            'status' => Task::STATUS_APPROVED
        ]);

        return back()->with('success', 'Task approved.');
    }

    public function reject(Request $request, Task $task)
    {
        $request->validate([
            'feedback' => 'required|string'
        ]);

        $task->update([
            'status' => Task::STATUS_REJECTED,
            'feedback' => $request->feedback
        ]);

        return back()->with('success', 'Task rejected.');
    }
}
