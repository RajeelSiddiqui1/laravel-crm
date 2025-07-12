@php
    $statuses = ['pending', 'in_progress', 'completed', 'rejected', 'late'];
@endphp

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
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($task->subtasks->count())
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="employeeFilter" class="form-control" placeholder="Filter by employee name...">
                </div>
                <div class="col-md-6">
                    <select id="statusFilter" class="form-control">
                        <option value="">Filter by status...</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Start Time</th>
                            <th>End Date</th>
                            <th>End Time</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task->subtasks as $index => $subtask)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subtask->title }}</td>
                                <td>{{ $subtask->employee->name ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('team_lead.subtask.update_status', $subtask->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}" {{ $subtask->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <span class="badge mt-2 {{ $subtask->status == 'completed' ? 'bg-success' : ($subtask->status == 'rejected' ? 'bg-danger' : ($subtask->status == 'late' ? 'bg-warning' : ($subtask->status == 'in_progress' ? 'bg-primary' : 'bg-secondary'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $subtask->status ?? 'pending')) }}
                                    </span>
                                </td>
                                <td>{{ $subtask->start_date ?? '-' }}</td>
                                <td>{{ $subtask->start_time ?? '-' }}</td>
                                <td>{{ $subtask->end_date ?? '-' }}</td>
                                <td>{{ $subtask->end_time ?? '-' }}</td>
                                <td>
                                     <a href="{{ route('team_lead.subtask.detail', $subtask->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                                <td>
                                    <a href="{{ route('team_lead.subtask.edit', $subtask->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                                <td>
                                    <form action="{{ route('team_lead.subtask.delete', $subtask->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subtask?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employeeInput = document.getElementById('employeeFilter');
            const statusSelect = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('table tbody tr');

            function filterTable() {
                const employeeValue = employeeInput.value.toLowerCase();
                const statusValue = statusSelect.value;

                rows.forEach(row => {
                    const employeeCell = row.cells[2]?.textContent.toLowerCase() || '';
                    const statusSelectInRow = row.querySelector('select[name="status"]');
                    const rowStatus = statusSelectInRow ? statusSelectInRow.value : '';

                    const matchesEmployee = employeeCell.includes(employeeValue);
                    const matchesStatus = !statusValue || rowStatus === statusValue;

                    row.style.display = matchesEmployee && matchesStatus ? '' : 'none';
                });
            }

            employeeInput.addEventListener('input', filterTable);
            statusSelect.addEventListener('change', filterTable);
        });
    </script>
@endsection
