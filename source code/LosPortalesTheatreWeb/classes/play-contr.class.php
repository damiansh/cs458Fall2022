<?php 
/**
 * PlayContr is the controller class for the Play class. Handles and receives the play information
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class PlayContr extends Play{
    private $playTitle;
    private $shortDesc;
    private $longDesc;
    private $sDate;
    private $eDate;
    private $cost;
    private $image_base64 =null;
    private $folderPath =null;

    /**
     * Initializes this class with the given options.
     *
     * @param string $playTitle title of the given play 
     * @param string $shortDesc short synopsis of the given play 
     * @param string $longDesc longer synopsis of the given play 
     * @param string $sDate start date time of the given play 
     * @param string $eDate end date time of the given play. Date must be the same, time can differ
     * @param float $cost default cost for the seats in the given play 
     */  
    public function __construct($playTitle, $shortDesc, $longDesc, $sDate, $eDate, $cost){
        $this->playTitle = $playTitle;
        $this->shortDesc = $shortDesc;
        $this->longDesc = $longDesc;
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->cost = $cost;
    }


    /**
     * addPlay(): executes error handling for PlayContr and prepares the process to create play 
     *
     * @return void
     */ 
    public function addPlay(){
        $this->errorHandlers("addPlay",null); //error handling
        $this->insertPlay($this->playTitle, $this->shortDesc, $this->longDesc, $this->sDate, $this->eDate);
        $playID = $this->getPlayID();
        $this->addSeats($playID, $this->cost);
        return $playID; 
    }


    /**
     * prepareChanges(): executes error handling for PlayContr and then prepares to update the changes in the play 
     *
     * @return void
     */ 
    public function prepareChanges($playID){
        $this->errorHandlers("index",$playID); //error handling
        $this->updatePlay($playID, $this->playTitle, $this->shortDesc, $this->longDesc, $this->sDate, $this->eDate);
    }

    /**
     * setImage(): method to set the image to be added to the server for the given play 
     *
     * @param string $image image information
     * @param string $folderPath folder path where the image is going to be uploaded
     * @param int $playID the id for the play 
     * @param string $page destination for the errors 
     * @return void
     */ 
    public function setImage($image, $folderPath,$playID,$page){
        $image_parts = explode(";base64,", $image);
        $this->image_base64 = base64_decode($image_parts[1]);
        $this->folderPath = $folderPath;
        $this->uploadImage($this->folderPath,$this->image_base64,$playID,$page);
    }

    
    /**
     * errorHandlers(): executes all the error handling methods for the class 
     * @param string $page destination for the errors 
     * @param int $playID the id for the play 
     * @return void
     */ 
    private function errorHandlers($page,$playID){
        if($playID!=null){
            $playID = "playID={$playID}&";
        }
        //Error handling for missing input 
        if($this->missingInput()){
            $_SESSION["message"] = "Error: Fill in all the Play Information."; 
            header("location: ../{$page}.php?{$playID}MissingPlayInfo");
            exit();
        }
        //Error handling for End Date cannot be less than Start Date
        if($this->isEndDateLess()){
            $_SESSION["message"] = "Error: End Date and Time cannot be equal or less than Start Date."; 
            header("location: ../{$page}.php?{$playID}DateError");
            exit();
        }
        //Error handling for Different Dates
        if($this->areDatesDifferent()){
            $_SESSION["message"] = "Error: Start Date and End Date must be in the same day"; 
            header("location: ../{$page}.php?{$playID}DateError");
            exit();
        }   
        
        //Error handling short description higher than expected
        if($this->isShortLonger()){
            $sl = strlen($this->shortDesc); 
            $_SESSION["message"] = "Error: Your short description cannot be more than 300 characters. ({$sl})"; 
            header("location: ../{$page}.php?{$playID}DateError");
            exit();
        }  

        //Error handling title higher than expected
        if($this->isTitleLonger()){
            $sl = strlen($this->playTitle); 
            $_SESSION["message"] = "Error: Your play title cannot be more than 36 characters ({$sl})"; 
            header("location: ../{$page}.php?{$playID}DateError");
            exit();
        }                
    }

    /**
     * missingInput(): checks for missing inputs
     * @return boolean true==error, false==OK
     */ 
    private function missingInput(){
        $a = empty($this->playTitle);
        $b = empty($this->shortDesc);
        $c = empty($this->longDesc);
        $d = empty($this->sDate);
        $e = empty($this->eDate);
        $f = empty($this->cost);


        if($a || $b || $c || $d || $e || $f){
            return true; 
        }
        return false; 
    }

    /**
     * isEndDateLess(): checks if end date time is lesser than start date time
     * @return boolean true==error, false==OK
     */     
    private function isEndDateLess(){
        $sDate = $this->sDate; 
        $eDate = $this->eDate; 
        if($eDate<=$sDate){
            return true; 
        }
        return false; 
    }

    /**
     * areDatesDifferent(): checks if the dates (not the time) are different
     * @return boolean true==error, false==OK
     */      
    private function areDatesDifferent(){
        $sDate = $this->sDate; 
        $eDate = $this->eDate; 
        $sDate = date('m/d/Y',strtotime($sDate));
        $eDate = date('m/d/Y',strtotime($eDate));

        if($eDate!=$sDate){
            return true; 
        }
        return false; 
    }    

    /**
     * isTitleLonger(): throws error if title is longer than 36 characters
     * @return boolean true==error, false==OK
     */     
    private function isTitleLonger(){
        $title = strlen($this->playTitle);
        if($title > 36){
            return true;
        }
        return false; 
     }

     
    /**
     * isShortLonger(): throws error if short description is longer than 300 characters
     * @return boolean true==error, false==OK
     */      
    private function isShortLonger(){
       $shortDescL = strlen($this->shortDesc);
       if($shortDescL > 300){
           return true;
       }
       return false; 
    }



}