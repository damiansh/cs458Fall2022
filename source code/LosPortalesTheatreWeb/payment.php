<!DOCTYPE html>
<html>
<head>
  <title>Payment Information</title>
  <?php include 'dependencies.php';?>
</head>
<body>
<?php include 'navbar.php';
        include 'classes/payment.class.php';
        include 'classes/payment-view.class.php';
?>
<div class="topImg" style="background-image:url('images/welcome.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Payment Method</h1>
    </div>
</div>
 <?php
  
  $paymentInfo = new PaymentView();
  $paymentInfo = $paymentInfo->getPayInfo();
  if(!isset($_SESSION["userid"])){
    header("location: index.php");
  }
  elseif($paymentInfo==null){
    header("location: addPayment.php");
  }
  $cnumber =  str_pad($paymentInfo[0]["cnumber"],16,"•",STR_PAD_LEFT);
  $ctitular = $paymentInfo[0]["ctitular"];
  $cmonth = $paymentInfo[0]["cmonth"];
  $cyear = $paymentInfo[0]["cyear"]; 
  $cbilling = $paymentInfo[0]["cbilling"]; 
  $czip = $paymentInfo[0]["czip"]; 

  ?>
<div class="container justify-content-center">
  <h1 class="league display-3 text-center my-4">Payment Method Information:</h1>
    <div class="card">
      <div class="card-header text-center">
        <h2><?php echo $cnumber;?></h2>
      </div>
      <div class="card-body">
        <h3 class="card-title  text-center"><strong><?php echo $ctitular;?></strong></h3>
        <h5 class="card-text  text-center"><strong>Billing Adddress: </strong><?php echo "{$cbilling}, {$czip}";?></h5>
        <h5 class="card-text  text-center"><strong>Expiration Date: </strong><?php echo "{$cmonth}/20{$cyear}";?></h5>
        <form  action="includes/included-payment.php"   method="post" >
          <div class="d-grid"><button type="submit"  name="deletePayment" class="btn btn-danger btn-lg">Delete</button></div>
        </form>
      </div>
    </div>
</div>
<?php include "notification.php"?>

</body>

</html>
