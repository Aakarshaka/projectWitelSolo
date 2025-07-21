// Form elements
const emailForm = document.getElementById("emailForm");
const passwordForm = document.getElementById("passwordForm");
const emailInput = document.getElementById("email");
const newPasswordInput = document.getElementById("newPassword");
const confirmNewPasswordInput = document.getElementById("confirmNewPassword");
const emailVerifyBtn = document.getElementById("emailVerifyBtn");
const otpModal = document.getElementById("otpModal");
const userEmailSpan = document.getElementById("userEmail");
const verifyBtn = document.getElementById("verifyBtn");
const cancelBtn = document.getElementById("cancelBtn");
const resendLink = document.getElementById("resendLink");
const resetPasswordBtn = document.getElementById("resetPasswordBtn");

// OTP inputs
const otpInputs = document.querySelectorAll('input[id^="otp"]');

// State variables
let isEmailVerified = false;
let currentStep = 1;
let userEmail = "";
let resetToken = "";

// Initialize
document.addEventListener("DOMContentLoaded", function () {
    showStep(1);
    setupPasswordToggle("newPassword", "newPasswordToggle");
    setupPasswordToggle("confirmNewPassword", "confirmNewPasswordToggle");
    setupOTPNavigation();
});

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

// Step management
function showStep(stepNumber) {
    currentStep = stepNumber;

    document.querySelectorAll(".form-step").forEach((step) => {
        step.classList.remove("active");
    });

    document.querySelectorAll(".step").forEach((step, index) => {
        step.classList.remove("active", "completed");
        if (index < stepNumber - 1) {
            step.classList.add("completed");
        } else if (index === stepNumber - 1) {
            step.classList.add("active");
        }
    });

    if (stepNumber === 1) {
        document.getElementById("emailStep").classList.add("active");
    } else if (stepNumber === 3) {
        document.getElementById("passwordStep").classList.add("active");
    }
}

// Validation functions
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

function validateNewPassword() {
    const password = newPasswordInput.value;
    const passwordError = document.getElementById("newPasswordError");
    const passwordSuccess = document.getElementById("newPasswordSuccess");

    if (password.length < 6) {
        showError(
            newPasswordInput,
            passwordError,
            "Password must be at least 6 characters long"
        );
        clearSuccess(passwordSuccess);
        return false;
    }

    if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
        showError(
            newPasswordInput,
            passwordError,
            "Password must contain at least one uppercase letter, one lowercase letter, and one number"
        );
        clearSuccess(passwordSuccess);
        return false;
    }

    clearError(newPasswordInput, passwordError);
    showSuccess(newPasswordInput, passwordSuccess, "Password is strong!");
    return true;
}

function validateConfirmNewPassword() {
    const password = newPasswordInput.value;
    const confirmPassword = confirmNewPasswordInput.value;
    const confirmPasswordError = document.getElementById(
        "confirmNewPasswordError"
    );
    const confirmPasswordSuccess = document.getElementById(
        "confirmNewPasswordSuccess"
    );

    if (password !== confirmPassword) {
        showError(
            confirmNewPasswordInput,
            confirmPasswordError,
            "Passwords do not match"
        );
        clearSuccess(confirmPasswordSuccess);
        return false;
    }

    if (confirmPassword.length === 0) {
        showError(
            confirmNewPasswordInput,
            confirmPasswordError,
            "Please confirm your password"
        );
        clearSuccess(confirmPasswordSuccess);
        return false;
    }

    clearError(confirmNewPasswordInput, confirmPasswordError);
    showSuccess(
        confirmNewPasswordInput,
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
        input.addEventListener("input", function (e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, "");

            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", function (e) {
            if (e.key === "Backspace" && this.value === "" && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener("paste", function (e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData(
                "text"
            );
            const pasteData = paste.replace(/[^0-9]/g, "").slice(0, 6);

            for (let i = 0; i < pasteData.length && i < otpInputs.length; i++) {
                otpInputs[i].value = pasteData[i];
            }

            if (pasteData.length > 0) {
                const lastIndex = Math.min(
                    pasteData.length - 1,
                    otpInputs.length - 1
                );
                otpInputs[lastIndex].focus();
            }
        });
    });
}

// Get OTP value
function getOTPValue() {
    return Array.from(otpInputs)
        .map((input) => input.value)
        .join("");
}

// Clear OTP inputs
function clearOTPInputs() {
    otpInputs.forEach((input) => {
        input.value = "";
    });
    if (otpInputs.length > 0) {
        otpInputs[0].focus();
    }
}

// Event listeners for validation
emailInput.addEventListener("blur", validateEmail);
emailInput.addEventListener("input", function () {
    if (emailInput.value.trim().length > 0) {
        validateEmail();
    }
});

newPasswordInput.addEventListener("blur", validateNewPassword);
newPasswordInput.addEventListener("input", function () {
    if (newPasswordInput.value.length > 0) {
        validateNewPassword();
    }
    if (confirmNewPasswordInput.value.length > 0) {
        validateConfirmNewPassword();
    }
});

confirmNewPasswordInput.addEventListener("blur", validateConfirmNewPassword);
confirmNewPasswordInput.addEventListener("input", function () {
    if (confirmNewPasswordInput.value.length > 0) {
        validateConfirmNewPassword();
    }
});

// Email verification
emailVerifyBtn.addEventListener("click", function () {
    if (validateEmail()) {
        userEmail = emailInput.value;
        const originalText = emailVerifyBtn.textContent;
        emailVerifyBtn.textContent = "Sending...";
        emailVerifyBtn.disabled = true;

        // Real API call for sending OTP
        fetch("/forget-password/send-otp", {
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
                    clearOTPInputs();
                    showStep(2);
                    alert("Verification code has been sent to your email!");
                } else {
                    showError(
                        emailInput,
                        document.getElementById("emailError"),
                        data.message
                    );
                    clearSuccess(document.getElementById("emailSuccess"));
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showError(
                    emailInput,
                    document.getElementById("emailError"),
                    "An error occurred. Please try again."
                );
                clearSuccess(document.getElementById("emailSuccess"));
            })
            .finally(() => {
                emailVerifyBtn.textContent = originalText;
                emailVerifyBtn.disabled = false;
            });
    }
});

// OTP verification - PERBAIKAN UTAMA
verifyBtn.addEventListener("click", function () {
    const otpValue = getOTPValue();

    if (otpValue.length !== 6) {
        alert("Please enter the complete 6-digit verification code");
        return;
    }

    const originalText = verifyBtn.textContent;
    verifyBtn.textContent = "Verifying...";
    verifyBtn.disabled = true;

    // Real API call for OTP verification - ENDPOINT SUDAH DIPERBAIKI
    fetch("/auth/verify-forget-password-otp", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            email: userEmail,
            otp: otpValue,  // PERBAIKAN: menggunakan 'otp' bukan 'fpotp'
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                isEmailVerified = true;
                resetToken = data.reset_token;
                otpModal.classList.remove("show");
                showStep(3);
                alert(
                    "Email verified successfully! You can now reset your password."
                );
            } else {
                alert("Error: " + data.message);
                clearOTPInputs();
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
            clearOTPInputs();
        })
        .finally(() => {
            verifyBtn.textContent = originalText;
            verifyBtn.disabled = false;
        });
});

