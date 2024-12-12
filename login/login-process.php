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
        if ($user['role'] === "User") {
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