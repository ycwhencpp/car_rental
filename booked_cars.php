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

$query = "SELECT cars.*, bookings.*
FROM cars
INNER JOIN bookings ON cars.id = bookings.car_id
WHERE cars.agency_id = $agency_id";


$resultAll = mysqli_query($conn, $query);
if (!$resultAll || mysqli_num_rows($resultAll) == 0) {
  // Car not found or does not belong to current agency user
  echo "<p> No carks booked yet </p>";
  exit();
}


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
      <?php
        while ($booking = $resultAll->fetch_assoc()) {
          echo '<div class="booked-car">';
          echo '<a href="booked_cars_info.php?carid=' . $booking['car_id'] . '">';
          echo '<div class="car-details">';
          echo '<p>' . $booking['model'] . '</p>';
          echo '<p>' . $booking['start_date'] . '</p>';
          echo '<img src="' . $booking['image_url'] . '" alt="car_image">';
          echo '</div></a></div>';
        }
      ?>
    </div>
  </body>
</html>
