<?php

if(isset($_POST["login"]) || isset($_POST["loginM"]))
{
    //Grabbing the data from the registration form
    $email = $_POST["email"];
    $psw = $_POST["psw"];
    $m = 0; //if 0 customer login, if 1 management login, if 2 API
    if(isset($_POST["loginM"])){
        $m=1;
    }

    //Instantiate Register  Contr class
    include "../classes/db.class.php"; // needs to be loaded first
    include "../classes/login.class.php";
    include "../classes/login-contr.class.php";
    $login = new LoginContr($email,$psw,$m);

    //Running error handlers and user registration
    $result = $login->loginUser();
    
    //Going to back to front page
    session_start();
    $_SESSION["message"] = $result["message"];
    if($m==1){
        header("location: ../management?{$result['status']}"); //index page for management area
    }
    else{
        if(isset($_GET["print"])) {
            header("location: ../tickets/printTickets.php?transactionID={$_GET['print']}"); //go to the print 
            exit; 
        }
        header("location: ../{$result['location']}{$result['status']}"); //index page for customer area
    }

}
else{
    header("location: ../index.php?error");
}