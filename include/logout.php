<?php
if (!isset($_SESSION)) {
    session_start();
}

// Destroy the session and redirect to the login page
session_destroy();
header("Location: ../login/login-registration.php");
exit();
?>
