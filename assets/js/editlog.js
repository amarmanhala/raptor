/* global app, base_url, bootbox, serviceBase, angular */

'use strict';
if($("#EditlogCtrl").length) {
    
    if (typeof app === 'undefined'){
   
        var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    }
    
    
    app.controller('EditlogCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {
 
    // filter
    $scope.editlogFilter = {
        fieldname: ''
       
    };

   var paginationOptions = {
     pageNumber: 1,
     pageSize: 25,
     sort: null,
     field: null 
   };

   $scope.editLogGrid = {
    paginationPageSizes: [10, 25, 50, 100],
     paginationPageSize: 25,
     useExternalPagination: true,
     useExternalSorting: true,
     enableColumnResizing: true,
     enableColumnMenus: false,
     columnDefs: [
                     { 
                         displayName:'Edit Date',
                         cellTooltip: true,
                         name: 'editdate',
                         width: 150
                     },
                     { 
                         displayName:'Edited By',
                         cellTooltip: true,
                         name: 'userid',
                         width: 200
                     },
                     { 
                         displayName:'Field Name',
                         cellTooltip: true,
                         name: 'fieldname',
                         width: 200
                     },
                    {   displayName:'Old Value', 
                        cellTooltip: true, 
                        name: 'oldvalue'
                    },
                    {   displayName:'New Value', 
                        cellTooltip: true, 
                        name: 'newvalue'
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
            editLogPage();
        });

         gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
           paginationOptions.pageNumber = newPage;
           paginationOptions.pageSize = pageSize;
           editLogPage();
         });

     }
   };

    $scope.refreshEditlogGrid = function() {
        editLogPage();
    };

    $scope.changeFilters = function() {
       editLogPage();
    };

   var editLogPage = function() {
       
        var params = { 
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort,
            table: $('#editlog_tablename').val(),
            recordid: $('#editlog_recordid').val()
        }; 
        
        var qstring = $.param(params)+'&'+$.param($scope.editlogFilter);
        
        $scope.overlay = true;
        $http.get(base_url+'ajax/loadeditlogs?'+ qstring, {
            headers : {
                "content-type" : "application/x-www-form-urlencoded"
            }
        }).success(function(response) {
            if (response.success === false) {
                bootbox.alert(response.message);
                
            }else{
                $scope.editLogGrid.totalItems = response.total;
                $scope.editLogGrid.data = response.data;  
            }
            $scope.overlay = false;
        });
   };

   editLogPage();
 }
 ]);
}