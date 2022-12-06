<?php 

/**

 * Register class handles the queries executions to the database with data from the controller and view

 * @author Pedro Damian Marta Rubio https://github.com/damiansh

 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 

 * @version 1.0

 */

class Register extends PortalesDB{



    /**

     * setUser(): inserts the new customer into the database 

     *

     * @param string $email of the new customer 

     * @param string $psw password of the new customer 

     * @param string $fname first name of the new customer 

     * @param string $lname last name of the new customer 

     * @param string $birthday the birthday of the new customer 

     * @param string $phone the phone number of the new customer 

     * @return void

     */ 

    protected function setUser($email,$psw,$fname,$lname,$birthday,$phone){

        $query = 'INSERT INTO customers (user_email, user_psw, user_fname, user_lname, user_birthday, user_phone, activation_code) VALUES (?,?,?,?,?,?,?);';

        $statement = $this->connect()->prepare($query);



        //hash password and activation code !IMPORTANT for security

        $hashedPsw = password_hash($psw, PASSWORD_DEFAULT);

        $code = bin2hex(random_bytes(16));

        $activation = password_hash($code,PASSWORD_DEFAULT);



        //this checks if the query is executed sucesfully 

        if(!$statement->execute(array($email, $hashedPsw, $fname, $lname, $birthday, $phone, $activation))){

            $statement = null;

            header("location: ../register.php?error=settingUser");

            exit();

        }

        //we send confirmation email 

        require 'sendEmail.php';

        require '../vendor/autoload.php';

        portalesEmail($email,"Email Confirmation, Activate your account now!",$this->emailBody($code,$email,$fname,$lname));

        $statement = null;

    

    }

    

    

    /**

     * checkEmail(): queries the database and checks if the given email already exists 

     *

     * @param string $email of the new customer 

     * @return void

     */ 

    protected function checkEmail($email){

        $query = 'SELECT user_email FROM customers WHERE user_email = ?;';

        $statement = $this->connect()->prepare($query);



        if(!$statement->execute(array($email))){

            $statement = null;

            header("location: ../register.php?error=checkingEmail");

            exit();

        }

        $resultCheck;

        if($statement->rowCount() > 0){

            $resultCheck = false;

        }

        else{

            $resultCheck = true;

        }



        return $resultCheck;

    }



    /**

     * emailBody(): generates the body of the email to be send 

     *

     * @param string $activation code for the new customer 

     * @param string $email email of the new customer 

     * @param string $fname first name of the new customer 

     * @param string $lname last name of the new customer

     * @return void

     */ 

    protected function emailBody($activation,$email, $fname, $lname){

        $greeting = "<h2>Welcome {$fname} {$lname} to Los Portales Theatre!</h2>";

        $greeting = "{$greeting}<p>Thank you for joining us.</p>";

        $greeting = "{$greeting}<p>Before you can start enjoying all the benefits of Los Portales Theatre, you need to activate your account.</p>";

        $hostname = getenv('HTTP_HOST');

        $link ="http://{$hostname}/auth/activate.php?email={$email}&code={$activation}";

        //$link ="http://{$hostname}/losportales/auth/activate.php?email={$email}&code={$activation}"; //comment this when testing live

        $a = "<a href='{$link}' target='_blank'><strong>here</strong></a>";

        $body ="{$greeting}<p>Please click {$a} to confirm and activate your account on Los Portales Theatre.</p>";

        return "<html>{$body}</html>";

    }

}