<?php

include 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard</title>

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
            padding: 8px 12px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 8px;
        }

        table th {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
        }

        table td {
            padding: 12px;
            text-align: center;
            background-color: #f9f9f9;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        table tr:hover {
            background-color: #eaf7ff;
        }

        table img {
            border-radius: 8px;
        }

        @media screen and (max-width: 768px) {
            table {
                border: 0;
            }

            table caption {
                font-size: 1.3em;
            }

            table thead {
                display: none;
            }

            table tr {
                display: block;
                margin-bottom: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                background-color: white;
                border-radius: 8px;
                overflow: hidden;
            }

            table td {
                display: block;
                text-align: right;
                font-size: 0.8em;
                border-bottom: 1px solid #ddd;
                padding: 10px;
            }

            table td::before {
                content: attr(data-label);
                float: left;
                text-transform: uppercase;
                font-weight: bold;
            }

            table td:last-child {
                border-bottom: 0;
            }
        }
    </style>
</head>

<body>
    <?php include 'include/navbar.php'; ?>

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
                $stmt = $conn->prepare("SELECT id, name, picture FROM missing_items WHERE status = 'missing'");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td data-label='Picture'><img src='data:image/jpeg;base64," . base64_encode($row['picture']) . "' alt='Item Image' width='100'></td>
                            <td data-label='Name'>" . htmlspecialchars($row['name']) . "</td>
                            <td data-label='Actions'>
                                <button class='btn' onclick='requestLogin()'>Request Claim</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function requestLogin() {
            alert('You need to log in to request this claim. Redirecting to the login page.');
            window.location.href = 'login/login-registration.php';
        }
    </script>

    <script src="../include/idle-logout.js"></script>
</body>

</html>
