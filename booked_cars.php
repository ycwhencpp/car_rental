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

$query = "SELECT cars.car_id, cars.car_model, cars.car_number ,cars.image_url
            FROM cars
            INNER JOIN bookings ON cars.car_id = bookings.car_id
            WHERE cars.agency_id = $agency_id";

$result = $conn->query($query);
$bookedcars=$result->fetch_all(MYSQLI_ASSOC);

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
      <?php foreach($booking as $bookedcars): ?>
            <a href="booked_cars_info.php?carid=<?php echo $booking['car_id'] ?>">
                <div class="cars">
                    <p><?php echo $booking['car_id']; ?></p>
                    <p><?php echo $booking['car_model']; ?></p>
                    <p><?php echo $booking['car_number']; ?></p>
                    <img src="<?php echo $booking['image_url']; ?>" alt="car_image">
                </div> 
            </a>  
      <?php endforeach;  ?>
      </div>
    </div>
  </body>
</html>