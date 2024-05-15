<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password are provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Validate the username and password (you should replace this with your validation logic)
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Example validation (replace this with your own)
        if ($username === 'test' && $password === 'test') {
            // Authentication successful, set session variable
            $_SESSION['username'] = $username;

            // Redirect to the dashboard or any other page
            header("Location: dashboard.php");
            exit();
        } else {
            // Authentication failed, redirect back to login page with error
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    }
}

// Redirect to login page if accessed directly
header("Location: login.php");
exit();
?>