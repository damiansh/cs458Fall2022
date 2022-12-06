<?php 
/**
 * Login class handles the queries executions to the database with data from the controller and view
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class Login extends PortalesDB{

    /**
     * getUser(): selects the users from the database by given email and if psw hash is true then logs the user
     *
     * @param string $email the email of the customer
     * @param string $psw customer password
     * @param int $m manager modal, if 1 then customer, 2 is manager and 3 is API login 
     * @return array
     */ 
    protected function getUser($email,$psw,$m){
        $query = 'SELECT * FROM customers WHERE user_email = ?;';
        if($m==1){
            $query = 'SELECT * FROM admin WHERE user_email = ?;';
        }
        $statement = $this->connect()->prepare($query);
        
        //to check if query was sucesfully run
        if(!$statement->execute(array($email))){
            $statement = null;
            return array('message'=>"Error: Error while confirming if user exists", 'status'=>'getUserFailed', 'location'=>'login.php?');
        }
        
        //Check if there was a match, if not, it means there is not such email in the database
        if($statement->rowCount()==0){
            $statement = null;
            return array('message'=>"Error: The e-mail you’ve entered doesn't exist.", 'status'=>'loginError=email', 'location'=>'login.php?');
        }

        $user = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch al the user data based on email
        $checkPsw = password_verify($psw,$user[0]["user_psw"]); //check hashed pasword
        $active = $user[0]["active"];

        //If false the password is incorrect, otherwhise check if user is activated, if yes then log in
        if($checkPsw == false){
            $statement = null;
            return array('message'=>"Error: The password you’ve entered is incorrect.", 'status'=>'loginError=Password', 'location'=>'login.php?');
        }
        elseif($checkPsw==true and $active==1){
            session_start();
            session_unset();
            session_destroy();
            session_start();
            session_regenerate_id(); //to prevent session fixation attacks

            //login for customer 
            if($m==0){
                $_SESSION["userEmail"] = $user[0]["user_email"];
                $_SESSION["userid"] = $user[0]["user_id"];
                $_SESSION["userFN"] = $user[0]["user_fname"];
                $_SESSION["userLN"] = $user[0]["user_lname"];   
                $statement = null; 
                return array( 'status'=>'loggedIn', 'location'=>'?');
           
            }
            //login for management area
            else if($m==1){
                $_SESSION["adminid"] = $user[0]["user_id"];
                $statement = null;
                return array('status'=>'loggedIn', 'location'=>'?');
            }
            //login for API
            else if($m==2){
                //we create a key for the app login
                $key = bin2hex(random_bytes(16));
                $userID = $user[0]["user_id"]; 
                $this->addKey($key,$userID); //add the key 
                return array('status'=>'loggedIn', 'key'=>"{$userID}-{$key}");
            }

        }
        else{
            $statement = null;
            return array('message'=>"Error: Your account hasn't been activated, check your e-mail.", 'status'=>'loginError=NotActivated', 'location'=>'login.php?');
        }
    }

    /**
     * addKey(): adds the key to the user so it can be identified in the app 
     *
     * @param int $key random key generated every time the API is useed for loggin 
     * @param int $userID random key generated every time the API is useed for loggin 
     * @return array
     */ 
    protected function addKey($key, $userID){
        $query = 'UPDATE customers SET activation_code = ? WHERE user_id = ?;'; //confirming account
        $statement = $this->connect()->prepare($query);

        //Hash key for security as it would be used as a password for the app
        $key = password_hash($key,PASSWORD_DEFAULT);

        //to check if query was sucesfully run
        if(!$statement->execute(array($key,$userID))){
            $statement = null;
            return array('status'=>'errorAddingKey', 'key'=>"0");
        }
        $statement = null;
    
    }

    /**
     * confirmKey(): selects the users from the database by given email and if psw hash is true then logs the user
     *
     * @param string $key is the customer key to verify the login of the user in the app 
     * @return int 0==not verified, 1=verified
     */ 
    public function confirmKey($key,$userID){
        $query = 'SELECT * FROM customers WHERE user_id = ? AND active = ?;';
        $statement = $this->connect()->prepare($query);


        //to check if query was sucesfully run
        if(!$statement->execute(array($userID,1))){
            $statement = null;
            array('status'=>0, "message"=>"Error connecting to the database");;
        }
        
        //Check if there was a match, if not, it means there is not such key 
        if($statement->rowCount()==0){
            $statement = null;
            return array('status'=>0, "message"=>"The key did not match");
        }

        $user = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the user data based on id
        $checkKey = password_verify($key,$user[0]["activation_code"]); //check hashed key

        $statement = null;

        //Get the user variables
        $userEmail = $user[0]["user_email"];
        $userFN = $user[0]["user_fname"];
        $userLN = $user[0]["user_lname"];  
        $sucessMatch =  array('status'=>1, "userID"=>$userID,"userEmail"=>$userEmail,"userFN"=>$userFN,"userLN"=>$userLN);
        if($checkKey) return $sucessMatch;
        return array('status'=>0, "message"=>"The key did not match");
    }

    
}