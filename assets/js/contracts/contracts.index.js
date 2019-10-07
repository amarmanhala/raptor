/* global base_url, angular, app, bootbox */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch',    
app.controller('ContractCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
    $scope.filterOptions = {
        filtertext : '',
        contracttypeid   : '',
        managerid  : '' 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    $scope.edit_opt = $('#edit_contract').val()==='1'?'':'disabled="disabled"';
    $scope.contractGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [
            { 
                displayName:'Contract Name',
                cellTooltip: true,
                name: 'name',
                minWidth: 200
            },
            { 
                displayName:'Type',
                cellTooltip: true,
                name: 'typename',
                width: 100
            },
            
            { 
                displayName:'Contract Ref',
                cellTooltip: true,
                name: 'contractref',
                width: 120
            }, 
            {   displayName:'Start Date', 
                cellTooltip: true, 
                name: 'startdate', 
                width: 100 
            },
            { displayName:'End Date', 
                cellTooltip: true, 
                name: 'enddate',
                width: 100
            },
            { 
                displayName:'Sites',
                cellTooltip: true,
                name: 'sitecount',
                width: 60,
                HeaderCellClass: 'text-center',
                cellClass: 'text-center'
            }, 
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'status', 
                width:55,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.id}}" '+$scope.edit_opt+'  data-id="{{row.entity.id}}"  class="chk_status"  ng-checked="row.entity.status == 1" /></div>'
            },
           
            { 
                displayName:'Edit',
                field:'edit',
                width: 40,
                visible :$('#edit_contract').val()==='1'?true:false, 
                enableSorting: false,
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><a title = "edit" href= "'+base_url+'contracts/edit/{{row.entity.id}}"><i class= "fa fa-edit"></i></a></div>'
            }, 
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_contract').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteContract(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
       
      $scope.changeFilter = function() {
           contactPage();
       };
       
       $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext : '',
                contracttypeid   : '',
                managerid  : '' 
            };

            $('.selectpicker').selectpicker('deselectAll');
            contactPage();
        };
    
        $scope.exportToExcel = function(){
            var qstring = $.param($scope.filterOptions);
            window.open(base_url+'contracts/exportcontracts?'+qstring);
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
            $http.get(base_url+'contracts/loadcontracts?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.contractGrid.totalItems = response.total;
                    $scope.contractGrid.data = response.data;  
                }

            });
        };

       contactPage();
       
       $scope.deleteContract = function(entity) {

            bootbox.confirm("Are you sure to delete Contract <b>"+entity.name+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"contracts/deletecontract", { id:entity.id }, function( response ) {
                        if (response.success) {
                            contactPage();
                            $('#mycontractstatus').html('<div class="alert alert-success" >Contract deleted successfully.</div>');
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

 