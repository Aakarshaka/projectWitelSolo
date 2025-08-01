// Form validation
const form = document.getElementById("registrationForm");
const usernameInput = document.getElementById("username");
const emailInput = document.getElementById("email");
const roleSelect = document.getElementById("role"); // Tambahkan ini
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirmPassword");
const otpModal = document.getElementById("otpModal");
const userEmailSpan = document.getElementById("userEmail");
const emailVerifyBtn = document.getElementById("emailVerifyBtn");

// OTP inputs
const otpInputs = document.querySelectorAll('input[id^="otp"]');

// State variables
let isEmailVerified = false;

// Password toggle functionality
function setupPasswordToggle(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);

    toggle.addEventListener("click", function () {
        const type =
            input.getAttribute("type") === "password" ? "text" : "password";
        input.setAttribute("type", type);

        const icon = toggle.querySelector("i");
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    });
}

setupPasswordToggle("password", "passwordToggle");
setupPasswordToggle("confirmPassword", "confirmPasswordToggle");

// Validation functions
function validateUsername() {
    const username = usernameInput.value.trim();
    const usernameError = document.getElementById("usernameError");
    const usernameSuccess = document.getElementById("usernameSuccess");

    if (username.length < 3) {
        showError(
            usernameInput,
            usernameError,
            "Username must be at least 3 characters long"
        );
        clearSuccess(usernameSuccess);
        return false;
    }

    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        showError(
            usernameInput,
            usernameError,
            "Username can only contain letters, numbers, and underscores"
        );
        clearSuccess(usernameSuccess);
        return false;
    }

    clearError(usernameInput, usernameError);
    showSuccess(usernameInput, usernameSuccess, "Username is available!");
    return true;
}

function validateEmail() {
    const email = emailInput.value.trim();
    const emailError = document.getElementById("emailError");
    const emailSuccess = document.getElementById("emailSuccess");
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
        showError(emailInput, emailError, "Please enter a valid email address");
        clearSuccess(emailSuccess);
        return false;
    }

    clearError(emailInput, emailError);
    showSuccess(emailInput, emailSuccess, "Email format is valid!");
    return true;
}

// Tambahkan fungsi validasi role
function validateRole() {
    const role = roleSelect.value;
    const roleError = document.getElementById("roleError");
    const roleSuccess = document.getElementById("roleSuccess");

    if (!role || role === "") {
        showError(roleSelect, roleError, "Unit is required");
        clearSuccess(roleSuccess);
        return false;
    }

    clearError(roleSelect, roleError);
    showSuccess(roleSelect, roleSuccess, "Unit selected!");
    return true;
}

function validatePassword() {
    const password = passwordInput.value;
    const passwordError = document.getElementById("passwordError");
    const passwordSuccess = document.getElementById("passwordSuccess");

    if (password.length < 6) {
        showError(
            passwordInput,
            passwordError,
            "Password must be at least 6 characters long"
        );
        clearSuccess(passwordSuccess);
        return false;
    }

    if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
        showError(
            passwordInput,
            passwordError,
            "Password must contain at least one uppercase letter, one lowercase letter, and one number"
        );
        clearSuccess(passwordSuccess);
        return false;
    }

    clearError(passwordInput, passwordError);
    showSuccess(passwordInput, passwordSuccess, "Password is strong!");
    return true;
}

function validateConfirmPassword() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const confirmPasswordError = document.getElementById(
        "confirmPasswordError"
    );
    const confirmPasswordSuccess = document.getElementById(
        "confirmPasswordSuccess"
    );

    if (password !== confirmPassword) {
        showError(
            confirmPasswordInput,
            confirmPasswordError,
            "Passwords do not match"
        );
        clearSuccess(confirmPasswordSuccess);
        return false;
    }

    if (confirmPassword.length === 0) {
        showError(
            confirmPasswordInput,
            confirmPasswordError,
            "Please confirm your password"
        );
        clearSuccess(confirmPasswordSuccess);
        return false;
    }

    clearError(confirmPasswordInput, confirmPasswordError);
    showSuccess(
        confirmPasswordInput,
        confirmPasswordSuccess,
        "Passwords match!"
    );
    return true;
}

// Helper functions
function showError(input, errorElement, message) {
    input.classList.add("error");
    input.classList.remove("success");
    errorElement.textContent = message;
    errorElement.classList.add("show");
}

