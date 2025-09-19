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

        .btn-link {
            color: var(--primary);
            text-decoration: none;
        }

        .btn-link:hover {
            color: #4338ca;
            text-decoration: underline;
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

        /* Enhanced Modal Styles */
        .modal-content {
            background: var(--table-bg);
            border: 1px solid var(--border);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            color: var(--text);
        }

        .modal-header {
            background: rgba(0, 0, 0, 0.8);
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.5rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text);
        }

        .modal-body {
            padding: 1.5rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text);
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 1rem 1.5rem;
        }

        .btn-close {
            background: none;
            color: var(--text);
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-content {
                border-radius: 0.75rem;
            }

            .modal-body {
                max-height: 50vh;
                padding: 1rem;
            }

            .modal-header,
            .modal-footer {
                padding: 0.75rem 1rem;
            }
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

        <!-- Shared Tasks Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Manger</th>
                        <th>Employee</th>
                        <th>Attachment</th>
                        <th>Visitor Status</th>
                        <th>Vendor Status</th>
                        <th>Machine Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shared_task as $shared)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shared->manager->name ?? 'N/A' }}</td>
                            <td>{{ $shared->employee->name ?? 'N/A' }}</td>


                            <td>
                                @if (!empty($shared['attachments']))
                                    <div class="mt-2 text-center">
                                        @php
                                            $fileUrl = Str::startsWith($shared['attachments'], ['http://', 'https://'])
                                                ? $shared['attachments']
                                                : asset('storage/' . $shared['attachments']);
                                            $ext = strtolower(
                                                pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION),
                                            );
                                            $fileName = basename($shared['attachments']);
                                        @endphp

                                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <a href="{{ $fileUrl }}" target="_blank">
                                                <img src="{{ $fileUrl }}" alt="Image" class="img-fluid rounded"
                                                    style="max-height: 80px; width: 100%; object-fit: cover;">
                                                <div>{{ Str::limit($fileName, 12) }}</div>
                                            </a>
                                        @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                            <video src="{{ $fileUrl }}" controls class="w-100 rounded"
                                                style="max-height: 80px;"></video>
                                            <div>{{ Str::limit($fileName, 12) }}</div>
                                        @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                            <audio controls class="w-100">
                                                <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                            </audio>
                                            <div>{{ Str::limit($fileName, 12) }}</div>
                                        @elseif (in_array($ext, ['pdf']))
                                            <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF"
                                                    class="icon" style="height: 40px;">
                                                <div>{{ Str::limit($fileName, 12) }}</div>
                                            </a>
                                        @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                            <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                    alt="Excel" class="icon" style="height: 40px;">
                                                <div>{{ Str::limit($fileName, 12) }}</div>
                                            </a>
                                        @elseif (in_array($ext, ['doc', 'docx']))
                                            <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word"
                                                    class="icon" style="height: 40px;">
                                                <div>{{ Str::limit($fileName, 12) }}</div>
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File"
                                                    class="icon" style="height: 40px;">
                                                <div>{{ Str::limit($fileName, 12) }}</div>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">No Attachment</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $status = $shared->status ?? 'N/A';
                                    $badgeClass = match ($status) {
                                        'signed' => 'success',
                                        'pending' => 'warning',
                                        'deployed' => 'primary',
                                        'not_avaiable' => 'info',
                                        're_shedule' => 'secondary',
                                        'not_intrested' => 'danger',
                                        default => 'dark',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>


                            <td>
                                @if ($shared->operation_employee_id == Auth::guard('employee')->id())
                                    {{-- Dropdown form (operation_employee update kar sakta hai) --}}
                                    <form action="{{ route('employee.update_vendor_status', $shared->id) }}"
                                        method="POST">
                                        @csrf
                                        <select name="vendor_status" class="form-select form-select-sm d-inline-block"
                                            style="width:auto;" onchange="this.form.submit()">
                                            <option value="pending"
                                                {{ $shared->vendor_status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="approved"
                                                {{ $shared->vendor_status == 'approved' ? 'selected' : '' }}>Approved
                                            </option>
                                            <option value="not_approved"
                                                {{ $shared->vendor_status == 'not_approved' ? 'selected' : '' }}>Not
                                                Approved</option>
                                        </select>
                                    </form>
                                @else
                                    {{-- Sirf badge show --}}
                                    @php
                                        $vendorColor = match ($shared->vendor_status) {
                                            'approved' => 'success',
                                            'not_approved' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $vendorColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $shared->vendor_status)) }}
                                    </span>
                                @endif
                            </td>


                            <td>
                                @if ($shared->operation_employee_id == Auth::guard('employee')->id())
                                    {{-- Dropdown form (operation_employee update kar sakta hai) --}}
                                    <form action="{{ route('employee.update_machine_status', $shared->id) }}"
                                        method="POST">
                                        @csrf
                                        <select name="machine_status" class="form-select form-select-sm d-inline-block"
                                            style="width:auto;" onchange="this.form.submit()">
                                            <option value="pending"
                                                {{ $shared->machine_status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="deployed"
                                                {{ $shared->machine_status == 'deployed' ? 'selected' : '' }}>Deployed
                                            </option>
                                            <option value="cancelled"
                                                {{ $shared->machine_status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        </select>
                                    </form>
                                @else
                                    {{-- Sirf badge show --}}
                                    @php
                                        $machineColor = match ($shared->machine_status) {
                                            'deployed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $machineColor }}">
                                        {{ ucfirst($shared->machine_status) }}
                                    </span>
                                @endif
                            </td>



                        </tr>

                        <!-- Enhanced Modal -->

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No Shared Task Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
