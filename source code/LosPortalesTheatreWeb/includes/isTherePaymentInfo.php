<?php
if(!isset($_SESSION["userid"])){
    header("location: login.php");
}

include 'classes/payment.class.php';
include 'classes/payment-view.class.php';

$paymentInfo = new PaymentView();
$paymentInfo = $paymentInfo->getPayInfo();

//If payment method is not available we sent it to the addPayment page. 
if($paymentInfo==null){
$_SESSION["message"] = "You must add a payment method before accessing to this section.";
header("location: addPayment.php");
}
else{
    include "notification.php";
}

?>