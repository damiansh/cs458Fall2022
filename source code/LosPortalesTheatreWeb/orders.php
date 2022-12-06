<!DOCTYPE html>
<html>
<head>
  <title>Orders</title>
  <?php include 'dependencies.php';?>
</head>

<body>

<?php include 'navbar.php';?>
<?php 
if(!isset($_SESSION["userid"])){
  header("location: login.php");
}
include 'classes/transaction.class.php';
include 'classes/transaction-view.class.php';

//get transaction data for dropdown
$transaction = new TransactionView();
$transaction->requestAllUserTransactions($_SESSION["userid"]);
$transactionData = $transaction->getInfo();

?>

<div class="topImg" style="background-image:url('images/report.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle"><?php echo "{$_SESSION['userFN']}'s ";?> Orders</h1>
    </div>
</div>

<div class="container py-3">
<div class="card">
  <h5 class="card-header league display-3">Select a transaction:</h5>
  <div class="card-body">
    <h5 class="card-title">Here you can find your previous purchases ordered by newest to oldest</h5>
    <select  id ="selector" class="form-select form-select-lg mb-3" onchange="selectTransaction(this)" aria-label=".form-select-lg example">

      <option value="0" selected>Select a transaction by number and date</option>
      <?php
        foreach ($transactionData as $t){
          $date = date('m/d/Y',strtotime($t["transaction_date"]));
          $time = date('h:i a',strtotime($t["transaction_date"]));
          $orderTotal = number_format($t["order_total"],2);
          $id = $t["transaction_id"];
          $option = "<option value='{$id}'>Transaction #{$id}: \${$orderTotal} {$date} {$time}</option>";
          echo $option;
        }
      ?>
    </select>

    <div class="d-grid gap-2">
        <button id ="print" type="button" class="btn btn-secondary btn-lg" onclick="printTickets()" disabled >Print</a>
    </div>  
  </div>
</div>

  </div>


</body>
</html>
<script>
id=0;
function selectTransaction(option){
  id = option.value;
  button = document.getElementById("print");
  if(id>0){
    button.disabled = false;
    button.innerHTML = "Print Tickets for Transaction #" + id;
    return;
  }
  button.disabled = true;
  button.innerHTML = "Print";

}
function printTickets(){
    window.open("tickets/printTickets.php?transactionID=" + id, '_blank');
}
</script>


