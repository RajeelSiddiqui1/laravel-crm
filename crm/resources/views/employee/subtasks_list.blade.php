@extends('layout.app')

@section('content')
    <div class="container">
        <h3 class="text-white">My Assigned Subtasks</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subtasks as $index => $subtask)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subtask->title }}</td>
                            <td>{{ $subtask->description }}</td>
                            <td>
                                <span
                                    class="badge 
        @if ($subtask->status == 'pending') bg-secondary
        @elseif ($subtask->status == 'in_progress') bg-warning
        @elseif ($subtask->status == 'completed') bg-success
        @elseif ($subtask->status == 'reject') bg-danger
        @elseif ($subtask->status == 'late') bg-dark
        @else bg-light text-dark @endif">
                                    {{ ucfirst(str_replace('_', ' ', $subtask->status)) }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('employee.subtask.update_status', $subtask->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" {{ $subtask->status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="in_progress"
                                            {{ $subtask->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $subtask->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                </form>

                            </td>

                            <td>
                                <a href="{{ route('employee.subtask.edit', $subtask->id) }}"
                                    class="btn btn-sm btn-primary">
                                    Info
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No subtasks assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
