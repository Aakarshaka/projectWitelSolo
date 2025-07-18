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
                <input type="text" class="regis-form-input" placeholder="Full Name" name="name" id="name" required>
                <div class="regis-error-message" id="nameError"></div>
                <div class="regis-success-message" id="nameSuccess"></div>
            </div>

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
            <a href="#" class="regis-login-link">Login here</a>
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
                <button class="otp-verify-btn" id="verifyBtn">Verify</button>
                <button class="otp-cancel-btn" id="cancelBtn">Cancel</button>
            </div>

            <div class="otp-resend">
                Didn't receive the code? <a href="#" class="otp-resend-link" id="resendLink">Resend</a>
            </div>
        </div>
    </div>

</body>
<script>
    // Form validation
    const form = document.getElementById('registrationForm');
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const otpModal = document.getElementById('otpModal');
    const userEmailSpan = document.getElementById('userEmail');
    const emailVerifyBtn = document.getElementById('emailVerifyBtn');

    // OTP inputs - PERBAIKAN: Deklarasikan variabel otpInputs
    const otpInputs = document.querySelectorAll('input[id^="otp"]'); // Ambil semua input dengan id yang dimulai dengan "otp"

    // State variables
    let isEmailVerified = false;

    // Password toggle functionality
    function setupPasswordToggle(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);

        toggle.addEventListener('click', function () {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            const icon = toggle.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    setupPasswordToggle('password', 'passwordToggle');
    setupPasswordToggle('confirmPassword', 'confirmPasswordToggle');

    // Validation functions
    function validateName() {
        const name = nameInput.value.trim();
        const nameError = document.getElementById('nameError');
        const nameSuccess = document.getElementById('nameSuccess');

        if (name.length < 2) {
            showError(nameInput, nameError, 'Name must be at least 2 characters long');
            clearSuccess(nameSuccess);
            return false;
        }

        clearError(nameInput, nameError);
        showSuccess(nameInput, nameSuccess, 'Name looks good!');
        return true;
    }

    function validateUsername() {
        const username = usernameInput.value.trim();
        const usernameError = document.getElementById('usernameError');
        const usernameSuccess = document.getElementById('usernameSuccess');

        if (username.length < 3) {
            showError(usernameInput, usernameError, 'Username must be at least 3 characters long');
            clearSuccess(usernameSuccess);
            return false;
        }

        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            showError(usernameInput, usernameError, 'Username can only contain letters, numbers, and underscores');
            clearSuccess(usernameSuccess);
            return false;
        }

        clearError(usernameInput, usernameError);
        showSuccess(usernameInput, usernameSuccess, 'Username is available!');
        return true;
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailError = document.getElementById('emailError');
        const emailSuccess = document.getElementById('emailSuccess');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            showError(emailInput, emailError, 'Please enter a valid email address');
            clearSuccess(emailSuccess);
            return false;
        }

        clearError(emailInput, emailError);
        showSuccess(emailInput, emailSuccess, 'Email format is valid!');
        return true;
    }

    function validatePassword() {
        const password = passwordInput.value;
        const passwordError = document.getElementById('passwordError');
        const passwordSuccess = document.getElementById('passwordSuccess');

        if (password.length < 6) {
            showError(passwordInput, passwordError, 'Password must be at least 6 characters long');
            clearSuccess(passwordSuccess);
            return false;
        }

        if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
            showError(passwordInput, passwordError, 'Password must contain at least one uppercase letter, one lowercase letter, and one number');
            clearSuccess(passwordSuccess);
            return false;
        }

        clearError(passwordInput, passwordError);
        showSuccess(passwordInput, passwordSuccess, 'Password is strong!');
        return true;
    }

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        const confirmPasswordSuccess = document.getElementById('confirmPasswordSuccess');

        if (password !== confirmPassword) {
            showError(confirmPasswordInput, confirmPasswordError, 'Passwords do not match');
            clearSuccess(confirmPasswordSuccess);
            return false;
        }

        if (confirmPassword.length === 0) {
            showError(confirmPasswordInput, confirmPasswordError, 'Please confirm your password');
            clearSuccess(confirmPasswordSuccess);
            return false;
        }

        clearError(confirmPasswordInput, confirmPasswordError);
        showSuccess(confirmPasswordInput, confirmPasswordSuccess, 'Passwords match!');
        return true;
    }

    // Helper functions
    function showError(input, errorElement, message) {
        input.classList.add('error');
        input.classList.remove('success');
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }

    function showSuccess(input, successElement, message) {
        input.classList.add('success');
        input.classList.remove('error');
        successElement.textContent = message;
        successElement.classList.add('show');
    }

    function clearError(input, errorElement) {
        input.classList.remove('error');
        errorElement.textContent = '';
        errorElement.classList.remove('show');
    }

    function clearSuccess(successElement) {
        successElement.textContent = '';
        successElement.classList.remove('show');
    }

    // OTP input navigation - PERBAIKAN: Tambahkan fungsi untuk navigasi OTP
    function setupOTPNavigation() {
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                // Jika input terisi dan bukan input terakhir, pindah ke input berikutnya
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function (e) {
                // Jika backspace dan input kosong, pindah ke input sebelumnya
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });
    }

    // Event listeners for real-time validation
    nameInput.addEventListener('blur', validateName);
    nameInput.addEventListener('input', function () {
        if (nameInput.value.trim().length > 0) {
            validateName();
        }
    });

    usernameInput.addEventListener('blur', validateUsername);
    usernameInput.addEventListener('input', function () {
        if (usernameInput.value.trim().length > 0) {
            validateUsername();
        }
    });

    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function () {
        if (emailInput.value.trim().length > 0) {
            validateEmail();
        }
    });

    passwordInput.addEventListener('blur', validatePassword);
    passwordInput.addEventListener('input', function () {
        if (passwordInput.value.length > 0) {
            validatePassword();
        }
        // Re-validate confirm password if it has value
        if (confirmPasswordInput.value.length > 0) {
            validateConfirmPassword();
        }
    });

    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
    confirmPasswordInput.addEventListener('input', function () {
        if (confirmPasswordInput.value.length > 0) {
            validateConfirmPassword();
        }
    });

    // Email verification
    emailVerifyBtn.addEventListener('click', function () {
        if (validateEmail()) {
            const originalText = emailVerifyBtn.textContent;
            emailVerifyBtn.textContent = 'Sending...';
            emailVerifyBtn.disabled = true;

            // Kirim OTP ke email
            fetch('/auth/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: emailInput.value
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show OTP modal
                        userEmailSpan.textContent = emailInput.value;
                        otpModal.classList.add('show');

                        // Setup OTP navigation dan fokus ke input pertama
                        setupOTPNavigation();
                        if (otpInputs.length > 0) {
                            otpInputs[0].focus();
                        }

                        // Show success message
                        alert('Verification code has been sent to your email!');
                    } else {
                        alert(data.message || 'Failed to send OTP. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    emailVerifyBtn.textContent = originalText;
                    emailVerifyBtn.disabled = false;
                });
        }
    });

    // OTP verification - PERBAIKAN: Perbaiki handler untuk tombol verify
    document.getElementById('verifyBtn').addEventListener('click', function () {
        // Pastikan otpInputs sudah terdefinisi
        if (!otpInputs || otpInputs.length === 0) {
            console.error('OTP inputs not found');
            alert('Error: OTP inputs not found. Please try again.');
            return;
        }

        const otpValue = Array.from(otpInputs).map(input => input.value).join('');

        if (otpValue.length !== 6) {
            alert('Please enter the complete 6-digit code');
            return;
        }

        const verifyBtn = document.getElementById('verifyBtn');
        const originalText = verifyBtn.textContent;
        verifyBtn.textContent = 'Verifying...';
        verifyBtn.disabled = true;

        // Verify OTP with server
        fetch('/auth/verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: emailInput.value,
                otp: otpValue
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isEmailVerified = true;
                    otpModal.classList.remove('show');

                    // Update email verification status
                    const emailSuccess = document.getElementById('emailSuccess');
                    showSuccess(emailInput, emailSuccess, 'Email verified successfully!');
                    emailVerifyBtn.textContent = 'Verified';
                    emailVerifyBtn.disabled = true;
                    emailVerifyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';

                    // Clear OTP inputs
                    otpInputs.forEach(input => input.value = '');

                    alert('Email verified successfully!');
                } else {
                    alert(data.message || 'Invalid verification code. Please try again.');
                    // Clear OTP inputs
                    otpInputs.forEach(input => input.value = '');
                    if (otpInputs.length > 0) {
                        otpInputs[0].focus();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                verifyBtn.textContent = originalText;
                verifyBtn.disabled = false;
            });
    });

    // Resend OTP - PERBAIKAN: Perbaiki endpoint URL
    document.getElementById('resendLink').addEventListener('click', function (e) {
        e.preventDefault();

        const resendLink = document.getElementById('resendLink');
        const originalText = resendLink.textContent;
        resendLink.textContent = 'Sending...';
        resendLink.style.pointerEvents = 'none';

        // Kirim ulang OTP
        fetch('/auth/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: emailInput.value
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Verification code has been resent to your email');
                    // Clear OTP inputs
                    otpInputs.forEach(input => input.value = '');
                    if (otpInputs.length > 0) {
                        otpInputs[0].focus();
                    }
                } else {
                    alert(data.message || 'Failed to resend OTP. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                resendLink.textContent = originalText;
                resendLink.style.pointerEvents = 'auto';
            });
    });

    // PERBAIKAN: Tambahkan berbagai cara untuk menutup modal

    // 1. Tutup modal saat klik di luar area modal (overlay)
    otpModal.addEventListener('click', function (e) {
        if (e.target === otpModal) {
            closeOTPModal();
        }
    });

    // 2. Tutup modal saat klik tombol close (X)
    const closeModalBtn = document.querySelector('#otpModal .close, #otpModal .modal-close, #otpModal [data-close]');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeOTPModal);
    }

    // 3. Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && otpModal.classList.contains('show')) {
            closeOTPModal();
        }
    });

    // 4. Fungsi untuk menutup modal
    function closeOTPModal() {
        otpModal.classList.remove('show');
        // Clear OTP inputs saat modal ditutup
        otpInputs.forEach(input => input.value = '');
    }

    // 5. Tambahkan tombol cancel jika ada
    const cancelBtn = document.querySelector('#otpModal .cancel-btn, #otpModal .btn-cancel');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeOTPModal);
    }

    // Form submission - Updated to use AJAX
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validate all fields
        const isNameValid = validateName();
        const isUsernameValid = validateUsername();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();

        // Check if email is verified
        if (!isEmailVerified) {
            alert('Please verify your email address first');
            return;
        }

        // Check if all validations pass
        if (isNameValid && isUsernameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid) {
            // Simulate registration process
            const registerBtn = document.getElementById('registerBtn');
            const originalText = registerBtn.textContent;
            registerBtn.textContent = 'Creating Account...';
            registerBtn.disabled = true;

            // Submit registration data
            fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: nameInput.value,
                    username: usernameInput.value,
                    email: emailInput.value,
                    password: passwordInput.value,
                    confirmPassword: confirmPasswordInput.value
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Account created successfully! You can now login.');

                        // Reset form
                        form.reset();

                        // Clear all validation states
                        const inputs = document.querySelectorAll('.regis-form-input');
                        const errors = document.querySelectorAll('.regis-error-message');
                        const successes = document.querySelectorAll('.regis-success-message');

                        inputs.forEach(input => {
                            input.classList.remove('error', 'success');
                        });

                        errors.forEach(error => {
                            error.classList.remove('show');
                            error.textContent = '';
                        });

                        successes.forEach(success => {
                            success.classList.remove('show');
                            success.textContent = '';
                        });

                        // Reset email verification
                        emailVerifyBtn.textContent = 'Verify';
                        emailVerifyBtn.disabled = false;
                        emailVerifyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                        isEmailVerified = false;

                        // Redirect to login page
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 2000);
                    } else {
                        if (data.errors) {
                            // Show validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(field + 'Error');
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    errorElement.classList.add('show');
                                }
                            });
                        } else {
                            alert(data.message || 'Registration failed. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    registerBtn.textContent = originalText;
                    registerBtn.disabled = false;
                });
        } else {
            alert('Please fix the errors before submitting the form');
        }
    });

    // PERBAIKAN: Inisialisasi saat DOM loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Re-query OTP inputs jika belum ditemukan
        if (otpInputs.length === 0) {
            const newOtpInputs = document.querySelectorAll('input[id^="otp"]');
            if (newOtpInputs.length > 0) {
                // Update otpInputs reference
                otpInputs = newOtpInputs;
            }
        }
    });
</script>

</html>