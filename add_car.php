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
// Handle form submission to add or edit cars
if (isset($_POST['submit'])) {
    $model =mysqli_real_escape_string($conn, $_POST['model']);
    $vehicle_number =mysqli_real_escape_string($conn, $_POST['vehicle_number']);
    $seating_capacity =mysqli_real_escape_string($conn, $_POST['seating_capacity']);
    $rent_per_day =mysqli_real_escape_string($conn, $_POST['rent_per_day']);
    $url =mysqli_real_escape_string($conn, $_POST['url']);

    
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
    

    if(count($errors)==0){

        $agency_id = $_SESSION['user_id'];
        // Add new car
        if(!$url){
            $query = "INSERT INTO cars (model, vehicle_number, seating_capacity, rent_per_day, agency_id) VALUES (?, ?, ?, ?,?)";
        }
        else{
            $query = "INSERT INTO cars (model, vehicle_number, seating_capacity, rent_per_day,image_url, agency_id) VALUES (?, ?, ?, ?, ?,?)";
        }
        
        // prepare statement 
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die('Failed to prepare statement');
          }
          
        if(!$url){
            mysqli_stmt_bind_param($stmt, 'ssddi', $model, $vehicle_number, $seating_capacity, $rent_per_day, $agency_id);
        }
        else{
            mysqli_stmt_bind_param($stmt, 'ssddsi', $model, $vehicle_number, $seating_capacity, $rent_per_day,$url, $agency_id);
        }
         // Execute statement
        if (mysqli_stmt_execute($stmt)) {
            // Success
            header('Location: index.php');
            exit;
        } else {
            // Error
            echo "Error:" . mysqli_error($conn);
        }
    

    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add New Car</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h1>Add New Car</h1>

  <form method="post">
    <label for="model">Vehicle model:</label>
    <input type="text" id="model" name="model" required>

    <label for="vehicle_number">Vehicle number:</label>
    <input type="text" id="vehicle_number" name="vehicle_number" required>

    <label for="seating_capacity">Seating capacity:</label>
    <input type="number" id="seating_capacity" name="seating_capacity" required>

    <label for="rent_per_day">Rent per day:</label>
    <input type="number" step="0.01" id="rent_per_day" name="rent_per_day" required>

    <label for="my-url-field">Enter a URL:</label>
    <input type="url" id="my-url-field" name="url">


    <input type="submit" name="submit" value="Add Car">
    <?php if(($errors)){ ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $errors[0]; ?>
            </div>
    <?php } ?>
  </form>

</body>
</html>
