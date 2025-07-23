@extends('layout.app')

@section('content')
<div class="container my-5">
    <div class="card glass-card shadow-lg border-0 rounded-5 mb-5 overflow-hidden">
        <div class="card-header bg-gradient p-4 d-flex align-items-center">
            <i class="bi bi-person-lines-fill me-2 fs-4"></i>
            <span class="fw-bold fs-5">Subtask Details</span>
        </div>

        <div class="p-4">
            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Title</span>
                        <div class="fs-6">{{ $subtask->title }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Description</span>
                        <div class="fs-6">{{ $subtask->description }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Assigned Employee</span>
                        <div class="fs-6">{{ $subtask->employee->name ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Department</span>
                        <div class="fs-6">{{ $subtask->employee->department->name ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Start Date</span>
                        <div class="fs-6">{{ $subtask->start_date ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Start Time</span>
                        <div class="fs-6">{{ $subtask->start_time ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">End Date</span>
                        <div class="fs-6">{{ $subtask->end_date ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">End Time</span>
                        <div class="fs-6">{{ $subtask->end_time ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($subtask->employeeSubtask)
    <div class="card glass-card shadow-lg border-0 rounded-5 mb-5">
        <div class="card-header bg-gradient p-3">
            <i class="bi bi-file-earmark-text me-2"></i>Employee Info
        </div>

        <div class="p-4">
            <div class="row g-3">
                @if(count($attachments))
                    <div class="col-12">
                        <label class="form-label mb-1">Attachments</label>
                        <div class="attachment-grid d-flex flex-wrap gap-3">
                            @foreach($attachments as $url)
                                @php $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)); @endphp
                                <div class="attachment-item border rounded p-2 bg-dark">
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ $url }}" alt="attachment" style="max-width: 120px; max-height: 120px;">
                                    @elseif(in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                        <video controls style="max-width: 160px;">
                                            <source src="{{ $url }}">
                                        </video>
                                    @elseif(in_array($ext, ['mp3', 'wav', 'ogg']))
                                        <audio controls>
                                            <source src="{{ $url }}">
                                        </audio>
                                    @else
                                        <a href="{{ $url }}" target="_blank" class="btn btn-outline-light btn-sm w-100">View</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @foreach([
                    'name','business_name','business_num','personal_num',
                    'personal_email','business_email','address','perivos',
                    'provider','category_pos','pos_type','debt','credit',
                    'rentle','date','time','bussiness_type'
                ] as $key)
                    @php
                        $values = (array) $subtask->employeeSubtask->{$key};
                    @endphp
                    @if(!empty(array_filter($values)))
                    <div class="col-12 col-md-4">
                        <div class="info-chip">
                            <strong class="text-capitalize text-info d-block mb-1">{{ str_replace('_',' ',$key) }}</strong>
                            <span>{{ implode(', ', $values) }}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .glass-card {
        background: rgba(20, 20, 20, 0.75);
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid rgba(255,255,255,.1);
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .glass-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0,0,0,.4);
    }

   
</style>
@endsection