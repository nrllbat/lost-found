<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "User") {
    header("Location: ../login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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

        .quote {
            margin-top: 30px;
            font-style: italic;
            font-size: 1.2rem;
            color: #f5f5f5;
        }

        .container {
            padding: 20px;
            text-align: center;
        }

        .container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="hero">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Your journey begins here.</p>
        <p class="quote">"The only limit to our realization of tomorrow is our doubts of today." - Franklin D. Roosevelt</p>
    </div>

    <div class="container">
        <h2>Dashboard Insights</h2>
        <p>Use the navigation bar above to explore content, update your profile, and more.</p>
    </div>

    <script src="../../include/idle-logout.js"></script>

</body>

</html>
