/* global angular, base_url, bootbox */

"use strict";
var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 

app.controller('SearchCtrl', [
'$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {


    // filter
    $scope.filterOptions = {
        jobid: '',
        custordref: '',
        custordref2: '',
        custordref3: '',
        suburb: '',
        state: [],
        fromleaddate: '',
        toleaddate: '',
        fromduedate: '',
        toduedate: '',
        jobstages: [],
        jobdescription:''
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
 
    $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
    });
    $scope.searchJob = function() {
        $scope.showsearchresult = true;

        paginationOptions.sort = '';
        paginationOptions.field = '';
        getPage();
   };
   
    $scope.showSearchForm = function() {
        $scope.showsearchresult = false;
 
   };
   
    $scope.resetSearch = function(){
        $scope.filterOptions = {
            jobid: '',
            custordref: '',
            custordref2: '',
            custordref3: '',
            suburb: '',
            state: [],
            fromleaddate: '',
            toleaddate: '',
            fromduedate: '',
            toduedate: '',
            jobstage: [],
            jobdescription:''
        };  
    };

    var getPage = function() {

        var params = { 
            page  : paginationOptions.pageNumber,
            size :  paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);

        $('#SearchCtrl .overlay').show();
        $http.get(base_url+'jobs/loadjobsearchresult?'+ qstring ).success(function (data) {
            $('#SearchCtrl .overlay').hide();
            if (data.success === false) {
                bootbox.alert(data.message);
               
            }else{
                $scope.jobs.totalItems = data.total;
                $scope.jobs.data = data.data;  
            }
        });
    }; 
    
    
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
   
     };
}
]);

 app.filter('trusted', function ($sce) {
        return function (value) {
          return $sce.trustAsHtml(value);
        };
    });
 $( document ).ready(function() {
	   
    $("#fromleaddate").on('changeDate', function(e) {
        $('#toleaddate').datepicker('setStartDate', e.date);
        $('#toleaddate').datepicker('setDate',  e.date); 
    });
    
    $("#toleaddate").on('changeDate', function(e) {
        $('#fromleaddate').datepicker('setEndDate', e.date);
        if($("#fromleaddate").val() === ''){
            $('#fromleaddate').datepicker('setDate', e.date); 
        }  
    });
     
    $("#fromduedate").on('changeDate', function(e) {
        $('#toduedate').datepicker('setStartDate', e.date);
        $('#toduedate').datepicker('setDate',  e.date); 
    });
    
    $("#toduedate").on('changeDate', function(e) {
        $('#fromduedate').datepicker('setEndDate', e.date);
        if($("#fromduedate").val() === ''){
            $('#fromduedate').datepicker('setDate', e.date); 
        }  
    });
   
});