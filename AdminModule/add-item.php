<?php
include '../conn.php';
session_start(); // Ensure session is started

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $OfficeCollectionCentre = $_POST['OfficeCollectionCentre'];
    $contributor_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Check if picture is uploaded
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $picture = file_get_contents($_FILES['picture']['tmp_name']);
    } else {
        die("Error: Unable to upload the picture.");
    }

    $status= 'missing';

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO missing_items (name, OfficeCollectionCentre, picture,status, Contributor_id, created) VALUES (?, ?, ?,?, ?, NOW())");

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssss", $name, $OfficeCollectionCentre, $picture,$status, $contributor_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!'); window.location.href = 'manage-item.php';</script>";
    } else {
        die("SQL Error: " . $stmt->error);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
