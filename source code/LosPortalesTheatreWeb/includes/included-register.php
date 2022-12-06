<?php
session_start();
if(isset($_SESSION["userid"])){
    header("location: ../index.php?AlreadyRegistered");
}
elseif(isset($_POST["register"]))
{
    //Grabbing the data from the registration form
    $email = $_POST["email"];
    $psw = $_POST["psw"];
    $pswRepeat = $_POST["psw-repeat"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $birthday = $_POST["birthday"];
    $phone = $_POST["phone"];

    //Instantiate Register  Contr class
    include "../classes/db.class.php"; // needs to be loaded first
    include "../classes/register.class.php";
    include "../classes/register-contr.class.php";
    $register = new RegisterContr($email,$psw,$pswRepeat,$fname,$lname,$birthday,$phone);

    //We set the registration message for a successfull registration

    //Running error handlers and user registration
    $result = $register->registerUser();
    
    //Going to back to registration page 
    session_start();
    $_SESSION["message"] = $result["message"];
    header("location: ../register.php?{$result['status']}");

}
else{
    header("location: ../index.php?error");
}