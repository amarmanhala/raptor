/* global base_url, google, bootbox, parseFloat */
$( document ).ready(function() {
     $("#tab_supplier_form").validate({
                    errorElement: 'span',
                    errorClass: 'help-block',
                    rules: {
                            supplier_name : {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            },
                            supplier_address : {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            },
                            supplier_email: {
                                email: true
                            },
                            preferred_contractor_email: {
                                email: true
                            },
                            supplier_website: {
                                url: true
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
                    return true;
                    }
            });
     
});