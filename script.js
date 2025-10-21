/**
 * ===============================================
 * Project: Student Registration with Validation
 * College: Chendhuran College of Engineering and Technology
 * Course: B.E CSE
 * Description: Client-side Form Validation using JavaScript
 * ===============================================
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    // Real-time validation on input
    fullNameInput.addEventListener('blur', validateFullName);
    emailInput.addEventListener('blur', validateEmail);
    phoneInput.addEventListener('blur', validatePhone);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);

    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous messages
        clearMessageBox();

        // Validate all fields
        const isFullNameValid = validateFullName();
        const isEmailValid = validateEmail();
        const isPhoneValid = validatePhone();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();

        // Check if all validations pass
        if (isFullNameValid && isEmailValid && isPhoneValid && isPasswordValid && isConfirmPasswordValid) {
            // All validations passed, submit the form
            form.submit();
        } else {
            // Show error message
            showMessage('Please correct all errors before submitting.', 'error');
        }
    });

    /**
     * Validate Full Name
     * Must not be empty and should contain only letters and spaces
     */
    function validateFullName() {
        const fullName = fullNameInput.value.trim();
        const errorElement = document.getElementById('fullNameError');

        if (fullName === '') {
            showError(fullNameInput, errorElement, 'Full name is required');
            return false;
        }

        if (fullName.length < 3) {
            showError(fullNameInput, errorElement, 'Full name must be at least 3 characters');
            return false;
        }

        if (!/^[a-zA-Z\s]+$/.test(fullName)) {
            showError(fullNameInput, errorElement, 'Full name should contain only letters and spaces');
            return false;
        }

        clearError(fullNameInput, errorElement);
        return true;
    }

    /**
     * Validate Email
     * Must be in valid email format
     */
    function validateEmail() {
        const email = emailInput.value.trim();
        const errorElement = document.getElementById('emailError');

        if (email === '') {
            showError(emailInput, errorElement, 'Email is required');
            return false;
        }

        // Email validation regex
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            showError(emailInput, errorElement, 'Please enter a valid email address');
            return false;
        }

        clearError(emailInput, errorElement);
        return true;
    }

    /**
     * Validate Phone Number
     * Must be exactly 10 digits
     */
    function validatePhone() {
        const phone = phoneInput.value.trim();
        const errorElement = document.getElementById('phoneError');

        if (phone === '') {
            showError(phoneInput, errorElement, 'Phone number is required');
            return false;
        }

        // Phone must be exactly 10 digits
        if (!/^\d{10}$/.test(phone)) {
            showError(phoneInput, errorElement, 'Phone number must be exactly 10 digits');
            return false;
        }

        clearError(phoneInput, errorElement);
        return true;
    }

    /**
     * Validate Password
     * Must be at least 6 characters
     */
    function validatePassword() {
        const password = passwordInput.value;
        const errorElement = document.getElementById('passwordError');

        if (password === '') {
            showError(passwordInput, errorElement, 'Password is required');
            return false;
        }

        if (password.length < 6) {
            showError(passwordInput, errorElement, 'Password must be at least 6 characters');
            return false;
        }

        clearError(passwordInput, errorElement);
        return true;
    }

    /**
     * Validate Confirm Password
     * Must match the password field
     */
    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const errorElement = document.getElementById('confirmPasswordError');

        if (confirmPassword === '') {
            showError(confirmPasswordInput, errorElement, 'Please confirm your password');
            return false;
        }

        if (password !== confirmPassword) {
            showError(confirmPasswordInput, errorElement, 'Passwords do not match');
            return false;
        }

        clearError(confirmPasswordInput, errorElement);
        return true;
    }

    /**
     * Show error message for a field
     */
    function showError(input, errorElement, message) {
        input.classList.add('error-input');
        errorElement.textContent = message;
    }

    /**
     * Clear error message for a field
     */
    function clearError(input, errorElement) {
        input.classList.remove('error-input');
        errorElement.textContent = '';
    }

    /**
     * Show message box (success or error)
     */
    function showMessage(message, type) {
        const messageBox = document.getElementById('message-box');
        messageBox.textContent = message;
        messageBox.className = 'message-box ' + type;
        messageBox.style.display = 'block';

        // Scroll to message box
        messageBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /**
     * Clear message box
     */
    function clearMessageBox() {
        const messageBox = document.getElementById('message-box');
        messageBox.style.display = 'none';
        messageBox.className = 'message-box';
    }

    // Restrict phone input to numbers only
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
