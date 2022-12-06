<?php
if(isset($_POST["deleteCart"])){

    //start session
    session_start();
    //Grabbing the data from the registration form
    $ticketN = $_POST["ticketN"];

    //Instantiate auth classes 
    include "../classes/db.class.php";
    include "../classes/seat.class.php";
    include "../classes/seat-contr.class.php";
    $cart = new SeatContr(null);  

    //Running error handlers remove cart
    $result = $cart->removeFromCart($ticketN,$_SESSION["userid"]); 
 
    //result variables
    $message = $result["message"];
    $location = $result["location"];

    //Going to back to the cart
    $_SESSION["message"] = $message;
    header("location: ../{$location}");
    
}
elseif(isset($_POST["checkout"])){
    //start session
    session_start();
    //Grabbing the data from the registration form
    $data = $_POST["cartData"];
    $orderTotal = $_POST["orderTotal"];
    $cartSeats = json_decode($data,true);

    //Instantiate auth classes 
    include "../classes/db.class.php";
    include "../classes/seat.class.php";
    include "../classes/seat-contr.class.php";
    $cart = new SeatContr($cartSeats);

    //get result
    $result = $cart->prepareCheckOut($orderTotal,$_SESSION["userid"], $_SESSION["userEmail"], $_SESSION["userFN"], $_SESSION["userLN"]);

    ///get result variables
    $success = $result["status"];
    $message = $result["message"];
    $location = $result["location"];


    //If transaction is successful 
    if($success=="success"){
        $transactionID = $result["transactionID"]; 
        $transMSJ = "<h5>Transaction ID #{$transactionID}</h5>";
        $link = "tickets/printTickets.php?transactionID={$transactionID}";
        $printTickets = "<a href='{$link}' target='_blank'class='btn btn-secondary'>Print tickets</a>";
        $message = "{$transMSJ}{$message}{$printTickets}";
        //Going to back to the cart
    }

    //Go back to the cart
    $_SESSION["message"] = $message; 
    header("location: ../{$location}");
}
else{
    header("location: ../index.php?error");
}