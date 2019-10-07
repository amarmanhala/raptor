/* global base_url, bootbox, base_img_url */

"use strict";

$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {         
      
        $("#contact_form").validate({
            rules: {
                phone: {  
                     regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                },
                mobile: { 
                     regex: /^[0-9]{4} [0-9]{3} [0-9]{3}$/
                },
                nonbillable: { 
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                }
            },
            success: function(label) {
                label.remove();
            },
            errorPlacement: function( error, element ) {
              error.appendTo(element.parent().find("span.with-errors"));
            },
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parent().addClass("has-error");	
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parent().removeClass("has-error");	
            },
            submitHandler: function() {
                return true;
            }
        }); 

      }
      
       //Trades code start  
       $(document).on('click','#contact_trades', function() {
            $('#contact_trade_form').trigger("reset");
            $("#contact_trade_form span.help-block").remove();
            $("#contact_trade_form .has-error").removeClass("has-error");

            $("#trade_select").val('');
            $("#trade_select").select2();
            var option = '<option value="">Select Works</option>';
            $("#works_select").html(option);
            $("#works_select").select2();
            option = '<option value="">Select Sub-Works</option>';
            $("#subworks_select").html(option);
            $("#subworks_select").select2();
            $("#tradeModal").modal();

            $('#tradeModal center').show();
            $("#contact_trade_form").hide();

            $('#tradeModal center').hide();
            $("#contact_trade_form").show();
            initjstree();
            var instance = $('#trade_jstree').jstree(true); 
            instance.select_node(false);
            instance.refresh();
       });
       
        
        $("#contact_trade_form .close").on('click', function() {
            $("#trade_jstree").jstree("destroy");
        });
        
        $("#contact_trade_form .close").on('click', function() {
            $("#trade_jstree").jstree("destroy");
        });
        
        $(document).on('change','#trade_select', function() {
            var value = $(this).val();
            var option;
            if(value === '') {
                option = '<option value="">Select Works</option>';
                $("#works_select").html(option);
                $("#works_select").select2();
                option = '<option value="">Select Sub-Works</option>';
                $("#subworks_select").html(option);
                $("#subworks_select").select2();
                return false;
            }
            var contactid = $("#contact_trade_form #contactid").val();
            $.get( base_url+"contacts/loadtradeworks/q", { get:1,contactid:contactid,  tradeid:value }, function( data ) {
                option = '<option value="">Select Works</option>';
                $.each( data, function( key, value ) {
                    option = option + '<option value="'+ value.id +'">'+ value.se_works_name +'</option>';
                });
                $("#works_select").html(option);
                $("#works_select").select2();
               
                
            }, 'json');
            
        });
        $(document).on('change','#works_select', function() {
            var value = $(this).val();
            var option;
            if(value === '') {
                   option = '<option value="">Select Sub-Works</option>';
                   $("#subworks_select").html(option);
                   $("#subworks_select").select2();
                   return false;
            }
            var contactid = $("#contact_trade_form #contactid").val();
            var tradeid = $("#trade_select").val();
            $.get( base_url+"contacts/loadsubworks/q", { get:1,contactid:contactid,tradeid:tradeid,workid:value }, function( data ) {
                     
                option = '<option value="">Select Sub-Works</option>';
                $.each( data, function( key, value ) {
                    option = option + '<option value="'+ value.id +'">'+ value.se_subworks_name +'</option>';
                });
                $("#subworks_select").html(option);
                $("#subworks_select").select2();
                 
            }, 'json');
            
        });
        
        $(document).on('click','#remove_trade', function() {
            var contactid = $("#contact_trade_form #contactid").val();
            var instance = $('#trade_jstree').jstree(true); 
         
            var selectedid=instance._data.core.selected[0];
            if(selectedid==="" || selectedid === undefined){
                bootbox.alert("Please Select Trade/Work/Subwork in tree.");
                return false;
               
            }
    
             
            var selectedtext=instance._data.core.last_clicked.text;
     
            bootbox.confirm('Are you sure you want to remove "'+selectedtext+'" ', function(result) {
                if(result) {
                    
                    $.post( base_url+"contacts/removetradeworksubwork", { contactid:contactid, id:selectedid}, function( data ) {
                        if(!(data.error)) {

                            var instance = $('#trade_jstree').jstree(true); 
                            instance.select_node(false);
                            instance.refresh();
                            reloadtradeworksubworkcombo();  
                        }
                    }, 'json');
                }
            });
            
            
             
        });
        
        $(document).on('click','#add_trade,#add_worksall', function() {
     
            var tradeid = $("#trade_select").val();
            if(tradeid===""){
                bootbox.alert("Please Select Trade.");
                return false;
               
            }
            addtradeworksubwork(tradeid,'all','all');
            
             
        });
        
        $(document).on('click','#add_works,#add_subworksall', function() {
     
            var tradeid = $("#trade_select").val();
            if(tradeid===""){
                bootbox.alert("Please Select Trade.");
                return false;
               
            }
            var worksid = $("#works_select").val();
          
            if(worksid===""){
                bootbox.alert("Please Select Work.");
                return false;
               
            }
            addtradeworksubwork(tradeid,worksid,'all');
             
        });
        
        $(document).on('click','#add_subworks', function() {
     
            var tradeid = $("#trade_select").val();
            if(tradeid===""){
                bootbox.alert("Please Select Trade.");
                return false;
               
            }
            var worksid = $("#works_select").val();
          
            if(worksid===""){
                bootbox.alert("Please Select Work.");
                return false;
               
            }
            var subworksid = $("#subworks_select").val();
          
            if(subworksid===""){
                bootbox.alert("Please Select Sub Work.");
                return false;
               
            }
            addtradeworksubwork(tradeid,worksid,subworksid);
             
        });
        $("#trade_jstree")
            .on('open_node.jstree', function(evt, data) {
              data.instance.set_icon(data.node, base_img_url+'assets/img/minus.png');
            })
            .on('close_node.jstree', function(evt, data) {
              data.instance.set_icon(data.node, base_img_url+'assets/img/plus.png');
        });
});

