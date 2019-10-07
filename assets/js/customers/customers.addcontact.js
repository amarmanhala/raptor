/* global base_url, bootbox, base_img_url */

"use strict";
 $( document ).ready(function() {

    if (typeof $.fn.validate === "function") {         
      
        $("#contact_form").validate({
            rules: {
                firstname: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                surname: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                email: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    },
                     validemail: /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
                },
                role: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                phone: {  
                     regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                },
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
                
                var pcontact = $("#contact_form #primarycontact:checked");
                var customerid = $("#contact_form #customerid").val();
                var editcontact = $("#contact_form #editcontact").val();
                var isprimary = $("#contact_form #isprimary").val();
                if(pcontact.length > 0 && isprimary == "") {
                    $.post(base_url+"customers/validatecontact/q", { customerid:customerid, contactid:editcontact }, function( data ) {
                      if(data.error) {
                        bootbox.dialog({
                            message: "<span class='bigger-110'>"+data.message+" is already set as the primary contact on this account. Make this contact the primary contact instead?</span>",
                            buttons: 			
                            {
                                "click" :
                                {
                                    "label" : "Yes",
                                    "className" : "btn-sm btn-success",
                                    callback: function() {
                                        bootbox.hideAll();
                                        $('#contact_form #primarycontact').prop('checked');
                                        $("#contact_form #isprimary").val('1');
                                        $("#contact_form").submit();
                                    }
                                },
                                "button" :
                                {
                                    "label" : "No",
                                    "className" : "btn-sm btn-warning",
                                    callback: function() {
                                        bootbox.hideAll();
                                        $('#contact_form #primarycontact').prop('checked', false);
                                        $("#contact_form #isprimary").val('0');
                                        $("#contact_form").submit();
                                    }
                                }
                            }
                        });
                      }
                      else {
                        $("#contact_form #isprimary").val('0');  
                        $("#contact_form").submit();
                      }
                    }, 'json'); 
                    return false;
                } else {
                    return true;
                }
            }
        }); 
      }
});

  var readProfileURL = function(input) {
	
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
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