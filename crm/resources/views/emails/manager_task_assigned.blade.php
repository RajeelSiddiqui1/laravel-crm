<!DOCTYPE html>
<html>
<head>
    <title>Task Assigned</title>
</head>
<body>
    <h2>Hello {{ $task->projectManager->name ?? 'Manager' }},</h2>
    <p>A new task has been assigned to you.</p>
    <p><strong>Client:</strong> {{ $task->client_name }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Start Date:</strong> {{ $task->start_date }}</p>
    <p><strong>Deadline:</strong> {{ $task->deadline }}</p>
    <p>Please log in to your dashboard to view more details.</p>
</body>
</html>
