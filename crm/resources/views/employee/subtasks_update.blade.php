@extends('layout.app')

<style>
    .blur-bg {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

@section('content')
    <div class="container mt-4">
        <h3 class="text-white mb-4">Update Subtask by Lead</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @foreach ($leadValues as $lead)
            @php
                $index = $lead - 1;
                $empSubtask = $employeeSubtask;
                $commentVal = old('comment', $empSubtask->comments[$index] ?? '');
                $statusVal = $empSubtask->statuses[$index] ?? 'pending';
                $attachmentUrls = $empSubtask->attachments[$index] ?? [];
            @endphp

            <h4 class="text-warning mt-4">Lead {{ $lead }}</h4>

            <form action="{{ route('employee.subtask.update', ['id' => $subtask->id]) }}" method="POST"
                enctype="multipart/form-data" class="mb-5 p-4 border rounded blur-bg">
                @csrf
                @method('PUT')

                <input type="hidden" name="lead" value="{{ $lead }}">

                <div class="mb-3">
                    <label class="form-label text-white">Title</label>
                    <input class="form-control" value="{{ $subtask->title }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Description</label>
                    <textarea class="form-control" readonly rows="2">{{ $subtask->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Comment</label>
                    <textarea name="comment" class="form-control">{{ $commentVal }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $statusVal == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $statusVal == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $statusVal == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                @if (count($attachmentUrls))
                    <div class="mb-3 text-white">
                        <label class="form-label">Attachments</label>
                        <div class="row g-2">
                            @foreach ($attachmentUrls as $url)
                                @php
                                    $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
                                    $isAudio = in_array($ext, ['mp3', 'wav', 'ogg']);
                                    $isDoc = in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt']);
                                @endphp
                                <div class="col-md-4">
                                    <div class="p-2 border border-light rounded">
                                        @if ($isImage)
                                            <img src="{{ $url }}" class="img-fluid rounded"
                                                style="height: 180px; object-fit: contain;">
                                        @elseif($isVideo)
                                            <video controls class="w-100 rounded"
                                                style="height: 180px; object-fit: contain;">
                                                <source src="{{ $url }}">
                                            </video>
                                        @elseif($isAudio)
                                            <audio controls class="w-100 mt-2">
                                                <source src="{{ $url }}">
                                            </audio>
                                        @elseif($isDoc)
                                            <a href="{{ $url }}" target="_blank"
                                                class="btn btn-info btn-sm w-100 mt-2">View Document</a>
                                        @else
                                            <a href="{{ $url }}" target="_blank"
                                                class="btn btn-secondary btn-sm w-100 mt-2">Download File</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif


                <div class="mb-3">
                    <label class="form-label text-white">Upload New Attachments</label>
                    <input type="file" name="attachments[]" multiple class="form-control">
                </div>

          

                <button type="submit" class="btn btn-primary">Update Lead {{ $lead }}</button>
            </form>
        @endforeach

    </div>
@endsection
