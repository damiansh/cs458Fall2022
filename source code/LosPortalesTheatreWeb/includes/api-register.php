<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/register.class.php";
    include "../classes/register-contr.class.php";


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
      $pswRepeat = $data->pswRepeat;
      $fname = $data->fname;
      $lname = $data->lname;
      $birthday = $data->birthday;
      $phone =$data->phone;

      $register = new RegisterContr($email,$psw,$pswRepeat,$fname,$lname,$birthday,$phone);

      //Running error handlers and user registration
      $result = $register->registerUser();
      
      //we return the result of the registration
      echo json_encode($result);
   }