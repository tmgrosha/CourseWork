// Utility function to escape HTML special characters
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// JavaScript function to toggle sidenav and switch icons
function toggleNav() {
    const sidenav = document.querySelector(".sidenav");
    const menuIcon = document.querySelector(".sidenav-trigger.menu");
    const closeIcon = document.querySelector(".sidenav-trigger.close");

    const isOpen = sidenav.classList.toggle("open");

    menuIcon.style.display = isOpen ? "none" : "block";
    closeIcon.style.display = isOpen ? "block" : "none";

    document.body.classList.toggle("no-scroll");
}

// Function to toggle password visibility
function togglePasswordVisibility() {
    const passwordField = document.getElementById("password_login");
    const confirmPasswordField = document.getElementById("confirm_password");
    const icons = document.querySelectorAll(".password-input i");

    const isPasswordType = passwordField.type === "password";
    passwordField.type = isPasswordType ? "text" : "password";
    confirmPasswordField.type = isPasswordType ? "text" : "password";

    icons[0].style.display = isPasswordType ? "none" : "inline-block";
    icons[1].style.display = isPasswordType ? "inline-block" : "none";
}

// Validation of the registration form
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    const name = escapeHtml(document.getElementById('name').value.trim());
    const email = escapeHtml(document.getElementById('email').value.trim());
    const phone = escapeHtml(document.getElementById('phone').value.trim());
    const password = document.getElementById('password_login').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const termsCheckbox = document.getElementById('terms_checkbox').checked;
    const errorMessages = [];

    // Validation checks
    if (!name) {
        errorMessages.push('Full Name is required.');
    }

    if (!email || !/\S+@\S+\.\S+/.test(email)) {
        errorMessages.push('Valid Email Address is required.');
    }

    if (!phone || !/^\d{10}$/.test(phone)) {
        errorMessages.push('Valid Phone Number is required (10 digits).');
    }

    if (password.length < 6) {
        errorMessages.push('Password must be at least 6 characters long.');
    }

    if (password !== confirmPassword) {
        errorMessages.push('Passwords do not match.');
    }

    if (!termsCheckbox) {
        errorMessages.push('You must agree to the terms & conditions.');
    }

    // Handle errors
    if (errorMessages.length > 0) {
        event.preventDefault();
        const errorContainer = document.getElementById('errorMessages');
        errorContainer.textContent = ''; // Clear previous messages
        errorMessages.forEach(msg => {
            const p = document.createElement('p');
            p.textContent = msg; // Safely set text content
            errorContainer.appendChild(p);
        });
    }
});
