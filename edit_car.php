<?php
// Check if user is an agency and has permission to access this page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
  header('Location: login.php');
  exit();
}

// Connect to the database
require_once 'db.php';
$errors = array();

// Check if a car_id is specified in the URL
if (!isset($_GET['car_id'])) {
  header('Location: index.php');
  exit();
}

// Get the car information from the database
$car_id = mysqli_real_escape_string($conn, $_GET['car_id']);
if(!$car_id){
  header('Location: index.php');
  exit();
}
$agency_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$query = "SELECT * FROM cars WHERE id = $car_id AND agency_id = $agency_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
  // Car not found or does not belong to current agency user
  header('Location: index.php');
  exit();
}


$car = mysqli_fetch_assoc($result);

// Handle form submission to update the car information
if (isset($_POST['submit'])) {
  $model = mysqli_real_escape_string($conn, $_POST['model']);
  $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle_number']);
  $seating_capacity = mysqli_real_escape_string($conn, $_POST['seating_capacity']);
  $rent_per_day = mysqli_real_escape_string($conn, $_POST['rent_per_day']);
  $url = mysqli_real_escape_string($conn, $_POST['url']);

  // Check for any validation errors
  if (!$model) {
    $errors[] = 'Please enter a vehicle model';
  }
  if (!$vehicle_number) {
    $errors[] = 'Please enter a vehicle number';
  }
  if (!$seating_capacity) {
    $errors[] = 'Please enter a valid seating capacity';
  }
  if (!$rent_per_day) {
    $errors[] = 'Please enter a valid rent per day';
  }

  if (count($errors) == 0) {
    // Update the car information in the database
    $query = "UPDATE cars SET model = ?, vehicle_number = ?, seating_capacity = ?, rent_per_day = ?, image_url = ? WHERE id = ? AND agency_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
      die('Failed to prepare statement');
    }
    mysqli_stmt_bind_param($stmt, 'ssddssi', $model, $vehicle_number, $seating_capacity, $rent_per_day, $url, $car_id, $agency_id);
    if (mysqli_stmt_execute($stmt)) {
      // Success
      header('Location: index.php');
      exit;
    } else {
      // Error
      echo "Error: " . mysqli_error($conn);
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Car</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
    <body>
        <h1>Edit Car</h1>

        <form method="post">
        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
        <label for="model">Vehicle model:</label>
        <input type="text" id="model" name="model" value="<?php echo $car['model']; ?>" required>

        <label for="vehicle_number">Vehicle number:</label>
        <input type="text" id="vehicle_number" name="vehicle_number" value="<?php echo $car['vehicle_number']; ?>" required>
        <label for="seating_capacity">Seating capacity:</label>
        <input type="number" id="seating_capacity" name="seating_capacity" value="<?php echo $car['seating_capacity']; ?>" required>

        <label for="rent_per_day">Rent per day:</label>
        <input type="number" step="0.01" id="rent_per_day" name="rent_per_day" value="<?php echo $car['rent_per_day']; ?>" required>

        <label for="my-url-field">Image URL:</label>
        <input type="url" id="my-url-field" name="url" value="<?php echo $car['image_url']; ?>">

        <input type="submit" name="submit" value="Update Car">
        <?php if(($errors)){ ?>
                <div class="alert alert-danger" role="alert">
                <?php echo $errors[0]; ?>
                </div>
        <?php } ?>
        </form>
    </body>
</html>