<?php
    session_start();

    // Clear admin session
    unset($_SESSION['admin']);

    header('Location: admin_login.php');
    exit();
?>
