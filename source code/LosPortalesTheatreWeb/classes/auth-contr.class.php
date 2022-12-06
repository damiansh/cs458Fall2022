<?php 
/**
 * AuthContr is the controller class for the Auth class that confirms the email of the customer
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class AuthContr extends Auth{
    private $email;
    private $code;

    /**
     * Initializes this class with the given options.
     *
     * @param string $email the email of the customer
     * @param string $code code to confirm that confirmation of the email is valid 
     * @return void
     */   
    public function __construct($email,$code){
        $this->email = $email;
        $this->code = $code;
    }

    /**
     * authUser(): executes error handlers and if passes, sends the necessary information to confirm a customer's email. 
     *
     * @return void
     */  
    public function authUser(){
        if($this->missingInput()==true){
            //Missing some of the inputs
            session_start();
            $_SESSION["message"] = "Error: Try clicking again in the confirmation email.";
            header("location: ../register.php?authError");
            exit();
        }

        $this->confirmEmail($this->email,$this->code);
    }

    /**
     * missingInput(): error handler that checks if input is missing 
     *
     * @return boolean true==error, false==OK
     */  
    private function missingInput(){
        if(empty($this->email) || empty($this->code)){
            return true;
        }
        return false;
    }


}