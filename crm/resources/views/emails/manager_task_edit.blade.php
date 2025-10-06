<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Updated</title>
    <style>
        body {
            background-color: #0f172a;
            color: #e5e7eb;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: linear-gradient(145deg, #1e293b, #111827);
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 24px;
            color: #38bdf8;
            margin: 0;
        }
        .highlight {
            background: #1d4ed8;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 600;
        }
        .details {
            background-color: #1e293b;
            border-left: 4px solid #38bdf8;
            padding: 15px 20px;
            border-radius: 8px;
            margin-top: 15px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background: #38bdf8;
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 25px;
            font-weight: 600;
        }
        .footer {
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            margin-top: 35px;
        }
        .divider {
            border-top: 1px solid #334155;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🛠️ Task Updated Notification</h1>
            <p><span class="highlight">Task #{{ $task->id }}</span> has been updated successfully.</p>
        </div>

        <p>Hello Manager,</p>
        <p>The task details have been updated. Please review the latest information below:</p>

        <div class="details">
            <strong>Client:</strong> {{ $task->client_name }}<br>
            <strong>Status:</strong> {{ ucfirst($task->status ?? 'Pending') }}<br>
            <strong>Updated At:</strong> 
            {{ $task->updated_at ? $task->updated_at->format('d M, Y h:i A') : 'N/A' }}<br>

            @if($task->audio_url)
                <strong>Audio Note:</strong> 
                <a href="{{ $task->audio_url }}" style="color:#93c5fd;">Listen Here</a><br>
            @endif

            {{-- <strong>Managers:</strong>
            <ul>
                @foreach(json_decode($task->managers, true) ?? [] as $manager)
                    <li>{{ $manager }}</li>
                @endforeach
            </ul> --}}
        </div>

        <div class="divider"></div>

        <a href="{{ url('/manager/tasks/'.$task->id) }}" class="button">View Updated Task</a>

        <div class="footer">
            © {{ date('Y') }} AAR Accessories — Task Management System<br>
            This is an automated notification. Please do not reply.
        </div>
    </div>
</body>
</html>
