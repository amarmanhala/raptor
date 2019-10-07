jQuery(function($) {
	 
	
	$('#validation-form').validate({
		errorElement: 'span',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {
			email: {
				required: true,
				email:true
			},
			 
			 
			phone: {
				required: true,
				phone: 'required'
			} 
			 
			 
		},

		messages: {
			 
			 
		},


		highlight: function (e) {
                    if($(e).parent().is('.input-group')) {
			 
                         $(e).parent().parent().removeClass('has-info').addClass('has-error');
                    }
                    else{
                       $(e).parent().removeClass('has-info').addClass('has-error');
                    } 
                           
                    
		},
               
		success: function (e) {
                   if($(e).parent().is('.input-group')) {
			$(e).parent().parent().removeClass("has-error");
                    }
                    else{
                        $(e).parent().removeClass("has-error");
                    }
                   $(e).remove();
		},

		errorPlacement: function (error, element) {
			if(element.val()=="")
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
		submitHandler: function (form) {
			form.submit();	
		},
		invalidHandler: function (form) {
		 
		}

	});

 
})