<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | GIAT CORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/loginstyle.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Logo Section -->
    <div class="login-logo-section">
        <div class="login-logo-container">
            <img src="{{ asset('images/giatlogo.png') }}" class="login-logo-img" alt="GIAT Logo">
            <div class="login-logo-text-container">
                <div class="login-logo-text">CORE</div>
                <div class="login-logo-subtitle">(Collaboration Needed' Request)</div>
            </div>
        </div>
        <div class="subtitle">Akses GIAT CORE dengan akun perusahaan Anda</div>
    </div>

    {{-- Error Message --}}
    @if ($errors->has('msg'))
        <div class="error-message">
            {{ $errors->first('msg') }}
        </div>
    @endif

    <!-- Login Options -->
    <div class="login-container">
        <div class="login-grid">
            <!-- Google Login -->
            <a href="{{ route('login.redirect', ['provider' => 'google']) }}" class="login-option">
                <div class="provider-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                </div>
                <div class="provider-name">Google</div>
                <div class="provider-desc">Login with Google</div>
            </a>

            <!-- Microsoft Login -->
            <a href="{{ route('login.redirect', ['provider' => 'microsoft']) }}" class="login-option">
                <div class="provider-icon">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#f25022" d="M1 1h10v10H1z" />
                        <path fill="#00a4ef" d="M13 1h10v10H13z" />
                        <path fill="#7fba00" d="M1 13h10v10H1z" />
                        <path fill="#ffb900" d="M13 13h10v10H13z" />
                    </svg>
                </div>
                <div class="provider-name">Microsoft</div>
                <div class="provider-desc">Login with Microsoft</div>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">Powered by <strong>GIAT CORE</strong></div>
</body>

</html>