function showSuccess(input, successElement, message) {
    input.classList.add("success");
    input.classList.remove("error");
    successElement.textContent = message;
    successElement.classList.add("show");
}

function clearError(input, errorElement) {
    input.classList.remove("error");
    errorElement.textContent = "";
    errorElement.classList.remove("show");
}

function clearSuccess(successElement) {
    successElement.textContent = "";
    successElement.classList.remove("show");
}

// OTP input navigation
function setupOTPNavigation() {
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", function () {
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", function (e) {
            if (e.key === "Backspace" && this.value === "" && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });
}

// Event listeners for real-time validation
usernameInput.addEventListener("blur", validateUsername);
usernameInput.addEventListener("input", function () {
    if (usernameInput.value.trim().length > 0) {
        validateUsername();
    }
});

emailInput.addEventListener("blur", validateEmail);
emailInput.addEventListener("input", function () {
    if (emailInput.value.trim().length > 0) {
        validateEmail();
    }
});

// Tambahkan event listener untuk role
roleSelect.addEventListener("change", validateRole);
roleSelect.addEventListener("blur", validateRole);

passwordInput.addEventListener("blur", validatePassword);
passwordInput.addEventListener("input", function () {
    if (passwordInput.value.length > 0) {
        validatePassword();
    }
    if (confirmPasswordInput.value.length > 0) {
        validateConfirmPassword();
    }
});

confirmPasswordInput.addEventListener("blur", validateConfirmPassword);
confirmPasswordInput.addEventListener("input", function () {
    if (confirmPasswordInput.value.length > 0) {
        validateConfirmPassword();
    }
});

// Email verification
emailVerifyBtn.addEventListener("click", function () {
    if (validateEmail()) {
        const originalText = emailVerifyBtn.textContent;
        emailVerifyBtn.textContent = "Sending...";
        emailVerifyBtn.disabled = true;

        fetch("/auth/send-otp", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                email: emailInput.value,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    userEmailSpan.textContent = emailInput.value;
                    otpModal.classList.add("show");
                    setupOTPNavigation();
                    if (otpInputs.length > 0) {
                        otpInputs[0].focus();
                    }
                    alert("Verification code has been sent to your email!");
                } else {
                    alert(
                        data.message || "Failed to send OTP. Please try again."
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            })
            .finally(() => {
                emailVerifyBtn.textContent = originalText;
                emailVerifyBtn.disabled = false;
            });
    }
});

// OTP verification
document.getElementById("verifyBtn").addEventListener("click", function () {
    if (!otpInputs || otpInputs.length === 0) {
        console.error("OTP inputs not found");
        alert("Error: OTP inputs not found. Please try again.");
        return;
    }

    const otpValue = Array.from(otpInputs)
        .map((input) => input.value)
        .join("");

    if (otpValue.length !== 6) {
        alert("Please enter the complete 6-digit code");
        return;
    }

    const verifyBtn = document.getElementById("verifyBtn");
    const originalText = verifyBtn.textContent;
    verifyBtn.textContent = "Verifying...";
    verifyBtn.disabled = true;

    fetch("/auth/verify-otp", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            email: emailInput.value,
            otp: otpValue,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                isEmailVerified = true;
                otpModal.classList.remove("show");

                const emailSuccess = document.getElementById("emailSuccess");
                showSuccess(
                    emailInput,
                    emailSuccess,
                    "Email verified successfully!"
                );
                emailVerifyBtn.textContent = "Verified";
                emailVerifyBtn.disabled = true;
                emailVerifyBtn.style.background =
                    "linear-gradient(135deg, #10b981, #059669)";

                otpInputs.forEach((input) => (input.value = ""));
                alert("Email verified successfully!");
            } else {
                alert(
                    data.message ||
                        "Invalid verification code. Please try again."
                );
                otpInputs.forEach((input) => (input.value = ""));
                if (otpInputs.length > 0) {
                    otpInputs[0].focus();
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        })
        .finally(() => {
            verifyBtn.textContent = originalText;
            verifyBtn.disabled = false;
        });
});

