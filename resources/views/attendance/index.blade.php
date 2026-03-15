<x-layouts.app-purple title="Attendance">

<div class="space-y-4">

<table class="w-full text-sm">

<thead class="text-left text-[#7f6ed6]">
<tr>
<th>Date</th>
<th>User</th>
<th>Time In</th>
<th>Break Start</th>
<th>Break End</th>
<th>Break Duration</th>
<th>Time Out</th>
<th>Status</th>
</tr>
</thead>

<tbody class="divide-y">

@foreach($records as $record)

@php
$breakDuration = '-';

if($record->break_start && $record->break_end){
$start = \Carbon\Carbon::parse($record->break_start);
$end = \Carbon\Carbon::parse($record->break_end);
$breakDuration = $start->diffInMinutes($end) . ' min';
}
@endphp

<tr>

<td>{{ $record->date }}</td>

<td>{{ $record->user->name }}</td>

<td>
{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : '-' }}
</td>

<td>
{{ $record->break_start ? \Carbon\Carbon::parse($record->break_start)->format('h:i A') : '-' }}
</td>

<td>
{{ $record->break_end ? \Carbon\Carbon::parse($record->break_end)->format('h:i A') : '-' }}
</td>

<td>
{{ $breakDuration }}
</td>

<td>
{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : '-' }}
</td>

<td>

<span class="
@if($record->status == 'On Time') text-green-600
@elseif($record->status == 'Late') text-red-500
@elseif($record->status == 'Early') text-blue-500
@elseif($record->status == 'Early Out') text-orange-500
@elseif($record->status == 'Overtime') text-purple-500
@elseif($record->status == 'Abnormal') text-yellow-500
@endif
font-semibold">

{{ $record->status }}

</span>

</td>

</tr>

@endforeach

</tbody>

</table>

{{ $records->links() }}

</div>

</x-layouts.app-purple>