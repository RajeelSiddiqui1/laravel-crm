@extends('layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-white">Subtasks for {{ $task->client_name }}</h3>
        <a href="{{ route('team_lead.manager_tasks') }}" class="btn btn-secondary">← Back to Manager Tasks</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($task->subtasks->count())
        <div class="table-responsive">
            <table class="table table-bordered table-dark text-white align-middle">
                <thead class="table-light text-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Start Time</th>
                        <th>End Date</th>
                        <th>End Time</th>
                        <th>Comment</th>
                        <th>Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($task->subtasks as $index => $subtask)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subtask->title }}</td>
                            <td>{{ $subtask->description }}</td>
                            <td>{{ $subtask->employee->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($subtask->status ?? 'pending') }}</td>
                            <td>{{ $subtask->start_date ?? '-' }}</td>
                            <td>{{ $subtask->start_time ?? '-' }}</td>
                            <td>{{ $subtask->end_date ?? '-' }}</td>
                            <td>{{ $subtask->end_time ?? '-' }}</td>
                            <td>{{ $subtask->comment ?? '-' }}</td>
                            <td>
                                <div class="d-flex flex-column gap-1">
<a href="{{ route('teamlead.subtask.detail', $subtask->id) }}" class="btn btn-sm btn-info">View</a>
<a href="{{ route('teamlead.subtask.edit', $subtask->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No subtasks created.</p>
    @endif
</div>
@endsection
