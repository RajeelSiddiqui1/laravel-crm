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

        .table th, .table td {
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

        .btn-success, .btn-warning, .btn-danger {
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

        .badge-info { background: #17a2b8; }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ffc107; }
        .badge-secondary { background: #6c757d; }

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
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4 fw-bold">My Tasks Dashboard</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('project_manager.mytask_create') }}" class="btn btn-primary mb-3">Create Task</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>Department</th>
                        <th>Team Lead</th>
                        <th>Team Lead Status</th>
                        <th>Your Status</th>
                        <th>Shared</th>
                        @if ($tasks->pluck('account')->filter()->isNotEmpty())
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Due Date</th>
                            <th>Business</th>
                            <th>Priority</th>
                            <th>Attachments</th>
                        @else
                            <th>Account Info</th>
                        @endif
                        <th>View</th>
                        <th>Chat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr class="align-middle text-center">
                            <td>{{ $loop->iteration }}</td>
                            <!-- Department -->
                            <td>
                                @if ($task->department && $task->department->name === 'Call operator')
                                    <span class="badge badge-info">Call Operator</span>
                                @else
                                    {{ $task->department->name ?? 'No Department' }}
                                @endif
                            </td>
                            <!-- Team Lead -->
                            <td>{{ $task->teamLead->name ?? 'N/A' }}</td>
                            <!-- Team Lead Status -->
                            <td>
                                <span class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <!-- Your Status -->
                            <td>
                                <form method="POST" action="{{ route('project_manager.update_status2', $task->id) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <select name="status2" class="form-control form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" {{ $task->status2 == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $task->status2 == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $task->status2 == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="lated" {{ $task->status2 == 'lated' ? 'selected' : '' }}>Lated</option>
                                    </select>
                                </form>
                            </td>
                            <!-- Shared Status -->
                            <td>
                                <span class="badge badge-{{ $task->is_shared ? 'info' : 'secondary' }}">
                                    {{ $task->is_shared ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <!-- Account Info -->
                            @if ($tasks->pluck('account')->filter()->isNotEmpty())
                                <td>{{ $task->account ? $task->account->clientname : 'N/A' }}</td>
                                <td>{{ $task->account ? $task->account->email : 'N/A' }}</td>
                                <td>{{ $task->account ? $task->account->due_date : 'N/A' }}</td>
                                <td>{{ $task->account ? $task->account->nature_of_business : 'N/A' }}</td>
                                <td>
                                    @if ($task->account)
                                        <span class="badge badge-{{ $task->account->priority == 'high' ? 'danger' : ($task->account->priority == 'medium' ? 'warning' : 'success') }}">
                                            {{ ucfirst($task->account->priority) }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if ($task->account && !empty($task->account->attachments))
                                        <div class="attachment-grid">
                                            @foreach ((array)$task->account->attachments as $url)
                                                @php $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)); @endphp
                                                <div class="attachment-item">
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <img src="{{ asset('storage/' . $url) }}" alt="Attachment">
                                                    @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                        <video controls src="{{ asset('storage/' . $url) }}"></video>
                                                    @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                        <audio controls src="{{ asset('storage/' . $url) }}"></audio>
                                                    @else
                                                        <a href="{{ asset('storage/' . $url) }}" target="_blank" class="btn btn-outline-light btn-sm w-100">View</a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">No Attachments</span>
                                    @endif
                                </td>
                            @else
                                <td>
                                    @if ($task->account)
                                        <div><strong>Client:</strong> {{ $task->account->clientname }}</div>
                                        <div><strong>Email:</strong> {{ $task->account->email }}</div>
                                        <div><strong>Due:</strong> {{ $task->account->due_date }}</div>
                                        <div><strong>Business:</strong> {{ $task->account->nature_of_business }}</div>
                                        <div><strong>Priority:</strong> {{ ucfirst($task->account->priority) }}</div>
                                        @if ($task->account && !empty($task->account->attachments))
                                            <div class="attachment-grid">
                                                @foreach ((array)$task->account->attachments as $url)
                                                    @php $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)); @endphp
                                                    <div class="attachment-item">
                                                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                            <img src="{{ asset('storage/' . $url) }}" alt="Attachment">
                                                        @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                            <video controls src="{{ asset('storage/' . $url) }}"></video>
                                                        @elseif (in_array($ext, ['mp3', 'wav', 'ogg']))
                                                            <audio controls src="{{ asset('storage/' . $url) }}"></audio>
                                                        @else
                                                            <a href="{{ asset('storage/' . $url) }}" target="_blank" class="btn btn-outline-light btn-sm w-100">View</a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No Attachments</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No Account Info</span>
                                    @endif
                                </td>
                            @endif
                            <!-- View -->
                            <td>
                                <a href="{{ route('project_manager.my_task_detail', $task->id) }}" class="btn btn-sm btn-success">View</a>
                            </td>
                            <!-- Chat -->
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}" class="btn btn-sm btn-primary">Chat</a>
                            </td>
                            <!-- Actions -->
                            <td>
                                <a href="{{ route('project_manager.mytask_edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('project_manager.mytask_delete', $task->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            @if ($tasks->pluck('account')->filter()->isNotEmpty())
                                <td colspan="14" class="text-center text-muted">No Tasks Found</td>
                            @else
                                <td colspan="8" class="text-center text-muted">No Tasks Found</td>
                            @endif
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection