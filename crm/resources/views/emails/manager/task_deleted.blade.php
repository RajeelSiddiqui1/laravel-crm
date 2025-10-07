<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Task Deleted Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d1117;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #161b22;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #30363d;
        }
        h2 {
            color: #f85149;
            text-align: center;
        }
        .details {
            margin-top: 25px;
            background: #21262d;
            padding: 20px;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #8b949e;
            font-size: 14px;
        }
        .highlight {
            color: #58a6ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🗑️ Task Deleted Successfully</h2>
        <p>Dear <strong>{{ $manager_name }}</strong>,</p>
        <p>Your <strong>Account {{ $task_type }}</strong> task has been deleted successfully from the system.</p>

        <div class="details">
            <p><strong>Task Title:</strong> {{ $task_name }}</p>
            <p><strong>Deleted At:</strong> {{ $deleted_at }}</p>
        </div>

        <p style="margin-top:20px;">If this action was not intended, please contact the system administrator immediately.</p>

        <div class="footer">
            <p>© {{ date('Y') }} Project Management Portal | All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