// Resend OTP - ENDPOINT SUDAH DIPERBAIKI
resendLink.addEventListener("click", function (e) {
    e.preventDefault();

    const originalText = resendLink.textContent;
    resendLink.textContent = "Sending...";
    resendLink.style.pointerEvents = "none";

    // Real API call for resending OTP - ENDPOINT SUDAH DIPERBAIKI
    fetch("/auth/resend-forget-password-otp", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            email: userEmail,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                clearOTPInputs();
                alert("New verification code has been sent to your email!");
            } else {
                alert("Error: " + data.message);
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

// Reset password form submission - ENDPOINT SUDAH DIPERBAIKI
resetPasswordBtn.addEventListener("click", function (e) {
    e.preventDefault();

    if (!isEmailVerified) {
        alert("Please verify your email first.");
        return;
    }

    if (!resetToken) {
        alert("Invalid reset token. Please try again.");
        return;
    }

    if (!validateNewPassword() || !validateConfirmNewPassword()) {
        return;
    }

    const originalText = resetPasswordBtn.textContent;
    resetPasswordBtn.textContent = "Resetting...";
    resetPasswordBtn.disabled = true;

    // Real API call for password reset - ENDPOINT SUDAH DIPERBAIKI
    fetch("/auth/reset-password", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            email: userEmail,
            token: resetToken,
            password: newPasswordInput.value,
            password_confirmation: confirmNewPasswordInput.value,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert(
                    "Password reset successfully! Redirecting to login page..."
                );
                // Redirect to login page after successful reset
                setTimeout(() => {
                    window.location.href = "/login";
                }, 2000);
            } else {
                alert("Error: " + data.message);

                // Handle validation errors
                if (data.errors) {
                    // Clear previous errors
                    clearError(
                        newPasswordInput,
                        document.getElementById("newPasswordError")
                    );
                    clearError(
                        confirmNewPasswordInput,
                        document.getElementById("confirmNewPasswordError")
                    );

                    // Show specific validation errors
                    if (data.errors.password) {
                        showError(
                            newPasswordInput,
                            document.getElementById("newPasswordError"),
                            data.errors.password[0]
                        );
                    }
                    if (data.errors.password_confirmation) {
                        showError(
                            confirmNewPasswordInput,
                            document.getElementById("confirmNewPasswordError"),
                            data.errors.password_confirmation[0]
                        );
                    }
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        })
        .finally(() => {
            resetPasswordBtn.textContent = originalText;
            resetPasswordBtn.disabled = false;
        });
});

// Form submission handlers
if (emailForm) {
    emailForm.addEventListener("submit", function (e) {
        e.preventDefault();
        emailVerifyBtn.click();
    });
}

if (passwordForm) {
    passwordForm.addEventListener("submit", function (e) {
        e.preventDefault();
        resetPasswordBtn.click();
    });
}
