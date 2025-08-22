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
                    <form method="POST" action="{{ route('project_manager.mytask_update', $task->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($accountType === 'AccountT1')
                            <h5 class="text-white mt-4">Account Details (AccountT1)</h5>

                            <div class="form-group">
                                <label class="text-white">Client Name</label>
                                <input type="text" name="clientname_t1" class="form-control text-white" value="{{ old('clientname_t1', $account->clientname) }}">
                                @error('clientname_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Period</label>
                                <input type="text" name="period_t1" class="form-control text-white" value="{{ old('period_t1', $account->period) }}">
                                @error('period_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Driving License</label>
                                <input type="text" name="driving_license_t1" class="form-control text-white" value="{{ old('driving_license_t1', $account->driving_license) }}">
                                @error('driving_license_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">SIM Number</label>
                                <input type="text" name="sim_number_t1" class="form-control text-white" value="{{ old('sim_number_t1', $account->sim_number) }}">
                                @error('sim_number_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Business Name</label>
                                <input type="text" name="bussiness_name_t1" class="form-control text-white" value="{{ old('bussiness_name_t1', $account->bussiness_name) }}">
                                @error('bussiness_name_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Family Name</label>
                                <input type="text" name="famliy_name_t1" class="form-control text-white" value="{{ old('famliy_name_t1', $account->famliy_name) }}">
                                @error('famliy_name_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Year</label>
                                <input type="text" name="year_t1" class="form-control text-white" value="{{ old('year_t1', $account->year) }}">
                                @error('year_t1') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Add Attachments</label>
                                <input type="file" name="attachments_t1[]" class="form-control text-white" multiple>
                                @error('attachments_t1.*') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @elseif ($accountType === 'AccountT2')
                            <h5 class="text-white mt-4">Account Details (AccountT2)</h5>

                            <div class="form-group">
                                <label class="text-white">Client Name</label>
                                <input type="text" name="clientname_t2" class="form-control text-white" value="{{ old('clientname_t2', $account->clientname) }}">
                                @error('clientname_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Email</label>
                                <input type="email" name="email_t2" class="form-control text-white" value="{{ old('email_t2', $account->email) }}">
                                @error('email_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Phone</label>
                                <input type="text" name="phone_t2" class="form-control text-white" value="{{ old('phone_t2', $account->phone) }}">
                                @error('phone_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Corporation Name</label>
                                <input type="text" name="corporation_name_t2" class="form-control text-white" value="{{ old('corporation_name_t2', $account->corporation_name) }}">
                                @error('corporation_name_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Corporation Number</label>
                                <input type="text" name="corporation_number_t2" class="form-control text-white" value="{{ old('corporation_number_t2', $account->corporation_number) }}">
                                @error('corporation_number_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Due Date</label>
                                <input type="date" name="due_date_t2" class="form-control text-white" value="{{ old('due_date_t2', $account->due_date) }}">
                                @error('due_date_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Nature of Business</label>
                                <textarea name="nature_of_business_t2" class="form-control text-white">{{ old('nature_of_business_t2', $account->nature_of_business) }}</textarea>
                                @error('nature_of_business_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Priority</label>
                                <select name="priority_t2" class="form-control custom-select text-white">
                                    <option value="low" {{ old('priority_t2', $account->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority_t2', $account->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority_t2', $account->priority) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority_t2') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Add Attachments</label>
                                <input type="file" name="attachments_t2" class="form-control text-white">
                                @error('attachments_t2.*') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @elseif ($accountType === 'AccountHST')
                            <h5 class="text-white mt-4">Account Details (AccountHST)</h5>

                            <div class="form-group">
                                <label class="text-white">Client Name</label>
                                <input type="text" name="clientname_hst" class="form-control text-white" value="{{ old('clientname_hst', $account->clientname) }}">
                                @error('clientname_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Email</label>
                                <input type="email" name="email_hst" class="form-control text-white" value="{{ old('email_hst', $account->email) }}">
                                @error('email_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Phone</label>
                                EXTRA CODE
                                <input type="text" name="phone_hst" class="form-control text-white" value="{{ old('phone_hst', $account->phone) }}">
                                @error('phone_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Corporation Name</label>
                                <input type="text" name="corporation_name_hst" class="form-control text-white" value="{{ old('corporation_name_hst', $account->corporation_name) }}">
                                @error('corporation_name_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Corporation Number</label>
                                <input type="text" name="corporation_number_hst" class="form-control text-white" value="{{ old('corporation_number_hst', $account->corporation_number) }}">
                                @error('corporation_number_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Due Date</label>
                                <input type="date" name="due_date_hst" class="form-control text-white" value="{{ old('due_date_hst', $account->due_date) }}">
                                @error('due_date_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Nature of Business</label>
                                <textarea name="nature_of_business_hst" class="form-control text-white">{{ old('nature_of_business_hst', $account->nature_of_business) }}</textarea>
                                @error('nature_of_business_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Priority</label>
                                <select name="priority_hst" class="form-control custom-select text-white">
                                    <option value="low" {{ old('priority_hst', $account->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority_hst', $account->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority_hst', $account->priority) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority_hst') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-white">Add Attachments</label>
                                <input type="file" name="attachments_hst[]" class="form-control text-white" multiple>
                                @error('attachments_hst.*') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="text-white">Task Priority</label>
                            <select name="priority" class="form-control custom-select text-white">
                                <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="text-white">Status</label>
                            <select name="status" class="form-control custom-select text-white">
                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ old('status', $task->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="text-white">Department</label>
                            <select name="department_id" id="department_id" class="form-control custom-select text-white">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="text-white">Team Lead</label>
                            <select name="team_lead_id" id="team_lead_id" class="form-control custom-select text-white">
                                <option value="">Select Team Lead</option>
                                @foreach ($team_leads as $lead)
                                    <option value="{{ $lead->id }}" data-department="{{ $lead->department_id }}" {{ old('team_lead_id', $task->team_lead_id) == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_lead_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-light">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($team_leads)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const departmentSelect = document.getElementById('department_id');
                const teamLeadSelect = document.getElementById('team_lead_id');

                departmentSelect.addEventListener('change', function () {
                    const selectedDept = this.value;
                    const teamLeads = teamLeadSelect.querySelectorAll('option[data-department]');
                    teamLeadSelect.innerHTML = '<option value="">Select Team Lead</option>';

                    teamLeads.forEach(option => {
                        if (option.getAttribute('data-department') === selectedDept) {
                            teamLeadSelect.appendChild(option.cloneNode(true));
                        }
                    });
                });

                // Trigger change to set initial team leads
                departmentSelect.dispatchEvent(new Event('change'));
            });
        </script>
    @endif
</div>
@endsection