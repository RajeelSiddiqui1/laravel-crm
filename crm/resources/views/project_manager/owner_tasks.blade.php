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
                    <th>Audio</th>
                    <th>Group chat</th>
                    <th>craete task</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->client_name }}</td>
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
                        <td>
                            <a href="{{ route('chat.group', $task->id) }}" class="btn btn-primary btn-sm">Group Chat</a>
                        </td>
                        <td>

                            <a href="{{ route('project_manager.mytask_create', parameters: $task->id) }}"
                                class="btn btn-sm btn-success">craete</a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
