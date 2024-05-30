<?php
    session_start();
    include 'config.php';

    // Check if the admin is logged in
    if (!isset($_SESSION['admin_username'])) {
        header('Location: admin_login.php');
        exit();
    }

    // Check if the user ID is provided
    if (!isset($_GET['id'])) {
        header('Location: admin_users.php');
        exit();
    }

    // Get the user ID
    $user_id = $_GET['id'];

    // Delete the user
    $query = "DELETE FROM users WHERE id = '$user_id'";
    mysqli_query($conn, $query);

    // Redirect back to the users page
    header('Location: admin_users.php');
?>
