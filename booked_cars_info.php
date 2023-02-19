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

if(!$car_id){
    header('Location: index.php');
    exit();
}

$query ="SELECT u.name, b.start_date, b.end_date FROM bookings b
            JOIN users u ON u.id = b.customer_id
            JOIN cars c ON c.id = b.car_id
            WHERE c.id = $car_id AND c.agency_id = $agency_id";

$result = mysqli_query($conn, $query);
$allbookings = mysqli_fetch_assoc($result);
// while($allbookings = mysqli_fetch_assoc($result)){
//     echo $allbookings['name'];
// }
$car_query = "SELECT model, image_url FROM cars WHERE id = $car_id";
$car_result = mysqli_query($conn, $car_query);
$car_info = mysqli_fetch_assoc($car_result);

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
  <?php if($allbookings) { ?>
        <table>
            <thead>
            <tr>
                <th>Customer Name</th>
                <th>Booking Date</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($booking = $result->fetch_assoc()) {
                        echo'<tr>';
                        echo'<td>' .$booking['name'] .'</td>';
                        echo'<td>' .$booking['start_date'] .'</td>';
                        echo'<td>' .$booking['end_date'] .'</td>';
                        echo '</tr>';
                  }
            ?>
                    
            </tbody>
        </table>
    <?php }
    else{ ?>
        <p> no bookings for this car </p>
    <?php }?>

</body>
</html>
