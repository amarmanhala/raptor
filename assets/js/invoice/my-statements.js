/* global base_url, parseFloat, bootbox, angular */

var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
$( document ).ready(function() {
    
    // Handle click on "Select all" control
    $(document).on('click', '.ui-grid-header-cell-row input[name="select_all"]', function(e){
        var divtabid=$(this).attr('data-targettableid');
        if(this.checked){
            $('#'+divtabid+' input[data-targetdiv='+divtabid+']:not(:checked)').trigger('click');
        } else {
            $('#'+divtabid+' input[data-targetdiv='+divtabid+']:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
   });
    
    $(document).on('click', '.ui-grid-cell input[type="checkbox"]', function(e){

        var tableid=$(this).data('targetdiv');

        updateDataTableSelectAllCtrl(tableid);

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });
    
   //for query invoice
   $(document).on('click', '.queryinvoicebtn', function() {
         
        var tableid=$(this).data('targettableid');
        var selectedRows = [];
              
        if(tableid === 'finalapprovalinvoicestbl'){
            var appElement = document.getElementById('finalapprovalInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
        }
        else if(tableid === 'fmapprovalinvoicestbl'){
            var appElement = document.getElementById('fmapprovalInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
        } 
        else{
            return false;
        }
         
        if(selectedRows.length !== 1){ 
            bootbox.alert('Please select a single invoice for querying.');
            return false;
        }
        var invoiceno = selectedRows[0].invoiceno;
        $("#queryInvoiceModel #subject").val('Invoice query - DCFM Invoice '+ invoiceno);
        $("#queryInvoiceModel #query").val('');
        $("#queryInvoiceModel #btnsave").button('reset');
        $('#queryInvoiceModel #btnsave').removeAttr("disabled");
        $("#queryInvoiceModel").modal();
         
        
    });
    
    $(document).on('click', '#queryInvoiceModel #btnsave', function() { 
        
        var recipients = $("#queryInvoiceModel #recipients").val();
        var subject =$("#queryInvoiceModel #subject").val();
	var query = $("#queryInvoiceModel #query").val();
        $("#queryInvoiceModel #btnsave").button('loading');
        $.post( base_url+"statements/sendqueryinvoice", {recipients: recipients, subject: subject, message: query  }, function( response ) {
          
            if(response.success) {
                $("#queryInvoiceModel #btnsave").button('reset');
                $('#queryInvoiceModel #btnsave').removeAttr("disabled");
                $("#queryInvoiceModel").modal('hide');

                $('#mystatementsstatus').html('<div class="alert alert-success" >'+response.message+'</div>');
                clearMsgPanel();
            }
            else{
                bootbox.alert(response.message);
                return false;
            }
            
        }, 'json');
     
       //return true;
    });
    
    $(document).on('click', '#queryInvoiceModel #btncancel', function() {
        $("#queryInvoiceModel #btnsave").button('reset');
        $('#queryInvoiceModel #btnsave').removeAttr("disabled");
        $("#queryInvoiceModel").modal('hide');
    });
    
    
    //for Edit Invoice
    $(document).on('click', '.editinvoicebtn', function() {
        
        var selectedRows = [];
        var tableid=$(this).data('targettableid');
         
        if(tableid === 'finalapprovalinvoicestbl'){
            var appElement = document.getElementById('finalapprovalInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
        }
        else if(tableid === 'fmapprovalinvoicestbl'){
            var appElement = document.getElementById('fmapprovalInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
        }
        else if(tableid === 'finalisedinvoicestbl'){
            var appElement = document.getElementById('finalisedInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
            
             
        }
        else{
            return false;
        }
         
        if(selectedRows.length !== 1){
            bootbox.alert('Please select a single invoice for editing.');
            return false;
        }
     
        
        $("#editInvoiceModel #btnsave").button('reset');
        $('#editInvoiceModel #btnsave').removeAttr("disabled");
        
       
        $("#editInvoiceModel #tableid").val(tableid);
        $("#editInvoiceModel #custordref").val(selectedRows[0].custordref);
        $("#editInvoiceModel #custordref2").val(selectedRows[0].custordref2);
        $("#editInvoiceModel #custordref3").val(selectedRows[0].custordref3);
        $("#editInvoiceModel #glcode").val(selectedRows[0].glCode);
        
        $("#editInvoiceModel #invoiceno").val(selectedRows[0].invoiceno);
        $("#editInvoiceModel #jobid").val(selectedRows[0].jobid);
      
        $("#editInvoiceModel #siteline2").val(selectedRows[0].siteline2);
        $("#editInvoiceModel #sitesuburb").val(selectedRows[0].sitesuburb);
        $("#editInvoiceModel #sitesuburb1").val(selectedRows[0].sitesuburb1);
        $("#editInvoiceModel #sitestate").val(selectedRows[0].sitestate);
        $("#editInvoiceModel #sitepostcode").val(selectedRows[0].sitepostcode);
        
        $("#editInvoiceModel").modal();
        
    });
    
    $(document).on('click', '#editInvoiceModel #btnsave', function() { 
        
        var tableid=$("#editInvoiceModel #tableid").val();
        $("#editInvoiceModel #btnsave").button('loading');
        $.post( base_url+"statements/updateorderrefs", $("#editInvoice_form").serialize(), function( response ) {
             $("#editInvoiceModel #btnsave").button('reset');
            $('#editInvoiceModel #btnsave').removeAttr("disabled");
            if(response.success) {
                $("#editInvoiceModel").modal('hide');
                if(tableid === 'finalapprovalinvoicestbl'){
                    if ($("#finalapprovalInvoicesCtrl .btn-refresh" ).length > 0) {
                        $("#finalapprovalInvoicesCtrl .btn-refresh" ).click();
                    }
                }
                if(tableid === 'fmapprovalinvoicestbl'){
                    if ($("#fmapprovalInvoicesCtrl .btn-refresh" ).length > 0) {
                        $("#fmapprovalInvoicesCtrl .btn-refresh" ).click();
                    }
                }
                if(tableid === 'finalisedinvoicestbl'){
                    if ($("#finalisedInvoicesCtrl .btn-refresh" ).length > 0) {
                        $("#finalisedInvoicesCtrl .btn-refresh" ).click();
                    }
                }
                 
                $('#mystatementsstatus').html('<div class="alert alert-success" >'+response.message+'</div>');
                clearMsgPanel();
            }
            else{
                bootbox.alert(response.message);
                return false;
            }
           
        }, 'json');
     
       //return true;
    });
    
    $(document).on('click', '#editInvoiceModel #btncancel', function() {
        $("#editInvoiceModel #btnsave").button('reset');
        $('#editInvoiceModel #btnsave').removeAttr("disabled");
        $("#editInvoiceModel").modal('hide');
    });
    
    
     $(document).on('click', '.batchinvoicesbtn', function() {
        
         
        var tableid=$(this).data('targettableid'); 
        var selectedRows = [];
              
        if(tableid === 'finalapprovalinvoicestbl'){
            var appElement = document.getElementById('finalapprovalInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
        }
        else if(tableid === 'fmapprovalinvoicestbl'){
            var appElement = document.getElementById('fmapprovalInvoicesCtrl');
            var $scope = angular.element(appElement).scope();
            selectedRows = $scope.gridApi.selection.getSelectedRows();
        } 
        else{
            return false;
        }
         
        if(selectedRows.length === 0){  
            bootbox.alert('Please select one or more invoices.');
            return false;
        }
        
	var  invoices= [];
        var amount = 0;
        var n = 0;
        selectedRows.forEach(function(rowEntity) {
               
            amount = amount + intVal(rowEntity.Invoiced);
            invoices.push(rowEntity.invoiceno);
            n++;
        }); 
	 
        
        amount = amount.toFixed(2);
        var message = n + ' invoices totalling $'+amount+' selected. Create batch?'; 
        bootbox.dialog({
           message: "<span class='bigger-110'>" + message + "</span>",
           buttons: 			
           {
               "click" :
               {
                   "label" : "Yes",
                   "className" : "btn-sm btn-success",
                   callback: function(e) { 
                        $.post( base_url + 'statements/createbatchinvoices', { invoices: invoices }, function( data ) {
                            bootbox.hideAll();
                            if(data.success) {
                                    bootbox.alert("Batch invoices created successfully.", function() {
                                    $("#batchHistoryCtrl .btn-refresh" ).click();
                                });
                            } else {
                                    bootbox.alert(data.message, function() {
                                    //window.location.reload();
                                });
                            }  

                        }, 'json');
                   }
               },
               "button" :
               {
                   "label" : "Cancel",
                   "className" : "btn-sm btn-primary",
                   callback: function(e) { 
                        bootbox.hideAll();
                        bootbox.alert("Batch not created.");
                   }
               }
           }
        });
    });
   
    //for check budget
    $(document).on('click', '.checkbudgetbtn', function() {
     
        $("#budgetspend_form input[name=budgettype][value='glcode']").prop("checked", true);
        $("#budgetspend_form #period").val('monthtodate');
        $("#budgetspend_form #period").trigger('change');
      
        $('#budgetSpendModal #loading-img').hide();
        $("#budgetSpendChart").html('');
        $("#budgetSpendModal").modal();
        getBudgetSpendData(); 
    });
     
    
    $(document).on('click', '#budgetspend_form #refreshdata', function() {
        getBudgetSpendData(); 
    });
    
    var getBudgetSpendData = function() {
        
        var spendbudgettype = $('input[name=budgettype]:checked', '#budgetspend_form').val(); 
        var fromdate = $('#budgetspend_form input[name="fromdate"]').datepicker('getDate');
        fromdate = formatCustomDate(fromdate);
        var todate =  $('#budgetspend_form input[name="todate"]').datepicker('getDate');
        todate = formatCustomDate(todate);

        $('#budgetSpendModal #loading-img').show();
        $("#budgetSpendChart").html('');
        $.post( base_url + 'statements/loadbudgetspend', { spendbudgettype: spendbudgettype, fromdate:fromdate, todate:todate }, function( data ) {
            $('#budgetSpendModal #loading-img').hide();
            if(data.success) {
                renderBudgetSpendChart(spendbudgettype, data.data);
            } else {
                bootbox.alert(data.message);
            }  
            
        }, 'json');
    };
    
    $(document).on('change', '#budgetspend_form #period', function() {
        var period = $(this).val();
        
        if(period === 'monthtodate') {
            var date = new Date();
            var todate = formatCustomDate(date);
            date.setDate(1);
            var fromdate = formatCustomDate(date);
            $('#budgetspend_form input[name="fromdate"]').datepicker('setDate', fromdate);
            $('#budgetspend_form input[name="todate"]').datepicker('setDate', todate);
        }
        
        if(period === 'lastmonth') {
            var date = new Date();
            date.setDate(1);
            date.setMonth(date.getMonth()-1);
            var fromdate = formatCustomDate(date);
            var lastdate = new Date(date.getFullYear(), date.getMonth()+1, 0);
            var todate = formatCustomDate(lastdate);
            
            $('#budgetspend_form input[name="fromdate"]').datepicker('setDate', fromdate);
            $('#budgetspend_form input[name="todate"]').datepicker('setDate', todate);
        }
        
        if(period === 'yeartodate') {
            var date = new Date();
            date.setDate(date.getDate());
            var todate = formatCustomDate(date);
            date.setDate(1);
            date.setMonth(0);
            var fromdate = formatCustomDate(date);
 
            $('#budgetspend_form input[name="fromdate"]').datepicker('setDate', fromdate);
            $('#budgetspend_form input[name="todate"]').datepicker('setDate', todate);
        }
    });
    
    $("#budgetspend_form #fromdate").on('changeDate', function(e) {
        $('#budgetspend_form input[name="todate"]').datepicker('setStartDate', e.date);
    });
    
    $("#budgetspend_form #todate").on('changeDate', function(e) {
        $('#budgetspend_form input[name="fromdate"]').datepicker('setEndDate', e.date);
    });
    
    $(document).on('click', '#approveinvstbl tbody input[type="checkbox"], #emailinvstbl tbody input[type="checkbox"]', function(e){
        var tableid=$(this).closest('table').attr('id');
        var $row = $(this).closest('tr');
        if(this.checked){
             $row.addClass('selected');
        } else {
           $row.removeClass('selected');
        }
 
        
        var $chkbox_all        = $('#'+tableid+' tbody input[type="checkbox"]');
        var $chkbox_checked    = $('#'+tableid+' tbody input[type="checkbox"]:checked');
        var chkbox_select_all  = $('#'+tableid+' thead input[name="select_all"]');
        
        $('#'+ tableid + ' #selectedinvcount').html($chkbox_checked.length);

         // Update state of "Select all" control
        if ($chkbox_checked.length === $chkbox_all.length){
            chkbox_select_all.prop('checked', true);
            
         }else{
            chkbox_select_all.prop('checked', false);
         }
        // Prevent click event from propagating to parent
        e.stopPropagation();
   });
    
    // Handle click on "Select all" control
    $(document).on('click', '#approveinvstbl thead input[name="select_all"], #emailinvstbl thead input[name="select_all"]', function(e){
        
        var tableid=$(this).data('targettableid');
 
        if(this.checked){
           $('#'+tableid+' tbody input[type="checkbox"]:not(:checked)').trigger('click');
        } else {
           $('#'+tableid+' tbody input[type="checkbox"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
   });
    
});   
function clearMsgPanel(){
    setTimeout(function(){ 
            $("#mystatementsstatus").html('');
           
    }, 3000);
}
function updateDataTableSelectAllCtrl(divtabid){
 
   var $chkbox_all = $("#"+divtabid+" input[data-targetdiv="+divtabid+"]" );
    var $chkbox_checked    = $("#"+divtabid+" input[data-targetdiv="+divtabid+"]:checked");
   var chkbox_select_all  = $('#'+divtabid+' input[name="select_all"]');
 
   // If none of the checkboxes are checked
   if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.prop('checked', true);
      
   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.prop('checked', false);
      
   }
} 
 