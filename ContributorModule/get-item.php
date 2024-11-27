<?php
include '../conn.php';
$id = $_GET['id']; 
$stmt = $conn->prepare("SELECT id, name, OfficeCollectionCentre FROM missing_items WHERE id = ? ");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}
$stmt->close();
$conn->close();
?>
