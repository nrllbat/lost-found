<?php
session_start();
include '../conn.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Check user role from session
$user_role = $_SESSION['role'] ?? null;
$user_name = $_SESSION['user_name'] ?? null;
// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $picture);
$stmt->fetch();
$stmt->close();

// Handle profile picture update
if (isset($_POST['update_picture'])) {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $new_picture = file_get_contents($_FILES['profile_picture']['tmp_name']);
        $update_sql = "UPDATE users SET picture = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $new_picture, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Profile picture updated successfully!'); window.location.href = 'manage-user.php';</script>";
        } else {
            echo "<script>alert('Error updating profile picture. Please try again.');</script>";
        }
        $stmt->close();
    }
}

// Handle user information update
if (isset($_POST['update_info'])) {
    $new_fullname = $_POST['fullname'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $new_gender = $_POST['gender'];

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE users SET name = ?, email = ?, password = ?, gender = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $new_fullname, $new_email, $hashed_password, $new_gender, $user_id);
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ?, gender = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $new_fullname, $new_email, $new_gender, $user_id);
    }
    if ($stmt->execute()) {
        $_SESSION['fullname'] = $new_fullname;
        echo "<script>alert('Profile information updated successfully!'); window.location.href = 'manage-user.php';</script>";
    } else {
        echo "<script>alert('Error updating profile information. Please try again.');</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-image {
            max-width: 100%;
            max-height: 150px;
            border-radius: 50%;
            border: 2px solid #ddd;
            padding: 5px;
            object-fit: cover;
        }

        .card-custom {
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .upload-section {
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row g-4">

            <!-- Profile Picture Section -->
            <div class="col-12 col-md-4">
                <div class="card-custom text-center">
                    <h4 class="mb-3">Profile Picture</h4>
                    <?php if ($picture): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($picture); ?>" alt="Profile Picture" class="profile-image mb-3">
                    <?php else: ?>
                        <img src="https://www.shutterstock.com/image-vector/user-icon-trendy-flat-style-600nw-1697898655.jpg" alt="Default Profile Picture" class="profile-image mb-3">
                    <?php endif; ?>
                    <form action="manage-user.php" method="POST" enctype="multipart/form-data">
                        <input type="file" class="form-control mb-3" name="profile_picture" accept="image/*">
                        <button type="submit" name="update_picture" class="btn btn-primary w-100">Update Profile Picture</button>
                    </form>
                </div>
            </div>

            <!-- User Information Section -->
            <div class="col-12 col-md-8">
                <div class="card-custom">
                    <h4 class="mb-3">User Information</h4>
                    <form action="manage-user.php" method="POST">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <button type="submit" name="update_info" class="btn btn-success w-100 w-md-auto">Save Changes</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../include/idle-logout.js"></script>
</body>

</html>