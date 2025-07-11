@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="card  text-white shadow-lg p-4 border border-secondary rounded-4">
        <h3 class="mb-4 border-bottom pb-2">Subtask Details</h3>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <strong>Title:</strong>
                    <div>{{ $subtask->title }}</div>
                </div>
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div>{{ $subtask->description }}</div>
                </div>
                <div class="mb-3">
                    <strong>Assigned To:</strong>
                    <div>{{ $subtask->employee->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <div>{{ ucfirst($subtask->status ?? 'pending') }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <strong>Start Date:</strong>
                    <div>{{ $subtask->start_date ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <strong>Start Time:</strong>
                    <div>{{ $subtask->start_time ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <strong>End Date:</strong>
                    <div>{{ $subtask->end_date ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <strong>End Time:</strong>
                    <div>{{ $subtask->end_time ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <strong>Comment:</strong>
            <div>{{ $subtask->comment ?? '-' }}</div>
        </div>

        @if ($subtask->attachments && is_array($subtask->attachments))
        <hr class="border-secondary">
        <div class="mb-3">
            <h5 class="mb-3">Attachments</h5>
            <div class="row g-4">
                @foreach ($subtask->attachments as $url)
                    @php
                        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']);
                        $isVideo = in_array(strtolower($ext), ['mp4','mov','avi','webm']);
                        $isAudio = in_array(strtolower($ext), ['mp3','wav','ogg']);
                        $isDoc   = in_array(strtolower($ext), ['pdf','docx','doc','txt','xlsx','xls']);
                    @endphp
                    <div class="col-md-4">
                        <div class=" bg-opacity-10 backdrop-blur rounded-4 p-3 h-100 d-flex flex-column justify-content-between">
                            <div class="text-center mb-2">
                                @if($isImage)
                                    <img src="{{ $url }}" class="img-fluid rounded" style="height: 200px; object-fit: contain;">
                                @elseif($isVideo)
                                    <video controls class="w-100 rounded" style="height: 200px; object-fit: contain;">
                                        <source src="{{ $url }}">
                                    </video>
                                @elseif($isAudio)
                                    <audio controls class="w-100 mt-4">
                                        <source src="{{ $url }}">
                                    </audio>
                                @elseif($isDoc)
                                    <a href="{{ $url }}" target="_blank" class="btn btn-info btn-sm w-100">View Document</a>
                                @else
                                    <a href="{{ $url }}" target="_blank" class="btn btn-light btn-sm w-100">View File</a>
                                @endif
                            </div>
                            <button type="button" class="btn btn-success btn-sm w-100" onclick="triggerDownload('{{ $url }}')">
                                <i class="bi bi-download me-1"></i> Download
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .backdrop-blur {
        backdrop-filter: blur(8px);
        background-color: rgba(255, 255, 255, 0.05);
    }
</style>

<script>
    function triggerDownload(url) {
        const isPDF = url.toLowerCase().includes('.pdf');
        const downloadUrl = isPDF ? url.replace('/upload/', '/upload/fl_attachment/') : url;

        fetch(downloadUrl, { mode: 'cors' })
            .then(response => {
                if (!response.ok) throw new Error('Download failed.');
                return response.blob();
            })
            .then(blob => {
                const blobUrl = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = blobUrl;
                link.download = url.split('/').pop().split('?')[0];
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(blobUrl);
            })
            .catch(() => alert('Download failed.'));
    }
</script>
@endsection
