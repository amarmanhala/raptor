/* global base_url, Highcharts */

"use strict";
var responsedata;
var title;
var fromdate=null;
var todate=null;
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
    });
    
    $(document).on('click', '#searchfilter', function(e) {
        loadjobdashboarChart();
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
    });

    /*$(document).on('change', '.chartfilter', function() {
            
        loadjobdashboarChart(); 
    });*/
    
    loadjobdashboarChart();
   
    $(document).on('click', '#resetfilter', function() {
        $("#periods").val($("#periods option:first").val());
        $("#state").val($("#state option:first").val());
        $("#manager").val($("#manager option:first").val());
        $("#site").val($("#site option:first").val());
        if($(".select2").length) {
            $(".select2").select2();
        }
        $("#periods").trigger('change');

        loadjobdashboarChart(); 
    });
    
    $(document).on('click', '.collapsed-box #jobstagecollapse', function() {
        if(responsedata.jobStage !== undefined){
            jobStageChart(responsedata.jobStage, title);
        }
    });
    
    $(document).on('click', '.collapsed-box #jobcompletioncollapse', function() {
        if(responsedata.jobCompletion !== undefined){
            jobCompletionChart(responsedata.jobCompletion, title);
        }
    });
    
    $(document).on('click', '.collapsed-box #jobattendancescollapse', function() {
        if(responsedata.jobAttendance !== undefined){
             jobAttendancesChart(responsedata.jobAttendance, title);
        }
    });
    
    $(document).on('click', '.collapsed-box #jobtrendcollapse', function() {
        if(responsedata.jobTrand !== undefined){
            jobTrendChart(responsedata.jobTrand, title);
        }
    });
    
});   

function loadjobdashboarChart() {

    var postdate = {
        //fromdate : $('#periods option:selected').attr('data-fromdate'),
        //todate : $('#periods option:selected').attr('data-todate'),
        fromdate : $('#fromdate').val(),
        todate : $('#todate').val(),
        state : $('#state').val(),
        manager : $('#manager').val(),
        site : $('#site').val()
    };
    title = 'period '+ postdate.fromdate + ' to ' + postdate.todate;
    
    $.post( base_url+"dashboard/loadjobdashboardchart", postdate, function( response ) {
        if(response.success) {
            var data = response.data;
            responsedata = data;
            jobStageChart(data.jobStage, title);
            jobCompletionChart(data.jobCompletion, title);
            jobAttendancesChart(data.jobAttendance, title);
            jobTrendChart(data.jobTrand, title);
            
        } else {
            bootbox.alert(response.message);
            return false;
        }
            
    });
}

function jobStageChart(data, title) {
   
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
            text: 'Job Stage in '+ title
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
            name: "Job Stage",
            colorByPoint: true,
            data: data
        }]
    }); 
         
}

function jobCompletionChart(data, title) {
  
    $('#jobcompletionChart').highcharts({
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
            text: 'Completion in '+ title
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
            name: "Job Completion",
            colorByPoint: true,
            data: data
        }]
    }); 
        
}

function jobAttendancesChart(data, title) {
  
    
    $('#jobattendancesChart').highcharts({
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
            text: 'Attendance in ' +title
        },
        tooltip: {
            pointFormat: '<b>{point.y}</b>'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
            name: "Job Attendance",
            colorByPoint: true,
            data: data
        }]
    }); 
        
}

function jobTrendChart(data, title) {
 
       
    $('#jobtrendChart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Job Count in ' + title
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jobs'
            } 
        },
         credits: {
            enabled: false
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'No of Jobs : <b>{point.y:.0f}</b>'
        },
        series: [{
            name: 'Jobs',
            data: data 
        }]
    });
         
}
 