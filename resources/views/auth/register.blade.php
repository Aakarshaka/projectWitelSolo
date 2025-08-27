<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="png" href="{{ asset('images/favgiatlogo.png') }}">
    <title>Register | GIAT CORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/registerstyle.css') }}" rel="stylesheet">
</head>

<body>
    <div class="main-container">
        <!-- Hero Card - LEFT SIDE -->
        <div class="hero-card">
            <div class="hero-content">
                <div class="slides-container">
                    <div class="slide">
                        <h1 class="hero-title">Join Our<br>Community</h1>
                        <p class="hero-subtitle">Create your GIAT CORE account and start your collaboration journey with powerful tools designed for seamless teamwork.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Section - RIGHT SIDE -->
        <div class="regis-section">
            <!-- Top Navigation -->
            <div class="regis-top-nav">
                <div class="regis-logo-container">
                    <img src="{{ asset('images/giatlogo.png') }}" alt="GIAT" class="regis-logo-img">
                    <div class="regis-logo-text-container">
                        <span class="regis-logo-text">CORE</span>
                        <div class="regis-logo-subtitle">(Collaboration Needed Request)</div>
                    </div>
                </div>
                <a href="#" class="regis-back-link">
                    Back to website →
                </a>
            </div>

            <!-- Register Content -->
            <div class="regis-content">
                <div class="regis-header">
                    <h2 class="regis-title">Create your account</h2>
                    <p class="regis-subtitle">Already have an account? <a href="{{ url('/login') }}">Sign in here</a></p>
                </div>

                <form class="regis-form-container" id="registrationForm">
                    <div class="regis-form-group">
                        <div class="regis-username-unit-group">
                            <div class="regis-username-input-container">
                                <input type="text" class="regis-form-input" placeholder="Username" name="username" id="username" required>
                                <div class="regis-error-message" id="usernameError"></div>
                                <div class="regis-success-message" id="usernameSuccess"></div>
                            </div>
                            <div class="regis-unit-select-container">
                                <select class="regis-form-select" name="role" id="role" required>
                                    <option disabled selected value="">Select Unit</option>
                                </select>
                                <div class="regis-error-message" id="roleError"></div>
                                <div class="regis-success-message" id="roleSuccess"></div>
                            </div>
                        </div>
                    </div>

                    <div class="regis-form-group">
                        <div class="regis-email-verification-group">
                            <div class="regis-email-input-container">
                                <input type="email" class="regis-form-input" placeholder="Email Address" name="email" id="email" required>
                                <div class="regis-error-message" id="emailError"></div>
                                <div class="regis-success-message" id="emailSuccess"></div>
                            </div>
                            <button type="button" class="regis-email-verify-btn" id="emailVerifyBtn">Verify</button>
                        </div>
                    </div>

                    <div class="regis-form-group">
                        <div class="regis-password-input-container">
                            <input type="password" class="regis-form-input" placeholder="Password" name="password" id="password" required>
                            <button type="button" class="regis-password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="regis-error-message" id="passwordError"></div>
                        <div class="regis-success-message" id="passwordSuccess"></div>
                    </div>

                    <div class="regis-form-group">
                        <div class="regis-password-input-container">
                            <input type="password" class="regis-form-input" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" required>
                            <button type="button" class="regis-password-toggle" id="confirmPasswordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="regis-error-message" id="confirmPasswordError"></div>
                        <div class="regis-success-message" id="confirmPasswordSuccess"></div>
                    </div>

                    <button type="submit" class="regis-button" id="registerBtn">
                        Create Account
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="regis-footer">
                Powered by <strong>GIAT CORE</strong>
            </div>
        </div>
    </div>

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