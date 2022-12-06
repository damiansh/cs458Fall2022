<?php
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


//Instantiate classes 
   include "../classes/db.class.php";
   include "../classes/seat.class.php";
   include "../classes/seat-contr.class.php";
   include "../classes/login.class.php";


//Get raw posted data
$data = json_decode(file_get_contents("php://input"));

//Error handling if not data
if($data==null){
   echo "ERROR";
}
else{
   //data from json 
   $dataArray = $data->key;
   $seats = json_decode(json_encode($data->seats ), true);
   $orderTotal = $data->total; 
   $email = $data->email; 
   $fName = $data->first_name; 
   $lName = $data->last_name; 

   //get the key data
   $dataArray = preg_split ("/\-/", $dataArray);
   $key = $dataArray[1];
   $userID = $dataArray[0];
   $login = new Login();

   //Running error handlers and user registration
   $result = $login->confirmKey($key,$userID);

   if($result['status'] ==0){
      echo json_encode(array('status'=>0));
      exit; 
   }


   //initiate cart
   $cart = new SeatContr($seats);


   //get result
   $result = $cart->prepareCheckOut($orderTotal,$userID,$email,$fName,$lName);

   ///get result variables
   $success = $result["status"];
   $message = $result["message"];
   $transactionID = 0; 

    //If transaction is successful 
    if($success=="success"){
      $transactionID = $result["transactionID"]; 
      $transMSJ = "<h5>Transaction ID #{$transactionID}</h5>";
      $message = "{$transMSJ}{$message}";
      //Going to back to the cart
   }

   //$Create object to return
   $result = array('status'=>1, 'message'=>$message, 'transaction_id'=> $transactionID, 'success'=> $success);

   //we return cart data
   echo json_encode($result);

}