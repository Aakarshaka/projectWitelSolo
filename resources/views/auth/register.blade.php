<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
                <input type="text" class="regis-form-input" placeholder="Username" name="username" id="username" required>
                <div class="regis-error-message" id="usernameError"></div>
                <div class="regis-success-message" id="usernameSuccess"></div>
            </div>

            <div class="regis-form-group">
                <div class="email-verification-group">
                    <div class="email-input-container">
                        <input type="email" class="regis-form-input" placeholder="Email Address" name="email" id="email" required>
                        <div class="regis-error-message" id="emailError"></div>
                        <div class="regis-success-message" id="emailSuccess"></div>
                    </div>
                    <button type="button" class="email-verify-btn" id="emailVerifyBtn">Verify</button>
                </div>
            </div>

            <div class="regis-form-group">
                <div class="password-input-container">
                    <input type="password" class="regis-form-input" placeholder="Password" name="password" id="password" required>
                    <button type="button" class="password-toggle" id="passwordToggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="regis-error-message" id="passwordError"></div>
                <div class="regis-success-message" id="passwordSuccess"></div>
            </div>

            <div class="regis-form-group">
                <div class="password-input-container">
                    <input type="password" class="regis-form-input" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" required>
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

        // State variables
        let isEmailVerified = false;

        // Password toggle functionality
        function setupPasswordToggle(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);

            toggle.addEventListener('click', function() {
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

        // Event listeners for real-time validation
        nameInput.addEventListener('blur', validateName);
        nameInput.addEventListener('input', function() {
            if (nameInput.value.trim().length > 0) {
                validateName();
            }
        });

        usernameInput.addEventListener('blur', validateUsername);
        usernameInput.addEventListener('input', function() {
            if (usernameInput.value.trim().length > 0) {
                validateUsername();
            }
        });

        emailInput.addEventListener('blur', validateEmail);
        emailInput.addEventListener('input', function() {
            if (emailInput.value.trim().length > 0) {
                validateEmail();
            }
        });

        passwordInput.addEventListener('blur', validatePassword);
        passwordInput.addEventListener('input', function() {
            if (passwordInput.value.length > 0) {
                validatePassword();
            }
            // Re-validate confirm password if it has value
            if (confirmPasswordInput.value.length > 0) {
                validateConfirmPassword();
            }
        });

        confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
        confirmPasswordInput.addEventListener('input', function() {
            if (confirmPasswordInput.value.length > 0) {
                validateConfirmPassword();
            }
        });

        // Email verification
        emailVerifyBtn.addEventListener('click', function() {
            if (validateEmail()) {
                // Show OTP modal
                userEmailSpan.textContent = emailInput.value;
                otpModal.classList.add('show');
                document.getElementById('otp1').focus();
            }
        });

        // OTP input handling
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                if (e.target.value.length === 1) {
                    // Move to next input
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    // Move to previous input
                    if (index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
            });

            // Only allow numbers
            input.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
                    e.preventDefault();
                }
            });
        });

        // OTP verification
        document.getElementById('verifyBtn').addEventListener('click', function() {
            const otpValue = Array.from(otpInputs).map(input => input.value).join('');

            if (otpValue.length !== 6) {
                alert('Please enter the complete 6-digit code');
                return;
            }

            // Simulate OTP verification
            if (otpValue === '123456') {
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
            } else {
                alert('Invalid verification code. Please try again.');
                // Clear OTP inputs
                otpInputs.forEach(input => input.value = '');
                document.getElementById('otp1').focus();
            }
        });

        // Cancel OTP
        document.getElementById('cancelBtn').addEventListener('click', function() {
            otpModal.classList.remove('show');
            // Clear OTP inputs
            otpInputs.forEach(input => input.value = '');
        });

        // Resend OTP
        document.getElementById('resendLink').addEventListener('click', function(e) {
            e.preventDefault();
            // Simulate resending OTP
            alert('Verification code has been resent to your email');
            // Clear OTP inputs
            otpInputs.forEach(input => input.value = '');
            document.getElementById('otp1').focus();
        });

        // Form submission
        form.addEventListener('submit', function(e) {
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
                registerBtn.textContent = 'Creating Account...';
                registerBtn.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    alert('Account created successfully! Please check your email for confirmation.');

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

                    // Reset button and email verification
                    registerBtn.textContent = 'Create Account';
                    registerBtn.disabled = false;
                    emailVerifyBtn.textContent = 'Verify';
                    emailVerifyBtn.disabled = false;
                    emailVerifyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    isEmailVerified = false;

                    // Redirect to login page (simulate)
                    // window.location.href = 'login.html';
                }, 2000);
            } else {
                alert('Please fix the errors before submitting the form');
            }
        });

        // Close OTP modal when clicking outside
        otpModal.addEventListener('click', function(e) {
            if (e.target === otpModal) {
                otpModal.classList.remove('show');
                // Clear OTP inputs
                otpInputs.forEach(input => input.value = '');
            }
        });

        // Prevent form submission on Enter key in OTP modal
        otpInputs.forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('verifyBtn').click();
                }
            });
        });

        // Add loading animation for email verification
        emailVerifyBtn.addEventListener('click', function() {
            if (validateEmail() && !isEmailVerified) {
                const originalText = emailVerifyBtn.textContent;
                emailVerifyBtn.textContent = 'Sending...';
                emailVerifyBtn.disabled = true;

                // Simulate sending email
                setTimeout(() => {
                    emailVerifyBtn.textContent = originalText;
                    emailVerifyBtn.disabled = false;
                }, 1500);
            }
        });

        // Enhance user experience with smooth transitions
        const inputs = document.querySelectorAll('.regis-form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add subtle hover effects to form groups
        const formGroups = document.querySelectorAll('.regis-form-group');
        formGroups.forEach(group => {
            group.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
            });

            group.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Initialize tooltips for better UX
        const tooltips = [{
                element: nameInput,
                message: 'Enter your full name as it appears on your ID'
            },
            {
                element: usernameInput,
                message: 'Choose a unique username (3+ characters, letters, numbers, underscores only)'
            },
            {
                element: emailInput,
                message: 'We\'ll send a verification code to this email'
            },
            {
                element: passwordInput,
                message: 'Use 6+ characters with uppercase, lowercase, and numbers'
            },
            {
                element: confirmPasswordInput,
                message: 'Re-enter your password to confirm'
            }
        ];

        tooltips.forEach(tooltip => {
            tooltip.element.addEventListener('focus', function() {
                // You can add tooltip display logic here
                console.log(tooltip.message);
            });
        });

        // Keyboard navigation enhancement
        document.addEventListener('keydown', function(e) {
            // Close OTP modal with Escape key
            if (e.key === 'Escape' && otpModal.classList.contains('show')) {
                otpModal.classList.remove('show');
                otpInputs.forEach(input => input.value = '');
            }
        });

        // Auto-resize functionality for better mobile experience
        function adjustForMobile() {
            const isMobile = window.innerWidth <= 768;
            const formGroups = document.querySelectorAll('.regis-form-group');

            if (isMobile) {
                formGroups.forEach(group => {
                    group.style.marginBottom = '15px';
                });
            } else {
                formGroups.forEach(group => {
                    group.style.marginBottom = '18px';
                });
            }
        }

        // Call on load and resize
        adjustForMobile();
        window.addEventListener('resize', adjustForMobile);

        // Add success animations
        function addSuccessAnimation(element) {
            element.style.animation = 'fadeInScale 0.3s ease-out';
            setTimeout(() => {
                element.style.animation = '';
            }, 300);
        }

        // Enhance success messages with animations
        const successMessages = document.querySelectorAll('.regis-success-message');
        successMessages.forEach(message => {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        if (message.classList.contains('show')) {
                            addSuccessAnimation(message);
                        }
                    }
                });
            });
            observer.observe(message, {
                attributes: true
            });
        });
    </script>
</body>

</html>