@extends('layout.app')

@section('content')
    <div class="container">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if (session('success_swal_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success_swal_swal') }}",
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if (session('error_swal_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error_swal_swal') }}",
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

        <h3 class="text-white mb-4">Create Subtask for {{ $task->client_name }}</h3>

        <form method="POST" action="{{ route('team_lead.subtask.store') }}">
            @csrf
            <input type="hidden" name="owner_task_id" value="{{ $task->id }}">

            <div class="mb-3">
                <label class="form-label text-white">Title</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Description</label>
                <textarea class="form-control" name="description" rows="4" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Leads</label>
                <input type="number" min="0" name="lead" class="form-control" value="{{ old('lead') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Assign to Employee</label>
                <select name="assigned_employee_id" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach ($assignedEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ old('assigned_employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} - {{ $emp->department->name ?? 'No Department' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Time</label>
                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Time</label>
                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Cell Center POS</label>
                <select name="cell_center_pos_id" class="form-control" required>
                    <option value="">Select Cell Center POS</option>
                    @foreach ($cellCenterPos as $pos)
                        <option value="{{ $pos->id }}" {{ old('cell_center_pos_id') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Form Task</label>
                <select name="form_task" class="form-control" required>
                    <option value="">Select Form Task</option>
                    @foreach ($cellCenterPos as $pos)
                        <option value="{{ $pos->id }}" {{ old('form_task') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success">Create Subtask</button>
        </form>
    </div>
@endsection