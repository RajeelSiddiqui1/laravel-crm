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

                            <td>{{ $subtask->comment ?? '—' }}</td>

                            <td>
                                <a href="{{ route('employee.subtask.edit', $subtask->id) }}" class="btn btn-sm btn-primary">
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
