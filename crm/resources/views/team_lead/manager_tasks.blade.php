@extends('layout.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
            scrollbar-width: thin;
            scrollbar-color: #0d6efd rgba(255, 255, 255, 0.1);
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

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background-color: #0d6efd;
            border-radius: 4px;
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
        <div class="table-responsive">
            <table class="table table-dark table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Client Name</th>
                        <th>Priority</th>
                        <th>Start Date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Status Update</th>
                        <th>Project Manager</th>
                        <th>Assigned Employees</th>
                        <th>View</th>
                        <th>Subtask</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        @php
                            $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                            $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                            $unassignedEmployees = $employees
                                ->whereNotIn('id', $assignedEmployeeIds)
                                ->where('department_id', $task->department_id);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->client_name }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>{{ $task->start_date->format('Y-m-d') }}</td>
                            <td>{{ $task->deadline->format('Y-m-d') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : ($task->status == 'cancelled' ? 'danger' : 'info')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('team_lead.tasks.update_status', $task->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()"
                                        style="min-width: 120px;">
                                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="in_progress"
                                            {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </form>
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
                                    <form method="POST"
                                        action="{{ route('team_lead.tasks.assign_employees', $task->id) }}">
                                        @csrf
                                        <div class="dropdown mt-2">
                                            <button class="btn btn-sm dropdown-toggle" type="button"
                                                id="dropdown-{{ $task->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Assign Employees
                                            </button>
                                            <ul class="dropdown-menu checkbox-wrapper"
                                                aria-labelledby="dropdown-{{ $task->id }}">
                                                @foreach ($unassignedEmployees as $employee)
                                                    <li>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="employee_id[]" value="{{ $employee->id }}"
                                                                id="emp-{{ $task->id }}-{{ $employee->id }}">
                                                            <label class="form-check-label"
                                                                for="emp-{{ $task->id }}-{{ $employee->id }}">
                                                                {{ $employee->name }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><button type="submit"
                                                        class="btn btn-sm btn-success w-100">Assign</button></li>
                                            </ul>
                                        </div>
                                    </form>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('team_lead.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-primary">View</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                    data-bs-target="#subtaskModal-{{ $task->id }}">
                                    Subtask
                                </button>

                               <div class="modal fade" id="subtaskModal-{{ $task->id }}" tabindex="-1" aria-labelledby="subtaskLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('team_lead.subtasks.store') }}">
            @csrf
            <input type="hidden" name="owner_task_id" value="{{ $task->id }}">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="subtaskLabel-{{ $task->id }}">
                        Create Subtask for {{ $task->client_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subtask Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign to Employee</label>
                        <select name="assigned_employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees->where('department_id', $task->department_id) as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create Subtask</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection