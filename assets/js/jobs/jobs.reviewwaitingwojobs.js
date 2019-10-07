/* global angular, base_url, bootbox, app */

"use strict";
if($("#reviewWaitingWOJobsCtrl").length) {
    app.controller('reviewWaitingWOJobsCtrl', [
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

        $scope.gridReviewWaitingWOJobs = {
            paginationPageSizes: [10, 25, 50, 100],
            paginationPageSize: 25,
            useExternalPagination: true,
            useExternalSorting: true,
            enableColumnMenus: false,
            columnDefs: [
//                    { 
//                        displayName:'Select',
//                        cellTooltip: true,
//                        enableSorting: false,
//                        name: 'select',
//                        width: 50,
//                        headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="reviewWaitingWOJobtbl"/></div>',
//                        cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="reviewWaitingWOJobId[]"  data-targetdiv="reviewWaitingWOJobtbl" value="{{row.entity.jobid}}"  /></div>'
//                    }, 
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        width:80,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span   data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
                    { 
                        displayName:'Report',
                        name: 'report', 
                        width:100,
                        cellTooltip: true,
                        enableSorting: false,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobawaitingwo/report/{{row.entity.jobid}}" title="view job detail" target="_blank"  ><img src="'+base_img_url+'assets/img/pdf_icon.png"></a></div>'
                    },
                    { 
                        displayName:$('#custordref1_label').val(),
                        cellTooltip: true,
                        name: 'custordref',
                        enableSorting: true,
                        width: 110 
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
                // ROWS RENDER
                gridApi.core.on.rowsRendered($scope, function () {
                    // each rows rendered event (init, filter, pagination, tree expand)
                    // Timeout needed : multi rowsRendered are fired, we want only the last one
                    if (rowsRenderedTimeout) {
                        $timeout.cancel(rowsRenderedTimeout);
                    }
                    rowsRenderedTimeout = $timeout(function () {
                        alignContainers('#reviewWaitingWOJobtbl', $scope.gridApi.grid);
                    });
                });

                // SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#reviewWaitingWOJobtbl', $scope.gridApi.grid);
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
        
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'jobawaitingwo/exportexcel/reviewwaitingwo?'+$.param($scope.filterOptions));

        };

        var getPage = function() {
     
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#reviewWaitingWOJobsCtrl .overlay').show();
            $http.get(base_url+'jobawaitingwo/loadreviewwaitingwojobs?'+ qstring ).success(function (data) {
           
                    $('#reviewWaitingWOJobsCtrl .overlay').hide();
                    if (data.success === false) {
                        bootbox.alert(data.message);
                        return false;
                    }else{ 
                        $scope.gridReviewWaitingWOJobs.totalItems = data.total;
                        $scope.gridReviewWaitingWOJobs.data = data.data;  
                    } 

              });

        }; 
        
        getPage();
        
        
        $(document).on('click', '#reviewWaitingWOJobsCtrl #approvebtn', function() {
 
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for approval.');
                return false;
            }

            console.log('click Waiting Approval >> Approve');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                jobids.push(rowEntity.jobid);

            });


            bootbox.confirm('Are you sure you want to approve <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $.post( base_url+"jobawaitingwo/updatewojobapprove", {jobids: jobids}, function( data ) {
                        if(data.success) {
                            $('#myjobsstatus').html('<div class="alert alert-success" >Jobs approved and ready for DCFM approval.</div>');
                            clearMsgPanel();

                            $("#reviewWaitingWOJobsCtrl .btn-refresh" ).click();
                            $("#WaitingWOApprovalHistoryCtrl .btn-refresh" ).click();

                        }
                        else{
                             $('#myjobsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
                             clearMsgPanel();
                        }
                    }, 'json');
                }
            });

        });

        $(document).on('click', '#reviewWaitingWOJobsCtrl #declinebtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for decline.');
                return false;
            }

            console.log('click Waiting Approval >> decline');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                jobids.push(rowEntity.jobid);

            });


            bootbox.confirm('Are you sure you want to decline <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $.post( base_url+"jobawaitingwo/updatewojobdecline", {jobids: jobids}, function( data ) {
                        if(data.success) {
                            $('#myjobsstatus').html('<div class="alert alert-success" >Jobs declined.</div>');
                            clearMsgPanel(); 
                            $("#reviewWaitingWOJobsCtrl .btn-refresh" ).click();  
                            $("#WaitingWODeclineHistoryCtrl .btn-refresh" ).click();
                        }
                        else{
                             $('#myjobsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
                             clearMsgPanel();
                        }
                    }, 'json');
                }
            });

        });

        $(document).on('click', '#reviewWaitingWOJobsCtrl #requestquotebtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for quote request.');
                return false;
            }

            console.log('click Waiting Approval');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                jobids.push(rowEntity.jobid);

            });

            bootbox.confirm('Are you sure you want to quote request <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $.post( base_url+"jobawaitingwo/updatewojobrequestquote", {jobids: jobids}, function( data ) {
                        if(data.success) {
                            $('#myjobsstatus').html('<div class="alert alert-success">Job Quote requested.</div>');
                            clearMsgPanel(); 
                            $("#reviewWaitingWOJobsCtrl .btn-refresh" ).click();  
                        }
                        else{
                             $('#myjobsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
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

$(document).ready(function(){
    
    
    
});