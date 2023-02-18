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

$agency_id = $_SESSION['user_id'];
// Query to select all the bookings for the cars owned by the agency

$query = "SELECT *
FROM bookings
JOIN cars ON bookings.car_id = cars.id
WHERE cars.agency_id = $agency_id";

$resultAll = mysqli_query($conn, $query);
if (!$resultAll || mysqli_num_rows($resultAll) == 0) {
  // Car not found or does not belong to current agency user
  echo "<p> No carks booked yet </p>";
  exit();
}
$bookedcars=mysqli_fetch_assoc($resultAll);

//close the db connection

$conn->close();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>View Booked Cars</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="container">
      <h1>View Booked Cars</h1>
      <div id="booked-cars">
      <?php foreach( $bookedcars as $booking): ?>
            <a href="booked_cars_info.php?carid=<?php echo $booking['car_id'] ?>">
                <div class="cars">
                    <p><?php echo $booking['id']; ?></p>
                    <p><?php echo $booking['customer_id']; ?></p>
                    <p><?php echo $booking['car_id']; ?></p>
                    <img src="<?php echo $booking['image_url']; ?>" alt="car_image">
                </div> 
            </a>  
      <?php endforeach;  ?>
      </div>
    </div>
  </body>
</html>