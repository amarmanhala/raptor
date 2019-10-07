/* global angular, etp_stage, etp_trade, etp_technicians, base_url, etp_client, bootbox */

"use strict";
if($("#potentialJobsCtrl").length) {
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    
    app.controller('potentialJobsCtrl', [
        '$scope', '$http', 'uiGridConstants', '$timeout', function($scope, $http, uiGridConstants, $timeout) {

            // filter
            $scope.declinedfilterOptions = {
                filterText: '' 
            };
            $scope.waitingapprovalfilterOptions = {
                filterText: '' 
            };
             
            var declinedpaginationOptions = {
                pageNumber: 1,
                pageSize: 25,
                sort: '',
                field: ''
            };
            
            var waitingapprovalpaginationOptions = {
                pageNumber: 1,
                pageSize: 25,
                sort: '',
                field: ''
            };
          $scope.waitingApprovalJobs = {
            paginationPageSizes: [10, 25, 50, 100],
            paginationPageSize: 25,
            useExternalPagination: true,
            useExternalSorting: true,
            enableColumnMenus: false,
            multiSelect : false,
            columnDefs: [
//                            { 
//                                displayName:'Select',
//                                name: 'select',
//                                enableSorting: false,
//                                width:60,
//                                cellTooltip: true,
//                                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" data-targetdiv="waitingapprovaltbl"  name="waitingapprovaljobcheckbox[]" value="{{row.entity.joblead_id}}" /></div>',
//                           
//                            },
                            { 
                                displayName:'Job Description',
                                name: 'shortdescription',
                                enableSorting: false,
                                cellTooltip: true,
                                width:300,
                                cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobleaddescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                            },
                             { 
                                displayName:'Site',
                                name: 'siteline2',
                                cellTooltip: true,
                                width:150
                            },
                            { 
                                displayName:'Suburb',
                                name: 'sitesuburb',
                                cellTooltip: true,
                                width:130
                            },
                            { 
                                displayName:'State',
                                name: 'sitestate',
                                cellTooltip: true,
                                width:100
                            },
                            { 
                                displayName:'Technician',
                                name: 'leaduserid',
                                cellTooltip: true,
                                width:150
                            },
                            { 
                                displayName:'Date Added',
                                name: 'leaddate',
                                cellTooltip: true,
                                width:110
                            },
                            { 
                                displayName:'Labour Est. (h)',
                                name: 'labour_estimate',
                                cellTooltip: true,
                                width:130,
                                cellClass: 'text-right', 
                                headerCellClass : 'text-right',
                                footerCellClass : 'text-right'
                            },
                            
                            
                            { 
                                displayName:'Materials Est. ($)',
                                name: 'material_estimate',
                                cellTooltip: true,
                                width:130,
                                cellClass: 'text-right', 
                                headerCellClass : 'text-right',
                                footerCellClass : 'text-right'
                            },
                            { 
                                displayName:'Works Type',
                                name: 'se_works_name',
                                cellTooltip: true,
                                width:150
                            } 
            ],
            onRegisterApi: function(gridApi) {
                $scope.gridApi = gridApi;

                gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
                  waitingapprovalpaginationOptions.pageNumber = newPage;
                  waitingapprovalpaginationOptions.pageSize = pageSize;
                  //getPage();
                  getPage('waitingapproval');
                });
                
                gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                    if (sortColumns.length === 0) {
                      waitingapprovalpaginationOptions.sort = '';
                      waitingapprovalpaginationOptions.field = '';
                    } else {
                      waitingapprovalpaginationOptions.sort = sortColumns[0].sort.direction;
                      waitingapprovalpaginationOptions.field = sortColumns[0].field;
                    }
                    getPage('waitingapproval');
                });
                // ROWS RENDER
                gridApi.core.on.rowsRendered($scope, function () {
                    // each rows rendered event (init, filter, pagination, tree expand)
                    // Timeout needed : multi rowsRendered are fired, we want only the last one
                    if (rowsRenderedTimeout) {
                        $timeout.cancel(rowsRenderedTimeout);
                    }
                    rowsRenderedTimeout = $timeout(function () {
                        alignContainers('#waitingapprovaltbl', $scope.gridApi.grid);
                    });
                });

                // SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#waitingapprovaltbl', $scope.gridApi.grid);
                });
            }
          };

          $scope.declinedJobs = {
            paginationPageSizes: [10, 25, 50, 100],
            paginationPageSize: 25,
            useExternalPagination: true,
            useExternalSorting: true,  
            multiSelect : false,
            enableColumnMenus: false,
            columnDefs: [
//                             { 
//                                displayName:'Select',
//                                name: 'select',
//                                enableSorting: false,
//                                width:60,
//                                cellTooltip: true,
//                                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" data-targetdiv="declinedjobtbl"  name="declinedjobcheckbox[]" value="{{row.entity.joblead_id}}" /></div>',
//                              
//                            },
                            { 
                                displayName:'Job Description',
                                name: 'shortdescription',
                                enableSorting: false,
                                cellTooltip: true,
                                width:300,
                                cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobleaddescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                            },
                             { 
                                displayName:'Site',
                                name: 'siteline2',
                                cellTooltip: true,
                                width:150
                            },
                            { 
                                displayName:'Suburb',
                                name: 'sitesuburb',
                                cellTooltip: true,
                                width:130
                            },
                            { 
                                displayName:'State',
                                name: 'sitestate',
                                cellTooltip: true,
                                width:100
                            },
                            { 
                                displayName:'Technician',
                                name: 'leaduserid',
                                cellTooltip: true,
                                width:150
                            },
                            { 
                                displayName:'Date Added',
                                name: 'leaddate',
                                cellTooltip: true,
                                width:110
                            },
                            { 
                                displayName:'Labour Est. (h)',
                                name: 'labour_estimate',
                                cellTooltip: true,
                                width:130,
                                cellClass: 'text-right', 
                                headerCellClass : 'text-right',
                                footerCellClass : 'text-right'
                            },
                            
                            
                            { 
                                displayName:'Materials Est. ($)',
                                name: 'material_estimate',
                                cellTooltip: true,
                                width:130,
                                cellClass: 'text-right', 
                                headerCellClass : 'text-right',
                                footerCellClass : 'text-right'
                            },
                            { 
                                displayName:'Works Type',
                                name: 'se_works_name',
                                cellTooltip: true,
                                width:150
                            } 

            ],
            onRegisterApi: function(gridApi) {
                $scope.declinedgridApi = gridApi;

                gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
                  declinedpaginationOptions.pageNumber = newPage;
                  declinedpaginationOptions.pageSize = pageSize;
                  //getPage1();
                  getPage('declined');
                });

                
                
                gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                    if (sortColumns.length === 0) {
                      declinedpaginationOptions.sort = '';
                      declinedpaginationOptions.field = '';
                    } else {
                      declinedpaginationOptions.sort = sortColumns[0].sort.direction;
                      declinedpaginationOptions.field = sortColumns[0].field;
                    }
                    getPage('declined');
                });
                
                // ROWS RENDER
                gridApi.core.on.rowsRendered($scope, function () {
                    // each rows rendered event (init, filter, pagination, tree expand)
                    // Timeout needed : multi rowsRendered are fired, we want only the last one
                    if (rowsRenderedTimeout) {
                        $timeout.cancel(rowsRenderedTimeout);
                    }
                    rowsRenderedTimeout = $timeout(function () {
                        alignContainers('#declinedjobtbl', $scope.declinedgridApi.grid);
                    });
                });

                // SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#declinedjobtbl', $scope.declinedgridApi.grid);
                });
 
            }
          };

        $scope.changeText = function(type) {
            var text ;
            if(type === 'declined'){
                text = $scope.declinedfilterOptions.filterText;
            }
            else{
                text = $scope.waitingapprovalfilterOptions.filterText;
            }
            
            if(text.length === 0 || text.length>1) { 
                getPage(type);
            } 
        };
         
        $scope.clearFilters = function(type) {
            
            if(type === 'declined'){
                declinedpaginationOptions.sort = '';
                declinedpaginationOptions.field = '';
                $scope.declinedfilterOptions = {
                    filterText: ''
                }; 
                
            }
            else{
                waitingapprovalpaginationOptions.sort = '';
                waitingapprovalpaginationOptions.field = '';
                $scope.waitingapprovalfilterOptions = {
                    filterText: ''
                }; 
              
            }
           
            getPage(type);
        };
        $scope.refreshGrid = function(type) {
            getPage(type);
        };
        
        
        $scope.exportToExcel=function(type){
            
            if(type === 'declined'){
                window.open(base_url+'potentialjobs/downloadexcel/declined?'+$.param($scope.declinedfilterOptions));
                
            }
            else{
                window.open(base_url+'potentialjobs/downloadexcel/waitingapproval?'+$.param($scope.waitingapprovalfilterOptions));
               
            }
            

        };
        
        
        var getPage = function(type) {
              
            if(type === 'declined'){
                if(typeof $scope.declinedfilterOptions.filterText === 'undefined') {
                    $scope.declinedfilterOptions.filterText = '';
                }

                 if(declinedpaginationOptions.sort === null) {
                     declinedpaginationOptions.sort = '';
                 }
                 if(declinedpaginationOptions.field === null) {
                     declinedpaginationOptions.field = '';
                 }
                var params = { 
                    page  : declinedpaginationOptions.pageNumber,
                    size :  declinedpaginationOptions.pageSize,
                    field : declinedpaginationOptions.field,
                    order : declinedpaginationOptions.sort
                }; 
                var qstring = $.param(params) + '&'+ $.param($scope.declinedfilterOptions);

                $('#declined .overlay').show();
                 $http
                    .get(base_url+'potentialjobs/loaddeclinedjobs?'+ qstring )
                    .success(function (data) {
                        if (data.success === false) {
                            bootbox.alert(data.message);

                        }else{
                            $scope.declinedJobs.totalItems = data.total;
                            $scope.declinedJobs.data = data.data;  
                            
                             
                        }

                       $('#declined .overlay').hide();
                    });
                
            }    
                 
             if(type === 'waitingapproval'){
                if(typeof $scope.waitingapprovalfilterOptions.filterText === 'undefined') {
                    $scope.waitingapprovalfilterOptions.filterText = '';
                }

                 if(waitingapprovalpaginationOptions.sort === null) {
                     waitingapprovalpaginationOptions.sort = '';
                 }
                 if(waitingapprovalpaginationOptions.field === null) {
                     waitingapprovalpaginationOptions.field = '';
                 }
                var params = { 
                    page  : waitingapprovalpaginationOptions.pageNumber,
                    size :  waitingapprovalpaginationOptions.pageSize,
                    field : waitingapprovalpaginationOptions.field,
                    order : waitingapprovalpaginationOptions.sort
                }; 
                var qstring = $.param(params) + '&'+ $.param($scope.waitingapprovalfilterOptions);

                $('#waitingapproval .overlay').show();
                 $http
                    .get(base_url+'potentialjobs/loadwaitingapprovaljobs?'+ qstring )
                    .success(function (data) {
                        if (data.success === false) {
                            bootbox.alert(data.message);

                        }else{
                            $scope.waitingApprovalJobs.totalItems = data.total;
                            $scope.waitingApprovalJobs.data = data.data; 
                             
                        }

                       $('#waitingapproval .overlay').hide();
                    });
                
            }      
 
          }; 

          getPage('waitingapproval');
          getPage('declined');
          
        $(document).on('click', '#approvedeclinedbtn', function() {
        
            $scope.selectedRows = $scope.declinedgridApi.selection.getSelectedRows();

            if($scope.selectedRows.length !== 1){
                bootbox.alert('Select one job for approval.');
                return false;
            }

            var jobleadid = $scope.selectedRows[0].joblead_id;

            window.location.href = base_url+ "logjobquote?jobleadid="+ jobleadid;
        });
          
        $(document).on('click', '#approvewaitingapprovalbtn', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();

            if($scope.selectedRows.length !== 1){
                bootbox.alert('Select one job for approval.');
                return false;
            }

            var jobleadid = $scope.selectedRows[0].joblead_id;

            window.location.href = base_url+ "logjobquote?jobleadid="+ jobleadid;
        });
    
        $(document).on('click', '#declinebtn', function() {
         
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();

            if($scope.selectedRows.length !== 1){
                bootbox.alert('Select one job to decline.');
                return false;
            }

            var jobleadid = $scope.selectedRows[0].joblead_id;

            bootbox.dialog({
               message: "<span class='bigger-110'>Are you sure to decline to selected job.</span>",
               buttons: 			
               {
                   "click" :
                   {
                       "label" : "Yes",
                       "className" : "btn-sm btn-success",
                       callback: function(e) {
                            $.post( base_url + 'potentialjobs/declinejoblead', { jobleadid: jobleadid}, function( response ) {
                            if(response.success) {
                                $('#potentialjobsstatus').html('<div class="alert alert-success" >'+response.message+'</div>');
                                clearMsgPanel();
                                $("#waitingapproval .btn-refresh" ).click();
                                $("#declined .btn-refresh" ).click();

                            } else {
                                bootbox.alert(response.message, function() {
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
        
    app.filter('trusted', function ($sce) {
            return function (value) {
              return $sce.trustAsHtml(value);
            };
    });
}
$( document ).ready(function() {
    $(document).on('click', '#waitingapprovaltbl input[name="waitingapprovaljobcheckbox[]"]', function(e) {
         
        var $chkbox_all = $('#waitingapprovaltbl input[name="waitingapprovaljobcheckbox[]"]');
        $chkbox_all.prop("checked", false); 
        this.checked = true;
         
        e.stopPropagation();
    }); 
    $(document).on('click', '#declinedjobtbl input[name="declinedjobcheckbox[]"]', function(e) {
         
        var $chkbox_all = $('#declinedjobtbl input[name="declinedjobcheckbox[]"]');
        $chkbox_all.prop("checked", false); 
        this.checked = true;
         
        e.stopPropagation();
    }); 
    
});   
function clearMsgPanel(){
    setTimeout(function(){ 
            $("#potentialjobsstatus").html('');
           
    }, 3000);
}
 
   

  