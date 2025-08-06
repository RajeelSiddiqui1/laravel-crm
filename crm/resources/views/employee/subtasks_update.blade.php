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

        .attachment-item img,
        .attachment-item video {
            height: 100px;
            width: 100%;
            object-fit: cover;
            border-radius: .5rem;
        }

        .hidden {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 fw-bold">Update Subtask by Lead</h2>

        @if(session('success_swal') || session('error_swal'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        title: '{{ session('success_swal') ? "Success" : "Error" }}',
                        text: '{{ session('success_swal') ?: session('error_swal') }}',
                        icon: '{{ session('success_swal') ? "success" : "error" }}',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @foreach($leadValues as $lead)
            @php
                $i = $lead - 1;
                $c = $employeeSubtask->comments[$i] ?? '';
                $s = $employeeSubtask->statuses[$i] ?? 'pending';
                $a = $employeeSubtask->attachments[$i] ?? [];
                // Ensure $a is an array
                if (!is_array($a)) {
                    $a = is_string($a) && !empty($a) ? [$a] : [];
                }
                // Fetch POS and Account data
                $posData = $employeeSubtask->cell_center_pos_data[$i] ?? [];
                $accountData = $employeeSubtask->cell_center_account_data[$i] ?? [];
            @endphp

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lead {{ $lead }}</span>
                    <button form="form{{ $lead }}" type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>

                <form id="form{{ $lead }}" action="{{ route($isCallCenterPos ? 'employee.subtask.pos.update' : ($isCallCenterAccount ? 'employee.subtask.account.update' : 'employee.subtask.update'), ['id' => $subtask->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="lead" value="{{ $lead }}">
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <label class="form-label mb-1">Title</label>
                            <input class="form-control form-control-sm" value="{{ $subtask->title }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" {{ $s == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $s == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $s == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1">Description</label>
                            <textarea class="form-control form-control-sm" rows="2" readonly>{{ $subtask->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1">Comment</label>
                            <textarea name="comment" class="form-control form-control-sm" rows="2">{{ old('comment', $c) }}</textarea>
                        </div>

                        @if($isCallCenterPos)
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Name</label>
                                <input name="name" class="form-control form-control-sm" value="{{ old('name', $posData['name'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Name</label>
                                <input name="business_name" class="form-control form-control-sm" value="{{ old('business_name', $posData['business_name'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Number</label>
                                <input name="business_number" class="form-control form-control-sm" value="{{ old('business_number', $posData['business_number'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Personal Number</label>
                                <input name="personal_number" class="form-control form-control-sm" value="{{ old('personal_number', $posData['personal_number'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Personal Email</label>
                                <input type="email" name="personal_email" class="form-control form-control-sm" value="{{ old('personal_email', $posData['personal_email'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Email</label>
                                <input type="email" name="business_email" class="form-control form-control-sm" value="{{ old('business_email', $posData['business_email'] ?? '') }}">
                            </div>
                            <div class="col-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Address</label>
                                <textarea name="address" class="form-control form-control-sm" rows="2">{{ old('address', $posData['address'] ?? '') }}</textarea>
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Provider</label>
                                <input name="provider" class="form-control form-control-sm" value="{{ old('provider', $posData['provider'] ?? '') }}">
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Category POS</label>
                                <input name="category_pos" class="form-control form-control-sm" value="{{ old('category_pos', $posData['category_pos'] ?? '') }}">
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">POS Type</label>
                                <input name="pos_type" class="form-control form-control-sm" value="{{ old('pos_type', $posData['pos_type'] ?? '') }}">
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Debt</label>
                                <input name="debt" class="form-control form-control-sm" value="{{ old('debt', $posData['debt'] ?? '') }}">
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Credit</label>
                                <input name="credit" class="form-control form-control-sm" value="{{ old('credit', $posData['credit'] ?? '') }}">
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Rental</label>
                                <input name="rental" class="form-control form-control-sm" value="{{ old('rental', $posData['rental'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Type</label>
                                <input name="business_type" class="form-control form-control-sm" value="{{ old('business_type', $posData['business_type'] ?? '') }}">
                            </div>
                            <div class="col-md-3 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Date</label>
                                <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', $posData['date'] ?? '') }}">
                            </div>
                            <div class="col-md-3 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Time</label>
                                <input type="time" name="time" class="form-control form-control-sm" value="{{ old('time', $posData['time'] ?? '') }}">
                            </div>
                        @elseif($isCallCenterAccount)
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Driving License</label>
                                <input name="driving_license" class="form-control form-control-sm" value="{{ old('driving_license', $accountData['driving_license'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $accountData['email'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Phone</label>
                                <input name="phone" class="form-control form-control-sm" value="{{ old('phone', $accountData['phone'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Number</label>
                                <input name="business_number" class="form-control form-control-sm" value="{{ old('business_number', $accountData['business_number'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Corporation Number</label>
                                <input name="corporation_number" class="form-control form-control-sm" value="{{ old('corporation_number', $accountData['corporation_number'] ?? '') }}">
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Corporation Email</label>
                                <input type="email" name="corporation_email" class="form-control form-control-sm" value="{{ old('corporation_email', $accountData['corporation_email'] ?? '') }}">
                            </div>
                            <div class="col-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Corporation Documents</label>
                                <textarea name="corporation_documents" class="form-control form-control-sm" rows="2">{{ old('corporation_documents', $accountData['corporation_documents'] ?? '') }}</textarea>
                            </div>
                            <div class="col-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Previous History</label>
                                <textarea name="previous_history" class="form-control form-control-sm" rows="2">{{ old('previous_history', $accountData['previous_history'] ?? '') }}</textarea>
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Fees</label>
                                <input name="fees" class="form-control form-control-sm" value="{{ old('fees', $accountData['fees'] ?? '') }}">
                            </div>
                        @endif

                        @if(is_array($a) && count($a) > 0)
                            <div class="col-12">
                                <label class="form-label mb-1">Attachments</label>
                                <div class="attachment-grid">
                                    @foreach($a as $url)
                                        @php $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)); @endphp
                                        <div class="attachment-item">
                                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <img src="{{ $url }}">
                                            @elseif(in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                                <video controls src="{{ $url }}"></video>
                                            @elseif(in_array($ext, ['mp3', 'wav', 'ogg']))
                                                <audio controls src="{{ $url }}"></audio>
                                            @else
                                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-light btn-sm w-100">View</a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label mb-1">Add Attachments</label>
                            <input type="file" name="attachments[]" multiple class="form-control form-control-sm">
                        </div>

                        <div class="col-12 my-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="toggleRow({{ $lead }})">
                                <i class="bi bi-chevron-down"></i> Show more
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <script>
        function toggleRow(id) {
            document.querySelectorAll('.row2' + id).forEach(el => el.classList.toggle('hidden'));
        }
    </script>
@endsection