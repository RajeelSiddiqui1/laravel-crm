<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Email</title>
</head>
<body>
    <h2>Welcome, {{ $user->name }}!</h2>
    <p>Thank you for registering as a Project Manager.</p>
    <p>You can login here:</p>
    <a href="{{ url('project_manager.login') }}">Click here to Login</a>
</body>
</html>
