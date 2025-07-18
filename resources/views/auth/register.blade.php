<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | GIAT CORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/registerstyle.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Logo Section -->
    <div class="regis-logo-section">
        <div class="regis-subtitle">Create your account</div>
    </div>

    <!-- Registration Form -->
    <div class="regis-container">
        <form class="regis-form" id="registrationForm">
            <div class="regis-form-group">
                <input type="text" class="regis-form-input" placeholder="Username" name="username" id="username"
                    required>
                <div class="regis-error-message" id="usernameError"></div>
                <div class="regis-success-message" id="usernameSuccess"></div>
            </div>

            <div class="regis-form-group">
                <div class="email-verification-group">
                    <div class="email-input-container">
                        <input type="email" class="regis-form-input" placeholder="Email Address" name="email" id="email"
                            required>
                        <div class="regis-error-message" id="emailError"></div>
                        <div class="regis-success-message" id="emailSuccess"></div>
                    </div>
                    <button type="button" class="email-verify-btn" id="emailVerifyBtn">Verify</button>
                </div>
            </div>

            <div class="regis-form-group">
                <div class="password-input-container">
                    <input type="password" class="regis-form-input" placeholder="Password" name="password" id="password"
                        required>
                    <button type="button" class="password-toggle" id="passwordToggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="regis-error-message" id="passwordError"></div>
                <div class="regis-success-message" id="passwordSuccess"></div>
            </div>

            <div class="regis-form-group">
                <div class="password-input-container">
                    <input type="password" class="regis-form-input" placeholder="Confirm Password"
                        name="confirmPassword" id="confirmPassword" required>
                    <button type="button" class="password-toggle" id="confirmPasswordToggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="regis-error-message" id="confirmPasswordError"></div>
                <div class="regis-success-message" id="confirmPasswordSuccess"></div>
            </div>

            <div class="regis-form-group">
                <button type="submit" class="regis-button" id="registerBtn">
                    Create Account
                </button>
            </div>
        </form>

        <!-- Login Link Section -->
        <div class="regis-login-section">
            <span class="regis-login-text">Already have an account?</span>
            <a href="{{ url('/login') }}" class="regis-login-link">Login here</a>
        </div>
    </div>

    <div class="regis-footer">Powered by <strong>GIAT CORE</strong></div>

    <!-- OTP Modal -->
    <div class="otp-modal" id="otpModal">
        <div class="otp-modal-content">
            <div class="otp-modal-header">
                <h3 class="otp-modal-title">Verify Your Email</h3>
                <p class="otp-modal-subtitle">
                    We've sent a 6-digit verification code to<br>
                    <strong id="userEmail">user@example.com</strong>
                </p>
            </div>

            <div class="otp-input-container">
                <input type="text" class="otp-input" maxlength="1" id="otp1">
                <input type="text" class="otp-input" maxlength="1" id="otp2">
                <input type="text" class="otp-input" maxlength="1" id="otp3">
                <input type="text" class="otp-input" maxlength="1" id="otp4">
                <input type="text" class="otp-input" maxlength="1" id="otp5">
                <input type="text" class="otp-input" maxlength="1" id="otp6">
            </div>

            <div class="otp-buttons">
                <button type="button" class="otp-verify-btn" id="verifyBtn">Verify</button>
                <button type="button" class="otp-cancel-btn" id="cancelBtn">Cancel</button>
            </div>

            <div class="otp-resend">
                Didn't receive the code? <a href="#" class="otp-resend-link" id="resendLink">Resend</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/registerscript.js') }}"></script>
</body>

</html>