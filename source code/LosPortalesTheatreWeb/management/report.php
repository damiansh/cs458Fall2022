<!DOCTYPE html>
<html>
<head>
  <title>Management Area</title>
  <?php include 'dependencies.php';?>
</head>
<body>
<?php 
include '../classes/db.class.php';
include '../classes/play.class.php';
include '../classes/play-view.class.php';

//get play data for dropdown
$plays = new PlayView();
$plays->requestAllPlays();
$plays = $plays->getPlayInfo();
//echo "<script>console.log(" .json_encode($plays) . ");</script>"; debug play get

//Play info for javascript
$playJ = null; 
//get data for playCard
if(isset($_GET["playID"])){
  $playCard = new PlayView();
  $playID = $_GET["playID"];
  $playCard->requestPlay($playID,0);
  //Get play data 
  $playData = $playCard->getPlayInfo();
  $playJ = $playData; 
  //check if playID exists 
  $exists = false;
  foreach ($plays as $play){
    if($play["play_id"]==$playID)
      $exists = true; 
  }
}   
?>
<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('../images/report.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Generate Report</h1>
    </div>
</div>

<div class="container py-3">
    <p>Select a play by its name and date of showing to generate a sale report</p>
    <select id="selectPlay" class="form-select form-select-lg mb-3" onchange="selectPlay(this)" aria-label=".form-select-lg example">

      <option selected>Select a Play by date</option>
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
      $playCard->printPlays("col-lg-4 col-xl-4",3); //print playCard

      include '../classes/transaction.class.php';
      include '../classes/transaction-view.class.php';
      $transaction = new TransactionView();

      //Get transaction data by playID 
      $transaction->requestPlayTransactions($playID);
      $transactionData = $transaction->getInfo();
      //If transaction is null then play doesn't have anything yet
      if($transactionData==null){
        echo "<div class='col-lg-8 col-xl-8'><h3>The selected play does not have any sales yet</h3></div>";
      } 
      else{
        echo "<div class='col-lg-8 col-xl-8'>";
        echo "<p class='phoneWarning'>Your screen is to small to display the report on the web, click at download to download the report as a spreadsheet</p>";
        echo "<button id='download-button' class='btn btn-secondary'>Download Spreadsheet</button>";

        $transaction->generateReport();
        echo "</div>";

      }
      
    
    ?>
  
    </div>
    <?php elseif(!isset($playID)):?>
    <?php else:  $_SESSION["message"] = "ERROR: Play ID: {$playID} does not exist"; ?>      
    <?php endif; ?>

  </div>

<?php  include "../notification.php"?>
</body>
<script>
  function selectPlay(playOption){
  id = playOption.value;
  window.location.href = "report.php?playID=" + id + "#selectPlay";
}


//code from https://yourblogcoach.com/export-html-table-to-csv-using-javascript/
function htmlToCSV(html, filename) {
	var data = [];
	var rows = document.querySelectorAll("table tr");
			
	for (var i = 0; i < rows.length; i++) {
		var row = [], cols = rows[i].querySelectorAll("td, th");
				
		for (var j = 0; j < cols.length; j++) {
		        row.push(cols[j].innerText);
        }
		        
		data.push(row.join(",")); 		
	}

	downloadCSVFile(data.join("\n"), filename);
}
//code from https://yourblogcoach.com/export-html-table-to-csv-using-javascript/

function downloadCSVFile(csv, filename) {
	var csv_file, download_link;

	csv_file = new Blob([csv], {type: "text/csv"});

	download_link = document.createElement("a");

	download_link.download = filename;

	download_link.href = window.URL.createObjectURL(csv_file);

	download_link.style.display = "none";

	document.body.appendChild(download_link);

	download_link.click();
}
//code from https://yourblogcoach.com/export-html-table-to-csv-using-javascript/
document.getElementById("download-button").addEventListener("click", function () {
	var html = document.querySelector("table").outerHTML;
  <?php
     $saveDate = "";
      $saveTitle = "";
      if($playJ!=null){
        $saveDate = date('m/d/Y',strtotime($playJ[0]["stime"]));
        $saveTitle = $playJ[0]["play_title"];
      }

  ?>
	htmlToCSV(html, "<?php  echo  "{$saveDate} - {$saveTitle}";?>");
});
</script>
</html>

