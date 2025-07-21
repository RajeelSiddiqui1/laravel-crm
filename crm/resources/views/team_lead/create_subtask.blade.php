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

        <h3 class="text-white mb-4">Create Subtask for {{ $task->client_name }}</h3>

        <form method="POST" action="{{ route('team_lead.subtask.store') }}">
            @csrf
            <input type="hidden" name="owner_task_id" value="{{ $task->id }}">

            <div class="mb-3">
                <label class="form-label text-white">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Description</label>
                <textarea class="form-control" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Leads(Optional)</label>
                <input type="number" min='0' name='lead' class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Assign to Employee</label>
                <select name="assigned_employee_id" class="form-select" required>
                    <option value="">Select Employee</option>
                    @foreach ($assignedEmployees as $emp)
                        <option value="{{ $emp->id }}">
                            {{ $emp->name }} - {{ $emp->department->name ?? 'No Department' }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label class="form-label text-white">Start Date</label>
                <input type="date" name="start_date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Time</label>
                <input type="time" name="start_time" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Date</label>
                <input type="date" name="end_date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Time</label>
                <input type="time" name="end_time" class="form-control">
            </div>


            <button class="btn btn-success">Create Subtask</button>
        </form>
    </div>
@endsection
