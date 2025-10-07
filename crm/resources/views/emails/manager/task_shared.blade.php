<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Shared Task</title>
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
            color: #58a6ff;
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
        <h2>📢 New Shared Task Assigned</h2>
        <p>Dear Manager,</p>
        <p>You have been assigned a new <strong>shared subtask</strong> by 
           <span class="highlight">{{ $assigner_name }}</span>.</p>

        <div class="details">
            <p><strong>Subtask Title:</strong> {{ $subtask_name }}</p>
            <p><strong>Shared At:</strong> {{ $shared_at }}</p>
        </div>

        <p style="margin-top:20px;">
            Please review the task in your project dashboard and begin work at your earliest convenience.
        </p>

        <div class="footer">
            <p>© {{ date('Y') }} Project Management Portal | All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
