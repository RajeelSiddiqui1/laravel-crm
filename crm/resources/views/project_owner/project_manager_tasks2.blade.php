@extends('layout.app')

@section('content')
<div class="container py-5">
    <!-- Page Title -->
    <h2 class="text-center fw-bold mb-5">All Assigned Owner Tasks</h2>

    {{-- SweetAlert Success/Error --}}
    @if (session('success_swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success_swal') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
    @if (session('error_swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error_swal') }}",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    {{-- Helper to render attachments --}}
    @php
        function renderAttachment($file) {
            if (!$file) return '<span class="text-muted small">No Attachments</span>';

            $fileUrl = \Illuminate\Support\Str::startsWith($file, ['http://', 'https://'])
                ? $file
                : asset('storage/' . $file);

            $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
            $fileName = basename($file);

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return '<a href="'.$fileUrl.'" target="_blank">
                            <img src="'.$fileUrl.'" alt="Image" class="attachment-preview">
                        </a>';
            } elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm'])) {
                return '<video src="'.$fileUrl.'" controls class="attachment-preview"></video>';
            } elseif (in_array($ext, ['mp3', 'wav', 'ogg'])) {
                return '<audio controls class="w-100">
                            <source src="'.$fileUrl.'" type="audio/'.$ext.'">
                        </audio>';
            } elseif ($ext === 'pdf') {
                return '<a href="'.$fileUrl.'" target="_blank" class="attachment-icon">
                            <img src="https://img.icons8.com/fluency/48/pdf.png" alt="PDF">
                            <span>'.\Illuminate\Support\Str::limit($fileName, 12).'</span>
                        </a>';
            } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
                return '<a href="'.$fileUrl.'" target="_blank" class="attachment-icon">
                            <img src="https://img.icons8.com/fluency/48/ms-excel.png" alt="Excel">
                            <span>'.\Illuminate\Support\Str::limit($fileName, 12).'</span>
                        </a>';
            } elseif (in_array($ext, ['doc', 'docx'])) {
                return '<a href="'.$fileUrl.'" target="_blank" class="attachment-icon">
                            <img src="https://img.icons8.com/fluency/48/ms-word.png" alt="Word">
                            <span>'.\Illuminate\Support\Str::limit($fileName, 12).'</span>
                        </a>';
            } else {
                return '<a href="'.$fileUrl.'" target="_blank" class="attachment-icon">
                            <img src="https://img.icons8.com/fluency/48/file.png" alt="File">
                            <span>'.\Illuminate\Support\Str::limit($fileName, 12).'</span>
                        </a>';
            }
        }
    @endphp

    {{-- Account T1 --}}
    @if ($tasks->accountT1)
        <div class="card shadow-lg mb-5 rounded-3 border-0">
            <div class="card-header bg-light text-center fw-semibold fs-5">Account T1</div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small">Client Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->clientname ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Business Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->bussiness_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Period</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->period ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Year</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->year ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Driving License</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->driving_license ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Family Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->famliy_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">SIM Number</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->sim_number ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Department</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->department->name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Team Lead</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT1->teamLead->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Account T2 --}}
    @if ($tasks->accountT2)
        <div class="card shadow-lg mb-5 rounded-3 border-0">
            <div class="card-header bg-light text-center fw-semibold fs-5">Account T2</div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small">Client Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->clientname ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Phone</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->phone ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Email</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->email ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Corporation Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->corporation_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Corporation Number</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->corporation_number ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Due Date</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->due_date ? \Carbon\Carbon::parse($tasks->accountT2->due_date)->format('M d, Y') : 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Nature of Business</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->nature_of_business ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Priority</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->priority ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Department</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->department->name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Team Lead</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountT2->teamLead->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="mt-5">
                    <h6 class="fw-semibold mb-3">Attachments</h6>
                    <div class="d-flex flex-wrap gap-3">
                        {!! renderAttachment($tasks->accountT2->attachments ?? null) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Account HST --}}
    @if ($tasks->accountHst)
        <div class="card shadow-lg mb-5 rounded-3 border-0">
            <div class="card-header bg-light text-center fw-semibold fs-5">Account HST</div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small">Client Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->clientname ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Phone</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->phone ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Email</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->email ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Corporation Name</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->corporation_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Corporation Number</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->corporation_number ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Due Date</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->due_date ? \Carbon\Carbon::parse($tasks->accountHst->due_date)->format('M d, Y') : 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Nature of Business</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->nature_of_business ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Priority</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->priority ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Department</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->department->name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Team Lead</label>
                        <input type="text" class="form-control" value="{{ $tasks->accountHst->teamLead->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="mt-5">
                    <h6 class="fw-semibold mb-3">Attachments</h6>
                    <div class="d-flex flex-wrap gap-3">
                        {!! renderAttachment($tasks->accountHst->attachments ?? null) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Manager Operation --}}
    @if ($tasks->managerOperation)
        <div class="card shadow-lg mb-5 rounded-3 border-0">
            <div class="card-header bg-light text-center fw-semibold fs-5">Operation Task</div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small">Description</label>
                        <input type="text" class="form-control" value="{{ $tasks->managerOperation->description ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Priority</label>
                        <input type="text" class="form-control" value="{{ $tasks->managerOperation->priority ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Manager Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $tasks->managerOperation->manager_status ?? 'N/A')) }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Team Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $tasks->managerOperation->team_status ?? 'N/A')) }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Department</label>
                        <input type="text" class="form-control" value="{{ $tasks->managerOperation->department->name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Team Lead</label>
                        <input type="text" class="form-control" value="{{ $tasks->managerOperation->teamLead->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="mt-5">
                    <h6 class="fw-semibold mb-3">Attachments</h6>
                    <div class="d-flex flex-wrap gap-3">
                        {!! renderAttachment($tasks->managerOperation->attachments ?? null) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="{{ route('project_owner.manager_tasks') }}" class="btn btn-secondary">Back to Tasks</a>
    </div>
</div>

<style>
    .attachment-preview {
        width: 120px;
        height: 90px;
        object-fit: cover;
        border-radius: .5rem;
        border: 1px solid #ddd;
    }
    .attachment-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        font-size: .8rem;
        color: #333;
        max-width: 100px;
    }
    .attachment-icon img {
        width: 48px;
        height: 48px;
    }
    .attachment-icon span {
        margin-top: .25rem;
        text-align: center;
        word-break: break-word;
    }
    input.form-control {
        background: transparent;
        border: 1px solid #fff;
    }
</style>
@endsection