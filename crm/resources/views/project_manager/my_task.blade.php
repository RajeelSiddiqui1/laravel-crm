@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">My Tasks Dashboard</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 justify-content-end">
                <a href="{{ route('project_manager.mytask_create') }}" class="btn btn-primary mb-3">Create Task</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Task Name</th>
                        <th>Department</th>
                        <th>TeamLeader</th>
                        <th>Teamlead Status</th>
                        <th>Shared Status</th>
                        <th>Your Status</th>
                        <th>Upadte Status</th>
                        <th>Shared Task</th>
                        <th>View</th>
                 
                        <th>Group Chats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->department->name ?? 'No Department' }}</td>
                            <td>{{ $task->teamLead->name ?? 'No Team Lead' }}</td>
                            {{-- first column: original status (read-only badge) --}}
                            <td>
                                <span
                                    class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td>
                                @if ($task->status3)
                                    <span
                                        class="badge badge-{{ $task->status3 == 'approved' ? 'success' : ($task->status3 == 'lated' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status3)) }}
                                    </span>
                                @else
                                    N/A
                                @endif

                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $task->status2 == 'approved' ? 'success' : ($task->status2 == 'lated' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status2)) }}
                                </span>
                            </td>


                            {{-- second column: status2 dropdown that actually updates --}}
                            <td>
                                <form method="POST" action="{{ route('project_manager.update_status2', $task->id) }}"
                                    class="d-inline">
                                    @csrf @method('PATCH')
                                    <select name="status2" class="form-control form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="pending" {{ $task->status2 == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ $task->status2 == 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ $task->status2 == 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                        <option value="lated" {{ $task->status2 == 'lated' ? 'selected' : '' }}>Lated
                                        </option>
                                    </select>
                                </form>
                            </td>

                            <td>
                                @php
                                    // already shared with this manager?
                                    $shared = \App\Models\SharedTask::where('owner_task_id', $task->id)
                                        ->where('assigned_by', auth()->guard('project_manager')->id())
                                        ->first();
                                @endphp

                                <form action="{{ route('project_manager.share_task') }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <input type="hidden" name="department_id" value="{{ $task->department_id }}">

                                    <select name="assigned_to" class="form-control form-select-sm"
                                        onchange="this.form.submit();">
                                        <option value="">Share with…</option>
                                        @foreach ($otherManagers as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ optional($shared)->assigned_to == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>


                            <td>
                                <a href="{{ route('project_manager.my_task_detail', $task->id) }}"
                                    class="btn btn-sm btn-success">View</a>
                            </td>
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}" class="btn btn-primary btn-sm">Group
                                    Chat</a>
                            </td>
                            <td>
                                <a href="{{ route('project_manager.mytask_edit', $task->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('project_manager.mytask_delete', $task->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
