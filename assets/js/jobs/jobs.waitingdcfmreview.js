/* global angular, base_url, bootbox, app */

"use strict";
if($("#waitingDCFMReviewJobsCtrl").length) {
    app.controller('waitingDCFMReviewJobsCtrl', [
    '$scope', '$http', 'uiGridConstants', '$timeout', function($scope, $http, uiGridConstants, $timeout) {


        // filter
        $scope.filterOptions = {
            state: '',
            suburb: '',
            contactid: '',
            filterText: '' 
        };

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };

        $scope.gridWaitingDCFMReview = {
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
                        cellTemplate: '<div class="ui-grid-cell-contents"  title="{{row.entity.site}}" ng-bind-html="COL_FIELD | trusted"></div>'
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
                        displayName:'Job Description',
                        cellTooltip: true,
                        name: 'shortdescription',
                        enableSorting: false,
                        width: 300,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
                    { 
                        displayName:'Job Stage',
                        cellTooltip: true,
                        name: 'portaldesc',
                        enableSorting: true,
                        width: 150 
                    }

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
                        alignContainers('#waitingdcfmreviewjobstbl', $scope.gridApi.grid);
                    });
                    
                });

                //SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#waitingdcfmreviewjobstbl', $scope.gridApi.grid);
                });
            }
        };
        
        //$scope.gridWaitingDCFMReview.multiSelect = true;
        //$scope.gridWaitingDCFMReview.modifierKeysToMultiSelect = false;
        //$scope.gridWaitingDCFMReview.noUnselect = true;

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
                filterText: '' 
            };
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
//        $scope.$watch(function() {
//            $('#waitingDCFMReviewJobsCtrl .selectpicker').each(function() {
//                $(this).selectpicker('refresh');
//            });
//        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'jobs/exportexcel/waitingdcfmreview?'+$.param($scope.filterOptions));

        };


        var getPage = function() {

            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#waitingDCFMReviewJobsCtrl .overlay').show();
            $http.get(base_url+'jobs/loadwaitingdcfmreviewjobs?'+ qstring ).success(function (data) {
                $('#waitingDCFMReviewJobsCtrl .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message);
                    return false;
                }else{ 
                    $scope.gridWaitingDCFMReview.totalItems = data.total;
                    $scope.gridWaitingDCFMReview.data = data.data;  
                } 

            });

        }; 
        
        //getPage();
        
            
        $(document).on('click', '#waitingDCFMReviewJobsCtrl #wd_updateglcode', function() {
            
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            var glcode = $("#wd_glcode").val();
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
                    $("#wd_updateglcode").button('loading');
                    $.post( base_url+"jobs/updatemultipleglcodes", { jobids: jobids, glcode:glcode }, function( response ) {
                        if(response.success) {
                            $("#wd_updateglcode").button('reset');
                            $("#waitingDCFMReviewJobsCtrl .btn-refresh" ).click();
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