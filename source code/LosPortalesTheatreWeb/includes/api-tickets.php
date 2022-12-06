<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/transaction.class.php";
    include "../classes/transaction-view.class.php";
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
      //grabing key data
      $dataArray = $data->key;
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

      //get transaction data 
      $transaction = new TransactionView();
      $transaction->requestAllUserTransactions($userID);
      $transactionData = $transaction->getInfo();

      //check if empty
      if($transactionData == null){
         echo json_encode(array('status'=>1, 'count'=>0));
         exit; 
      }

      //$Create object to return
      $result = array('status'=>1, 'transactionData'=>$transactionData, 'count'=>count($transactionData));

      //we return cart data
      echo json_encode($result);

   }