<?php
   //Instantiate auth classes 
   include "classes/play.class.php";
   include "classes/play-view.class.php";

   $play = new PlayView();
   $play->requestPublished();
  
?>

<div class="container">
    <h1 class="eTitle display-3 text-center my-4">Upcoming Shows</h1>
    <div class="row">
        <?php $play->printPlays("col-md-6 col-lg-6 col-xl-4 py-1",0); ?>
        
    </div>
</div>
