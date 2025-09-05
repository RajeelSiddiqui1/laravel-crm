@extends('layout.app')

@section('content')
    <div class="container">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- <h3 class="text-white mb-4">Create Subtask for {{ $task->client_name }}</h3> --}}

        <form method="POST" action="{{ route('team_lead.subtask.store') }}">
            @csrf
            {{-- <input type="hidden" name="owner_task_id" value="{{ $task->id }}"> --}}

            <div class="mb-3">
                <label class="form-label text-white">Title</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required
                    aria-label="Subtask Title">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Description</label>
                <textarea class="form-control" name="description" rows="4" required
                    aria-label="Subtask Description">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Number of Leads</label>
                <input type="number" min="0" name="lead" class="form-control" value="{{ old('lead') }}"
                    required aria-label="Number of Leads">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Assign to Employee</label>
                <select name="assigned_employee_id" class="form-control" required
                    aria-label="Assign to Employee">
                    <option value="">Select Employee</option>
                    @foreach ($assignedEmployees as $emp)
                        <option value="{{ $emp->id }}"
                            {{ old('assigned_employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} - {{ $emp->department->name ?? 'No Department' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Task Type</label>
                <select name="task_type" class="form-control" required aria-label="Task Type">
                    <option value="">Select Task Type</option>
                    @foreach ($cellCenterPos as $pos)
                        <option value="{{ $pos->id }}"
                            {{ old('task_type') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name ?? $pos->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"
                    required aria-label="Start Date">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Time</label>
                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}"
                    required aria-label="Start Time">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}"
                    required aria-label="End Date">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Time</label>
                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}"
                    required aria-label="End Time">
            </div>

            <button class="btn btn-success" type="submit">Create Subtask</button>
        </form>
    </div>
@endsection

@section('styles')
    <style>
        .container {
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
        }
        .form-control {
            background-color: #2c2c2c;
            color: #fff;
            border: 1px solid #444;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .alert-danger {
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const startDate = form.querySelector('[name="start_date"]').value;
                const endDate = form.querySelector('[name="end_date"]').value;
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error!',
                        text: 'End date must be after start date.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection