<?php 

/**

 * PlayView is the view class for the Play class. Handles displaying/outputting information

 * @author Pedro Damian Marta Rubio https://github.com/damiansh

 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 

 * @version 1.0

 */

class PlayView extends Play implements JsonSerializable {

    private $plays;



    /**

     * requestPublished(): request to the database all the upcoming and published plays 

     *

     * @return void

     */      

    public function requestPublished(){

        $this->plays = $this->getUpcomingPlays(); 

    }

    

    

    /**

     * requestAllPlays(): request to the database all the plays in the system  

     *

     * @return void

     */      

    public function requestAllPlays(){

        $this->plays = $this->getAllPlays(); 

    }

    

    /**

     * requestAllPlays(): request to the database for the play with the given id 

     *

     * @param int $playID id of the play 

     * @param int $modal MODAL = 0 no criteria, MODAL = 1 then only published and upcoming plays

     * @return void 

     */       

    public function requestPlay($playID,$modal){

        $this->plays = $this->getPlay($playID,$modal); 

    }





    /**

     * printPlays(): method to print the play information to html 

     *

     * @param string $column contains the class information for the div parent generated

     * @param int $modal MODAL = 0 (purchase card), 1 (long desc card), 2 (modify card)

     * @return void 

     */    

    public function printPlays($column,$modal){

        //modal=0 homepage, modal=1, graphicSeatPlan, modal=2, modify play

        $plays = $this->plays;

        $desc = "short_desc";

        if($modal==1) $desc = "long_desc";

        if($plays==null){

            echo "There is not upcoming plays at this moment.";

        }

        else{

            //Loop through all the upcoming plays 

            foreach ($plays as $play){

                echo $this->playCard($play["play_id"],$play["play_title"],$play[$desc],$play["stime"],$play["etime"],$play["published"],$play["pURL"],$column,$modal)->saveHTML();



            }

            echo "<script>playCard()</script>";

            // echo $dom;

        }

    }



    /**

     * getPlayInfo(): returns the Play object 

     *

     * @return object $plays: object with the play information

     */     

    public function getPlayInfo(){

        return $this->plays; 

    }



    /**

     * printPlays(): method to print the play information to html 

     *

     * @param int    $playID  ID of the play to be printed 

     * @param string $playTitle title of the play to be printed

     * @param string $desc synopsis for the play to be printed 

     * @param string $sTimeR the start date time for the play to be printed 

     * @param string $eTimeR the end date time for the div parent generated

     * @param int    $published the published status for the play, 0==unpublished, 1== published 

     * @param string $pURL the url for the imagen of the play to be printed 

     * @param string $column  the class information for the div parent generated

     * @param int $modal MODAL = 0 (purchase card), 1 (long desc card), 2 (modify card)

     * @return HTML $dom, the html createad gets returne to the printPlays 

     */    

