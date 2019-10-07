/* global bootbox, Customercontacts, app, angular, defaultdateformat */

"use strict";
 var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('activityLogCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

    // filter
    $scope.filterOptions = {    
        company: '',
        customerid: '',
        contact: '',
        contactid:'',
        success: '1', 
        fromdate: '',
        todate: ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.activityLogGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [ 
            {   displayName:'Company', 
                cellTooltip: true, 
                name: 'companyname'
            },
            {   displayName:'Contact Name', 
                cellTooltip: true, 
                name: 'firstname'
            },
            { 
                displayName:'User Name',
                cellTooltip: true,
                name: 'username'
            },
            {   displayName:'Login Time', 
                cellTooltip: true, 
                name: 'login'
            },
            {   displayName:'Success', 
                cellTooltip: true, 
                name: 'success', 
                enableFiltering: false, 
                width: 95,
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.success == 0"><input type="checkbox" value="{{row.entity.id}}" class="security_success" disabled/></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.success == 1"><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="security_success" disabled /></div>'
            },
            { 
                displayName:'IP Address',
                cellTooltip: true,
                name: 'ipaddress'
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
                activityLogPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               activityLogPage();
             });
 	
         }
       };
        $scope.changeCustomerText = function() {
            var text = $scope.filterOptions.company;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
                $scope.filterOptions.customerid = '';
                $scope.filterOptions.contact = '';
                $scope.filterOptions.contactid = ''; 
                activityLogPage();
            } 
        };
        $scope.changeContactText = function() {
            var text = $scope.filterOptions.contact;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
                $scope.filterOptions.contactid = '';
                activityLogPage();
            } 
        };
        $scope.changeFilters = function() {
           activityLogPage();
        };
        
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                company: '',
                customerid: '',
                contact: '',
                contactid:'',
                success: '1', 
                fromdate: '',
                todate: ''
            };
            $('input[name="fromdate"]').datepicker('setEndDate', null);
            $('input[name="todate"]').datepicker('setStartDate', null);
           activityLogPage();
        };
        
        $scope.refreshGrid = function() {
            activityLogPage();
        };
 
        var activityLogPage = function() {
           
           if($scope.filterOptions.customerid === '' || $scope.filterOptions.customerid == undefined || $scope.filterOptions.company === '' || $scope.filterOptions.company == undefined) {
                $scope.filterOptions.customerid = '';
                $scope.filterOptions.contactid = '';
            }
           if($scope.filterOptions.contactid === '' || $scope.filterOptions.contactid == undefined || $scope.filterOptions.contact === '' || $scope.filterOptions.contact == undefined) {
               
                $scope.filterOptions.contactid = '';
            }
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'admin/activitylogs/loadactivitylogs?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.activityLogGrid.totalItems = response.total;
                    $scope.activityLogGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
       
        $scope.exportToExcel = function(){
           var qstring = $.param($scope.filterOptions);
           window.open(base_url+'admin/activitylogs/exportactivitylogs?'+qstring);
        };

        activityLogPage();
        
        
         var deferred;  
     
        //Any function returning a promise object can be used to load values asynchronously
        $scope.getCustomer = function(val) {

            deferred = $q.defer(); 
            $http.get(base_url+'admin/customercontacts/loadcustomersearch', {
                params: {
                            search: val
                       },
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{

                    deferred.resolve(response.data);
                }

            });
            return deferred.promise;  

        };
       
        $scope.onCustomerSelect = function ($item, $model, $label) {
             
            $scope.filterOptions.company = $item.companyname;
            $scope.filterOptions.customerid = $item.customerid; 
            $scope.filterOptions.contact = '';
            $scope.filterOptions.contactid = ''; 
            activityLogPage();
         };
         
          //Any function returning a promise object can be used to load values asynchronously
        $scope.getContact = function(val) {

            if($scope.filterOptions.customerid === '' || $scope.filterOptions.customerid == undefined || $scope.filterOptions.company === '' || $scope.filterOptions.company == undefined) {

                bootbox.alert('Please select Company.');
                return false;
            }
            deferred = $q.defer(); 
            $http.get(base_url+'admin/customercontacts/loadcontactsearch', {
                params: {
                        search: val,
                        customerid: $scope.filterOptions.customerid
                    },
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{

                    deferred.resolve(response.data);
                }

            });
            return deferred.promise;  

        };
       
        $scope.onContactSelect = function ($item, $model, $label) {
             
            $scope.filterOptions.contact = $item.firstname;
            $scope.filterOptions.contactid = $item.contactid; 
            
            activityLogPage();
         };
         
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