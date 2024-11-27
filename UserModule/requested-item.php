<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "User") {
    header("Location: ../login/login.php");
    exit();
}

include '../conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requested Items</title>

    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: #f5f5f5;
        }

        .btn-delete {
            padding: 5px 10px;
            color: white;
            background-color: #e63946;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-delete:hover {
            background-color: #d62828;
        }
    </style>
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container">
        <h2>Requested Items</h2>

        <table>
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Claim Centre Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_id = $_SESSION['user_id'];

                // SQL to retrieve data from claim_item, missing_items, and users
                $sql = "
                    SELECT 
                        ci.id AS claim_id,
                        mi.id AS item_id,
                        mi.picture, 
                        mi.name, 
                        mi.OfficeCollectionCentre, 
                        mi.status 
                    FROM 
                        claim_item ci
                    INNER JOIN 
                        missing_items mi 
                    ON 
                        ci.item_id = mi.id
                    INNER JOIN 
                        users u 
                    ON 
                        ci.user_id = u.id
                    WHERE 
                        ci.user_id = ? 
                    AND 
                        mi.status = 'pending'
                ";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='Picture'><img src='data:image/jpeg;base64," . base64_encode($row['picture']) . "' alt='Item Image' width='100'></td>
                            <td data-label='Name'>" . htmlspecialchars($row['name']) . "</td>
                            <td data-label='Claim Centre Location'>" . htmlspecialchars($row['OfficeCollectionCentre']) . "</td>
                            <td data-label='Status'>" . htmlspecialchars($row['status']) . "</td>
                            <td data-label='Action'>
                                <button class='btn-delete' onclick='deleteRequest(" . $row['claim_id'] . ", " . $row['item_id'] . ")'>Delete Request</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function deleteRequest(claimId, itemId) {
            if (confirm("Are you sure you want to delete this request?")) {
                fetch('delete-request.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            claim_id: claimId,
                            item_id: itemId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Request deleted successfully!');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to delete the request. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }
    </script>

    <script src="../include/idle-logout.js"></script>
</body>

</html>