@extends('layout.app')

@section('content')
<style>
    body {
        background: url('/your-background.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .glass-card {
      
        backdrop-filter: blur(2px);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .attachment-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .attachment-preview img,
    .attachment-preview video {
        border-radius: 8px;
        max-height: 200px;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .attachment-preview img:hover,
    .attachment-preview video:hover {
        transform: scale(1.03);
    }
</style>

<div class="container mt-5 text-white">
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-dark">Subtask Details</h3>
            <a href="{{ url()->previous() }}" class="btn btn-light">← Back</a>
        </div>

        <table class="table  table-hover table-bordered">
            <tr>
                <th>Employee</th>
                <td>{{ $subtask->employee->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Subtask Title</th>
                <td>{{ $subtask->title }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $subtask->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ $subtask->start_date }}</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{ $subtask->end_date }}</td>
            </tr>
            <tr>
                <th>Start Time</th>
                <td>{{ $subtask->start_time }}</td>
            </tr>
            <tr>
                <th>End Time</th>
                <td>{{ $subtask->end_time }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-{{ $statusColors[$subtask->status] ?? 'light' }}">
                        {{ ucfirst($subtask->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{ $subtask->remarks ?? '—' }}</td>
            </tr>
            <tr>
                <th>Attachments</th>
                <td>
                    @if($subtask->attachments && is_array($subtask->attachments))
                        <div class="attachment-preview">
                            @foreach($subtask->attachments as $file)
                                @php
                                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ $file }}" alt="Image Attachment" class="img-fluid" style="max-width: 200px;">
                                @elseif(in_array($extension, ['mp4', 'mov', 'webm']))
                                    <video controls style="max-width: 300px;">
                                        <source src="{{ $file }}" type="video/{{ $extension }}">
                                    </video>
                                @else
                                    <a href="{{ $file }}" target="_blank" class="btn btn-info">Download File</a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <span>No attachment uploaded.</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
