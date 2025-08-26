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

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
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
                $c = $employeeSubtask->comment ?? '';
                $s = $employeeSubtask->status ?? 'pending';
                $posRecord = $isCallCenterPos && isset($employeeSubtask->cell_center_pos_ids[$lead - 1])
                    ? ($posData[$employeeSubtask->cell_center_pos_ids[$lead - 1]] ?? null)
                    : null;
                $accountRecord = $isCallCenterAccount && isset($employeeSubtask->cell_center_account_ids[$lead - 1])
                    ? ($accountData[$employeeSubtask->cell_center_account_ids[$lead - 1]] ?? null)
                    : null;
                $a = $isCallCenterPos ? ($posRecord ? ($posRecord->attachments ?? []) : []) : ($isCallCenterAccount ? ($accountRecord ? ($accountRecord->attachments ?? []) : []) : []);
                // Ensure $a is an array
                if (is_string($a)) {
                    try {
                        $decoded = json_decode($a, true);
                        $a = is_array($decoded) ? $decoded : (!empty($a) ? [$a] : []);
                    } catch (\Exception $e) {
                        $a = !empty($a) ? [$a] : [];
                    }
                } elseif (!is_array($a)) {
                    $a = [];
                }
            @endphp

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lead {{ $lead }}</span>
                    <button form="form{{ $lead }}" type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
                <form id="form{{ $lead }}" action="{{ route($isCallCenterPos ? 'employee.subtask.pos.update' : 'employee.subtask.account.update', ['id' => $subtask->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body row g-3">
                        <input type="hidden" name="lead" value="{{ $lead }}">
                        <input type="hidden" name="employee_id" value="{{ Auth::guard('employee')->user()->id ?? '' }}">
                        <input type="hidden" name="subtask_id" value="{{ $subtask->id }}">

                        <div class="col-md-8">
                            <label class="form-label mb-1">Title</label>
                            <input class="form-control form-control-sm" value="{{ $subtask->title }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1">Description</label>
                            <textarea class="form-control form-control-sm" rows="2" readonly>{{ $subtask->description }}</textarea>
                        </div>

                        @if($isCallCenterPos)
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm @error('status') is-invalid @enderror">
                                    <option value="pending" {{ $s == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $s == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $s == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Comment</label>
                                <textarea name="comment" class="form-control form-control-sm @error('comment') is-invalid @enderror" rows="2">{{ old('comment', $c) }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Name</label>
                                <input name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name', $posRecord->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Name</label>
                                <input name="business_name" class="form-control form-control-sm @error('business_name') is-invalid @enderror" value="{{ old('business_name', $posRecord->business_name ?? '') }}">
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Number</label>
                                <input name="business_number" class="form-control form-control-sm @error('business_number') is-invalid @enderror" value="{{ old('business_number', $posRecord->business_number ?? '') }}">
                                @error('business_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Personal Number</label>
                                <input name="personal_number" class="form-control form-control-sm @error('personal_number') is-invalid @enderror" value="{{ old('personal_number', $posRecord->personal_number ?? '') }}">
                                @error('personal_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Personal Email</label>
                                <input type="email" name="personal_email" class="form-control form-control-sm @error('personal_email') is-invalid @enderror" value="{{ old('personal_email', $posRecord->personal_email ?? '') }}">
                                @error('personal_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Email</label>
                                <input type="email" name="business_email" class="form-control form-control-sm @error('business_email') is-invalid @enderror" value="{{ old('business_email', $posRecord->business_email ?? '') }}">
                                @error('business_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Address</label>
                                <textarea name="address" class="form-control form-control-sm @error('address') is-invalid @enderror" rows="2">{{ old('address', $posRecord->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Provider</label>
                                <input name="provider" class="form-control form-control-sm @error('provider') is-invalid @enderror" value="{{ old('provider', $posRecord->provider ?? '') }}">
                                @error('provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Category POS</label>
                                <input name="category_pos" class="form-control form-control-sm @error('category_pos') is-invalid @enderror" value="{{ old('category_pos', $posRecord->category_pos ?? '') }}">
                                @error('category_pos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">POS Type</label>
                                <input name="pos_type" class="form-control form-control-sm @error('pos_type') is-invalid @enderror" value="{{ old('pos_type', $posRecord->pos_type ?? '') }}">
                                @error('pos_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Debt</label>
                                <input name="debt" class="form-control form-control-sm @error('debt') is-invalid @enderror" value="{{ old('debt', $posRecord->debt ?? '') }}">
                                @error('debt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Credit</label>
                                <input name="credit" class="form-control form-control-sm @error('credit') is-invalid @enderror" value="{{ old('credit', $posRecord->credit ?? '') }}">
                                @error('credit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Rental</label>
                                <input name="rental" class="form-control form-control-sm @error('rental') is-invalid @enderror" value="{{ old('rental', $posRecord->rental ?? '') }}">
                                @error('rental')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Type</label>
                                <input name="business_type" class="form-control form-control-sm @error('business_type') is-invalid @enderror" value="{{ old('business_type', $posRecord->business_type ?? '') }}">
                                @error('business_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Date</label>
                                <input type="date" name="date" class="form-control form-control-sm @error('date') is-invalid @enderror" value="{{ old('date', $posRecord->date ?? '') }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Time</label>
                                <input type="time" name="time" class="form-control form-control-sm @error('time') is-invalid @enderror" value="{{ old('time', $posRecord->time ?? '') }}">
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @elseif($isCallCenterAccount)
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm @error('status') is-invalid @enderror">
                                    <option value="pending" {{ $s == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $s == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $s == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Comments</label>
                                <textarea name="comments" class="form-control form-control-sm @error('comments') is-invalid @enderror" rows="2">{{ old('comments', $c) }}</textarea>
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Driving License</label>
                                <input name="driving_license" class="form-control form-control-sm @error('driving_license') is-invalid @enderror" value="{{ old('driving_license', $accountRecord->driving_license ?? '') }}">
                                @error('driving_license')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $accountRecord->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Phone</label>
                                <input name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $accountRecord->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Business Number</label>
                                <input name="business_number" class="form-control form-control-sm @error('business_number') is-invalid @enderror" value="{{ old('business_number', $accountRecord->business_number ?? '') }}">
                                @error('business_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Corporation Number</label>
                                <input name="corporation_number" class="form-control form-control-sm @error('corporation_number') is-invalid @enderror" value="{{ old('corporation_number', $accountRecord->corporation_number ?? '') }}">
                                @error('corporation_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Corporation Email</label>
                                <input type="email" name="corporation_email" class="form-control form-control-sm @error('corporation_email') is-invalid @enderror" value="{{ old('corporation_email', $accountRecord->corporation_email ?? '') }}">
                                @error('corporation_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Corporation Documents</label>
                                <textarea name="corporation_documents" class="form-control form-control-sm @error('corporation_documents') is-invalid @enderror" rows="2">{{ old('corporation_documents', $accountRecord->corporation_documents ?? '') }}</textarea>
                                @error('corporation_documents')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Previous History</label>
                                <textarea name="previous_history" class="form-control form-control-sm @error('previous_history') is-invalid @enderror" rows="2">{{ old('previous_history', $accountRecord->previous_history ?? '') }}</textarea>
                                @error('previous_history')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 hidden row2{{ $lead }}">
                                <label class="form-label mb-1">Fees</label>
                                <input name="fees" class="form-control form-control-sm @error('fees') is-invalid @enderror" value="{{ old('fees', $accountRecord->fees ?? '') }}">
                                @error('fees')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <input type="file" name="attachments[]" multiple class="form-control form-control-sm @error('attachments.*') is-invalid @enderror">
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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