/* global base_url, angular, app, bootbox */

"use strict";
 var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('CustomerSummaryCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {
  
    // filter
    $scope.filterOptions = {
        customerid : '',
        company : '' 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    
    $scope.contactGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [
            { 
                displayName:'Select',
                name: 'customerid',
                enableSorting: false,
                width:80,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="contactscheckbox[]" value="{{row.entity.customerid}}" contact-title="{{row.entity.companyname}}"   /></div>',
                pinnedLeft:true
                //headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  /></div>'
            }, 
            { 
                displayName:'Customer',
                cellTooltip: true,
                name: 'companyname',
                width: 200
            },
            { 
                displayName:'Modules',
                cellTooltip: true,
                name: 'modulecount',
                width: 95,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right'
            },
            { 
                displayName:'Active Login',
                cellTooltip: true,
                name: 'activecontacts',
                enableFiltering: false,
                width: 120,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right'
            },
            { 
                displayName:'Contacts',
                cellTooltip: true,
                name: 'contacts',
                enableFiltering: false,
                width: 95,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right'
            },
            { 
                displayName:'Last Login',
                cellTooltip: true,
                name: 'lastcplogin',
                enableFiltering: false,
                width: 140
            },
           {   displayName:'Status', 
                cellTooltip: true, 
                name: 'status', 
                width: 90
 
            },
            { displayName:'Status Notes', 
                cellTooltip: true, 
                name: 'cpstatusnote',
                width: 180
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
       
       $scope.changeCustomerText = function() {
            var text = $scope.filterOptions.company;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
                $scope.filterOptions.customerid = ''; 
                contactPage();
            } 
        };
       
        $scope.changeContactFilter = function() {
            contactPage();
        };
       
       $scope.clearContactFilters = function() {
            $scope.filterOptions = { 
                customerid : '',
                company : '' 
            };

         
            contactPage();
        };
         
        
    
        
       
        var contactPage = function() {
            var text = $scope.filterOptions.company;

            if(text === undefined || text === null) { 
                $scope.filterOptions.customerid = ''; 
            } 

            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
            $scope.filterOptions.updatestatuscontactid = '';         
            $scope.overlay = true;
            $http.get(base_url+'admin/customersummary/loadcustomersummary?'+ qstring, {
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
            contactPage();
         };
       
       
        $scope.addCustomerModule = function() {
            var text = $scope.filterOptions.company;
         
            if(text === undefined || text === null) { 
                bootbox.alert("Please Select customer.");
                return false;
            } 
            if(parseInt($scope.contactGrid.totalItems)>0){
                bootbox.alert($scope.filterOptions.company + " is already assigned to the client portal.");
                return false;
            }
            $scope.overlay = true;
            $.post( base_url+"admin/customersummary/assigncustomercp", {'customerid':$scope.filterOptions.customerid }, function( data ) {
                if(data.success){ 
                    bootbox.alert('<b>'+$scope.filterOptions.company+'</b> added successfully. You will also need to activate this customer, and set up contacts and security.');
                    $scope.filterOptions.company = '';
                    $scope.filterOptions.customerid = '';  
                    contactPage(); 
                }else{
                    $scope.overlay = false;
                    bootbox.alert(data.message);
                }

            },'json');
             
        };
       
        $(document).on('click', '#btndeactivated', function() {
         
            var length = $('#contactGrid input[name="contactscheckbox[]"]').length;
            if(length === 0) {
                bootbox.alert("No customer available to deactivate.");
                return false;
            }

            var $chkbox_checked    = $('#contactGrid input[name="contactscheckbox[]"]:checked');
            if($chkbox_checked.length !== 1){
                bootbox.alert('Please select one customer.');
                return false;
            }
            
            var customerid = $('#contactGrid input[name="contactscheckbox[]"]:checked').val();
            var companyname = $('#contactGrid input[name="contactscheckbox[]"]:checked').attr('contact-title');
            bootbox.confirm('Deactivate access to the client portal for <b>'+companyname+'</b>?', function(result) {
                if(result) {
                    $scope.overlay = true;
                    $.post( base_url+"admin/customersummary/updatecustomercpstatus", {status: 0, 'customerid':customerid }, function( data ) {
                        if(data.success){ 
                            bootbox.alert(data.message);
                            contactPage(); 
                        }else{
                           $scope.overlay = false;
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });
     
        $(document).on('click', '#btnactivated', function() {
         
            var length = $('#contactGrid input[name="contactscheckbox[]"]').length;
            if(length === 0) {
                bootbox.alert("No customer available to deactivate.");
                return false;
            }

            var $chkbox_checked    = $('#contactGrid input[name="contactscheckbox[]"]:checked');
            if($chkbox_checked.length !== 1){
                bootbox.alert('Please select one customer.');
                return false;
            }
            
            var customerid = $('#contactGrid input[name="contactscheckbox[]"]:checked').val();
            var companyname = $('#contactGrid input[name="contactscheckbox[]"]:checked').attr('contact-title');
            bootbox.confirm('Activate access to the client portal for <b>'+companyname+'</b>?', function(result) {
                if(result) {
                    $scope.overlay = true;
                    $.post( base_url+"admin/customersummary/updatecustomercpstatus", {status: 1, 'customerid':customerid }, function( data ) {
                        if(data.success){ 
                            bootbox.alert(data.message);
                            contactPage(); 
                        }else{
                           $scope.overlay = false;
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });
         
    }
    
    
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
 