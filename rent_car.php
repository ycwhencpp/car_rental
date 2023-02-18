<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['user_type'] == 'agency') {
    header('Location: index.php');
    exit;
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_id =mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $car_id =mysqli_real_escape_string($conn, $_POST['car_id']);
    $days = mysqli_real_escape_string($conn,$_POST['num_days']);
    $start_date =mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $days . ' days'));



    $stmt = $conn->prepare( "INSERT INTO bookings (customer_id, car_id, start_date, end_date) VALUES (?,?,?,?)");
    $stmt->bind_param("iiss", $customer_id, $car_id, $start_date, $end_date);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "<p>Car rented successfully.</p>";
    } else {
        echo "<p>Error renting car: " . $stmt->error . "</p>";
    }
}
