<!DOCTYPE html>
<html>
<head>
  <title>Management Area</title>
  <?php include 'dependencies.php';?>
</head>
<?php 
include '../classes/db.class.php';
include '../classes/seat.class.php';
include '../classes/seat-view.class.php';
include '../classes/play.class.php';
include '../classes/play-view.class.php';
$playCard = new PlayView();
if(!isset($_GET["playID"])){
    header("location: index.php?noSeatData");
}   
//Get play data 
$playID = $_GET["playID"];
$playCard->requestPlay($playID,0);
$playData = $playCard->getPlayInfo();

//If palyDATA is null then play doesn't exist 
if($playData==null){
  header("location: index.php?NOFOUND");
}

//Get the play Seats by its ID 
$seat = new SeatView($playID);

?>

<body>

<?php include 'navbar.php';?>
<div class="topImg" style="background-image:url('../images/seats.jpg')">
    <div class="txt-overlay d-flex justify-content-center align-items-center">
      <h1 class="eTitle">Modify Seats</h1>
    </div>
</div>
<div class="container">

  <div class="row">
    <!-- Print Play Card -->
    <?php $playCard->printPlays("col-lg-5 col-xl-6 py-1",1);?>

    <div class="col-lg-7 col-xl-6 py-1">
      <!-- Print Graphic Seat Plan  -->
      <?php $seats = $seat->showSeats(1);?>
        <div class="d-grid py-1">
          <button type="button" onclick="toggleAll(this)" class="btn btn-secondary">Toggle All Seats</button>
        </div>
        <form action="includes/included-seats.php"  method="post" onsubmit="return updatePrices()">
            <div class="row">
                <div class="col-6">
                    <input type="number"  placeholder="New price here" min="0.01" step=".01" class="form-control"  name="cost" id="cost"  required>
                    <input type="hidden"  name="seatsJSON" id="seatsJSON" required>
                </div>
                <div class="col-6"><button id="submitButton" name="updateCost" type="submit" class="btn btn-success" disabled>Update Price</button></div>
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
<?php include "../notification.php"?>
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
    var seatsSelected = []; // array of seats object that contains the selected seats 
  /**
  * Function that handles the selection of seats and adds the seat to the selected list 
  * @param seat - HTML element of the input checkbox being selected (the seat in this case)
  */  
    function seatToggle(seat){

      cost = document.getElementById("cost"); //Cost input field (to modify prices)
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
        //console.log(seatsSelected); 

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
        //console.log(seatsSelected); 
      } 
    }

  /**
  * Function that toggles the row of the respective row button being clicked 
  * @param idArray[] - array of the ids of the row being selected
  */  
    function selectRow(rowLetter, idArray){
      currentButton = document.getElementById(rowLetter);

      
      if(currentButton.classList.contains("btn-secondary")){
        currentButton.classList.remove("btn-secondary");
        currentButton.classList.add("btn-danger");
      }
      else{
        currentButton.classList.remove("btn-danger");
        currentButton.classList.add("btn-secondary");
      }
      for (const i in idArray) {
        currentSeat = document.getElementById(idArray[i]);
        if(currentSeat.checked){
          currentSeat.checked = false;
        }
        else{
          currentSeat.checked = true; 
        }
        seatToggle(currentSeat);
      } 


    }
  /**
  * Function that toggles all the seats in the graphic plan 
  * @param currentButton - the html element of the button being clicked on 
  */  
    function toggleAll(currentButton){
      checkStatus = false; 
      if(currentButton.classList.contains("btn-secondary")){
        currentButton.classList.remove("btn-secondary");
        currentButton.classList.add("btn-danger");
        checkStatus = true; 
      }
      else{
        currentButton.classList.remove("btn-danger");
        currentButton.classList.add("btn-secondary");
        checkStatus = false; 
      }
      letter = "A"
      for(let i = 1;i<=8;i++){
        rowButton = document.getElementById(letter);
        rowButton.click();
        letter = nextChar(letter);
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
 * Function that updates the new prices in the selectedList created on 
 */
    function updatePrices(){
        if(seatsSelected.length==0){
          alert("Your haven't selected any seats");
          return false; 
        }
        for(let i=0;i<seatsSelected.length;i++){
        seatsSelected[i]["cost"] = cost.value; 
        }
        seatJ = JSON.stringify(seatsSelected);
        document.getElementById("seatsJSON").value = seatJ; 
        return true; 
    }
    
    
  </script>