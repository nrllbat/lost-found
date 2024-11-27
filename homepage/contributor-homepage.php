<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "Contributor") {
    header("Location: ../login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributor Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: #f5f5f5;
        }

        .hero {
            background: linear-gradient(to right, #6a0572, #a4508b);
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
        <h1>Welcome Contributor, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p>Your creativity shapes the world.</p>
        <p class="quote">"Creativity is intelligence having fun." - Albert Einstein</p>
    </div>

    <div class="container">
        <h2>Dashboard Insights</h2>
        <p>Use the navigation bar above to create, manage, and share your content.</p>
    </div>

    <script src="../include/idle-logout.js"></script>
</body>

</html>