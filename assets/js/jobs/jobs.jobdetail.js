/* global angular, base_url, readDocURL, readImageURL, bootbox */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 

$( document ).ready(function() {

    $(document).on('click', '#jobdetailcheckbox input[name="jobdtprintal"]', function(e) {
        if(this.checked){
           $('#jobdetailcheckbox input[name="jobdtprint[]"]:not(:checked)').trigger('click');
        } else {
           $('#jobdetailcheckbox input[name="jobdtprint[]"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    $(document).on('click', '#jobdetailcheckbox input[name="jobdtprint[]"]', function(e){

        var chkbox_all = $('#jobdetailcheckbox input[name="jobdtprint[]"]');
        var chkbox_checked    = $('#jobdetailcheckbox input[name="jobdtprint[]"]:checked');
        var chkbox_select_all  = $('#jobdetailcheckbox input[name="jobdtprintal"]');

            // If none of the checkboxes are checked
            if (chkbox_checked.length === chkbox_all.length){
               chkbox_select_all.prop('checked', true);

            // If some of the checkboxes are checked
            } else {
               chkbox_select_all.prop('checked', false);

            }

         // Prevent click event from propagating to parent
         e.stopPropagation();
    });
    
    
     $(document).on('click', '#jobdetail_form #allocatebtn', function() {

            
 
            $("#allocationModal").modal();
  
            $("#allocationModal #btnsave").button('reset');
            $('#allocationModal #btnsave').removeAttr("disabled");
            $('#allocationModal .status').html('');
            $('#allocationform').trigger("reset");
            $("#allocationform .alert-danger").hide(); 
            $("#allocationform span.help-block").remove();
            $("#allocationform .has-error").removeClass("has-error");
            $('#allocationform #btnsave').button("reset");
            $('#allocationform #btncancel').button("reset");
            $('#allocationform #btnsave').removeAttr("disabled");
            $('#allocationform #btncancel').removeAttr("disabled");
            $("#allocationform .close").css('display', 'block');
            $("#allocationModal h4.modal-title").html('Job Allocate');
            
            
            $('#allocationform #rdbdcfm').prop('checked', true);
            $("#allocationform #supplierid").val('');
            $("#allocationform #supplierid").selectpicker('refresh');
            $("#allocationform #internasupplierid").val('');
            $("#allocationform #internasupplierid").selectpicker('refresh');
            $('#othersupplierdiv').hide();
            $('#internalsupplierdiv').hide();
              
        });

        $(document).on('click', "#allocationform input[name=allocateto]", function() {
            var typecode = $(this).val();
      
            $('#othersupplierdiv').hide();
            $('#internalsupplierdiv').hide();
            
            if(typecode === 'Supplier'){
               $('#othersupplierdiv').show();
            }
            if(typecode === 'Internal'){
               $('#internalsupplierdiv').show();
            }
            
        });
        
        
         $(document).on('click', '#jobdetail_form #approveQuotebtn', function() {

            var jobid = $.trim($("#jobdetail_form #jobid").val());;
            console.log('click quote Approval');


            bootbox.confirm('Are you sure you want to approve quote request <b>'+ jobid+ '</b> .?', function(result) {
                if(result) {

                    $("#quoteApproveModal").modal();

                    $('#quoteApproveModal #loading-img').show();
                    $('#quoteApproveModal #sitegriddiv').hide();

                    $("#quoteApproveModal #btnsave").button('reset');
                    $('#quoteApproveModal #btnsave').attr("disabled", "disabled");
                    $('#quoteApproveModal .status').html('');
                    $("#approvequoteform #notes").val('');
                    $("#approvequoteform #openfrom").val('jobdetail');
                    $('#approvequoteform input[name="duedate"]').datepicker({
    minDate: 0
});
                    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
                        if(data.success){ 
                            $("#approvequoteform #duedate").val(data.data.duedate);
                            $("#approvequoteform #duetime").val(data.data.duetime);
                            $("#approvequoteform #jobid").val(data.data.jobid);
                            $("#approvequoteform #custordref").val(data.data.custordref);
                            $("#approvequoteform #custordref2").val(data.data.custordref2);
                            $("#approvequoteform #custordref3").val(data.data.custordref3); 
                            $('#quoteApproveModal #loading-img').hide();
                            $('#quoteApproveModal #sitegriddiv').show(); 
                            $('#quoteApproveModal #btnsave').removeAttr("disabled");

                        }else{
                            $("#quoteApproveModal").modal('hide');
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });

       

        $(document).on('click', '#jobdetail_form #declineQuotebtn', function() {

             

            var jobid = $.trim($("#jobdetail_form #jobid").val());;

            bootbox.confirm('Are you sure you want to decline quote request <b>'+ jobid+ '</b> .?', function(result) {
                if(result) {

                    $("#quoteDeclineModal").modal();

                    $('#quoteDeclineModal #loading-img').show();
                    $('#quoteDeclineModal #sitegriddiv').hide();

                    $("#quoteDeclineModal #btnsave").button('reset');
                    $('#quoteDeclineModal #btnsave').attr("disabled", "disabled");
                    $('#quoteDeclineModal .status').html('');
                    $("#declinequoteform #reason").val('');
                    $("#declinequoteform #notes").val('');
                    $("#declinequoteform #openfrom").val('jobdetail');
                    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
                        if(data.success){   
                            $("#declinequoteform #jobid").val(data.data.jobid); 
                            $('#quoteDeclineModal #loading-img').hide();
                            $('#quoteDeclineModal #sitegriddiv').show(); 
                            $('#quoteDeclineModal #btnsave').removeAttr("disabled");

                        }else{
                            $("#quoteDeclineModal").modal('hide');
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });
           
    $(document).on('click', '#allocationModal #btnsave', function() {

       
 
        $("#allocationform span.help-block").remove();
        
        var typecode = $('#allocationform input[name=allocateto]:checked').val();
        
        if(typecode === 'Supplier'){
            var supplierid = $("#allocationform #supplierid");
            if($.trim(supplierid.val()) === "") {
                $(supplierid).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(supplierid.parent().parent());
                return false;
            } else {
                $(supplierid).parent().parent().removeClass("has-error");
            }
 
        }
        if(typecode === 'Internal'){
            var supplierid = $("#allocationform #internasupplierid");
            if($.trim(supplierid.val()) === "") {
                $(supplierid).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(supplierid.parent().parent());
                return false;
            } else {
                $(supplierid).parent().parent().removeClass("has-error");
            }
 
        }
        $("#allocationform #btnsave").button('loading'); 

        $.post( base_url+"jobs/updatejoballocation", $("#allocationform").serialize(), function( response ) {
            $("#allocationModal #btnsave").button('reset');
            $('#allocationModal #btnsave').removeAttr("disabled");
            if(response.success) {
                if(response.data.success){
                    //$('#myjobsstatus').html('<div class="alert alert-success" >Job allocated.</div>');
                    var jobid= $("#allocationform #jobid").val();
                    window.location.href = base_url+ "jobs/jobdetail/"+ jobid;
                }
                else{
                    bootbox.dialog({
                                     message: "<span class='bigger-110'>"+response.data.message+"</span>",
                                     buttons: 			
                                     {
                                        "click" :
                                         {
                                             "label" : "OK",
                                             "className" : "btn-sm btn-success",
                                             callback: function() {
                                                  window.open(base_url+'suppliers');
                                            }
                                         },
                                         "cancel" :
                                         {
                                            "label" : "Cancel",
                                            "className" : "btn-sm  btn-primary falcon-warning-btn",
                                            callback: function() {
                                               modaloverlap();
                                            }
                                         }
                                     }
                                });
                        
                        $('#allocationModal .status').html('<div class="alert alert-danger" >'+response.data.message+'</div>');
                }
            }
            else{
                 $('#allocationModal .status').html('<div class="alert alert-danger" >'+response.message+'</div>');
            }
        }, 'json');
    });
    
    $(document).on('click', '#jobdetail_form #updateglcode', function() {
        
        var glcode = $.trim($("#jobdetail_form #jobdetail_glcode").val());
        if(glcode == '') {
            return false;
        }
        
        $("#jobdetail_form #updateglcode").button('loading');
        $.post( base_url+"jobs/updateglcode", { glcode: glcode, jobid:$("#jobdetail_form #jobid").val() }, function( response ) {
            if(response.success) {
                $("#jobdetail_form #updateglcode").button('reset');
            }
            else {
                bootbox.alert(response.message);
            }
        }, 'json');
    });
    return false;
    
});

function clearMsgPanel() {
    setTimeout(function(){ 
        $("#myjobstatus").html('');
    }, 3000);
}

var generatePDF = function() {
    
    var length = $('#jobdetailcheckbox input[name="addresscheckbox[]"]').length;
    
    var chkbox_checked = $('#jobdetailcheckbox input[name="jobdtprint[]"]:checked');
    if(chkbox_checked.length === 0){
        bootbox.alert('Please select print option.');
        return false;
    }
    
    var options = [];
    

    $(chkbox_checked).each(function() {
        options.push($(this).val());
    });
            
    var jobid = $("#jobdetail_form #jobid").val();
    var url = base_url+'jobs/printjob/?jobid='+jobid+'&op='+options;
    window.open(url);
};