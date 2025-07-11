@extends('layout.app')

@section('content')
<div class="container mt-4">
  <h4 class="text-white mb-4">🔔 All Notifications</h4>

    @forelse ($notifications as $notification)
        <div class="alert alert-secondary d-flex justify-content-between">
            <div>
                <strong>{{ $notification->data['title'] }}</strong><br>
                {{ $notification->data['message'] }}
            </div>
            <a href="{{ route('project_manager.notifications.view', $notification->id) }}" class="btn btn-sm btn-primary">View</a>
        </div>
    @empty
        <p class="text-muted">No notifications available.</p>
    @endforelse
</div>
@endsection
