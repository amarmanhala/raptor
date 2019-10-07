/* global app, serviceBase, $window, data */

'use strict';
var emailModalErrorMsg = '';

var emailCustomerContacts = [];
var emailInternalContacts = [];
var emailAttachmentDocs = [];
var emailOtherDocuments = [];

var emailData = [];

var openSendEmailDialog = function(jobid, poref, type, custom) {

    
    emailModalErrorMsg = '';

    emailCustomerContacts = [];
    emailInternalContacts = [];
    emailAttachmentDocs = [];
    emailOtherDocuments = [];

    $("#emailDialogForm").trigger('reset');
    $("#emailDialogForm span.help-block").remove();
    $("#emailDialogForm .has-error").removeClass("has-error");
    $("#EmailDialogModal").modal();
    $('#EmailDialogModal #loading-img').show();
    $('#EmailDialogModal #sitegriddiv').hide();
    $('#EmailDialogModal #btnSave').attr('disabled', 'disabled');
    $('#EmailDialogModal #btnCancel').button('loading');
    $('#EmailDialogModal .close').hide();
    $('#EmailDialogModal #emailModalErrorMsg').hide();
    $('#EmailDialogModal #emailModalSuccessMsg').hide();
    
    $.get( base_url+"jobs/loadjobemaildata", { jobid:jobid, poref:poref, type:type, custom:custom }, function( response ) {
        
        $('#EmailDialogModal #btnSave').removeAttr('disabled');
        $('#EmailDialogModal #btnCancel').button('reset');
        $('#EmailDialogModal .close').show();
        if (response.success === false) {
            $("#EmailDialogModal").modal('hide');
            $('#EmailDialogModal #loading-img').show();
            $('#EmailDialogModal #sitegriddiv').hide();
            bootbox.alert(response.message);
        }
        else{
            emailData = response.data.mailData;
            emailInternalContacts = response.data.internalContacts;
            emailCustomerContacts = response.data.customerContacts;
            emailOtherDocuments = response.data.otherDocuments;
            
            var tableBody = '';
            $.each(emailInternalContacts, function( key, val ) {
                if($.trim(val.email) !== '') {
                    tableBody += '<tr id="icon_'+ val.contactid +'"><td class="text-center">';
                    tableBody += '<input type="checkbox" value="'+ val.email +'" onclick="emailToContactSelection('+ key +', \'internal\')"></td>';
                    tableBody += '<td class="text-center"><input type="checkbox" value="'+ val.email +'"  onclick="emailCCContactSelection('+ key +', \'internal\')">';
                    tableBody += '</td><td>'+ val.name +'</td><td>'+ val.email +'</td><td>'+ val.position +'</td></tr>';
                }
            });
            
            $("#emailInternalContacts tbody").html(tableBody);
            
            var tableBody = '';
            $.each(emailCustomerContacts, function( key, val ) {
                if($.trim(val.email) !== '') {
                    tableBody += '<tr id="icon_'+ val.contactid +'"><td class="text-center">';
                    tableBody += '<input type="checkbox" value="'+ val.email +'" onclick="emailToContactSelection('+ key +', \'customer\')"></td>';
                    tableBody += '<td class="text-center"><input type="checkbox" value="'+ val.email +'"  onclick="emailCCContactSelection('+ key +', \'customer\')">';
                    tableBody += '</td><td>'+ val.name +'</td><td>'+ val.email +'</td><td>'+ val.position +'</td></tr>';
                }
            });
            
            $("#emailCustomerContacts tbody").html(tableBody);

            $("#EmailDialogModal #subject").val(emailData.subject);
            $("#EmailDialogModal #message").val(emailData.message);
            
            var tableBody = '';
            $.each(emailOtherDocuments, function( key, val ) {
                tableBody += '<tr id="doc_'+ val.documentid +'"><td class="text-center">';
                tableBody += '<input type="checkbox" value="'+ val.documentid +'" onclick="emailDocSelection('+ key +', \'other\')"></td>';
                tableBody += '</td><td>'+ val.docname +'</td><td>'+ val.doctype +'</td><td>'+ val.documentdesc +'</td><td>'+ val.dateadded +'</td></tr>';
            });
            
            $("#emailOtherDocuments tbody").html(tableBody);
          

            $('#EmailDialogModal #loading-img').hide();
            $('#EmailDialogModal #sitegriddiv').show();
        }
    }, 'json');
};


var closeSendEmailDialog = function() {
    $("#EmailDialogModal").modal('hide'); 
    $("#emailDialogForm").trigger('reset');
};

var openRecipientEmailDialog = function() {
    $("#EmailRecipientDialogModal").modal(); 

};

var closeRecipientEmailDialog = function() {
    $("#EmailRecipientDialogModal").modal('hide'); 
    modaloverlap();
};

var openDocumentEmailDialog = function() {
    $("#EmailDocumentDialogModal").modal(); 

};

var closeDocumentEmailDialog = function() {
    $("#EmailDocumentDialogModal").modal('hide'); 
    modaloverlap();
};


