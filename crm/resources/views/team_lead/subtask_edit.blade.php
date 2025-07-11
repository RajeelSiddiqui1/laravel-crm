@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white shadow-lg">
            <div class="card-header border-bottom border-secondary">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Subtask</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('employee.subtask.update', $subtask->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $subtask->title) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', \Carbon\Carbon::parse($subtask->start_date)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control"
                                value="{{ old('start_time', \Carbon\Carbon::parse($subtask->start_time)->format('H:i')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', \Carbon\Carbon::parse($subtask->end_date)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control"
                                value="{{ old('end_time', \Carbon\Carbon::parse($subtask->end_time)->format('H:i')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $subtask->description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-light">
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
