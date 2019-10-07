/* global base_url, angular, app, bootbox */

"use strict";
 var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('HelpsCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {
  
         // filter
    $scope.filterOptions = {
        filtertext : '',
        status   : 'active' 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    
    $scope.gridOptions = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [
             
            { 
                displayName:'ID',
                cellTooltip: true,
                name: 'id',
                width: 60
            },
            { 
                displayName:'Route',
                cellTooltip: true,
                name: 'route' 
            },
            { 
                displayName:'Caption',
                cellTooltip: true,
                name: 'caption' 
            },
            { 
                displayName:'Last Updated',
                cellTooltip: true,
                name: 'last_updated',
                enableFiltering: false,
                width: 160
            },
            { 
                displayName:'Active',
                name: 'isactive', 
                width:80,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.isactive == 0">NO</div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.isactive == 1">YES</div>'
            },
            { 
                displayName:'Edit',
                name: 'edit',
                cellTooltip: true,
                enableFiltering: false,  
                enableSorting: false, 
                width: 60,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="Edit Help Topic"><a  href="'+base_url+'admin/helps/edithelp/{{row.entity.id}}" ><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false,  
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteHelp(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
            }
             
         ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                    paginationOptions.sort = '';
                    paginationOptions.field = '';
                } else {
                    paginationOptions.sort = sortColumns[0].sort.direction;
                    paginationOptions.field = sortColumns[0].field;
                }
                HelpsPage();
            });
            
            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               HelpsPage();
            });
 	
         }
       };
       
        
       
      $scope.refreshFilter = function() {
           HelpsPage();
    };
       
       
    var HelpsPage = function() {
           
        
        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        
        
        var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
  
        $scope.overlay = true;
        $http.get(base_url+'admin/helps/loadhelps?'+ qstring, {
            headers : {
                "content-type" : "application/x-www-form-urlencoded"
            }
        }).success(function(response) {
            if (response.success === false) {
                bootbox.alert(response.message);
            }else{
                $scope.gridOptions.totalItems = response.total;
                $scope.gridOptions.data = response.data;  
            }
            $scope.overlay = false;
        });
    };
 
    HelpsPage();
    
    $scope.deleteHelp = function(entity) {

            bootbox.confirm("Are you sure to delete Help <b>"+entity.caption+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"admin/helps/deletehelp", { id:entity.id }, function( response ) {
                        if (response.success) {
                            bootbox.alert('Help deleted successfully');
                            HelpsPage();
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
         
       
    
}
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

 