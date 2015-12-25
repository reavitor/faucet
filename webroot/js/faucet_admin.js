$(function() {
    var current = old = "#" + $("#data\\[default_captcha\\]").val();     
    $(current).slideDown(); //show current captcha
    
    $('#data\\[default_captcha\\]').change(function(){ 
        $(old).slideToggle();   //hide the old one
        current = old = "#" + $(this).val();
        $(current).slideToggle();   //show the new one
    });
    
    $("#save_faucet").click(function(){
        //add hidden fields to form - default captcha - api_key - currency
        var dc = $('<input>')
            .attr('type' , 'hidden')
            .attr('name' , 'data[default_captcha]').val( $("#data\\[default_captcha\\]").val() );
        var api_key = $('<input>')
            .attr('type' , 'hidden')
            .attr('name' , 'data[api_key]').val( $("#data\\[api_key\\]" ).val() );    
        var currency = $('<input>')
            .attr('type' , 'hidden')
            .attr('name' , 'data[currency]').val( $("#data\\[currency\\]" ).val() );     
        $(current).append( dc ).append( api_key ).append( currency );
        $(current).submit();
    });
});

