
/* global base_url, angular, bootbox */

"use strict";
if($("#batchHistoryCtrl").length) {
     
    
    app.controller('batchHistoryCtrl', [
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
  
       $scope.batchHistory = {
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
//                width: 70,
//                //headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="batchhistorytbl"/></div>',
//                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="batchhistoryid[]" id="batchhistoryid_{{row.entity.id}}" data-targetdiv="batchhistorytbl" value="{{row.entity.id}}"   data-recipients="{{row.entity.recipients}}"/></div>'
//            },
            { 
                displayName:'Batch Id',
                cellTooltip: true,
                enableSorting: true,
                name: 'custbatchid',
                width: 80,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Invoice PDF"><a href="'+base_url+'statements/batchinvoicepdf/{{row.entity.id}}"  target="_blank" >{{row.entity.custbatchid}}</a></div>'
            },
            { 
                displayName:'Batch Date',
                cellTooltip: true,
                name: 'batchdate',
                enableSorting: true,
                width: 130
            },
            { 
                displayName:'Created By',
                cellTooltip: true,
                name: 'createdby',
                enableSorting: true,
                width: 200 
            },
            { 
                displayName:'Recipients',
                cellTooltip: true,
                name: 'recipients',
                enableSorting: true, 
            },
            { 
                displayName:'Sent Date',
                cellTooltip: true,
                name: 'esentdate',
                enableSorting: true,
                width: 130 
            },
            { 
                displayName:'No. Invoices',
                cellTooltip: true,
                name: 'invoicecount',
                enableSorting: true,
                width: 100,
                footerCellTemplate: '<div class="ui-grid-cell-contents text-right"><span class="ng-binding">Total </span></div>'
            },
            { 
                displayName:'Total Value',
                cellTooltip: true,
                name: 'totalvalue',
                enableSorting: true,
                width: 120,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totaltotalvalue = 0;
                    $scope.batchHistory.data.forEach(function(rowEntity) {
                        totaltotalvalue =totaltotalvalue +  intVal(rowEntity.totalvalue);
                    }); 
                    return '$ '+ parseFloat(totaltotalvalue).toFixed(2);
                } 
               
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

                selectedRows.forEach(function(rowEntity) {
                    $("#tobatchrecipient").val($.trim(rowEntity.recipients));
                     
                });
                if(selectedRows.length == 0) {
                    $("#tobatchrecipient").val('');
                }
            
            });
         }
       };
       
       $scope.batchHistory.multiSelect = false;
       $scope.batchHistory.modifierKeysToMultiSelect = false;
      
       
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
            
            window.open(base_url+'statements/downloadexcel/batchhistory?'+$.param($scope.filterOptions));

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
  
            $('#batchHistoryCtrl .overlay').show();
             $http.get(base_url+'statements/loadbatchhistory?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.batchHistory.totalItems = data.total;
                        $scope.batchHistory.data = data.data;  
                    }
                 
                   $('#batchHistoryCtrl .overlay').hide();
             });
       };

       getPage();
       
       $(document).on('click', '#exportbatchinvoice', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length !== 1){ 
                 bootbox.alert('Please select a single batch invoice for export.');
                return false;
            }

            var batchid = $scope.selectedRows[0].id;
            
            window.open(base_url + "statements/exportbatchinvoice/" + batchid);
        });
    
    $(document).on('click', '#printbatchinvoice', function() {
        
        $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
        if($scope.selectedRows.length !== 1){ 
            bootbox.alert('Please select a single batch invoice for pdf.');
            return false;
        }

        var batchid = $scope.selectedRows[0].id;
        
         
        var url = base_url + "statements/batchinvoicepdf/"+batchid;
        window.open(url);
        
        
    });    
    
    $(document).on('click', '#emailbatchinvoice', function() {
        
        $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
        if($scope.selectedRows.length !== 1){ 
            bootbox.alert('Select a single batch only.');
            return false;
        }

        var batchid = $scope.selectedRows[0].id;
         
        
        var recipient = $.trim($("#tobatchrecipient").val());
        if(recipient === '') {
            bootbox.alert("Recipient required.");
            return false;
        }
        
        var message = "Email batch to "+recipient+"?";
        bootbox.dialog({
           message: "<span class='bigger-110'>" + message + "</span>",
           buttons: 			
           {
               "click" :
               {
                   "label" : "Yes",
                   "className" : "btn-sm btn-success",
                   callback: function(e) {
                           $.post( base_url + 'statements/batchinvoiceemail', { batchid: batchid, recipient:recipient }, function( data ) {
                        if(data.success) {
                             bootbox.hideAll();
                             var message = "Update the sent date?";
                             bootbox.dialog({
                                message: "<span class='bigger-110'>" + message + "</span>",
                                buttons: 			
                                {
                                    "click" :
                                    {
                                        "label" : "Yes",
                                        "className" : "btn-sm btn-success",
                                        callback: function(e) {
                                            $.post( base_url + 'statements/updatebatchinvoice', { batchid: batchid }, function( data ) {
                                                bootbox.hideAll();
                                                if(data.success) {
                                                    $("#batchHistoryCtrl .btn-refresh" ).click();
                                                    bootbox.alert(data.message, function() {

                                                    });
                                                } else {
                                                    bootbox.alert(data.message, function() {

                                                    });
                                                }  
                                            }, 'json');
                                        }
                                    },
                                    "button" :
                                    {
                                        "label" : "No",
                                        "className" : "btn-sm btn-primary",
                                        callback: function(e) {
                                             bootbox.hideAll();
                                        }
                                    }
                                }
                             });
                        } else {
                            bootbox.alert(data.message, function() {
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
                   }
               }
           }
        });
    });
       
     }
     ]);
}
 