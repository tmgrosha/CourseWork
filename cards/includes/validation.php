<?php
// validation.php

function validateRegistrationForm($data) {
    $errors = [];

    // Validate Full Name
    if (empty($data['name'])) {
        $errors['name_err'] = "Full Name is required.";
    } else {
        $data['name'] = htmlspecialchars($data['name']);
    }

    // Validate Email Address
    if (empty($data['email'])) {
        $errors['email_err'] = "Email Address is required.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_err'] = "Invalid email format.";
    } else {
        $data['email'] = htmlspecialchars($data['email']);
    }

    // Validate Phone Number
    if (empty($data['phone'])) {
        $errors['phone_err'] = "Phone Number is required.";
    } else {
        $data['phone'] = htmlspecialchars($data['phone']);
    }

    // Validate Password
    if (empty($data['password_login'])) {
        $errors['password_login_err'] = "Password is required.";
    } elseif (strlen($data['password_login']) < 6) {
        $errors['password_login_err'] = "Password must be at least 6 characters.";
    }

    // Validate Confirm Password
    if (empty($data['confirm_password'])) {
        $errors['confirm_password_err'] = "Please confirm your password.";
    } elseif ($data['confirm_password'] !== $data['password_login']) {
        $errors['confirm_password_err'] = "Passwords do not match.";
    }

    // Validate Terms and Conditions
    if (empty($data['terms_checkbox']) && !isset($data['terms_checkbox'])) {
        $errors['terms_err'] = "You must agree to the terms and conditions.";
    }

    return [$data, $errors];
}
?>
