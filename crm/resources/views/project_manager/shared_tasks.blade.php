@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-3">Shared With Me</h2>

        @if (session('success_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', () =>
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success_swal') }}",
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                );
            </script>
        @endif
        @if (session('error_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', () =>
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error_swal') }}",
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                );
            </script>
        @endif

        <div style="max-height:70vh;overflow-y:auto;">
            <table class="table table-bordered table-hover table-sm">
                <thead class="bg-dark text-white sticky-top">
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Priority</th>
                        <th>Start</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Team Lead</th>
                        <th>View</th>
                        <th>Group Chat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->client_name }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>{{ $task->start_date->format('Y-m-d') }}</td>
                            <td>{{ $task->deadline->format('Y-m-d') }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            {{-- status2 (read-only badge) --}}
                            <td>
                                <span
                                    class="badge badge-{{ $task->status2 == 'approved' ? 'success' : ($task->status2 == 'rejected' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($task->status2) }}
                                </span>
                            </td>

                            {{-- status3 dropdown --}}
                            <td>
                                <form method="POST" action="{{ route('project_manager.update_status3', $task->id) }}"
                                    class="d-inline">
                                    @csrf @method('PATCH')
                                    <select name="status3" class="form-control form-select-sm"
                                        onchange="this.form.submit()">
                                        @foreach (['pending', 'approved', 'rejected', 'lated'] as $s)
                                            <option value="{{ $s }}"
                                                {{ $task->status3 == $s ? 'selected' : '' }}>
                                                {{ ucfirst($s) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>{{ $task->teamLead->name ?? '—' }}</td>
                            <td>
                                <a href="{{ route('project_manager.tasks.detail', $task->id) }}"
                                    class="btn btn-sm btn-primary">View</a>
                            </td>
                            <td>
                                <a href="{{ route('chat.group', $task->id) }}" class="btn btn-success btn-sm">Group
                                    Chat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No shared tasks.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