var addtradeworksubwork=function(tradeid,worksid,subworksid){
    var contactid = $("#contact_trade_form #contactid").val();
    $.post( base_url+"contacts/addtradeworksubwork", { contactid:contactid,tradeid:tradeid, worksid:worksid, subworksid:subworksid}, function( data ) {
            if(!(data.error)) {
             
                var instance = $('#trade_jstree').jstree(true); 
                instance.select_node(false);
                instance.refresh();
                reloadtradeworksubworkcombo();  
            }
    }, 'json');
    
    
};

var reloadtradeworksubworkcombo=function(){
    
    
    var contactid = $("#contact_trade_form #contactid").val();
    var tradeid = $("#trade_select").val();
    var worksid = $("#works_select").val();
    var subworksid = $("#subworks_select").val();
    
    var option = '<option value="">Select Trade</option>';
    $("#trade_select").html(option);
    $("#trade_select").select2();
    
    option = '<option value="">Select Works</option>';
    $("#works_select").html(option);
    $("#works_select").select2();

    option = '<option value="">Select Sub-Works</option>';
    $("#subworks_select").html(option);
    $("#subworks_select").select2();
    
    
    $.get( base_url+"contacts/loadtrades/q", { get:1,contactid:contactid}, function( data ) {
        option = '<option value="">Select Trade</option>';
        var find=false;
        $.each( data, function( key, value ) {
            if(value.id===tradeid)
            {
                find=true;
            }
            option = option + '<option value="'+ value.id +'">'+ value.se_trade_name +'</option>';
        });
        $("#trade_select").html(option);
        $("#trade_select").val(tradeid);
        $("#trade_select").select2();
         
        if(find===true){
             find=false;
             $.get( base_url+"contacts/loadtradeworks/q", { get:1,contactid:contactid,  tradeid:tradeid }, function( data ) {
                option = '<option value="">Select Works</option>';
                $.each( data, function( key, value ) {
                    if(value.id===worksid)
                    {
                        find=true;
                    }
                    option = option + '<option value="'+ value.id +'">'+ value.se_works_name +'</option>';
                });
                $("#works_select").html(option);
                $("#works_select").val(worksid);
                $("#works_select").select2();
               
                if(find===true){
                    find=false;
                    $.get( base_url+"contacts/loadsubworks/q", { get:1,contactid:contactid,tradeid:tradeid,workid:worksid }, function( data ) {
               
                        option = '<option value="">Select Sub-Works</option>';
                        $.each( data, function( key, value ) {
                            option = option + '<option value="'+ value.id +'">'+ value.se_subworks_name +'</option>';
                        });
                        $("#subworks_select").html(option);
                        $("#subworks_select").val(subworksid);
                        $("#subworks_select").select2();
 
                    }, 'json');
                   
                }
                
            }, 'json');
        }
                
    }, 'json');
};

var initjstree = function() {
    var contactid = $("#contact_trade_form #contactid").val();
    $('#trade_jstree').jstree({
        'core' : {
            'data' : {
                "url" : base_url+"contacts/gettradedata",
                "dataType" : "json",
                'type': 'POST',
                'data' : function (node) {
                  return {contactid:contactid, 'id' : node.id };
                }
            }
        },
        'plugins' : [
            "types", "wholerow"
        ]  
    });
    
  
};
 