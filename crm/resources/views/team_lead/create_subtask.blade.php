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
                                        ? ['call_center_pos', 'call_center_accounts','client_details']
                                        : ['operations','call_center_pos'];
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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

        .container {
            max-width: 600px;
            background: var(--table-bg);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            margin-top: 2rem;
        }

        .form-label {
            color: var(--text);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            background: rgba(31, 41, 55, 0.6);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.5);
            background: rgba(31, 41, 55, 0.8);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-success {
            background: var(--success);
            border: none;
        }

        .btn-success:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .alert-danger {
            background: var(--danger);
            color: #fff;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .text-muted {
            color: #9ca3af !important;
            font-size: 0.85rem;
        }

        .form-label .text-danger {
            font-size: 0.9em;
            color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.85rem;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
            text-align: center;
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