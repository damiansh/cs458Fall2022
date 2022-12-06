<?php
session_start();
if(isset($_SESSION["adminid"])){
    if(isset($_POST["updateCost"])){
        $seatsJSON = $_POST["seatsJSON"];
        $seats = json_decode($seatsJSON,true);
        
        //Instantiate auth classes 
        include "../../classes/db.class.php";
        include "../../classes/seat.class.php";
        include "../../classes/seat-contr.class.php";
        $seatPlan = new SeatContr($seats);

        //Updating seats' cost 
        $seatPlan->setPrice();

        //Going to back 
        $_SESSION["message"] = "The prices have been updated.";
        header("location: ../modifySeats.php?playID=" . urlencode($seats[0]["play_id"]));

    }
    else{
        header("location: ../index.php?error");
    }
}
else if(isset($_SESSION["userid"])){
    if(isset($_POST["addSeats"])){
        $seatsJSON = $_POST["seatsJSON"];
        $seats = json_decode($seatsJSON,true);
        //Instantiate auth classes 
        include "../../classes/db.class.php";
        include "../../classes/seat.class.php";
        include "../../classes/seat-contr.class.php";
        $seatPlan = new SeatContr($seats);

        //Adding seats to shopping cart
        $seatStatus = $seatPlan->addtoCart($_SESSION["userid"]); 
        echo $seatStatus["available"];
        if($seatStatus["available"]!=""){
            $available = rtrim($seatStatus["available"],", ");
            $available =  "The seats [{$available}] have been added to your shopping cart.";
        }
        if($seatStatus["unavailable"]!=""){
            $unavailable = rtrim($seatStatus["unavailable"],", ");
            $unavailable =  "<br>The seats [{$unavailable}] have been purchased or reserved by another customer.";
        }
        //Going to back 
        $_SESSION["message"] = "{$available}{$unavailable}";
        header("location: ../../cart.php");

    }
    else{
        header("location: ../../index.php?error");
    }
}
else{
    header("location: ../../index.php?error");
}
