<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('CEO')) {
            $records = AttendanceRecord::with('user')
                ->latest()
                ->paginate(20);
        } else {
            $records = AttendanceRecord::where('user_id', $user->id)
                ->latest()
                ->paginate(20);
        }

        return view('attendance.index', compact('records'));
    }

    /*
    |--------------------------------------------------------------------------
    | TIME IN
    |--------------------------------------------------------------------------
    */

    public function timeIn()
    {
        $user = Auth::user();

        // Always use server Philippine time
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        $record = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($record && $record->time_in) {
            return back()->with('error', 'You have already timed in today.');
        }

        if (!$record) {
            $record = new AttendanceRecord();
            $record->user_id = $user->id;
            $record->date = $today;
        }

        $record->time_in = $now;

        /*
        |--------------------------------------------------------------------------
        | ABNORMAL ATTENDANCE DETECTION
        |--------------------------------------------------------------------------
        */

        $validStart = Carbon::createFromTime(6, 0, 0, 'Asia/Manila');
        $validEnd = Carbon::createFromTime(23, 0, 0, 'Asia/Manila');

        if ($now->lt($validStart) || $now->gt($validEnd)) {
            $record->status = 'Abnormal';
            $record->late_minutes = 0;

            $record->save();

            return back()->with('warning', 'Attendance recorded but flagged as abnormal.');
        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL ATTENDANCE CLASSIFICATION
        |--------------------------------------------------------------------------
        */

        $officialStart = Carbon::createFromTime(10, 0, 0, 'Asia/Manila');
        $gracePeriod = Carbon::createFromTime(10, 15, 0, 'Asia/Manila');

        if ($now->lt($officialStart)) {
            $record->status = 'Early';
            $record->late_minutes = 0;
        } elseif ($now->between($officialStart, $gracePeriod)) {
            $record->status = 'On Time';
            $record->late_minutes = 0;
        } else {
            $record->status = 'Late';
            $record->late_minutes = $gracePeriod->diffInMinutes($now);
        }

        $record->save();

        return back()->with('success', 'Time in recorded successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | BREAK START
    |--------------------------------------------------------------------------
    */

    public function breakStart()
    {
        $user = Auth::user();

        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        $record = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$record || !$record->time_in) {
            return back()->with('error', 'Please Time In first.');
        }

        if ($record->break_start) {
            return back()->with('error', 'Break already started.');
        }

        if ($record->time_out) {
            return back()->with('error', 'You already timed out.');
        }

        $record->break_start = $now;
        $record->save();

        return back()->with('success', 'Break started.');
    }

    /*
    |--------------------------------------------------------------------------
    | BREAK END
    |--------------------------------------------------------------------------
    */

    public function breakEnd()
    {
        $user = Auth::user();

        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        $record = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$record || !$record->time_in) {
            return back()->with('error', 'Please Time In first.');
        }

        if (!$record->break_start) {
            return back()->with('error', 'Break has not started.');
        }

        if ($record->break_end) {
            return back()->with('error', 'Break already ended.');
        }

        $record->break_end = $now;
        $record->save();

        return back()->with('success', 'Break ended successfully.');
    }

public function timeOut()
{
    $user = Auth::user();

    $now = Carbon::now('Asia/Manila');
    $today = $now->toDateString();

    $record = AttendanceRecord::where('user_id', $user->id)
        ->where('date', $today)
        ->first();

    if (!$record || !$record->time_in) {
        return back()->with('error', 'You must Time In first.');
    }

    if ($record->time_out) {
        return back()->with('error', 'You have already timed out.');
    }

    $record->time_out = $now;

    $officialStart = Carbon::createFromTime(10, 0, 0, 'Asia/Manila');
    $officialEnd   = Carbon::createFromTime(19, 0, 0, 'Asia/Manila');

    $timeIn = Carbon::parse($record->time_in);

    /*
    |--------------------------------------------------------------------------
    | DO NOT OVERRIDE ABNORMAL STATUS
    |--------------------------------------------------------------------------
    */

    if ($record->status !== 'Abnormal') {

        /*
        |--------------------------------------------------------------
        | Only allow overtime if employee started within working hours
        |--------------------------------------------------------------
        */

        if ($timeIn->between($officialStart, $officialEnd)) {

            if ($now->gt($officialEnd)) {

                $record->status = 'Overtime';
                $record->overtime_minutes = $officialEnd->diffInMinutes($now);

            } elseif ($now->lt($officialEnd)) {

                $record->status = 'Early Out';

            }

        }

    }

    $record->save();

    return back()->with('success', 'Time out recorded successfully.');
}
}
