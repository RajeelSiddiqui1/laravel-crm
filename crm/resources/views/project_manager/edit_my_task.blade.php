@extends('layout.app')

<style>
    .card-body{max-height:calc(100vh - 200px);overflow-y:auto;padding:1.5rem;}
    .form-group{margin-bottom:.75rem;}
    .form-control{height:2.25rem;font-size:.9rem;}
    textarea.form-control{height:4rem;resize:vertical;}
    .btn-light{padding:.5rem 2rem;font-size:.9rem;}
    .alert{margin-bottom:1rem;font-size:.85rem;}
    .text-danger{font-size:.8rem;}
    .card-title{font-size:1.25rem;margin-bottom:1rem;}
    .custom-select{border-radius:.25rem;padding:.5rem;border:1px solid #555;}
    .custom-select:focus{border-color:#999;background:#222;color:#fff;outline:none;box-shadow:none;}
</style>

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success_swal'))
                <script>
                    document.addEventListener('DOMContentLoaded',()=>Swal.fire({title:'Success!',text:"{{ session('success_swal') }}",icon:'success',confirmButtonText:'OK'}));
                </script>
            @endif
            @if(session('error_swal'))
                <script>
                    document.addEventListener('DOMContentLoaded',()=>Swal.fire({title:'Error!',text:"{{ session('error_swal') }}",icon:'error',confirmButtonText:'OK'}));
                </script>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h2 class="card-title text-center text-white">Edit Task</h2>
                    <form method="POST" action="{{ route('project_manager.mytask_update', $task->id) }}">
                        @csrf @method('PUT')

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
                            <label class="text-white">Department</label>
                            <select name="department_id" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $task->department_id)==$dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-white">Team Lead</label>
                            <select name="team_lead_id" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Team Lead</option>
                                @foreach($team_leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('team_lead_id', $task->team_lead_id)==$lead->id ? 'selected' : '' }}>{{ $lead->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-white">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::parse($task->start_date)->format('Y-m-d')) }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white">Deadline</label>
                            <input type="date" name="deadline" class="form-control" value="{{ old('deadline', \Carbon\Carbon::parse($task->deadline)->format('Y-m-d')) }}">
                        </div>

                        <div class="form-group">
                            <label class="text-white">Priority</label>
                            <select name="priority" class="form-control custom-select bg-dark text-white">
                                <option value="">Select Priority</option>
                                <option value="Low"    {{ old('priority', $task->priority)=='Low'    ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority', $task->priority)=='Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High"   {{ old('priority', $task->priority)=='High'   ? 'selected' : '' }}>High</option>
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