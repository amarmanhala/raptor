/* global bootbox, base_url, app, angular, defaultdateformat */

"use strict";
    app.controller('portalAuditLogCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

    // filter
    $scope.filterOptions = {    
        rulename: '',
        fromdate: '',
        todate: ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.portalAuditLogGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [ 
            {   displayName:'Date', 
                cellTooltip: true, 
                name: 'dateadded'
            },
            {   displayName:'Setting', 
                cellTooltip: true, 
                name: 'setting'
            },
            { 
                displayName:'Old Value',
                cellTooltip: true,
                name: 'oldvalue'
            },
            { 
                displayName:'New Value',
                cellTooltip: true,
                name: 'newvalue'
            },
            { 
                displayName:'Edited By',
                cellTooltip: true,
                name: 'editedby'
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
                portalAuditLogPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               portalAuditLogPage();
             });
 	
         }
       };
        
        $scope.changeFilters = function() {
           portalAuditLogPage();
        };
        
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                rulename: '',
                fromdate: '',
                todate: ''
            };
            $('input[name="fromdate"]').datepicker('setEndDate', null);
            $('input[name="todate"]').datepicker('setStartDate', null);
           portalAuditLogPage();
        };
        
        $scope.refreshAuditLogGrid = function() {
            portalAuditLogPage();
        };
 
        var portalAuditLogPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'settings/loadportalauditlog?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                     
                }else{
                    $scope.portalAuditLogGrid.totalItems = response.total;
                    $scope.portalAuditLogGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
       
        $scope.exportToExcel = function(){
           var qstring = $.param($scope.filterOptions);
           window.open(base_url+'settings/exportportalauditlog?'+qstring);
        };

        portalAuditLogPage();
    }
]);


$( document ).ready(function() {
    
    $("#fromdate").on('changeDate', function(e) {
        $('input[name="todate"]').datepicker('setStartDate', e.date);
    });
    
    $("#todate").on('changeDate', function(e) {
        $('input[name="fromdate"]').datepicker('setEndDate', e.date);
    });
});