@extends('layout.app')

@section('content')
<div class="container my-5">
    {{-- Hero card ------------------------------------------------}}
    <div class="card glass-card  shadow-lg border-0 rounded-5 mb-5 overflow-hidden">
        <div class="card-header bg-gradient p-4 d-flex align-items-center">
            <i class="bi bi-person-lines-fill me-2 fs-4"></i>
            <span class="fw-bold fs-5">Subtask Details</span>
        </div>

        <div class="p-4">
            <div class="row gy-4">
                {{-- Left column --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Title</span>
                        <div class="fs-6 ">{{ $subtask->title }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Description</span>
                        <div class="fs-6 ">{{ $subtask->description }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Assigned Employee</span>
                        <div class="fs-6 ">{{ $subtask->employee->name ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Department</span>
                        <div class="fs-6 ">{{ $subtask->employee->department->name ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Right column --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Start Date</span>
                        <div class="fs-6 ">{{ $subtask->start_date ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">Start Time</span>
                        <div class="fs-6 ">{{ $subtask->start_time ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">End Date</span>
                        <div class="fs-6 ">{{ $subtask->end_date ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold">End Time</span>
                        <div class="fs-6 ">{{ $subtask->end_time ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Extra information (only if present) ----------------------}}
    @if($subtask->employeeSubtask)
    <div class="card glass-card  shadow-lg border-0 rounded-5 mb-5">
        <div class="card-header bg-gradient p-3">
            <i class="bi bi-file-earmark-text me-2"></i>Employee Info
        </div>

        <div class="p-4">
            <div class="row g-3">
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
                            <span class="">{{ implode(', ', $values) }}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Inline minimal CSS (no build step) --}}
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

    .info-chip {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: .75rem;
        padding: .75rem 1rem;
        height: 100%;
    }
</style>
@endsection