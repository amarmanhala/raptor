/* global base_url, angular, app */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch',    
app.controller('SuppliersCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
    $scope.filterOptions = {
        filtertext : '',
        status   : '',
        state  : '',
        tradeids : []
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    $scope.edit_opt = $('#edit_supplier').val()==='1'?'':'disabled="disabled"';
    $scope.contactGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [
            
            { 
                displayName:'Action',
                field:'action',
                width: 60,
                visible :$('#edit_supplier').val()==='1'?true:false, 
                enableSorting: false,
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Action</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><a title = "edit" href= "'+base_url+'suppliers/edit/{{row.entity.customerid}}"><i class= "fa fa-edit"></i></a></div>'
            }, 
            { 
                displayName:'Login', 
                field:'primarycontactid', 
                width: 55, 
                visible :$('#allow_etp_login').val()==='1'?true:false, 
                enableSorting: false, 
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Login</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.primarycontactid ==\'\'">&nbsp;</div>'+
                              '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.primarycontactid != \'\'">'+
                              '<a title = "Falcon Login" class= "btn btn-info btn-xs" href= "'+base_url+'../falcon/auth/falogin/{{row.entity.primarycontactid}}" target="_blank"><i class= "fa fa-unlock-alt"></i></a>&nbsp;'+
                               '</div>'

            },
            { 
                displayName:'Company Name',
                cellTooltip: true,
                name: 'companyname',
                width: 200
            },
            { 
                displayName:'Type',
                cellTooltip: true,
                name: 'typename',
                width: 100
            },
            
            { 
                displayName:'Primary Trade',
                cellTooltip: true,
                name: 'se_trade_name',
                width: 140
            }, 
            {   displayName:'Phone', 
                cellTooltip: true, 
                name: 'phone', 
                width: 100 
            },
            { displayName:'Email', 
                cellTooltip: true, 
                name: 'email',
                width: 200
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'shipsuburb',
                width: 90
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'shipstate',
                enableFiltering: false,
                width: 65
            },
            { 
                displayName:'Primary Contact',
                cellTooltip: true,
                name: 'primarycontact',
                enableFiltering: false,
                width: 120
            },
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'isactive', 
                width: 70,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.supplierid}}" '+$scope.edit_opt+'  data-id="{{row.entity.supplierid}}"  class="chk_isactive"  ng-checked="row.entity.isactive == 1" /></div>'
            },
            {   displayName:'Portal Access', 
                cellTooltip: true,
                enableSorting: false,
                name: 'hasetpaccess', 
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.supplierid}}" '+$scope.edit_opt+'  data-id="{{row.entity.supplierid}}"  class="chk_hasetpaccess"  ng-checked="row.entity.hasetpaccess == 1" /></div>'
            },
            { 
                displayName:'Balance',
                cellTooltip: true,
                name: 'currentbalance',
                enableFiltering: false,
                width: 130
            },   
            
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_supplier').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteSupplier(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                contactPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               contactPage();
             });
 	
         }
       };
       
      $scope.changeContactFilter = function() {
           contactPage();
       };
       
       $scope.clearContactFilters = function() {
            $scope.filterOptions = {
                filtertext : '',
                status   : '',
                state  : '',
                tradeids : []
            };

            $('.selectpicker').selectpicker('deselectAll');
            contactPage();
        };
    
        $scope.exportToExcel = function(){
            var qstring = $.param($scope.filterOptions);
            window.open(base_url+'suppliers/exportsuppliers?'+qstring);
        };
       
        var contactPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'suppliers/loadsuppliers?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.contactGrid.totalItems = response.total;
                    $scope.contactGrid.data = response.data;  
                }

            });
        };

       contactPage();
       
       $scope.deleteSupplier = function(entity) {

            bootbox.confirm("Are you sure to delete Supplier <b>"+entity.compantname+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"suppliers/deletesupplier", { id:entity.supplierid }, function( response ) {
                        if (response.success) {
                            contactPage();
                            $('#mysupplierstatus').html('<div class="alert alert-success" >Supplier deleted successfully.</div>');
                            clearMsgPanel();
                            
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

 