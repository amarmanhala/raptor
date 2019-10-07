/* global base_url, angular, bootbox, eventDatePickerCounter, Highcharts */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']);
var fromdate=null;
var todate=null;
app.controller('labourDashboardCtrl', [
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

    $scope.labourGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        showColumnFooter: true,
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

        $scope.refreshFilter = function() {
            reportPage();
        };
        
        $scope.clearFilters = function() {
            $("#periods").val($("#periods option:first").val());
            $("#state").val($("#state option:first").val());
            $("#manager").val($("#manager option:first").val());
            $("#site").val($("#site option:first").val());
            $("#contract").val($("#contract option:first").val());
            if($(".select2").length) {
                $(".select2").select2();
            }
            $("#periods").trigger('change');

            reportPage(); 
            //$('.selectpicker').selectpicker('deselectAll');
            //contactPage();
        };
    
        $scope.exportToExcel = function() {
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                fromdate : $('#fromdate').val(),
                todate : $('#todate').val(),
                state : $('#state').val(),
                manager : $('#manager').val(),
                site : $('#site').val(),
                contract : $('#contract').val(),
                groupby : $('input[name=groupby]:checked').val()
            }; 

            var qstring = $.param(params);
            window.open(base_url+'dashboard3/exportdashboarddata?'+qstring);
        };
        
        $(document).on('click', 'input[name=groupby]', function() {
            reportPage();
        });
        
        var gridData = [];
        var reportPage = function() {
            
            var groupby = $('input[name=groupby]:checked').val();
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                fromdate : $('#fromdate').val(),
                todate : $('#todate').val(),
                state : $('#state').val(),
                manager : $('#manager').val(),
                site : $('#site').val(),
                contract : $('#contract').val(),
                groupby : groupby
            }; 

            var qstring = $.param(params);
            $("#chartfilter").val('lbourhr');

            $scope.overlay = true;
            $http.get(base_url+'dashboard3/loadlabourdashboarddata?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                  
                }else{
                    var data = response.data;
                    //labourChart(data.chartData);
                    
                    
                    var columns = data.columns;
                        
                        $scope.columns = [];
                        $scope.labourGrid.columnDefs = $scope.columns;
                        
                        $.each(columns, function( key, val ) {
                                var width = '*';
                                if(parseInt(val.site) === 1) {
                                    width = 100;
                                    if(key === 0) {
                                        width = 140;
                                    }
                                }
                                if(parseInt(val.tech) === 1) {
                                    width = '*';
                                }
                                if(parseInt(val.job) === 1) {
                                    width = 100;
                                    if(key === 1) {
                                        width = 140;
                                    }
                                }
                                                                
                                var c = { 
                                    displayName:val.displayfield,
                                    cellTooltip: true,
                                    name: val.name,
                                    enableSorting: false,
                                    width: width,
                                    //cellTemplate: '<div class="btn-group-vertical text-center" ng-bind-html="COL_FIELD | trusted"></div>'
                                };
                                
                                var labelTotal = '<div class="ui-grid-cell-contents text-right"><span class="ng-binding">Total</span></div>';
                                if(parseInt(val.site) === 1 && key === 4) {
                                    c.footerCellTemplate = labelTotal;
                                } else if(parseInt(val.tech) === 1 && key === 1) {
                                    c.footerCellTemplate = labelTotal
                                } else if(parseInt(val.job) === 1 && key === 4) {
                                    c.footerCellTemplate = labelTotal
                                }
                                
                                var hoursTotal = function() {
                                    var hours = 0;
                                    $scope.labourGrid.data.forEach(function(rowEntity) {
                                        hours = hours +  intVal(rowEntity.hours);
                                    });
                                    return parseFloat(hours).toFixed(2) + ' Hrs';
                                };
                                
                                if(parseInt(val.site) === 1 && key === 5) {
                                    c.footerCellClass = 'text-left', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = hoursTotal
                                } else if(parseInt(val.tech) === 1 && key === 2) {
                                    c.footerCellClass = 'text-left', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = hoursTotal
                                } else if(parseInt(val.job) === 1 && key === 5) {
                                    c.footerCellClass = 'text-left', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = hoursTotal
                                }
                                
                                var rateTotal = function() {
                                    var rate = 0;
                                    $scope.labourGrid.data.forEach(function(rowEntity) {
                                        rate = rate +  intVal(rowEntity.rate);
                                    });
                                    return '$ '+ rate;
                                };
                                
                                if(parseInt(val.site) === 1 && key === 6) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = rateTotal;
                                } else if(parseInt(val.tech) === 1 && key === 3) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = rateTotal;
                                } else if(parseInt(val.job) === 1 && key === 6) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = rateTotal;
                                }
                                
                                var materialcostTotal = function() {
                                    var materialcost = 0;
                                    $scope.labourGrid.data.forEach(function(rowEntity) {
                                        materialcost = materialcost +  intVal(rowEntity.materialcosts);
                                    });
                                    return '$ '+ parseFloat(materialcost).toFixed(2);
                                 };
                                 
                                if(parseInt(val.site) === 1 && key === 7) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = materialcostTotal
                                } else if(parseInt(val.job) === 1 && key === 7) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = materialcostTotal
                                }
                                
                                var total = function() {
                                    var billamt = 0;
                                    $scope.labourGrid.data.forEach(function(rowEntity) {
                                        billamt = billamt +  intVal(rowEntity.billamt);
                                    });
                                    return '$ '+ parseFloat(billamt).toFixed(2);
                                };
                                 
                                if(parseInt(val.site) === 1 && key === 8) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = total;
                                } else if(parseInt(val.job) === 1 && key === 8) {
                                    c.cellClass = 'text-right', 
                                    c.footerCellClass = 'text-right', 
                                    c.aggregationHideLabel = true,
                                    c.aggregationType = total;
                                }
                                
                                $scope.columns.push(c);
                        });

                        $scope.labourGrid.totalItems = data.total;
                        $scope.labourGrid.data = data.data;
                        gridData = data.data;
                        
                        var chartData = [];
                        var json = {};
                        var errLabel = '';
                        $.each(data.data, function( key, val ) {
                            if(groupby == 'bysite') {
                                json = {
                                    name : val.siteline2,
                                    y : parseInt(val.hours)
                                };
                                errLabel = 'Sites';
                            }
                            
                            if(groupby == 'bytech') {
                                json = {
                                    name : val.technician,
                                    y : parseInt(val.hours)
                                };
                                errLabel = 'Technicans';
                            }
                            
                            if(groupby == 'byjob') {
                                json = {
                                    name : 'Job Id '+val.jobid,
                                    y : parseInt(val.hours)
                                };
                                errLabel = 'Jobs';
                            }
                            chartData.push(json);
                        });
                        
                        if(gridData.length > 5) {
                            $("#jobstageChart").html('<div class="alert alert-info">Too many '+errLabel+' to display on chart</div>');
                        } else {
                            labourChart(chartData, 'Labour Hours');
                        }
                    }
                $scope.overlay = false;
            });
       };
       
       $(document).on('change', '#chartfilter', function() {
           
            var groupby = $('input[name=groupby]:checked').val();
            var v = $(this).val();
  
            var chartlabel = '';
            if(v === 'lbourhr') {
                chartlabel = 'Labour Hours';
            } else if(v === 'lbour') {
                chartlabel = 'Labour';
            } else if(v === 'material') {
                chartlabel = 'Material';
            }
            
            var chartData = [];
            var json = {};
            var errLabel = '';
            $.each(gridData, function( key, val ) {
                
                if(groupby === 'bysite') {
                    json = {
                        name : val.siteline2,
                        y : v === 'lbourhr' ? parseInt(val.hours) : v === 'lbour' ? parseInt(val.rate) : parseInt(val.materialcosts)
                    };
                    errLabel = 'Sites';
                }

                if(groupby === 'bytech') {
                    json = {
                        name : val.technician,
                        y : v === 'lbourhr' ? parseInt(val.hours) : parseInt(val.rate)
                    };
                    errLabel = 'Technicans';
                }

                if(groupby === 'byjob') {
                    json = {
                        name : 'Job Id '+val.jobid,
                        y : v === 'lbourhr' ? parseInt(val.hours) : v === 'lbour' ? parseInt(val.rate) : parseInt(val.materialcosts)
                    };
                    errLabel = 'Jobs';
                }
                chartData.push(json);
            });
            
            if(gridData.length > 5) {
                $("#jobstageChart").html('<div class="alert alert-info">Too many '+errLabel+' to display on chart</div>');
            } else {
                labourChart(chartData, chartlabel);
            }
       });
       
       
       reportPage();
       loadPageData();
    }
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    }
});


$(document).ready(function() {

    $(document).on('change', '#fromdate', function() {
        fromdate = $('#fromdate').datepicker("getDate");
        todate = $('#todate').datepicker("getDate");
        $('input[name="todate"]').datepicker('setStartDate', fromdate);
        $(".select2").select2("val", "custom");
        
        fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
        todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
        if(todate<fromdate) {
            $('#todate').val($('#fromdate').val());
            $('#todate').datepicker('update');
        }
        
        if(eventDatePickerCounter == 0) {
            setTimeout(loadPageData(), 100);
        }
        eventDatePickerCounter++;
    });
    
    $(document).on('change', '#todate', function() {
        fromdate = $('#fromdate').datepicker("getDate");
        todate = $('#todate').datepicker("getDate");
        $('input[name="fromdate"]').datepicker('setEndDate', todate);
        $(".select2").select2("val", "custom");
        
        fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
        todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
        if(todate<fromdate) {
            $('#fromdate').val($('#todate').val());
            $('#fromdate').datepicker('update');
        }
        
        if(eventDatePickerCounter == 0) {
            setTimeout(loadPageData(), 100);
        }
        eventDatePickerCounter++;
    });
    
    $(document).on('change', '#periods', function() {
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
        loadPageData();
    });
    
    $(document).on('change', '#state', function() {
        loadPageData();
    });
});

function loadPageData() {
    
    var request = {
        fromdate : $('#fromdate').val(),
        todate : $('#todate').val(),
        state : $('#state').val()
    };

    $.get( base_url+"dashboard3/loadpagedata", request, function( response ) {

        if (response.success) {
            var options = '<option value="">All</option>';
            $.each(response.data.technicians, function( key, val ) {
                options += '<option value="'+val.userid+'">'+val.technician+'</option>';
            });
            $("#technician").html(options);
            options = '<option value="">All</option>';
            $.each(response.data.sites, function( key, val ) {
                options += '<option value="'+val.labelid+'">'+val.site+'</option>';
            });
            $("#site").html(options);
            if($(".select2").length) {
                $(".select2").select2();
            }
        }
        else {
            bootbox.alert(response.message);
        }

    });
}

function labourChart(data, label) {
   
    $('#jobstageChart').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: null
        },
        tooltip: {
            //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            pointFormat: '{series.name}: <b>{point.y}</b>'

        },
        legend: {
            layout: 'vertical',
            align: 'bottom',
            verticalAlign: 'bottom',
            borderWidth: 0,
            floating: true,
            y:20
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: label,
            colorByPoint: true,
            data: data
        }]
    }); 
         
}