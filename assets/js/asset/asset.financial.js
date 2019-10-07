/* global base_url, google, bootbox, parseFloat */
$( document ).ready(function() {
    
     $("#tab_financial_form").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                    life_expectancy: {
                        number: true
                    },
                    purchase_price: {
                        number: true,
                    },
                    annual_depreciation_rate: {
                        number: true
                    },
                    replacement_value: {
                        number: true
                    },
                    current_value: {
                        number: true
                    }
            },
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
                        $(e).parent().parent().parent().removeClass("has-error");
                    }
                    else{
                        $(e).parent().parent().removeClass("has-error");
                    }


                },
            submitHandler: function() {
                calculateCValue();
                return true;
            }
    });
  
	
});   
  
 var calculateCValue = function() {
  
     var depreciation_method_id = $.trim($('#tab_financial_form #depreciation_method_id option:selected').text());
     var purchase_price = intVal($("#tab_financial_form #purchase_price").val());
     var life_expectancy = intVal($("#tab_financial_form #life_expectancy").val());
     var current_age = intVal($("#tab_financial_form #current_age").val());
     var annual_depreciation_rate = intVal($("#tab_financial_form #annual_depreciation_rate").val());
   
     if(depreciation_method_id === 'Prime Cost'){
        var CurrentValue = purchase_price *(1 - (current_age/life_expectancy));
      
        $("#tab_financial_form #current_value").val(parseFloat(CurrentValue).toFixed(2))
     }
     if(depreciation_method_id === 'Diminishing Value'){
         
        var startvalue = purchase_price;
        
        while(current_age>0){
            startvalue = startvalue *(1 - (1* (annual_depreciation_rate/life_expectancy)));
            current_age = current_age -1;
        }
         
        var CurrentValue = startvalue;
      
        $("#tab_financial_form #current_value").val(parseFloat(CurrentValue).toFixed(2))
     }
     
 };