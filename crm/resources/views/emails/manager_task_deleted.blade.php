<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Task Deleted</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px;">
    <div style="background-color: #fff; border-radius: 8px; padding: 20px;">
        <h2 style="color: #e3342f;">🚨 Task Deleted</h2>
        <p>Hello Manager,</p>
        <p>The following task has been <strong>deleted</strong> by {{ $deletedBy }}:</p>
        <ul>
            <li><strong>Client Name:</strong> {{ $task->client_name }}</li>
            <li><strong>Task ID:</strong> #{{ $task->id }}</li>
            @if(!empty($task->audio_url))
                <li><strong>Previous Audio URL:</strong> <a href="{{ $task->audio_url }}">View Audio</a></li>
            @endif
        </ul>
        <p style="margin-top: 10px;">If this was a mistake, please contact the project owner.</p>
    </div>
</body>
</html>
