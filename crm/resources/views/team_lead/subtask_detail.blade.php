@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="card text-white shadow-lg p-4 border border-secondary rounded-4">
        <h3 class="mb-4 border-bottom pb-2">Subtask Details</h3>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3"><strong>Title:</strong> <div>{{ $subtask->title }}</div></div>
                <div class="mb-3"><strong>Description:</strong> <div>{{ $subtask->description }}</div></div>
                <div class="mb-3"><strong>Assigned Employee:</strong> <div>{{ $subtask->employee->name ?? 'N/A' }}</div></div>
                <div class="mb-3"><strong>Department:</strong> <div>{{ $subtask->employee->department->name ?? 'N/A' }}</div></div>
            </div>

            <div class="col-md-6">
                <div class="mb-3"><strong>Start Date:</strong> <div>{{ $subtask->start_date ?? '-' }}</div></div>
                <div class="mb-3"><strong>Start Time:</strong> <div>{{ $subtask->start_time ?? '-' }}</div></div>
                <div class="mb-3"><strong>End Date:</strong> <div>{{ $subtask->end_date ?? '-' }}</div></div>
                <div class="mb-3"><strong>End Time:</strong> <div>{{ $subtask->end_time ?? '-' }}</div></div>
            </div>
        </div>
    </div>

    <div class="card   text-white mt-5 p-4 rounded-4">
        <h4 class="border-bottom pb-2 mb-4">All Subtasks for {{ $subtask->employee->name }}</h4>

        <table class="table table-blur table-bordered align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Lead</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Attachments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employeeSubtasks as $task)
                    @php $empSub = $task->employeeSubtask; @endphp
                    @for ($i = 0; $i < ($task->lead ?? 1); $i++)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $empSub->comments[$i] ?? '-' }}</td>
                            <td>{{ ucfirst($empSub->statuses[$i] ?? 'pending') }}</td>
                            <td>
                                @if (!empty($empSub->attachments[$i]))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($empSub->attachments[$i] as $url)
                                            @php
                                                $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                                $isVideo = in_array($ext, ['mp4','mov','avi','webm']);
                                                $isAudio = in_array($ext, ['mp3','wav','ogg']);
                                                $isDoc   = in_array($ext, ['pdf','docx','txt']);
                                            @endphp

                                            @if ($isImage)
                                                <a href="{{ $url }}" target="_blank">
                                                    <img src="{{ $url }}" class="rounded" style="height: 50px; width: 50px; object-fit: cover;">
                                                </a>
                                            @elseif ($isVideo)
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary">Video</a>
                                            @elseif ($isAudio)
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-warning">Audio</a>
                                            @elseif ($isDoc)
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-info">Doc</a>
                                            @else
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-light">File</a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endfor
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
