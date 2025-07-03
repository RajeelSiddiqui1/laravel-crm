<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Email</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Click the button below to login to your account:</p>
    <a href="{{ $loginlink }}" style="display:inline-block;padding:10px 20px;background:#28a745;color:#fff;text-decoration:none;border-radius:4px;">
        Login Now
    </a>
    <p>This link is valid for 30 minutes and will expire after it's used.</p>
</body>
</html>
