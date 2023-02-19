<nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
              <ul class="site-menu main-menu js-clone-nav ml-auto ">
                
                <li class="active"><a href="index.php" class="nav-link">Home</a></li>
                <?php if($user_type==='agency'): ?>
                  <li><a href="add_car.php" class="nav-link">AddCars</a></li>
                  <li><a href="booked_cars.php" class="nav-link">BookedCars</a></li>
                <?php endif; ?>
                <?php if($user_id): ?>
                <li><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                <li><a href="login.php" class="nav-link">Login</a></li>
                <li><a href="register.php" class="nav-link">Register</a></li>
                <?php endif; ?>
              </ul>
            </nav>