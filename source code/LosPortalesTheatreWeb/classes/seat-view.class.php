<?php 
/**
 * SeatView is the view class for the Seat class. Handles displaying/outputting information
 * @author Pedro Damian Marta Rubio https://github.com/damiansh
 * @copyright Copyright (c) 2022, Pedro Damian Marta Rubio 
 * @version 1.0
 */
class SeatView extends Seat{
    private $seats; 
 
    /**
     * Initializes this class with the given options.
     *
     * @param int $play_id is the id for the play that is requesting the seats 
     */     
    public function __construct($play_id){
       $this->seats = $this->getSeats($play_id);
    }

    /**
     * setCart(): request and returns the object with the seats in the customer's cart 
     *
     * @return object $seats: object with the plays info gets returned 
     */
    public function setCart($userID){
        $this->seats = $this->getCartContent($userID);
        return $this->seats; 
     }

     
    /**
     * getCart(): returns the seats
     *
     * @return object $seats: object with the plays info gets returned 
     */
    public function getSeatsData(){
        return $this->seats; 
     }
     
     
    /**
     * getCount(): gets the count of items in the customer's cart 
     *
     * @return int $count: number of the items in the customer's cart 
     */     
    public function getCount(){
        if(!isset($this->seats)) return 0; 
        $count = 0; 
        $i =0;
        foreach ($this->seats as $seat){
            if($this->removeReservation($i)==1){
                $count++;
            }
            else{
                $this->seats[$i]["reserved"] = null; 
                $this->seats[$i]["status"] = "0"; 

            }
            $i++;
        }
        return $count;
    }

