<?php
// Check if user is an agency and has permission to access this page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
  header('Location: login.php');
  exit();
}
require_once 'db.php';

$agency_id = $_SESSION['user_id'];
$car_id = $_GET['carid'];

$query ="SELECT u.name, b.start_date, b.end_date FROM bookings b
            JOIN users u ON u.id = b.customer_id
            JOIN cars c ON c.id = b.car_id
            WHERE c.id = $car_id AND c.agency_id = $agency_id";

$result = $conn->query($query);
$allbookings = $result->fetch_all(MYSQLI_ASSOC);
$car_query = "SELECT model, image_url FROM cars WHERE id = $car_id";
$car_result = $conn->query($query);
$car_info = $car_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Booking details</title>
</head>
<body>
  <h1>Booking details of :<?php echo $car_info['model'] ?> </h1>
  <img src="<?php echo $car_info['image_url'] ?> " alt="">
  <?php if($all_bookings): ?>
        <table>
            <thead>
            <tr>
                <th>Customer Name</th>
                <th>Booking Date</th>
            </tr>
            </thead>
            <tbody>

                <?php foreach( $booking as $allbookings): ?>
                    <tr>
                        <td><?php  echo  $booking['name'] ?></td>
                        <td><?php  echo  $booking['start_date'] ?></td>
                        <td><?php  echo  $booking['start_date'] ?></td>
                    </tr>;
                <?php endforeach; ?>
            </tbody>
        </table>
    <? else: ?>
        <p> no bookings for this car </p>
    <? endif; ?>

</body>
</html>
