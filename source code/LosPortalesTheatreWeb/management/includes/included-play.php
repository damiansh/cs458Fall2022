<?php
session_start();
if(!isset($_SESSION["adminid"])){
    header("location: ../login.php?NotLogged");
}
elseif(isset($_POST["addP"]))
{

    //Grabbing the post data
    $playTitle = $_POST['playTitle'];
    $shortDesc = $_POST['shortDesc'];
    $longDesc = $_POST['longDesc'];
    $sDate = $_POST['sDate'];
    $eDate = $_POST['eDate'];
    $image = $_POST['image'];
    $cost = $_POST['cost'];
    $folderPath = '../../images/plays/';

    //Instantiate auth classes 
    include "../../classes/db.class.php";
    include "../../classes/play.class.php";
    include "../../classes/play-contr.class.php";
    $play = new PlayContr($playTitle, $shortDesc, $longDesc, $sDate, $eDate, $cost);

    //Running upload
    $playID = $play->addPlay();
    
    //Setting image if provided
    if($image!=null){
        $play->setImage($image, $folderPath,$playID,"addPlay");
    }
  

    //Going to back 
    $_SESSION["message"] = "The play has been added successfully.";
    header("location: ../index.php?playID=" . urlencode($playID));

}
elseif(isset($_POST["publish"]))
{
    //grabbing post data
    $playID = $_POST["playID"];
    $published = $_POST["published"];
    //Instantiate auth classes 
    include "../../classes/db.class.php";
    include "../../classes/play.class.php";

    $play = new Play();

    //Running upload
    $play->publishPlay($playID,$published);

    //Going to back 
    $_SESSION["message"] = "The play has been published.";
    if($published == 0){
        $_SESSION["message"] = "The play has been unpublished and is not longer visible.";
    }
    $urlPart = "{$playID}#play{$playID}";
    header("location: ../index.php?playID={$urlPart}");
}

elseif(isset($_POST["delete"]))
{
    //grabbing post data
    $playID = $_POST["playID"];
    //Instantiate auth classes 
    include "../../classes/db.class.php";
    include "../../classes/play.class.php";

    $play = new Play();

    //Running upload
    $play->deletePlay($playID,$published);

    //Going to back 
    $_SESSION["message"] = "The play has been deleted from the system.";
    header("location: ../index.php");
}

elseif(isset($_POST["update"]))
{
    //Grabbing the post data
    $playID = $_POST['playIDinput'];
    $playTitle = $_POST['playTitle'];
    $shortDesc = $_POST['shortDesc'];
    $longDesc = $_POST['longDesc'];
    $sDate = $_POST['sDate'];
    $eDate = $_POST['eDate'];
    $image = $_POST['image'];
    $cost = 1; // value doesn't matter as long as it is not null or 0
    $folderPath = '../../images/plays/';

    //Instantiate auth classes 
    include "../../classes/db.class.php";
    include "../../classes/play.class.php";
    include "../../classes/play-contr.class.php";
    $play = new PlayContr($playTitle, $shortDesc, $longDesc, $sDate, $eDate, $cost);

    //Running upload
    $play->prepareChanges($playID);
    
    //Setting image if provided
    if($image!=null){
        $play->setImage($image, $folderPath,$playID,"index");
    }
  

    //Going to back 
    $_SESSION["message"] = "The play information has been updated.";
    header("location: ../index.php?playID=" . urlencode($playID));

}
else{
    header("location: ../index.php?error");
}