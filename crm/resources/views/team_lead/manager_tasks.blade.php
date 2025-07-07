@extends('layout.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

@section('styles')

    <style>
        .checkbox-wrapper {
            max-height: 160px;
            overflow-y: auto;
            border-radius: 8px;
            padding: 10px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            scrollbar-width: thin;
            scrollbar-color: #0d6efd rgba(255, 255, 255, 0.1);
        }
        .checkbox-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        .checkbox-wrapper::-webkit-scrollbar-thumb {
            background-color: #0d6efd;
            border-radius: 10px;
        }
        .form-check {
            margin-bottom: 8px;
        }
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #0d6efd;
            border-radius: 4px;
            background-color: transparent;
            transition: all 0.25s ease-in-out;
            margin-top: 0.2rem;
        }
        .form-check-input:hover {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .form-check-label {
            color: #ffffff;
            font-weight: 500;
            margin-left: 10px;
            cursor: pointer;
        }
        .employee-name {
            color: #ffffff;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .dropdown-menu {
            background-color: #212529;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
        }
        .dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }
        .dropdown-menu::-webkit-scrollbar-thumb {
            background-color: #0d6efd;
            border-radius: 4px;
        }
        .dropdown-toggle {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .dropdown-toggle:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h2 class="text-white">Manager Tasks</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Client Name</th>
                    <th>Priority</th>
                    <th>Start Date</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Project Manager</th>
                    <th>Assigned Employees</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    @php
                        $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                        $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                        $unassignedEmployees = $employees->whereNotIn('id', $assignedEmployeeIds)->where('department_id', $task->department_id);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->client_name }}</td>
                        <td>{{ $task->priority }}</td>
                        <td>{{ $task->start_date->format('Y-m-d') }}</td>
                        <td>{{ $task->deadline->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td>{{ $task->projectManager->name ?? 'N/A' }}</td>
                        <td>
                            @if ($assignedEmployees->isNotEmpty())
                                @foreach ($assignedEmployees as $employee)
                                    <div class="employee-name">{{ $employee->name }}</div>
                                @endforeach
                            @else
                                <div class="employee-name text-muted">No employees assigned</div>
                            @endif

                            @if ($unassignedEmployees->isNotEmpty())
                                <form method="POST" action="{{ route('team_lead.tasks.assign_employees', $task->id) }}">
                                    @csrf
                                    <div class="dropdown mt-2">
                                        <button class="btn btn-sm dropdown-toggle" type="button" id="dropdown-{{ $task->id }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Assign Employees
                                        </button>
                                        <ul class="dropdown-menu checkbox-wrapper" aria-labelledby="dropdown-{{ $task->id }}">
                                            @foreach ($unassignedEmployees as $employee)
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="employee_id[]"
                                                            value="{{ $employee->id }}"
                                                            id="emp-{{ $task->id }}-{{ $employee->id }}">
                                                        <label class="form-check-label" for="emp-{{ $task->id }}-{{ $employee->id }}">
                                                            {{ $employee->name }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="submit" class="btn btn-sm btn-success w-100">Assign</button>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('team_lead.task_detail', $task->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
