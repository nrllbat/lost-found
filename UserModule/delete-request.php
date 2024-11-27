<?php
include '../conn.php';
header('Content-Type: application/json');

// Retrieve and decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['claim_id'], $data['item_id'])) {
    $claim_id = intval($data['claim_id']);
    $item_id = intval($data['item_id']);

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Delete the request from the claim_item table
        $stmt1 = $conn->prepare("DELETE FROM claim_item WHERE id = ?");
        $stmt1->bind_param("i", $claim_id);
        $stmt1->execute();

        // Update the status of the item in the missing_items table
        $stmt2 = $conn->prepare("UPDATE missing_items SET status = 'missing' WHERE id = ?");
        $stmt2->bind_param("i", $item_id);
        $stmt2->execute();

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
