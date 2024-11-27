<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "Admin") {
    header("Location: ../login/login.php");
    exit();
}
include '../conn.php';

// Fetch data for the charts
// Count of missing items per Office Collection Centre
$officeCollectionData = $conn->query("
    SELECT OfficeCollectionCentre, COUNT(*) AS count
    FROM missing_items
    GROUP BY OfficeCollectionCentre
")->fetch_all(MYSQLI_ASSOC);

// Contributor Distribution of Missing Items
$contributorData = $conn->query("
    SELECT u.name AS contributor_name, COUNT(mi.id) AS count
    FROM missing_items mi
    INNER JOIN users u ON mi.Contributor_id = u.id
    GROUP BY mi.Contributor_id
")->fetch_all(MYSQLI_ASSOC);

// Status Distribution of Missing Items
$statusData = $conn->query("
    SELECT status, COUNT(*) AS count
    FROM missing_items
    GROUP BY status
")->fetch_all(MYSQLI_ASSOC);

// Count of Users Based on Role
$roleData = $conn->query("
    SELECT role, COUNT(*) AS count
    FROM users
    GROUP BY role
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .container {
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1d3557;
        }

        .chart-container {
            margin: 20px auto;
            width: 80%;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        canvas {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <?php include '../include/navbar.php'; ?>
    <div class="container">
        <h2>Admin Dashboard - Charts and Analytics</h2>

        <!-- Chart: Missing Items by Office Collection Centre -->
        <div class="chart-container">
            <h3>Missing Items by Office Collection Centre</h3>
            <canvas id="officeChart"></canvas>
        </div>

        <!-- Chart: Contributor Distribution -->
        <div class="chart-container">
            <h3>Contributor Distribution</h3>
            <canvas id="contributorChart"></canvas>
        </div>

        <!-- Chart: Status Distribution of Missing Items -->
        <div class="chart-container">
            <h3>Status Distribution of Missing Items</h3>
            <canvas id="statusChart"></canvas>
        </div>

        <!-- Chart: User Roles -->
        <div class="chart-container">
            <h3>Users Based on Role</h3>
            <canvas id="roleChart"></canvas>
        </div>
    </div>

    <script>
        // Data for "Missing Items by Office Collection Centre"
        const officeData = {
            labels: <?= json_encode(array_column($officeCollectionData, 'OfficeCollectionCentre')) ?>,
            datasets: [{
                label: 'Missing Items',
                data: <?= json_encode(array_column($officeCollectionData, 'count')) ?>,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        // Chart: Office Collection Centre
        const officeCtx = document.getElementById('officeChart').getContext('2d');
        new Chart(officeCtx, {
            type: 'bar',
            data: officeData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Data for "Contributor Distribution"
        const contributorData = {
            labels: <?= json_encode(array_column($contributorData, 'contributor_name')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($contributorData, 'count')) ?>,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40'],
                borderWidth: 1
            }]
        };

        // Chart: Contributor Distribution
        const contributorCtx = document.getElementById('contributorChart').getContext('2d');
        new Chart(contributorCtx, {
            type: 'pie',
            data: contributorData,
            options: {
                responsive: true
            }
        });

        // Data for "Status Distribution of Missing Items"
        const statusData = {
            labels: <?= json_encode(array_column($statusData, 'status')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($statusData, 'count')) ?>,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                borderWidth: 1
            }]
        };

        // Chart: Status Distribution
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: statusData,
            options: {
                responsive: true
            }
        });

        // Data for "User Roles"
        const roleData = {
            labels: <?= json_encode(array_column($roleData, 'role')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($roleData, 'count')) ?>,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'],
                borderWidth: 1
            }]
        };

        // Chart: User Roles
        const roleCtx = document.getElementById('roleChart').getContext('2d');
        new Chart(roleCtx, {
            type: 'pie',
            data: roleData,
            options: {
                responsive: true
            }
        });
    </script>

    <script src="../../include/idle-logout.js"></script>
</body>

</html>