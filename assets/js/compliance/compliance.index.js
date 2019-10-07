
/* global base_url, angular, bootbox, headercategory */

"use strict";
if($("#ComplianceCtrl").length) {
    
//    var app = angular.module('app', ['ngGrid']);
//
//app.controller('ComplianceCtrl', ['$scope', '$http', function ($scope, $http) {
 
    
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('ComplianceCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
        $scope.filterOptions = {
            state: '',
            trade: '',
            filterText: ''
        };

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };
  
       $scope.gridOptions = {
         paginationPageSizes: [10, 25, 50, 100, 200],
         paginationPageSize: 25,
         headerTemplate: base_img_url+'ui-grid-template/category_header.html',
         category: headercategory,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         enableFiltering: false,
         columnDefs: [
            { 
                displayName:'Name',
                cellTooltip: true,
                name: 'firstname',
                width: 140,
                category:'Contractor Details'
            },
            { 
                displayName:'Company',
                cellTooltip: true,
                name: 'company',
                enableSorting: true,
                width: 200,
                category:'Contractor Details'
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'suburb',
                enableSorting: true,
                width: 100,
                category:'Contractor Details'
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'state',
                enableSorting: true,
                width: 100,
                category:'Contractor Details'
            },
            
            { 
                displayName:'Trade',
                cellTooltip: true,
                name: 'trade',
                enableSorting: true,
                width: 140,
                category:'Contractor Details'
            },
            { 
                displayName:'Type',
                cellTooltip: true,
                name: 'type',
                enableSorting: true,
                width: 100,
                category:'Contractor Details'
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
                getPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               getPage();
             });
         }
       };
       
        $.each(captionData, function( key, val ) {
            if(val.has_number > 0){
                var cp = '';
                if(val.has_startdate > 0 || val.has_expiry > 0){
                    cp = 'Number';
                }
                $scope.gridOptions.columnDefs.push({ 
                    displayName:cp,
                    cellTooltip: true,
                    name: val.name+'_number',
                    enableSorting: false,
                    minWidth: 130,
                    category:val.caption,
                    cellTemplate: '<div class="ui-grid-cell-contents" ng-if="!row.entity.'+val.name+'_has_number">&nbsp;</div>'+
                              '<div class="ui-grid-cell-contents" ng-if="row.entity.'+val.name+'_has_number">'+
                                    '<div  ng-if="row.entity.'+val.name+'_has_doclink">'+
                                        '<a ng-if="!row.entity.'+val.name+'_has_expiry"  title= "View Document"  ng-click="grid.appScope.validateDocument(row.entity.'+val.name+'_documentid)" href= "javascript:void(0);">{{row.entity.'+val.name+'_number}}</a>&nbsp;'+
                                        '<span  ng-if="row.entity.'+val.name+'_has_expiry"  >{{row.entity.'+val.name+'_number}}</spasn>&nbsp;'+
                                    '</div>'+
                                    '<div  ng-if="!row.entity.'+val.name+'_has_doclink">'+
                                         '{{row.entity.'+val.name+'_number}}'+
                                    '</div>'+
                              '</div>'
                });
            }
            if(val.has_startdate > 0){
                $scope.gridOptions.columnDefs.push({ 
                    displayName:'Start Date',
                    cellTooltip: true,
                    name: val.name+'_sdate',
                    enableSorting: false,
                    width: 110,
                    category:val.caption,
                    cellTemplate: '<div class="ui-grid-cell-contents" ng-if="!row.entity.'+val.name+'_has_startdate">&nbsp;</div>'+
                              '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.'+val.name+'_has_startdate">'+
                                 '<div  ng-if="row.entity.'+val.name+'_has_doclink">'+
                                        '<a ng-if="!row.entity.'+val.name+'_has_expiry && !row.entity.'+val.name+'_has_number"  title= "View Document" ng-click="grid.appScope.validateDocument(row.entity.'+val.name+'_documentid)" href= "javascript:void(0);">{{row.entity.'+val.name+'_sdate}}</a>&nbsp;'+
                                        '<span ng-if="row.entity.'+val.name+'_has_expiry || row.entity.'+val.name+'_has_number"  >{{row.entity.'+val.name+'_sdate}}</spasn>&nbsp;'+
                                    '</div>'+
                                    '<div  ng-if="!row.entity.'+val.name+'_has_doclink">'+
                                         '{{row.entity.'+val.name+'_sdate}}'+
                                    '</div>'+
                              '</div>'
                });
            }
            if(val.has_expiry > 0){
                $scope.gridOptions.columnDefs.push({ 
                    displayName:'Expiry Date',
                    cellTooltip: true,
                    name: val.name+'_edate',
                    enableSorting: false,
                    width: 110,
                    category:val.caption,
                    cellTemplate: '<div class="ui-grid-cell-contents" ng-if="!row.entity.'+val.name+'_has_expiry">&nbsp;</div>'+
                                '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.'+val.name+'_has_expiry">'+
                                    '<div  ng-if="row.entity.'+val.name+'_has_doclink">'+
                                        '<a ng-if="row.entity.'+val.name+'_edate!=\'\'"  title= "View Document"  ng-click="grid.appScope.validateDocument(row.entity.'+val.name+'_documentid)" href= "javascript:void(0);">{{row.entity.'+val.name+'_edate}}</a>&nbsp;'+
                                        '<span  ng-if="row.entity.'+val.name+'_edate==\'\'"  >{{row.entity.'+val.name+'_edate}}</spasn>&nbsp;'+
                                    '</div>'+
                                    '<div  ng-if="!row.entity.'+val.name+'_has_doclink">'+
                                         '{{row.entity.'+val.name+'_edate}}'+
                                    '</div>'+
                                '</div>'
                });
            }
        });
       
       $scope.gridOptions.multiSelect = false;
       $scope.gridOptions.modifierKeysToMultiSelect = false;
       $scope.gridOptions.noUnselect = true;
       
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
                categoryid: '',
                filterText: ''
            }; 
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        $scope.height = 20;
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
         
             
            $('.ui-category-heading').each(function() {
               if($scope.height < parseInt($(this).height())){
                   $scope.height = parseInt($(this).height());
                   console.log($scope.height);
                   $('.ui-category-heading').css('height',($scope.height+12)+'px');
               }
            });
            
        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'compliance/exportexcel?'+$.param($scope.filterOptions));

        };
        
        $scope.validateDocument = function(documentid) {
           $('#ComplianceCtrl .overlay').show();
            $.get( base_url+"documents/checkdocument", { documentid:documentid }, function( response ) {
                $('#ComplianceCtrl .overlay').hide();
                if (response.success) {
                     window.open(base_url+'documents/viewdocument/'+documentid);
                }
                else {
                    bootbox.alert(response.message);
                }
            }); 
        };
        
       var getPage = function() {
            if(typeof $scope.filterOptions.filterText === 'undefined') {
                $scope.filterOptions.filterText = '';
            }
                
             
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#ComplianceCtrl .overlay').show();
             $http.get(base_url+'compliance/loadcompliances?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.gridOptions.totalItems = data.total;
                        $scope.gridOptions.data = data.data;  
                    }
                 
                   $('#ComplianceCtrl .overlay').hide();
             });
       };

       getPage();
     }
     ]);
}
 