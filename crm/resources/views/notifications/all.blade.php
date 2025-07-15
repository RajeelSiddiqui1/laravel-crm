@extends('layout.app')

@section('content')
<div class="container mt-5 text-white">
    <h2 class="mb-3">Your Notifications</h2>
    @if($notifications->count())
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item bg-dark text-white mb-2">
                    <strong>{{ $notification->title }}</strong><br>
                    {{ $notification->message }}<br>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-warning">No notifications found.</div>
    @endif
</div>
@endsection
