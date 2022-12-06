<?php 
/**
 * Payment class handles the queries executions to the database with data from the controller and view
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class Payment extends PortalesDB{

    /**
     * requestPaymentInfo(): selects payment information by user_id
     *
     * @global int $_SESSION["userid"] customer id 
     * @return object|null with the payment information found in the query 
     */     
    protected function requestPaymentInfo(){
        $userID = $_SESSION["userid"];
        $query = 'SELECT * FROM payment WHERE user_id = ?;';
        $statement = $this->connect()->prepare($query);
        
        //to check if query was sucesfully run
        if(!$statement->execute(array($userID))){
            $statement = null;
            header("location: index.php?error=paymentInfoError");
            exit();
        }
        
        //Check if there was a match, if not, the payment method has not been added
        if($statement->rowCount()==0){
            $statement = null;
            return null;          
        }

        $paymentInfo = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the payment info
        $statement = null;
        return $paymentInfo; 
    }

    /**
     * insertPaymentInfo(): inserts the payment information received to the database
     *
     * @global int $_SESSION["userid"] customer id 
     * @param int $cnumber credit/debit card last four digits 
     * @param string $ctitular the name on the card
     * @param int $cmonth expiration's month
     * @param int $cyear expiration's year
     * @param int $ccvc the cvc of the card
     * @param string $cbilling billing address associated to the card
     * @param int $czip zip code of the billing address
     * @return void
     */     
    protected function insertPaymentInfo($cnumber, $ctitular, $cmonth, $cyear, $ccvc, $cbilling, $czip){
        $userID = $_SESSION["userid"]; 
        $query = 'INSERT INTO payment (user_id,cnumber,ctitular,cmonth,cyear,ccvc,cbilling,czip) VALUES (?,?,?,?,?,?,?,?);';
        $statement = $this->connect()->prepare($query);
        
        //to check if query was sucesfully run
        if(!$statement->execute(array($userID,$cnumber, $ctitular, $cmonth, $cyear, $ccvc,$cbilling,$czip))){
            $statement = null;
            header("location: ../addPayment.php?error=AddingPayment");
            exit();
        }
   
        $statement = null;
    } 

    /**
     * deletePayment(): deletes payment information by user_id
     *
     * @global int $_SESSION["userid"] customer id 
     * @return void
     */     
    public function deletePayment(){
    $userID = $_SESSION["userid"];
    $query = 'DELETE FROM payment WHERE user_id = ?;'; 
    $statement = $this->connect()->prepare($query);

    //to check if query was sucesfully run
    if(!$statement->execute(array($userID))){
        $statement = null;
        $_SESSION["message"] = "ERROR DELETING PAYMENT Method.";
        header("location: ../payment.php?error=deletePayment");
        exit();
    }
    
    $statement = null;

    }        
}