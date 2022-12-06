<?php 
/**
 * TransactionView is the view class for the Transaction class. Handles displaying/outputting information
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class TransactionView extends Transaction{
    private $transactions;

    /**
     * requestTransaction(): sends info to request to the database a transaction by its id 
     * 
     * @param int $id the id of the transaction to be requested
     * @return void
     */    
    public function requestTransaction($id, $userID){
        $this->transactions = $this->getTransaction($id, $userID); 
    }

    /**
     * requestAllUserTransactions(): request all the transactions for the current user 
     * 
     * @param int $id the id of the transaction to be requested
     * @return void
     */        
    public function requestAllUserTransactions($userID){
        $this->transactions = $this->getAllUTransaction($userID); 
    }
    

    /**
     * requestPlayTransactions(): request to the database all the transactions for the given play id
     * 
     * @param int $playID the id of the play 
     * @return void
     */       
    public function requestPlayTransactions($playID){
        $this->transactions = $this->getPlayTransactions($playID); 
    }
    
    /**
     * getInfo(): returns the object with the transactions info 
     * 
     * @return object $transactions, it contains transaction information
     */      
    public function getInfo(){
        return $this->transactions; 

    }

    /**
     * generateReport(): generates report for current transactions
     * 
     * @return void
     */      
    public function generateReport(){
        $transactions = $this->transactions; 
        //Creaste DOM ELEMENT
        $dom = new DOMDocument('1.0', 'utf-8');
        
        //<table class="table">
        $table = $dom->createElement('table', ''); 
        $table->setAttribute("class","table text-center reportTable");
        $table->setAttribute("id","tableReport");
        $dom->appendChild($table);
        
        //  <thead>
        $thead = $dom->createElement('thread', ''); 
        $table->appendChild($thead);

        // <tr>
        $tr = $dom->createElement('tr', ''); 
        $thead->appendChild($tr);

        // <th> Transaction #
        $th = $dom->createElement('th', 'Transaction #'); 
        $th->setAttribute("scope","col");
        $tr->appendChild($th);
    
        // <th> Seat #
        $th = $dom->createElement('th', 'Seat #'); 
        $tr->appendChild($th);
    
        // <th> Cost
        $th = $dom->createElement('th', 'Cost'); 
        $tr->appendChild($th);   

        // <th> Transaction Date
        $th = $dom->createElement('th', 'Transaction Date'); 
        $tr->appendChild($th);    

        // <th> Customer
        $th = $dom->createElement('th', 'Customer'); 
        $tr->appendChild($th);   

        // <th> Customer Email
        $th = $dom->createElement('th', 'Email'); 
        $tr->appendChild($th);    

        // <tbody>
        $tbody = $dom->createElement('tbody', ''); 
        $table->appendChild($tbody);    
        
        //initialize total and seat count
        $total = 0; 
        $i = 0; 
        //generate rows for report 
        foreach ($transactions as $transaction){
            // <tr> Row
            $tr = $dom->createElement('tr', ''); 
            $tbody->appendChild($tr);

            // <th> Transaction #
            $th = $dom->createElement("th", "{$transaction['transaction_id']}"); 
            $th->setAttribute("scope","row");
            $tr->appendChild($th);
        
            // <td> Seat #
            $seatN = $this->seatRowCol($transaction['seat_number']);
            $td = $dom->createElement("td", "{$seatN}"); 
            $tr->appendChild($td);

            // <td> Cost 
            $cost =  number_format($transaction['cost'],1);
            $td = $dom->createElement("td", "\${$cost}"); 
            $tr->appendChild($td);
        
            // <td> Transaction Date
            $date = date('m/d/Y',strtotime($transaction["transaction_date"]));
            $td = $dom->createElement("td", "{$date}"); 
            $tr->appendChild($td);

            // <td> Customer
            $fullname = "{$transaction["user_fname"]} {$transaction["user_lname"]}";
            $td = $dom->createElement("td", "{$fullname}"); 
            $tr->appendChild($td);
        
            // <td> Customer
            $email = "{$transaction["user_email"]}";
            $td = $dom->createElement("td", "{$email}"); 
            $tr->appendChild($td);

            //add to total 
            $total = $total + $transaction["cost"];
            $i++; //count seat
        }
        //total seats sold 
        $seats = $dom->createElement("h3", "Seats sold: {$i}"); 
        $seats->setAttribute("class","fw-bold py-2");
        $seats->setAttribute("id","report");
        $seats->setAttribute("style","color:#cc0000");
        $dom->appendChild($seats);
        //total revenue report 
        $total = number_format($total,2);
        $total = $dom->createElement("h3", "Current Revenue: \${$total}"); 
        $total->setAttribute("class","fw-bold py-2");
        $total->setAttribute("style","color:#cc0000");
        $dom->appendChild($total);

        //print html 
        $dom = $dom->saveHTML();
        echo $dom;
    }

    /**
     * printTickets(): method that prints to the page the tickets for the current transactions 
     * 
     * @return void
     */       
    public function printTickets(){
        //modal=0 homepage, modal=1, graphicSeatPlan, modal=2, modify play
        $transactions = $this->transactions;
        if($transactions==null){
            echo "Error, you do not have access to this information.";
        }
        else{
            //Loop through all the upcoming plays 
            foreach ($transactions as $ticket){
                $this->generateTicket($ticket);
                $ticketNumber = $ticket['ticket_id'];
                $qrID = "qrcode{$ticket['ticket_id']}";
                echo "<script>new QRCode(document.getElementById('{$qrID}'), '{$ticketNumber}');</script>";
            }
            //echo "<script>playCard()</script>";
        }
    }


    /**
     * generateTicket(): generates the HTML elements for the given ticket 
     * 
     * @param object $ticket, contains the ticket information 
     * @return void
     */    
   public function generateTicket($ticket){
        //Creaste DOM ELEMENT
        $dom = new DOMDocument('1.0', 'utf-8');
        //Set Date and Time  Format
        $date = date('m/d/Y',strtotime($ticket["stime"]));
        $sTime = date('h:i a',strtotime($ticket["stime"]));
        $eTime = date('h:i a',strtotime($ticket["etime"]));

        //<div class="ticket">
        $ticketD = $dom->createElement('div', ''); 
        $ticketD->setAttribute("class","py-2");
        $ticketD->setAttribute("id","{$ticket['ticket_id']}");
        $dom->appendChild($ticketD);
        
        //<div class="card">
        $card = $dom->createElement('div', ''); 
        $card->setAttribute("class","card");
        $ticketD->appendChild($card);

        //Portales
        $theatre = $dom->createElement("h5", "Los Portales Theatre Ticket#{$ticket['ticket_id']}"); 
        $theatre->setAttribute("class","card-header league display-4 text-center");
        $card->appendChild($theatre);

        // <div class="card-body">
        $cardBody = $dom->createElement('div', ''); 
        $cardBody->setAttribute("class","card-body");
        $card->appendChild($cardBody);
        
        // <div class="row">
        $row = $dom->createElement('div', ''); 
        $row->setAttribute("class","row");
        $cardBody->appendChild($row);
  
        // <div class="col-8">
        $col10 = $dom->createElement("div", ""); 
        $col10->setAttribute("class","col-9");
        $row->appendChild($col10);

        // <h5 class="card-header league display-3 text-center">Play Name</h5>
        $playTitle = $dom->createElement("h5", "{$ticket['play_title']}"); 
        $playTitle->setAttribute("class","league playTitle text-center");
        $col10->appendChild($playTitle);

        //hr
        $hr = $dom->createElement("hr", ""); 
        $col10->appendChild($hr);
        // Seat Number
        $seatN = $this->seatRowCol($ticket["seat_number"]);
        $data = $dom->createElement("h5", "Transaction ID: {$ticket['transaction_id']}, Seat Number: {$seatN}"); 
        $data->setAttribute("class","league playTitle text-center");
        $col10->appendChild($data);

        //hr
        $hr = $dom->createElement("hr", ""); 
        $col10->appendChild($hr);
        // Seat Number
        $seatN = $this->seatRowCol($ticket["seat_number"]);
        $data = $dom->createElement("h5", "Date: {$date}, Starts at: {$sTime}"); 
        $data->setAttribute("class","league playTitle text-center");
        $col10->appendChild($data);

        //hr
        $hr = $dom->createElement("hr", ""); 
        $col10->appendChild($hr);
        // <div class="col-4">
        $col2 = $dom->createElement("div", ""); 
        $col2->setAttribute("class","col-3");
        $row->appendChild($col2);

        // <div id="qrcode"></div>
        $qr = $dom->createElement("div", ""); 
        $qr->setAttribute("id","qrcode{$ticket['ticket_id']}");
        $col2->appendChild($qr);


        $dom = $dom->saveHTML();
        echo $dom;
    }

}