```php
@php use Illuminate\Support\Str; @endphp

@extends('layout.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --body-bg: #121217;
            --primary: #4f46e5;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text: #d1d5db;
            --border: #2d3748;
            --table-bg: rgba(31, 41, 55, 0.6);
            --hover-bg: rgba(75, 85, 99, 0.2);
        }

        body {
            background: var(--body-bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin-top: 2rem;
        }

        .chat-container {
            background: var(--table-bg);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            height: 500px;
            overflow-y: auto;
            padding: 1.5rem;
            scrollbar-width: thin;
            scrollbar-color: var(--border) var(--table-bg);
        }

        .chat-container::-webkit-scrollbar {
            width: 8px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: var(--table-bg);
        }

        .chat-container::-webkit-scrollbar-thumb {
            background-color: var(--border);
            border-radius: 10px;
        }

        .message-container {
            max-width: 70%;
            margin-bottom: 1.5rem;
        }

        .message-bubble {
            padding: 1rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .message-bubble.sender {
            background: var(--primary);
            color: #fff;
            border-radius: 1rem 1rem 0 1rem;
        }

        .message-bubble.receiver {
            background: rgba(75, 85, 99, 0.6);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 1rem 1rem 1rem 0;
        }

        .message-bubble p {
            word-break: break-word;
            margin-bottom: 0;
        }

        .sender-name {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .timestamp {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 0.5rem;
        }

        .attachments-img,
        .attachments-video,
        audio {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .attachment-link {
            color: inherit;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .attachment-link i {
            margin-right: 0.5rem;
        }

        .input-group {
            background: var(--table-bg);
            border: 1px solid var(--border);
            border-radius: 1rem;
            overflow: hidden;
        }

        .form-control {
            background: transparent;
            color: var(--text);
            border: none;
            padding: 0.75rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary {
            background: transparent;
            color: var(--text);
            border: none;
        }

        .btn-outline-secondary:hover {
            background: var(--hover-bg);
            color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .badge-success {
            background: var(--success);
            color: #fff;
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
        }

        @media (max-width: 576px) {
            .message-container {
                max-width: 90%;
            }

            .attachments-img,
            .attachments-video,
            audio {
                max-width: 100%;
                max-height: 100px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Success Notification -->
        @if (session('success_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success_swal') }}",
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#22c55e'
                    });
                });
            </script>
        @endif

        <!-- Error Notifications -->
        @if (session('error_swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error_swal') }}",
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                });
            </script>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4>Group Chat - {{ $accountType }} (ID: {{ $account->id }})</h4>


            <span class="badge badge-success">Active</span>
        </div>

        <div class="chat-container" id="chat-box">
            @foreach ($messages as $msg)
                @php
                    $isSender = $msg->sender_id == Auth::guard($msg->sender_type)->id();
                    $attachments = $msg->attachments ?? null;
                @endphp

                <div class="d-flex mb-3 {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="message-container">
                        <div class="message-bubble {{ $isSender ? 'sender' : 'receiver' }}">
                            @unless ($isSender)
                                <div class="sender-name">
                                    @if ($msg->sender_type === 'employee' && $msg->employee)
                                        {{ $msg->employee->name ?? 'Employee' }}
                                    @elseif ($msg->sender_type === 'team_lead' && $msg->teamLead)
                                        {{ $msg->teamLead->name ?? 'Team Lead' }}
                                    @elseif ($msg->sender_type === 'project_manager' && $msg->projectManager)
                                        {{ $msg->projectManager->name ?? 'Project Manager' }}
                                    @elseif ($msg->sender_type === 'project_owner' && $msg->projectOwner)
                                        {{ $msg->projectOwner->name ?? 'Project Owner (Admin)' }}
                                    @else
                                        Unknown
                                    @endif
                                </div>
                            @endunless

                            @if ($msg->content)
                                <p>{{ $msg->content }}</p>
                            @endif

                            @if ($attachments && filter_var($attachments, FILTER_VALIDATE_URL))
                                @php
                                    $ext = strtolower(
                                        pathinfo(parse_url($attachments, PHP_URL_PATH), PATHINFO_EXTENSION),
                                    );
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
                                    $isAudio = in_array($ext, ['mp3', 'wav', 'ogg']);
                                    $isDoc = in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt']);
                                @endphp

                                <div class="mt-2">
                                    @if ($isImage)
                                        <a href="{{ $attachments }}" target="_blank">
                                            <img src="{{ $attachments }}" class="attachments-img" alt="Image">
                                        </a>
                                    @elseif ($isVideo)
                                        <video controls class="attachments-video">
                                            <source src="{{ $attachments }}" type="video/{{ $ext }}">
                                        </video>
                                    @elseif ($isAudio)
                                        <audio controls>
                                            <source src="{{ $attachments }}" type="audio/{{ $ext }}">
                                        </audio>
                                    @elseif ($isDoc)
                                        <a href="{{ $attachments }}" target="_blank" class="attachment-link">
                                            <i class="bi bi-file-earmark-text"></i>
                                            {{ Str::limit(basename($attachments), 20) }}
                                        </a>
                                    @else
                                        <a href="{{ $attachments }}" target="_blank" class="attachment-link">
                                            <i class="bi bi-paperclip"></i> Attachment
                                        </a>
                                    @endif
                                </div>
                            @elseif ($attachments)
                                <span class="text-danger">Invalid attachment</span>
                            @endif
                        </div>
                        <div class="timestamp {{ $isSender ? 'text-end' : 'text-start' }}">
                            {{ $msg->created_at->format('h:i A, M d') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('chat.group.send') }}" enctype="multipart/form-data" class="mt-3">
            @csrf
            <input type="hidden" name="account_type" value="{{ $accountType }}">
            <input type="hidden" name="account_id" value="{{ $account->id }}">

            <div class="input-group align-items-center">
                <input type="text" name="content" class="form-control" placeholder="Type your message..."
                    aria-label="Message content">
                <label class="btn btn-outline-secondary">
                    <i class="bi bi-paperclip"></i>
                    <input type="file" name="attachments"
                        accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm,.mp3,.wav,.ogg,.pdf,.doc,.docx,.xls,.xlsx,.txt"
                        style="display: none;" aria-label="Upload attachment">
                </label>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
@endsection
```
