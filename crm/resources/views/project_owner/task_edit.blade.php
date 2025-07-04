@extends('layout.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h2 class="card-title text-center text-white">Edit Task</h2>
                    <form method="POST" action="{{ route('project_manager.tasks.update', $task->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="text-white">Task Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $task->name) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Client Name</label>
                            <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $task->client_name) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Description</label>
                            <textarea name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="text-white">Client Email</label>
                            <input type="email" name="client_email" class="form-control" value="{{ old('client_email', $task->client_email) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Client Contact</label>
                            <input type="text" name="client_contact" class="form-control" value="{{ old('client_contact', $task->client_contact) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Project Manager</label>
                            <select name="project_manager_id" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Project Manager</option>
                                @foreach ($departments as $department)
                                    @foreach ($department->projectManagers as $manager)
                                        <option value="{{ $manager->id }}" {{ $manager->id == $task->project_manager_id ? 'selected' : '' }}>
                                            {{ $department->name }} ({{ $manager->name }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-white">Manager Email</label>
                            <input type="email" name="manager_email" class="form-control" value="{{ old('manager_email', $task->manager_email) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $task->start_date) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Deadline</label>
                            <input type="date" name="deadline" class="form-control" value="{{ old('deadline', $task->deadline) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white">Priority</label>
                            <select name="priority" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Priority</option>
                                <option value="Low" {{ old('priority', $task->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority', $task->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ old('priority', $task->priority) == 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-light">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
