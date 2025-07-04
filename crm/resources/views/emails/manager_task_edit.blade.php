<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Updated</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hello {{ $task->manager_name ?? 'Manager' }},</h2>
    <p>The task <strong>{{ $task->name }}</strong> has been updated.</p>
    <p><strong>Client:</strong> {{ $task->client_name }}</p>
    <p><strong>Start Date:</strong> {{ $task->start_date }}</p>
    <p><strong>Deadline:</strong> {{ $task->deadline }}</p>
    <p><strong>Priority:</strong> {{ $task->priority }}</p>
    <br>
    <p>Regards,</p>
    <p><strong>CRM Owner</strong></p>
</body>
</html>
