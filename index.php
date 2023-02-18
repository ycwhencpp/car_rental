<?php 
session_start();
// Establish a database connection
require_once 'db.php';

// Query to get all available cars


$user_id=$_SESSION['user_id'];
$user_type = $_SESSION['user_type'];


$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+30 days'));
$user_email = $_SESSION['email'];

$query = "SELECT * FROM cars WHERE id NOT IN (SELECT car_id FROM bookings WHERE start_date <= ? AND end_date >= ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $end_date, $start_date);
$stmt->execute();
$result = $stmt->get_result();
$available_cars = $result->fetch_all(MYSQLI_ASSOC);


?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Available Cars to Rent</title>
  </head>
  <body>
    <h1>Available Cars to Rent</h1>
    <?php if(count($available_cars)!=0): ?>
        <table>
          <thead>
            <tr>
              <th>Vehicle Model</th>
              <th>Vehicle Number</th>
              <th>Seating Capacity</th>
              <th>Rent Per Day</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($available_cars as $car): ?>
              <p></p>
              <tr>
                <td><img src="<?php echo $car['image_url'] ?>" alt=""></td>
                <td><?= htmlspecialchars($car['model']) ?></td>
                <td><?= htmlspecialchars($car['vehicle_number']) ?></td>
                <td><?= htmlspecialchars($car['seating_capacity']) ?></td>
                <td><?= htmlspecialchars($car['rent_per_day']) ?></td>
              </tr>
              <?php if ($user_type === 'customer'): ?>
                  <h2>Rent a Car</h2>
                  <form action="rent_car.php" method="post">
                  <label for="num_days">Number of Days:</label>
                    <select name="num_days" id="num_days">
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                        <?php endfor; ?>
                    </select>
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                    <input type="hidden" name="car_id" value="<?php echo $car['id'] ?>">
                    <button type="submit">Rent Car</button>
                  </form>
              <?php endif; ?>

            <?php endforeach; ?>
          </tbody>
        </table>


    <?php else: ?>
        <p>No cars available to rent</p>
    <?php endif; ?>
  </body>
</html>
