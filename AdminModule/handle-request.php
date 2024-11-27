<?php
include '../conn.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['claim_id'], $data['item_id'], $data['action'])) {
    $claim_id = intval($data['claim_id']);
    $item_id = intval($data['item_id']);
    $action = $data['action'];

    $conn->begin_transaction();

    try {
        if ($action === 'accept') {
            // Update status to 'found' in missing_items
            $stmt = $conn->prepare("UPDATE missing_items SET status = 'found' WHERE id = ?");
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
        } elseif ($action === 'reject') {
            // Delete from claim_item
            $stmt1 = $conn->prepare("DELETE FROM claim_item WHERE id = ?");
            $stmt1->bind_param("i", $claim_id);
            $stmt1->execute();

            // Update status back to 'missing' in missing_items
            $stmt2 = $conn->prepare("UPDATE missing_items SET status = 'missing' WHERE id = ?");
            $stmt2->bind_param("i", $item_id);
            $stmt2->execute();
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            exit();
        }

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
}
?>
