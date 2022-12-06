<?php
    //other variables for form display
    $method = null;
    $changeDate = "oninput='equalDate(this)'";
    $previewImg =  '<div class="col-3"><img id="cardPlayImage" src=""></div>';
    $submit = "<div class='px-1'><button  type='submit' name ='addP' class='btn btn-secondary btn-lg'>Add Play</button></div>";
    if(isset($playID)){
        $method = "oninput='updateCard(this,{$playID})'";
        $changeDate = "oninput='updateCard(this,{$playID})'";
        $submit = "<div class='px-1'><button  type='submit' name ='update' class='btn btn-success btn-lg'>Save Changes</button></div>";
        $previewImg = null;        
    } 
?>
<form   action="includes/included-play.php"  method="post">
    <div class="form-floating mb-3">
        <input type="text" placeholder="Play Title" <?php echo $playTitle;?> class="form-control"  name="playTitle" id="playTitle" <?php echo $method;?> maxlength="36"  required>
        <label for="playTitle">Play Title:</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" placeholder="Short synopsis for the play" <?php echo $short;?> class="form-control"  name="shortDesc" id="shortDesc" maxlength="300" <?php echo $method;?> required>
        <label for="shortDesc" class="form-label">Short Synopsis:</label>
    </div>
    <div class="form-floating mb-3">
        <textarea class="form-control" id="longDesc" name="longDesc"  maxlength="1500" required placeholder="Longer description of the play" style="height: 100px"><?php echo $long;?></textarea>
        <label for="longDesc">Long Description:</label>
    </div>
    <div class="row g-2">
    <div class="col-6"><div class="form-floating mb-3">
        <input type="datetime-local"  class="form-control" <?php echo $start;?> name="sDate" id="sDate" min="<?php echo date("Y-m-d") . "T" . date("H:i"); ?>" <?php echo $changeDate;?> required>
        <label for="sDate" class="form-label">Start Date: </label>            
    </div> </div>
    <div class="col-6"><div class="form-floating mb-3">
        <input type="datetime-local"  class="form-control" <?php echo $end;?>  name="eDate" id="eDate" min="<?php echo date("Y-m-d") . "T" . date("H:i"); ?>" <?php echo $method;?> required>
        <label for="eDate" class="form-label">End Date: </label>
    </div></div>  
    </div>
    <?php if(!isSet($playID)): ?>   
    <div class="form-floating mb-3">
        <input type="number" value="20" placeholder="Default Price for Seats"  min="0.01" step=".01" class="form-control"  name="cost" id="cost" required>
        <label for="cost" class="form-label">Default Price:</label>
    </div> 
    <?php endif; ?>   
    <?php echo $previewImg;?>
    <div class="row">
        <div class="col-10">
            <input id="imageFile"  type="file" class="image form-control" onchange="deleteAction()">
            <input type="hidden" <?php echo $img;?> id="imageData" name="image">
            <input type="hidden" <?php echo $inputID;?> id="playIDinput" name="playIDinput">
        </div>
        <div class="col-2">
            <button id ="deleteButton" type="button" class="btn btn-danger d-none" onClick="deleteImage()" >X</button>
        </div>
    </div>
    <div class="btn-toolbar justify-content-center py-2">
        <?php echo $submit;?>
        <div class="px-1"><button type="reset" value="Reset" class="btn btn-danger btn-lg">Reset</button></div>
    </div>
</form>

<script>
//Autocomplete dates
function equalDate(inDate){
    mdfDate = document.getElementById("eDate");
    mdfDate.value = inDate.value;
}

function deleteImage(){
    imageFile = document.getElementById("imageFile");
    imageData = document.getElementById("imageData");
    deleteButton = document.getElementById("deleteButton");
    document.getElementById("cardPlayImage").src = "../images/plays/placeholder.png";
    imageFile.value = null;
    imageData.value = null;
    deleteButton.classList.add("d-none");
}

function deleteAction(){
    document.getElementById("deleteButton").classList.remove("d-none");
}

</script>
