<!DOCTYPE html>

<html>

<head>

  <title>Shopping cart</title>

  <?php include 'dependencies.php';?>

  <meta http-equiv="Refresh" content="60">



</head>





<body>



<?php include 'navbar.php';?>

<?php 

include 'includes/isTherePaymentInfo.php';



if(!isset($_SESSION["userid"])){

    header("location: login.php");

}  





?>

<div class="topImg" style="background-image:url('images/seats.jpg')">

    <div class="txt-overlay d-flex justify-content-center align-items-center">

      <h1 class="eTitle"><?php echo "{$_SESSION['userFN']}'s ";?>Cart</h1>

    </div>

</div>

<div class="container py-4">

<form action="includes/included-checkout.php" method="post">



<div class="card">

  <h5 class="card-header league display-3">Tickets</h5>

  <div class="card-body">

    <h5 class="card-title">After adding a seat to your cart, you have 10 minutes to purchase it before the reservation expires.</h5>



    <?php $cart->showCart();?>

    <?php

      if($cartData==null){

        echo "<button  class='btn btn-secondary btn-lg' disabled>Checkout</button>";

      }

      else{

        $cartIDs = new ArrayObject();

        $i = 0; 

        foreach ($cartData as $item){

          $cartIDs[$i]["ticket_id"] = $item["ticket_id"];

          $cartIDs[$i]["cost"] = $item["cost"];

          $cartIDs[$i]["seat_number"] = $item["seat_number"];



          $i++;

        }



        $dataJS = json_encode($cartIDs);

        echo "<input type='hidden' value='{$dataJS}' name='cartData'>";

        echo "<button type='submit' name='checkout' class='btn btn-secondary btn-lg'>Checkout</button>";

      }

    ?>

  </div>

</div>

</form>

</div>



</body>



</html>



