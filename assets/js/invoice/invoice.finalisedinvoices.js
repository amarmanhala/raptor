
/* global base_url, angular, bootbox */

"use strict";
if($("#finalisedInvoicesCtrl").length) {
     
    
    app.controller('finalisedInvoicesCtrl', [
        '$scope', '$http', 'uiGridConstants', '$timeout', function($scope, $http, uiGridConstants, $timeout) {

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
  
       $scope.finalizedInvoices = {
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
//                headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="finalisedinvoicestbl"/></div>',
//                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="process[]" id="process_ino_{{row.entity.invoiceno}}" data-targetdiv="finalisedinvoicestbl" value="{{row.entity.invoiceno}}" /></div>'
//            },
            { 
                displayName:'Invoice No.',
                cellTooltip: true,
                enableSorting: true,
                name: 'invoiceno',
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Invoice PDF"><a href="'+base_url+'statements/invoicepdf/{{row.entity.invoiceno}}"  target="_blank" >{{row.entity.invoiceno}}&nbsp;<img  src="'+base_img_url+'assets/img/pdf_icon.png" /></a></div>'
            },
           
            { 
                displayName:'GL Code',
                cellTooltip: true,
                name: 'glCode',
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
                width: 100
             },
//            { 
//                displayName:'GL Code',
//                cellTooltip: true,
//                name: 'editglcode',
//                enableSorting: false,
//                width: 160,
//                cellTemplate: '<div class="ui-grid-cell-contents" title="{{row.entity.glCode}}" ng-bind-html="COL_FIELD | trusted"></div>'
//         
//                
//            },
//            { 
//                displayName:'Cust Order',
//                cellTooltip: true,
//                name: 'custordref',
//                enableSorting: true,
//                width: 130,
//                cellTemplate: '<div class="ui-grid-cell-contents" title="Edit"><input type="text" name="custordref[{{row.entity.invoiceno}}]" id="custordref_{{row.entity.invoiceno}}" value="{{row.entity.custordref}}" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}" class="addchange-ctr"><br/><input type="text" name="custordref2[{{row.entity.invoiceno}}]" id="custordref2_{{row.entity.invoiceno}}" value="{{row.entity.custordref3}}" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}" class="addchange-ctr"><br/><input type="text" name="custordref3[{{row.entity.invoiceno}}]" id="custordref3_{{row.entity.invoiceno}}" value="{{row.entity.custordref3}}" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}" class="addchange-ctr"></div>'
// 
//            },
            { 
                displayName:'Invoice Date',
                cellTooltip: true,
                name: 'invoicedate',
                enableSorting: true,
                width: 120
            },
			{ 
                displayName:'Amount (inc. GST)',
                cellTooltip: true,
                name: 'amount',
                enableSorting: true,
                width: 120,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
               
            },
            { 
                displayName:'Job ID',
                cellTooltip: true,
                name: 'jobid',
                enableSorting: true,
                width: 80,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Open Job Detail"><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}"  target="_blank" >{{row.entity.jobid}}</a></div>'
            },
            { 
                displayName:'Site',
                cellTooltip: true,
                name: 'sitesuburb',
                enableSorting: true,
                width: 200,
                //cellTemplate: '<div class="ui-grid-cell-contents"  title="{{row.entity.siteaddress}}" ng-if="row.entity.allow_address" ><input type="text" name="siteline2[{{row.entity.invoiceno}}]" id="siteline2_{{row.entity.invoiceno}}" value="{{row.entity.siteline2}}" class="f-siteline2" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}"><br/><input type="text" name="sitesuburb[{{row.entity.invoiceno}}]" id="sitesuburb_{{row.entity.invoiceno}}" value="{{row.entity.sitesuburb}}" class="suburb suburbtypeahead" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}" data-suburb="sitesuburb_2_{{row.entity.invoiceno}}" data-state="sitestate_{{row.entity.invoiceno}}" data-postcode="sitepostcode_{{row.entity.invoiceno}}"><input type="hidden" name="sitesuburb2[{{row.entity.invoiceno}}]" id="sitesuburb_2_{{row.entity.invoiceno}}" value="{{row.entity.sitesuburb}}" class="suburb" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}"><br/><input type="text" name="sitestate[{{row.entity.invoiceno}}]" id="sitestate_{{row.entity.invoiceno}}" value="{{row.entity.sitestate}}" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}" readonly><br/><input type="text" name="sitepostcode[{{row.entity.invoiceno}}]" id="sitepostcode_{{row.entity.invoiceno}}" value="{{row.entity.sitepostcode}}" class="postcode postcodetypeahead" data-inv="{{row.entity.invoiceno}}" data-jobid="{{row.entity.jobid}}" readonly></div><div class="ui-grid-cell-contents"  title="{{row.entity.siteaddress}}" ng-if="!row.entity.allow_address" ng-bind-html="row.entity.siteaddress | trusted"></div>'
                cellTemplate: '<div class="ui-grid-cell-contents"  title="{{row.entity.siteaddress}}"   ng-bind-html="row.entity.siteaddress | trusted"></div>'
               
            },
            { 
                displayName:'Site FM',
                cellTooltip: true,
                name: 'sitefm',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Job Description',
                cellTooltip: true,
                name: 'shortdescription',
                 enableSorting: false,
                width: 300,
                cellTemplate: '<div class="ui-grid-cell-contents"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
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
             
             gridApi.selection.on.rowSelectionChanged($scope, function() {
                    
                    var selectedRows = $scope.gridApi.selection.getSelectedRows();
                    
                     
            });
                
            //ROWS RENDER
            gridApi.core.on.rowsRendered($scope, function () {
                     
                // each rows rendered event (init, filter, pagination, tree expand)
                // Timeout needed : multi rowsRendered are fired, we want only the last one
                if (rowsRenderedTimeout) {
                    $timeout.cancel(rowsRenderedTimeout);
                }

                rowsRenderedTimeout = $timeout(function () {
                    alignContainers('#finalisedinvoicestbl', $scope.gridApi.grid);
                });

            });

            //SCROLL END
            gridApi.core.on.scrollEnd($scope, function () {
                alignContainers('#finalisedinvoicestbl', $scope.gridApi.grid);
            });
             
         }
       };
       
//       $scope.finalizedInvoices.multiSelect = false;
//       $scope.finalizedInvoices.modifierKeysToMultiSelect = false;
//       $scope.finalizedInvoices.noUnselect = true;
       
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
            
            window.open(base_url+'statements/downloadexcel/finalised?'+$.param($scope.filterOptions));

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
  
            $('#finalisedInvoicesCtrl .overlay').show();
             $http.get(base_url+'statements/loadfinalisedinvoices?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.finalizedInvoices.totalItems = data.total;
                        $scope.finalizedInvoices.data = data.data;  
                    }
                 
                   $('#finalisedInvoicesCtrl .overlay').hide();
             });
       };

       getPage();
       
       $(document).on('click', '#finalisedapproveinvoicesbtn1', function() {
         
            

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more invoices for approve.');
                return false;
            }

            console.log('click finalise');
            var  iva= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                iva.push(rowEntity.invoiceno);

            }); 

             var invnox=iva.join(',');
             bootbox.confirm('Are you sure you want to Approve this invoices  <b>"'+ invnox+ '"</b> now ?', function(result) {
                    if(result) {
                        $.post( base_url+"statements/updatefinaliseapprove", {invoices: iva }, function( data ) {
                            if(data.success) {

                                $('#mystatementsstatus').html('<div class="alert alert-success" >Invoice approved and ready for your approval.</div>');

                                clearMsgPanel();

                                $("#finalisedInvoicesCtrl .btn-refresh" ).click();

                                if ($("#fmapprovalInvoicesCtrl .btn-refresh" ).length) {
                                    $("#fmapprovalInvoicesCtrl .btn-refresh" ).click();
                                }

                                if ($("#finalapprovalInvoicesCtrl .btn-refresh" ).length) {
                                    $("#finalapprovalInvoicesCtrl .btn-refresh" ).click();
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

        $(document).on('click', '#finalisedapproveinvoicesbtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more invoices for finalise.');
                return false;
            }

            console.log('click finalise');
            var  iva= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                iva.push(rowEntity.invoiceno);

            }); 
 

             var invnox=iva.join(',');
             bootbox.confirm('Are you sure you want to finalise this invoices  <b>"'+ invnox+ '"</b> now ?', function(result) {
                    if(result) {
                          $.post( base_url+"statements/updatefinalise", {invoices: iva }, function( data ) {
                              if(data.success) {
                                  $('#mystatementsstatus').html('<div class="alert alert-success" >Invoice finalised and ready for your approval.</div>');
                                  clearMsgPanel();
                                  $("#finalisedInvoicesCtrl .btn-refresh" ).click();

                                  if ($("#fmapprovalInvoicesCtrl .btn-refresh" ).length) {
                                        $("#fmapprovalInvoicesCtrl .btn-refresh" ).click();
                                    }
                                  if ($("#finalapprovalInvoicesCtrl .btn-refresh" ).length) {
                                      $("#finalapprovalInvoicesCtrl .btn-refresh" ).click();

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
     app.filter('trusted', function ($sce) {
        return function (value) {
          return $sce.trustAsHtml(value);
        };
    });
   
}
  
 
  