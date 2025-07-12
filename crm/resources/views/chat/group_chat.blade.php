@extends('layout.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold text-dark">Group Chat - Task: {{ $task->name }}</h4>
            <span class="badge bg-success text-white">Active</span>
        </div>

        <div class="card shadow-sm border-0" style="height: 500px; overflow-y: auto; background-color: #f8f9fa;"
            id="chat-box">
            <div class="card-body p-4">
                @foreach ($messages as $msg)
                    @php
                        $isSender = $msg->sender_id == Auth::guard($msg->sender_type)->id();
                        $attachments = $msg->attachments ?? null;
                    @endphp

                    <div class="d-flex mb-3 {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="message-container">
                            <div
                                class="p-3 rounded {{ $isSender ? 'bg-primary text-white' : 'bg-light text-dark border' }} shadow-sm">
                                @unless ($isSender)
                                    <div class="fw-semibold mb-1 text-primary">
                                        @if ($msg->sender_type === 'employee')
                                            {{ $msg->employee->name ?? 'Employee' }}
                                        @elseif ($msg->sender_type === 'team_lead')
                                            {{ $msg->teamLead->name ?? 'Team Lead' }}
                                        @elseif ($msg->sender_type === 'project_manager')
                                            {{ $msg->projectManager->name ?? 'Project Manager' }}
                                        @elseif ($msg->sender_type === 'project_owner')
                                            {{ $msg->projectOwner->name ?? 'Project Owner (Admin)' }}
                                        @else
                                            Unknown
                                        @endif


                                    </div>
                                @endunless

                                @if ($msg->content)
                                    <p class="mb-0">{{ $msg->content }}</p>
                                @endif

                                @if ($attachments)
                                    @php
                                        $ext = strtolower(
                                            pathinfo(parse_url($attachments, PHP_URL_PATH), PATHINFO_EXTENSION),
                                        );
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                        $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
                                        $isAudio = in_array($ext, ['mp3', 'wav', 'ogg']);
                                        $isDoc = in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt']);
                                    @endphp

                                    <div class="mt-2">
                                        @if ($isImage)
                                            <a href="{{ $attachments }}" target="_blank">
                                                <img src="{{ $attachments }}" class="img-fluid rounded attachments-img"
                                                    alt="Image">
                                            </a>
                                        @elseif ($isVideo)
                                            <video controls class="rounded attachments-video">
                                                <source src="{{ $attachments }}" type="video/{{ $ext }}">
                                            </video>
                                        @elseif ($isAudio)
                                            <audio controls>
                                                <source src="{{ $attachments }}" type="audio/{{ $ext }}">
                                            </audio>
                                        @elseif ($isDoc)
                                            <a href="{{ $attachments }}" target="_blank"
                                                class="text-decoration-none {{ $isSender ? 'text-white' : 'text-primary' }}">
                                                <i class="fas fa-file-alt me-1"></i> {{ basename($attachments) }}
                                            </a>
                                        @else
                                            <a href="{{ $attachments }}" target="_blank"
                                                class="text-decoration-none {{ $isSender ? 'text-white' : 'text-primary' }}">
                                                <i class="fas fa-paperclip me-1"></i> Attachment
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="text-muted small mt-1 {{ $isSender ? 'text-end' : 'text-start' }}">
                                {{ $msg->created_at->format('h:i A, M d') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('chat.group.send') }}" enctype="multipart/form-data" class="mt-3">
            @csrf
            <input type="hidden" name="owner_task_id" value="{{ $task->id }}">
            <div class="input-group align-items-center">
                <input type="text" name="content" class="form-control border-0 shadow-sm"
                    placeholder="Type your message..." style="padding: 12px; border-radius: 20px 0 0 0;">
                <label class="btn btn-outline-secondary border-0 shadow-sm" style="padding: 12px; border-radius: 0;">
                    <i class="fas fa-paperclip"></i>
                    <input type="file" name="attachments"
                        accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm,.mp3,.wav,.ogg,.pdf,.doc,.docx,.xls,.xlsx,.txt"
                        style="display: none;">
                </label>
                <button class="btn btn-primary" type="submit" style="border-radius: 0 20px 20px 0; padding: 12px 20px;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 15px;
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

        .attachments-img,
        .attachments-video {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
        }

        audio {
            max-width: 200px;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f1f1;
            color: #007bff;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
            border-color: #007bff;
        }
    </style>
@endsection
