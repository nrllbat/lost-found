<?php
// Include database connection
include '../conn.php'; // Ensure the path to the database connection file is correct

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form values
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Check for empty fields
  if (empty($username) || empty($password)) {
    echo "<script>alert('Please fill in all fields.');</script>";
  } else {
    // Query to find the user by email
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Fetch user data
      $user = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === "Contributor") {
          header("Location: ../homepage/contributor-homepage.php"); // Contributor's homepage
        } elseif ($user['role'] === "User") {
          header("Location: ../homepage/user-homepage.php"); // Normal User's homepage
        } else {
          header("Location: ../homepage/admin-homepage.php"); // Admin's homepage or default
        }
        exit();
      } else {
        echo "<script>alert('Incorrect password.');</script>";
      }
    } else {
      echo "<script>alert('No account found with that email.');</script>";
    }

    // Close the statement
    $stmt->close();
  }

  // Close the database connection
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
  <div class="wrapper">
    <header>Login Form</header>
    <form action="login.php" method="POST">
      <!-- Email Field -->
      <div class="field email">
        <div class="input-area">
          <input type="text" name="username" placeholder="Username" required>
          <i class="icon fas fa-user-tag"></i>
        </div>
      </div>
      <!-- Password Field -->
      <div class="field password">
        <div class="input-area">
          <input type="password" name="password" placeholder="Password" required>
          <i class="icon fas fa-lock"></i>
        </div>
      </div>
      <div class="pass-txt"><a href="#">Forgot password?</a></div>
      <input type="submit" value="Login">
    </form>
    <div class="sign-txt">Not yet a member? <a href="../registration/registration.php">Signup now</a></div>
  </div>
</body>

</html>