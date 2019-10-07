"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 


$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {    
	$("#supplierdetailform").validate({
		rules: {
                    companyname: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    
                    
                    structure: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    abn: {

                         regex: /^[0-9]{2} [0-9]{3} [0-9]{3} [0-9]{3}$/
                    },
                    typeid: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    shipping1: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    shipsuburb: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    shipstate: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    shippostcode: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    mail1: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    mailsuburb: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    state: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    postcode: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    phone: {  
                         regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                    } ,
                    
                    fax: {  
                         regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                    } ,
                    email: { 
                            required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        },
                        regex: /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
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

    $(document).on('change', "#supplierdetailform #tradeid", function() {
        $("#supplierdetailform #primarytrade").val($('#supplierdetailform #tradeid option:selected').text());
    });
    

    $(document).on('click', "#supplierdetailform input[name=typeid]", function() {
        var typecode = $(this).attr('data-code');
        
        if(typecode === 'T'){
           $('.primarytradediv').show();
        }
        else{
            $("#supplierdetailform #primarytrade").val('');
            $("#supplierdetailform #tradeid").val('');
           $('.primarytradediv').hide();
        }
    });
   $(document).on('click', "#btncopyaddress", function() {
       $("#supplierdetailform #mail1").val($("#supplierdetailform #shipping1").val());
       $("#supplierdetailform #mail2").val($("#supplierdetailform #shipping2").val());
       $("#supplierdetailform #mailsuburb").val($("#supplierdetailform #shipsuburb").val());
       $("#supplierdetailform #mailsuburb1").val($("#supplierdetailform #shipsuburb1").val());
       $("#supplierdetailform #state").val($("#supplierdetailform #shipstate").val());
       $("#supplierdetailform #postcode").val($("#supplierdetailform #shippostcode").val());
   });
   
});
