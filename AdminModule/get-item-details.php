<?php
include '../conn.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8'); // Ensure JSON output

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Prepare SQL query
    $stmt = $conn->prepare("
        SELECT 
            mi.id,
            mi.name, 
            mi.picture, 
            mi.status, 
            mi.created, 
            mi.OfficeCollectionCentre, 
            u.name AS contributor_name, 
            u.picture AS contributor_picture 
        FROM 
            missing_items mi
        INNER JOIN 
            users u 
        ON 
            mi.Contributor_id = u.id
        WHERE 
            mi.id = ?
    ");

    if (!$stmt) {
        echo json_encode(["error" => "Failed to prepare statement", "sql_error" => $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Ensure binary data is encoded properly
        $row['picture'] = base64_encode($row['picture']);
        $row['contributor_picture'] = base64_encode($row['contributor_picture']);

        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Item not found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