    /**
     * showSeats(): generates in the page the graphic seat plan 
     *
     * @param int modal = 0 then customer area, modal 1 then managementa area. 
     * @return object $seats: objects with the seats information
     */      
    public function showSeats($modal){
       //Creaste DOM ELEMENT
       $dom = new DOMDocument('1.0', 'utf-8');

       // <div class="btn-toolbar justify-content-center"> COLOR TOOLBAR
       $colorBar = $dom->createElement('div', ''); 
       $colorBar->setAttribute("class","btn-toolbar justify-content-center py-1");
       $colorBar->setAttribute("id","stageColors");
       $dom->appendChild($colorBar);   

       // AVAILABLE DIV
       $buttonDiv = $dom->createElement('div', ''); 
       $buttonDiv->setAttribute("class","px-1");
       $colorBar->appendChild($buttonDiv);    
       // Available Seats Color
       $color = $dom->createElement('button', 'Available'); 
       $color->setAttribute("class","btn btn-success");
       $buttonDiv->appendChild($color);    

       // Reserved Div
       $buttonDiv = $dom->createElement('div', ''); 
       $buttonDiv->setAttribute("class","px-1");
       $colorBar->appendChild($buttonDiv);    
       // Reserved Seats Color
       $color = $dom->createElement('button', 'Reserved (10min)'); 
       $color->setAttribute("class","btn btn-dark");
       $buttonDiv->appendChild($color);   

       // Sold Div
       $buttonDiv = $dom->createElement('div', ''); 
       $buttonDiv->setAttribute("class","px-2");
       $colorBar->appendChild($buttonDiv);    
       // Sold Seats COlor
       $color = $dom->createElement('button', 'Sold'); 
       $color->setAttribute("class","btn btn-danger");
       $color->setAttribute("style","background-color:#cc0000");
       $buttonDiv->appendChild($color);   

       // Stage Div
       $stage = $dom->createElement('div', ''); 
       $stage->setAttribute("class","d-grid py-1");
       $stage->setAttribute("id","stage");
       $dom->appendChild($stage);   

       // Stage Button
       $stageBTN = $dom->createElement('button', 'Stage'); 
       $stageBTN->setAttribute("class","btn btn-primary");
       $stage->appendChild($stageBTN);  
       
       
          
       $l = "A";
       //loop for rows
       for ($i = 1; $i <= 8; $i++) {
            //"<div class='btn-toolbar justify-content-center'>";
            $seatRow= $dom->createElement('div', ''); 
            $seatRow->setAttribute("class","btn-toolbar justify-content-center");
            $dom->appendChild($seatRow);
            $idArray = new ArrayObject();
            for ($j = 1; $j <= 12; $j++) {
                //Calculate the seat number
                $seatNumber = $j + (($i-1)*12);

                //Cost for current seat
                $cost = number_format($this->seats[$seatNumber-1]["cost"],2);

                //get status
                $status = $this->seats[$seatNumber-1]["status"];

                //<input  type='checkbox' class='btn-check' id='{$l}{$j}' 
                //autocomplete='off' data-seat='$counter' onchange='seatToggle(this)'>";
                $seatChkBox = $dom->createElement('input', ''); 
                $seatChkBox->setAttribute("type","checkbox");
                $seatChkBox->setAttribute("class","btn-check");
                $seatChkBox->setAttribute("id","{$l}{$j}");
                $seatChkBox->setAttribute("autocomplete","off");
                $seatChkBox->setAttribute("data-seat","{$seatNumber}");
                $seatChkBox->setAttribute("onchange","seatToggle(this)");

                //Append to the row
                $seatRow->appendChild($seatChkBox);

                //<label id='{$counter}' class='btn btn-outline-dark seat' for='{$l}{$j}'
                //data-bs-toggle='popover' data-bs-trigger='hover focus' data-bs-placement='top' data-bs-content='\${$cost}'>
                $seatLabel = $dom->createElement('label', ''); 
                $seatLabel->setAttribute("data-bs-toggle","popover");
                $seatLabel->setAttribute("data-bs-trigger","hover focus");
                $seatLabel->setAttribute("data-bs-placement","top");
                $seatLabel->setAttribute("id","{$seatNumber}");
                if($status==1){
                    $status = $this->removeReservation($seatNumber-1);
                }
                if($status==1){
                    $seatLabel->setAttribute("class","btn btn-dark seat countdown");
                    $seatLabel->setAttribute("data-bs-content","Reserved seat");
                }
                elseif($status==2){
                    $seatLabel->setAttribute("class","btn btn-danger seat");
                    $seatLabel->setAttribute("style","background-color:#cc0000");
                    $seatLabel->setAttribute("data-bs-content","This seat has been sold.");
                }
                else{
                    $seatLabel->setAttribute("class","btn btn-success seat");
                    $seatLabel->setAttribute("for","{$l}{$j}");
                    $seatLabel->setAttribute("data-bs-content","\${$cost}");
                    $idArray[$j] = "{$l}{$j}";


                }
     

                //<span class='seatN'>{$l}{$j}</span></label>
                $seatSpan = $dom->createElement("span", "{$l}{$j}"); 
                $seatSpan->setAttribute("class","font-monospace seatN");
                $seatSpan->setAttribute("id","s{$seatNumber}");
                $seatLabel->appendChild($seatSpan);      

                //Append to the row
                $seatRow->appendChild($seatChkBox);      
                $seatRow->appendChild($seatLabel);                
                

            }
            $idJson = json_encode($idArray);
            //<button type='button' id='{$l}' class='btn btn-secondary btn-sm selector' 
            //onclick='selectRow({$idJson})'>Toggle {$l}</button>";
            //Modal = 0 then customer area, modal 1 then managementa area. 
            if($modal==1){
                $rowButton = $dom->createElement("button", "{$l}"); 
                $rowButton->setAttribute("type","button");
                $rowButton->setAttribute("id","{$l}");
                $rowButton->setAttribute("class","btn btn-secondary btn-sm selector font-monospace");
                $rowButton->setAttribute("onclick","selectRow('{$l}',{$idJson})");
                //Append to the row
                $seatRow->appendChild($rowButton);     
            } 
            $l++; //next letter in the abcd
       }

       //print graphic seat plan
       $dom = $dom->saveHTML();
       echo $dom;
       echo "<script>graphicPlan()</script>";
       return $this->seats; 
    }

