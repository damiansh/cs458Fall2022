<!DOCTYPE html>
<html>
<head>
  <title>Los Portales</title>
  <?php include 'dependencies.php';?>

</head>
<body>

<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('images/welcome.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <?php if (isset($_SESSION["userid"])): ?>
        <h1 class="eTitle">Welcome <?php echo $_SESSION["userFN"]; ?>!!</h1>
      <?php else: ?>
        <h1 class="eTitle">Welcome Guest!!</h1>
      <?php endif; ?>
    </div>
</div>
<?php include 'upcoming.php';?>

<?php include 'footer.php';?>

</body>

</html>
