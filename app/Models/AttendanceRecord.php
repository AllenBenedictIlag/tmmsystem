<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
   protected $fillable = [
    'user_id',
    'date',
    'time_in',
    'break_start',
    'break_end',
    'time_out',
    'late_minutes',
    'overtime_minutes',
    'status'
];

public function user()
{
    return $this->belongsTo(User::class);
}
}
