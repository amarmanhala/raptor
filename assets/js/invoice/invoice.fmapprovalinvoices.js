
/* global base_url, angular, bootbox */

"use strict";
if($("#fmapprovalInvoicesCtrl").length) {
     
     app.controller('fmapprovalInvoicesCtrl', [
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
  
       $scope.fmapprovalInvoices = {
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
//                headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="fmapprovalinvoicestbl"/></div>',
//                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="faapprinvoiceno[]" id="faapprinvoiceno_{{row.entity.invoiceno}}" data-targetdiv="fmapprovalinvoicestbl" value="{{row.entity.invoiceno}}" data-custordref="{{row.entity.custordref}}"  data-custordref2="{{row.entity.custordref2}}"  data-custordref3="{{row.entity.custordref3}}" data-glcode="{{row.entity.glCode}}" data-jobid="{{row.entity.jobid}}"  data-invamt="{{row.entity.Invoiced}}"/></div>'
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
                    $scope.fmapprovalInvoices.data.forEach(function(rowEntity) {
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
            
            window.open(base_url+'statements/downloadexcel/fmapproval?'+$.param($scope.filterOptions));

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
  
            $('#fmapprovalInvoicesCtrl .overlay').show();
             $http.get(base_url+'statements/loadfmapprovalinvoices?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.fmapprovalInvoices.totalItems = data.total;
                        $scope.fmapprovalInvoices.data = data.data;  
                    }
                 
                   $('#fmapprovalInvoicesCtrl .overlay').hide();
             });
       };

       getPage();
       
        $(document).on('click', '#fmapprovalapproveinvoicesbtn', function() {
             
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
            

             var invnox=iva.join(',');
             bootbox.confirm('Are you sure you want to approval this invoices  <b>"'+ invnox+ '"</b> now ?', function(result) {
                    if(result) {
                          $.post( base_url+"statements/updatefmapproval", {invoices: iva,expectval:tbal }, function( data ) {
                              if(data.success) {
                                  $('#mystatementsstatus').html('<div class="alert alert-success" >Invoice approved and ready for final approval.</div>');
                                    clearMsgPanel();

                                    $("#fmapprovalInvoicesCtrl .btn-refresh" ).click();

                                    if ($("#finalapprovalInvoicesCtrl .btn-refresh" ).length > 0) {
                                       $("#finalapprovalInvoicesCtrl .btn-refresh" ).click();
                                    }
                                    else{
                                        $("#openInvoicesCtrl .btn-refresh" ).click();
                                    }
                              }
                              else{
                                   $('#mystatementsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
                                   clearMsgPanel();
                              }
                        }, 'json');
                    }
                });
        });
   
    
       
     }
     ]);
}

 $( document ).ready(function() {
 
    
    
    
 });