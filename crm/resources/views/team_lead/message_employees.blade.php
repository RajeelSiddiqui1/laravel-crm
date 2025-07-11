@extends('layout.app')

@section('content')
<div class="container py-4">
    <h4 class="text-white mb-4">Chat with {{ $employee->name }}</h4>

    <div class="card p-3 mb-3" style="height: 400px; overflow-y: auto; background: #1e1e2f;">
        @foreach ($messages as $msg)
            @php
                $isSender = $msg->sender_id == Auth::guard('team_lead')->id();
                $imagePath = $isSender
                    ? asset('images/team_leads/' . Auth::guard('team_lead')->user()->image)
                    : asset('images/employees/' . $employee->image);
            @endphp

            <div class="d-flex mb-3 {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
                @if (!$isSender)
                    <img src="{{ $imagePath }}" class="rounded-circle me-2" width="40" height="40" alt="Sender">
                @endif

                <div>
                    <div class="p-2 {{ $isSender ? 'bg-primary' : 'bg-secondary' }} text-white rounded">
                        {{ $msg->content }}
                    </div>
                    <div class="text-muted small mt-1 text-end">
                        {{ $msg->created_at->format('h:i A') }}
                    </div>
                </div>

                @if ($isSender)
                    <img src="{{ $imagePath }}" class="rounded-circle ms-2" width="40" height="40" alt="Sender">
                @endif
            </div>
        @endforeach
    </div>

    <form action="{{ route('team_lead.message.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $employee->id }}">
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Type your message..." required>
            <button class="btn btn-success" type="submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>
@endsection
