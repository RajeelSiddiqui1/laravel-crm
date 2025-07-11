@extends('layout.app')

@section('content')
<div class="container py-4">
    <h4 class="text-dark mb-3">Group Chat - Task: {{ $task->title }}</h4>

    <div class="border p-3 mb-3" style="height: 400px; overflow-y: auto;">
        @foreach ($messages as $msg)
            <div class="mb-2 {{ $msg->sender_id == Auth::id() ? 'text-end' : 'text-start' }}">
                <div class="p-2 {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }} rounded">
                    {{ $msg->content }}
                    @if ($msg->attachments)
                        <div class="mt-2">
                            <a href="{{ $msg->attachments }}" target="_blank">View Attachment</a>
                        </div>
                    @endif
                </div>
                <small class="text-muted">{{ $msg->created_at->format('h:i A') }}</small>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('chat.group.send') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="owner_task_id" value="{{ $task->id }}">
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Type message...">
            <input type="file" name="attachments" class="form-control">
            <button class="btn btn-success">Send</button>
        </div>
    </form>
</div>
@endsection
