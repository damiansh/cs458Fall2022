<?php include "includes/included-cart.php";?>
<!-- Navbar -->
<div class="container-fluid">
    <nav class="navbar navbar-expand-sm navbar-dark fixed-top black nav-padding">
      <a href="index.php" class="navbar-brand">Los Portales</a>
      <button
        class="navbar-toggler" 
        type="button"
        data-bs-toggle="collapse" 
        data-bs-target="#toggleMobileMenu" 
        aria-controls="toggleMobileMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center order" id="toggleMobileMenu">
        <ul class="navbar-nav ms-auto text-center">
          <!-- Navigation bar content always visible-->
          <li><a  id ="index.php" class="nav-link" href="index.php">Home</a></li>
          <!-- Navigation bar content if user is logged -->
          <?php if(isset($_SESSION["userid"])){ ?>
          <li><a  id ="cart.php" class="nav-link" href="cart.php">Cart (<?php echo $count;?>)</a></li>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php  echo "{$_SESSION['userFN']} {$_SESSION['userLN']}"; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="payment.php">Payment Method</a></li>
            <li><a class="dropdown-item" href='orders.php'>Orders</a></li>
            <li><a class="dropdown-item" href='includes/included-logout.php'>Logout</a></li>
          </ul>
        </li>    
              
          <?php 
          } 
          else{

            ?>
           <!-- Navigation bar content if user is not logged -->
          <li><a  id ="login.php" class="nav-link" href="login.php">Login</a></li>
          <li><a  id ="register.php" class="nav-link" href="register.php">Register</a></li>
          <?php 
          } 
         ?>            
        </ul>
      </div>

    </nav>
  </div>

<!-- Javascript for Navbar -->
<script>
  //Highlight active page in the navbar
  var path = window.location.pathname;
  var page = path.split("/").pop();
  currentPage = document.getElementById(page);
  if(page =="" || page =="index.php"){
    currentPage = document.getElementById("index.php");
  }
  else if(page =="selectSeats.php" || page =="payment.php" || page =="addPayment.php" || page =="orders.php"){
    currentPage = document.getElementById("navbarDropdownMenuLink");
  }
  currentPage.classList.add("active"); //we add the class active  
</script>
