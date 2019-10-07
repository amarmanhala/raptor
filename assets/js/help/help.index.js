/* global bootbox, base_url */
$( document ).ready(function() {

    $(document).on('click', '#btn_helpfeedback_yes', function(){
 
        postfeedback(3)
        $("#btn_helpfeedback_yes").button('loading');
        $("#btn_helpfeedback_somewhat").attr('disabled','disabled');
        $("#btn_helpfeedback_no").attr('disabled','disabled');
       
       return false; 
    });
    
     $(document).on('click', '#btn_helpfeedback_somewhat', function(){
 
        postfeedback(2)
        $("#btn_helpfeedback_yes").attr('disabled','disabled');
        $("#btn_helpfeedback_somewhat").button('loading');
        $("#btn_helpfeedback_no").attr('disabled','disabled');
       
       return false; 
    });
    
     $(document).on('click', '#btn_helpfeedback_no', function(){
 
        postfeedback(1)
        $("#btn_helpfeedback_yes").attr('disabled','disabled');
        $("#btn_helpfeedback_somewhat").attr('disabled','disabled');
        $("#btn_helpfeedback_no").button('loading');
       
       return false; 
    });
});
 

var postfeedback = function(rating) {
    var helpid = $('#helpid').val();
    $.post( base_url+"help/submitfeedback", {helpid:helpid,rating:rating}, function( response ) {
        
        $("#btn_helpfeedback_yes").removeAttr('disabled');
        $("#btn_helpfeedback_somewhat").removeAttr('disabled');
        $("#btn_helpfeedback_no").removeAttr('disabled');
        $("#btn_helpfeedback_yes").button('reset');
        $("#btn_helpfeedback_somewhat").button('reset');
        $("#btn_helpfeedback_no").button('reset');
        if (response.success) {
            bootbox.alert(response.message);
        }
        else {
            bootbox.alert(response.message);
        }
    });
};
