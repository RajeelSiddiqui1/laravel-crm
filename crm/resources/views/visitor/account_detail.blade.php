@extends('layout.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Account Details</h3>

    <table class="table table-bordered table-dark">
        <tr><th>Email</th><td>{{ $account->email ?? 'N/A' }}</td></tr>
        <tr><th>Phone</th><td>{{ $account->phone ?? 'N/A' }}</td></tr>
        <tr><th>Business Number</th><td>{{ $account->bussiness_number ?? 'N/A' }}</td></tr>
        <tr><th>Corporation Number</th><td>{{ $account->corpuration_number ?? 'N/A' }}</td></tr>
        <tr><th>Corporation Email</th><td>{{ $account->corpuration_email ?? 'N/A' }}</td></tr>
        <tr><th>Previous History</th><td>{{ $account->pervious_history ?? 'N/A' }}</td></tr>
        <tr><th>Fees</th><td>{{ $account->fees ?? 'N/A' }}</td></tr>
        <tr><th>Status</th><td>{{ $account->status ?? 'N/A' }}</td></tr>
        <tr><th>Comments</th><td>{{ $account->comments ?? 'N/A' }}</td></tr>

        {{-- Attachments --}}
       <tr>
            <th>Attachments</th>
            <td>
                @php
                    $attachments = is_array($account->attachments) ? $account->attachments : ($account->attachments ? [$account->attachments] : []);
                @endphp

                @if(!empty($attachments))
                    <div class="row g-2">
                        @foreach($attachments as $file)
                            @php
                                $ext = strtolower(pathinfo(parse_url($file, PHP_URL_PATH), PATHINFO_EXTENSION));
                            @endphp
                            <div class="col-12 col-md-4 mb-2">
                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ $file }}" class="img-fluid rounded" style="max-height: 200px;">
                                @elseif(in_array($ext, ['mp4', 'mov', 'avi', 'webm']))
                                    <video controls class="w-100 rounded" style="max-height: 200px;">
                                        <source src="{{ $file }}" type="video/{{ $ext }}">
                                    </video>
                                @elseif(in_array($ext, ['mp3', 'wav', 'ogg']))
                                    <audio controls class="w-100">
                                        <source src="{{ $file }}" type="audio/{{ $ext }}">
                                    </audio>
                                @else
                                    <a href="{{ $file }}" target="_blank" download class="btn btn-outline-light btn-sm d-block mb-1">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No attachments</p>
                @endif
            </td>
        </tr>
    </table>

    <a href="{{ route('visitor.sharedtask.view') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
