<?php 
/**
 * LoginContr is the controller class for the Login class. Handles and receives the login information
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class LoginContr extends Login{
    private $email;
    private $psw;
    private $m;

    /**
     * Initializes this class with the given options.
     *
     * @param string $email the email of the customer
     * @param string $psw code to confirm that confirmation of the email is valid 
     * @param int $m stands for manager mode, 1=customer, 2=admin, 3=API
     */      
    public function __construct($email,$psw,$m){
        $this->email = $email;
        $this->psw = $psw;
        $this->m = $m;
    }

    /**
     * loginUser(): executes error handling for login and then calls the database
     *
     * @return void
     */     
    public function loginUser(){
        if($this->missingInput()==true){
            //Missing some of the inputs
            return array('message'=>'Error: Fill in all the fields.', 'status'=>'loginError', 'location'=>'login.php?');
        }

        return $this->getUser($this->email,$this->psw,$this->m);
    }

    /**
     * missingInput(): error handler that checks if input is missing 
     *
     * @return boolean true==error, false==OK
     */ 
    private function missingInput(){
        if(empty($this->email) || empty($this->psw)){
            return true;
        }
        return false;
    }


}