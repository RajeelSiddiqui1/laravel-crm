@extends('layout.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --body-bg: #0d0d11;
            --card-bg: #1a1b26;
            --accent: #7b68ee;
            --text: #f5f5f5;
        }

        body {
            background: var(--body-bg);
            color: var(--text);
        }

        .card {
            background: var(--card-bg);
            border: none;
            border-radius: .75rem;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, var(--accent), #8a5cf5);
            color: #fff;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
        }

        .btn-primary:hover {
            background: #5a4fcf;
        }

        .btn-success {
            background: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background: #218838;
            border-color: #1e7e34;
        }

        .form-control,
        .form-select {
            background: #252837;
            border: 1px solid #3a3c4f;
            color: var(--text);
        }

        .form-control:focus,
        .form-select:focus {
            background: #252837;
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(123, 104, 238, .25);
        }

        .attachment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: .5rem;
        }

       
        .hidden {
            display: none;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
        }

        .status-badge {
            padding: 0.25em 0.5em;
            border-radius: 0.25rem;
            font-size: 0.8em;
        }

        .status-pending {
            background-color: #6c757d;
            color: white;
        }

        .status-in_progress {
            background-color: #ffc107;
            color: #212529;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
        }

        .readonly-field {
            background-color: #2d2d3d !important;
            cursor: not-allowed;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-muted {
            color: #aaa !important;
        }

        .form-row {
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold text-white">Update Subtask: {{ $subtask->title }}</h2>
            <a href="{{ route('employee.subtasks') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        @if (session('success_swal') || session('error_swal'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        title: '{{ session('success_swal') ? 'Success' : 'Error' }}',
                        text: '{{ session('success_swal') ?: session('error_swal') }}',
                        icon: '{{ session('success_swal') ? 'success' : 'error' }}',
                        confirmButtonText: 'OK'
                    });
                });
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

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Subtask Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Task Type:</strong>
                        <span class="badge bg-secondary">{{ $subtask->task_type ?? 'General' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Total Leads:</strong> {{ $subtask->lead ?? 0 }}
                    </div>
                    <div class="col-md-6">
                        <strong>Start Date:</strong>
                        {{ $subtask->start_date ? \Carbon\Carbon::parse($subtask->start_date)->format('M d, Y') : 'Not specified' }}
                    </div>
                    <div class="col-md-6">
                        <strong>End Date:</strong>
                        {{ $subtask->end_date ? \Carbon\Carbon::parse($subtask->end_date)->format('M d, Y') : 'Not specified' }}
                    </div>
                    <div class="col-md-6">
                        @if ($subtask->start_time)
                            <strong>Start Time:</strong> {{ \Carbon\Carbon::parse($subtask->start_time)->format('H:i') }}
                        @else
                            <strong>Start Time:</strong> Not specified
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if ($subtask->end_time)
                            <strong>End Time:</strong> {{ \Carbon\Carbon::parse($subtask->end_time)->format('H:i') }}
                        @else
                            <strong>End Time:</strong> Not specified
                        @endif
                    </div>
                    <div class="col-12">
                        <strong>Description:</strong>
                        <p class="mb-0 mt-1 text-muted">{{ $subtask->description }}</p>
                    </div>
                    @if ($subtask->attachments)
                        <div class="col-12">
                            <strong>Subtask Attachment:</strong>
                            <div class="mt-2">
                                @php
                                    $subtaskExt = strtolower(
                                        pathinfo(parse_url($subtask->attachments, PHP_URL_PATH), PATHINFO_EXTENSION),
                                    );
                                @endphp
                                @if (in_array($subtaskExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ $subtask->attachments }}" alt="Subtask Attachment"
                                        class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">
                                @elseif(in_array($subtaskExt, ['mp4', 'mov', 'avi', 'webm']))
                                    <video controls class="w-100 rounded" style="max-height: 200px;">
                                        <source src="{{ $subtask->attachments }}" type="video/{{ $subtaskExt }}">
                                    </video>
                                @elseif(in_array($subtaskExt, ['mp3', 'wav', 'ogg']))
                                    <audio controls class="w-100">
                                        <source src="{{ $subtask->attachments }}" type="audio/{{ $subtaskExt }}">
                                    </audio>
                                @else
                                    <a href="{{ $subtask->attachments }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download"></i> Download Attachment
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @forelse($leadValues as $lead)
            @php
                $leadStatus = 'pending';
                $leadComment = '';
                $leadData = [];
                $leadAttachments = [];

                $posRecord = $posRecords[$lead] ?? null;
                $accountRecord = $accountRecords[$lead] ?? null;

                if ($posRecord) {
                    $leadStatus = $posRecord->status ?? 'pending';
                    $leadComment = $posRecord->comment ?? '';
                    $leadData = $posRecord->toArray();
                    $leadAttachments = $posRecord->attachments ?? [];
                } elseif ($accountRecord) {
                    $leadStatus = $accountRecord->status ?? 'pending';
                    $leadComment = $accountRecord->comments ?? '';
                    $leadData = $accountRecord->toArray();
                    $leadAttachments = $accountRecord->attachments ?? [];
                } else {
                    $leadData = old('lead_data_' . $lead, []);
                }

                if (empty($leadAttachments)) {
                    $leadAttachments = [];
                } elseif (is_string($leadAttachments)) {
                    $leadAttachments = trim($leadAttachments);
                    if ($leadAttachments === '') {
                        $leadAttachments = [];
                    } elseif (strpos($leadAttachments, ',') !== false) {
                        $leadAttachments = array_map('trim', explode(',', $leadAttachments));
                        $leadAttachments = array_filter($leadAttachments);
                    } elseif (json_decode($leadAttachments) !== null) {
                        $leadAttachments = json_decode($leadAttachments, true) ?? [$leadAttachments];
                        if (!is_array($leadAttachments)) {
                            $leadAttachments = [$leadAttachments];
                        }
                    } else {
                        $leadAttachments = [$leadAttachments];
                    }
                } elseif (!is_array($leadAttachments) && !($leadAttachments instanceof \Illuminate\Support\Collection)) {
                    $leadAttachments = [$leadAttachments];
                } else {
                    if ($leadAttachments instanceof \Illuminate\Support\Collection) {
                        $leadAttachments = $leadAttachments->toArray();
                    }
                }
            @endphp

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold">Lead {{ $lead }}</span>
                        <span class="status-badge status-{{ $leadStatus }} ms-2">
                            {{ ucfirst(str_replace('_', ' ', $leadStatus)) }}
                        </span>
                    </div>
                    <button form="form{{ $lead }}" type="submit" class="btn btn-primary btn-sm"
                        id="saveBtn{{ $lead }}">
                        <i class="bi bi-save"></i> Save Lead {{ $lead }}
                    </button>
                </div>

                <form id="form{{ $lead }}" action="{{ route('employee.subtask.update', $subtask->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="lead" value="{{ $lead }}">
                    <div class="card-body">
                        <input type="hidden" name="employee_id" value="{{ Auth::guard('employee')->user()->id ?? '' }}">
                        <input type="hidden" name="subtask_id" value="{{ $subtask->id }}">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label mb-1">Title</label>
                                <input type="text" class="form-control form-control-sm readonly-field"
                                    value="{{ $subtask->title }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1">Task Type</label>
                                <input type="text" class="form-control form-control-sm readonly-field"
                                    value="{{ $subtask->task_type ?? 'General' }}" readonly>
                            </div>

                            <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                <label class="form-label mb-1">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select form-select-sm @error('status') is-invalid @enderror" required>
                                    <option value="pending"
                                        {{ old('status_' . $lead, $leadStatus) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress"
                                        {{ old('status_' . $lead, $leadStatus) == 'in_progress' ? 'selected' : '' }}>In Progress
                                    </option>
                                    <option value="completed"
                                        {{ old('status_' . $lead, $leadStatus) == 'completed' ? 'selected' : '' }}>Completed
                                    </option>
                                    <option value="rejected"
                                        {{ old('status_' . $lead, $leadStatus) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 hidden row2{{ $lead }} form-row">
                                <label class="form-label mb-1">{{ $subtask->task_type === 'cell_center_accounts' ? 'Comments' : 'Comment' }}</label>
                                <textarea name="{{ $subtask->task_type === 'cell_center_accounts' ? 'comments' : 'comment' }}"
                                    class="form-control form-control-sm @error($subtask->task_type === 'cell_center_accounts' ? 'comments' : 'comment') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Add your comments for Lead {{ $lead }}...">{{ old(($subtask->task_type === 'cell_center_accounts' ? 'comments_' : 'comment_') . $lead, $leadComment) }}</textarea>
                                @error($subtask->task_type === 'cell_center_accounts' ? 'comments' : 'comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($subtask->task_type === 'call_center_pos' || $isCallCenterPos)
                                <div class="col-12 hidden row2{{ $lead }}">
                                    <h6 class="text-primary mb-2"><i class="bi bi-building"></i> POS Information</h6>
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Name</label>
                                    <input name="name"
                                        class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        value="{{ old('name_' . $lead, $leadData['name'] ?? '') }}"
                                        placeholder="Enter name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Business Name</label>
                                    <input name="business_name"
                                        class="form-control form-control-sm @error('business_name') is-invalid @enderror"
                                        value="{{ old('business_name_' . $lead, $leadData['business_name'] ?? '') }}"
                                        placeholder="Enter business name">
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Business Number</label>
                                    <input type="tel" name="business_number"
                                        class="form-control form-control-sm @error('business_number') is-invalid @enderror"
                                        value="{{ old('business_number_' . $lead, $leadData['business_number'] ?? '') }}"
                                        placeholder="Enter business number">
                                    @error('business_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Personal Number</label>
                                    <input type="tel" name="personal_number"
                                        class="form-control form-control-sm @error('personal_number') is-invalid @enderror"
                                        value="{{ old('personal_number_' . $lead, $leadData['personal_number'] ?? '') }}"
                                        placeholder="Enter personal number">
                                    @error('personal_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Personal Email</label>
                                    <input type="email" name="personal_email"
                                        class="form-control form-control-sm @error('personal_email') is-invalid @enderror"
                                        value="{{ old('personal_email_' . $lead, $leadData['personal_email'] ?? '') }}"
                                        placeholder="Enter personal email">
                                    @error('personal_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Business Email</label>
                                    <input type="email" name="business_email"
                                        class="form-control form-control-sm @error('business_email') is-invalid @enderror"
                                        value="{{ old('business_email_' . $lead, $leadData['business_email'] ?? '') }}"
                                        placeholder="Enter business email">
                                    @error('business_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Address</label>
                                    <textarea name="address"
                                        class="form-control form-control-sm @error('address') is-invalid @enderror"
                                        rows="2"
                                        placeholder="Enter business address">{{ old('address_' . $lead, $leadData['address'] ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Provider</label>
                                    <input name="provider"
                                        class="form-control form-control-sm @error('provider') is-invalid @enderror"
                                        value="{{ old('provider_' . $lead, $leadData['provider'] ?? '') }}"
                                        placeholder="Enter current provider">
                                    @error('provider')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Category POS</label>
                                    <input name="category_pos"
                                        class="form-control form-control-sm @error('category_pos') is-invalid @enderror"
                                        value="{{ old('category_pos_' . $lead, $leadData['category_pos'] ?? '') }}"
                                        placeholder="Enter POS category">
                                    @error('category_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">POS Type</label>
                                    <select name="pos_type"
                                        class="form-select form-select-sm @error('pos_type') is-invalid @enderror">
                                        <option value="">Select POS Type</option>
                                        <option value="retail"
                                            {{ old('pos_type_' . $lead, $leadData['pos_type'] ?? '') == 'retail' ? 'selected' : '' }}>
                                            Retail</option>
                                        <option value="restaurant"
                                            {{ old('pos_type_' . $lead, $leadData['pos_type'] ?? '') == 'restaurant' ? 'selected' : '' }}>
                                            Restaurant</option>
                                        <option value="service"
                                            {{ old('pos_type_' . $lead, $leadData['pos_type'] ?? '') == 'service' ? 'selected' : '' }}>
                                            Service</option>
                                    </select>
                                    @error('pos_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Debt</label>
                                    <input type="number" step="0.01" name="debt"
                                        class="form-control form-control-sm @error('debt') is-invalid @enderror"
                                        value="{{ old('debt_' . $lead, $leadData['debt'] ?? '') }}"
                                        placeholder="0.00">
                                    @error('debt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Credit</label>
                                    <input type="number" step="0.01" name="credit"
                                        class="form-control form-control-sm @error('credit') is-invalid @enderror"
                                        value="{{ old('credit_' . $lead, $leadData['credit'] ?? '') }}"
                                        placeholder="0.00">
                                    @error('credit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Rental</label>
                                    <input type="number" step="0.01" name="rental"
                                        class="form-control form-control-sm @error('rental') is-invalid @enderror"
                                        value="{{ old('rental_' . $lead, $leadData['rental'] ?? '') }}"
                                        placeholder="0.00">
                                    @error('rental')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Business Type</label>
                                    <input name="business_type"
                                        class="form-control form-control-sm @error('business_type') is-invalid @enderror"
                                        value="{{ old('business_type_' . $lead, $leadData['business_type'] ?? '') }}"
                                        placeholder="Enter business type">
                                    @error('business_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Date</label>
                                    <input type="date" name="date"
                                        class="form-control form-control-sm @error('date') is-invalid @enderror"
                                        value="{{ old('date_' . $lead, $leadData['date'] ?? '') }}">
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Time</label>
                                    <input type="time" name="time"
                                        class="form-control form-control-sm @error('time') is-invalid @enderror"
                                        value="{{ old('time_' . $lead, $leadData['time'] ?? '') }}">
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            @elseif($subtask->task_type === 'cell_center_accounts' || $isCallCenterAccount)
                                <div class="col-12 hidden row2{{ $lead }}">
                                    <h6 class="text-primary mb-2"><i class="bi bi-person-lines-fill"></i> Account Information</h6>
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Driving License</label>
                                    <input name="driving_license"
                                        class="form-control form-control-sm @error('driving_license') is-invalid @enderror"
                                        value="{{ old('driving_license_' . $lead, $leadData['driving_license'] ?? '') }}"
                                        placeholder="Enter driving license number">
                                    @error('driving_license')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Email</label>
                                    <input type="email" name="email"
                                        class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        value="{{ old('email_' . $lead, $leadData['email'] ?? '') }}"
                                        placeholder="Enter email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Phone</label>
                                    <input type="tel" name="phone"
                                        class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                        value="{{ old('phone_' . $lead, $leadData['phone'] ?? '') }}"
                                        placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Business Number</label>
                                    <input type="text" name="bussiness_number"
                                        class="form-control form-control-sm @error('bussiness_number') is-invalid @enderror"
                                        value="{{ old('bussiness_number_' . $lead, $leadData['bussiness_number'] ?? '') }}"
                                        placeholder="Enter business number">
                                    @error('bussiness_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Corporation Number</label>
                                    <input type="text" name="corpuration_number"
                                        class="form-control form-control-sm @error('corpuration_number') is-invalid @enderror"
                                        value="{{ old('corpuration_number_' . $lead, $leadData['corpuration_number'] ?? '') }}"
                                        placeholder="Enter corporation number">
                                    @error('corpuration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Corporation Email</label>
                                    <input type="email" name="corpuration_email"
                                        class="form-control form-control-sm @error('corpuration_email') is-invalid @enderror"
                                        value="{{ old('corpuration_email_' . $lead, $leadData['corpuration_email'] ?? '') }}"
                                        placeholder="Enter corporation email">
                                    @error('corpuration_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Previous History</label>
                                    <textarea name="pervious_history"
                                        class="form-control form-control-sm @error('pervious_history') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Enter previous account history">{{ old('pervious_history_' . $lead, $leadData['pervious_history'] ?? '') }}</textarea>
                                    @error('pervious_history')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Fees</label>
                                    <input type="number" step="0.01" name="fees"
                                        class="form-control form-control-sm @error('fees') is-invalid @enderror"
                                        value="{{ old('fees_' . $lead, $leadData['fees'] ?? '') }}"
                                        placeholder="0.00">
                                    @error('fees')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            @else
                                <div class="col-12 hidden row2{{ $lead }}">
                                    <h6 class="text-primary mb-2"><i class="bi bi-list-task"></i> Additional Information</h6>
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Client Name</label>
                                    <input name="client_name"
                                        class="form-control form-control-sm @error('client_name') is-invalid @enderror"
                                        value="{{ old('client_name_' . $lead, $leadData['client_name'] ?? '') }}"
                                        placeholder="Enter client name">
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Contact Information</label>
                                    <input type="text" name="contact_info"
                                        class="form-control form-control-sm @error('contact_info') is-invalid @enderror"
                                        value="{{ old('contact_info_' . $lead, $leadData['contact_info'] ?? '') }}"
                                        placeholder="Phone/Email">
                                    @error('contact_info')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 hidden row2{{ $lead }} form-row">
                                    <label class="form-label mb-1">Notes</label>
                                    <textarea name="notes"
                                        class="form-control form-control-sm @error('notes') is-invalid @enderror"
                                        rows="2"
                                        placeholder="Additional notes for this lead...">{{ old('notes_' . $lead, $leadData['notes'] ?? '') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

@if (!empty($leadAttachments))
    <div class="col-12">
        <label class="form-label mb-1">Existing Lead Attachments</label>
        <div class="row g-3">
            @foreach ($leadAttachments as $url)
                @if ($url)
                    @php
                        $fileUrl = Str::startsWith($url, ['http://', 'https://']) ? $url : asset('storage/' . $url);
                        $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                        $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));
                    @endphp

                    <div class="col-md-3 col-sm-6">
                        <div class="attachment-item text-center p-2 border rounded bg-dark-subtle shadow-sm h-100">

                            {{-- Images --}}
                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']))
                                <a href="{{ $fileUrl }}" target="_blank">
                                    <img src="{{ $fileUrl }}" alt="{{ $fileName }}" 
                                         class="img-fluid rounded mb-2"
                                         style="max-height: 120px; object-fit: cover; width: 100%;">
                                </a>
                                <small class="text-muted d-block">{{ Str::limit($fileName, 15) }}</small>

                            {{-- Videos --}}
                            @elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']))
                                <video src="{{ $fileUrl }}" controls class="rounded mb-2 w-100"
                                       style="max-height: 120px; object-fit: cover;">
                                </video>
                                <small class="text-muted d-block">{{ Str::limit($fileName, 15) }}</small>

                            {{-- Audio --}}
                            @elseif (in_array($ext, ['mp3', 'wav', 'ogg', 'm4a']))
                                <audio controls class="w-100 mb-2">
                                    <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                </audio>
                                <small class="text-muted d-block">{{ Str::limit($fileName, 15) }}</small>

                            {{-- PDF --}}
                            @elseif ($ext === 'pdf')
                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                    <img src="https://img.icons8.com/color/96/pdf.png" alt="PDF" class="mb-1" style="width:48px;">
                                    <div>{{ Str::limit($fileName, 15) }}</div>
                                </a>

                            {{-- Excel --}}
                            @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                    <img src="https://img.icons8.com/color/96/ms-excel.png" alt="Excel" class="mb-1" style="width:48px;">
                                    <div>{{ Str::limit($fileName, 15) }}</div>
                                </a>

                            {{-- Word --}}
                            @elseif (in_array($ext, ['doc', 'docx']))
                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                    <img src="https://img.icons8.com/color/96/ms-word.png" alt="Word" class="mb-1" style="width:48px;">
                                    <div>{{ Str::limit($fileName, 15) }}</div>
                                </a>

                            {{-- Other files --}}
                            @else
                                <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                    <img src="https://img.icons8.com/fluency/96/file.png" alt="File" class="mb-1" style="width:48px;">
                                    <div>{{ Str::limit($fileName, 15) }}</div>
                                </a>
                            @endif

                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif

                            <div class="col-12">
                                <label class="form-label mb-1">Add New Attachments</label>
                                <input type="file" name="attachments[]" multiple
                                    class="form-control form-control-sm @error('attachments.*') is-invalid @enderror"
                                    accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.csv">
                                <small class="text-muted">Optional: Upload images, videos, audio, PDFs, or documents (Max 5MB each)</small>
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                    onclick="toggleRow({{ $lead }})" id="toggleBtn{{ $lead }}">
                                    <i class="bi bi-chevron-down" id="toggleIcon{{ $lead }}"></i>
                                    Show More Details for Lead {{ $lead }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @empty
            <div class="card mb-3">
                <div class="card-body text-center py-4">
                    <i class="bi bi-exclamation-triangle display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">No Leads Available</h5>
                    <p class="text-muted">This subtask doesn't have any leads assigned.</p>
                </div>
            </div>
        @endforelse
    </div>

    <script>
        function toggleRow(id) {
            const elements = document.querySelectorAll('.row2' + id);
            const toggleBtn = document.getElementById('toggleBtn' + id);
            const toggleIcon = document.getElementById('toggleIcon' + id);
            const saveBtn = document.getElementById('saveBtn' + id);

            elements.forEach(el => el.classList.toggle('hidden'));

            if (toggleIcon.classList.contains('bi-chevron-down')) {
                toggleIcon.classList.remove('bi-chevron-down');
                toggleIcon.classList.add('bi-chevron-up');
                toggleBtn.innerHTML = '<i class="bi bi-chevron-up"></i> Hide Details';
            } else {
                toggleIcon.classList.remove('bi-chevron-up');
                toggleIcon.classList.add('bi-chevron-down');
                toggleBtn.innerHTML = '<i class="bi bi-chevron-down"></i> Show More Details for Lead ' + id;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const statusSelect = form.querySelector('select[name="status"]');
                    if (statusSelect && !statusSelect.value) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Validation Error',
                            text: 'Please select a status before saving.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }

                    const leadId = form.querySelector('input[name="lead"]').value;
                    const taskFields = form.querySelectorAll('.row2' + leadId + ' input:not([type="hidden"]), .row2' + leadId + ' select, .row2' + leadId + ' textarea');
                    let hasRequiredData = false;
                    taskFields.forEach(field => {
                        if (field.value && field.value.trim()) {
                            hasRequiredData = true;
                        }
                    });

                    if (!hasRequiredData) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Incomplete Information',
                            text: 'Please fill in at least some task-specific information or leave a comment.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.row21')) {
                const firstLeadElements = document.querySelectorAll('.row21');
                firstLeadElements.forEach(el => el.classList.remove('hidden'));
                const firstToggle = document.getElementById('toggleBtn1');
                if (firstToggle) {
                    const firstIcon = document.getElementById('toggleIcon1');
                    firstIcon.classList.remove('bi-chevron-down');
                    firstIcon.classList.add('bi-chevron-up');
                    firstToggle.innerHTML = '<i class="bi bi-chevron-up"></i> Hide Details';
                }
            }
        });
    </script>
@endsection