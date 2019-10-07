/* global bootbox, base_url, app, angular, defaultdateformat */
 
"use strict";

var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('portalSetupCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {
      
     // filter
    $scope.portalSettingsfilterOptions = {    
        customer: '',
        customerid: '',
        filtertext : ''
    };
 
    $scope.portalSettingsGrid = {
        enableSorting: false,
        enableColumnMenus: false,
        enablePagination:false,
        enablePaginationControls:false,
        columnDefs: [ 
             {   displayName:'ID', 
                cellTooltip: true, 
                name: 'rulename_id', 
                width:50
            },
            {   displayName:'Setting', 
                cellTooltip: true, 
                name: 'caption'
            },
            {   displayName:'Value', 
                name: 'value',
                 
                headerCellClass : 'text-center', 
                cellTemplate: '\
                            <div class="ui-grid-cell-contents text-center" ng-if="row.entity.valuetype == \'N\'"><input name="portaltext" type="text" data-rulename_id="{{row.entity.rulename_id}}" value="{{row.entity.value}}" class="form-control allownumericwithoutdecimal" /></div>\n\
                            <div class="ui-grid-cell-contents text-center" ng-if="row.entity.valuetype == \'S\'"><input name="portaltext" type="text" data-rulename_id="{{row.entity.rulename_id}}" value="{{row.entity.value}}" class="form-control" /></div>\n\
                            <div class="ui-grid-cell-contents text-center" ng-if="row.entity.valuetype == \'B\'"><input type="checkbox" data-rulename_id="{{row.entity.rulename_id}}" class="chk_rule_value" ng-checked="row.entity.value == 1" /></div>'
            },
             { 
                displayName:'For Master',
                name: 'is_master', 
                width:100,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">For Master</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.rulename_id}}" data-rulename_id="{{row.entity.rulename_id}}"  class="chk_is_master"  ng-checked="row.entity.is_master == 1" /></div>'
            },
             { 
                displayName:'Active',
                name: 'is_sitefm', 
                width:100,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">For Site FM</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.rulename_id}}" data-rulename_id="{{row.entity.rulename_id}}"  class="chk_is_sitefm"  ng-checked="row.entity.is_sitefm == 1" /></div>'
            },
             { 
                displayName:'Active',
                name: 'is_sitecontact', 
                width:120,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">For Site Contact</div>',
               cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.rulename_id}}" data-rulename_id="{{row.entity.rulename_id}}"  class="chk_is_sitecontact"  ng-checked="row.entity.is_sitecontact == 1" /></div>'
            }
         ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
         }
       };

       $scope.changeCustomerText = function(type) {
           if(type === 'portalsetting'){
               var text = $scope.portalSettingsfilterOptions.customer;
                if(text === undefined){
                    return false;
                }
                if(text === null || text.length === 0) { 
                    $scope.portalSettingsGrid.totalItems = 0;
                    $scope.portalSettingsGrid.data = [];  
                }
               
            }
            else{
                var text = $scope.filterOptions.customer;
                if(text === undefined){
                    return false;
                }
                if(text === null || text.length === 0) { 
                    $scope.portalAuditLogGrid.totalItems = 0;
                    $scope.portalAuditLogGrid.data = [];  
                }
                
            }
             
        };
        
        $scope.changeText = function() {
            var text = $scope.portalSettingsfilterOptions.filtertext;
            
            if(text.length === 0 || text.length>1) { 
                portalSettingsPage();
            } 
        };
        $scope.clearFilters = function() {
             $scope.portalSettingsfilterOptions = {    
                customer: '',
                customerid: '',
                filtertext : ''
            };
            
            $scope.portalAuditLogGrid.totalItems = 0;
            $scope.portalAuditLogGrid.data = [];  
        };
       
       
        $scope.refreshPortalSettingsGrid = function() {
            portalSettingsPage();
        };
 
        $scope.exportPortalSettings = function(){ 
            if($scope.portalSettingsfilterOptions.customerid === '' || $scope.portalSettingsfilterOptions.customerid == undefined || $scope.portalSettingsfilterOptions.customer === '' || $scope.portalSettingsfilterOptions.customer == undefined) {
                $scope.portalSettingsfilterOptions.customerid = ''; 
                bootbox.alert('Select Customer');
                return false;
            }
           var qstring = $.param($scope.portalSettingsfilterOptions);
           window.open(base_url+'admin/portalsetup/exportportalsettings?'+qstring);
        };
        
        var portalSettingsPage = function() {
            
            if($scope.portalSettingsfilterOptions.customerid === '' || $scope.portalSettingsfilterOptions.customerid == undefined || $scope.portalSettingsfilterOptions.customer === '' || $scope.portalSettingsfilterOptions.customer == undefined) {
                $scope.portalSettingsfilterOptions.customerid = ''; 
                bootbox.alert('Select Customer');
                return false;
            }
            
            var qstring = $.param($scope.portalSettingsfilterOptions);
            $scope.overlay = true;
            $http.get(base_url+'admin/portalsetup/loadportalsettings?'+qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{
                    $scope.portalSettingsGrid.totalItems = response.total;
                    $scope.portalSettingsGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
         
        var savePortalSettings = function(rulename_id, field, value, input) {
    
            var postData = {
                rulename_id:rulename_id,
                customerid: $scope.portalSettingsfilterOptions.customerid,
                value: value,
                field: field
            }; 

            $.each($scope.portalSettingsGrid.data, function( key, val ) {
                if(parseInt(val.rulename_id) === parseInt(rulename_id)){
                    if(field === 'is_master'){
                        $scope.portalSettingsGrid.data[key].is_master = value;
                    }
                    else if(field === 'is_sitefm'){
                        $scope.portalSettingsGrid.data[key].is_sitefm = value;
                    }
                    else if(field === 'is_sitecontact'){
                        $scope.portalSettingsGrid.data[key].is_sitecontact = value;
                    }
                    else{
                        $scope.portalSettingsGrid.data[key].value = value;
                    }
                     
                    postData.is_master = $scope.portalSettingsGrid.data[key].is_master;
                    postData.is_sitefm = $scope.portalSettingsGrid.data[key].is_sitefm;
                    postData.is_sitecontact = $scope.portalSettingsGrid.data[key].is_sitecontact;
                    postData.rule_value = $scope.portalSettingsGrid.data[key].value;
                }
                        
            });

            input.addClass('custom-input-success');
            var qstring = $.param(postData);
            $scope.overlay = true;
            $http.post(base_url+'admin/portalsetup/updateportalsettings', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                    setTimeout(removecls(input), 2000);
                }
                else {
                    bootbox.alert(data.message);
                }
            });
            
        };

        var removecls = function(input) {
            input.removeClass('custom-input-success');
        };
 

        $(document).on('change', '.chk_rule_value', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'rule_value', value, $(this));
        
        }); 
        
        $(document).on('change', '.chk_is_master', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'is_master', value, $(this));
         
        });
        
        $(document).on('change', '.chk_is_sitefm', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'is_sitefm', value, $(this));
          
        });
        
        $(document).on('change', '.chk_is_sitecontact', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'is_sitecontact', value, $(this));

        });

        $(document).on('change', "#portalsettingsgrid input[type='text'][name='portaltext']",function(){
            var rulename_id = $(this).attr('data-rulename_id');
            var value = $.trim($(this).val());
           
            savePortalSettings(rulename_id, 'rule_value', value, $(this));
        });
        
        
        //portal audit log
        
        // filter
        $scope.filterOptions = {    
            rulename: '',
            fromdate: '',
            todate: '',
            customer: '',
            customerid: '' 
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
        
        $scope.changeAuditLogFilters = function() {
           portalAuditLogPage();
        };
        
        $scope.clearAuditLogFilters = function() {
            $scope.filterOptions.rulename = '';
            $scope.filterOptions.fromdate = '';
            $scope.filterOptions.todate = '';
            $('input[name="fromdate"]').datepicker('setEndDate', null);
            $('input[name="todate"]').datepicker('setStartDate', null);
           portalAuditLogPage();
        };
        
        $scope.refreshAuditLogGrid = function() {
            portalAuditLogPage();
        };
 
        var portalAuditLogPage = function() {
           
           if($scope.filterOptions.customerid === '' || $scope.filterOptions.customerid == undefined || $scope.filterOptions.customer === '' || $scope.filterOptions.customer == undefined) {
                $scope.filterOptions.customerid = ''; 
                bootbox.alert('Select Customer');
                return false;
            }
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'admin/portalsetup/loadportalauditlog?'+ qstring, {
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
            if($scope.filterOptions.customerid === '' || $scope.filterOptions.customerid == undefined || $scope.filterOptions.customer === '' || $scope.filterOptions.customer == undefined) {
                $scope.filterOptions.customerid = ''; 
                bootbox.alert('Select Customer');
                return false;
            }
           var qstring = $.param($scope.filterOptions);
           window.open(base_url+'admin/portalsetup/exportportalauditlog?'+qstring);
        };
 
 
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
       
        $scope.onCustomerSelect = function ($item, $model, $label,type) {
            
            if(type === 'portalsetting'){
                $scope.portalSettingsfilterOptions.customer = $item.companyname;
                $scope.portalSettingsfilterOptions.customerid = $item.customerid;  
                 
                portalSettingsPage();
               
                
            }
            else{
                $scope.filterOptions.customer = $item.companyname;
                $scope.filterOptions.customerid = $item.customerid;  
                portalAuditLogPage();
            }
            
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

