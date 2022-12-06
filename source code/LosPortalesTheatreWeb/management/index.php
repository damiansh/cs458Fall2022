<!DOCTYPE html>
<html>
<head>
  <title>Play Manager</title>
  <?php include 'dependencies.php';?>
</head>
<?php 
include '../classes/db.class.php';
include '../classes/play.class.php';
include '../classes/play-view.class.php';

//get play data for dropdown
$plays = new PlayView();
$plays->requestAllPlays();
$plays = $plays->getPlayInfo();
//echo "<script>console.log(" .json_encode($plays) . ");</script>"; debug play get

//get data for playCard
if(isset($_GET["playID"])){
  $playCard = new PlayView();
  $playID = $_GET["playID"];
  $playCard->requestPlay($playID,0);
  //Get play data 
  $playData = $playCard->getPlayInfo();
  //check if playID exists 
  $exists = false;
  foreach ($plays as $play){
    if($play["play_id"]==$playID)
      $exists = true; 
  }
}   
?>

<body>

<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('../images/modify-play.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Play Manager</h1>
    </div>
</div>

<div class="container py-3">
    <p>Select in the dropdown below, the play you wish to modify or delete</p>
    <select class="form-select form-select-lg mb-3" onchange="selectPlay(this)" aria-label=".form-select-lg example">

      <option selected>Select a Play</option>
      <?php
        foreach ($plays as $play){
          $date = date('m/d/Y',strtotime($play["stime"]));
          $sTime = date('h:i a',strtotime($play["stime"]));
          $eTime = date('h:i a',strtotime($play["etime"]));
          $playTitle = $play["play_title"];
          $playoid = $play["play_id"];
          $option = "<option value='{$playoid}'>{$playTitle}: {$date} {$sTime} - {$eTime}</option>";
          echo $option;
        }
      ?>
    </select>
    <hr>
    <?php if(isset($playID) && $exists):?>
    <div class="row">
    <?php 
      $playCard->printPlays("col-lg-5 col-xl-6",2);
      //fields variables
      $inputID = $playData[0]["play_id"];
      $inputID = "value='{$inputID}'";
      $playTitle = htmlspecialchars($playData[0]["play_title"]);
      $playTitle = 'value="' . $playTitle . '"';
      $short = htmlspecialchars($playData[0]["short_desc"]);
      $short = 'value="' . $short . '"';
      $long = htmlspecialchars($playData[0]["long_desc"]);
      $start = $playData[0]["stime"];
      $start =  date('Y-m-d\TH:i',strtotime($start));
      $start = "value='{$start}'";
      $end = $playData[0]["etime"];
      $end = date('Y-m-d\TH:i',strtotime($end));
      $end = "value='{$end}'";
      //IMG ENCODING NOT VERY EFFICIENT AS IMAGE GETS UPDATED AGAIN
      $img = null;
      /*
      $path = "../images/plays/{$playID}.png";
      $exists = file_exists($path);
      if($exists){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $img = "value='{$base64}'";
      }*/
       
    ?>
      <div class="col-lg-5 col-xl-6"><?php include 'playForm.php';?> </div>
      
    </div>
    <?php elseif(!isset($playID)):?>
    <?php else:  $_SESSION["message"] = "ERROR: Play ID: {$playID} does not exist"; ?>      
    <?php endif; ?>
    <div class="d-grid gap-2">
        <a href ="addPlay.php" class="btn btn-secondary btn-lg" type="button">Add a New Play</a>
    </div>
  </div>

<?php  include "../notification.php"?>
<?php include 'crop.php';?>
</body>
</html>
<script>
function selectPlay(playOption){
  id = playOption.value;
  window.location.href = "index.php?playID=" + id + "#play" + id;
}
function changeSubmit(){
  submit = document.getElementById("submit");
  submit.classList.replace("btn-secondary","btn-success");
  submit.innerHTML = "Update Play";
  submit.name = "update";
}
function deletePlay(playID){
  console.log("delete play" + playID);
}
function updateCard(input, playID){
    newValue = input.value;
    htmlModf = document.getElementById("Card" + input.id);
    if(input.id=="sDate"){
      playDate = new Date(input.value);
      startTime = playDate.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") + " - ";
      document.getElementById("startingTime").innerHTML = startTime; 
      playDate =  ('0' + (playDate.getMonth()+1)).slice(-2) + '/' + ('0' + playDate.getDate()).slice(-2) + '/' + playDate.getFullYear();
      newValue = playDate; 
    }
    if(input.id=="eDate"){
      playDate = new Date(input.value);
      endTime = playDate.toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
      newValue = endTime; 
      htmlModf = document.getElementById("endingTime"); 
    }
    htmlModf.innerHTML = newValue; 

}
</script>


