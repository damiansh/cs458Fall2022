<?php
   header('Access-Control-Allow-Origin: *');
   header("Content-type:application/json");

   //Instantiate classes 
    include "../classes/db.class.php";
    include "../classes/play.class.php";
    include "../classes/play-view.class.php";


    $play = new PlayView();
    $play->requestPublished();
   
    $json = json_encode($play);
   
    echo $json;
