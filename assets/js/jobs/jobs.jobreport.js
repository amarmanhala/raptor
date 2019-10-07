/* global app, angular, base_url, readDocURL, readImageURL, bootbox */

$( document ).ready(function() {
    $("#editableReports").click(function () {
        var url = $(this).data('url');
        window.open(url,'_blank');
    });
});

"use strict";
if($("#JobReportCtrl").length) {

    app.controller('JobReportCtrl', [
    '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

    // filter
    $scope.filterOptions = {
        filtertext : ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };

      $scope.jobReportGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnResizing: true,
        enableColumnMenus: false,
        columnDefs: [   
            { 
                displayName:'Document Type',
                name: 'doctype',
                cellTooltip: true,
                width:180
            },
            { 
                displayName:'Document Name',
                name: 'docname',
                cellTooltip: true
            },
            { 
                displayName:'Description',
                name: 'documentdesc',
                cellTooltip: true
            },
            { 
                displayName:'Date Added',
                name: 'dateadded',
                cellTooltip: true,
                width:120,
                sort: {
                    direction: uiGridConstants.DESC
                }
            },
            { 
                displayName:'Edit',
                name: 'xrefid',
                cellTooltip: true,
                visible :$('#edit_report').val()==='1'?true:false, 
                enableSorting: false,
                width:60,
                headerCellClass : 'text-center', 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents" ng-if="!row.entity.edit_report || row.entity.xrefid == \'\' || row.entity.reportid == \'\'">&nbsp;</div>'+
                              '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.edit_report==1 && row.entity.xrefid != \'\' && row.entity.reportid != \'\'">'+
                              '<a title = "edit report" target="_blank"  href= "'+base_url+'EditableReport/index/{{row.entity.xrefid}}/{{row.entity.reportid}}/0/1"><i class= "fa fa-edit"></i></a>&nbsp;'+
                              '</div>'
            },
            { 
                displayName:'PDF',
                name: 'documentid',
                cellTooltip: true,
                enableSorting: false,
                width:60,
                cellTemplate: '<div class="ui-grid-cell-contents"><a href="'+base_url+'documents/download/{{row.entity.documentid}}" title = "download"><img src="'+base_url+'assets/img/pdf_icon.png" /></a></div>'
            }

        ],
        onRegisterApi: function(gridApi) {
            $scope.gridApi = gridApi;

            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
              paginationOptions.pageNumber = newPage;
              paginationOptions.pageSize = pageSize;
              jobReportPage();
            });
            
            gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = '';
                  paginationOptions.field = '';
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                jobReportPage();
            });
        }
      };
 
        $scope.refreshJobReport = function() {
           jobReportPage();
        };

       var jobReportPage = function() {

            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                jobid : $("#jobdetail_form #jobid").val()
            }; 


            var qstring = $.param(params);

            $scope.overlay = true;
            $http.get(base_url+'documents/loadjobreports?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.jobReportGrid.totalItems = response.total;
                    $scope.jobReportGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
        }; 

        jobReportPage();
    }
    ]);
}



