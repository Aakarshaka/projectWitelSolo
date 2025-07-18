<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GIAT CORE</title>
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
        <div class="subtitle">Access GIAT CORE with your company account</div>
    </div>

    @if(session('message'))
        <div class="alert alert-success text-center" style="margin: 20px auto; max-width: 400px;">
            {{ session('message') }}
        </div>
    @endif

    <!-- Login Form -->
    <div class="login-container">
        <form class="login-form" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-input" placeholder="Username or Email" name="username" value="{{ old('username') }}" required>
            </div>
            
            <div class="form-group">
                <input type="password" class="form-input" placeholder="Password" name="password" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="login-button">
                    Login
                </button>
            </div>
        </form>

        <!-- Register Section -->
        <div class="register-section">
            <span class="register-text">Don't have an account?</span>
            <a href="#" class="register-link">Sign up here</a>
        </div>
    </div>

    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">Powered by <strong>GIAT CORE</strong></div>
</body>

</html>