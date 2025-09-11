@php
    $statuses = ['pending', 'in_progress', 'approved', 'rejected', 'late'];
@endphp

@extends('layout.app')

@section('styles')
    <style>
        :root {
            --body-bg: #121217;
            --primary: #4f46e5;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text: #d1d5db;
            --border: #2d3748;
            --table-bg: rgba(31, 41, 55, 0.6);
            --hover-bg: rgba(75, 85, 99, 0.2);
        }

        body {
            background: var(--body-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .table {
            background: var(--table-bg);
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .table thead {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 1rem;
            text-align: center;
            border: 1px solid var(--border);
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: var(--hover-bg);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            border: none;
        }

        .btn-success:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: var(--warning);
            border: none;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
            border: none;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .form-control,
        .form-select {
            background: rgba(55, 65, 81, 0.3);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(75, 85, 99, 0.5);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        .badge-danger {
            background: var(--danger);
        }

        .badge-warning {
            background: var(--warning);
        }

        .badge-success {
            background: var(--success);
        }

        .badge-secondary {
            background: #6b7280;
        }

        .dropdown-menu {
            background: rgba(55, 65, 81, 0.9);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            z-index: 1050;
        }

        .dropup .dropdown-menu {
            bottom: 100%;
            top: auto;
            margin-bottom: 0.125rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-white">Subtasks for {{ $task->clientname ?? ($task->title ?? 'Task') }}</h3>
        
        </div>

        @if (session('success_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success_swal') }}",
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if (session('error_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error_swal') }}",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if ($subtasks->count())
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
                            <th>Teamlead</th>
                            <th>Employee Assigned</th>
                            <th>Team Lead Status</th>
                            <th>Employee Status</th>
                            <th>Start Date</th>
                            <th>Start Time</th>
                            <th>End Date</th>
                            <th>End Time</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Employee Tasks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subtasks as $index => $subtask)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subtask->title }}</td>
                                  <td>{{ $subtask->teamlead->name ?? 'N/A' }}</td>
                                <td>{{ $subtask->employee->name ?? 'N/A' }}</td>
                               
                                <td>
                                    <span
                                        class="badge 
        {{ $subtask->teamlead_status == 'completed' ? 'bg-success' : '' }}
        {{ $subtask->teamlead_status == 'pending' ? 'bg-secondary' : '' }}
        {{ $subtask->teamlead_status == 'late' ? 'bg-warning' : '' }}
        {{ $subtask->teamlead_status == 'rejected' ? 'bg-danger' : '' }}">
                                        {{ ucfirst($subtask->teamlead_status ?? 'pending') }}
                                    </span>
                                </td>

                                </td>
                                <td>
                                    <span
                                        class="badge 
                                        {{ $subtask->employee_status == 'complete' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($subtask->employee_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>{{ $subtask->start_date ?? '-' }}</td>
                                <td>{{ $subtask->start_time ?? '-' }}</td>
                                <td>{{ $subtask->end_date ?? '-' }}</td>
                                <td>{{ $subtask->end_time ?? '-' }}</td>
                              
                                <td>
                                    <a href="{{ route('team_lead.subtask.edit', $subtask->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                </td>
                                <td>
                                    <form action="{{ route('team_lead.subtask.delete', $subtask->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this subtask?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('project_manager.subtask_detail', $subtask->id) }}"
                                        class="btn btn-success">
                                        Show
                                    </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const employeeInput = document.getElementById('employeeFilter');
            const statusSelect = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('table tbody tr');

            function filterTable() {
                const employeeValue = employeeInput.value.toLowerCase();
                const statusValue = statusSelect.value;

                rows.forEach(row => {
                    const employeeCell = row.cells[2]?.textContent.toLowerCase() || '';
                    const statusSelectInRow = row.querySelector('select[name="teamlead_status"]');
                    const rowStatus = statusSelectInRow ? statusSelectInRow.value : '';
                    const employeeStatusCell = row.cells[4]?.textContent.toLowerCase() || '';

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
