<?php 
/**
 * PaymentContr is the controller class for the Payment class. Handles and receives the payment information
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class PaymentContr extends Payment{
    private $cnumber;
    private $ctitular;
    private $cmonth;
    private $cyear;
    private $ccvc; 
    private $cbilling; 
    private $czip; 


    /**
     * Initializes this class with the given options.
     *
     * @param int $cnumber credit/debit card last four digits 
     * @param string $ctitular the name on the card
     * @param int $cmonth expiration's month
     * @param int $cyear expiration's year
     * @param int $ccvc the cvc of the card
     * @param string $cbilling billing address associated to the card
     * @param int $czip zip code of the billing address
     */ 
    public function __construct($cnumber, $ctitular, $cmonth, $cyear, $ccvc, $cbilling, $czip){
        $this->cnumber = $cnumber;
        $this->ctitular = $ctitular;
        $this->cmonth = $cmonth;
        $this->cyear = $cyear;
        $this->ccvc = $ccvc; 
        $this->cbilling = $cbilling; 
        $this->czip = $czip; 

    }

    /**
     * addPaymentInfo(): executes error handling for payment and then calls to insert info to database
     *
     * @return void
     */  
    //Method to generate Graphic Seat Plan 
    public function addPaymentInfo(){
        //execute error handling
        $this->errorHandlers("addPayment");
        $this->insertPaymentInfo($this->cnumber, $this->ctitular, $this->cmonth, $this->cyear, $this->ccvc, $this->cbilling, $this->czip); //add payment info 
    }

    /**
     * errorHandlers(): executes all the error handling methods for the class 
     * @param string $page destination for the errors 
     * @return void
     */ 
    private function errorHandlers($page){
        //Error handling for missing input 
        if($this->missingInput()){
            $_SESSION["message"] = "Error: Fill in all the Payment Information."; 
            header("location: ../{$page}.php?MissingPaymentInfo");
            exit();
        }
        if($this->checkMonth()){
            $_SESSION["message"] = "Error: Invalid month"; 
            header("location: ../{$page}.php?MissingPaymentInfo");
            exit();
        }
        if($this->isExpired()){
            $_SESSION["message"] = "Error: Your card is expired."; 
            header("location: ../{$page}.php?ExpiredCard");
            exit();
        }
        if($this->isValid()){
            $_SESSION["message"] = "Error: Your card information is invalid."; 
            header("location: ../{$page}.php?invalidCard");
            exit();
        }
    }

    /**
     * missingInput(): checks for missing inputs
     * @return boolean true==error, false==OK
     */ 
    private function missingInput(){
        $a = empty($this->cnumber);
        $b = empty($this->ctitular);
        $c = empty($this->cmonth);
        $d = empty($this->cyear);
        $e = empty($this->ccvc);
        $f = empty($this->cbilling);
        $g = empty($this->czip);




        if($a || $b || $c || $d || $e || $f || $g){
            return true;
        }

        return false;
    }

    /**
     * checkMonth(): checks if the month is invalid (>12)
     * @return boolean true==error, false==OK
     */ 
    private function checkMonth(){
        $month = $this->cmonth;

        if($month<1 || $month>12){
            return true; 
        }
        return false;
    }

    /**
     * isExpired(): checks if card is already expired 
     * @return boolean true==error, false==OK
     */     
     private function isExpired(){
        date_default_timezone_set('America/Boise'); // change to MOUNTAIN TIME
        $currentyear = date('y'); 
        $currentmonth = date('m'); 
        $year = $this->cyear;
        $month = $this->cmonth;

        if($year<$currentyear){
            return true; 
        }
        else if($year==$currentyear && $month<$currentmonth){
            return true; 
        }
        return false;
    }   

    /**
     * isValid(): checks if the card is valid by cvc length
     * @return boolean true==error, false==OK
     */       
     private function isValid(){
        $ccode = $this->ccvc;
        $digits = strlen($ccode);
        if($digits<3 || $digits>3){
            return true; 
        }

        return false;
    }       

}