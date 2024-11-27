<?php
if (!isset($_SESSION)) {
    session_start();
}

// Destroy the session and redirect to login page
session_destroy();
header("Location: ../login/login.php");
exit();
