/* global bootbox */

"use strict";
$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {         

      $("#profile_form").validate({
            rules: {
                name:{
                  required:true  
                },
                phone: {  
                     regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                } ,
                mobile: { 
                     regex: /^[0-9]{4} [0-9]{3} [0-9]{3}$/
                }
            },
            errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                    if($(e).parent().is('.input-group')) {
			 
                         $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                    }
                    else{
                       $(e).parent().parent().removeClass('has-info').addClass('has-error');
                    } 
                           
                    
		},
               
		success: function (e) {
                   if($(e).parent().is('.input-group')) {
			$(e).parent().parent().parent().removeClass("has-error");
                    }
                    else{
                        $(e).parent().parent().removeClass("has-error");
                    }
                   $(e).remove();
		},

		errorPlacement: function (error, element) {
			if(element.val()==="")
			{
				element.focus();
			}
			if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.appendTo(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else if(element.is('.select2')) {
				error.appendTo(element.siblings('[class*="select2-container"]:eq(0)'));
			}
			else if(element.is('.chosen-select')) {
				error.appendTo(element.siblings('[class*="chosen-container"]:eq(0)'));
			}
                        else if(element.parent().is('.input-group')) {
				error.appendTo(element.parent().parent());
			}
			else error.appendTo(element.parent());
		},
                unhighlight: function(e, errorClass, validClass) {
                     if($(e).parent().is('.input-group')) {
			$(e).parent().parent().removeClass("has-error");
                    }
                    else{
                        $(e).parent().removeClass("has-error");
                    }
                    	

                },
            submitHandler: function() {
                return true;
            }
        }); 

    }
});

var readProfileURL = function(input) {
	
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg']) === -1) {
        $(input).val('');
       
        bootbox.alert('invalid file format!');
        return false;
    }
 
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $('#selected-profile').attr('src',e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
   }
};

var readcompanylogoURL = function(input) {
	
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg']) === -1) {
        $(input).val('');
       
        bootbox.alert('invalid file format!');
        return false;
    }
 
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $('#selected-logo').attr('src',e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
   }
};