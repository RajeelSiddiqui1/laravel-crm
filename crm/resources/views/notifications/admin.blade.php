@extends('layout.app')

@section('content')
<div class="container mt-5 text-white">
    <h2>All Notifications (Admin View)</h2>
    @if($notifications->count())
        <ul class="list-group bg-dark">
            @foreach($notifications as $n)
                <li class="list-group-item bg-secondary text-white">
                    <strong>{{ $n->title }}</strong> 
                    <br>
                    <span>{{ $n->message }}</span>
                    <br>
                    <small>To: {{ $n->user_type }} #{{ $n->user_id }} | {{ $n->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-warning">No notifications available.</div>
    @endif
</div>
@endsection
