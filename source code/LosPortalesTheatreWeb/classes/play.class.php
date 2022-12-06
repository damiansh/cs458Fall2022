<?php 
/**
 * Play class handles the queries executions to the database with data from the controller and view
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class Play extends PortalesDB{

    /**
     * insertPlay(): inserts given information to the play table in the database
     *
     * @param string $playTitle title of the given play 
     * @param string $shortDesc short synopsis of the given play 
     * @param string $longDesc longer synopsis of the given play 
     * @param string $sDate start date time of the given play 
     * @param string $eDate end date time of the given play. Date must be the same, time can differ
     * @return void
     */     
    protected function insertPlay($playTitle, $shortDesc, $longDesc, $sDate, $eDate){
        $query = 'INSERT INTO plays (play_title, long_desc, short_desc, stime, etime) VALUES (?,?,?,?,?);';
        $statement = $this->connect()->prepare($query);


        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($playTitle, $longDesc, $shortDesc, $sDate, $eDate))){
            $statement = null;
            header("location: ../addPlay.php?error=errorAddPlay");
            exit();
        }
        $statement = null;
    }


    /**
     * addSeats(): creates the seats (96) for the play with the given playID
     *
     * @param int $playID id to the play to which the seat belongs to 
     * @param float $defaultCost default cost for the seats in the given play      
     * @return void
     */         
    protected function addSeats($playID, $defaultCost){
        $query = 'INSERT INTO seats (play_id, seat_number, cost) VALUES (?,?,?);';
        $statement = $this->connect()->prepare($query);
        for($i=1;$i<=96;$i++){
            //this checks if the query is executed sucesfully 
            if(!$statement->execute(array($playID, $i, $defaultCost))){
                $statement = null;
                header("location: ../addPlay.php?error=errorAddSeat");
                exit();
            }
        }
        $statement = null;
    }

    /**
     * updatePlay(): updates a play information by a given id 
     * 
     * @param int $playID id of the play that is going to be updated
     * @param string $playTitle title of the given play 
     * @param string $shortDesc short synopsis of the given play 
     * @param string $longDesc longer synopsis of the given play 
     * @param string $sDate start date time of the given play 
     * @param string $eDate end date time of the given play. Date must be the same, time can differ
     * @return void
     */     
    protected function updatePlay($playID, $playTitle, $shortDesc, $longDesc, $sDate, $eDate){
        $query = 'UPDATE plays SET play_title=?, long_desc=?, short_desc=?, stime=?, etime=? WHERE play_id = ?;';
        $statement = $this->connect()->prepare($query);
        $uniqueURL = "{$playID}.png?" . uniqid();
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($playTitle, $longDesc, $shortDesc, $sDate, $eDate,$playID))){
            $statement = null;
            $_SESSION["message"] = "ERROR UPDATING PLAY #{$playID}.";
            header("location: ../index.php?error=updatePlay&playID=" . urlencode($playID));
            exit();
        }
        $statement = null;
    }    
    


    /**
     * updatePlay(): updates a image to the server 
     * 
     * @param string $path folder path where the image is going to be uploaded
     * @param string $base64 base64 code of the image to be uploaded 
     * @param int $playID the id that ties the image to the play being uploaded 
     * @param string $page destination for the errors 
     * @return void
     */      
    protected function uploadImage($path,$base64,$playID,$page){
        $query = 'UPDATE plays SET pURL = ? WHERE play_id = ?;';
        $statement = $this->connect()->prepare($query);
        $uniqueURL = "{$playID}.png?" . uniqid();

        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($uniqueURL,$playID))){
            $statement = null;
            header("location: ../{$page}.php?error=errorAddImgURL");
            exit();
        }
        $imageName = $playID;
        $file = $path . $imageName . '.png';
        file_put_contents($file, "$base64");
        $statement = null;
        return $imageName; //return play ID 

    }

    /**
     * getPlayID(): executes a select query to a view called latestplay to get the latest play id 
     * 
     * @return int $playID, returns the play ID of the latest play 
     */       
    protected function getPlayID(){
        $query = 'SELECT * FROM latestplay;';
        $statement = $this->connect()->prepare($query);
        
        
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array())){
            $statement = null;
            $_SESSION["message"] = "Error Querying.";
            header("location: ../index.php?error=errorgetPlay");
            exit();
        }  


        //Check if rows
        if($statement->rowCount()==0){
            $statement = null;
            $_SESSION["message"] = "NULL PLAYS";
            header("location: ../index.php?error=NullPlays");
            exit();          
        }

        $play = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $playID = $play[0]["play_id"];
        $statement = null;
        return $playID; //return play ID 

    }
    

    /**
     * getUpcomingPlays(): executes a select query for the plays to get all the upcoming and published plays 
     * 
     * @return object|null $plays, play object data with the play information 
     */       
     protected function getUpcomingPlays(){
        date_default_timezone_set('America/Boise'); // change to MOUNTAIN TIME
        $currentDate = date('Y-m-d H:i:s'); //get currentDateTime
        $query = 'SELECT * FROM plays WHERE stime>=? AND published = ? ORDER BY stime ASC;';
        $statement = $this->connect()->prepare($query);
        
     
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($currentDate,1))){
            $statement = null;
            header("location: ../error=loadingPlays");
            exit();
        }  


        //If there is not plays that meet the criteria
        if($statement->rowCount()==0){
            $statement = null;
           return null;
        }

        $plays = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $statement = null;
        return $plays; //return the plays 

    }

    /**
     * getAllPlays(): executes a select query for ALL the plays in the system 
     * 
     * @return object|null $plays, play object data with all the play information 
     */        
     protected function getAllPlays(){
        $query = 'SELECT * FROM plays ORDER BY stime DESC;';
        $statement = $this->connect()->prepare($query);

     
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array())){
            $statement = null;
            header("location: ../index.php?error=loadingAllPlays");
            exit();
        }  


        //If there is not plays that meet the criteria
        if($statement->rowCount()==0){
            $statement = null;
           return null;
        }

        $plays = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $statement = null;
        return $plays; //return the plays 

    }

    /**
     * getPlay(): executes a select query for an individual play by modal
     * @param int $playID id of the play to be selected
     * @param int $modal MODAL = 0 no criteria, MODAL = 1 then only published and upcoming plays
     * @return object|null $plays, play object data with all the play information 
     */     
     protected function getPlay($playID,$modal){
        $query = 'SELECT * FROM plays WHERE play_id=?;';
        //change exeuction if modal 1
        if($modal==1){
            date_default_timezone_set('America/Boise'); // change to MOUNTAIN TIME
            $currentDate = date('Y-m-d H:i:s'); //get currentDateTime
            $query = 'SELECT * FROM plays WHERE play_id=? AND stime>=? and published = ?;';
            $statement = $this->connect()->prepare($query);

            //this checks if the query is executed sucesfully 
            if(!$statement->execute(array($playID,$currentDate,1))){
                $statement = null;
                header("location: ../index.php?error=loadingSinglePlay");
                exit();
            }  
        }
        else{
            $statement = $this->connect()->prepare($query);
            //this checks if the query is executed sucesfully 
            if(!$statement->execute(array($playID))){
                $statement = null;
                header("location: ../index.php?error=loadingSinglePlay");
                exit();
            }  
        }

        //If there is not plays that meet the criteria
        if($statement->rowCount()==0){
            $statement = null;
           return null;
        }

        $play = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $statement = null;
        return $play; //return the plays 

    }

    /**
     * publishPlay(): updates the published status of a given play ID to the received status 
     * @param int $playID id of the play to be selected
     * @param int $published 0==unpublished, 1==published 
     * @return void
     */       
    public function publishPlay($playID,$published){
        $query = 'UPDATE plays SET published = ? WHERE play_id = ?;'; 
        $statement = $this->connect()->prepare($query);

        //to check if query was sucesfully run
        if(!$statement->execute(array($published,$playID))){
            $statement = null;
            $_SESSION["message"] = "ERROR PUBLISHING PLAY.";
            header("location: ../index.php?error=publishPlay&playID=" . urlencode($playID));
            exit();
        }
        
        $statement = null;

    }  

    /**
     * deletePlay(): deletes a play by the given id and its seats 
     * @param int $playID id of the play to be selected
     * @return void
     */      
     public function deletePlay($playID){
        //Seats contain foreign key for play, thus they must be deleted first
        $query = 'DELETE FROM seats WHERE play_id = ?; DELETE FROM plays WHERE play_id = ?;'; 
        $statement = $this->connect()->prepare($query);

        //to check if query was sucesfully run
        if(!$statement->execute(array($playID,$playID))){
            $statement = null;
            $_SESSION["message"] = "ERROR DELETING PLAY #{$playID}.";
            header("location: ../index.php?error=deletePlay&playID=" . urlencode($playID));
            exit();
        }
        
        $statement = null;

    }       

}
