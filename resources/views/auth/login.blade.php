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

    {{-- Error Message --}}
    @if ($errors->has('msg'))
        <div class="alert alert-danger text-center w-75 mx-auto mt-3">
            {{ $errors->first('msg') }}
        </div>
    @endif

    {{-- Google Login --}}
    <a href="{{ route('login.redirect', ['provider' => 'google']) }}" class="btn-login">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon">
        Login with Google
    </a>

    {{-- Microsoft Login --}}
    <a href="{{ route('login.redirect', ['provider' => 'microsoft']) }}" class="btn-login">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/microsoft.svg" alt="Microsoft Icon">
        Login with Microsoft
    </a>

    <div class="footer">Powered by <strong>GIAT CORE</strong></div>

</body>

</html>
