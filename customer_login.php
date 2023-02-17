<?php
session_start(); // Start the session
require('config.php'); // Include the database connection file

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if the customer exists
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  // If the customer exists, log them in
  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_type'] = 'customer';
    header('Location: index.php'); // Redirect to the home page
  } else {
    $error = "Invalid email or password.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Login</title>
</head>
<body>
  <h1>Customer Login</h1>
  <?php if (isset($error)) { ?>
    <div style="color: red;"><?php echo $error; ?></div>
  <?php } ?>
  <form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>
    <br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
