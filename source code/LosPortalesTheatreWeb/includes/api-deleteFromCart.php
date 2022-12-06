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
   $ticketN = $data->ticket_id;

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
   $cart = new SeatContr(null);  

   //Running error handlers remove cart
   $result = $cart->removeFromCart($ticketN,$userID); 
    
   //result variables
   $message = $result["message"];
   $status = $result["status"];


   //$Create object to return
   $result = array('status'=>$status, 'message'=>$message);

   //we return cart data
   echo json_encode($result);

}