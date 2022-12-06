
function playCard(){
    cardText = document.getElementsByClassName("card-text");
    if(cardText.length==0) return; 
    if(cardText.length==1) return; 
    maxHeight = maxCard();
    for(let i = 0;i<cardText.length;i++){
        cardText[i].style.height = maxHeight + "px";
    }
}

function graphicPlan(){
    seats = document.getElementsByClassName("seat");
    if(seats.length==0) return; 
    stageW = document.getElementById("stage").offsetWidth;
    toggleW = document.getElementById("A");
    if(toggleW!=null)
        toggleW = document.getElementById("A").offsetWidth;
    seatW = (stageW-toggleW-10)/12;
    bodyW = document.body.clientWidth;
    for(let i = 0;i<seats.length;i++){
        button = document.getElementById(i+1);
        if(bodyW<800){
            button.classList.add("btn-sm");
        }
        else if(button.classList.contains("btn-sm")){
            button.classList.remove("btn-sm");
        }
        seats[i].style.width= seatW + "px";
    }
}

function maxCard(){
    max = 0;
    for(let i = 0;i<cardText.length;i++){
        cardText[i].style.height=null;
        current = cardText[i].offsetHeight;
        if(max<current){
            max=current;
        }
    }
    return max;
}


//listen for window resize event
window.addEventListener('resize', function(event){
    var newWidth = window.innerWidth;
    var newHeight = window.innerHeight; 
});

$(window).resize(function() {
    playCard();
    graphicPlan();
  });

function pAlert(modal){
    $(document).ready(function(){
        $(modal).modal('show')
    });
}
