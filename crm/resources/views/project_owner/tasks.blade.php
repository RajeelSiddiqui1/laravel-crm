@extends('layout.app')

@section('content')
    <div class="container mt-4">
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

        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Project Owner Dashboard</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('project_owner.tasks.createview') }}" class="btn btn-primary mb-3">Create Task</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Client Name</th>
                        <th>Assigned Managers</th>
                        <th>Audio</th>
                        <th>owner task</th>
                        <th>Group Chats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->client_name }}</td>
                            <td>
                                @php
                                    $managerIds = is_string($task->managers) ? json_decode($task->managers, true) : ($task->managers ?? []);
                                    $managerNames = $managerIds ? App\Models\ProjectManager::whereIn('id', $managerIds)->pluck('name')->implode(', ') : 'No Managers Assigned';
                                @endphp
                                {{ $managerNames }}
                            </td>
                             <td>
                                @if ($task->audio_url)
                                    <audio controls class="w-100">
                                        <source src="{{ $task->audio_url }}" type="audio/webm">
                                        Your browser does not support the audio element.
                                    </audio>
                                @else
                                    No Audio
                                @endif
                            </td>
                       
                              <td>  <a href="{{ route('chat.group', $task->id) }}" class="btn btn-primary btn-sm">Group Chat</a>
                            </td>
                            <td>
                                <a href="{{ route('project_owner.tasks.edit', $task->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('project_owner.tasks.delete', $task->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('project_owner.tasks.view', $task->id) }}"
                                    class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection