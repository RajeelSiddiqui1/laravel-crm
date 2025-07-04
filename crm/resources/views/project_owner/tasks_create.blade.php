@extends('layout.app')

<style>
    .card-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding: 1.5rem;
    }
    .form-group {
        margin-bottom: 0.75rem;
    }
    .form-control {
        height: 2.25rem;
        font-size: 0.9rem;
    }
    textarea.form-control {
        height: 4rem;
        resize: vertical;
    }
    .btn-light {
        padding: 0.5rem 2rem;
        font-size: 0.9rem;
    }
    .alert {
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }
    .text-danger {
        font-size: 0.8rem;
    }
    .card-title {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }
    .custom-select {
        border-radius: 0.25rem;
        padding: 0.5rem;
        border: 1px solid #555;
    }
    .custom-select:focus {
        border-color: #999;
        background-color: #222;
        color: #fff;
        outline: none;
        box-shadow: none;
    }
</style>

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
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
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
                    <h2 class="card-title text-center text-white">Create Task</h2>
                    <form method="POST" action="{{ route('project_manager.tasks.post') }}">
                        @csrf

                        <div class="form-group">
                            <label class="text-white" for="name">Task Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label class="text-white" for="client_name">Client Name</label>
                            <input type="text" name="client_name" id="client_name" class="form-control" value="{{ old('client_name') }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="client_email">Client Email</label>
                            <input type="email" name="client_email" id="client_email" class="form-control" value="{{ old('client_email') }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="client_contact">Client Contact</label>
                            <input type="text" name="client_contact" id="client_contact" class="form-control" value="{{ old('client_contact') }}">
                        </div>

                       

                        <div class="form-group">
                            <label class="text-white" for="project_manager_id">Project Manager</label>
                            <select name="project_manager_id" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Project Manager</option>
                                @foreach ($departments as $department)
                                    @foreach ($department->projectManagers as $manager)
                                        <option value="{{ $manager->id }}">
                                            {{ $department->name }} ({{ $manager->name }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="manager_email">Manager Email</label>
                            <input type="email" name="manager_email" id="manager_email" class="form-control" value="{{ old('manager_email') }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="deadline">Deadline</label>
                            <input type="date" name="deadline" id="deadline" class="form-control" value="{{ old('deadline') }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="priority">Priority</label>
                            <select name="priority" id="priority" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Priority</option>
                                <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-white" for="status">Status</label>
                            <select name="status" id="status" class="form-control custom-select bg-dark text-white" hidden>
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-light">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
