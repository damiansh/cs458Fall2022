

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


      //initiate seat plan 
      $seatPlan = new SeatContr($seats);

      //Adding seats to shopping cart and getting result variables
      $result =  $seatPlan->addtoCart($userID); 
      $seatStatus = $result['seatStatus'];
      $status =  $result['status'];
      $available =  "";
      $unavailable =  "";
      $message = "error";
      $space = ""; 
      if($status="success"){
         if($seatStatus["available"]!=""){
             $available = rtrim($seatStatus["available"],", ");
             $available =  "The seats <b><font color='red'>[{$available}]</font></b> have been added to your shopping cart.";
             $space = "<br><br>";
         }
         if($seatStatus["unavailable"]!=""){
             $unavailable = rtrim($seatStatus["unavailable"],", ");
             $unavailable =  "{$space}The seats <b><font color='red'>[{$unavailable}]</font></b> have been purchased or reserved by another customer.";
         }
         $message =  "{$available}{$unavailable}";
     }
    

      //$Create object to return
      $result = array('status'=>1, 'message'=>$message);

      //we return cart data
      echo json_encode($result);

   }