   public function playCard($playID, $playTitle, $desc,$sTimeR, $eTimeR, $published, $pURL, $column, $modal){

        //htmlspecialchars 

        $playTitle = htmlspecialchars($playTitle);

        $desc = htmlspecialchars($desc);



        //Creaste DOM ELEMENT

        $dom = new DOMDocument('1.0', 'utf-8');

        //Set Date and Time  Format

        $date = date('m/d/Y',strtotime($sTimeR));

        $sTime = date('h:i a',strtotime($sTimeR));

        $eTime = date('h:i a',strtotime($eTimeR));



        //image url 

        $uri = $_SERVER['REQUEST_URI'];

        $link ="images/plays/";

        $find = strpos($uri,"management");

        //if executing in management, change path

        if($find){

            $link ="../images/plays/";

        }

        //check if file exists, if not, use placeholder image

        $exists = file_exists($link . $playID . ".png");

        if(!$exists){

            $link = $link . "placeholder.png";

        }

        else{

            $link = $link . $pURL;

        }





        //<div class="">

        $colmd = $dom->createElement('div', ''); 

        $colmd->setAttribute("class",$column);

        $colmd->setAttribute("id","play{$playID}");

        $dom->appendChild($colmd);



        //<div class="card mb-3 text-white black text-center">

        $cardmb3 = $dom->createElement('div', ''); 

        $cardmb3->setAttribute("class","card mb-3 text-white black text-center");

        $colmd->appendChild($cardmb3); //is inside colmd



        //<div class="row">

        $row = $dom->createElement('div', ''); 

        $row->setAttribute("class","row");

        $cardmb3->appendChild($row); // is inside cardmb3



        // <div class="thumbnail text-center"> 

        $thumbnail = $dom->createElement('div', ''); 

        $thumbnail->setAttribute("class","thumbnail text-center");

        $row->appendChild($thumbnail); //is inside row

    

        // <img class="card-img-top darker" src="images/plays/placeholder.png" alt="Play Name">

        $img = $dom->createElement('img', ''); 

        $img->setAttribute("class","card-img-top darker");

        $img->setAttribute("id","cardPlayImage");

        $img->setAttribute("src",$link);

        $img->setAttribute("alt",$playTitle);

        $thumbnail->appendChild($img); //is inside thumbnail







        //<div class="card-body">

        $cardBody = $dom->createElement('div', ''); 

        $cardBody->setAttribute("class","card-body");

        $cardmb3->appendChild($cardBody); // is inside cardmb3



    

        //Play Title <h1 class="card-title league">Here the name of the play</h1> playTitle

        $title = $dom->createElement("h2", $playTitle); 

        $title->setAttribute("class","playTitle league");

        $title->setAttribute("id","CardplayTitle");

        $cardBody->appendChild($title); //is inside caption        



        //<div class="btn-group" role="group">

        $btnGroup = $dom->createElement('div', ''); 

        $btnGroup->setAttribute("class","btn-group py-1");

        $btnGroup->setAttribute("role","group");

        $cardBody->appendChild($btnGroup); // is inside card body



        //DATE OF THE PLAY <button type="button" class="btn btn-dark fw-bold">Start Date</button> sDate

        $date = $dom->createElement('button', $date); 

        $date->setAttribute("type","button");

        $date->setAttribute("class","btn btn-dark fw-bold");

        $date->setAttribute("id","CardsDate");

        $btnGroup->appendChild($date); // is inside button group



        //Start and End Time <button type="button" class="btn btn-dark fw-bold">8:00 P.M. - 10:00 P.M.</button>

        $time = $dom->createElement("button", ""); 

        $time->setAttribute("type","button");

        $time->setAttribute("class","btn btn-dark fw-bold");

        $btnGroup->appendChild($time); // is inside button group 

        

        //Stime

        $timeStart = $dom->createElement("span", "{$sTime} - "); 

        $timeStart->setAttribute("id","startingTime");

        $time->appendChild($timeStart); // is inside button group 

        //eTime

        $timeEnd = $dom->createElement("span", "{$eTime}"); 

        $timeEnd->setAttribute("id","endingTime");

        $time->appendChild($timeEnd); // is inside button group 

        

        //Description <p class="card-text">Desc</p> shortDesc

        $desc = $dom->createElement("p", $desc); 

        $desc->setAttribute("class","card-text");

        $desc->setAttribute("id","CardshortDesc");

        $cardBody->appendChild($desc); // is inside cardbody



        //Purchase <button type="button" class="btn btn-secondary">Purchase or Log In </button> 

        if($modal==0){ //only purchase if in homepage

            $purchase = $dom->createElement('a', 'Select your seats'); 

            $purchase->setAttribute("href","selectSeats.php?playID=" . urlencode($playID));

            if(!isset($_SESSION["userid"])){

                $purchase = $dom->createElement('a', 'Login'); 

                $purchase->setAttribute("href","login.php");

            }

            $purchase->setAttribute("class","btn btn-secondary");

            $cardBody->appendChild($purchase); // after desc in cardBody  

        }

        else if($modal==2){ //modify Play modal 

            //<form action="includes/included-play.php"  method="post">

            $form = $dom->createElement('form', ''); 

            $form->setAttribute("action","includes/included-play.php");

            $form->setAttribute("method","POST");

            $cardBody->appendChild($form); 

            // <input type="hidden"  name="playID" id="playID" required>

            $hiddenID = $dom->createElement('input', ''); 

            $hiddenID->setAttribute("type","hidden");

            $hiddenID->setAttribute("name","playID");

            $hiddenID->setAttribute("value","{$playID}");

            $form->appendChild($hiddenID);

            //<input type="hidden"  name="published" id="published" required>

            $hiddenP = $dom->createElement('input', ''); 

            $hiddenP->setAttribute("type","hidden");

            $hiddenP->setAttribute("name","published");

            if($published==0) $flip = 1; 

            if($published==1) $flip = 0; 

            $hiddenP->setAttribute("value","{$flip}");

            $form->appendChild($hiddenP); 

            //Modify Button

            $modify = $dom->createElement('a', 'Modify Seats'); 

            $modify->setAttribute("href","modifySeats.php?playID=" . urlencode($playID));

            $modify->setAttribute("class","btn btn-primary");

            $form->appendChild($modify); 

            //Delete Button

            $bText = "Publish";

            $bClass = "btn btn-success";

            if($published==1){

                $bText = "Unpublish";

                $bClass = "btn btn-warning";

            }

            $publish = $dom->createElement("button", $bText); 

            $publish->setAttribute("type","submit");

            $publish->setAttribute("name","publish");

            $publish->setAttribute("class",$bClass);

            $form->appendChild($publish); 

            //Delete Button

            $delete = $dom->createElement("button", "Delete Play"); 

            $delete->setAttribute("type","submit");

            $delete->setAttribute("name","delete");

            $delete->setAttribute("class","btn btn-danger");

            $form->appendChild($delete); 

        }



        return $dom;

    }

   /**

     * jsonSerialize(): implementation of jsonSerialize

     *

     * @return object

     */      
    public function jsonSerialize() {
        return $this->plays;
    }



}