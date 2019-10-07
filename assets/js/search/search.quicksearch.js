/* global angular, base_url, bootbox */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 

app.controller('QuickSearchCtrl', [
'$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {


    // filter
    $scope.filterOptions = {
        filterText: $('#searchtext').val()
    };

   var paginationOptions = {
          pageNumber: 1,
          pageSize: 25,
          sort: null,
          field: null
        };

        $scope.jobs = {
            paginationPageSizes: [10, 25, 50, 100],
            paginationPageSize: 25,
            useExternalPagination: true,
            useExternalSorting: true,
            enableFiltering: false,
            enableRowSelection: true,
            enableRowHeaderSelection: false,
            multiSelect:false,
            enableColumnMenus: false,
            columnDefs: [
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        width:100,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
                    { 
                        displayName:$('#custordref1_label').val(),
                        cellTooltip: true,
                        name: 'custordref',
                        enableSorting: true,
                        width: 150 
                    },
                    { 
                        displayName:'Date In',
                        cellTooltip: true,
                        name: 'leaddate',
                        enableSorting: true,
                        width: 100 
                    },
                    { 
                        displayName:'Due Date',
                        cellTooltip: true,
                        name: 'duedate',
                        enableSorting: true,
                        width: 100 
                    },
                    { 
                        displayName:'Job Stage',
                        cellTooltip: true,
                        name: 'portaldesc',
                        enableSorting: true,
                        width: 130 
                    },
                    { 
                        displayName:'Site',
                        cellTooltip: true,
                        name: 'site', 
                        width: 200,
                        cellTemplate: '<div class="ui-grid-cell-contents"  title="{{row.entity.site}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
                    { 
                        displayName:'Job Description',
                        cellTooltip: true,
                        name: 'shortdescription',
                        enableSorting: false,
                         width: 300,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
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
  

    var getPage = function() {

        var params = { 
            page  : paginationOptions.pageNumber,
            size :  paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);

        $('#QuickSearchCtrl .overlay').show();
        $http.get(base_url+'jobs/loadjobsearchresult?'+ qstring ).success(function (data) {
            $('#QuickSearchCtrl .overlay').hide();
            if (data.success === false) {
                bootbox.alert(data.message);
               
            }else{
                $scope.jobs.totalItems = data.total;
                $scope.jobs.data = data.data;  
            }
        });
    }; 
    if($('#searchtext').val() != ''){
        getPage();
    }
  
}
]);

 app.filter('trusted', function ($sce) {
        return function (value) {
          return $sce.trustAsHtml(value);
        };
    });
  