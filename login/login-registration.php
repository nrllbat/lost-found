
<!DOCTYPE html>
<html>

<head>
  <title>Login/Registration</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>

<body>
  <div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">

    <div class="signup">
      <form action="register-process.php" method="POST">
        <label for="chk" aria-hidden="true">Sign up</label>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="phone" placeholder="Phone Number" required>

        <button type="submit">Sign up</button>
      </form>
    </div>

    <div class="login">
      <form action="login-process.php" method="POST">
        <label for="chk" aria-hidden="true">Login</label>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>

</html>