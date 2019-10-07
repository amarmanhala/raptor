/* global bootbox, base_url, angular */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']);
    app.controller('reportsCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

    // filter
    $scope.filterOptions = {    
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.reportGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [ 
            {   displayName:'Name', 
                cellTooltip: true, 
                name: 'name'
            },
            {   displayName:'Description', 
                cellTooltip: true, 
                name: 'description'
            },
            { 
                displayName:'Select',
                name: 'select',
                enableSorting: false,
                width:120,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Select</div>',
                //headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  />&nbsp;Select All</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="reportcheckbox[]" value="{{row.entity.id}}" data-reportroute="{{row.entity.reportroute}}" data-familyname="{{row.entity.familyname}}" /></div>'
                
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
                reportPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               reportPage();
             });
 	
         }
       };
        
        $scope.changeFilters = function() {
           reportPage();
        };
        
        $scope.refreshReportGrid = function() {
            reportPage();
        };
        
        $scope.generateReport = function() {
            
            var fromdate = $('#fromdate').datepicker('getDate');
            var todate = $('#todate').datepicker('getDate');
            var startyear = parseInt(fromdate.getFullYear());
            var endyear = parseInt(todate.getFullYear());
            var yeardiff = endyear-startyear;

            if(yeardiff>2) {
                bootbox.alert('Date Range limit 2 years.');
                return false;
            }

            var length = $('#reportGrid input[name="reportcheckbox[]"]').length;
            var chkbox_checked = $('#reportGrid input[name="reportcheckbox[]"]:checked');
            if(chkbox_checked.length === 0){
                bootbox.alert('Please select report.');
                return false;
            }
            
            if(chkbox_checked.length > 1){
                bootbox.alert('Please select one report.');
                return false;
            }
            
            var report='';
            var reportroute = '';
            $(chkbox_checked).each(function() {
                if($(this).attr('data-reportroute') != '' || $(this).attr('data-familyname') != '') {
                    report = $(this).attr('data-familyname').toLowerCase()+'/'+$(this).attr('data-reportroute').toLowerCase();
                    reportroute = $(this).attr('data-reportroute').toLowerCase();
                }
            });
            
            if(report == '') {
                bootbox.alert('Report route not defined.');
                return false;
            }
            
            var params = {
                fromdate: $("#fromdate").val(),
                todate: $("#todate").val(),
                reportroute: reportroute
            };
            
            $.ajax({
                url : base_url+"reports/processreportdata",
                data: params,
                method: 'get',
                beforeSend: function(){
                    bootbox.dialog({
                        closeButton : false,
                        message: "<span class='bigger-110'>Generating Report..</span>",
                        title: "Processing.."
                    });
                },
                complete: function(response){

                },
                success: function(response) {
                    bootbox.hideAll();
                    if (response.success) {
                        var qstring = $.param(params)+'&rp='+encodeURIComponent(JSON.stringify(response.data));
                        window.open(base_url+'reports/generatereports/'+report+'?'+qstring);
                    }
                    else {
                        bootbox.alert(response.message);
                    }
                }
            });
            
            
            
            
        };

        var reportPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.get(base_url+'reports/loadreports?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                  
                }else{
                    $scope.reportGrid.totalItems = response.total;
                    $scope.reportGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
       
       reportPage();
    }
]);


$( document ).ready(function() {
    
    var fromdate = null;
    var todate = null;
    $(document).on('change', '#fromdate', function() {
        fromdate = $('#fromdate').datepicker("getDate");
        todate = $('#todate').datepicker("getDate");
        $('input[name="todate"]').datepicker('setStartDate', fromdate);
        $("#reportsCtrl #daterange").val('custom');
        
        fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
        todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
        if(todate<fromdate) {
            $('#todate').val($('#fromdate').val());
            $('#todate').datepicker('update');
        }
    });
    
    $(document).on('change', '#todate', function() {
        fromdate = $('#fromdate').datepicker("getDate");
        todate = $('#todate').datepicker("getDate");
        $('input[name="fromdate"]').datepicker('setEndDate', todate);
       $("#reportsCtrl #daterange").val('custom');
        
        fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
        todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
        if(todate<fromdate) {
            $('#fromdate').val($('#todate').val());
            $('#fromdate').datepicker('update');
        }
    });
    
    $(document).on('change', '#reportsCtrl #daterange', function() {
        var elm = $(this);
        if(elm.val() === 'custom') {
            return false;
        }
        $("#fromdate").val($('option:selected', elm).attr('data-fromdate'));
        $("#todate").val($('option:selected', elm).attr('data-todate'));
        
        $('#fromdate').datepicker('update');
        $('#todate').datepicker('update');
        
        $('input[name="todate"]').datepicker('setStartDate', $('#fromdate').datepicker("getDate"));
        $('input[name="fromdate"]').datepicker('setEndDate', $('#todate').datepicker("getDate"));
    });
    
    $(document).on('click', '#reportGrid input[name="select_all"]', function(e){

        if(this.checked){
           $('#reportGrid input[name="reportcheckbox[]"]:not(:checked)').trigger('click');
        } else {
           $('#reportGrid input[name="reportcheckbox[]"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    $(document).on('click', '#reportGrid input[name="reportcheckbox[]"]', function(e){

        var chkbox_all = $('#reportGrid input[name="reportcheckbox[]"]');
        var chkbox_checked    = $('#reportGrid input[name="reportcheckbox[]"]:checked');
        var chkbox_select_all  = $('#reportGrid input[name="select_all"]');

            // If none of the checkboxes are checked
            if (chkbox_checked.length === chkbox_all.length){
               chkbox_select_all.prop('checked', true);

            // If some of the checkboxes are checked
            } else {
               chkbox_select_all.prop('checked', false);

            }

         // Prevent click event from propagating to parent
         e.stopPropagation();
    });
    
    
});