@extends('layout.app')
<style>
    .blur-bg {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
    }
</style>    
@section('content')
<div class="container mt-4">
    <h3 class="text-white mb-4">Update Subtask</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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

    <form action="{{ route('employee.subtask.update', $subtask->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')


         <div class="mb-3">
            <label class="form-label text-white">Title</label>
            <input name="comment" class="form-control" value="{{  $subtask->title }}" readonly>
        </div>
         <div class="mb-3">
            <label class="form-label text-white">Description</label>
            <textarea name="comment" class="form-control" rows="4" readonly>{{  $subtask->description }}</textarea>
        </div>
       
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

        @if ($subtask->attachments && is_array($subtask->attachments))
        <div class="mb-4 text-white">
            <label class="form-label">Uploaded Attachments</label>
            <div class="row g-4">
                @foreach ($subtask->attachments as $url)
                    @php
                        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']);
                        $isVideo = in_array(strtolower($ext), ['mp4','mov','avi','webm']);
                        $isAudio = in_array(strtolower($ext), ['mp3','wav','ogg']);
                        $isDoc   = in_array(strtolower($ext), ['pdf','docx','doc','txt','xlsx','xls']);
                    @endphp
                    <div class="col-md-4 mt-3">
                        <div class="border border-white rounded p-3  h-100 d-flex flex-column justify-content-between">
                            <div class="text-center mb-2">
                                @if($isImage)
                                    <img src="{{ $url }}" class="img-fluid rounded" style="height: 200px; object-fit: contain;">
                                @elseif($isVideo)
                                    <video controls style="width: 100%; height: 200px; object-fit: contain;" class="rounded">
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

                            <button type="button" class="btn btn-success btn-sm w-100 mb-2" onclick="triggerDownload('{{ $url }}')">Download</button>

                          
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mb-4">
            <label for="attachments" class="form-label text-white">Upload Attachments</label>
            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
        </div>

        <button type="submit" class="btn btn-primary">Update Subtask</button>
    </form>
</div>

<script>
    function triggerDownload(url) {
        fetch(url, { mode: 'cors' })
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
            .catch(err => {
                alert("Download failed.");
                console.error(err);
            });
    }
</script>
@endsection
