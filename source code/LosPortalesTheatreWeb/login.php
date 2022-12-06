<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <?php include 'dependencies.php';
    $destiny="includes/included-login.php";
    if(isset($_SESSION["userid"])) 
      header("location: index.php");
    elseif(isset($_GET["print"])){
      //change destiny
      $destiny = "{$destiny}?print={$_GET['print']}";
    }
  ?>
  
</head>
<body>

<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('images/login.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Login into your account</h1>
    </div>
</div>
  <div class="container py-3">
    <div class="row">
      <div class="col-sm">
        <form action="<?php echo $destiny ?>" method="post">
          <h1 class="league display-3 text-center my-4">Login information:</h1>
          <h5>Enter your Email Address and Password to login into your account:</h3>
            <div class="mb-3">
              <label for="email"><b>Email</b></label>
              <input type="text" placeholder="Enter Email" class="form-control"name="email" required>
            </div>
            <div class="mb-3">
              <label for="psw"><b>Password</b></label>
              <input type="password" placeholder="Enter Password" class="form-control" name="psw" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" name="login"  class="btn btn-secondary btn-lg">Login</button>
            </div>
        </form>
      </div>
      <div class="col-sm">
        <form action="register.php" method="post">
          <h1 class="league display-3 text-center my-4">Not Member Yet?</h1>
          <h5>Click below to create an account on Los Portales Theatre website</h3>
            <div class="d-grid gap-2">
              <button type="submit" name="register"  class="btn btn-secondary">Join Los Portales Theatre!</button>
            </div>
        </form>
      </div>
    </div>

  </div>

  <?php include "notification.php"?>
  <?php include 'footer.php';?>
</body>
</html>
