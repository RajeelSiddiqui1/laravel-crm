@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h3 class="text-white">Update Subtask</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employee.subtask.update', $subtask->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label text-white">Comment</label>
            <textarea name="comment" class="form-control" rows="4">{{ old('comment', $subtask->comment) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label text-white">Status</label>
            <select name="status" class="form-select">
                <option value="pending" {{ $subtask->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $subtask->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $subtask->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        @if($subtask->attachment)
            <div class="mb-3">
                <label class="form-label text-white">Current Attachment:</label><br>
                @php
                    $attachmentUrl = $subtask->attachment;
                    $extension = pathinfo(parse_url($attachmentUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'webm']);
                    $isAudio = in_array(strtolower($extension), ['mp3', 'wav', 'ogg']);
                    $isDocument = in_array(strtolower($extension), ['pdf', 'docx', 'xlsx', 'txt']);
                @endphp

                @if($isImage)
                    <img src="{{ $attachmentUrl }}" alt="Current Attachment" class="img-fluid" style="max-width: 300px; height: auto;">
                @elseif($isVideo)
                    <video controls class="img-fluid" style="max-width: 400px;">
                        <source src="{{ $attachmentUrl }}" type="video/{{ strtolower($extension) }}">
                    </video>
                @elseif($isAudio)
                    <audio controls class="w-100">
                        <source src="{{ $attachmentUrl }}" type="audio/{{ strtolower($extension) }}">
                    </audio>
                @elseif($isDocument)
                    <a href="{{ $attachmentUrl }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-file-alt"></i> View Document ({{ strtoupper($extension) }})
                    </a>
                @else
                    <a href="{{ $attachmentUrl }}" target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-external-link-alt"></i> View Attachment
                    </a>
                @endif
                <p class="text-white-50 mt-2">To replace, upload a new file below.</p>
            </div>
        @endif

        <div class="mb-3">
            <label for="attachment" class="form-label text-white">Attachment (optional)</label>
            <input type="file" name="attachment" id="attachment" class="form-control" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
            <small class="form-text text-white-50">Allowed file types: Images (jpg, png, gif), Videos (mp4), Audio (mp3, wav), Documents (pdf, docx, xlsx, txt).</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Subtask</button>
    </form>
</div>
@endsection
