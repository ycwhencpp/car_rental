
<?php
include 'db_connection_config.php';

// Connect to database
// $conn = mysqli_connect("localhost", "username", "password", "database_name");

if (isset($_POST['login'])) {
	// Get email and password from form
	$email = $_POST['email'];
	$password = $_POST['password'];

	// Check if user is customer or car rental agency
	$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
	$result = mysqli_query($conn, $query);
	$user = mysqli_fetch_assoc($result);

	if ($user) {
		// Redirect to appropriate dashboard
		if ($user['user_type'] == 'customer') {
			header("Location: customer_dashboard.php");
			exit();
		} else if ($user['user_type'] == 'agency') {
			header("Location: agency_dashboard.php");
			exit();
		}
	} else {
		echo "Invalid email or password";
	}
}
?>
