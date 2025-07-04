@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Project Owner Dashboard</h2>
            </div>


        </div>
        <div class="row">
            <div class="col-md-12 justify-content-end">
                <a href="{{ route('project_owner.tasks.createview') }}" class="btn btn-primary mb-3">Create Task</a>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Task Name</th>
                        <th>Department</th>
                        <th>View</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->department->name ?? 'No Department' }}</td>
                            <td>
                                @if ($task->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif ($task->status == 'in_progress')
                                    <span class="badge badge-warning">In Progress</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('project_owner.task_detail', $task->id) }}"
                                    class="btn btn-sm btn-primary">View</a>
                            </td>

                            <td>
                                <a href="{{ route('project_manager.tasks.edit', $task->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('project_manager.tasks.delete', $task->id) }}" method="POST"
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
                            <td colspan="5" class="text-center text-muted">No Tasks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
