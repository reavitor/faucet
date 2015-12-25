$(function() {
    $("#data\\[page_ads\\]").on('change' , function(){
        $.get("./ad?" + $(this).val()  , function( html ){
            $("#data\\[page_ad\\]").val( html );    //put the html in editor
            $("#preview").html( html ); //show the preview
        });
    }); 
    
    $("#save").on('click' , function(e){
        e.preventDefault(); //lat ajax do the post
        $.post("./ads" , $(this.form).serialize()).done(function( html ){ $("#preview").html(html)});
    });

});