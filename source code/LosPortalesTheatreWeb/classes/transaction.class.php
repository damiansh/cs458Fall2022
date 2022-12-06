<?php 
/**
 * Seat class handles the queries executions to the database with data from the view
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class Transaction extends PortalesDB{

    /**
     * getTransaction(): selects from the database a transaction by user and transaction id 
     *
     * @param $id transaction id of the current transaction
     * @param $userID object containing the seats to be updated 
     * @return object|null $transactions object with the transaction information 
     */        
    protected function getTransaction($id, $userID){
        $query = 'SELECT * FROM purchases WHERE transaction_id = ? and user_id = ?;';
        $statement = $this->connect()->prepare($query);
        
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($id, $userID))){
            $statement = null;
            header("location: index.php?error=errorTransaction");
            exit();
        }  

        //Check if rows
        if($statement->rowCount()==0){
            $statement = null;
            return null;         
        }

        $transactions = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $statement = null;
        return $transactions; 

    }

    /**
     * getPlayTransactions(): selects from the database  transactions by play id 
     *
     * @param $id play id
     * @return object|null $transactions object with the transaction information 
     */      
    protected function getPlayTransactions($id){
        $query = 'SELECT * FROM purchases WHERE play_id = ? ORDER BY transaction_id ASC;';
        $statement = $this->connect()->prepare($query);
        
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($id))){
            $statement = null;
            header("location: index.php?error=errorPlayTransaction");
            exit();
        }  

        //Check if rows
        if($statement->rowCount()==0){
            $statement = null;
            return null;         
        }

        $transactions = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $statement = null;
        return $transactions; 

    }

    /**
     * getAllUTransaction(): selects from the database all the transactions by user id 
     *
     * @param $userID object containing the seats to be updated 
     * @return object|null $transactions object with the transaction information 
     */  
    protected function getAllUTransaction($userID){
        $query = 'SELECT * FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC;';
        $statement = $this->connect()->prepare($query);
        
        //this checks if the query is executed sucesfully 
        if(!$statement->execute(array($userID))){
            $statement = null;
            header("location: index.php?error=errorOrders");
            exit();
        }  

        //Check if rows
        if($statement->rowCount()==0){
            $statement = null;
            return null;         
        }

        $transactions = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch all the play data
        $statement = null;
        return $transactions; 

    }


}
