<?php
session_start(); // Start the session
require_once 'db.php'; // estblishing the conection.

if($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
   
  if($result->num_rows==1)
  {
    $row=$result->fetch_assoc();
    $stored_password=$row['password'];

    if(password_verify($password, $stored_password))
        {
            if($row['type']=='customer')
            {
                session_start();
                $_SESSION['email']=$email;
                $_SESSION['user_id']=$row['id'];
                $_SESSION['user_type']='customer';
                session_write_close();
                header('Location: index.php');
            }
            else
            {
                session_start();
                $_SESSION['email']=$email;
                $_SESSION['user_id']=$row['id'];
                $_SESSION['user_type']='agency';
                session_write_close();
                header('Location: index.php');
            }
      } 
      else{
        $error = "Your Email or Password is invalid";
      }
  }
  else{
      $error = "User not found. Please register";
  }
}
?>

<!DOCTYPE html>
<html>
   <head>
      <title>Login Page</title>
      <link href="style.css" rel="stylesheet" type="text/css">
   </head>
   <body>
      <div id="frm">
         <h2>Login</h2>
         <form action="" method="POST">
            <p>
               <label>Email:</label>
               <input type="text" name="email" required>
            </p>
            <p>
               <label>Password:</label>
               <input type="password" name="password" required>
            </p>
            <p>
               <input type="submit" name="submit" value="Login">
            </p>
            <?php if(!empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $error; ?>
            </div>
            <?php } ?>
         </form>
      </div>
   </body>
</html>
