<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Unset the user session variables
    unset($_SESSION['username']);
    
    // Optionally, destroy the session for added security
    session_destroy();

    // Redirect to the user login page or any other desired location
    header('Location: login.php');
    exit();
} else {
    // If no user is logged in, redirect to a default location
    header('Location: index.php');
    exit();
}
?>
