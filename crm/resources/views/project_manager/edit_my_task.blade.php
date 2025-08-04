@extends('layout.app')

<style>
    .card-body { max-height: calc(100vh - 200px); overflow-y: auto; padding: 1.5rem; }
    .form-group { margin-bottom: 0.75rem; }
    .form-control { height: 2.25rem; font-size: 0.9rem; }
    textarea.form-control { height: 4rem; resize: vertical; }
    .btn-light { padding: 0.5rem 2rem; font-size: 0.9rem; }
    .alert { margin-bottom: 1rem; font-size: 0.85rem; }
    .text-danger { font-size: 0.8rem; }
    .card-title { font-size: 1.25rem; margin-bottom: 1rem; }
    .custom-select { border-radius: 0.25rem; padding: 0.5rem; border: 1px solid #555; }
    .custom-select:focus { border-color: #999; background: #222; color: #fff; outline: none; box-shadow: none; }
</style>

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if (session('success_swal'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success_swal') }}",
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }));
                </script>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
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
                    <form method="POST" action="{{ route('project_manager.mytask_update', $task->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php $isAccounts = $task->account !== null; @endphp

                        @if ($isAccounts)
                            @php $acc = $task->account; @endphp

                            <h5 class="text-white mt-4">Account Details</h5>

                            <div class="form-group">
                                <label class="text-white">Client Name</label>
                                <input type="text" name="client_name" class="form-control text-white" value="{{ old('client_name', $acc->clientname) }}">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Email</label>
                                <input type="email" name="client_email" class="form-control text-white" value="{{ old('client_email', $acc->email) }}">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Phone</label>
                                <input type="text" name="client_contact" class="form-control text-white" value="{{ old('client_contact', $acc->phone) }}">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Due Date</label>
                                <input type="date" name="deadline" class="form-control text-white" value="{{ old('deadline', $acc->due_date) }}">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Nature of Business</label>
                                <textarea name="nature_of_business" class="form-control text-white">{{ old('nature_of_business', $acc->nature_of_business) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Priority</label>
                                <select name="priority" class="form-control custom-select text-white">
                                    <option value="Low" {{ old('priority', $acc->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ old('priority', $acc->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="High" {{ old('priority', $acc->priority) == 'High' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Add Attachments</label>
                                <input type="file" name="attachments" class="form-control text-white">
                            </div>
                        @endif

                       

                        <div class="form-group">
                            <label class="text-white">Status</label>
                            <select name="status" class="form-control custom-select text-white">
                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ old('status', $task->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-white">Department</label>
                            <select name="department_id" class="form-control custom-select text-white">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="text-white">Team Lead</label>
                            <select name="team_lead_id" class="form-control custom-select text-white">
                                <option value="">Select Team Lead</option>
                                @foreach ($team_leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('team_lead_id', $task->team_lead_id) == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->name }}
                                    </option>
                                @endforeach
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
