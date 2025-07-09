@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h3 class="text-white">Subtask Details</h3>
    <table class="table table-bordered table-dark text-white">
        <tr><th>Title</th><td>{{ $subtask->title }}</td></tr>
        <tr><th>Description</th><td>{{ $subtask->description }}</td></tr>
        <tr><th>Assigned To</th><td>{{ $subtask->employee->name ?? 'N/A' }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($subtask->status ?? 'pending') }}</td></tr>
        <tr><th>Start Date</th><td>{{ $subtask->start_date ?? '-' }}</td></tr>
        <tr><th>Start Time</th><td>{{ $subtask->start_time ?? '-' }}</td></tr>
        <tr><th>End Date</th><td>{{ $subtask->end_date ?? '-' }}</td></tr>
        <tr><th>End Time</th><td>{{ $subtask->end_time ?? '-' }}</td></tr>
        <tr><th>Comment</th><td>{{ $subtask->comment ?? '-' }}</td></tr>
        <tr>
            <th>Attachment</th>
            <td>
                @if ($subtask->attachment)
                    <a href="{{ $subtask->attachment }}" target="_blank" class="btn btn-info btn-sm">View File</a>
                @else
                    N/A
                @endif
            </td>
        </tr>
    </table>
</div>
@endsection
