<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Core\ClientController;
use App\Http\Controllers\Core\ContractController;
use App\Http\Controllers\Core\ProjectController;
use App\Http\Controllers\CEO\ApprovalController;
use App\Http\Controllers\Creative\CreativeTaskController;
use App\Http\Controllers\SMM\PostingController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ADMIN'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/users', UserManagementController::class);

        Route::get('/audit-logs', [AuditController::class, 'index'])
            ->name('audit.index');
    });

/*
|--------------------------------------------------------------------------
| CORE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:CORE'])
    ->prefix('core')
    ->name('core.')
    ->group(function () {

        Route::get('/dashboard', [ProjectController::class, 'dashboard'])->name('dashboard');
        Route::resource('/tasks', \App\Http\Controllers\Core\CoreTaskController::class);
        Route::resource('/clients', ClientController::class);
        Route::resource('/contracts', ContractController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('/contracts/{contract}/upload-signed', [ContractController::class, 'uploadSigned'])
            ->name('contracts.uploadSigned');
        Route::resource('/projects', ProjectController::class);
    });
/*
|--------------------------------------------------------------------------
| CEO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:CEO'])
    ->prefix('ceo')
    ->name('ceo.')
    ->group(function () {

        Route::get('/dashboard', [ApprovalController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/approvals', [ApprovalController::class, 'index'])
            ->name('approvals.index');

        Route::post('/tasks/{task}/approve', [ApprovalController::class, 'approve'])
            ->name('tasks.approve');

        Route::post('/tasks/{task}/reject', [ApprovalController::class, 'reject'])
            ->name('tasks.reject');

        Route::patch(
            '/tasks/{task}/deadline',
            [ApprovalController::class, 'updateDeadline']
        )->name('tasks.updateDeadline');

        Route::get('/tasks/{task}', [ApprovalController::class, 'show'])
            ->name('tasks.show');

        Route::get(
            '/clients',
            [\App\Http\Controllers\CEO\CEODashboardController::class, 'clients']
        )->name('clients.index');

        Route::get(
            '/projects',
            [\App\Http\Controllers\CEO\CEODashboardController::class, 'projects']
        )->name('projects.index');

        Route::get(
            '/projects/{project}',
            [\App\Http\Controllers\CEO\CEODashboardController::class, 'showProject']
        )->name('projects.show');

        Route::get(
            '/performance',
            [\App\Http\Controllers\CEO\CEODashboardController::class, 'performance']
        )->name('performance.index');

        Route::get('/calendar-approvals', [ApprovalController::class, 'calendarApprovals'])
            ->name('calendar.approvals');

        Route::post('/calendar/{project}/approve', [ApprovalController::class, 'approveCalendar'])
            ->name('calendar.approve');

        Route::post('/calendar/{project}/reject', [ApprovalController::class, 'rejectCalendar'])
            ->name('calendar.reject');
    });

/*
|--------------------------------------------------------------------------
| SMM
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:SOCIAL_MEDIA_MANAGER'])
    ->prefix('smm')
    ->name('smm.')
    ->group(function () {

        Route::get('/dashboard', [PostingController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/projects/{project}/calendar', [PostingController::class, 'calendar'])
            ->name('calendar');

        Route::post('/projects/{project}/tasks', [PostingController::class, 'storeTask'])
            ->name('tasks.store');

      

            Route::post('/tasks/{task}/posted', [PostingController::class, 'markPosted'])
    ->name('tasks.posted');
            
        Route::get('/tasks/{task}/edit', [PostingController::class, 'edit'])
            ->name('tasks.edit');

        Route::put('/tasks/{task}', [PostingController::class, 'update'])
            ->name('tasks.update');

        Route::delete('/tasks/{task}', [PostingController::class, 'destroy'])
            ->name('tasks.destroy');

        Route::post('/tasks/{task}/assign', [PostingController::class, 'assignCreative'])
            ->name('tasks.assign');

        Route::post('/tasks/{task}/review', [PostingController::class, 'reviewTask'])
            ->name('tasks.review');

        Route::post(
            '/projects/{project}/calendar/submit',
            [PostingController::class, 'submitCalendar']
        )->name('calendar.submit');
    });



/*
|--------------------------------------------------------------------------
| CREATIVE
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'role:CREATIVES'])
    ->prefix('creative')
    ->name('creative.')
    ->group(function () {

        Route::get('/dashboard', [CreativeTaskController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/tasks', [CreativeTaskController::class, 'index'])
            ->name('tasks.index');

        Route::get('/tasks/{task}', [CreativeTaskController::class, 'show'])
            ->name('tasks.show');

        Route::post('/tasks/{task}/upload', [CreativeTaskController::class, 'upload'])
            ->name('tasks.upload');

        Route::post('/tasks/{task}/submit', [CreativeTaskController::class, 'submit'])
            ->name('tasks.submit');

        Route::delete('/assets/{asset}', [CreativeTaskController::class, 'deleteAsset'])
            ->name('assets.delete');
        Route::post('/assets/{asset}/replace', [CreativeTaskController::class, 'replace'])
            ->name('assets.replace');
    });



/*
|--------------------------------------------------------------------------
| ATTENDANCE (GLOBAL)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('attendance')
    ->name('attendance.')
    ->group(function () {

        Route::get('/', [AttendanceController::class, 'index'])
            ->name('index');

        Route::post('/timein', [AttendanceController::class, 'timeIn'])
            ->name('timein');

        Route::post('/break-start', [AttendanceController::class, 'breakStart'])
            ->name('break.start');

        Route::post('/break-end', [AttendanceController::class, 'breakEnd'])
            ->name('break.end');

        Route::post('/timeout', [AttendanceController::class, 'timeOut'])
            ->name('timeout');

    });

    require __DIR__.'/auth.php';