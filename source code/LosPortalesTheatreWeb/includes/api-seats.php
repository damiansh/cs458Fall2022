<?php
   header('Access-Control-Allow-Origin: *');
   header('Content-type:application/json');
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/seat.class.php";
    include "../classes/seat-view.class.php";
    include '../classes/play.class.php';
    include '../classes/play-view.class.php';


   //Get raw posted data
   $data = json_decode(file_get_contents("php://input"));

   //Error handling if not data
   if($data==null){
      echo "ERROR";
   }
   else{
      //Grabbing the data from the json 
      $playID = $data->playID;

      //Get the play Seats by its ID 
      $seat = new SeatView($playID);
      //to update seats reservation, count method does that 
      $seat->getCount(); 
 
      $seat  = $seat->getSeatsData(); 

      if($seat == null) {
         echo json_encode(array('status'=>0, 'message'=>'Seats for this play are not available'));
         exit; 
      }

      //get the play info
      $playInfo = new PlayView();
      $playInfo->requestPlay($playID,1);
      $playInfo = $playInfo->getPlayInfo();
      
      //we return the result of the registration
      echo json_encode(array('status'=>1, 'playInfo'=>$playInfo[0], 'seatInfo'=>$seat));
   }