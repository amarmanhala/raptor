
/* global base_url, angular, bootbox */

"use strict";
if($("#finalapprovalInvoicesCtrl").length) {
     
     app.controller('finalapprovalInvoicesCtrl', [
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
  
       $scope.finalapprovalInvoices = {
         paginationPageSizes: [10, 25, 50, 100, 200],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         showColumnFooter: true,
         enableColumnMenus: false,
         enableFiltering: false,
         
         columnDefs: [
//              { 
//                displayName:'Select',
//                cellTooltip: true,
//                enableSorting: false,
//                name: 'select',
//                width: 50,
//                headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="finalapprovalinvoicestbl"/></div>',
//                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="finalappinvoiceno[]" id="finalappinvoiceno_{{row.entity.invoiceno}}" data-targetdiv="finalapprovalinvoicestbl" value="{{row.entity.invoiceno}}" data-custordref="{{row.entity.custordref}}"  data-custordref2="{{row.entity.custordref2}}"  data-custordref3="{{row.entity.custordref3}}" data-glcode="{{row.entity.glCode}}" data-jobid="{{row.entity.jobid}}"  data-invamt="{{row.entity.Invoiced}}"/></div>'
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
                displayName:$('#custordref2_label').val(),
                cellTooltip: true,
                name: 'custordref2',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:$('#custordref3_label').val(),
                cellTooltip: true,
                name: 'custordref3',
                width: 100,
                footerCellTemplate: '<div class="ui-grid-cell-contents text-right"><span class="ng-binding">Total </span></div>',
            },
             
            { 
                displayName:'Amount ($)',
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
                    $scope.finalapprovalInvoices.data.forEach(function(rowEntity) {
                        totalInvoiced =totalInvoiced +  intVal(rowEntity.Invoiced);
                    }); 
                    return '$ '+ parseFloat(totalInvoiced).toFixed(2);
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
                displayName:'Site FM',
                cellTooltip: true,
                name: 'sitefm',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Approved By',
                cellTooltip: true,
                name: 'approvedby',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Approved Date',
                cellTooltip: true,
                name: 'approvaldate',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Suburb ',
                cellTooltip: true,
                name: 'sitesuburb',
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
        
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'statements/downloadexcel/finalapproval?'+$.param($scope.filterOptions));

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
  
            $('#finalapprovalInvoicesCtrl .overlay').show();
             $http.get(base_url+'statements/loadfinalapprovalinvoices?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.finalapprovalInvoices.totalItems = data.total;
                        $scope.finalapprovalInvoices.data = data.data;  
                    }
                 
                   $('#finalapprovalInvoicesCtrl .overlay').hide();
             });
       };

       getPage();
       
        
        $(document).on('click', '#finalapproveinvoicesbtn', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more invoices for approve.');
                return false;
            }

            console.log('click approval');
            var  iva= [];
            var tbal=0; 
            $scope.selectedRows.forEach(function(rowEntity) {
                
                tbal+=Math.round(rowEntity.Invoiced,2);
                iva.push(rowEntity.invoiceno);

            }); 

            $("#approvalinvoiceModel").modal();

            $('#approvalinvoiceModel #loading-img').show();
            $('#approvalinvoiceModel #sitegriddiv').hide();
            $("#approvalinvoiceModel #btnsave").button('reset');
            $('#approvalinvoiceModel #btnsave').attr("disabled", "disabled");
            $('#approvalinvoiceModel .status').html('');
            $.post( base_url + 'statements/loadselectedinvoices', {invoices: iva,type:'approval' }, function( data ) {
                $('#approvalinvoiceModel #btnsave').removeAttr("disabled");
                if(data.success) {
                    $("#approvalinvoiceModel #estpaydate").val(data.data.estpaydate);
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
                    $('#approvalinvoiceModel #approveinvstotal').html(parseFloat($total).toFixed(2));
                    $('#approvalinvoiceModel #selectedinvcount').html(data.data.selectedinv.length);
                    $("#approvalinvoiceModel #tblapprovalinvbody").html($result);

                    $('#approvalinvoiceModel #loading-img').hide();
                    $('#approvalinvoiceModel #sitegriddiv').show(); 

                }
                else{
                    $("#approvalinvoiceModel").modal('hide');
                    bootbox.alert(data.message);

                    return false;

                }

            },'json');

        });
       
     }
     ]);
}

 $( document ).ready(function() {
 
   
    
    $(document).on('click', '#approvalinvoiceModel #btnsave', function() {
 
        var tableid='approveinvstbl';
       
        var $chkbox_checked    = $('#'+tableid+' tbody input[type="checkbox"]:checked');
        if($chkbox_checked.length === 0){
            bootbox.alert('Please select invoices for approval.');
            return false;
        }
        console.log('click approval');
	var  iva= [];
        var tbal=0; 
	$('#'+tableid+' tbody input[type="checkbox"]:checked').each(function() {
	 
		var invno= $(this).val();
                var bal= $(this).data('invamt');
                tbal+=Math.round(bal,2);
		iva.push(invno);
	 
	});
        
        var estpaydate=$('#approvalinvoiceModel #estpaydate').val();
         var invnox=iva.join(',');
         bootbox.confirm('Are you sure you want to approval this invoices  <b>"'+ invnox+ '"</b> now ?', function(result) {
                if(result) {
                      $.post( base_url+"statements/updateapproval", {invoices: iva,expectval:tbal,estpaydate:estpaydate }, function( data ) {
                          if(data.success) {
                              $("#approvalinvoiceModel").modal('hide');
                              $('#mystatementsstatus').html('<div class="alert alert-success" >Invoice approved and ready show in open invoices.</div>');
                              clearMsgPanel();
                                $("#finalapprovalInvoicesCtrl .btn-refresh" ).click();
                                $("#openInvoicesCtrl .btn-refresh" ).click();
                              
                              
                          }
                          else{
                               $('#approvalinvoiceModel .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                               
                          }
                        }, 'json');
                }
            });
  
    });
    
    $(document).on('click', '#approvalinvoiceModel #btncancel', function() {
 
         $("#approvalinvoiceModel").modal('hide');
    });
    
 });