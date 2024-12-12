<?php
if (!isset($_SESSION)) {
    session_start();
}

// Retrieve user's role and name
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest';
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>

<style>
    /* Global Styles */
    body {
        margin: 0;
        font-family: 'Arial', sans-serif;
    }

    /* Navbar Styles */
    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #333;
        padding: 10px 20px;
        color: #fff;
        position: relative;
    }

    .navbar .logo {
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
        color: #fff;
    }

    .navbar ul {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
        padding: 0;
        align-items: center;
    }

    .navbar ul li {
        position: relative;
    }

    .navbar ul li a {
        text-decoration: none;
        color: #fff;
        font-size: 16px;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background 0.3s ease;
    }

    .navbar ul li a:hover {
        background: #444;
    }

    /* Dropdown Menu Styles */
    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #444;
        min-width: 160px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        display: block;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .dropdown-content a:hover {
        background-color: #555;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .logout-btn {
        background: #e63946;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .logout-btn:hover {
        background: #d62828;
    }

    .hamburger {
        display: none;
        font-size: 24px;
        cursor: pointer;
        color: white;
    }

    /* Hamburger Menu */
    @media (max-width: 768px) {
        .navbar ul {
            display: none;
            /* Hide menu by default */
            flex-direction: column;
            gap: 10px;
            position: absolute;
            top: 60px;
            left: 0;
            background: #333;
            width: 100%;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar ul.active {
            display: flex;
            /* Show menu when active */
        }

        .hamburger {
            display: block;
        }

        .dropdown-content {
            position: static;
            box-shadow: none;
        }
    }
</style>

<nav class="navbar">
    <div class="logo"><?php echo htmlspecialchars($user_role); ?> Dashboard</div>
    <div class="hamburger" onclick="toggleMenu()">&#9776;</div> <!-- Hamburger Icon -->
    <ul id="nav-menu">

        <!-- Admin Navbar -->
        <?php if ($user_role === "Admin") { ?>
            <li><a href="../homepage/admin-homepage.php">Home</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Manage Item</a>
                <div class="dropdown-content">
                    <a href="../AdminModule/manage-item.php">List Item</a>
                    <a href="../AdminModule/manage-request-claim.php">Requested claim</a>
                </div>
            </li>
            <li><a href="../AdminModule/admin-report.php">Admin Report</a></li>
            <li>
            <form action="../include/logout.php" method="POST" style="margin: 0;">
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </li>

            <!-- Normal User Navbar -->
        <?php } elseif ($user_role === "User") { ?>
            <li><a href="../homepage/user-homepage.php">Home</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Lost Item</a>
                <div class="dropdown-content">
                    <a href="../UserModule/list-lost-item.php">List Item</a>
                    <a href="../UserModule/requested-item.php">Requested Item</a>
                </div>

            </li>
            <li>
                <form action="../include/logout.php" method="POST" style="margin: 0;">
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </li>

        <?php } else { ?>
            <li><a href="../login/login-registration.php">Login</a></li>
        <?php } ?>
    </ul>
</nav>

<script>
    // Function to toggle the menu
    function toggleMenu() {
        const navMenu = document.getElementById('nav-menu');
        navMenu.classList.toggle('active');
    }
</script>