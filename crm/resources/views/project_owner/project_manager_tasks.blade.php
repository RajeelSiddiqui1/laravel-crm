@extends('layout.app')

@section('content')
<div class="container mt-4 text-white">
    <h2 class="mb-4">All Assigned Owner Tasks</h2>

    @if ($tasks->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Task Name</th>
                        <th>Client</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned Manager</th>
                        <th>Created</th>
                        <th>view</th>
                    </tr>
                </thead>
                <tbody style="background-color: transparent;">
                    @foreach ($tasks as $task)
                        <tr class="text-white">
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->client_name }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>
                                @php
                                    $badge = match($task->status) {
                                        'completed' => 'success',
                                        'in_progress' => 'primary',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>{{ $task->projectManagerTask->name ?? 'N/A' }}</td>
                            <td>{{ $task->created_at->diffForHumans() }}</td>
                             <td>
                                <a href="{{ route('project_owner.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">No tasks found.</div>
    @endif
</div>
@endsection
