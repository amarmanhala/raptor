/* global angular, base_url, bootbox */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 

app.controller('GridCtrl', [
'$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {


    // filter
    $scope.filterOptions = {
        filterText: '',
        jobstage: '',
        quotestatusid: ''
    };

  var paginationOptions = {
    pageNumber: 1,
    pageSize: 25,
    sort: '',
    field: ''
  };

  $scope.jobs = {
    paginationPageSizes: [10, 25, 50, 100],
    paginationPageSize: 25,
    useExternalPagination: true,
    useExternalSorting: true,
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
                        width: 350,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    } 

    ],
    onRegisterApi: function(gridApi) {
        $scope.gridApi = gridApi;

        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
          paginationOptions.pageNumber = newPage;
          paginationOptions.pageSize = pageSize;
          getPage('jobs');
        });
        
        gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
            if (sortColumns.length === 0) {
              paginationOptions.sort = '';
              paginationOptions.field = '';
            } else {
              paginationOptions.sort = sortColumns[0].sort.direction;
              paginationOptions.field = sortColumns[0].field;
            }
            getPage('jobs');
        });
    }
  };

  $scope.quotes = {
    paginationPageSizes: [10, 25, 50, 100],
    paginationPageSize: 25,
    useExternalPagination: true,
    useExternalSorting: true,
    enableColumnMenus: false,
    columnDefs: [
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        width:100,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span   data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
                    { 
                        displayName:$('#custordref1_label').val(),
                        cellTooltip: true,
                        name: 'custordref',
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
                        displayName:'Suburb',
                        cellTooltip: true,
                        name: 'sitesuburb', 
                        width: 100 
                    },
                    { 
                        displayName:'Site FM',
                        cellTooltip: true,
                        name: 'sitefm', 
                        width: 150 
                    },
                    { 
                        displayName:'Job Description',
                        cellTooltip: true,
                        name: 'shortdescription',
                        enableSorting: false,
                        width: 350,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
                    { 
                        displayName:'Job Stage',
                        cellTooltip: true,
                        name: 'portaldesc',
                        enableSorting: true,
                        width: 150 
                    },
                    { 
                        displayName:'Due Date',
                        cellTooltip: true,
                        name: 'duedate',
                        enableSorting: true,
                        width: 100 
                    }

    ],
    onRegisterApi: function(gridApi) {
        $scope.gridApi = gridApi;

        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
          paginationOptions.pageNumber = newPage;
          paginationOptions.pageSize = pageSize;
          getPage('quotes');
        });
        
        gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
            if (sortColumns.length === 0) {
              paginationOptions.sort = '';
              paginationOptions.field = '';
            } else {
              paginationOptions.sort = sortColumns[0].sort.direction;
              paginationOptions.field = sortColumns[0].field;
            }
            getPage('quotes');
        });
    }
  };
  
  
   $scope.invoices = {
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
                displayName:'Invoice No.',
                cellTooltip: true,
                enableSorting: true,
                name: 'invoiceno',
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Invoice PDF"><a href="'+base_url+'statements/invoicepdf/{{row.entity.invoiceno}}"  target="_blank" >{{row.entity.invoiceno}}</a></div>'
            },
            { 
                displayName:'Invoice Date',
                cellTooltip: true,
                name: 'invoicedate',
                enableSorting: true,
                width: 120
            },
            
            { 
                displayName:$('#custordref1_label').val(),
                cellTooltip: true,
                name: 'custordref',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:$('#custordref2_label').val(),
                cellTooltip: true,
                name: 'custordref2',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:$('#custordref3_label').val(),
                cellTooltip: true,
                name: 'custordref3',
                width: 100,
                footerCellTemplate: '<div class="ui-grid-cell-contents text-right"><span class="ng-binding">Total </span></div>',
            },
             
            { 
                displayName:'Amount ($)',
                cellTooltip: true,
                name: 'Invoiced',
                enableSorting: true,
                width: 120,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalInvoiced = 0;
                    $scope.invoices.data.forEach(function(rowEntity) {
                        totalInvoiced =totalInvoiced +  intVal(rowEntity.Invoiced);
                    }); 
                    return '$ '+ parseFloat(totalInvoiced).toFixed(2);
                } 
               
            },
            { 
                displayName:'GL Code',
                cellTooltip: true,
                name: 'glCode',
                enableSorting: true,
                width: 100
            },
            { 
                displayName:'Site FM',
                cellTooltip: true,
                name: 'sitefm',
                enableSorting: true,
                width: 150 
            },
             
            { 
                displayName:'Suburb ',
                cellTooltip: true,
                name: 'sitesuburb',
                enableSorting: true,
                width: 100
            },
            { 
                displayName:$('#sitereflabel1').val(),
                cellTooltip: true,
                name: 'siteref',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:'Job ID',
                cellTooltip: true,
                name: 'jobid',
                enableSorting: true,
                width: 80,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Open Job Detail"><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}"  target="_blank" >{{row.entity.jobid}}</a></div>'
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
                getPage('invoices');
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               getPage('invoices');
             });
         }
       };
  
  $scope.refreshJobs = function(index, jobstage) {
    jQuery('#gridquotedetail').css('display','none');  
    jQuery('#gridjobdetail').css('display','block');
    jQuery('#gridinvoicedetail').css('display','none');
    
    jQuery("#jobgridbox tr").removeClass('bg-green');
    jQuery("#quotegridbox tr").removeClass('bg-green');
    jQuery("#invoicegridbox tr").removeClass('bg-green');
    jQuery("#jobgridbox tr").removeClass('text-white');
    jQuery("#quotegridbox tr").removeClass('text-white');
    jQuery("#invoicegridbox tr").removeClass('text-white');
    jQuery("#jobgridbox #j_"+index).addClass('bg-green');
    jQuery("#jobgridbox #j_"+index).addClass('text-white');
    jQuery("#gridjobdetail .stagename").html(jobstage);
    
    $scope.filterOptions.jobstage = jobstage;
    paginationOptions.sort = '';
    paginationOptions.field = '';
    getPage('jobs');
 };

 $scope.refreshQuotes = function(index, quotestage) {
     jQuery('#gridjobdetail').css('display','none'); 
     jQuery('#gridquotedetail').css('display','block');
     jQuery('#gridinvoicedetail').css('display','none');
     
     jQuery("#jobgridbox tr").removeClass('bg-green');
    jQuery("#quotegridbox tr").removeClass('bg-green');
    jQuery("#invoicegridbox tr").removeClass('bg-green');
    jQuery("#jobgridbox tr").removeClass('text-white');
    jQuery("#quotegridbox tr").removeClass('text-white');
    jQuery("#invoicegridbox tr").removeClass('text-white');
    jQuery("#quotegridbox #q_"+index).addClass('bg-green');
    jQuery("#quotegridbox #q_"+index).addClass('text-white');
     jQuery("#gridquotedetail .stagename").html(quotestage);
     
     $scope.filterOptions.quotestage = quotestage;
     paginationOptions.sort = '';
     paginationOptions.field = '';
     getPage('quotes');
 };
 $scope.refreshInvoices = function(index, invoicestage) {
    jQuery('#gridjobdetail').css('display','none');  
    jQuery('#gridquotedetail').css('display','none');
    jQuery('#gridinvoicedetail').css('display','block');
    
    jQuery("#jobgridbox tr").removeClass('bg-green');
    jQuery("#quotegridbox tr").removeClass('bg-green');
    jQuery("#invoicegridbox tr").removeClass('bg-green');
    jQuery("#jobgridbox tr").removeClass('text-white');
    jQuery("#quotegridbox tr").removeClass('text-white');
    jQuery("#invoicegridbox tr").removeClass('text-white');
    
    jQuery("#invoicegridbox #i_"+index).addClass('bg-green');
    jQuery("#invoicegridbox #i_"+index).addClass('text-white');
     jQuery("#gridinvoicedetail .stagename").html(invoicestage);
     
     paginationOptions.sort = '';
     paginationOptions.field = '';
     $scope.filterOptions.invoicestage = invoicestage;
     getPage('invoices');
 };
 

    var getPage = function(type) {

         
        var params = { 
            page  : paginationOptions.pageNumber,
            size :  paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        var qstring = $.param(params);
        
        var url;
        if(type === 'jobs') {
          
            qstring = qstring+ '&jobstage='+$scope.filterOptions.jobstage;
            $('#gridjobdetail .overlay').show();
            url = base_url+'jobs/loadjobsearchresult?'+ qstring;
            $http.get(url)
            .success(function (data) {
                 $('#gridjobdetail .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message);
                    return false;
                }else{ 
                    $scope.jobs.totalItems = data.total;
                    $scope.jobs.data = data.data;  
                } 
                
          });
        }

        if(type === 'quotes') {
            var status = $scope.filterOptions.quotestage;
           $('#gridquotedetail .overlay').show();
           if(status === 'In Progress'){
                url = base_url+'quotes/loadinprogressquotes?'+ qstring ;
           }
           else if(status === 'Pending Submission'){
                url = base_url+'quotes/loadwaitingdcfmreviewquotes?'+ qstring ;
           }
           else{
                url = base_url+'quotes/loadpendingapprovalquotes?'+ qstring ;
           }
           
          $http.get(url)
            .success(function (data) {
                $('#gridquotedetail .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message);
                    return false;
                }else{ 
                    $scope.quotes.totalItems = data.total;
                    $scope.quotes.data = data.data;  
                } 
               
          });
        }
        
        if(type === 'invoices') {
            var status = $scope.filterOptions.invoicestage;
           $('#gridinvoicedetail .overlay').show();
           if(status === 'For FM Approval' || status ==='For Approval'){
                url = base_url+'statements/loadfmapprovalinvoices?'+ qstring ;
           }
           else if(status === 'For Final Approval'){
                url = base_url+'statements/loadfinalapprovalinvoices?'+ qstring ;
           }
           else if(status === 'Open Invoices'){
                url = base_url+'statements/loadopeninvoices?'+ qstring ;
           }
           
           else if(status === 'Invoice History'){
                url = base_url+'statements/loadhistoryinvoices?'+ qstring ;
           }
           else {
                url = base_url+'statements/loadfinalisedinvoices?'+ qstring ;
           }
           
          $http.get(url)
            .success(function (data) {
                $('#gridinvoicedetail .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message);
                    return false;
                }else{ 
                    $scope.invoices.totalItems = data.total;
                    $scope.invoices.data = data.data;  
                } 
               
          });
        }
    }; 
  
}
]);

 app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});