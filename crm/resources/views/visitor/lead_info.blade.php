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

        .container {
            max-width: 1400px;
        }

        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .card-header {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 1rem 1rem 0 0;
            padding: 1rem;
        }

        .card-body {
            padding: 2rem;
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

        .btn-secondary {
            background: #6b7280;
            border: none;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .form-control,
        .form-select,
        .form-control-file {
            background: rgba(55, 65, 81, 0.3);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus,
        .form-control-file:focus {
            background: rgba(75, 85, 99, 0.5);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
        }

        .form-label {
            color: var(--text);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        .badge-success {
            background: var(--success);
        }

        .badge-warning {
            background: var(--warning);
        }

        .badge-danger {
            background: var(--danger);
        }

        h2.text-center {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
        }

        h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Lead Information</h2>
                <div class="card">
                    <div class="card-header text-center">
                        Shared Task #{{ $shared_task->id }}
                    </div>
                    <div class="card-body">
                        <!-- Shared Task Details -->
                        <h4>Shared Task Details</h4>
                        <p><strong>Shared Task ID:</strong> {{ $shared_task->id ?? 'N/A' }}</p>
                        <p><strong>Shared Task Status:</strong>
                            <span
                                class="badge badge-{{ $shared_task->status == 'active' ? 'success' : ($shared_task->status == 'pending' ? 'warning' : ($shared_task->status == 'deployed' ? 'success' : ($shared_task->status == 'on_leave' ? 'warning' : 'danger'))) }}">
                                {{ ucfirst($shared_task->status ?? 'N/A') }}
                            </span>
                        </p>

                        <!-- POS or Account Details -->
                        @if ($type == 'pos' && $record)
                            <h4>POS Details</h4>
                            <p><strong>Name:</strong> {{ $record->name ?? 'N/A' }}</p>
                            <p><strong>Business Name:</strong> {{ $record->business_name ?? 'N/A' }}</p>
                            <p><strong>Business Number:</strong> {{ $record->business_number ?? 'N/A' }}</p>
                            <p><strong>Status:</strong>
                                <span
                                    class="badge badge-{{ $record->status == 'active' ? 'success' : ($record->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($record->status ?? 'N/A') }}
                                </span>
                            </p>
                        @elseif($type == 'account' && $record)
                            <h4>Account Details</h4>
                            <p><strong>Email:</strong> {{ $record->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $record->phone ?? 'N/A' }}</p>
                            <p><strong>Business Number:</strong> {{ $record->bussiness_number ?? 'N/A' }}</p>
                            <p><strong>Status:</strong>
                                <span
                                    class="badge badge-{{ $record->status == 'active' ? 'success' : ($record->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($record->status ?? 'N/A') }}
                                </span>
                            </p>
                        @else
                            <p class="text-muted">No associated POS or Account found.</p>
                        @endif

                        <!-- Update Form -->
                        <h4>Update Shared Task</h4>
                        <form action="{{ route('visitor.lead_info.post', $shared_task->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <!-- Status Dropdown -->
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="inactive" {{ $shared_task->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                    <option value="pending" {{ $shared_task->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="deployed" {{ $shared_task->status == 'deployed' ? 'selected' : '' }}>
                                        Deployed</option>
                                    <option value="on_leave" {{ $shared_task->status == 'on_leave' ? 'selected' : '' }}>On
                                        Leave</option>
                                </select>

                                <!-- Status Badge -->
                                <span
                                    class="badge badge-{{ $shared_task->status == 'deployed' ? 'success' : ($shared_task->status == 'pending' ? 'warning' : ($shared_task->status == 'on_leave' ? 'warning' : 'danger')) }}">
                                    {{ ucfirst($shared_task->status ?? 'N/A') }}
                                </span>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comments -->
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comments</label>
                                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="5">{{ old('comment', $shared_task->comment) }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="mb-3">
                                <label for="attachments" class="form-label">Attachments</label>
                                <input type="file" name="attachments" id="attachments"
                                    class="form-control-file @error('attachments') is-invalid @enderror">
                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($shared_task->attachments)
                                    <div class="mt-2">
                                        <small class="text-muted">Existing Attachment:</small>
                                        <div class="mt-1">
                                            @php
                                                $fileUrl = Str::startsWith($shared_task->attachments, [
                                                    'http://',
                                                    'https://',
                                                ])
                                                    ? $shared_task->attachments
                                                    : asset('storage/' . $shared_task->attachments);
                                                $ext = strtolower(
                                                    pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION),
                                                );
                                                $fileName = basename($shared_task->attachments);
                                            @endphp
                                            <div class="text-center">
                                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <a href="{{ $fileUrl }}" target="_blank">
                                                        <img src="{{ $fileUrl }}" alt="Image"
                                                            class="img-fluid rounded"
                                                            style="max-height:80px; width:100%; object-fit:cover; border-radius:0.5rem;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                    <video src="{{ $fileUrl }}" controls class="w-100 rounded"
                                                        style="max-height:80px;"></video>
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                    <audio controls class="w-100">
                                                        <source src="{{ $fileUrl }}"
                                                            type="audio/{{ $ext }}">
                                                    </audio>
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                @elseif (in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png"
                                                            alt="PDF" class="icon" style="height:40px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                            alt="Excel" class="icon" style="height:40px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['doc', 'docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                            alt="Word" class="icon" style="height:40px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                            alt="File" class="icon" style="height:40px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Form Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('visitor.sharedtask.view') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Update Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
