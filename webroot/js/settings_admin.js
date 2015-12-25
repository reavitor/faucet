$(function() {
    // Bind the blur event to the function
    $(document).on("blur", "input", function(event) {
        $.post("./admin/settings/save" , $(this.form).serialize()).done(function(data){/*alert(data)*/});
    });
    
    $("#new_setting").click(function(){
        $.get("./js/elements/admin/settings/new_setting.html" , function(html){
            $("#settings").append(html);
        });
    });    
});

