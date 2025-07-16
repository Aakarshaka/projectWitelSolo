<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | GIAT CORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/loginstyle.css') }}" rel="stylesheet">
</head>

<body>

    <div class="logo">GIAT<span style="color:#FDD835;">CORE</span></div>
    <div class="subtitle">Akses GIAT CORE dengan akun perusahaan Anda</div>

    <a href="{{ url('newdashboard') }}" class="btn-login">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon">
        Login with Google
    </a>

    <div class="footer">Powered by <strong>GIAT CORE</strong></div>

</body>

</html>