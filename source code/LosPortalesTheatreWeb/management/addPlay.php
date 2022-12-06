<!DOCTYPE html>
<html>
<head>
  <title>Add Plays</title>
  <?php include 'dependencies.php';?>
</head>
<body>

<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('../images/add-play.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Add Plays</h1>
    </div>
</div>
  <div class="container py-3">
    <p>Please fill this form to add a new play to the system.</p>
    <hr>
    <?php 
        //fields variables
        $inputID = null;
        $playTitle = null;
        $short = null;
        $long = null;
        $start = null;
        $end = null;
        $img = null;
        include 'playForm.php';
    ?>
  </div>
  <?php include 'crop.php';
        include "../notification.php";
  ?>
</body>

</html>
