<?php
session_start();
include '../conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "Admin") {
    header("Location: ../login/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM missing_items WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href = 'manage-item.php';</script>";
    } else {
        echo "<script>alert('Error deleting item.'); window.location.href = 'manage-item.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: manage-missing-items.php");
    exit();
}
?>
