   <?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/seat.class.php";
    include "../classes/seat-view.class.php";
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


      //get cart data
      $cart = new SeatView(0);
      $cartData = $cart->setCart($userID);
      

      //get the count, this also removes items not longer in the cart
      $count = $cart->getCount();

      //check if empty
      if($cartData == null or $count==0){
         echo json_encode(array('status'=>1, 'count'=>$count));
         exit; 
      }
      //$Create object to return
      $cartData = array('status'=>1, 'cart'=>$cartData, 'count'=>$count);

      //we return cart data
      echo json_encode($cartData);

   }