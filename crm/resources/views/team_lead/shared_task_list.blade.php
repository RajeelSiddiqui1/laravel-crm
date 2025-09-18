@php use Illuminate\Support\Str; @endphp

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
            max-width: 1400px;
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

        .account-type-header {
            margin: 2rem 0 1rem;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
            border-left: 4px solid var(--primary);
            padding-left: 1rem;
        }

        .table-responsive {
            margin-bottom: 2rem;
        }

        h2.text-center {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Shared Tasks</h2>
            </div>
        </div>

        <!-- Shared POS -->
                @if ($posResults)
        <h3 class="account-type-header">Shared POS</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Shared Task ID</th>
                        <th>POS Name</th>
                        <th>Business</th>
                        <th>Business Number</th>
                        <th>POS Status</th>
                        <th>Shared Task Status</th>
                        <th>Action</th>
                        <th>Assigned TeamLead</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posResults as $index => $pos)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pos->shared_task_id ?? 'N/A' }}</td>
                            <td>{{ $pos->name ?? 'N/A' }}</td>
                            <td>{{ $pos->business_name ?? 'N/A' }}</td>
                            <td>{{ $pos->business_number ?? 'N/A' }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $pos->status == 'active' ? 'success' : ($pos->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($pos->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $status = $pos->shared_status ?? 'N/A';
                                    $badgeClass = match ($status) {
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'deployed' => 'primary',
                                        'on_leave' => 'info',
                                        'inactive' => 'secondary',
                                        default => 'dark',
                                    };
                                @endphp

                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('team-lead.shared.pos.detail', $pos->id) }}"
                                    class="btn btn-sm btn-success">Show More</a>
                            </td>
                            <td>
                                @php
                                    $employee = $employees->firstWhere('id', $pos->assigned_employee_id);
                                @endphp

                                @if ($employee)
                                    <span class="badge bg-info">{{ $employee->name }}</span>
                                @else
                                    <form
                                        action="{{ route('team-lead.assign_employee_shared_task', $pos->shared_task_id) }}"
                                        method="POST">
                                        @csrf
                                        <select name="employee_id" class="form-select form-select-sm d-inline-block"
                                            style="width:auto;">
                                            <option value="">-- Select --</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary mt-1">Assign</button>
                                    </form>
                                @endif
                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No Shared POS Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@endif

        <!-- Shared Accounts -->
        @if ($accountResults)
            
      
        <h3 class="account-type-header">Shared Accounts</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Shared Task ID</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Business No</th>
                        <th>Account Status</th>
                        <th>Shared Task Status</th>
                        <th>Action</th>
                        <th>Assigned TeamLead</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accountResults as $index => $account)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $account->shared_task_id ?? 'N/A' }}</td>
                            <td>{{ $account->email ?? 'N/A' }}</td>
                            <td>{{ $account->phone ?? 'N/A' }}</td>
                            <td>{{ $account->bussiness_number ?? 'N/A' }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $account->status == 'active' ? 'success' : ($account->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($account->status ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $status = $account->shared_status ?? 'N/A';
                                    $badgeClass = match ($status) {
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'deployed' => 'primary',
                                        'on_leave' => 'info',
                                        'inactive' => 'secondary',
                                        default => 'dark',
                                    };
                                @endphp

                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('team-lead.shared.account.detail', $account->id) }}"
                                    class="btn btn-sm btn-success">Show More</a>
                            </td>
                            <td>
                                @php
                                    $employee = $employees->firstWhere('id', $account->assigned_employee_id);
                                @endphp

                                @if ($employee)
                                    <span class="badge bg-info">{{ $employee->name }}</span>
                                @else
                                    <form
                                        action="{{ route('team-lead.assign_employee_shared_task', $account->shared_task_id) }}"
                                        method="POST">
                                        @csrf
                                        <select name="employee_id" class="form-select form-select-sm d-inline-block"
                                            style="width:auto;">
                                            <option value="">-- Select --</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary mt-1">Assign</button>
                                    </form>
                                @endif
                            </td>


                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No Shared Accounts Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
          @endif
          
    </div>
@endsection
