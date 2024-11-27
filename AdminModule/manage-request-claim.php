<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "Admin") {
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
    <title>Manage Request Items</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
        }

        table th {
            background-color: #457b9d;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        table td {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        img {
            border-radius: 5px;
            max-width: 100px;
        }

        .btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-accept {
            background-color: #28a745;
        }

        .btn-accept:hover {
            background-color: #218838;
        }

        .btn-reject {
            background-color: #dc3545;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }

        /* Responsive Table */
        @media screen and (max-width: 768px) {

            table,
            table thead,
            table tbody,
            table th,
            table td,
            table tr {
                display: block;
            }

            table th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            table td {
                position: relative;
                padding-left: 50%;
                text-align: left;
            }

            table td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
                text-transform: uppercase;
                color: #666;
            }

            img {
                max-width: 80px;
            }
        }
    </style>
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container">
        <h2>Manage Request Items</h2>

        <table>
            <thead>
                <tr>
                    <th>Item Picture</th>
                    <th>Item Name</th>
                    <th>User Picture</th>
                    <th>User Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL to retrieve data from claim_item, missing_items, and users
                $sql = "
                    SELECT 
                        ci.id AS claim_id,
                        mi.id AS item_id,
                        mi.picture AS item_picture, 
                        mi.name AS item_name, 
                        u.picture AS user_picture, 
                        u.name AS user_name 
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
                        mi.status = 'pending'
                ";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();

                // Default profile picture URL
                $default_picture = "https://static.vecteezy.com/system/resources/thumbnails/030/504/836/small_2x/avatar-account-flat-isolated-on-transparent-background-for-graphic-and-web-design-default-social-media-profile-photo-symbol-profile-and-people-silhouette-user-icon-vector.jpg";

                while ($row = $result->fetch_assoc()) {
                    $item_picture = $row['item_picture']
                        ? 'data:image/jpeg;base64,' . base64_encode($row['item_picture'])
                        : $default_picture;

                    $user_picture = $row['user_picture']
                        ? 'data:image/jpeg;base64,' . base64_encode($row['user_picture'])
                        : $default_picture;

                    echo "<tr>
                            <td data-label='Item Picture'><img src='" . htmlspecialchars($item_picture) . "' alt='Item Picture'></td>
                            <td data-label='Item Name'>" . htmlspecialchars($row['item_name']) . "</td>
                            <td data-label='User Picture'><img src='" . htmlspecialchars($user_picture) . "' alt='User Picture'></td>
                            <td data-label='User Name'>" . htmlspecialchars($row['user_name']) . "</td>
                            <td data-label='Actions'>
                                <button class='btn btn-accept' onclick='handleAction(" . $row['claim_id'] . ", " . $row['item_id'] . ", \"accept\")'>Accept</button>
                                <button class='btn btn-reject' onclick='handleAction(" . $row['claim_id'] . ", " . $row['item_id'] . ", \"reject\")'>Reject</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function handleAction(claimId, itemId, action) {
            const confirmation = confirm(`Are you sure you want to ${action} this request?`);
            if (!confirmation) return;

            fetch('handle-request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ claim_id: claimId, item_id: itemId, action: action }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Request ${action}ed successfully!`);
                    location.reload();
                } else {
                    alert(`Failed to ${action} the request. Please try again.`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>

<script src="../../include/idle-logout.js"></script>
</body>

</html>
