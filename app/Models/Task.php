<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    /*
    |--------------------------------------------------------------------------
    | WORKFLOW STATUSES
    |--------------------------------------------------------------------------
    */

    public const STATUS_ASSIGNED    = 'assigned';      // created by SMM
    public const STATUS_IN_PROGRESS = 'in_progress';   // creative working
    public const STATUS_AWAITING_SMM = 'awaiting_smm_review';
    public const STATUS_SUBMITTED   = 'submitted';     // submitted to CEO
    public const STATUS_APPROVED    = 'approved';      // CEO approved
    public const STATUS_REJECTED    = 'rejected';      // CEO rejected
    public const STATUS_POSTED      = 'posted';        // SMM posted
    public const STATUS_DRAFT       = 'draft';         // calendar item, not executable yet

    protected $fillable = [
        'project_id',

        // content info
        'title',
        'description', // this is caption/content body

        // assignment
        'assigned_to', // creative user id
        'created_by',  // SMM id
        'posted_by',   // SMM id when posted

        // scheduling & deadlines
        'due_at',        // creative deadline
        'scheduled_at',  // posting date

        // workflow timestamps
        'submitted_at',
        'approved_at',
        'completed_at',

        // platform info
        'platform',
         'inspo_link',
        // workflow
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'due_at'        => 'datetime',
        'scheduled_at'  => 'datetime',
        'submitted_at'  => 'datetime',
        'approved_at'   => 'datetime',
        'completed_at'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function assets()
    {
        return $this->hasMany(TaskAsset::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (Adviser Requirements)
    |--------------------------------------------------------------------------
    */

    // Creative overdue
    public function scopeOverdueCreative(Builder $query): Builder
    {
        return $query
            ->whereNotNull('due_at')
            ->whereIn('status', [
                self::STATUS_ASSIGNED,
                self::STATUS_IN_PROGRESS,
                self::STATUS_REJECTED
            ])
            ->where('due_at', '<', now());
    }

    // CEO approved after scheduled date (late approval)
    public function scopeLateApproval(Builder $query): Builder
    {
        return $query
            ->whereNotNull('approved_at')
            ->whereNotNull('scheduled_at')
            ->whereColumn('approved_at', '>', 'scheduled_at');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isEditableBySMM(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_ASSIGNED,
            self::STATUS_REJECTED
        ]);
    }

    public function isAwaitingApproval(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isReadyToPost(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->scheduled_at <= now();
    }


    public function setStatusAttribute($value): void
{
    if (is_null($value)) {
        $this->attributes['status'] = null;
        return;
    }

    $normalized = strtolower(trim((string) $value));
    $normalized = str_replace([' ', '-'], '_', $normalized);
    $normalized = preg_replace('/_+/', '_', $normalized);

    $this->attributes['status'] = $normalized;
}



public function getStatusLabel(): string
{
    return match ($this->status) {
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_ASSIGNED => 'Assigned to Creative',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_AWAITING_SMM => 'Awaiting SMM Review',
        self::STATUS_SUBMITTED => 'Pending CEO Approval',
        self::STATUS_APPROVED => 'Approved by CEO',
        self::STATUS_REJECTED => 'Returned for Revision',
        self::STATUS_POSTED => 'Posted',
        default => ucfirst(str_replace('_',' ',$this->status)),
    };
}
}
