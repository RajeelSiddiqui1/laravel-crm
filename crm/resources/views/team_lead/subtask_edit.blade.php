@php
    use Illuminate\Support\Str;
@endphp

@extends('layout.app')

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
            --card-bg: rgba(31, 41, 55, 0.6);
            --hover-bg: rgba(75, 85, 99, 0.2);
        }

        body {
            background: var(--body-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .card-header {
            background: rgba(0, 0, 0, 0.8);
            border-bottom: 1px solid var(--border);
        }

        .form-label {
            color: var(--text);
            font-weight: 500;
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

        .btn-outline-light {
            border-color: var(--text);
            color: var(--text);
        }

        .btn-outline-light:hover {
            background: var(--hover-bg);
            color: #fff;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.85rem;
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
    <div class="container mt-5">
        <div class="card text-white shadow-lg">
            <div class="card-header border-bottom border-secondary">
                <h4 class="mb-0">Update Subtask</h4>
            </div>
            <div class="card-body">
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

                <form action="{{ route('team_lead.subtask.update', $subtask->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields for account_type and account_id -->
                    <input type="hidden" name="account_type"
                           value="{{ $subtask->account_t1_id ? 'T1' : ($subtask->account_t2_id ? 'T2' : ($subtask->account_hst_id ? 'HST' : 'MANAGER')) }}">
                    <input type="hidden" name="account_id"
                           value="{{ $subtask->account_t1_id ?? $subtask->account_t2_id ?? $subtask->account_hst_id ?? $subtask->manager_operation_id }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $subtask->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lead (Hours)</label>
                            <input type="number" name="lead" class="form-control @error('lead') is-invalid @enderror"
                                   value="{{ old('lead', $subtask->lead) }}" step="0.1" min="0">
                            @error('lead')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign to Employee</label>
                        <div class="dropup">
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach ($assignedEmployees as $emp)
                                    <option value="{{ $emp->id }}"
                                            {{ old('employee_id', $subtask->employee_id) == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} - {{ $emp->department->name ?? 'No Department' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $subtask->start_date) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $subtask->end_date) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                                   value="{{ old('start_time', $subtask->start_time) }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                                   value="{{ old('end_time', $subtask->end_time) }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                  required>{{ old('description', $subtask->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('team_lead.subtask.list', [$subtask->account_t1_id ?? $subtask->account_t2_id ?? $subtask->account_hst_id ?? $subtask->manager_operation_id]) }}"
                           class="btn btn-outline-light">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Update Subtask
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection