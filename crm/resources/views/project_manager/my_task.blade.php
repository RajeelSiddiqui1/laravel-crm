@php use Illuminate\Support\Str; @endphp

@extends('layout.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --body-bg: #121217;
            /* Darker, modern background */
            --primary: #4f46e5;
            /* Vibrant indigo for primary actions */
            --success: #22c55e;
            /* Green for success */
            --warning: #f59e0b;
            /* Amber for warnings */
            --danger: #ef4444;
            /* Red for destructive actions */
            --text: #d1d5db;
            /* Light gray for text readability */
            --border: #2d3748;
            /* Subtle border color */
            --table-bg: rgba(31, 41, 55, 0.6);
            /* Semi-transparent table background */
            --hover-bg: rgba(75, 85, 99, 0.2);
            /* Subtle hover effect */
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
            /* Wider container for modern dashboards */
        }

        .table {
            background: var(--table-bg);
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            /* Glassmorphism effect */
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

        .btn-warning {
            background: var(--warning);
            border: none;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
            border: none;
        }

        .btn-danger:hover {
            background: #dc2626;
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

        .attachment-item img,
        .attachment-item video,
        .attachment-item audio {
            max-height: 80px;
            width: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            transition: transform 0.3s ease;
        }

        .attachment-item img:hover,
        .attachment-item video:hover {
            transform: scale(1.05);
        }

        .attachment-item a {
            text-decoration: none;
        }

        .attachment-item img.icon {
            height: 40px;
        }

        .attachment-item div {
            font-size: 0.75rem;
            color: var(--text);
            margin-top: 0.25rem;
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
                <h2 class="text-center mb-4">My Tasks Dashboard</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('project_manager.owntask_create') }}" class="btn btn-primary mb-4">Create Task</a>
            </div>
        </div>

        <!-- T1 Tasks -->
        @if ($accountst1->isNotEmpty())
            <h3 class="account-type-header">T1 Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Year</th>
                            <th>Business</th>
                            <th>View</th>
                            <th>Group Chat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountst1 as $account)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $account->clientname ?? 'N/A' }}</td>
                                <td>{{ $account->email ?? 'N/A' }}</td>
                                <td>{{ $account->year ?? 'N/A' }}</td>
                                <td>{{ $account->bussiness_name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('project_manager.my_task_detail', $account->id) }}"
                                        class="btn btn-sm btn-success">View</a>
                                </td>
                                <td> <a href="{{ route('chat.group', $account->id) }}" class="btn btn-primary btn-sm">Group
                                        Chat</a>
                                <td>
                                    <a href="{{ route('project_manager.mytask_edit', $account->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('project_manager.mytask_destroy', $account->id) }}"
                                        method="POST" style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No T1 Tasks Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- T2 Tasks -->
        @if ($accountst2->isNotEmpty())
            <h3 class="account-type-header">T2 Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Due Date</th>
                            <th>Business</th>
                            <th>Priority</th>
                            <th>Attachments</th>
                            <th>View</th>
                            <th>Group Chat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountst2 as $account)
                            @php
                                $dueDateOrYear = $account->due_date
                                    ? \Carbon\Carbon::parse($account->due_date)->format('M d, Y')
                                    : 'N/A';
                                $business = $account->nature_of_business ?? 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $account->clientname ?? 'N/A' }}</td>
                                <td>{{ $account->email ?? 'N/A' }}</td>
                                <td>{{ $dueDateOrYear }}</td>
                                <td>{{ $business }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $account->priority == 'high' ? 'danger' : ($account->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($account->priority ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($account->attachments)
                                        @php
                                            $fileUrl = Str::startsWith($account->attachments, ['http://', 'https://'])
                                                ? $account->attachments
                                                : asset('storage/' . $account->attachments);
                                            $ext = strtolower(
                                                pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION),
                                            );
                                            $fileName = basename($account->attachments);
                                        @endphp
                                        <div class="attachment-item text-center">
                                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ $fileUrl }}" target="_blank">
                                                    <img src="{{ $fileUrl }}" alt="Image">
                                                </a>
                                            @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                <video src="{{ $fileUrl }}" controls></video>
                                            @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                <audio controls>
                                                    <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                </audio>
                                            @elseif (in_array($ext, ['pdf']))
                                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF"
                                                        class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                        alt="Excel" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @elseif (in_array($ext, ['doc', 'docx']))
                                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                        alt="Word" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @else
                                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                        alt="File" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No Attachments</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('project_manager.my_task_detail', $account->id) }}"
                                        class="btn btn-sm btn-success">View</a>
                                </td>
                                <td> <a href="{{ route('chat.group', $account->id) }}" class="btn btn-primary btn-sm">Group
                                        Chat</a>
                                <td>
                                    <a href="{{ route('project_manager.mytask_edit', $account->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('project_manager.mytask_destroy', $account->id) }}"
                                        method="POST" style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No T2 Tasks Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- HST Tasks -->
        @if ($accountsthst->isNotEmpty())
            <h3 class="account-type-header">HST Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Due Date</th>
                            <th>Business</th>
                            <th>Priority</th>
                            <th>Attachments</th>
                            <th>View</th>
                            <th>Gropu Chat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountsthst as $account)
                            @php
                                $dueDateOrYear = $account->due_date
                                    ? \Carbon\Carbon::parse($account->due_date)->format('M d, Y')
                                    : 'N/A';
                                $business = $account->nature_of_business ?? 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $account->clientname ?? 'N/A' }}</td>
                                <td>{{ $account->email ?? 'N/A' }}</td>
                                <td>{{ $dueDateOrYear }}</td>
                                <td>{{ $business }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $account->priority == 'high' ? 'danger' : ($account->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($account->priority ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($account->attachments)
                                        @php
                                            $fileUrl = Str::startsWith($account->attachments, ['http://', 'https://'])
                                                ? $account->attachments
                                                : asset('storage/' . $account->attachments);
                                            $ext = strtolower(
                                                pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION),
                                            );
                                            $fileName = basename($account->attachments);
                                        @endphp
                                        <div class="attachment-item text-center">
                                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ $fileUrl }}" target="_blank">
                                                    <img src="{{ $fileUrl }}" alt="Image">
                                                </a>
                                            @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                <video src="{{ $fileUrl }}" controls></video>
                                            @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                <audio controls>
                                                    <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                </audio>
                                            @elseif (in_array($ext, ['pdf']))
                                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF"
                                                        class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                        alt="Excel" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @elseif (in_array($ext, ['doc', 'docx']))
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                        alt="Word" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @else
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                        alt="File" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No Attachments</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('project_manager.my_task_detail', $account->id) }}"
                                        class="btn btn-sm btn-success">View</a>
                                </td>
                                <td>  <a href="{{ route('chat.group', $account->id) }}" class="btn btn-primary btn-sm">Group Chat</a>
                                <td>
                                    <a href="{{ route('project_manager.mytask_edit', $account->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('project_manager.mytask_destroy', $account->id) }}"
                                        method="POST" style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No HST Tasks Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Operation Tasks -->
        @if ($manageroperation->isNotEmpty())
            <h3 class="account-type-header">Operation Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Attachments</th>
                            <th>View</th>
                            <th>Grop Chat</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($manageroperation as $operation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $operation->description ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $operation->priority == 'high' ? 'danger' : ($operation->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($operation->priority ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($operation->attachments)
                                        @php
                                            $fileUrl = Str::startsWith($operation->attachments, ['http://', 'https://'])
                                                ? $operation->attachments
                                                : asset('storage/' . $operation->attachments);
                                            $ext = strtolower(
                                                pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION),
                                            );
                                            $fileName = basename($operation->attachments);
                                        @endphp
                                        <div class="attachment-item text-center">
                                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ $fileUrl }}" target="_blank">
                                                    <img src="{{ $fileUrl }}" alt="Image">
                                                </a>
                                            @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                <video src="{{ $fileUrl }}" controls></video>
                                            @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                <audio controls>
                                                    <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                </audio>
                                            @elseif (in_array($ext, ['pdf']))
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/pdf.png"
                                                        alt="PDF" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                        alt="Excel" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @elseif (in_array($ext, ['doc', 'docx']))
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                        alt="Word" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @else
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    title="{{ $fileName }}">
                                                    <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                        alt="File" class="icon">
                                                    <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No Attachments</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('project_manager.my_task_detail', $operation->id) }}"
                                        class="btn btn-sm btn-success">View</a>
                                </td>
                                <td>  <a href="{{ route('chat.group', $operation->id) }}" class="btn btn-primary btn-sm">Group Chat</a>
                                <td>
                                    <a href="{{ route('project_manager.mytask_edit', $operation->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('project_manager.mytask_destroy', $operation->id) }}"
                                        method="POST" style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No Operation Tasks Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