// Resend OTP
document.getElementById("resendLink").addEventListener("click", function (e) {
    e.preventDefault();

    const resendLink = document.getElementById("resendLink");
    const originalText = resendLink.textContent;
    resendLink.textContent = "Sending...";
    resendLink.style.pointerEvents = "none";

    fetch("/auth/send-otp", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            email: emailInput.value,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Verification code has been resent to your email");
                otpInputs.forEach((input) => (input.value = ""));
                if (otpInputs.length > 0) {
                    otpInputs[0].focus();
                }
            } else {
                alert(
                    data.message || "Failed to resend OTP. Please try again."
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        })
        .finally(() => {
            resendLink.textContent = originalText;
            resendLink.style.pointerEvents = "auto";
        });
});

// Modal close functionality
function closeOTPModal() {
    otpModal.classList.remove("show");
    otpInputs.forEach((input) => (input.value = ""));
}

// 1. Close modal when clicking outside (overlay)
otpModal.addEventListener("click", function (e) {
    if (e.target === otpModal) {
        closeOTPModal();
    }
});

// 2. Close modal with ESC key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && otpModal.classList.contains("show")) {
        closeOTPModal();
    }
});

// 3. Cancel button functionality
document.getElementById("cancelBtn").addEventListener("click", function (e) {
    e.preventDefault();
    closeOTPModal();
});

// Form submission - PERBAIKAN UTAMA DI SINI
form.addEventListener("submit", function (e) {
    e.preventDefault();

    const isUsernameValid = validateUsername();
    const isEmailValid = validateEmail();
    const isRoleValid = validateRole(); // Tambahkan validasi role
    const isPasswordValid = validatePassword();
    const isConfirmPasswordValid = validateConfirmPassword();

    if (!isEmailVerified) {
        alert("Please verify your email address first");
        return;
    }

    // Tambahkan isRoleValid ke dalam kondisi
    if (
        isUsernameValid &&
        isEmailValid &&
        isRoleValid &&
        isPasswordValid &&
        isConfirmPasswordValid
    ) {
        const registerBtn = document.getElementById("registerBtn");
        const originalText = registerBtn.textContent;
        registerBtn.textContent = "Creating Account...";
        registerBtn.disabled = true;

        // PERBAIKAN: Tambahkan role ke dalam data yang dikirim
        fetch("/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                username: usernameInput.value,
                email: emailInput.value,
                role: roleSelect.value, // TAMBAHKAN INI - field role yang hilang
                password: passwordInput.value,
                confirmPassword: confirmPasswordInput.value,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Account created successfully! You can now login.");

                    form.reset();

                    const inputs =
                        document.querySelectorAll(".regis-form-input");
                    const errors = document.querySelectorAll(
                        ".regis-error-message"
                    );
                    const successes = document.querySelectorAll(
                        ".regis-success-message"
                    );

                    inputs.forEach((input) => {
                        input.classList.remove("error", "success");
                    });

                    errors.forEach((error) => {
                        error.classList.remove("show");
                        error.textContent = "";
                    });

                    successes.forEach((success) => {
                        success.classList.remove("show");
                        success.textContent = "";
                    });

                    emailVerifyBtn.textContent = "Verify";
                    emailVerifyBtn.disabled = false;
                    emailVerifyBtn.style.background =
                        "linear-gradient(135deg, #10b981, #059669)";
                    isEmailVerified = false;

                    setTimeout(() => {
                        window.location.href = "/login";
                    }, 2000);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach((field) => {
                            const errorElement = document.getElementById(
                                field + "Error"
                            );
                            if (errorElement) {
                                errorElement.textContent =
                                    data.errors[field][0];
                                errorElement.classList.add("show");
                            }
                        });
                    } else {
                        alert(
                            data.message ||
                                "Registration failed. Please try again."
                        );
                    }
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            })
            .finally(() => {
                registerBtn.textContent = originalText;
                registerBtn.disabled = false;
            });
    } else {
        alert("Please fix the errors before submitting the form");
    }
});

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    setupOTPNavigation();
});

// Unit list array
const unitList = [
    'TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 
    'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 
    'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 
    'GS', 'PRQ', 'SSGS', 'LESA V', 'RSO WITEL','INTERN'
];

// Populate role dropdown
function populateRoleDropdown() {
    const roleSelect = document.getElementById('role');
    
    unitList.forEach(unit => {
        const option = document.createElement('option');
        option.value = unit;
        option.textContent = unit;
        roleSelect.appendChild(option);
    });
}

// Initialize dropdown when page loads
document.addEventListener('DOMContentLoaded', function() {
    populateRoleDropdown();
});