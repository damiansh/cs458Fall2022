<?php

if(isset($_POST["addPayment"]))
{
    //start session
    session_start();
    //Grabbing the data from the registration form
    $cnumber = $_POST["cnumber"];
    $ctitular = $_POST["ctitular"];
    $cmonth = $_POST["cmonth"];
    $cyear = $_POST["cyear"];
    $ccvc = $_POST["ccvc"];
    $cbilling = $_POST["cbilling"];
    $czip = $_POST["czip"];



    //Instantiate Register  Contr class
    include "../classes/db.class.php"; // needs to be loaded first
    include "../classes/payment.class.php";
    include "../classes/payment-contr.class.php";

    $payment = new PaymentContr($cnumber, $ctitular, $cmonth, $cyear, $ccvc, $cbilling, $czip);

    //Running error handlers and payment update
    $payment->addPaymentInfo();
    
    //Going to back to the payment page
    $_SESSION["message"] = "Your payment information has been updated";
    header("location: ../payment.php?success");

}
elseif(isset($_POST["deletePayment"])){
    session_start();

    //Instantiate auth classes 
    include "../classes/db.class.php";
    include "../classes/payment.class.php";

    $payment = new Payment();

    //Running upload
    $payment->deletePayment();

    //Going to back 
    $_SESSION["message"] = "Your payment method has been removed.";
    header("location: ../addPayment.php");    

}
else{
    header("location: ../index.php?error");
}