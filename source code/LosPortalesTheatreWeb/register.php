<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
  <?php include 'dependencies.php';
    if(isset($_SESSION["userid"])) 
      header("location: index.php");

    //today date max Date of registration
    $today = new DateTime('now');
    $today->modify('-13 years');
    $maxDate = $today->format("Y-m-d")
  ?>
  
</head>
<body>

<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('images/registration.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Join Los Portales Theatre!</h1>
    </div>
</div>

  <div class="container">
  <form action="login.php" method="post">
          <h3 class="league display-4 text-center my-4">Already a member?</h3>
            <div class="d-grid gap-2">
              <button type="submit" name="register"  class="btn btn-secondary">Login!</button>
            </div>
        </form>
        <form action="includes/included-register.php" method="post">
            <h1 class="league display-3 text-center my-4">Registration Form:</h1>
              <h5>Please fill in this form to create an account.</h3>
              <hr>
          <div class="mb-3">
            <label for="email" class="form-label">Email address:</label>
            <input type="email" class="form-control" placeholder="Enter Email" name="email" id="email" required>
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="mb-3">
            <label for="psw" class="form-label">Password:</label>
            <input type="password" placeholder="Enter Password"   class="form-control"  name="psw" id="psw"  minlength="6" required>
          </div>
          <div class="mb-3">
            <label for="psw-repeat" class="form-label">Confirm Password:</label>
            <input type="password" placeholder="Enter again your password"    class="form-control"  name="psw-repeat" id="psw-repeat" minlength="6" required>
          </div>

          <div class="mb-3">
            <label for="fname" class="form-label">First name:</label>
            <input type="text" placeholder="Enter your first name"  class="form-control"  name="fname" id="fname" required>
          </div>
          <div class="mb-3">
            <label for="lname" class="form-label">Last name:</label>
            <input type="text" placeholder="Enter your last name"  class="form-control"  name="lname" id="lname" required>
          </div>
          <div class="mb-3">
            <label for="birthday" class="form-label">Date of Birth:</label>
            <input type="date" placeholder="Enter your date of birth"  class="form-control"  name="birthday" id="date" max='<?php echo $maxDate;?>' required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number XXX-XXX-XXXX:</label>
            <input type="tel" placeholder="Enter your phone number" maxlength="12" class="form-control"  pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="phone" id="phone" required >
          </div>
          <div class="d-grid gap-2">
              <button type="submit" name="register"  class="btn btn-secondary btn-lg">Join</button>
            </div>
      
        </form>

  </div>
<br>
<?php include "notification.php"?>
<?php include 'footer.php';?>

</body>
</html>
<script>
var tele = document.getElementById('phone');

tele.addEventListener('keyup', function(e){
  if (event.key != 'Backspace' && (tele.value.length === 3 || tele.value.length === 7)){
  tele.value += '-';
  }
});
  </script>
