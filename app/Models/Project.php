<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'assigned_smm_id',
        'planning_due_at',
        'start_date',
        'end_date',
        'contract_id',
        'name',
        'description',
        'created_by',
        'calendar_status',
    ];

    protected $casts = [
        'planning_due_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public const CALENDAR_DRAFT = 'draft';
    public const CALENDAR_SUBMITTED = 'submitted';
    public const CALENDAR_APPROVED = 'approved';
    public const CALENDAR_REJECTED = 'rejected';

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
    public function smm()
    {
        return $this->belongsTo(User::class, 'assigned_smm_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
