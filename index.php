<?php 
session_start();
// Establish a database connection
require_once 'db.php';


if(isset($_SESSION['user_id'])){
  $user_id=$_SESSION['user_id'];
}
else{
  $user_id=null;
}
if(isset($_SESSION['user_type'])){
  $user_type = $_SESSION['user_type'];
}
else{
  $user_type="";
}
if(isset($_SESSION['email'])){
  $user_email = $_SESSION['email'];
}
if(isset($_SESSION['msg'])){
  $error =$_SESSION['msg'];
}

unset($_SESSION['msg']);
$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+30 days'));


$query = "SELECT * FROM cars WHERE id NOT IN (SELECT car_id FROM bookings WHERE start_date <= ? AND end_date >= ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $end_date, $start_date);
$stmt->execute();
$result = $stmt->get_result();
$available_cars = $result->fetch_all(MYSQLI_ASSOC);



?>

<!doctype html>
<html lang="en">

  <head>
    <title>Cars</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,400,700&display=swap" rel="stylesheet">
    <?php include('css.php');?>

  </head>

  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">    
    <div class="site-wrap" id="home-section">

      <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>


    <header class="site-navbar site-navbar-target" role="banner">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-3 ">
              <div class="site-logo">
                <a href="index.html">CarRent</a>
              </div>
            </div>

            <div class="col-9  text-right">
              

              <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle py-5 text-white"><span class="icon-menu h3 text-white"></span></a></span>

              <?php include('nav.php'); ?>
          </div>

          
        </div>
      </div>

</header>

    <div class="ftco-blocks-cover-1">
      <div class="ftco-cover-1 overlay" style="background-image: url('images/hero_1.jpg')">

        <div class="container">
          <div class="row align-items-center">
            <div class="col align-self-center">
            <?php if(!empty($error)){ ?>
                  <div class="alert alert-success mt-2 d-flex justify-content-center align-items-center" role="alert">
                <?php echo $error; ?>
                  </div>
                <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section bg-light ">
      <!-- <div class="container"> -->
      <?php if(count($available_cars)!=0): ?>
        <?php if($user_type==='agency'): ?>
          <h2 class =" d-flex  justify-content-center  mb-5 pb-2 ">Edit Cars</h2>
        <?php else: ?>
          <h2 class =" d-flex  justify-content-center  mb-5 pb-2 ">Rent Cars</h2>
        <?php endif; ?>
          <div class="container-fluid m-2  "> 
            <div class=" d-flex justify-content-around  align-content-between flex-wrap ">
                <?php foreach ($available_cars as $car): ?>
                    <div class="item-1 col-sm-3 mb-3 ">
                      <img src="<?php echo $car['image_url'] ?>"alt="Image" class="img-fluid h-50">
                      <div class="item-1-contents">
                        <div class="text-center">
                        <h3><a href="rent_car.php"><?= htmlspecialchars($car['model']) ?></a></h3>
                        <div class="rating">
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                          <span class="icon-star text-warning"></span>
                        </div>
                        <div class="rent-price"><span>Rs<?= htmlspecialchars($car['rent_per_day']) ?>/</span>day</div>
                        </div>
                        <ul class="specs">
                          <li>
                            <span>Vehicle Number</span>
                            <span class="spec"><?= htmlspecialchars($car['vehicle_number']) ?></span>
                          </li>
                          <li>
                            <span>Seats</span>
                            <span class="spec"><?= htmlspecialchars($car['seating_capacity']) ?></span>
                          </li>
                          
                        </ul>
                        <?php if($user_type==='agency'): ?>
                          <div class="d-flex action justify-content-center ">
                              <a href="edit_car.php?car_id=<?php echo $car['id'] ?>" class="btn btn-primary w-100 ">Edit Details</a>
                          </div>
                        <?php else: ?>
      
                          <div class="d-flex action justify-content-center ">
                              <a href="rent_car.php?car_id=<?php echo $car['id'] ?>" class="btn btn-primary w-100 ">Rent Car</a>
                          </div>
                        <?php endif; ?>
                        
                      </div>
                    </div>
                <?php endforeach; ?>
            </div>
          </div>
       <?php else: ?>
       <p>No cars available to rent</p>
       <?php endif; ?>
    </div>
    <?php include('footer.php'); ?>
    </div>
    <?php include ('scripts.php'); ?>
  </body>

</html>
