@extends('layout.app')

@section('content')
    <div class="container">
        <h2>Owner Tasks</h2>
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

        <table class="table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>No</th>
                    <th>Client Name</th>
                    <th>Priority</th>
                    <th>Start Date</th>
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
                                class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td>
                            @if ($task->teamLead)
                                {{ $task->teamLead->name }}
                            @else
                                <form method="POST"
                                    action="{{ route('project_manager.tasks.assign_team_lead', $task->id) }}">
                                    @csrf
                                    <select name="team_lead_id" class="form-control form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="">Select</option>
                                        @foreach ($teamLeads as $lead)
                                            @if ($lead->department_id == $task->department_id)
                                                <option value="{{ $lead->id }}">{{ $lead->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
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
                        <td colspan="8" class="text-center text-muted">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
