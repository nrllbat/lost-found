<?php
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $OfficeCollectionCentre = $_POST['OfficeCollectionCentre'];

    // Handle file upload if present
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $picture = file_get_contents($_FILES['picture']['tmp_name']);
        $stmt = $conn->prepare("UPDATE missing_items SET name = ?, OfficeCollectionCentre = ?, picture = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $OfficeCollectionCentre, $picture, $id);
    } else {
        $stmt = $conn->prepare("UPDATE missing_items SET name = ?, OfficeCollectionCentre = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $OfficeCollectionCentre, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Item updated successfully!'); window.location.href = 'manage-item.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>