<!-- Navbar -->
<div class="container-fluid">
    <nav class="navbar navbar-expand-sm navbar-dark fixed-top black nav-padding">
      <a href="index.php" class="navbar-brand">Los Portales Management Area</a>
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
          <!-- Navigation bar content if user is logged -->
          <?php if(isset($_SESSION["adminid"])){ ?>
            <li class="nav-item dropdown">
          <a id="play-manager" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Play Manager 
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="addPlay.php">Add Plays</a></li>
            <li><a class="dropdown-item" href='index.php'>Modify Plays</a></li>
          </ul>
        </li>  
            <li><a  id ="report.php" class="nav-link" href="report.php">Generate Report</a></li>
            <li><a class="nav-link" href='includes/included-logout.php'>Logout</a></li>
   
          <?php 
          } 
          else{

            ?>
           <!-- Navigation bar content if user is not logged -->
          <li><a  id ="login.php" class="nav-link" href="login.php">Login</a></li>
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
  if(page !="report.php" && page != "login.php"){
    currentPage = document.getElementById("play-manager");
  }
 
  currentPage.classList.add("active"); //we add the class active  
</script>
