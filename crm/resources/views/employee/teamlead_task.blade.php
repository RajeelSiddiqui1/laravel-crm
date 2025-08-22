@php use Illuminate\Support\Str; @endphp

@extends('layout.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --body-bg: #0d0d11;
            --table-bg: #1a1b26;
            --accent: #7b68ee;
            --text: #f5f5f5;
        }

        body {
            background: var(--body-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
        }

        .table {
            background: var(--table-bg);
            border: none;
            border-radius: .75rem;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(90deg, var(--accent), #8a5cf5);
            color: #fff;
            font-weight: 600;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 0.75rem;
            text-align: center;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 0.5rem;
        }

        .btn-primary:hover {
            background: #5a4fcf;
        }

        .btn-success,
        .btn-warning,
        .btn-danger,
        .btn-info {
            border-radius: 0.5rem;
        }

        .form-control,
        .form-select {
            background: #252837;
            border: 1px solid #3a3c4f;
            color: var(--text);
            border-radius: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            background: #252837;
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(123, 104, 238, .25);
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .badge-info {
            background: #17a2b8;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-warning {
            background: #ffc107;
        }

        .badge-secondary {
            background: #6c757d;
        }

        .badge-danger {
            background: #dc3545;
        }

        .attachment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: .5rem;
        }

        .attachment-item img,
        .attachment-item video {
            height: 100px;
            width: 100%;
            object-fit: cover;
            border-radius: .5rem;
        }

        .attachment-item div {
            font-size: 12px;
            color: var(--text);
        }

        .account-type-header {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text);
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4 fw-bold">My Tasks Dashboard</h2>
            </div>
        </div>

        @if($tasks->whereNotNull('accountT1')->count() > 0)
        <h3 class="account-type-header">T1 Tasks</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>Department</th>
                        <th>Team Lead</th>
                        <th>Manager Status</th>
                        <th>Shared</th>
                        <th>Account Type</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Year</th>
                        <th>Business</th>
                        <th>Attachments</th>
                        <th>View</th>
                        <th>Manager</th>
                        <th>Group Chat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks->whereNotNull('accountT1') as $task)
                        @php
                            $account = $task->accountT1;
                            $accountType = 'T1';
                            $dueDateOrYear = $account->year ?? 'N/A';
                            $business = $account->bussiness_name ?? 'N/A';
                        @endphp
                        <tr class="align-middle text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($task->department && $task->department->name === 'Call operator')
                                    <span class="badge badge-info">Call Operator</span>
                                @else
                                    {{ $task->department->name ?? 'Accounts' }}
                                @endif
                            </td>
                            <td>{{ $task->teamLead->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $task->status2 === 'approved' ? 'success' : ($task->status2 === 'pending' ? 'secondary' : ($task->status2 === 'rejected' ? 'danger' : ($task->status2 === 'late' ? 'warning' : 'dark'))) }}">
                                    {{ ucfirst($task->status2 ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->is_shared ? 'info' : 'secondary' }}">
                                    {{ $task->is_shared ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $accountType }}</td>
                            <td>{{ $account->clientname ?? 'N/A' }}</td>
                            <td>{{ $account->email ?? 'N/A' }}</td>
                            <td>{{ $dueDateOrYear }}</td>
                            <td>{{ $business }}</td>
                            <td>
                                <span class="text-muted">No Attachments</span>
                            </td>
                            <td>
                                <a href="{{ route('employee.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-success">View</a>
                            </td>
                            <td>{{ $task->projectManager->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}"
                                    class="btn btn-outline-primary btn-sm">Group Chat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center text-muted">No T1 Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if($tasks->whereNotNull('accountT2')->count() > 0)
        <h3 class="account-type-header">T2 Tasks</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>Department</th>
                        <th>Team Lead</th>
                        <th>Manager Status</th>
                        <th>Shared</th>
                        <th>Account Type</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Due Date</th>
                        <th>Business</th>
                        <th>Priority</th>
                        <th>Attachments</th>
                        <th>View</th>
                        <th>Manager</th>
                        <th>Group Chat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks->whereNotNull('accountT2') as $task)
                        @php
                            $account = $task->accountT2;
                            $accountType = 'T2';
                            $dueDateOrYear = $account->due_date ? \Carbon\Carbon::parse($account->due_date)->format('M d, Y') : 'N/A';
                            $business = $account->nature_of_business ?? 'N/A';
                        @endphp
                        <tr class="align-middle text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($task->department && $task->department->name === 'Call operator')
                                    <span class="badge badge-info">Call Operator</span>
                                @else
                                    {{ $task->department->name ?? 'Accounts' }}
                                @endif
                            </td>
                            <td>{{ $task->teamLead->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $task->status2 === 'approved' ? 'success' : ($task->status2 === 'pending' ? 'secondary' : ($task->status2 === 'rejected' ? 'danger' : ($task->status2 === 'late' ? 'warning' : 'dark'))) }}">
                                    {{ ucfirst($task->status2 ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->is_shared ? 'info' : 'secondary' }}">
                                    {{ $task->is_shared ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $accountType }}</td>
                            <td>{{ $account->clientname ?? 'N/A' }}</td>
                            <td>{{ $account->email ?? 'N/A' }}</td>
                            <td>{{ $dueDateOrYear }}</td>
                            <td>{{ $business }}</td>
                            <td>
                                <span class="badge badge-{{ $account->priority == 'high' ? 'danger' : ($account->priority == 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($account->priority ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                @if ($account && !empty($account->attachments))
                                    <div class="attachment-grid">
                                        @foreach ((array) $account->attachments as $url)
                                            @php
                                                $fileUrl = Str::startsWith($url, ['http://', 'https://'])
                                                    ? $url
                                                    : asset('storage/' . $url);
                                                $ext = strtolower(
                                                    pathinfo(
                                                        parse_url($fileUrl, PHP_URL_PATH),
                                                        PATHINFO_EXTENSION,
                                                    ),
                                                );
                                                $fileName = basename($url);
                                            @endphp
                                            <div class="attachment-item text-center">
                                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <a href="{{ $fileUrl }}" target="_blank">
                                                        <img src="{{ $fileUrl }}" alt="Image"
                                                            style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px;">
                                                    </a>
                                                @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                    <video src="{{ $fileUrl }}" controls
                                                        style="width: 100%; height: 100px; border-radius: 8px;"></video>
                                                @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                    <audio controls style="width: 100%;">
                                                        <source src="{{ $fileUrl }}"
                                                            type="audio/{{ $ext }}">
                                                    </audio>
                                                @elseif (in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png"
                                                            alt="PDF" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                            alt="Excel" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['doc', 'docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                            alt="Word" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                            alt="File" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No Attachments</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('employee.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-success">View</a>
                            </td>
                            <td>{{ $task->projectManager->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}"
                                    class="btn btn-outline-primary btn-sm">Group Chat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center text-muted">No T2 Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if($tasks->whereNotNull('accountHst')->count() > 0)
        <h3 class="account-type-header">HST Tasks</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>Department</th>
                        <th>Team Lead</th>
                        <th>Manager Status</th>
                        <th>Shared</th>
                        <th>Account Type</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Due Date</th>
                        <th>Business</th>
                        <th>Priority</th>
                        <th>Attachments</th>
                        <th>View</th>
                        <th>Manager</th>
                        <th>Group Chat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks->whereNotNull('accountHst') as $task)
                        @php
                            $account = $task->accountHst;
                            $accountType = 'HST';
                            $dueDateOrYear = $account->due_date ? \Carbon\Carbon::parse($account->due_date)->format('M d, Y') : 'N/A';
                            $business = $account->nature_of_business ?? 'N/A';
                        @endphp
                        <tr class="align-middle text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($task->department && $task->department->name === 'Call operator')
                                    <span class="badge badge-info">Call Operator</span>
                                @else
                                    {{ $task->department->name ?? 'Accounts' }}
                                @endif
                            </td>
                            <td>{{ $task->teamLead->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $task->status2 === 'approved' ? 'success' : ($task->status2 === 'pending' ? 'secondary' : ($task->status2 === 'rejected' ? 'danger' : ($task->status2 === 'late' ? 'warning' : 'dark'))) }}">
                                    {{ ucfirst($task->status2 ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->is_shared ? 'info' : 'secondary' }}">
                                    {{ $task->is_shared ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $accountType }}</td>
                            <td>{{ $account->clientname ?? 'N/A' }}</td>
                            <td>{{ $account->email ?? 'N/A' }}</td>
                            <td>{{ $dueDateOrYear }}</td>
                            <td>{{ $business }}</td>
                            <td>
                                <span class="badge badge-{{ $account->priority == 'high' ? 'danger' : ($account->priority == 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($account->priority ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                @if ($account && !empty($account->attachments))
                                    <div class="attachment-grid">
                                        @foreach ((array) $account->attachments as $url)
                                            @php
                                                $fileUrl = Str::startsWith($url, ['http://', 'https://'])
                                                    ? $url
                                                    : asset('storage/' . $url);
                                                $ext = strtolower(
                                                    pathinfo(
                                                        parse_url($fileUrl, PHP_URL_PATH),
                                                        PATHINFO_EXTENSION,
                                                    ),
                                                );
                                                $fileName = basename($url);
                                            @endphp
                                            <div class="attachment-item text-center">
                                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <a href="{{ $fileUrl }}" target="_blank">
                                                        <img src="{{ $fileUrl }}" alt="Image"
                                                            style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px;">
                                                    </a>
                                                @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                    <video src="{{ $fileUrl }}" controls
                                                        style="width: 100%; height: 100px; border-radius: 8px;"></video>
                                                @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                    <audio controls style="width: 100%;">
                                                        <source src="{{ $fileUrl }}"
                                                            type="audio/{{ $ext }}">
                                                    </audio>
                                                @elseif (in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png"
                                                            alt="PDF" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png"
                                                            alt="Excel" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif (in_array($ext, ['doc', 'docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png"
                                                            alt="Word" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png"
                                                            alt="File" style="height: 48px;">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No Attachments</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('employee.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-success">View</a>
                            </td>
                            <td>{{ $task->projectManager->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}"
                                    class="btn btn-outline-primary btn-sm">Group Chat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center text-muted">No HST Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if($tasks->whereNull('accountT1')->whereNull('accountT2')->whereNull('accountHst')->count() > 0)
        <h3 class="account-type-header">Other Tasks</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>Department</th>
                        <th>Team Lead</th>
                        <th>Manager Status</th>
                        <th>Shared</th>
                        <th>Account Type</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Due Date/Year</th>
                        <th>Business</th>
                        <th>Attachments</th>
                        <th>View</th>
                        <th>Manager</th>
                        <th>Group Chat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks->whereNull('accountT1')->whereNull('accountT2')->whereNull('accountHst') as $task)
                        @php
                            $account = null;
                            $accountType = 'N/A';
                            $dueDateOrYear = 'N/A';
                            $business = 'N/A';
                        @endphp
                        <tr class="align-middle text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($task->department && $task->department->name === 'Call operator')
                                    <span class="badge badge-info">Call Operator</span>
                                @else
                                    {{ $task->department->name ?? 'Accounts' }}
                                @endif
                            </td>
                            <td>{{ $task->teamLead->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $task->status2 === 'approved' ? 'success' : ($task->status2 === 'pending' ? 'secondary' : ($task->status2 === 'rejected' ? 'danger' : ($task->status2 === 'late' ? 'warning' : 'dark'))) }}">
                                    {{ ucfirst($task->status2 ?? 'N/A') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->is_shared ? 'info' : 'secondary' }}">
                                    {{ $task->is_shared ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $accountType }}</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>
                                <span class="text-muted">No Attachments</span>
                            </td>
                            <td>
                                <a href="{{ route('employee.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-success">View</a>
                            </td>
                            <td>{{ $task->projectManager->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}"
                                    class="btn btn-outline-primary btn-sm">Group Chat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center text-muted">No Other Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection