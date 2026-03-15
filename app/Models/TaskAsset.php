<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TaskAsset extends Model
{
    protected $fillable = [
        'task_id',
        'uploaded_by',
        'file_path',
        'original_name',
        'mime',
        'size'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}