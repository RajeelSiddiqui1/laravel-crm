@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="card  text-white shadow-lg">
            <div class="card-header border-bottom border-secondary">
                <h4 class="mb-0">Update Subtask</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('team_lead.subtask.update', $subtask->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $subtask->title) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lead</label>
                            <input type="number" name="lead" class="form-control"
                                value="{{ old('lead', $subtask->lead) }}" required>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Assign to Employee</label>
                        <select name="assigned_employee_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach ($assignedEmployees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ $subtask->assigned_employee_id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} - {{ $emp->department->name ?? 'No Department' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="date" name="start_date" class="form-control"
                        value="{{ old('start_date', $subtask->start_date) }}">

                    <input type="date" name="end_date" class="form-control"
                        value="{{ old('end_date', $subtask->end_date) }}">

                    <input type="time" name="start_time" class="form-control"
                        value="{{ old('start_time', $subtask->start_time) }}">

                    <input type="time" name="end_time" class="form-control"
                        value="{{ old('end_time', $subtask->end_time) }}">


                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $subtask->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('team_lead.subtask.list', $subtask->owner_task_id) }}"
                            class="btn btn-outline-light">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Update Subtask
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
