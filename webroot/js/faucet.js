$(function() {
    //set timer for get_satoshi button
    function getTimeRemaining(endtime){
        var t = endtime - Date.now();        
        var seconds = Math.floor( (t/1000) % 60 );
        var minutes = Math.floor( (t/1000)/60 % 60 );
        var hours = Math.floor( (t/(1000*60*60)) % 24 );
        var days = Math.floor( t/(1000*60*60*24) );                
        var clock = {
            'total': t ,
            'days': days ,
            'hours': hours ,
            'minutes': minutes ,
            'seconds': seconds
        };
        //set clock values to use leading zeros
        $.each( clock , function(name , value){
            value = value >=0 && value < 10 ? "0" + value : value;
            clock[name] = value;            
        });
        return clock;
    }//end function
    
    function updateClock(){
        var t = getTimeRemaining(endtime);
        if(t.total <= 0){
            clearInterval(timeinterval);
            $("#get_satoshi").html('Get Satoshi');
        }
        else {
            $("#get_satoshi").html( t.hours + " : " + t.minutes + " : " + t.seconds);
        }
        //$("#clock").html(JSON.stringify(t));                              
    }//end function

    console.log(endtime);
    updateClock(); // run function once at first to avoid delay
    var timeinterval = setInterval(updateClock , 1000);
    
    //only allow button to click if timer is done
    //set timer to users time
    $("#get_satoshi").on('click' , function(){
        if($("#get_satoshi").html() == 'Get Satoshi'){
            $("#faucet\\[time\\]").val(Date.now()); //set timer to endusers time
            $("#faucet").submit();
        } else { alert('Please wait for timer ;)');}
    });
});