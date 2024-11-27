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
  $role = trim($_POST['role']);

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
          window.location.href = '../login/login.php'; // Redirect to login page
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
  <div class="wrapper">
    <header>Registration Form</header>
    <form action="registration.php" method="POST">
      <!-- Name Field -->
      <div class="field name">
        <div class="input-area">
          <input type="text" name="name" placeholder="Full Name" required>
          <i class="icon fas fa-user"></i>
        </div>
      </div>
      <!-- Username Field -->
      <div class="field username">
        <div class="input-area">
          <input type="text" name="username" placeholder="Username" required>
          <i class="icon fas fa-user-tag"></i>
        </div>
      </div>
      <!-- Email Field -->
      <div class="field email">
        <div class="input-area">
          <input type="email" name="email" placeholder="Email Address" required>
          <i class="icon fas fa-envelope"></i>
        </div>
      </div>
      <!-- Password Field -->
      <div class="field password">
        <div class="input-area">
          <input type="password" name="password" placeholder="Password" required>
          <i class="icon fas fa-lock"></i>
        </div>
      </div>
      <!-- Phone Number Field -->
      <div class="field phone">
        <div class="input-area">
          <input type="text" name="phone" placeholder="Phone Number" required>
          <i class="icon fas fa-phone"></i>
        </div>
      </div>
      <!-- Role Dropdown -->
      <div class="field role">
        <div class="input-area">
          <select name="role" required>
            <option value="User">Normal User</option>
            <option value="Contributor">Contributor</option>
          </select>
          <i class="icon fas fa-users"></i>
        </div>
      </div>
      <input type="submit" value="Register">
    </form>
    <div class="sign-txt">Already a member? <a href="../login/login.php">Login now</a></div>
  </div>
</body>

</html>
