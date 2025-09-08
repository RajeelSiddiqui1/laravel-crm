@extends('layout.app')

@section('content')
    <div class="container">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Success Notification -->
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

        <!-- Error Notifications -->
        @if (session('error_swal') || session('error_swal_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error_swal') ?? session('error_swal_swal') }}",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h3 class="text-white mb-4">Create Subtask for {{ $account->name ?? 'Account' }} ({{ $accountType }})</h3>

        <form method="POST" action="{{ route('team_lead.subtask.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="account_id" value="{{ $account->id }}">
            <input type="hidden" name="account_type" value="{{ $accountType }}">

            <div class="mb-3">
                <label class="form-label text-white">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required
                    aria-label="Subtask Title">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" rows="4" required
                    aria-label="Subtask Description">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Number of Leads <span class="text-danger">*</span></label>
                <input type="number" min="0" name="lead" class="form-control" value="{{ old('lead') }}"
                    required aria-label="Number of Leads">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Assign to Employee <span class="text-danger">*</span></label>
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

            <div class="mb-3 form-group">
                        <label class="form-label">Task Type <span class="text-muted">(Optional)</span></label>
                        <div class="dropup">
                            <select name="task_type" class="form-select @error('task_type') is-invalid @enderror"
                                    aria-label="Task Type">
                                <option value="">Select Task Type</option>
                                @php
                                    $teamLead = Auth::guard('team_lead')->user();
                                    $taskTypes = ($teamLead->department && $teamLead->department->name === 'Accounts')
                                        ? ['Call_Center_POS', 'Call_Center_Accounts']
                                        : ['Operations','Call_Center_POS'];
                                @endphp
                                @foreach ($taskTypes as $type)
                                    <option value="{{ $type }}"
                                            {{ old('task_type') == $type ? 'selected' : '' }}>
                                        {{ Str::replace('_', ' ', $type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('task_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            <div class="mb-3">
                <label class="form-label text-white">Attachment</label>
                <input type="file" class="form-control" name="attachments"
                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" aria-label="Attachment">
                <small class="text-muted">Optional: Max 2MB, allowed formats: JPG, PNG, PDF, DOC, DOCX</small>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"
                    aria-label="Start Date">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Time</label>
                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}"
                    aria-label="Start Time">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}"
                    aria-label="End Date">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Time</label>
                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}"
                    aria-label="End Time">
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
            max-width: 600px;
        }
        .form-control, .form-select {
            background-color: #2c2c2c;
            color: #fff;
            border: 1px solid #444;
        }
        .form-control:focus, .form-select:focus {
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
        .text-muted {
            color: #aaa !important;
        }
        .form-label .text-danger {
            font-size: 0.9em;
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
                const startTime = form.querySelector('[name="start_time"]').value;
                const endTime = form.querySelector('[name="end_time"]').value;

                // Validate end date is not before start date
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error!',
                        text: 'End date must be after or equal to start date.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Validate end time is after start time if same date
                if (startDate && endDate && startDate === endDate && startTime && endTime) {
                    if (startTime >= endTime) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Error!',
                            text: 'End time must be after start time on the same day.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    </script>
@endsection