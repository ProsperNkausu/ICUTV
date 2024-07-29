<?php
session_start();

// Check if a user is logged in and clear their session
if (isset($_SESSION['user_loggedin']) && $_SESSION['user_loggedin']) {
    // Unset user session variables
    unset($_SESSION['user_loggedin']);
    unset($_SESSION['user_fullname']);
}

// Check if an admin is logged in and clear their session
if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin']) {
    // Unset admin session variables
    unset($_SESSION['admin_loggedin']);
    unset($_SESSION['admin_username']);
}

// Destroy the session
session_destroy();

// Redirect to the homepage or login page
header("Location: index.php"); // Adjust the location if necessary
exit();
?>