    /**
     * showCart(): shows in the page the tickets in the cart
     *
     * @return void
     */    
    public function showCart(){
        //Creaste DOM ELEMENT
        $dom = new DOMDocument('1.0', 'utf-8');       
        
        //<div class="accordion" id="accordionPanelsStayOpenExample">
        $accordion = $dom->createElement('div', ''); 
        $accordion->setAttribute("class","accordion");
        $accordion->setAttribute("id","accordionPanelsStayOpenExample");
        $dom->appendChild($accordion);
        $i =0;
        $total = 0;
        foreach ($this->seats as $seat){
            if($this->removeReservation($i)==1){
                $accordion->appendChild($this->cartHTML($seat,$dom));
                $total = $total + $seat["cost"];
            }
            $i++;
        }   
        //tax calc
        $portalesTax = 0.08188;
        $tax = $portalesTax*$total;
        $totalOrder = number_format($total + $tax,2);
        $tax = number_format($tax,2);

        //order summary
        $orderHeader = $dom->createElement("h2", "Order Summary:"); 
        $orderHeader->setAttribute("class","fw-bold py-2");
        $dom->appendChild($orderHeader);
        //hr
        $hr = $dom->createElement("hr", ""); 
        $dom->appendChild($hr);
        //total before tax
        $totalBeforeTax = number_format($total,2);
        $text = $dom->createElement("h4", "Total before tax:"); 
        $dom->appendChild($text);
        $value = $dom->createElement("span", " \${$totalBeforeTax}"); 
        $value->setAttribute("class","fw-bold py-2");
        $text->appendChild($value);
        //tax to be collected
        $text = $dom->createElement("h4", "Estimated tax to be collected:"); 
        $dom->appendChild($text);
        $value = $dom->createElement("span", " \${$tax}"); 
        $value->setAttribute("class","fw-bold py-2");
        $text->appendChild($value);
        //hr
        $hr = $dom->createElement("hr", ""); 
        $dom->appendChild($hr);
        //total + tax
        $totalC = $dom->createElement("h3", "Order Total: \${$totalOrder}"); 
        $totalC->setAttribute("class","fw-bold py-2");
        $totalC->setAttribute("style","color:#cc0000");
        $dom->appendChild($totalC);

        //input total 
        $input = $dom->createElement("input", ""); 
        $input->setAttribute("type","hidden");
        $input->setAttribute("name","orderTotal");
        $input->setAttribute("value","{$totalOrder}");
        $dom->appendChild($input);
        $dom = $dom->saveHTML();
        echo $dom;
    }

