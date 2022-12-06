<!DOCTYPE html>
<html>
<head>
  <title>Add Payment Information</title>
  <?php include 'dependencies.php';?>
  <link href="css/card-js.min.css" rel="stylesheet" type="text/css" />
  <script src="js/card-js.min.js"></script>
  <style type="text/css">
    form button {
      display: block;
      margin-top: 15px;
      width: 100%;
      font-size: 12px;
      padding: 8px 12px;
    }
  </style>
</head>
<body>
<?php include 'navbar.php';
      include 'classes/payment.class.php';
      include 'classes/payment-view.class.php';
      if(!isset($_SESSION["userid"])){
        header("location: index.php");
      }
      $paymentInfo = new PaymentView();
      $paymentInfo = $paymentInfo->getPayInfo();
      if($paymentInfo!=null){
        header("location: payment.php");
      }
?>
<div class="topImg" style="background-image:url('images/welcome.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Add Payment Information</h1>
    </div>
</div>
<div class="container justify-content-center py-3">
  <form  action="includes/included-payment.php"  id="paymentForm" method="post" >
      <h1 class="league display-3 text-center my-4">Your Card Details:</h1>
      <!--
        -- Card JS Input Group https://github.com/CardJs/CardJs
        -->
      <div class="card-js form-group">

        <!-- Card number -->
        <input class="card-number form-control"
              name="cnumber"
              placeholder="Enter your card number"
              autocomplete="off"
              required>

        <!-- Name on card -->
        <input class="name form-control"
              id="the-card-name-id"
              name="ctitular"
              placeholder="Enter the name on your card"
              autocomplete="off"
              required>

        <!-- Card expiry (element that is displayed) -->
        <input class="expiry form-control"
              autocomplete="off"
              required>

        <!-- Card expiry - Month (hidden) -->
        <input class="expiry-month" name="cmonth" required>

        <!-- Card expiry - Year (hidden) -->
        <input class="expiry-year" name="cyear" required>


        <!-- Card CVC -->
        <input class="cvc form-control"
              name="ccvc"
              maxlength = "3"
              minlength = "3"
              autocomplete="off"
              required>

      </div><!-- END .card-js wrapper -->
        <div class="row">
          <div class="col-lg-10 py-2">
            <input type="text" placeholder="Enter the billing address tied to your card"  class="form-control"  name="cbilling"  required>
          </div>
          <div class="col-lg-2 py-2">
            <input type="number" min="10000"  max="99999" placeholder="Zip code" class="form-control"  name="czip"  required>
          </div>
        </div>

      <!--
        -- Submit button
        --
        -- (Must be outside of the div with class 'card-js')
        -->
      <button type="submit" class="btn btn-secondary" name="addPayment" >Add Payment Method</button>

  </form>
</div>


<?php include "notification.php"?>

</body>

</html>
