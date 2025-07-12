@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">All Notifications</h4>

    @forelse ($notifications as $notification)
        <div class="alert alert-secondary d-flex justify-content-between">
            <div>
                <strong>{{ $notification->data['title'] ?? 'Message' }}</strong><br>
                {{ $notification->data['message'] ?? 'You have a new message.' }}
                <br>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            @if (!$notification->read_at)
                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-outline-primary">Mark as read</a>
            @endif
        </div>
    @empty
        <div class="alert alert-info">You have no notifications.</div>
    @endforelse
</div>
@endsection
