/* global angular, base_url, bootbox, app */

"use strict";
if($("#completedJobsCtrl").length) {
    app.controller('completedJobsCtrl', [
    '$scope', '$http', 'uiGridConstants', '$timeout', function($scope, $http, uiGridConstants, $timeout) {


        // filter
        $scope.filterOptions = {
            state: '',
            suburb: '',
            contactid: '',
            filterText: '',
            supplierid: ''
        };

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };

        $scope.gridCompleted = {
            paginationPageSizes: [10, 25, 50, 100],
            paginationPageSize: 25,
            useExternalPagination: true,
            useExternalSorting: true,
            enableColumnMenus: false,
            columnDefs: [
                      
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        width:80,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
                    { 
                        displayName:$('#custordref1_label').val(),
                        cellTooltip: true,
                        name: 'custordref',
                        enableSorting: true,
                        width: 110 
                    },
                    { 
                        displayName:'Gl-Code',
                        cellTooltip: true,
                        name: 'glcode',
                        enableSorting: true,
                        width: 80 
                    },
                    { 
                        displayName:'Date In',
                        cellTooltip: true,
                        name: 'leaddate',
                        enableSorting: true,
                        width: 80 
                    },
                    { 
                        displayName:'Due Date',
                        cellTooltip: true,
                        name: 'duedate',
                        enableSorting: true,
                        width: 80 
                    },
                    { 
                        displayName:'Site',
                        cellTooltip: true,
                        name: 'site', 
                        width: 200,
                        cellTemplate: '<div class="ui-grid-cell-contents "  title="{{row.entity.site}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
                    { 
                        displayName:'Suburb',
                        cellTooltip: true,
                        name: 'sitesuburb', 
                        width: 90 
                    },
                    { 
                        displayName:'Site FM',
                        cellTooltip: true,
                        name: 'sitefm', 
                        width: 120 
                    },
                    { 
                        displayName:'Supplier',
                        cellTooltip: true,
                        name: 'supplier', 
                        width: 150,
                         visible :$('#direct_allocate').val()==='1'?true:false
                    },
                    { 
                        displayName:'Job Description',
                        cellTooltip: true,
                        name: 'shortdescription',
                        enableSorting: false,
                        width: 300,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
//                    { 
//                        displayName:'Job Stage',
//                        cellTooltip: true,
//                        name: 'portaldesc',
//                        enableSorting: true,
//                        width: 150 
//                    },
//                    { 
//                        displayName:'Safety',
//                        cellTooltip: true,
//                        name: 'safety',
//                        enableSorting: false,
//                        width: 100 
//                    },
//                    { 
//                        displayName:'Order Doc',
//                        cellTooltip: true,
//                        name: 'orderdoc',
//                        enableSorting: false,
//                        width: 100 
//                    },
//                    { 
//                        displayName:'Images',
//                        cellTooltip: false,
//                        name: 'Images',
//                        enableSorting: true,
//                        width: 100 
//                    }

            ],
            onRegisterApi: function(gridApi) {
                $scope.gridApi = gridApi;

                gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
                  paginationOptions.pageNumber = newPage;
                  paginationOptions.pageSize = pageSize;
                  getPage();
                });

                gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                    if (sortColumns.length === 0) {
                      paginationOptions.sort = '';
                      paginationOptions.field = '';
                    } else {
                      paginationOptions.sort = sortColumns[0].sort.direction;
                      paginationOptions.field = sortColumns[0].field;
                    }
                    getPage();
                });
                
                //ROWS RENDER
                gridApi.core.on.rowsRendered($scope, function () {
                     
                    // each rows rendered event (init, filter, pagination, tree expand)
                    // Timeout needed : multi rowsRendered are fired, we want only the last one
                    if (rowsRenderedTimeout) {
                        $timeout.cancel(rowsRenderedTimeout);
                    }
                    
                    rowsRenderedTimeout = $timeout(function () {
                        alignContainers('#completedjobstbl', $scope.gridApi.grid);
                    });
                    
                });

                //SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#completedjobstbl', $scope.gridApi.grid);
                });
            }
        };

        //$scope.gridCompleted.multiSelect = false;
        //$scope.gridCompleted.modifierKeysToMultiSelect = false;
        //$scope.gridCompleted.noUnselect = true;

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
                state: '',
                suburb: '',
                contactid: '',
                filterText: '',
                supplierid: ''
            }; 
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
//        $scope.$watch(function() {
//            $('#completedJobsCtrl .selectpicker').each(function() {
//                $(this).selectpicker('refresh');
//            });
//        });
//        
        $scope.exportToExcel=function(){
            
            window.open(base_url+'jobs/exportexcel/completed?'+$.param($scope.filterOptions));

        };

        var getPage = function() {

            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#completedJobsCtrl .overlay').show();
            $http.get(base_url+'jobs/loadcompletedjobs?'+ qstring ).success(function (data) {
                $('#completedJobsCtrl .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message);
                    return false;
                }else{ 
                    $scope.gridCompleted.totalItems = data.total;
                    $scope.gridCompleted.data = data.data;  
                } 

            });

        }; 
        
        //getPage();
        
        
        $(document).on('click', '#completedJobsCtrl #c_updateglcode', function() {
            
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            var glcode = $("#c_glcode").val();
            if(glcode === ''){
                bootbox.alert('Please select gl code.');
                return false;
            }
            
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for glcode update.');
                return false;
            }

            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
                jobids.push(rowEntity.jobid);
            });

            bootbox.confirm('Are you sure you want to update Gl Code for <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $("#c_updateglcode").button('loading');
                    $.post( base_url+"jobs/updatemultipleglcodes", { jobids: jobids, glcode:glcode }, function( response ) {
                        if(response.success) {
                            $("#c_updateglcode").button('reset');
                            $("#completedJobsCtrl .btn-refresh" ).click();
                        }
                        else{
                            bootbox.alert(response.message);
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