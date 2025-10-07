<!DOCTYPE html>
<html>
<head>
    <title>Task Updated Notification</title>
</head>
<body>
    <h2>Task Updated!</h2>
    <p><strong>Client Name:</strong> {{ $clientName }}</p>
    <p><strong>Status:</strong> {{ ucfirst($status) }}</p>
    <p><strong>Department:</strong> {{ $department }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($priority) }}</p>

    <p>The task details have been updated by your project manager.</p>
    <p>Thank you for your continued efforts!</p>
</body>
</html>
