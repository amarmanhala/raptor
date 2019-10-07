
/* global base_url, angular, bootbox */

"use strict";
if($("#historyInvoicesCtrl").length) {
     
     app.controller('historyInvoicesCtrl', [
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
  
       $scope.historyInvoices = {
         paginationPageSizes: [10, 25, 50, 100, 200],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         showColumnFooter: true,
         enableColumnMenus: false,
         enableFiltering: false,
         
         columnDefs: [
              
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
                displayName:'Send Date',
                cellTooltip: true,
                name: 'esentdate',
                enableSorting: true,
                width: 130
            },
            { 
                displayName:'Payment Date',
                cellTooltip: true,
                name: 'paymentdate',
                enableSorting: true,
                width: 130 
            },
            { 
                displayName:'Days',
                cellTooltip: true,
                name: 'days',
                enableSorting: true,
                width: 60
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
                footerCellTemplate: '<div class="ui-grid-cell-contents text-right"><span class="ng-binding">Total </span></div>'
            },
            { 
                displayName:'Exc. GST',
                cellTooltip: true,
                name: 'Net',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalNet = 0;
                    $scope.historyInvoices.data.forEach(function(rowEntity) {
                        totalNet =totalNet +  intVal(rowEntity.Net);
                    }); 
                    return '$ '+ parseFloat(totalNet).toFixed(2);
                }  
            },
            { 
                displayName:'GST',
                cellTooltip: true,
                name: 'GST',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalGST = 0;
                    $scope.historyInvoices.data.forEach(function(rowEntity) {
                        totalGST =totalGST +  intVal(rowEntity.GST);
                    }); 
                    return '$ '+ parseFloat(totalGST).toFixed(2);
                } 
            },
            { 
                displayName:'Total Amount',
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
                    $scope.historyInvoices.data.forEach(function(rowEntity) {
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
       
       $scope.historyInvoices.multiSelect = false;
       $scope.historyInvoices.modifierKeysToMultiSelect = false;
       $scope.historyInvoices.noUnselect = true;
       
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
            
            window.open(base_url+'statements/downloadexcel/invoiceshistory?'+$.param($scope.filterOptions));

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
  
            $('#historyInvoicesCtrl .overlay').show();
             $http.get(base_url+'statements/loadhistoryinvoices?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.historyInvoices.totalItems = data.total;
                        $scope.historyInvoices.data = data.data;  
                    }
                 
                   $('#historyInvoicesCtrl .overlay').hide();
             });
       };

       getPage();
     }
     ]);
}
 