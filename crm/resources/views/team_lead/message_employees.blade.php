@extends('layout.app')

@section('content')
<div class="container py-4">
    <!-- Chat Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold text-dark">Chat with {{ $employee->name }}</h4>
        <span class="badge bg-success text-white">Online</span>
    </div>

    <!-- Chat Box -->
    <div class="card shadow-sm border-0" style="height: 500px; overflow-y: auto; background-color: #f8f9fa;" id="chat-box">
        <div class="card-body p-4">
            @foreach ($messages as $msg)
                @php
                    $isSender = $msg->sender_id == Auth::guard('team_lead')->id();
                    $imagePath = $isSender
                        ? asset('images/team_leads/' . Auth::guard('team_lead')->user()->image)
                        : asset('images/employees/' . $employee->image);
                    $attachments = $msg->attachments ?? null; // Single attachments per message
                @endphp

                <div class="d-flex mb-3 {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
                    @if (!$isSender)
                        <img src="{{ $imagePath }}" class="rounded-circle me-2" width="40" height="40" alt="Sender" style="object-fit: cover;">
                    @endif

                    <div class="message-container">
                        <div class="p-3 rounded {{ $isSender ? 'bg-primary text-white' : 'bg-light text-dark border' }} shadow-sm">
                            @if ($msg->content)
                                <p class="mb-0">{{ $msg->content }}</p>
                            @endif
                            @if ($attachments)
                                @php
                                    $extension = pathinfo(parse_url($attachments, PHP_URL_PATH), PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                    $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'webm']);
                                    $isAudio = in_array(strtolower($extension), ['mp3', 'wav', 'ogg']);
                                    $isDocument = in_array(strtolower($extension), ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt']);
                                @endphp
                                <div class="mt-2">
                                    @if ($isImage)
                                        <a href="{{ $attachments }}" target="_blank">
                                            <img src="{{ $attachments }}" class="img-fluid rounded attachments-img" alt="attachments" style="max-width: 200px;">
                                        </a>
                                    @elseif ($isVideo)
                                        <video controls class="rounded attachments-video" style="max-width: 200px;">
                                            <source src="{{ $attachments }}" type="video/{{ $extension }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif ($isAudio)
                                        <audio controls class="w-100">
                                            <source src="{{ $attachments }}" type="audio/{{ $extension }}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @elseif ($isDocument)
                                        <a href="{{ $attachments }}" target="_blank" class="d-block text-decoration-none {{ $isSender ? 'text-white' : 'text-primary' }}">
                                            <i class="fas fa-file-alt me-1"></i> {{ basename($attachments) }}
                                        </a>
                                    @else
                                        <a href="{{ $attachments }}" target="_blank" class="d-block text-decoration-none {{ $isSender ? 'text-white' : 'text-primary' }}">
                                            <i class="fas fa-file me-1"></i> {{ basename($attachments) }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="text-muted small mt-1 {{ $isSender ? 'text-end' : 'text-start' }}">
                            {{ $msg->created_at->format('h:i A, M d') }}
                        </div>
                    </div>

                    @if ($isSender)
                        <img src="{{ $imagePath }}" class="rounded-circle ms-2" width="40" height="40" alt="Sender" style="object-fit: cover;">
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Message Input Form -->
    <form action="{{ route('team_lead.message.send') }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $employee->id }}">
        <div class="input-group align-items-center">
            <input type="text" name="content" class="form-control border-0 shadow-sm" placeholder="Type your message..." style="border-radius: 20px 0 0 0; padding: 12px;">
            <label class="btn btn-outline-secondary border-0 shadow-sm" style="padding: 12px; border-radius: 0;">
                <i class="fas fa-paperclip"></i>
                <input type="file" name="attachments" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm,.mp3,.wav,.ogg,.pdf,.doc,.docx,.xls,.xlsx,.txt" style="display: none;">
            </label>
            <button class="btn btn-primary" type="submit" style="border-radius: 0 20px 20px 0; padding: 12px 20px;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>

@section('styles')
<style>
    /* Professional Chat Styling */
    .card {
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .message-container {
        max-width: 70%;
    }

    .bg-primary {
        background-color: #007bff !important;
        border-radius: 15px 15px 0 15px;
    }

    .bg-light {
        background-color: #e9ecef !important;
        border-radius: 15px 15px 15px 0;
        border: 1px solid #dee2e6;
    }

    .card-body {
        scrollbar-width: thin;
        scrollbar-color: #adb5bd #f8f9fa;
    }

    .card-body::-webkit-scrollbar {
        width: 8px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f8f9fa;
    }

    .card-body::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 10px;
    }

    .input-group .form-control {
        background-color: #fff;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .input-group .form-control:focus {
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
        border-color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #ced4da;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #007bff;
    }

    .badge {
        padding: 8px 12px;
        font-size: 0.9em;
        border-radius: 20px;
    }

    .attachments-img, .attachments-video {
        max-height: 150px;
        object-fit: cover;
    }

    audio {
        max-width: 200px;
    }
</style>
@endsection
@endsection