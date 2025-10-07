<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Shared Task Assigned</title>
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
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 20px;
            background-color: #238636;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #2ea043;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📢 Shared Task Assigned to You</h2>
        <p>Dear Team Lead,</p>
        <p>You’ve been assigned a <strong>shared task</strong> by 
           <span class="highlight">{{ $assigner_name }}</span> 
           under manager <span class="highlight">{{ $manager_name }}</span>.</p>

        <div class="details">
            <p><strong>Subtask:</strong> {{ $subtask_name }}</p>
            <p><strong>Assigned At:</strong> {{ $assigned_at }}</p>
        </div>

        <p style="margin-top:20px;">Please review the task and begin coordination with your team.</p>

        <div style="text-align:center;">
            <a href="{{ url('/teamlead/shared-tasks') }}" class="btn">View Task</a>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Project Management Portal | All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
