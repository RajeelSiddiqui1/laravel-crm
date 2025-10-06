<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Task Assigned</title>
    <style>
        body {
            background-color: #111827;
            color: #e5e7eb;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #1f2937;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.05);
        }

        h1 {
            color: #60a5fa;
            font-size: 22px;
        }

        .details {
            margin-top: 20px;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            margin-top: 25px;
            font-weight: 600;
        }

        .footer {
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h1>📋 New Task Assigned</h1>
        <p>Hello Manager,</p>
        <p>A new task has been assigned to you.</p>

        <div class="details">
            <strong>Client:</strong> {{ $task->client_name }}<br>
            {{-- <<strong>Created At:</strong>
                {{ $task->created_at ? $task->created_at->format('d M, Y h:i A') : 'N/A' }}<br> --}}

                @if ($task->audio_url)
                    <strong>Audio Note:</strong>
                    <a href="{{ $task->audio_url }}" style="color:#93c5fd;">Listen here</a><br>
                @endif
        </div>

        <a href="{{ url('/manager/tasks/' . $task->id) }}" class="button">View Task</a>

        <div class="footer">
            © {{ date('Y') }} MH Enterprices CRM — Task Management System
        </div>
    </div>
</body>

</html>
