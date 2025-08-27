@php use Illuminate\Support\Str; @endphp

@extends('layout.app')

@section('styles')
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

                <div class="form-group">
                                    <label class="text-white" for="client_name">Client Name</label>
                                    <input type="text" name="client_name" id="client_name" class="form-control"
                                        value="{{ old('client_name', $task->client_name) }}" readonly>
                                    @error('client_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                 <div class="form-group">
                                    <label class="text-white d-block">Current Audio</label>
                                    @if ($task->audio_url)
                                        <audio controls class="w-100">
                                            <source src="{{ $task->audio_url }}" type="audio/webm" readonly>
                                            Your browser does not support the audio element.
                                        </audio>
                                    @else
                                        <p>No audio available</p>
                                    @endif
                                </div>
                                
                <div class="card shadow rounded">
                    <div class="card-body">
                        <h2 class="card-title text-center text-white">Create Task</h2>
                        <form method="POST" action="{{ route('project_manager.mytask_store', $task->id) }}" enctype="multipart/form-data">
                            @csrf

                            @if ($isAccounts)
                                <div class="form-group">
                                    <label class="text-white" for="account_type">Account Type</label>
                                    <select name="account_type" id="account_type" class="form-control custom-select text-white" onchange="toggleAccountForm()">
                                        <option value="AccountT2" {{ old('account_type', 'AccountT2') == 'AccountT2' ? 'selected' : '' }}>AccountT2</option>
                                        <option value="AccountHST" {{ old('account_type') == 'AccountHST' ? 'selected' : '' }}>AccountHST</option>
                                        <option value="AccountT1" {{ old('account_type') == 'AccountT1' ? 'selected' : '' }}>AccountT1</option>
                                    </select>
                                </div>

                                <!-- AccountT2 Form -->
                                <div id="accountT2_form" style="{{ old('account_type', 'AccountT2') == 'AccountT2' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">AccountT2 Details</h5>
                                    @php $accT2 = $accountsT2->first(); @endphp

                                    <div class="form-group">
                                        <label class="text-white">Client Name</label>
                                        <input type="text" name="clientname_t2" class="form-control text-white"
                                            value="{{ old('clientname_t2', optional($accT2)->clientname) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Email</label>
                                        <input type="email" name="email_t2" class="form-control text-white"
                                            value="{{ old('email_t2', optional($accT2)->email) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Phone</label>
                                        <input type="text" name="phone_t2" class="form-control text-white"
                                            value="{{ old('phone_t2') }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Corporation Name</label>
                                        <input type="text" name="corpration_name_t2" class="form-control text-white"
                                            value="{{ old('corpration_name_t2', optional($accT2)->corpration_name) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Corporation Number</label>
                                        <input type="text" name="corpration_number_t2" class="form-control text-white"
                                            value="{{ old('corpration_number_t2', optional($accT2)->corpration_number) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Due Date</label>
                                        <input type="date" name="due_date_t2" class="form-control text-white"
                                            value="{{ old('due_date_t2', optional($accT2)->due_date) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Nature of Business</label>
                                        <textarea name="nature_of_business_t2" class="form-control text-white">{{ old('nature_of_business_t2', optional($accT2)->nature_of_business) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label mb-1">Priority</label>
                                        <select name="priority_t2" class="form-control">
                                            <option value="low" {{ old('priority_t2', optional($accT2)->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority_t2', optional($accT2)->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority_t2', optional($accT2)->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label mb-1">Add Attachments</label>
                                     <input type="file" name="attachments_t2" class="form-control" >
                                    </div>
                                </div>

                                <!-- AccountHST Form -->
                                <div id="accountHST_form" style="{{ old('account_type') == 'AccountHST' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">AccountHST Details</h5>
                                    @php $accHST = $accountsHST->first(); @endphp

                                    <div class="form-group">
                                        <label class="text-white">Client Name</label>
                                        <input type="text" name="clientname_hst" class="form-control text-white"
                                            value="{{ old('clientname_hst', optional($accHST)->clientname) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Email</label>
                                        <input type="email" name="email_hst" class="form-control text-white"
                                            value="{{ old('email_hst', optional($accHST)->email) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Phone</label>
                                        <input type="text" name="phone_hst" class="form-control text-white"
                                            value="{{ old('phone_hst') }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Corporation Name</label>
                                        <input type="text" name="corpration_name_hst" class="form-control text-white"
                                            value="{{ old('corpration_name_hst', optional($accHST)->corpration_name) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Corporation Number</label>
                                        <input type="text" name="corpration_number_hst" class="form-control text-white"
                                            value="{{ old('corpration_number_hst', optional($accHST)->corpration_number) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Due Date</label>
                                        <input type="date" name="due_date_hst" class="form-control text-white"
                                            value="{{ old('due_date_hst', optional($accHST)->due_date) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Nature of Business</label>
                                        <textarea name="nature_of_business_hst" class="form-control text-white">{{ old('nature_of_business_hst', optional($accHST)->nature_of_business) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label mb-1">Priority</label>
                                        <select name="priority_hst" class="form-control">
                                            <option value="low" {{ old('priority_hst', optional($accHST)->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority_hst', optional($accHST)->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority_hst', optional($accHST)->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label mb-1">Add Attachments</label>
                                        <input type="file" name="attachments_hst" class="form-control" multiple>
                                    </div>
                                </div>

                                <!-- AccountT1 Form -->
                                <div id="accountT1_form" style="{{ old('account_type') == 'AccountT1' ? '' : 'display: none;' }}">
                                    <h5 class="text-white mt-4">AccountT1 Details</h5>
                                    @php $accT1 = $accountsT1->first(); @endphp

                                    <div class="form-group">
                                        <label class="text-white">Client Name</label>
                                        <input type="text" name="clientname_t1" class="form-control text-white"
                                            value="{{ old('clientname_t1', optional($accT1)->clientname) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Period</label>
                                        <input type="text" name="period_t1" class="form-control text-white"
                                            value="{{ old('period_t1', optional($accT1)->period) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Driving License</label>
                                        <input type="text" name="driving_license_t1" class="form-control text-white"
                                            value="{{ old('driving_license_t1', optional($accT1)->driving_license) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">SIM Number</label>
                                        <input type="text" name="sim_number_t1" class="form-control text-white"
                                            value="{{ old('sim_number_t1', optional($accT1)->sim_number) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Business Name</label>
                                        <input type="text" name="bussiness_name_t1" class="form-control text-white"
                                            value="{{ old('bussiness_name_t1', optional($accT1)->bussiness_name) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Family Name</label>
                                        <input type="text" name="famliy_name_t1" class="form-control text-white"
                                            value="{{ old('famliy_name_t1', optional($accT1)->famliy_name) }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-white">Year</label>
                                        <input type="text" name="year_t1" class="form-control text-white"
                                            value="{{ old('year_t1', optional($accT1)->year) }}">
                                    </div>

                                    

                                    
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="text-white" for="department_id">Department</label>
                                <select name="department_id" id="department_id"
                                    class="form-control custom-select text-white">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-white" for="team_lead_id">Team Lead</label>
                                <select name="team_lead_id" id="team_lead_id"
                                    class="form-control custom-select text-white">
                                    <option value="">Select Team Lead</option>
                                    @foreach ($team_leads as $lead)
                                        <option value="{{ $lead->id }}"
                                            {{ old('team_lead_id') == $lead->id ? 'selected' : '' }}>
                                            {{ $lead->name }}
                                        </option>
                                    @endforeach
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

    <script>
        function toggleAccountForm() {
            const accountType = document.getElementById('account_type').value;
            const accountT2Form = document.getElementById('accountT2_form');
            const accountHSTForm = document.getElementById('accountHST_form');
            const accountT1Form = document.getElementById('accountT1_form');

            accountT2Form.style.display = accountType === 'AccountT2' ? 'block' : 'none';
            accountHSTForm.style.display = accountType === 'AccountHST' ? 'block' : 'none';
            accountT1Form.style.display = accountType === 'AccountT1' ? 'block' : 'none';
        }
    </script>
@endsection