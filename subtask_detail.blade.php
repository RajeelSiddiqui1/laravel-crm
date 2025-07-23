@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="card text-white shadow-lg p-4 border border-secondary rounded-4">
            <h3 class="mb-4 border-bottom pb-2">Subtask Details</h3>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3"><strong>Title:</strong>
                        <div>{{ $subtask->title }}</div>
                    </div>
                    <div class="mb-3"><strong>Description:</strong>
                        <div>{{ $subtask->description }}</div>
                    </div>
                    <div class="mb-3"><strong>Assigned Employee:</strong>
                        <div>{{ $subtask->employee->name ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3"><strong>Department:</strong>
                        <div>{{ $subtask->employee->department->name ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3"><strong>Start Date:</strong>
                        <div>{{ $subtask->start_date ?? '-' }}</div>
                    </div>
                    <div class="mb-3"><strong>Start Time:</strong>
                        <div>{{ $subtask->start_time ?? '-' }}</div>
                    </div>
                    <div class="mb-3"><strong>End Date:</strong>
                        <div>{{ $subtask->end_date ?? '-' }}</div>
                    </div>
                    <div class="mb-3"><strong>End Time:</strong>
                        <div>{{ $subtask->end_time ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card   text-white mt-5 p-4 rounded-4">
            <h4 class="border-bottom pb-2 mb-4">All Subtasks for {{ $subtask->employee->name }}</h4>

            <table class="table table-blur table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Lead</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>show more</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employeeSubtasks as $task)
                        @php $empSub = $task->employeeSubtask; @endphp
                        @for ($i = 0; $i < ($task->lead ?? 1); $i++)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $empSub->comments[$i] ?? '-' }}</td>
                                <td>{{ ucfirst($empSub->statuses[$i] ?? 'pending') }}</td>
                                <td>
                                    <a href="{{ route('team_subtask_show_more', $task->id) }}"
                                        class="btn btn-primary">View</a>
                                </td>

                                <td>
                                    @if ($subtask->employeeTasks && $subtask->employeeTasks->count())
                                        <div class="card text-white mt-5 p-4 rounded-4">
                                            <h4 class="border-bottom pb-2 mb-4">Employee Tasks for This Subtask</h4>

                                            <table class="table table-blur table-bordered align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Task Title</th>
                                                        <th>Task Description</th>
                                                        <th>Status</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($subtask->employeeTasks as $task)
                                                        <tr>
                                                            <td>{{ $task->title ?? '-' }}</td>
                                                            <td>{{ $task->description ?? '-' }}</td>
                                                            <td>{{ ucfirst($task->status ?? 'pending') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d M, Y h:i A') }}
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                </td>
                            </tr>
                        @endfor
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
