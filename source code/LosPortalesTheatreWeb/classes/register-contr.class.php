<?php 
/**
 * RegisterContr is the controller class for the Register class. Handles and receives the registration info
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class RegisterContr extends Register{
    private $email;
    private $psw;
    private $pswRepeat;
    private $fname;
    private $lname;
    private $birthday;
    private $phone;
    
    /**
     * Initializes this class with the given options.
     *
     * @param string $email of the new customer 
     * @param string $psw password of the new customer 
     * @param string $pswRepeat confirmation of the password
     * @param string $fname first name of the new customer 
     * @param string $lname last name of the new customer 
     * @param string $birthday the birthday of the new customer 
     * @param string $phone the phone number of the new customer 
     */  
    public function __construct($email,$psw,$pswRepeat,$fname,$lname,$birthday,$phone){
        $this->email = $email;
        $this->psw = $psw;
        $this->pswRepeat = $pswRepeat;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->birthday = $birthday;
        $this->phone = $phone;

    }

    /**
     * registerUser(): executes error handling for registration and calls the database to add the information
     *
     * @return void
     */   
    public function registerUser(){
        if($this->missingInput()==true){
            //Missing some of the inputs
            $result = array('message'=>'Error: Fill in all the fields.', 'status'=>'signupError');
            return $result;
            //header("location: ../register.php?signupError");
        }
        if($this->invalidEmail()==true){
            //Invalid email format
            $result = array('message'=>'Error: Invalid e-mail.', 'status'=>'signupError');
            return $result;
            //header("location: ../register.php?signupError");
        }
        if($this->pswMatch()==false){
            //Passwords don't match
            $result = array('message'=>"Error: The passwords you've entered don't match.", 'status'=>'signupError');
            return $result;
            //header("location: ../register.php?signupError");
        }
        if($this->emailUsed()==true){
            //Email already exists
            $result = array('message'=>'Error: This email is already in use.', 'status'=>'signupError');
            return $result;
            //header("location: ../register.php?signupError");
        }
        $this->setUser($this->email,$this->psw,$this->fname,$this->lname,$this->birthday,$this->phone);

        //we return a successfull message
        return array('message'=>"A confirmation e-mail has been sent to your account. Check your spam folder if you don't see the e-mail.", 'status'=>'confirmedEmail');
    }

    /**
     * missingInput(): checks for missing inputs
     * @return boolean true==error, false==OK
     */     
    private function missingInput(){
        $cEmail = empty($this->email);
        $cp = empty($this->psw);
        $cp2 = empty($this->pswRepeat);
        $cfn = empty($this->fname);
        $cln = empty($this->lname);
        $cBirthday = empty($this->birthday);
        $cPhone = empty($this->phone);

        if($cEmail || $cp || $cp2 || $cfn || $cln || $cBirthday || $cPhone){
            return true; 
        }
        return false; 
    }

    /**
     * invalidEmail(): checks if the given has an invalid format 
     * @return boolean true==error, false==OK
     */ 
    private function invalidEmail(){
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            return true; 
        }
        return false; 
    }

    /**
     * pswMatch(): checks if the given passwords match 
     * @return boolean true==match, false==error, no match 
     */ 
    private function pswMatch(){
        if($this->psw !== $this->pswRepeat){
            return false; 
        }
        return true; 
    }

    /**
     * emailUsed(): checks if the given email already exists 
     * @return boolean true==error, it exists. false==ok, doesn't exist 
     */ 
    private function emailUsed(){
        if(!$this->checkEmail($this->email)){
            return true; 
        }
        return false;
    }
}