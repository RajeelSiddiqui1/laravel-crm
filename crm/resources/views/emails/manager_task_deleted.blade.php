<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Deleted</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hello {{ $task->manager_name ?? 'Manager' }},</h2>
    <p>This is to inform you that your assigned task "<strong>{{ $task->name }}</strong>" has been <span style="color: red;">deleted</span>.</p>

    <p><strong>Client:</strong> {{ $task->client_name }}</p>
    <p><strong>Originally Scheduled:</strong> {{ $task->start_date }} to {{ $task->deadline }}</p>
    
    <br>
    <p>If you have any questions, feel free to contact your supervisor.</p>
    <p>Regards,</p>
    <p><strong>AAR Accessories Task Management Team</strong></p>
</body>
</html>
