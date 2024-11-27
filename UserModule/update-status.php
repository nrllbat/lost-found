<?php
include '../conn.php';
header('Content-Type: application/json');
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the data from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

if (isset($data['item_id'])) {
    $item_id = intval($data['item_id']);
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Begin a transaction to ensure both operations succeed together
    $conn->begin_transaction();

    try {
        // Update the status of the item to 'pending'
        $stmt1 = $conn->prepare("UPDATE missing_items SET status = 'pending' WHERE id = ?");
        if (!$stmt1) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt1->bind_param("i", $item_id);
        if (!$stmt1->execute()) {
            throw new Exception("Execute failed: " . $stmt1->error);
        }

        // Insert a record into the claim_item table
        $stmt2 = $conn->prepare("INSERT INTO claim_item (item_id, user_id) VALUES (?, ?)");
        if (!$stmt2) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt2->bind_param("ii", $item_id, $user_id);
        if (!$stmt2->execute()) {
            throw new Exception("Execute failed: " . $stmt2->error);
        }

        // Commit the transaction
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
}
?>
