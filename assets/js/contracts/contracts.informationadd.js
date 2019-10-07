"use strict";

$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {    
	$("#contractdetailform").validate({
		rules: {
                    name: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    
                    
                    contractref: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    }, 
                    contracttypeid: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    startdate: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    enddate: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    managerid: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    } 
		},
		errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                    if($(e).parent().is('.input-group') || $(e).parent().is('.radio-inline')) {
			 
                         $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                    }
                    else{
                       $(e).parent().parent().removeClass('has-info').addClass('has-error');
                    } 
                           
                    
		},
                success: function (e) {
                   if($(e).parent().is('.input-group') || $(e).parent().is('.radio-inline')) {
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
                     if($(e).parent().is('.input-group') || $(e).parent().is('.radio-inline')) {
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

    $(document).on('change', "#contractdetailform #managerid", function() {
        $("#contractdetailform #phone").val($('#contractdetailform #managerid option:selected').attr('data-phone'));
    });
    
 
    $("#startdate").on('changeDate', function(e) {
        $('input[name="enddate"]').datepicker('setStartDate', e.date);
//        if($('input[name="enddate"]').val()!=''){
//            var d1 =e.date; //datepicker (text fields)
//            var d2 = $('#enddate').datepicker( "getDate" ); // datepicker
//            var months;
//            months = (d2.getFullYear() - d1.getFullYear()) * 12;
//            months -= d1.getMonth() + 1;
//            months += d2.getMonth();
//            months = months <= 0 ? 0 : months;
//            $('input[name="months"]').val(months);
//        }
    });
    
   
    $("#enddate").on('changeDate', function(e) {
        if($('input[name="startdate"]').val()==''){
            $('input[name="startdate"]').val($('input[name="enddate"]').val());
        }
        
//         var d2 =e.date; //datepicker (text fields)
//        var d1 = $('#startdate').datepicker( "getDate" ); // datepicker
//         
//            var months;
//            months = (d2.getFullYear() - d1.getFullYear()) * 12;
//            months -= d1.getMonth() + 1;
//            months += d2.getMonth();
//            months = months <= 0 ? 0 : months;
//            $('input[name="months"]').val(months);
    });
   
   
});