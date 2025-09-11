@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold text-light display-6">{{ $subtask->title }} - Details</h2>
        <a href="{{ url()->previous() }}" class="btn btn-outline-light btn-lg rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>

    @if ($subtask->attachments)
    <div class="card bg-glass mb-5 shadow-lg border-0">
        <div class="card-body p-4">
            <h4 class="text-light fw-semibold mb-3">Subtask Attachment</h4>
            <div class="attachment-container">
                @php
                    $subtaskExt = strtolower(pathinfo(parse_url($subtask->attachments, PHP_URL_PATH), PATHINFO_EXTENSION));
                @endphp
                @if (in_array($subtaskExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <a href="{{ $subtask->attachments }}" data-lightbox="subtask-attachment">
                        <img src="{{ $subtask->attachments }}" alt="Subtask Attachment" class="img-fluid rounded shadow-sm" style="max-height: 300px; width: auto;">
                    </a>
                    <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                        <i class="bi bi-download me-2"></i> Download Image
                    </a>
                @elseif (in_array($subtaskExt, ['mp4', 'mov', 'avi', 'webm']))
                    <div class="media-wrapper">
                        <video controls class="w-100 rounded shadow-sm" style="max-height: 300px;">
                            <source src="{{ $subtask->attachments }}" type="video/{{ $subtaskExt }}">
                        </video>
                        <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                            <i class="bi bi-download me-2"></i> Download Video
                        </a>
                    </div>
                @elseif (in_array($subtaskExt, ['mp3', 'wav', 'ogg']))
                    <div class="media-wrapper">
                        <audio controls class="w-100">
                            <source src="{{ $subtask->attachments }}" type="audio/{{ $subtaskExt }}">
                        </audio>
                        <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                            <i class="bi bi-download me-2"></i> Download Audio
                        </a>
                    </div>
                @else
                    <a href="{{ $subtask->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i> Download File ({{ strtoupper($subtaskExt) }})
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if ($posRecords->isNotEmpty())
        <h4 class="mt-5 mb-4 text-light fw-semibold">Call Center POS Records</h4>
        @forelse($posRecords as $pos)
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-glass shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-light mb-0">{{ $pos->name ?? 'N/A' }}</h5>
                                <span class="badge {{ $pos->status === 'active' ? 'bg-success' : ($pos->status === 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $pos->status }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Employee:</strong> {{ $pos->employee->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comment:</strong> {{ $pos->comment ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business:</strong> {{ $pos->business_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Number:</strong> {{ $pos->business_number ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Personal Number:</strong> {{ $pos->personal_number ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Personal Email:</strong> {{ $pos->personal_email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Email:</strong> {{ $pos->business_email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Address:</strong> {{ $pos->address ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Provider:</strong> {{ $pos->provider ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Category:</strong> {{ $pos->category_pos ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>POS Type:</strong> {{ $pos->pos_type ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Debt:</strong> ${{ number_format($pos->debt ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Credit:</strong> ${{ number_format($pos->credit ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Rental:</strong> ${{ number_format($pos->rental ?? 0, 2) }}</p>
                                    <p class="mb-2"><strong>Business Type:</strong> {{ $pos->business_type ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Date:</strong> {{ $pos->date ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Time:</strong> {{ $pos->time ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if ($pos->attachments)
                                <div class="attachment-container mt-4">
                                    @php
                                        $posExt = strtolower(pathinfo(parse_url($pos->attachments, PHP_URL_PATH), PATHINFO_EXTENSION));
                                    @endphp
                                    @if (in_array($posExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <a href="{{ $pos->attachments }}" data-lightbox="pos-attachment-{{ $pos->id }}">
                                            <img src="{{ $pos->attachments }}" alt="POS Attachment" class="img-fluid rounded shadow-sm" style="max-height: 200px; width: auto;">
                                        </a>
                                        <a href="{{ $pos->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                                            <i class="bi bi-download me-2"></i> Download Image
                                        </a>
                                    @elseif (in_array($posExt, ['mp4', 'mov', 'avi', 'webm']))
                                        <div class="media-wrapper">
                                            <video controls class="w-100 rounded shadow-sm" style="max-height: 200px;">
                                                <source src="{{ $pos->attachments }}" type="video/{{ $posExt }}">
                                            </video>
                                            <a href="{{ $pos->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                                                <i class="bi bi-download me-2"></i> Download Video
                                            </a>
                                        </div>
                                    @elseif (in_array($posExt, ['mp3', 'wav', 'ogg']))
                                        <div class="media-wrapper">
                                            <audio controls class="w-100">
                                                <source src="{{ $pos->attachments }}" type="audio/{{ $posExt }}">
                                            </audio>
                                            <a href="{{ $pos->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                                                <i class="bi bi-download me-2"></i> Download Audio
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ $pos->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill">
                                            <i class="bi bi-file-earmark-arrow-down me-2"></i> Download File ({{ strtoupper($posExt) }})
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    @endif

    @if ($accountRecords->isNotEmpty())
        <h4 class="mt-5 mb-4 text-light fw-semibold">Call Center Account Records</h4>
        @forelse($accountRecords as $account)
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card bg-glass shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-light mb-0">{{ $account->email ?? 'N/A' }}</h5>
                                <span class="badge {{ $account->status === 'active' ? 'bg-success' : ($account->status === 'inactive' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ $account->status }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Employee:</strong> {{ $account->employee->name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comments:</strong> {{ $account->comments ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Phone:</strong> {{ $account->phone ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $account->email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Business Number:</strong> {{ $account->business_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Corporation Number:</strong> {{ $account->corporation_number ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Email:</strong> {{ $account->corporation_email ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Corporation Documents:</strong> {{ $account->corporation_documents ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Previous History:</strong> {{ $account->previous_history ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Fees:</strong> ${{ number_format($account->fees ?? 0, 2) }}</p>
                                </div>
                            </div>
                            @if ($account->attachments)
                                <div class="attachment-container mt-4">
                                    @php
                                        $accountExt = strtolower(pathinfo(parse_url($account->attachments, PHP_URL_PATH), PATHINFO_EXTENSION));
                                    @endphp
                                    @if (in_array($accountExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <a href="{{ $account->attachments }}" data-lightbox="account-attachment-{{ $account->id }}">
                                            <img src="{{ $account->attachments }}" alt="Account Attachment" class="img-fluid rounded shadow-sm" style="max-height: 200px; width: auto;">
                                        </a>
                                        <a href="{{ $account->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                                            <i class="bi bi-download me-2"></i> Download Image
                                        </a>
                                    @elseif (in_array($accountExt, ['mp4', 'mov', 'avi', 'webm']))
                                        <div class="media-wrapper">
                                            <video controls class="w-100 rounded shadow-sm" style="max-height: 200px;">
                                                <source src="{{ $account->attachments }}" type="video/{{ $accountExt }}">
                                            </video>
                                            <a href="{{ $account->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                                                <i class="bi bi-download me-2"></i> Download Video
                                            </a>
                                        </div>
                                    @elseif (in_array($accountExt, ['mp3', 'wav', 'ogg']))
                                        <div class="media-wrapper">
                                            <audio controls class="w-100">
                                                <source src="{{ $account->attachments }}" type="audio/{{ $accountExt }}">
                                            </audio>
                                            <a href="{{ $account->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill mt-2">
                                                <i class="bi bi-download me-2"></i> Download Audio
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ $account->attachments }}" download class="btn btn-outline-light btn-sm rounded-pill">
                                            <i class="bi bi-file-earmark-arrow-down me-2"></i> Download File ({{ strtoupper($accountExt) }})
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    @endif
</div>
@endsection

@section('styles')
<style>
.bg-glass { background: rgba(255,255,255,0.15); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255,255,255,0.3); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.bg-glass:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); }
body { background: linear-gradient(135deg,#1e3a8a,#3b82f6); min-height:100vh; font-family:'Inter',sans-serif; }
.text-light { color: #f8f9fa !important; }
.card-title { font-size:1.5rem; font-weight:600; }
.badge { font-size:0.9rem; padding:0.5em 1em; border-radius:12px; }
.attachment-container { display:flex; flex-direction:column; align-items:flex-start; gap:1rem; }
.media-wrapper { width:100%; max-width:500px; }
.img-fluid, video, audio { max-width:100%; height:auto; border-radius:8px; }
.btn-outline-light { border-color: rgba(255,255,255,0.7); color:#f8f9fa; transition: background 0.3s ease, transform 0.2s ease; }
.btn-outline-light:hover { background: rgba(255,255,255,0.3); color:#fff; transform: scale(1.05); }
@media(max-width:768px){ .card-title{font-size:1.25rem;} .btn-outline-light{font-size:0.9rem;} .img-fluid, video{max-height:200px;} }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script>
lightbox.option({ 'resizeDuration': 200, 'wrapAround': true, 'disableScrolling': true });
</script>
@endsection
