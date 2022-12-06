<!DOCTYPE html>
<html>
<head>
  <title>Print Tickets</title>
  <?php include 'dependencies.php';?>

</head>
<?php 
include '../classes/db.class.php';
include '../classes/transaction.class.php';
include '../classes/transaction-view.class.php';
$transaction = new TransactionView();
if(!isset($_GET["transactionID"])){
    header("location: ../index.php?error");
}   

if(!isset($_SESSION["userid"])){
  header("location: ../login.php?print={$_GET['transactionID']}");
  exit; 
}

//Get transaction data
$transactionID = $_GET["transactionID"];
$transaction->requestTransaction($transactionID,$_SESSION["userid"]);
$transactionData = $transaction->getInfo();
//if null, the ticket does not exist or the user does not have access 
if($transactionData==null){
  header("location: ../index.php?AccessDenied");
  exit; 
}

?>
<body>

<div class="container">
<div class="d-grid gap-2 d-print-none">
  <button class="btn btn-secondary btn-lg" type="button" onclick="window.print()">Print tickets</button>
</div>
<?php $transaction->printTickets();?>
</div>

</body>

</html>
