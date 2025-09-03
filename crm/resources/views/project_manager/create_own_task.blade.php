@php use Illuminate\Support\Str; @endphp

@extends('layout.app')

@section('styles')
    <style>
        .card-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            padding: 1.5rem;
        }
        .form-group { margin-bottom: 0.75rem; }
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
        .text-danger { font-size: 0.8rem; }
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
@endsection

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
                @if (session('error_swal'))
                    <script>
                        document.addEventListener('DOMContentLoaded', () => Swal.fire({
                            title: 'Error!',
                            text: "{{ session('error_swal') }}",
                            icon: 'error',
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
                        <h2 class="card-title text-center text-white">Create Task</h2>
                        <form method="POST" action="{{ route('project_manager.owntask_store') }}" enctype="multipart/form-data">
                            @csrf

                            @if ($isAccounts || $isOperation)
                                <div class="form-group">
                                    <label class="text-white" for="account_type">Task Type</label>
                                    <select name="account_type" id="account_type" class="form-control custom-select text-white" onchange="toggleAccountForm()">
                                        @if ($isAccounts)
                                            <option value="AccountT2" {{ old('account_type', 'AccountT2') == 'AccountT2' ? 'selected' : '' }}>AccountT2</option>
                                            <option value="AccountHST" {{ old('account_type') == 'AccountHST' ? 'selected' : '' }}>AccountHST</option>
                                            <option value="AccountT1" {{ old('account_type') == 'AccountT1' ? 'selected' : '' }}>AccountT1</option>
                                        @endif
                                        @if ($isOperation)
                                            <option value="operation" {{ old('account_type') == 'operation' ? 'selected' : '' }}>Operation</option>
                                        @endif
                                    </select>
                                </div>

                                <!-- AccountT2 Form -->
                                <div id="accountT2_form" style="{{ old('account_type', 'AccountT2') == 'AccountT2' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">AccountT2 Details</h5>
                                    @php $accT2 = $accountsT2->first(); @endphp
                                    <div class="form-group">
                                        <label class="text-white">Client Name</label>
                                        <input type="text" name="clientname_t2" class="form-control text-white" value="{{ old('clientname_t2', optional($accT2)->clientname) }}">
                                        @error('clientname_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Email</label>
                                        <input type="email" name="email_t2" class="form-control text-white" value="{{ old('email_t2', optional($accT2)->email) }}">
                                        @error('email_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Phone</label>
                                        <input type="text" name="phone_t2" class="form-control text-white" value="{{ old('phone_t2') }}">
                                        @error('phone_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Corporation Name</label>
                                        <input type="text" name="corporation_name_t2" class="form-control text-white" value="{{ old('corporation_name_t2', optional($accT2)->corporation_name) }}">
                                        @error('corporation_name_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Corporation Number</label>
                                        <input type="text" name="corporation_number_t2" class="form-control text-white" value="{{ old('corporation_number_t2', optional($accT2)->corporation_number) }}">
                                        @error('corporation_number_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Due Date</label>
                                        <input type="date" name="due_date_t2" class="form-control text-white" value="{{ old('due_date_t2', optional($accT2)->due_date) }}">
                                        @error('due_date_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Nature of Business</label>
                                        <textarea name="nature_of_business_t2" class="form-control text-white">{{ old('nature_of_business_t2', optional($accT2)->nature_of_business) }}</textarea>
                                        @error('nature_of_business_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label mb-1">Priority</label>
                                        <select name="priority_t2" class="form-control">
                                            <option value="low" {{ old('priority_t2', optional($accT2)->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority_t2', optional($accT2)->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority_t2', optional($accT2)->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                        @error('priority_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label mb-1">Add Attachment</label>
                                        <input type="file" name="attachments_t2" class="form-control">
                                        @error('attachments_t2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- AccountHST Form -->
                                <div id="accountHST_form" style="{{ old('account_type') == 'AccountHST' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">AccountHST Details</h5>
                                    @php $accHST = $accountsHST->first(); @endphp
                                    <div class="form-group">
                                        <label class="text-white">Client Name</label>
                                        <input type="text" name="clientname_hst" class="form-control text-white" value="{{ old('clientname_hst', optional($accHST)->clientname) }}">
                                        @error('clientname_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Email</label>
                                        <input type="email" name="email_hst" class="form-control text-white" value="{{ old('email_hst', optional($accHST)->email) }}">
                                        @error('email_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Phone</label>
                                        <input type="text" name="phone_hst" class="form-control text-white" value="{{ old('phone_hst') }}">
                                        @error('phone_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Corporation Name</label>
                                        <input type="text" name="corporation_name_hst" class="form-control text-white" value="{{ old('corporation_name_hst', optional($accHST)->corporation_name) }}">
                                        @error('corporation_name_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Corporation Number</label>
                                        <input type="text" name="corporation_number_hst" class="form-control text-white" value="{{ old('corporation_number_hst', optional($accHST)->corporation_number) }}">
                                        @error('corporation_number_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Due Date</label>
                                        <input type="date" name="due_date_hst" class="form-control text-white" value="{{ old('due_date_hst', optional($accHST)->due_date) }}">
                                        @error('due_date_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Nature of Business</label>
                                        <textarea name="nature_of_business_hst" class="form-control text-white">{{ old('nature_of_business_hst', optional($accHST)->nature_of_business) }}</textarea>
                                        @error('nature_of_business_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label mb-1">Priority</label>
                                        <select name="priority_hst" class="form-control">
                                            <option value="low" {{ old('priority_hst', optional($accHST)->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority_hst', optional($accHST)->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority_hst', optional($accHST)->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                        @error('priority_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label mb-1">Add Attachment</label>
                                        <input type="file" name="attachments_hst" class="form-control">
                                        @error('attachments_hst') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- AccountT1 Form -->
                                <div id="accountT1_form" style="{{ old('account_type') == 'AccountT1' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">AccountT1 Details</h5>
                                    @php $accT1 = $accountsT1->first(); @endphp
                                    <div class="form-group">
                                        <label class="text-white">Client Name</label>
                                        <input type="text" name="clientname_t1" class="form-control text-white" value="{{ old('clientname_t1', optional($accT1)->clientname) }}">
                                        @error('clientname_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Period</label>
                                        <input type="text" name="period_t1" class="form-control text-white" value="{{ old('period_t1', optional($accT1)->period) }}">
                                        @error('period_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Driving License</label>
                                        <input type="text" name="driving_license_t1" class="form-control text-white" value="{{ old('driving_license_t1', optional($accT1)->driving_license) }}">
                                        @error('driving_license_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">SIM Number</label>
                                        <input type="text" name="sim_number_t1" class="form-control text-white" value="{{ old('sim_number_t1', optional($accT1)->sim_number) }}">
                                        @error('sim_number_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Business Name</label>
                                        <input type="text" name="business_name_t1" class="form-control text-white" value="{{ old('business_name_t1', optional($accT1)->business_name) }}">
                                        @error('business_name_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Family Name</label>
                                        <input type="text" name="family_name_t1" class="form-control text-white" value="{{ old('family_name_t1', optional($accT1)->family_name) }}">
                                        @error('family_name_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-white">Year</label>
                                        <input type="text" name="year_t1" class="form-control text-white" value="{{ old('year_t1', optional($accT1)->year) }}">
                                        @error('year_t1') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Operation Form -->
                                <div id="operation_form" style="{{ old('account_type') == 'operation' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">Operation Details</h5>
                                    @php $operationRecord = $operation->first(); @endphp
                                    <div class="form-group">
                                        <label class="text-white">Description</label>
                                        <textarea name="description" class="form-control text-white">{{ old('description', optional($operationRecord)->description) }}</textarea>
                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label mb-1">Add Attachment</label>
                                        <input type="file" name="attachments" class="form-control">
                                        @error('attachments') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label mb-1">Priority</label>
                                        <select name="priority" class="form-control">
                                            <option value="low" {{ old('priority', optional($operationRecord)->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority', optional($operationRecord)->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority', optional($operationRecord)->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                        @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="text-white" for="department_id">Department</label>
                                <select name="department_id" id="department_id" class="form-control custom-select text-white">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            @if ($team_leads)
                                <div class="form-group">
                                    <label class="text-white" for="team_lead_id">Team Lead</label>
                                    <select name="team_lead_id" id="team_lead_id" class="form-control custom-select text-white">
                                        <option value="">Select Team Lead</option>
                                        @foreach ($team_leads as $team_lead)
                                            <option value="{{ $team_lead->id }}" data-department="{{ $team_lead->department_id }}" {{ old('team_lead_id') == $team_lead->id ? 'selected' : '' }}>
                                                {{ $team_lead->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_lead_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const departmentSelect = document.getElementById('department_id');
                                        const teamLeadSelect = document.getElementById('team_lead_id');
                                        const allOptions = Array.from(teamLeadSelect.querySelectorAll('option[data-department]'));

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
                                    });
                                </script>
                            @endif

                            <div class="form-group text-center mt-3">
                                <button type="submit" class="btn btn-light">Create Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleAccountForm() {
                const accountType = document.getElementById('account_type').value;
                const forms = {
                    'AccountT2': 'accountT2_form',
                    'AccountHST': 'accountHST_form',
                    'AccountT1': 'accountT1_form',
                    'operation': 'operation_form'
                };
                Object.values(forms).forEach(formId => document.getElementById(formId).style.display = 'none');
                if (forms[accountType]) document.getElementById(forms[accountType]).style.display = 'block';
            }
            document.addEventListener('DOMContentLoaded', toggleAccountForm);
        </script>
    </div>
@endsection