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
        <div class="subtitle">Access GIAT CORE with your company account</div>
    </div>

    <!-- Login Form -->
    <div class="login-container">
        <form class="login-form" action="#" method="POST">
            <div class="form-group">
                <input type="text" class="form-input" placeholder="Username or Email" name="username" required>
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

    <!-- Error Message (example) -->
    <!-- <div class="error-message">
        Invalid username or password
    </div> -->

    <!-- Footer -->
    <div class="footer">Powered by <strong>GIAT CORE</strong></div>
</body>

</html>