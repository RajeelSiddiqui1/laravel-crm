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
            max-width: 1400px;
        }

        .table {
            background: var(--table-bg);
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 1rem;
            border: 1px solid var(--border);
            transition: background 0.2s ease;
        }

        .table th {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table tbody tr:hover {
            background: var(--hover-bg);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        .badge-danger {
            background: var(--danger);
        }

        .badge-warning {
            background: var(--warning);
        }

        .badge-success {
            background: var(--success);
        }

        .attachment-item img,
        .attachment-item video,
        .attachment-item audio {
            max-height: 80px;
            width: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            transition: transform 0.3s ease;
        }

        .attachment-item img:hover,
        .attachment-item video:hover {
            transform: scale(1.05);
        }

        .attachment-item a {
            text-decoration: none;
        }

        .attachment-item img.icon {
            height: 40px;
        }

        .attachment-item div {
            font-size: 0.75rem;
            color: var(--text);
            margin-top: 0.25rem;
        }

        h2.text-center {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Account Details</h2>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Email</th>
                        <td>{{ $account->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $account->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Business Number</th>
                        <td>{{ $account->bussiness_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Number</th>
                        <td>{{ $account->corpuration_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Corporation Email</th>
                        <td>{{ $account->corpuration_email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Previous History</th>
                        <td>{{ $account->pervious_history ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Fees</th>
                        <td>{{ $account->fees ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $account->status == 'active' ? 'success' : ($account->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($account->status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Comments</th>
                        <td>{{ $account->comments ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Attachments</th>
                        <td>
                            @php
                                $attachments = $account->attachments;
                                if (is_string($attachments)) {
                                    $decoded = json_decode($attachments, true);
                                    $attachments = is_array($decoded) ? $decoded : [$attachments];
                                } elseif (!is_array($attachments)) {
                                    $attachments = $attachments ? [$attachments] : [];
                                }
                            @endphp

                            @if(!empty($attachments))
                                <div class="row g-3">
                                    @foreach($attachments as $file)
                                        @php
                                            $fileUrl = Str::startsWith($file, ['http://', 'https://'])
                                                ? $file
                                                : asset('storage/' . $file);
                                            $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                            $fileName = basename($file);
                                        @endphp
                                        <div class="col-12 col-md-4 text-center">
                                            <div class="attachment-item">
                                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                                    <a href="{{ $fileUrl }}" target="_blank">
                                                        <img src="{{ $fileUrl }}" alt="Image" class="img-fluid rounded">
                                                    </a>
                                                @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                                                    <video src="{{ $fileUrl }}" controls class="w-100 rounded"></video>
                                                @elseif(in_array($ext, ['mp3','wav','ogg']))
                                                    <audio controls class="w-100">
                                                        <source src="{{ $fileUrl }}" type="audio/{{ $ext }}">
                                                    </audio>
                                                @elseif(in_array($ext, ['pdf']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['xls','xlsx','csv']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-excel.png" alt="Excel" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @elseif(in_array($ext, ['doc','docx']))
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/color/48/000000/ms-word.png" alt="Word" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}" target="_blank" title="{{ $fileName }}">
                                                        <img src="https://img.icons8.com/fluency/48/000000/file.png" alt="File" class="icon">
                                                        <div>{{ \Illuminate\Support\Str::limit($fileName, 12) }}</div>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No Attachments</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('employee.sharedtask.view') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection