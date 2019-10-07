/* global base_url, bootbox, base_img_url */

"use strict";

$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {         
      
        $("#helpform").validate({
            rules: {
                route: {  
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                other: {  
                    required: {
                        depends:function(){
                            if($.trim($('#route').val()) == 'other'){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }
                        }   
                    }
                },
                caption: { 
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                content: { 
                    required: {
                        depends:function(){
                            var editorText = CKEDITOR.instances.ckeditor.getData();
                            $(this).html(editorText);
                            return true;
                        }   
                    }
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
                if(element.parent().is('.input-group')) {
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
      
      $(document).on('change', '#route', function() {
          
          if($(this).val() == 'other'){
              $('#otherroute').show();
          }
          else{
              $('#otherroute').hide();
          }
      });
       
       
       
       //Add Edit Help Link
        $(document).on('click', '.deletehelplink', function() {
            var id = $(this).attr('data-helplinkid');
            bootbox.confirm("Are you sure to delete help link?", function(result) {
                if (result) {
                    
                    $.post( base_url+"admin/helps/deletehelplink", { id:id }, function( response ) {
                        if (response.success) {
                            bootbox.alert('Help Link deleted successfully');
                            document.location.reload(); 
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        });
        
        
         $(document).on('click', '.edithelplink', function() {
           
            $("#helplinkModel #loading-img").show();
            $("#helplinkModel #sitegriddiv").hide();
            $('#helplinkform').trigger("reset");
            $("#helplinkform .alert-danger").hide(); 
            $("#helplinkform span.help-block").remove();
            $("#helplinkform .has-error").removeClass("has-error");
            $('#helplinkform #btnsave').button("reset");
            $('#helplinkform #btncancel').button("reset");
            $('#helplinkform #btnsave').removeAttr("disabled");
            $('#helplinkform #btncancel').removeAttr("disabled");
            $("#helplinkform .close").css('display', 'block');
            $("#helplinkModel h4.modal-title").html('Edit Link : ' + $(this).attr('data-helplinkid'));
            $("#helplinkModel").modal();

            $("#helplinkform #helplinkid").val($(this).attr('data-helplinkid')); 
            $("#helplinkform #helpid").val($(this).attr('data-helpid')); 
            $("#helplinkform #mode").val('edit');  
             
            setTimeout(function(){ 
                $("#helplinkModel #loading-img").hide();
                $("#helplinkModel #sitegriddiv").show();

            }, 1000);
         
            $("#helplinkform #caption").val($(this).attr('data-caption')); 
            $("#helplinkform #link").val($(this).attr('data-link'));
            $("#helplinkform #sortorder").val($(this).attr('data-sortorder')); 
     
            if($('#isvideo_'+ $(this).attr('data-helplinkid')).is(":checked")){
                $('#helplinkform input[name="isvideo"]').prop('checked', true);
            }
            else{
                $('#helplinkform input[name="isvideo"]').prop('checked', false);
            }   
            if($('#isactive_'+ $(this).attr('data-helplinkid')).is(":checked")){
                $('#helplinkform input[name="isactive"]').prop('checked', true);
            }
            else{
                $('#helplinkform input[name="isactive"]').prop('checked', false);
            }

        });
        
        
        $(document).on('click', '#addhelplink', function() { 
         
            $("#helplinkModel #loading-img").show();
            $("#helplinkModel #sitegriddiv").hide();
            $('#helplinkform').trigger("reset");
            $("#helplinkform .alert-danger").hide(); 
            $("#helplinkform span.help-block").remove();
            $("#helplinkform .has-error").removeClass("has-error");
            $('#helplinkform #btnsave').button("reset");
            $('#helplinkform #btncancel').button("reset");
            $('#helplinkform #btnsave').removeAttr("disabled");
            $('#helplinkform #btncancel').removeAttr("disabled");
            $("#helplinkform .close").css('display', 'block');
            $("#helplinkModel h4.modal-title").html('Add Help Link');
            $("#helplinkModel").modal();

            $("#helplinkform #announcementid").val(''); 
            $("#helplinkform #mode").val('add');  
            $("#helplinkform #reset").val(''); 
            
             $("#helplinkform #caption").val(''); 
            $("#helplinkform #link").val('');
            $("#helplinkform #sortorder").val(parseInt($('#helplinkbody tr').length)+1);
            $('#helplinkform input[name="isvideo"]').prop('checked', true);
            $('#helplinkform input[name="isactive"]').prop('checked', true);
            setTimeout(function(){ 
                $("#helplinkModel #loading-img").hide();
                $("#helplinkModel #sitegriddiv").show();

            }, 1000);
            
 
        });

        $(document).on('click', '#helplinkModel #btnsave', function() {

           
            var caption = $("#helplinkform #caption");
            var link = $("#helplinkform #link");
       
            $("#helplinkform span.help-block").remove();
           
            if($.trim(caption.val()) === "") {
                $(caption).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(caption.parent());
            } else {
                $(caption).parent().removeClass("has-error");
            }

            if($.trim(link.val()) === "") {
                $(link).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(link.parent());
            } else {
                $(link).parent().removeClass("has-error");
            }
 

            if($.trim(caption.val()) === "" || $.trim(link.val()) === ""){
                 return false;
            }

            $("#helplinkform #btnsave").button('loading'); 
            $("#helplinkform #btncancel").button('loading'); 
            $.post( base_url+"admin/helps/addedithelplink", $("#helplinkform").serialize(), function( response ) {
                $('#helplinkform #btnsave').removeAttr("disabled");
                $('#helplinkform #btncancel').removeAttr("disabled");
                $('#helplinkform #btnsave').removeClass("disabled");
                $('#helplinkform #btncancel').removeClass("disabled");
                $('#helplinkform #btnsave').html("Save");
                $('#helplinkform #btncancel').html("Cancel");
                if(response.success) {
                    bootbox.alert('Help Link updated successfully');
                    document.location.reload(); 
                    
                }
                else{
                     $('#helplinkModel .status').html('<div class="alert alert-danger" >'+response.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#helplinkModel #btncancel', function() {
            $("#helplinkModel").modal('hide');
        });
        
        
         $(document).on('change', '.isvideo_l', function(event) {
            var id = $(this).attr('data-helplinkid');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateHelpLink(id, 'isvideo', value);

        }); 
        
        $(document).on('change', '.isactive_l', function(event) {
            var id = $(this).attr('data-helplinkid');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateHelpLink(id, 'isactive', value);

        }); 
    
        var updateHelpLink = function(id, field, value) {
 
            var params = { 
                id  : id,
                field: field,
                value: value
            }; 

            $.post( base_url+"admin/helps/updatehelplink", params, function( response ) {
               
                if(response.success) {
                     
                    
                }
                else{
                     
                }
            }, 'json');

          
        };
});
 