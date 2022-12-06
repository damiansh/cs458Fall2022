<!DOCTYPE html>
<html>
<head>
  <title>Select your seats</title>
  <?php include 'dependencies.php';?>
</head>

<body>

<?php include 'navbar.php';?>
<?php 
include 'classes/play.class.php';
include 'classes/play-view.class.php';
$playCard = new PlayView();
if(!isset($_GET["playID"])){
    header("location: index.php?noSeatData");
}  
elseif(!isset($_SESSION["userid"])){
  header("location: login.php");
} 

include 'includes/isTherePaymentInfo.php';

//Get play data 
$playID = $_GET["playID"];
$playCard->requestPlay($playID,1);
$playData = $playCard->getPlayInfo();

//If palyDATA is null then play doesn't exist 
if($playData==null){
  header("location: index.php?NOFOUND");
}

//Get the play Seats by its ID 
$seat = new SeatView($playID);

?>
<div class="topImg" style="background-image:url('images/seats.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Select your seats</h1>
    </div>
</div>
<div class="container">

  <div class="row">
    <!-- Print Play Card -->
    <?php $playCard->printPlays("col-lg-5 col-xl-6 py-1",1);?>

    <div class="col-lg-7 col-xl-6 py-1">
      <!-- Print Graphic Seat Plan  -->
      <?php $seats = $seat->showSeats(0);?>
        <div class="d-grid py-1">
          <button type="button"  class="btn btn-secondary"></button>
        </div>
        <form action="includes/included-seatsC.php"  method="post" onsubmit="return addToCart()">
        <input type="hidden"  name="seatsJSON" id="seatsJSON" required>
            <div class="row">
                <div class="d-grid col-6"><button id="totalCost"  type="button" class="btn btn-dark" ><strong>Total:</strong> $0.00</button></div>
                <div class="d-grid col-6"><button id="submitButton" type="submit" name="addSeats" class="btn btn-success" disabled>Add seats to cart</button></div>
            </div>

            <div id="costLabel" class="d-flex flex-wrap" ></div>

        </form>
        <div class="d-grid py-3">
          <div class="d-grid gap-2" >
            <a href="index.php?playID=<?php echo "{$playID}#play{$playID}";?>"   role="button" class="btn btn-primary" >Go back</a>
          </div> 
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php';?>

</body>

</html>
<script>
    //Initialize popovert
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
    })
    //Getting seats information from the server side 
    var seatList = <?php echo json_encode($seats); ?>; //encoding seats object to json 
    //Seats Selected Array and total cost variable 
    var seatsSelected = []; // array of seats object that contains the selected seats 
    var total = 0; 

  /**
  * Function that handles the selection of seats and adds the seat to the selected list 
  * @param seat - HTML element of the input checkbox being selected (the seat in this case)
  */  
    function seatToggle(seat){
      totalCost = document.getElementById("totalCost"); //total cost field 
      costSel = document.getElementById("costLabel"); //Area where the selected seats are displayed 
      seatNumber = seat.dataset.seat; //getting the seat number fromt he object 
      seatButton = document.getElementById(seatNumber); //get the Toggled button
      submitButton = document.getElementById("submitButton"); //submit button


      //What to do when seat is being checked 
      if(seat.checked){
        //Set seat button to checkeed color 
        seatButton.classList.replace("btn-success","btn-warning");
        //Create selected seat button of the selected seat that go @costSel
        seatDiv = document.createElement("div"); 
        seatDiv.classList.add("p-1");
        seatTag = document.createElement("button");
        seatTag.setAttribute("type", "button");
        seatTag.classList.add("btn");
        seatTag.classList.add("btn-warning");
        seatTag.classList.add("btn-sm");

        seatTag.style.backgroundColor = "";
        seatDiv.id = "seat" + seat.id;
        seatTag.innerHTML = seat.id; 
        seatDiv.appendChild(seatTag); 
        costSel.appendChild(seatDiv); //append the button to costSel
        //Enable input and button if seats selected
        if(seatsSelected.length==0){
          submitButton.disabled = false; 
        }
        //add selected seats to the selected list
        seatsSelected.push(seatList[seatNumber-1]); 
        //add cost to total 
        total = total + parseFloat(seatList[seatNumber-1]["cost"]);
        totalCost.innerHTML = "<strong>Total:</strong> $" + total.toFixed(2); 
        console.log(seatsSelected); 

      }
      else{
        //get seat tag and remove the tag
        seatTag = document.getElementById("seat" + seat.id)
        costSel.removeChild(seatTag);
        //set seat button to not checked color 
        seatButton.classList.replace("btn-warning","btn-success");

        //remove from the list the seat unchecked 
        removee = seatsSelected.indexOf(seatList[seatNumber-1]);
        seatsSelected.splice(removee,1);
        if(seatsSelected.length==0){
          submitButton.disabled = true; 
        }
        //decrease total for deselecting seat
        total = total - parseFloat(seatList[seatNumber-1]["cost"]);
        totalCost.innerHTML = "<strong>Total:</strong> $" + total.toFixed(2); 
        console.log(seatsSelected); 
      } 
    }



 /**
 * Function that receives a character and returns the next one 
 * @param {String} c - the character sended in the call 
 */
    function nextChar(c) {
        var res = c == 'z' ? 'a' : c == 'Z' ? 'A' : String.fromCharCode(c.charCodeAt(0) + 1);
        return res; 
    }
    
/**
 * Function that adds seats as json to the input value 
 */
    function addToCart(){
        if(seatsSelected.length==0){
          alert("Your haven't selected any seats");
          return false; 
        }

        seatJ = JSON.stringify(seatsSelected);
        document.getElementById("seatsJSON").value = seatJ; 
        return true; 
    }
    
    
  </script>