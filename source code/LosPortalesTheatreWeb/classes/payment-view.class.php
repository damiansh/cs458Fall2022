<?php 
/**
 * PaymentView is the view class for the Payment class. Handles displaying/outputting information
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class PaymentView extends Payment{
    private $paymentInfo; 

    
    /**
     * Initializes this class with the given options.
     *
     * @param object $paymentInfo object with the payment information from the database
     */     
    public function __construct(){
       $this->paymentInfo = $this->requestPaymentInfo();
    }

    /**
     * getPayInfo(): returns the paymentInfo object 
     *
     * @return object paymentInfo: object with the payment info gets returned 
     */  
    public function getPayInfo(){
        return $this->paymentInfo;  
    }


}