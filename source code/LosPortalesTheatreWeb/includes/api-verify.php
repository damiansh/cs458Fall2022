<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/login.class.php";

   //error handler function
   function customError($errno, $errstr) {
      return; 
   }
 
   //set error handler
   set_error_handler("customError");

   //Get raw posted data
   $data = json_decode(file_get_contents("php://input"));

   //Error handling if not data
   if($data==null){
      echo "ERROR";
   }
   else{
      
      //Grabbing the data from the registration form
      $dataArray = $data->key;
      $dataArray = preg_split ("/\-/", $dataArray);
      $key = $dataArray[1];
      $userID = $dataArray[0];
      $login = new Login();

      //Running error handlers and user registration
      $result = $login->confirmKey($key,$userID);

      
      //we return the result of the registration
      echo json_encode($result);
   }