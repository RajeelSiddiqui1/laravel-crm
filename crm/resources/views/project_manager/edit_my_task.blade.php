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
        background: #222;
        color: #fff;
        outline: none;
        box-shadow: none;
    }
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
                        <h2 class="card-title text-center text-white">Edit Task ({{ $accountType ?? $operationType }})</h2>

                        @if ($account)
                            <!-- Account Form -->
                            @if ($account->ownerTask)
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="text-white">Parent Task Client Name</label>
                                        <input type="text" class="form-control text-white"
                                            value="{{ $account->ownerTask->client_name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Parent Task Audio</label>
                                        @if ($account->ownerTask->audio_url)
                                            <audio controls class="w-100">
                                                <source src="{{ $account->ownerTask->audio_url }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @else
                                            <input type="text" class="form-control bg-secondary text-white"
                                                value="No audio available" readonly>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('project_manager.mytask_update', $account->id) }}"
                                enctype="multipart/form-data">
                                @csrf @method('PUT')

                                <h5 class="text-white mt-4">Account Details ({{ $accountType }})</h5>

                                <!-- Common Fields Across All Account Types -->
                                <div class="form-group">
                                    <label class="text-white">Client Name</label>
                                    <input type="text" name="client_name" class="form-control text-white"
                                        value="{{ old('client_name', $account->client_name ?? $account->clientname) }}"
                                        required>
                                    @error('client_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- T1-Specific Fields -->
                                @if ($accountType === 'T1')
                                    <div class="form-group">
                                        <label class="text-white">Period</label>
                                        <input type="text" name="period" class="form-control text-white"
                                            value="{{ old('period', $account->period) }}" required>
                                        @error('period')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Driving License</label>
                                        <input type="text" name="driving_license" class="form-control text-white"
                                            value="{{ old('driving_license', $account->driving_license) }}" required>
                                        @error('driving_license')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">SIM Number</label>
                                        <input type="text" name="sim_number" class="form-control text-white"
                                            value="{{ old('sim_number', $account->sim_number) }}" required>
                                        @error('sim_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Business Name</label>
                                        <input type="text" name="business_name" class="form-control text-white"
                                            value="{{ old('business_name', $account->business_name ?? $account->bussiness_name) }}"
                                            required>
                                        @error('business_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Family Name</label>
                                        <input type="text" name="family_name" class="form-control text-white"
                                            value="{{ old('family_name', $account->family_name ?? $account->famliy_name) }}"
                                            required>
                                        @error('family_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Year</label>
                                        <input type="text" name="year" class="form-control text-white"
                                            value="{{ old('year', $account->year) }}" required>
                                        @error('year')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <!-- T2 and HST Shared Fields -->
                                @if ($accountType === 'T2' || $accountType === 'HST')
                                    <div class="form-group">
                                        <label class="text-white">Phone</label>
                                        <input type="text" name="phone" class="form-control text-white"
                                            value="{{ old('phone', $account->phone) }}" required>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Email</label>
                                        <input type="email" name="email" class="form-control text-white"
                                            value="{{ old('email', $account->email) }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Due Date</label>
                                        <input type="date" name="due_date" class="form-control text-white"
                                            value="{{ old('due_date', $account->due_date) }}" required>
                                        @error('due_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Corporation Number</label>
                                        <input type="text" name="corporation_number" class="form-control text-white"
                                            value="{{ old('corporation_number', $account->corporation_number) }}"
                                            required>
                                        @error('corporation_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Corporation Name</label>
                                        <input type="text" name="corporation_name" class="form-control text-white"
                                            value="{{ old('corporation_name', $account->corporation_name) }}" required>
                                        @error('corporation_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Nature of Business</label>
                                        <input type="text" name="nature_of_business" class="form-control text-white"
                                            value="{{ old('nature_of_business', $account->nature_of_business) }}"
                                            required>
                                        @error('nature_of_business')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Priority</label>
                                        <select name="priority" class="form-control custom-select text-white" required>
                                            <option value="low"
                                                {{ old('priority', $account->priority) == 'low' ? 'selected' : '' }}>Low
                                            </option>
                                            <option value="medium"
                                                {{ old('priority', $account->priority) == 'medium' ? 'selected' : '' }}>
                                                Medium</option>
                                            <option value="high"
                                                {{ old('priority', $account->priority) == 'high' ? 'selected' : '' }}>High
                                            </option>
                                        </select>
                                        @error('priority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="text-white">Attachment</label>
                                    <input type="file" name="attachments" class="form-control text-white">
                                    @error('attachments')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Common Fields for Department, Team Lead, and Status -->
                                <div class="form-group">
                                    <label class="text-white">Department</label>
                                    <select name="department_id" id="department_id"
                                        class="form-control custom-select text-white" required>
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $account->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Team Lead</label>
                                    <select name="team_lead_id" id="team_lead_id"
                                        class="form-control custom-select text-white" >
                                        <option value="">Select Team Lead</option>
                                        @foreach ($team_leads as $lead)
                                            <option value="{{ $lead->id }}"
                                                data-department="{{ $lead->department_id }}"
                                                {{ old('team_lead_id', $account->team_lead_id) == $lead->id ? 'selected' : '' }}>
                                                {{ $lead->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_lead_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Status</label>
                                    <select name="status" class="form-control custom-select text-white" required>
                                        <option value="pending"
                                            {{ old('status', $account->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="in_progress"
                                            {{ old('status', $account->status) == 'in_progress' ? 'selected' : '' }}>In
                                            Progress</option>
                                        <option value="completed"
                                            {{ old('status', $account->status) == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success px-4">Update</button>
                                    <a href="{{ route('project_manager.mytask') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        @elseif ($operation)
                            <!-- Operation Form -->
                            @if ($operation->ownerTask)
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="text-white">Parent Task Client Name</label>
                                        <input type="text" class="form-control text-white"
                                            value="{{ $operation->ownerTask->client_name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Parent Task Audio</label>
                                        @if ($operation->ownerTask->audio_url)
                                            <audio controls class="w-100">
                                                <source src="{{ $operation->ownerTask->audio_url }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @else
                                            <input type="text" class="form-control bg-secondary text-white"
                                                value="No audio available" readonly>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('project_manager.mytask_update', $operation->id) }}"
                                enctype="multipart/form-data">
                                @csrf @method('PUT')

                                <h5 class="text-white mt-4">Operation Details</h5>

                                <!-- Operation Fields -->
                              
                                <div class="form-group">
                                    <label class="text-white">Description</label>
                                    <textarea name="description" class="form-control text-white" rows="4" required>{{ old('description', $operation->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Attachment</label>
                                    <input type="file" name="attachments" class="form-control text-white">
                                    @error('attachments')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @if ($operation->attachments)
                                        <p class="text-white mt-1">Current: <a href="{{ $operation->attachments }}" target="_blank">View Attachment</a></p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Priority</label>
                                    <select name="priority" class="form-control custom-select text-white" required>
                                        <option value="low"
                                            {{ old('priority', $operation->priority) == 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="medium"
                                            {{ old('priority', $operation->priority) == 'medium' ? 'selected' : '' }}>
                                            Medium</option>
                                        <option value="high"
                                            {{ old('priority', $operation->priority) == 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                    @error('priority')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Department</label>
                                    <select name="department_id" id="department_id_operation"
                                        class="form-control custom-select text-white" >
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $operation->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Team Lead</label>
                                    <select name="team_lead_id" id="team_lead_id_operation"
                                        class="form-control custom-select text-white" required>
                                        <option value="">Select Team Lead</option>
                                        @foreach ($team_leads as $lead)
                                            <option value="{{ $lead->id }}"
                                                data-department="{{ $lead->department_id }}"
                                                {{ old('team_lead_id', $operation->team_lead_id) == $lead->id ? 'selected' : '' }}>
                                                {{ $lead->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_lead_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Status</label>
                                    <select name="status" class="form-control custom-select text-white" required>
                                        <option value="pending"
                                            {{ old('status', $operation->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="in_progress"
                                            {{ old('status', $operation->status) == 'in_progress' ? 'selected' : '' }}>In
                                            Progress</option>
                                        <option value="completed"
                                            {{ old('status', $operation->status) == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success px-4">Update</button>
                                    <a href="{{ route('project_manager.mytask') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-danger text-center">
                                Task not found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($team_leads)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Account form team lead filter
                    const departmentSelect = document.getElementById('department_id');
                    const teamLeadSelect = document.getElementById('team_lead_id');
                    const allOptions = Array.from(teamLeadSelect?.querySelectorAll('option[data-department]') || []);

                    if (departmentSelect && teamLeadSelect) {
                        departmentSelect.addEventListener('change', function() {
                            const selectedDept = this.value;
                            teamLeadSelect.innerHTML = '<option value="">Select Team Lead</option>';
                            allOptions.forEach(opt => {
                                if (opt.getAttribute('data-department') === selectedDept) {
                                    teamLeadSelect.appendChild(opt.cloneNode(true));
                                }
                            });
                        });
                        departmentSelect.dispatchEvent(new Event('change'));
                    }

                    // Operation form team lead filter
                    const departmentSelectOp = document.getElementById('department_id_operation');
                    const teamLeadSelectOp = document.getElementById('team_lead_id_operation');
                    const allOptionsOp = Array.from(teamLeadSelectOp?.querySelectorAll('option[data-department]') || []);

                    if (departmentSelectOp && teamLeadSelectOp) {
                        departmentSelectOp.addEventListener('change', function() {
                            const selectedDept = this.value;
                            teamLeadSelectOp.innerHTML = '<option value="">Select Team Lead</option>';
                            allOptionsOp.forEach(opt => {
                                if (opt.getAttribute('data-department') === selectedDept) {
                                    teamLeadSelectOp.appendChild(opt.cloneNode(true));
                                }
                            });
                        });
                        departmentSelectOp.dispatchEvent(new Event('change'));
                    }
                });
            </script>
        @endif
    </div>
@endsection