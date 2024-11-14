<?php
session_start();

// Function to generate a CSRF token
function generateCSRFToken() {
    // Generate a random token
    $token = bin2hex(random_bytes(32));
    // Store the token in the session
    $_SESSION['csrf_token'] = $token;
    return $token;
}

// Function to validate a CSRF token
function validateCSRFToken($token) {
    // Check if the token in the session matches the token provided
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Optionally, you can clear the CSRF token after use
function clearCSRFToken() {
    unset($_SESSION['csrf_token']);
}
?>