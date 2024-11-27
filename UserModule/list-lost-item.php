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
    <title>User Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: #f5f5f5;
        }

        .hero {
            background: linear-gradient(to right, #00b09b, #96c93d);
            color: white;
            padding: 50px 20px;
            text-align: center;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin: 0;
        }

        .hero p {
            margin-top: 20px;
            font-size: 1.5rem;
        }

        .btn {
            padding: 5px 10px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container">
        <h2>Missing Items</h2>

        <table>
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT id, name, picture, OfficeCollectionCentre FROM missing_items WHERE status = 'missing'");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='Picture'><img src='data:image/jpeg;base64," . base64_encode($row['picture']) . "' alt='Item Image' width='100'></td>
                            <td data-label='Name'>" . htmlspecialchars($row['name']) . "</td>
                            <td data-label='Actions'>
                                <button class='btn' onclick='requestClaim(" . $row['id'] . ")'>Request Claim</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function requestClaim(itemId) {
            if (confirm('Are you sure you want to claim this item?')) {
                // Send AJAX request to update the status and insert into the claim_item table
                fetch('update-status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            item_id: itemId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Request submitted successfully!');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to submit the request. Please try again.');
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