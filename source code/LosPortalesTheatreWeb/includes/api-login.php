<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/login.class.php";
    include "../classes/login-contr.class.php";


   //Get raw posted data
   $data = json_decode(file_get_contents("php://input"));

   //Error handling if not data
   if($data==null){
      echo "ERROR";
   }
   else{
      //Grabbing the data from the registration form
      $email = $data->email;
      $psw = $data->psw;
      $login = new LoginContr($email,$psw,2);

      //Running error handlers and user registration
      $result = $login->loginUser();

      
      //we return the result of the registration
      echo json_encode($result);
   }