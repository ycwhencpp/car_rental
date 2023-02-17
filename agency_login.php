<?php
session_start(); // Start the session
require_once 'db.php'; // estblishing the conection.


// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if the agency exists
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND type = 'agency'");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  // If the agency exists, log them in
  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_type'] = 'agency';
    header('Location: index.php'); // Redirect to the home page
  } else {
    $error = "Invalid email or password.";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Car Rental Agency Login</title>
</head>
<body>
  <h1>Car Rental Agency Login</h1>
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
</html
