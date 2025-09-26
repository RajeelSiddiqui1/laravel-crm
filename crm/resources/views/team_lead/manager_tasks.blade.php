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

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: #fff;
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

        .attachment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.5rem;
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

        .dropdown-menu {
            background: rgba(55, 65, 81, 0.9);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            z-index: 1050;
        }

        .dropdown-menu .form-check {
            padding: 0.5rem 1rem;
        }

        .dropdown-menu .form-check-label {
            color: var(--text);
            cursor: pointer;
        }

        .dropdown-menu .form-check-input {
            margin-right: 0.5rem;
        }

        .dropdown-divider {
            border-color: var(--border);
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Team Lead Tasks Dashboard</h2>
            </div>
        </div>

        @if($accountst1->isNotEmpty())
            <h3 class="account-type-header">T1 Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Year</th>
                            <th>Business</th>
                            <th>Manager Status</th>
                            <th>Team Status</th>
                            <th>Attachments</th>
                            <th>View</th>
                          ?? 'N/A'
                            <th>Subtask</th>
                            <th>Subtask View</th>
                            <th>Group Chat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountst1 as $task)
                            @php
                                $accountType = 'T1';
                                $dueDateOrYear = $task->year ?? 'N/A';
                                $business = $task->bussiness_name ?? 'N/A';
                                $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                                $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                                $unassignedEmployees = $employees
                                    ->whereNotIn('id', $assignedEmployeeIds)
                                    ->where('department_id', $teamLead->department_id);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Accounts</td>
                                <td>{{ $task->clientname ?? 'N/A' }}</td>
                                <td>{{ $task->email ?? 'N/A' }}</td>
                                <td>{{ $dueDateOrYear }}</td>
                                <td>{{ $business }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->manager_status == 'rejected' ? 'danger' : ($task->manager_status == 'in_progress' ? 'warning' : ($task->manager_status == 'completed' ? 'success' : ($task->manager_status == 'not_interested' ? 'warning' : 'danger'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->manager_status ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('team_lead.tasks.update_team_status', ['id' => $task->id, 'type' => 't1']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="team_status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 120px;">
                                            <option value="" disabled {{ is_null($task->team_status) ? 'selected' : '' }}>Select Status</option>
                                            <option value="rejected" {{ $task->team_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="in_progress" {{ $task->team_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->team_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="not_interested" {{ $task->team_status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                            <option value="in_completed" {{ $task->team_status == 'in_completed' ? 'selected' : '' }}>In Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if (!empty($task->attachments))
                                        <div class="attachment-grid">
                                            @foreach ((array) $task->attachments as $url)
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
                                                            <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @else
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
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
                                    <a href="{{ route('team_lead.task_detail', $task->id) }}" class="btn btn-sm btn-success">View</a>
                                </td>
                             ?? 'N/A'
                                <td>
                                    <a href="{{ route('team_lead.subtask.create', $task->id) }}" class="btn btn-sm btn-warning mb-1">Subtask</a>
                                </td>
                                <td>
                                    <a href="{{ route('team_lead.subtask.list', $task->id) }}" class="btn btn-sm btn-success">Subtask Assign</a>
                                </td>
                                <td>
                                    <a href="{{ route('chat.group', $task->id) }}" class="btn btn-sm btn-outline-primary">Group Chat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted">No T1 Tasks Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $accountst1->links() }}
            </div>
        @endif

        @if($accountst2->isNotEmpty())
            <h3 class="account-type-header">T2 Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Due Date</th>
                            <th>Business</th>
                            <th>Priority</th>
                            <th>Manager Status</th>
                            <th>Team Status</th>
                            <th>Attachments</th>
                            <th>View</th>
                          ?? 'N/A'
                            <th>Subtask</th>
                            <th>Subtask View</th>
                            <th>Group Chat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountst2 as $task)
                            @php
                                $accountType = 'T2';
                                $dueDateOrYear = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A';
                                $business = $task->nature_of_business ?? 'N/A';
                                $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                                $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                                $unassignedEmployees = $employees->whereNotIn('id', $assignedEmployeeIds)->where('department', 'Accounts');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Accounts</td>
                                <td>{{ $task->clientname ?? 'N/A' }}</td>
                                <td>{{ $task->email ?? 'N/A' }}</td>
                                <td>{{ $dueDateOrYear }}</td>
                                <td>{{ $business }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($task->priority ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $task->manager_status == 'rejected' ? 'danger' : ($task->manager_status == 'in_progress' ? 'warning' : ($task->manager_status == 'completed' ? 'success' : ($task->manager_status == 'not_interested' ? 'warning' : 'danger'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->manager_status ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('team_lead.tasks.update_team_status', ['id' => $task->id, 'type' => 't2']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="team_status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 120px;">
                                            <option value="" disabled {{ is_null($task->team_status) ? 'selected' : '' }}>Select Status</option>
                                            <option value="rejected" {{ $task->team_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="in_progress" {{ $task->team_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->team_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="not_interested" {{ $task->team_status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                            <option value="in_completed" {{ $task->team_status == 'in_completed' ? 'selected' : '' }}>In Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if (!empty($task->attachments))
                                        <div class="attachment-grid">
                                            @foreach ((array) $task->attachments as $url)
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
                                                            <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @else
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
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
                                    <a href="{{ route('team_lead.task_detail', $task->id) }}" class="btn btn-sm btn-success">View</a>
                                </td>
                             ?? 'N/A'
                                <td>
                                    <a href="{{ route('team_lead.subtask.create', $task->id) }}" class="btn btn-sm btn-warning mb-1">Subtask</a>
                                </td>
                                <td>
                                    <a href="{{ route('team_lead.subtask.list', $task->id) }}" class="btn btn-sm btn-success">Subtask Assign</a>
                                </td>
                                <td>
                                    <a href="{{ route('chat.group', $task->id) }}" class="btn btn-sm btn-outline-primary">Group Chat</a>
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

        @if($accountsthst->isNotEmpty())
            <h3 class="account-type-header">HST Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Due Date</th>
                            <th>Business</th>
                            <th>Priority</th>
                            <th>Manager Status</th>
                            <th>Team Status</th>
                            <th>Attachments</th>
                            <th>View</th>
                          ?? 'N/A'
                            <th>Subtask</th>
                            <th>Subtask View</th>
                            <th>Group Chat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountsthst as $task)
                            @php
                                $accountType = 'HST';
                                $dueDateOrYear = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A';
                                $business = $task->nature_of_business ?? 'N/A';
                                $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                                $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                                $unassignedEmployees = $employees->whereNotIn('id', $assignedEmployeeIds)->where('department', 'Accounts');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Accounts</td>
                                <td>{{ $task->clientname ?? 'N/A' }}</td>
                                <td>{{ $task->email ?? 'N/A' }}</td>
                                <td>{{ $dueDateOrYear }}</td>
                                <td>{{ $business }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($task->priority ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $task->manager_status == 'rejected' ? 'danger' : ($task->manager_status == 'in_progress' ? 'warning' : ($task->manager_status == 'completed' ? 'success' : ($task->manager_status == 'not_interested' ? 'warning' : 'danger'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->manager_status ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('team_lead.tasks.update_team_status', ['id' => $task->id, 'type' => 'hst']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="team_status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 120px;">
                                            <option value="" disabled {{ is_null($task->team_status) ? 'selected' : '' }}>Select Status</option>
                                            <option value="rejected" {{ $task->team_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="in_progress" {{ $task->team_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->team_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="not_interested" {{ $task->team_status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                            <option value="in_completed" {{ $task->team_status == 'in_completed' ? 'selected' : '' }}>In Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if (!empty($task->attachments))
                                        <div class="attachment-grid">
                                            @foreach ((array) $task->attachments as $url)
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
                                                            <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @else
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
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
                                    <a href="{{ route('team_lead.task_detail', $task->id) }}" class="btn btn-sm btn-success">View</a>
                                </td>
                             ?? 'N/A'
                                <td>
                                    <a href="{{ route('team_lead.subtask.create', $task->id) }}" class="btn btn-sm btn-warning mb-1">Subtask</a>
                                </td>
                                <td>
                                    <a href="{{ route('team_lead.subtask.list', $task->id) }}" class="btn btn-sm btn-success">Subtask Assign</a>
                                </td>
                                <td>
                                    <a href="{{ route('chat.group', $task->id) }}" class="btn btn-sm btn-outline-primary">Group Chat</a>
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

        @if($manageroperation->isNotEmpty())
            <h3 class="account-type-header">Operations Tasks</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Manager Status</th>
                            <th>Team Status</th>
                            <th>Attachments</th>
                            <th>View</th>
                          ?? 'N/A'
                            <th>Subtask</th>
                            <th>Subtask View</th>
                            <th>Group Chat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($manageroperation as $task)
                            @php
                                $dueDateOrYear = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A';
                                $assignedEmployeeIds = array_filter(explode(',', $task->employee_id ?? ''));
                                $assignedEmployees = $employees->whereIn('id', $assignedEmployeeIds);
                                $unassignedEmployees = $employees->whereNotIn('id', $assignedEmployeeIds)->where('department', 'Operations');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Operations</td>
                                <td>{{ $task->title ?? 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($task->description ?? 'N/A', 50) }}</td>
                                <td>{{ $dueDateOrYear }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->manager_status == 'rejected' ? 'danger' : ($task->manager_status == 'in_progress' ? 'warning' : ($task->manager_status == 'completed' ? 'success' : ($task->manager_status == 'not_interested' ? 'warning' : 'danger'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->manager_status ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('team_lead.tasks.update_team_status', ['id' => $task->id, 'type' => 'operation']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="team_status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 120px;">
                                            <option value="" disabled {{ is_null($task->team_status) ? 'selected' : '' }}>Select Status</option>
                                            <option value="rejected" {{ $task->team_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="in_progress" {{ $task->team_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->team_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="not_interested" {{ $task->team_status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                            <option value="in_completed" {{ $task->team_status == 'in_completed' ? 'selected' : '' }}>In Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if (!empty($task->attachments))
                                        <div class="attachment-grid">
                                            @foreach ((array) $task->attachments as $url)
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
                                                            <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                            <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                        </a>
                                                    @else
                                                        <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                            <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
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
                                    <a href="{{ route('team_lead.task_detail', $task->id) }}" class="btn btn-sm btn-success">View</a>
                                </td>
                             ?? 'N/A'
                                <td>
                                    <a href="{{ route('team_lead.subtask.create', $task->id) }}" class="btn btn-sm btn-warning mb-1">Subtask</a>
                                </td>
                                <td>
                                    <a href="{{ route('team_lead.subtask.list', $task->id) }}" class="btn btn-sm btn-success">Subtask Assign</a>
                                </td>
                                <td>
                                    <a href="{{ route('chat.group', $task->id) }}" class="btn btn-sm btn-outline-primary">Group Chat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted">No Operations Tasks Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection