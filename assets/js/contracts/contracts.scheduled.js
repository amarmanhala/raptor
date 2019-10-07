
/* global base_url, angular, bootbox, headercategory, eventDatePickerCounter, moment, defaultdateformat */

"use strict";
if($("#ServiceScheduleCtrl").length) {

    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('ServiceScheduleCtrl', [
        '$scope', '$http', 'uiGridConstants', '$timeout', function($scope, $http, uiGridConstants, $timeout) {

         // filter
        $scope.filterOptions = {
            state: '',
            contract: '',
            site: '',
            servicetype: '',
            jobstatus: '',
            show: 'month',
            cweek: '',
            cmonth: ''
        };

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };
       
       $scope.columns = [
            { 
                displayName:'',
                cellTooltip: true,
                name: 'Ref',
                width: 100,
                category:'Ref'
            },
            { 
                displayName:'Week',
                cellTooltip: true,
                name: 'weekending',
                enableSorting: true,
                width: 200,
                category:'Site',
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Week</div>'
            }
        ];
        
       $scope.gridOptions = {
         paginationPageSizes: [10, 25, 50, 100, 200],
         paginationPageSize: 25,
         headerTemplate: base_img_url+'ui-grid-template/category_header.html',
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         enableFiltering: false,
         enablePinning: true,
         enableRowSelection: false,
         enableRowHeaderSelection: false,
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

        $scope.gridOptions.multiSelect = false;
        $scope.gridOptions.modifierKeysToMultiSelect = false;
        $scope.gridOptions.noUnselect = false;

        $scope.changeFilters = function() {
            getPage();
        };
        
        $scope.clearFilters = function() {
            
            paginationOptions.sort = '';
            paginationOptions.field = '';
            $scope.filterOptions = {
                state: '',
                contract: '',
                site: '',
                servicetype: '',
                jobstatus: '',
                show: $scope.filterOptions.show,
                cweek: $scope.filterOptions.cweek,
                cmonth: $scope.filterOptions.cmonth
            }; 
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
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
                   //console.log($scope.height);
                   $('.ui-category-heading').css('height',($scope.height+12)+'px');
               }
            });
        });
        
        $scope.exportToExcel=function(){
            window.open(base_url+'contracts/exportscheduled?'+$.param($scope.filterOptions));
        };
        
        $scope.validateDocument = function(documentid) {
           $('#ServiceScheduleCtrl .overlay').show();
            $.get( base_url+"documents/checkdocument", { documentid:documentid }, function( response ) {
                $('#ServiceScheduleCtrl .overlay').hide();
                if (response.success) {
                     window.open(base_url+'documents/viewdocument/'+documentid);
                }
                else {
                    bootbox.alert(response.message);
                }
            }); 
        };
        
        $(document).on('click', '#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth', function() {
            $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
            $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
            var id = $(this).attr('id');
            $scope.filterOptions.cweek = '';
            $scope.filterOptions.cmonth = '';
            var dateformat = defaultdateformat.toUpperCase();
            if(id === 'showDay') {
                $scope.filterOptions.show = 'day';
            } else if(id === 'showWeek') {
                $scope.filterOptions.show = 'week';
            } else if(id === 'showMonth') {
                $scope.filterOptions.show = 'month';
            } else if(id === 'showThisMonth') {
                var startDate = moment(new Date().getFullYear() + '-' + (new Date().getMonth()+1) + '-' + '01' + ' 00:00:00');
                var endDate = startDate.clone().endOf('month');
                $('#fromdate').val(moment(startDate).format(dateformat));
                $('#todate').val(moment(endDate).format(dateformat));
                $('#fromdate').datepicker('update');
                $('#todate').datepicker('update');
                $scope.filterOptions.show = 'day';
                $scope.filterOptions.cmonth = '1';
            } else if(id === 'showThisWeek') {
                $('#fromdate').val(moment(new Date()).weekday(0).format(dateformat)); 
                $('#todate').val(moment(new Date()).weekday(6).format(dateformat)); 
                $('#fromdate').datepicker('update');
                $('#todate').datepicker('update');
                $scope.filterOptions.show = 'day';
                $scope.filterOptions.cweek = '1';
            }
            getPage();
        });
            
        var fromdate = null;
        var todate = null;

        $(document).on('change', '#fromdate', function() {
            fromdate = $('#fromdate').datepicker("getDate");
            todate = $('#todate').datepicker("getDate");
            $('input[name="todate"]').datepicker('setStartDate', fromdate);

            fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
            todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
            if(todate<fromdate) {
                $('#todate').val($('#fromdate').val());
                $('#todate').datepicker('update');
            }
            
            //console.log($scope.filterOptions.cweek+" "+$scope.filterOptions.cmonth);
            if($scope.filterOptions.cweek === '' && $scope.filterOptions.cmonth === '') {
                
            } else {
                $scope.filterOptions.cweek = '';
                $scope.filterOptions.cmonth = '';
                var a = moment([fromdate.getFullYear(), fromdate.getMonth(), fromdate.getDate()]);
                var b = moment([todate.getFullYear(), todate.getMonth(), todate.getDate()]);
                
                var startmonth = parseInt(fromdate.getMonth())+1;
                var endmonth = parseInt(todate.getMonth())+1;
                if(startmonth === endmonth) {
                    $scope.filterOptions.show = 'day';
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
                    $('#showDay').removeClass('btn-default');
                    $('#showDay').addClass('btn-primary');
                } else if((endmonth - startmonth) > 1) {
                    
                    
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
                    $('#showMonth').removeClass('btn-default');
                    $('#showMonth').addClass('btn-primary');
                } else {
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
                    $('#showWeek').removeClass('btn-default');
                    $('#showWeek').addClass('btn-primary');
                }
                //var daydiff = a.diff(b, 'days');
                //var monthdiff = a.diff(b, 'months');
                //console.log(monthdiff);
            }
           
            if(eventDatePickerCounter == 0) {
                setTimeout(getPage(), 100);
            }
            eventDatePickerCounter++;
            
        });

        $(document).on('change', '#todate', function() {
            fromdate = $('#fromdate').datepicker("getDate");
            todate = $('#todate').datepicker("getDate");
            $('input[name="fromdate"]').datepicker('setEndDate', todate);

            fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
            todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
            if(todate<fromdate) {
                $('#fromdate').val($('#todate').val());
                $('#fromdate').datepicker('update');
            }
            
            if($scope.filterOptions.cweek === '' && $scope.filterOptions.cmonth === '') {
                
            } else {
                $scope.filterOptions.cweek = '';
                $scope.filterOptions.cmonth = '';
                var a = moment([fromdate.getFullYear(), fromdate.getMonth(), fromdate.getDate()]);
                var b = moment([todate.getFullYear(), todate.getMonth(), todate.getDate()]);
                
                var startmonth = parseInt(fromdate.getMonth())+1;
                var endmonth = parseInt(todate.getMonth())+1;
                if(startmonth === endmonth) {
                    $scope.filterOptions.show = 'day';
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
                    $('#showDay').removeClass('btn-default');
                    $('#showDay').addClass('btn-primary');
                } else if((endmonth - startmonth) > 1) {
                    $scope.filterOptions.show = 'month';
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
                    $('#showMonth').removeClass('btn-default');
                    $('#showMonth').addClass('btn-primary');
                } else {
                    $scope.filterOptions.show = 'week';
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').removeClass('btn-primary');
                    $('#showDay, #showWeek, #showMonth, #showThisWeek, #showThisMonth').addClass('btn-default');
                    $('#showWeek').removeClass('btn-default');
                    $('#showWeek').addClass('btn-primary');
                }
                //var daydiff = a.diff(b, 'days');
                //var monthdiff = a.diff(b, 'months');
                //console.log(monthdiff);
            }
            
            if(eventDatePickerCounter == 0) {
                setTimeout(getPage(), 100);
            }
            eventDatePickerCounter++;
            //console.log($scope.filterOptions.cweek+" "+$scope.filterOptions.cmonth);
        });
        
       var getPage = function() {
             
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            
            $scope.filterOptions.fromdate = $('#fromdate').val();
            $scope.filterOptions.todate = $('#todate').val();
            
            var dateformat = defaultdateformat.toUpperCase();
            var show = $scope.filterOptions.show;
            
            var fromdate = moment($scope.filterOptions.fromdate, dateformat).toDate();
            var todate = moment($scope.filterOptions.todate, dateformat).toDate();
            var startyear = parseInt(fromdate.getFullYear());
            var endyear = parseInt(todate.getFullYear());
            var startmonth = parseInt(fromdate.getMonth())+1;
            var endmonth = parseInt(todate.getMonth())+1;
            var yeardiff = endyear-startyear;

            if(yeardiff>1) {
                bootbox.alert('Date Range limit 2 years');
                return false;
            }
            
            if(show === 'day' && monthDiff(fromdate, todate) > 2) {
                bootbox.alert('Two month limit in Day option');
                return false;
            }
            
            if((show === 'week' || show === 'month') && monthDiff(fromdate, todate) > 12) {
                bootbox.alert('12 month limit');
                return false;
            }
            
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#ServiceScheduleCtrl .overlay').show();
             $http.get(base_url+'contracts/loadscheduledservices?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        var columns = data.data.columns;
                        //console.log(data.data.data);
                        
                        headercategory = [
                            { name: 'Ref' , visible: true },
                            { name: 'Site' , visible: true }
                        ];
                        
                        var wd = 'Week';
                        if($scope.filterOptions.show === 'week') {
                            wd = 'Week';
                        } else if($scope.filterOptions.show === 'day') {
                            wd = 'Day';
                        }else if($scope.filterOptions.show === 'month') {
                            wd = 'Month';
                        } else {
                            wd = 'Day';
                        }
                        //console.log($scope.filterOptions.show);

                        $scope.columns = [
                            { 
                                displayName:'',
                                cellTooltip: true,
                                //name: 'Ref',
                                name: 'siteref',
                                minWidth: 100,
                                pinnedLeft:true,
                                category:'Ref'
                            },
                            { 
                                displayName: wd,
                                cellTooltip: true,
                                //name: $scope.filterOptions.show,
                                name: $scope.filterOptions.show + '_sitesuburb',
                                enableSorting: true,
                                minWidth: 200,
                                pinnedLeft:true,
                                category:'Site',
                                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">' + wd + '</div>'
                            }
                        ];
                        
                        $scope.gridOptions.category = headercategory;
                        $scope.gridOptions.columnDefs = $scope.columns;

                        
                        $.each(columns, function( key, val ) {
                            headercategory.push({
                                name: val.month,
                                visible: true
                            });
                        });
                        
                        $.each(columns, function( key, val ) {
                            //console.log(val.month);
                            var cp = '';
                            if(parseInt(val.m) === 1) {
                                $scope.columns.push({ 
                                    displayName:cp,
                                    cellTooltip: true,
                                    name: 'mon_'+val.monthNumeric,
                                    enableSorting: false,
                                    width: 150,
                                    category:val.month,
                                    cellTemplate: '<div class="btn-group-vertical text-center" ng-bind-html="COL_FIELD | trusted"></div>'
                                });
                            }
                            
                            if(parseInt(val.w) === 1) {
                                $.each(val.weeks, function( key1, val1 ) {
                                    $scope.columns.push({ 
                                        displayName:val1.day,
                                        cellTooltip: true,
                                        name: 'd_' + val.monthNumeric + '_' + val1.day,
                                        enableSorting: false,
                                        width: 70,
                                        category:val.month,
                                        cellTemplate: '<div class="btn-group-vertical text-center" ng-bind-html="COL_FIELD | trusted"></div>'
                                    });
                                });
                            }
                            
                            if(parseInt(val.d) === 1) {
                                if($scope.filterOptions.cweek === '') {
                                    var columnWidth = 70;
                                    if((val.days).length <= 2) {
                                        columnWidth = 150;
                                    }
                                    $.each(val.days, function( key1, val1 ) {
                                        $scope.columns.push({ 
                                            displayName:val1,
                                            cellTooltip: true,
                                            name: 'd_' + val.monthNumeric + '_' + val1,
                                            enableSorting: false,
                                            width: columnWidth,
                                            category:val.month,
                                            cellTemplate: '<div class="btn-group-vertical text-center" ng-bind-html="COL_FIELD | trusted"></div>'
                                        });
                                    });
                                } else {
                                    $.each(val.days, function( key1, val1 ) {
                                        val1 = val1.split(' ');
                                        $scope.columns.push({ 
                                            displayName:val1[1],
                                            cellTooltip: true,
                                            name: 'd_' + val.monthNumeric + '_' + val1[0],
                                            enableSorting: false,
                                            width: 70,
                                            category:val.month,
                                            cellTemplate: '<div class="btn-group-vertical text-center" ng-bind-html="COL_FIELD | trusted"></div>'
                                        });
                                    });
                                }
                                
                            }
                        });

                        $scope.gridOptions.totalItems = data.total;
                        $scope.gridOptions.data = data.data.data;
                        
                        //ROWS RENDER
                        $scope.gridApi.core.on.rowsRendered($scope, function () {

                            // each rows rendered event (init, filter, pagination, tree expand)
                            // Timeout needed : multi rowsRendered are fired, we want only the last one
                            if (rowsRenderedTimeout) {
                                $timeout.cancel(rowsRenderedTimeout);
                            }

                            rowsRenderedTimeout = $timeout(function () {
                                alignContainers('#scheduledGrid', $scope.gridApi.grid);
                            });

                        });

                        //SCROLL END
                        $scope.gridApi.core.on.scrollEnd($scope, function () {
                            alignContainers('#scheduledGrid', $scope.gridApi.grid);
                        });
                        
                    }
                 
                   $('#ServiceScheduleCtrl .overlay').hide();
             });
       };

       getPage();
     }
     ]);
     
    app.filter('trusted', function ($sce) {
        return function (value) {
          return $sce.trustAsHtml(value);
        }
    });
}

var monthDiff = function(d1, d2) {
    var m= (d2.getFullYear()-d1.getFullYear())*12+(d2.getMonth()-d1.getMonth())+1;
    return m;
};

$( document ).ready(function() {
    
}); 