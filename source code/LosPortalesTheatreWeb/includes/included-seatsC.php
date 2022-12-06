<?php
session_start();

if(isset($_SESSION["userid"])){
    if(isset($_POST["addSeats"])){
        $seatsJSON = $_POST["seatsJSON"];
        $seats = json_decode($seatsJSON,true);
        //Instantiate auth classes 
        include "../classes/db.class.php";
        include "../classes/seat.class.php";
        include "../classes/seat-contr.class.php";
        $seatPlan = new SeatContr($seats);

        //Adding seats to shopping cart and getting result variables
        $result =  $seatPlan->addtoCart($_SESSION["userid"]); 
        $seatStatus = $result['seatStatus'];
        $location = $result['location'];
        $status =  $result['status'];
        $message =  "error";
        if($status="success"){
            if($seatStatus["available"]!=""){
                $available = rtrim($seatStatus["available"],", ");
                $available =  "The seats [{$available}] have been added to your shopping cart.";
            }
            if($seatStatus["unavailable"]!=""){
                $unavailable = rtrim($seatStatus["unavailable"],", ");
                $unavailable =  "<br>The seats [{$unavailable}] have been purchased or reserved by another customer.";
            }
            $message =  "{$available}{$unavailable}";
        }
       
        //Going to back 
        $_SESSION["message"] = $message;
        header("location: ../{$location}");

    }
    else{
        header("location: ../index.php?error");
    }
}
else{
    header("location: ../index.php?error");
}
