<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Fetch form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
    $type = mysqli_real_escape_string($conn,$_POST['type']);

    // hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare and execute SQL query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $type);
    $stmt->execute();
    
    // Check for successful registration
    if ($stmt->affected_rows > 0) {
        // Redirect to login page
        header("Location: login.php");
        exit;
    } else {
        // registration failed, display an error message
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Register User</h2>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="hidden" name="type" value="customer">
        <input type="submit" value="Register">
        <?php if(!empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $error; ?>
            </div>
        <?php } ?>
    </form>
    
    <h2>Register Agency</h2>
    <form action="" method="post">
        <label for="name">Company Name:</label>
        <input type="text" id="name" name="name"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="hidden" name="type" value="agency">
        <input type="submit" value="Register">
        <?php if(!empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $error; ?>
            </div>
        <?php } ?>
    </form>
</body>
</html>






