
/* global base_url, angular, bootbox */

"use strict";
if($("#AssetDocumentCtrl").length) {
    var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('AssetDocumentCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {
 
         // filter
        $scope.filterOptions = {
            documenttype:[],
            site:[],
            suburb:'',
            category:[],
            monthyear:[],
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
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         showColumnFooter: true,
         enableColumnMenus: false,
         enableFiltering: false,
         
         columnDefs: [
              
            { 
                displayName:'Asset ID',
                cellTooltip: true,
                enableSorting: true,
                name: 'assetid',
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents" title="View asset"><a href="'+base_url+'asset/edit/{{row.entity.assetid}}"  target="_blank" >{{row.entity.assetid}}</a></div>'
            },
            { 
                displayName:'Asset Category',
                cellTooltip: true,
                enableSorting: true,
                name: 'category_name',
                width: 150
            },
            { 
                displayName:'Client Asset ID',
                cellTooltip: true,
                enableSorting: true,
                name: 'client_asset_id',
                width: 110
            },
            { 
                displayName:'Service Tag',
                cellTooltip: true,
                enableSorting: true,
                name: 'service_tag',
                width: 110
            },
            /*{ 
                displayName:'Job Description',
                cellTooltip: true,
                name: 'jobdescription',
                enableSorting: false,
                width: 120, 
                cellTemplate: '<div class="ui-grid-cell-contents text-center"  title="{{row.entity.jobdescription}}" ><span class="fa fa-file-text-o"></span></div>' 
            },
            { 
                displayName:'Customer Ref',
                cellTooltip: true,
                name: 'custordref',
                enableSorting: true,
                width: 130
            },*/
            { 
                displayName:'Date Added',
                cellTooltip: true,
                name: 'dateadded',
                enableSorting: true,
                width: 130 
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'sitesuburb',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:'Document Type',
                cellTooltip: true,
                name: 'doctype',
                enableSorting: true,
                width: 150
            },
            { 
                displayName:'Document Name',
                cellTooltip: true,
                name: 'docname',
                enableSorting: true,
                width: 200,
                cellTemplate: '<div class="ui-grid-cell-contents"><a href="javascript:void(0);"  ng-click="grid.appScope.validateDocument(row.entity.documentid)" target="_blank">{{row.entity.docname}}</a></div>'
            },
            { 
                displayName:'Description',
                cellTooltip: true,
                name: 'documentdesc',
                enableSorting: true,
                width: 200
            }
            /*{ 
                displayName:'State',
                cellTooltip: true,
                name: 'sitestate',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:'Site FM',
                cellTooltip: true,
                name: 'sitefm',
                width: 150
            },
            { 
                displayName:'File Size',
                cellTooltip: true,
                name: 'filesize',
                enableSorting: true,
                width: 100
            } */
        ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = null;
                  paginationOptions.field = null;
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
       
       $scope.gridOptions.multiSelect = false;
       $scope.gridOptions.modifierKeysToMultiSelect = false;
       $scope.gridOptions.noUnselect = true;
       
       $scope.changeText = function() {
            var text = $scope.filterOptions.filterText;
            
            if(text.length === 0 || text.length>1) { 
                getPage();
            } 
        };
        $scope.changeSuburbText = function() {
            var text = $scope.filterOptions.suburb;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
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
                documenttype:[],
                site:[],
                suburb:'',
                category:[],
                monthyear:[],
                filterText: ''
            }; 
            
            
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
        $scope.validateDocument = function(documentid) {
           $('#AssetDocumentCtrl .overlay').show();
            $.get( base_url+"documents/checkdocument", { documentid:documentid }, function( response ) {
                $('#AssetDocumentCtrl .overlay').hide();
                if (response.success) {
                     window.open(base_url+'documents/viewdocument/'+documentid);
                }
                else {
                    bootbox.alert(response.message);
                }
            }); 
        };
        
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'documents/exportassetdocuments?'+$.param($scope.filterOptions));

        };
        
       var getPage = function() {
            if(typeof $scope.filterOptions.filterText === 'undefined') {
                $scope.filterOptions.filterText = '';
            }
              
             if(paginationOptions.sort === null) {
                 paginationOptions.sort = '';
             }
             if(paginationOptions.field === null) {
                 paginationOptions.field = '';
             }
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $scope.overlay = true; 
             $http.get(base_url+'documents/loadassetdocuments?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.gridOptions.totalItems = data.total;
                        $scope.gridOptions.data = data.data;  
                    }
                  $scope.overlay = false; 
             });
       };

       getPage();
       
        var deferred;  
     
        //Any function returning a promise object can be used to load values asynchronously
        $scope.getCityPostCode = function(val, type) {

            deferred = $q.defer(); 
            $http.get(base_url+'ajax/loadpostcode', {
                params: {
                            search: val,
                            type: type 
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
       
        $scope.onCitySelect = function ($item, $model, $label, mode) {
            $scope.filterOptions.suburb = $item.suburb;
            $scope.filterOptions.state = [$item.state]; 
            getPage();
         };
     }
     ]);
}
 