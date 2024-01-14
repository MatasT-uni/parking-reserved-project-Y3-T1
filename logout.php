<?php
session_start();

// Get the redirect URL from the query parameters, default to home.php
$redirect_url = $_GET['redirect'] ?? 'homepage.php';

// Unset all of the session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the specified URL
header('location: ' . $redirect_url);
?>