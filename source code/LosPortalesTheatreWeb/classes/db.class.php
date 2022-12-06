<?php 

/**

 * PortalesDB class handles the connection to the database and is the parent of all classes connecting to the Database

 * @author Pedro Damian Marta Rubio https://github.com/damiansh

 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 

 * @version 1.0

 */

class PortalesDB{



    /**

     * connect(): connects to the database

     * 

     * @return void

     */ 

    protected function connect(){

       try {


           $username = "portales";

           $password = "c)WRs[229j2tYD";

           $db = new PDO('mysql:host=localhost;dbname=portales_losport', $username, $password);
         

           return $db;



       } 

       catch (PDOException $e) {

           print "Error: " . $e->getMessage() . "<br/>";

           die();

       }

    }



    /**

     * seatRowCol(): converts the seatNumber to the format Row Letter Col Number {A1,B2,ETC.}

     *

     * @param int $number the seat number 

     * @param string the row letter and col number 

     */     

    protected function seatRowCol($number){

        if($number<=12){

            $seatN = $number-(12*0);

            return "A{$seatN}";

        }

        elseif($number<=24){

            $seatN = $number-(12*1);

            return "B{$seatN}";

        }

        elseif($number<=36){

            $seatN = $number-(12*2);

            return "C{$seatN}";

        }

        elseif($number<=48){

            $seatN = $number-(12*3);

            return "D{$seatN}";

        }

        elseif($number<=60){

            $seatN = $number-(12*4);

            return "E{$seatN}";

        }

        elseif($number<=72){

            $seatN = $number-(12*5);

            return "F{$seatN}";

        }

        elseif($number<=84){

            $seatN = $number-(12*6);

            return "G{$seatN}";

        }

        elseif($number<=96){

            $seatN = $number-(12*7);

            return "H{$seatN}";

        }



    }

}



