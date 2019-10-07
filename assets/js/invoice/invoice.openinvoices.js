
/* global base_url, angular, bootbox */

"use strict";
if($("#openInvoicesCtrl").length) {
     
    
    app.controller('openInvoicesCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
        $scope.filterOptions = {
            filterText: ''
        };

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };
  
       $scope.openInvoices = {
         paginationPageSizes: [10, 25, 50, 100, 200],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         showColumnFooter: true,
         enableColumnMenus: false,
         enableFiltering: false,
         
         columnDefs: [
//             { 
//                displayName:'Select',
//                cellTooltip: true,
//                enableSorting: false,
//                name: 'select',
//                width: 50,
//                headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="openinvoicestbl"/></div>',
//                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="openinvoiceno[]" id="open_invoiceno_{{row.entity.invoiceno}}" data-targetdiv="openinvoicestbl" value="{{row.entity.invoiceno}}" /></div>'
//            },
            { 
                displayName:'Invoice No.',
                cellTooltip: true,
                enableSorting: true,
                name: 'invoiceno',
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Invoice PDF"><a href="'+base_url+'statements/invoicepdf/{{row.entity.invoiceno}}"  target="_blank" >{{row.entity.invoiceno}}</a></div>'
            },
            { 
                displayName:'Invoice Date',
                cellTooltip: true,
                name: 'invoicedate',
                enableSorting: true,
                width: 120
            },
            { 
                displayName:$('#custordref1_label').val(),
                cellTooltip: true,
                name: 'custordref',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:'$ Invoiced',
                cellTooltip: true,
                name: 'Invoiced',
                enableSorting: true,
                width: 120,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalInvoiced = 0;
                    $scope.openInvoices.data.forEach(function(rowEntity) {
                        totalInvoiced =totalInvoiced +  intVal(rowEntity.Invoiced);
                    }); 
                    return '$ '+ parseFloat(totalInvoiced).toFixed(2);
                } 
               
            },
            { 
                displayName:'Balance',
                cellTooltip: true,
                name: 'balance',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalbalance = 0;
                    $scope.openInvoices.data.forEach(function(rowEntity) {
                        totalbalance =totalbalance +  intVal(rowEntity.balance);
                    }); 
                    return '$ '+ parseFloat(totalbalance).toFixed(2);
                } 
            },
            { 
                displayName:'GL Code',
                cellTooltip: true,
                name: 'glCode',
                enableSorting: true,
                width: 100
            },
            { 
                displayName:'Approval Date',
                cellTooltip: true,
                name: 'approvaldate',
                enableSorting: true,
                width: 140 
            },
            { 
                displayName:'Emailed',
                cellTooltip: true,
                name: 'esentdate',
                enableSorting: true,
                width: 140
            },
            { 
                displayName:'Site FM',
                cellTooltip: true,
                name: 'sitefm',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'sitestate',
                enableSorting: true,
                width: 100
            },
            { 
                displayName:$('#sitereflabel1').val(),
                cellTooltip: true,
                name: 'siteref',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:'Job ID',
                cellTooltip: true,
                name: 'jobid',
                enableSorting: true,
                width: 80,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Open Job Detail"><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}"  target="_blank" >{{row.entity.jobid}}</a></div>'
            }
        ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = null;
                  paginationOptions.field = null;
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                getPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               getPage();
             });
         }
       };
       
        
       
       $scope.changeText = function() {
            var text = $scope.filterOptions.filterText;
            if(text.length === 0 || text.length>1) { 
                getPage();
            } 
        };
        $scope.changeFilters = function() {
            getPage();
        };
        $scope.clearFilters = function() {
            paginationOptions.sort = '';
            paginationOptions.field = '';
            $scope.filterOptions = {
                filterText: ''
            }; 
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
        
        $scope.exportToExcel=function(){
            
            window.open(base_url+'statements/downloadexcel/open?'+$.param($scope.filterOptions));

        };
        
       var getPage = function() {
            if(typeof $scope.filterOptions.filterText === 'undefined') {
                $scope.filterOptions.filterText = '';
            }
                
             if(paginationOptions.sort === null) {
                 paginationOptions.sort = '';
             }
             if(paginationOptions.field === null) {
                 paginationOptions.field = '';
             }
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#openInvoicesCtrl .overlay').show();
             $http.get(base_url+'statements/loadopeninvoices?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.openInvoices.totalItems = data.total;
                        $scope.openInvoices.data = data.data;  
                    }
                 
                   $('#openInvoicesCtrl .overlay').hide();
             });
       };

       getPage();
       
        $(document).on('click', '#emailinvoicesbtn', function() {
          
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length === 0){  
                 bootbox.alert('Please select one or more invoices.');
                 return false;
             }

             console.log('click emailinvoices');
             var  iva= [];
             $scope.selectedRows.forEach(function(rowEntity) {
               
                iva.push(rowEntity.invoiceno);

            });
           
             //var invnox=iva.join(',');

             $("#emailinvoicesModel").modal();

             $('#emailinvoicesModel #loading-img').show();
             $('#emailinvoicesModel #sitegriddiv').hide();
             $("#emailinvoicesModel #btnsave").button('reset');
             $('#emailinvoicesModel #btnsave').attr("disabled", "disabled");
             $('#emailinvoicesModel .status').html('');
             $.post( base_url + 'statements/loadselectedinvoices', {invoices: iva,type:'email' }, function( data ) {
                 $('#emailinvoicesModel #btnsave').removeAttr("disabled");
                 if(data.success) {
                     $("#emailinvoicesModel #recipients").val(data.data.emailData.recipients);
                     $("#emailinvoicesModel #subject").val(data.data.emailData.subject);
                     $("#emailinvoicesModel #message").val(data.data.emailData.message);
                     var $result = '';
                     var $total = 0;
                     $.each(data.data.selectedinv, function( key, val ) {
                         $total = $total + intVal(val.Invoiced);
                         $result = $result+'<tr><td><a href="'+ base_url+'statements/invoicepdf/'+val.invoiceno+'" target="_blank"  title="Click To Open PDF"><img  src="'+ base_img_url +'assets/img/pdf_icon.png" /></a>&nbsp;'+ val.jobid+'</td>';
                         $result = $result+'<td>'+val.invoiceno+'</td>';
                         $result = $result+'<td>'+val.custordref+'</td>';
                         $result = $result+'<td>'+val.custordref2+'</td>';
                         $result = $result+'<td>'+val.custordref3+'</td>';
                         $result = $result+'<td class=\"text-right\">'+val.formatedInvoiced+'</td>';
                         $result = $result+'<td>'+val.esentdate+'</td>';
                         $result = $result+'<td><input type="checkbox" name="approvals[]" id="approvals_'+val.invoiceno+'" value="'+val.invoiceno+'"   data-invamt="'+val.Invoiced+'" checked></td></tr>'; 


                     });
                     $('#emailinvoicesModel #approveinvstotal').html(parseFloat($total).toFixed(2));
                     $('#emailinvoicesModel #selectedinvcount').html(data.data.selectedinv.length);
                     $("#emailinvoicesModel #tblemailinvstbody").html($result);

                     $('#emailinvoicesModel #loading-img').hide();
                     $('#emailinvoicesModel #sitegriddiv').show(); 

                 }
                 else{
                     $("#emailinvoicesModel").modal('hide');
                     bootbox.alert(data.message);

                     return false;

                 }

             },'json');

         });
       
     }
     ]);
}
 $( document ).ready(function() {
     //For Open Invoice Tab
    $(document).on('click', '#currentstatementbtn', function() {
        
        var ajaxurl=$(this).data('ajaxurl');
        var custid=$(this).data('customerid');
        var pdfurl=$(this).data('pdfurl');
        customerStatementPDF(ajaxurl,custid,pdfurl);
        
    });
    
   
    
    
   
    $(document).on('click', '#emailinvoicesModel #btnsave', function() {
 
        var tableid='emailinvstbl';
       
        var $chkbox_checked    = $('#'+tableid+' tbody input[type="checkbox"]:checked');
        if($chkbox_checked.length === 0){
            bootbox.alert('Please select invoices for Email Invoices.');
            return false;
        }
        if($.trim($('#emailinvoicesModel #subject').val())===""){
     
            bootbox.alert('Please enter email subject.');
            return false;
        }
        if($.trim($('#emailinvoicesModel #message').val())===""){
     
            bootbox.alert('Please enter email message.');
            return false;
        }
        if($.trim($('#emailinvoicesModel #recipients').val())===""){
     
            bootbox.alert('Please enter email recipients.');
            return false;
        }
        console.log('click Email Invoices');
	var  iva= [];
  
	$('#'+tableid+' tbody input[type="checkbox"]:checked').each(function() {
	 
		var invno= $(this).val();
               
		iva.push(invno);
	 
	});
        
        var subject=$('#emailinvoicesModel #subject').val();
        var message=$('#emailinvoicesModel #message').val();
        var recipients=$('#emailinvoicesModel #recipients').val();
     
        var invnox=iva.join(',');
        bootbox.confirm('Are you sure you want to Email this invoices  <b>"'+ invnox+ '"</b> now ?', function(result) {
            if(result) {
                $.post( base_url+"statements/sendemailinvoices", {invoices: iva,subject:subject,message:message,recipients:recipients }, function( data ) {
                    if(data.success) {
                        $("#emailinvoicesModel").modal('hide');
                        $('#mystatementsstatus').html('<div class="alert alert-success" >'+data.message+'</div>');
                        clearMsgPanel();


                    }
                    else{
                         $('#emailinvoicesModel .status').html('<div class="alert alert-danger" >'+data.message+'</div>');

                    }
                }, 'json');
            }
        });
  
    });
    $(document).on('click', '#emailinvoicesModel #btncancel', function() {
 
         $("#emailinvoicesModel").modal('hide');
    });
 });
 
 function customerStatementPDF(url,custid,pdfurl){
    var str='custida=!A!customerid!e!'+custid;
    var params='&qstr='+str;
    params+='&func=arAgedStatementSingle';
    $.ajax({
             url: url,
             success: function(result) {
                window.open(pdfurl);
            },
             async:   true,
             data: params,
             processData: false,
             type: 'POST',
             beforeSend: function (){

             }

    });
 
}