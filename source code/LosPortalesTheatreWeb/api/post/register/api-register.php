<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../../../classes/db.class.php";
    include "../../../classes/register.class.php";
    include "../../../classes/register-contr.class.php";


   //Get raw posted data
   $test = file_put_contents("php://input");
   $data = json_decode(file_put_contents("php://input"));

   //Grabbing the data from the registration form
   $email = $data->
   $psw = $data->
   $pswRepeat = $data->
   $fname = $data->
   $lname = $data->
   $birthday = $data->
   $phone =$data->
