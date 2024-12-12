<?php
// Include database connection
include '../conn.php'; // Ensure the path to the database connection file is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form values
  $name = trim($_POST['name']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password for security
  $phone = trim($_POST['phone']);
  $role = 'User'; // Automatically set role to "User"

  // Check for empty fields
  if (empty($name) || empty($username) || empty($email) || empty($_POST['password']) || empty($phone)) {
    echo "<script>alert('Please fill in all fields.');</script>";
  } else {
    // Insert all users into the 'users' table with their role
    $sql = "INSERT INTO users (name, username, password, email, phone_number, role) VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("ssssss", $name, $username, $password, $email, $phone, $role);
    if ($stmt->execute()) {
      echo "<script>
          alert('Registration successful!');
          window.location.href = '../login/login-registration.php'; // Redirect to login page
      </script>";
    } else {
      echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
  }

  // Close the database connection
  $conn->close();
}
?>
