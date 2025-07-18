<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forget Password | GIAT CORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/fpstyle.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Logo Section -->
    <div class="forgot-logo-section">
        <div class="forgot-subtitle">Reset your password</div>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step active" id="step1"></div>
        <div class="step" id="step2"></div>
        <div class="step" id="step3"></div>
    </div>

    <!-- Forgot Password Form -->
    <div class="forgot-container">
        <!-- Step 1: Email Verification -->
        <div class="form-step active" id="emailStep">
            <form class="forgot-form" id="emailForm">
                <div class="forgot-form-group">
                    <div class="email-verification-group">
                        <div class="email-input-container">
                            <input type="email" class="forgot-form-input" placeholder="Enter your email address"
                                name="email" id="email" required>
                            <div class="forgot-error-message" id="emailError"></div>
                            <div class="forgot-success-message" id="emailSuccess"></div>
                        </div>
                        <button type="button" class="email-verify-btn" id="emailVerifyBtn">Send OTP</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Step 2: OTP Verification (handled by modal) -->

        <!-- Step 3: New Password -->
        <div class="form-step" id="passwordStep">
            <form class="forgot-form" id="passwordForm">
                <div class="forgot-form-group">
                    <div class="password-input-container">
                        <input type="password" class="forgot-form-input" placeholder="New Password"
                            name="newPassword" id="newPassword" required>
                        <button type="button" class="password-toggle" id="newPasswordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="forgot-error-message" id="newPasswordError"></div>
                    <div class="forgot-success-message" id="newPasswordSuccess"></div>
                </div>

                <div class="forgot-form-group">
                    <div class="password-input-container">
                        <input type="password" class="forgot-form-input" placeholder="Confirm New Password"
                            name="confirmNewPassword" id="confirmNewPassword" required>
                        <button type="button" class="password-toggle" id="confirmNewPasswordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="forgot-error-message" id="confirmNewPasswordError"></div>
                    <div class="forgot-success-message" id="confirmNewPasswordSuccess"></div>
                </div>

                <div class="forgot-form-group">
                    <button type="submit" class="forgot-button" id="resetPasswordBtn">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Link Section -->
        <div class="forgot-login-section">
            <span class="forgot-login-text">Remember your password?</span>
            <a href="/login" class="forgot-login-link">Login here</a>
        </div>
    </div>

    <div class="forgot-footer">Powered by <strong>GIAT CORE</strong></div>

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