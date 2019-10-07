/* global angular, base_url, bootbox, app */

"use strict";
if($("#WaitingWODeclineHistoryCtrl").length) {
    app.controller('WaitingWODeclineHistoryCtrl', [
    '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {


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

        $scope.gridWaitingWODeclineHistory = {
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
                        displayName:'Decline Date',
                        cellTooltip: true,
                        name: 'leadDeclinedDate',
                        enableSorting: true,
                        width: 150 
                    },
                    { 
                        displayName:'Declined By',
                        cellTooltip: true,
                        name: 'leadDeclinedBy',
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
            }
        };
        
        $scope.gridWaitingWODeclineHistory.multiSelect = false;
        $scope.gridWaitingWODeclineHistory.modifierKeysToMultiSelect = false;
        $scope.gridWaitingWODeclineHistory.noUnselect = true;

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
            
            window.open(base_url+'jobawaitingwo/exportexcel/waitingwodeclinehistory?'+$.param($scope.filterOptions));

        };


        var getPage = function() {

            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#WaitingWODeclineHistoryCtrl .overlay').show();
            $http.get(base_url+'jobawaitingwo/loadwaitingwodeclinehistoryjobs?'+ qstring ).success(function (data) {
                $('#WaitingWODeclineHistoryCtrl .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message);
                    return false;
                }else{ 
                    $scope.gridWaitingWODeclineHistory.totalItems = data.total;
                    $scope.gridWaitingWODeclineHistory.data = data.data;  
                } 

            });

        }; 
        
        getPage();

    }
    ]);

    app.filter('trusted', function ($sce) {
            return function (value) {
              return $sce.trustAsHtml(value);
            };
    });
}