    /**
     * cartHTML(): generates the html for the tickets 
     *
     * @return HTML $accordionItem contains the accordeon for the ticket 
     */     
    private function cartHTML($seat,$dom){
        //Cart variables 
        $seatTag = $this->seatRowCol($seat["seat_number"]);
        $cost = number_format($seat["cost"],2);

        $ticketN = $seat["ticket_id"];
        $playTitle = "{$seat['play_title']}";
        $reserved = $this->getRemainder($seat["reserved"]);
        $pURL = $seat['pURL'];
        $playID = $seat['play_id'];
        //image url 
        $link ="images/plays/";

        //check if file exists, if not, use placeholder image
        $exists = file_exists($link . $playID . ".png");
        if(!$exists){
            $link = $link . "placeholder.png";
        }
        else{
            $link = $link . $pURL;
        }

        //Set Date and Time  Format
        $date = date('m/d/Y',strtotime($seat["stime"]));
        $sTime = date('h:i a',strtotime($seat["stime"]));
        $eTime = date('h:i a',strtotime($seat["etime"]));

        // <div class="accordion-item">
        $accordionItem = $dom->createElement('div', ''); 
        $accordionItem->setAttribute("class","accordion-item");
        // <h2 class="accordion-header" id="panelsStayOpen-heading{$ticketN}">
        $header = $dom->createElement('h2', ''); 
        $header->setAttribute("class","accordion-header");
        $header->setAttribute("id","panelsStayOpen-heading{$ticketN}");
        $accordionItem->appendChild($header);
        // <button class="accordion-button" 
        ///type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse"
        // aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
        $button = $dom->createElement("button", ""); 
        $button->setAttribute("class","accordion-button collapsed accTxt");
        $button->setAttribute("type","button");
        $button->setAttribute("data-bs-toggle","collapse");
        $button->setAttribute("data-bs-target","#panelsStayOpen-collapse{$ticketN}");
        $button->setAttribute("aria-expanded","false");
        $button->setAttribute("aria-controls","panelsStayOpen-collapse{$ticketN}");
        $header->appendChild($button);
               
        //Play seat Tag 
        $tag = $dom->createElement("span", "{$seatTag}:"); 
        $tag->setAttribute("class","fw-bold px-1");
        $button->appendChild($tag);

        //Seat reserved remainder
        $tag = $dom->createElement("span", "{$reserved}"); 
        $tag->setAttribute("class","fw-bold px-1");
        $tag->setAttribute("style","color:#cc0000");
        $button->appendChild($tag);
    
        //Play title 
        $title = $dom->createElement("span", "{$playTitle}"); 
        $title->setAttribute("class","fw-bold px-2");
        $button->appendChild($title);
        //Dates 
        $dates = $dom->createElement("span", "{$date}"); 
        $dates->setAttribute("class","px-2");
        $button->appendChild($dates);

        //Play cost
        $tag = $dom->createElement("span", "\${$cost}"); 
        $button->appendChild($tag);
        //delete form 
        $form = $dom->createElement("form", ""); 
        $form->setAttribute("action", "includes/included-checkout.php"); 
        $form->setAttribute("method", "post"); 
        $button->appendChild($form);
        //delete input 
        $dInput = $dom->createElement("input", ""); 
        $dInput->setAttribute("type", "hidden"); 
        $dInput->setAttribute("name", "ticketN"); 
        $dInput->setAttribute("value", "{$ticketN}"); 
        $form->appendChild($dInput);
        //delete button 
        $deleteBtn = $dom->createElement("button", "X"); 
        $deleteBtn->setAttribute("type", "submit"); 
        $deleteBtn->setAttribute("class", "btn btn-danger"); 
        $deleteBtn->setAttribute("name", "deleteCart"); 
        $form->appendChild($deleteBtn);

        //<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
        $collapse = $dom->createElement('div', ''); 
        $collapse->setAttribute("id","panelsStayOpen-collapse{$ticketN}");
        $collapse->setAttribute("class","accordion-collapse collapse");
        $collapse->setAttribute("aria-labelledby","panelsStayOpen-heading{$ticketN}");
        $accordionItem->appendChild($collapse);
        //<div class="accordion-body">
        $body = $dom->createElement('div', ''); 
        $body->setAttribute("class","accordion-body");
        $collapse->appendChild($body);

        //img
        $img = $dom->createElement('img', ''); 
        $img->setAttribute("class","col-sm-12 col-lg-5 img-thumbnail");
        $img->setAttribute("id","cardPlayImage");
        $img->setAttribute("src",$link);
        $img->setAttribute("alt",$playTitle);
        $body->appendChild($img); //is inside thumbnail
        
        //Start Time <p>
        $p = $dom->createElement("p", ""); 
        $body->appendChild($p);
        //Start Time label
        $label = $dom->createElement("span", "Start Time: "); 
        $label->setAttribute("class","fw-bold");
        $p->appendChild($label);
        //Start Time value 
        $content = $dom->createElement("span", "{$sTime}"); 
        $p->appendChild($content);

        //End Time <p>
        $p = $dom->createElement("p", ""); 
        $body->appendChild($p);
        //End Time label
        $label = $dom->createElement("span", "End Time: "); 
        $label->setAttribute("class","fw-bold");
        $p->appendChild($label);
        //End Time value 
        $content = $dom->createElement("span", "{$eTime}"); 
        $p->appendChild($content);

        //Start Time <p>
        $p = $dom->createElement("p", ""); 
        $body->appendChild($p);
        //Start Time label
        $label = $dom->createElement("span", "Synopsis: "); 
        $label->setAttribute("class","fw-bold");
        $p->appendChild($label);
        //Start Time value 
        $content = $dom->createElement("span", "{$seat['long_desc']}"); 
        $p->appendChild($content);

  

        return $accordionItem;
    }    

    /**
     * getRemainder(): generates the html for the tickets 
     *
     * @param string $reservedTime 
     * @return string $remainder, time limit to checkout the ticket 
     */        
    private function getRemainder($reservedTime){
        $reservedTime = new DateTime($reservedTime);
        $reservedTime->modify('+11 minutes');
        $currentDate = new DateTime('now');
        $remainder =  $currentDate->diff($reservedTime);
        return $remainder->format('%imin');
    }

    /**
     * removeReservation(): checks if reservation has expired
     *
     * @param int $i the index of the seat array 
     * @return int number 0 means that it has expired, 1 means is still reserved
     */     
    private function removeReservation($i){
        date_default_timezone_set('America/Boise'); // change to MOUNTAIN TIME
        $currentSeat = $this->seats[$i];
        $reservedTime = $currentSeat["reserved"];
        $reservedTime = new DateTime($reservedTime);
        $reservedTime->modify('+10 minutes');
        
        $currentDate = new DateTime('now');
        
        if($currentDate>$reservedTime){
            $this->updateReservation($currentSeat["ticket_id"],0,null,0,null);
            return 0;
        }

        return 1; 
    }

}