var emailToContactSelection = function (index, type) {

    var toemails = [];
    if(type === 'customer'){
        emailCustomerContacts[index].toselected = !emailCustomerContacts[index].toselected;
    }
    else{
        emailInternalContacts[index].toselected = !emailInternalContacts[index].toselected;
    }

    $.each( emailCustomerContacts, function( key, val ) {
        if(val.toselected){
            toemails.push(val.email);
        }
    });

    $.each( emailInternalContacts, function( key, val ) {
        if(val.toselected){
            toemails.push(val.email);
        }
    });
    
    $("#EmailDialogModal #recipients").val(toemails.join(';'));
};

var emailCCContactSelection = function (index, type) {

    var ccemails = [];
    if(type === 'customer'){
        emailCustomerContacts[index].ccselected = !emailCustomerContacts[index].ccselected;
    }
    else{
        emailInternalContacts[index].ccselected = !emailInternalContacts[index].ccselected;
    }

    $.each( emailCustomerContacts, function( key, val ) {
        if(val.ccselected){
            ccemails.push(val.email);
        }
    });

    $.each( emailInternalContacts, function( key, val ) {
        if(val.ccselected){
            ccemails.push(val.email);
        }
    });
    
    $("#EmailDialogModal #cc").val(ccemails.join(';'));
};

var emailDocSelection = function (index, type) {

    if(type === 'other'){
        emailOtherDocuments[index].selected = !emailOtherDocuments[index].selected;
    }
    addAttachement();
};

var addAttachement = function() {

    var selectedDocs = [];
    var tsize = 0;

    $.each( emailOtherDocuments, function( key, val ) {
        if(val.selected){
            tsize = tsize + parseFloat(val.filesizekb);
            val.dtype = 'other';
            selectedDocs.push(val);
        }
    });

    var tableBody = '';
    $.each(selectedDocs, function( key, val ) {
        tableBody += '<tr id="docA_'+val.documentid+'">'; 
        tableBody += '<td>'+val.docname+'</td><td>'+val.filesizekb+'</td>';
        tableBody += '<td class="text-center">';
        tableBody += '<button type="button" class="btn btn-danger btn-xs" title="Remove" onclick="removeEmailJobdocs('+key+')" ><i class="fa fa-remove"></i></button>';
        tableBody += '</td></tr>';
    });
    console.log(selectedDocs);

    $("#emailattachmenttbl tbody").html(tableBody);

    emailAttachmentDocs = selectedDocs;

    tsize = tsize / 1024;
    tsize = tsize.toFixed(2);
};


var removeEmailJobdocs = function (index) {

    var $item = emailAttachmentDocs[index];

    if($item.dtype === 'other' ){
        $.each(emailOtherDocuments, function( key, val ) {
            if(val.documentid === $item.documentid) {
                emailOtherDocuments[key].selected = false;
            }
        });
    }

    emailAttachmentDocs.splice(index, 1);

    addAttachement();

};

$( document ).ready(function() {
         
    if (typeof $.fn.validate === "function") {         
      
        $("#emailDialogForm").validate({
            rules: {
                recipients: {  
                    validaterequired: true
                },
                cc: {  
                    validaterequired: true
                },
                subject: {  
                    validaterequired: true
                },
                message: {  
                    validaterequired: true
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
                   //$(e).remove();
            },
            errorPlacement: function (error, element) {
                if(element.parent().is('.input-group')) {
                    error.appendTo(element.parent().parent());
                }
                else  {
                    error.appendTo(element.parent());
                }

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

                var attachOtherDocuments = [];
                $.each(emailAttachmentDocs, function( key, val ) {
                    
                    if(val.dtype === 'other') {
                        attachOtherDocuments.push(val);
                    }
                });
                
                var postData = {
                    attachOtherDocuments:   attachOtherDocuments,
                    recipients:             $('#recipients').val(),
                    cc:                     $('#cc').val(),
                    subject:                $('#subject').val(),
                    message:                $('#message').val(),
                    jobid:                  $('#jobdetail_form #jobid').val()
                };
                
                $('#EmailDialogModal #btnSave').button('loading');
                $('#EmailDialogModal #btnCancel').button('loading');
                $('#EmailDialogModal .close').hide();
                $('#EmailDialogModal #emailModalErrorMsg').hide();
                $('#EmailDialogModal #emailModalSuccessMsg').hide();
                
                $.post( base_url+"jobs/sendjobemails", postData, function( response ) {
                    $('#EmailDialogModal #btnSave').button('reset');
                    $('#EmailDialogModal #btnCancel').button('reset');
                    $('#EmailDialogModal .close').show();
                    
                    if (response.success) {
                        if (response.data.success) {
                            $("#EmailDialogModal").modal('hide');
                            $("#emailDialogForm").trigger('reset');
                            $("#myjobstatus").html('');
                            $('#myjobstatus').html('<div class="alert alert-success" >'+response.message+'</div>');
                            clearMsgPanel();
                        }
                        else{
                            $("#EmailDialogModal #emailModalErrorMsg").html(response.data.message);
                        }
                    }
                    else {
                        bootbox.alert(response.message, function(){ modaloverlap();});
                    }
                });
                return false;
            }
        });
    }
});
