@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h3 class="text-white">Edit Subtask</h3>

        <form action="{{ route('employee.subtask.update', $subtask->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label text-white">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $subtask->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Description</label>
                <textarea name="description" class="form-control" required>{{ old('description', $subtask->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Date</label>
                <input type="date" name="start_date" class="form-control"
                    value="{{ old('start_date', \Carbon\Carbon::parse($subtask->start_date)->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Start Time</label>
                <input type="time" name="start_time" class="form-control"
                    value="{{ old('start_time', \Carbon\Carbon::parse($subtask->start_time)->format('H:i')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Date</label>
                <input type="date" name="end_date" class="form-control"
                    value="{{ old('end_date', \Carbon\Carbon::parse($subtask->end_date)->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">End Time</label>
                <input type="time" name="end_time" class="form-control"
                    value="{{ old('end_time', \Carbon\Carbon::parse($subtask->end_time)->format('H:i')) }}">
            </div>







            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
