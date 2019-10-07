/* global base_url, bootbox */

$( document ).ready(function() {
   
    $("#poChartModal #chartfromdate").on('changeDate', function(e) {
        $('#poChartModal input[name="charttodate"]').datepicker('setStartDate', e.date);
         
    });
    
    $("#poChartModal #charttodate").on('changeDate', function(e) {
        $('#poChartModal input[name="chartfromdate"]').datepicker('setEndDate', e.date);
         
    });
   
    $(document).on('click', '#btnchartpo', function(){
        $('#poChartModal').modal();
        
//        if($('#poChartModal #chartfromdate').val() !== ""){
//            $('#poChartModal input[name="chartfromdate"]').datepicker('setDate', $('#poChartModal #chartfromdate').val());
//        }
//        if($('#poChartModal #charttodate').val() !== ""){
//            $('#poChartModal input[name="charttodate"]').datepicker('setDate', $('#poChartModal #charttodate').val());
//        }
            
            
        allcharts();
    });
     $(document).on('click', '.btnClearChartFilter', function(){
        $('#poChartModal input[name="chartfromdate"]').datepicker('setEndDate', null);
        $('#poChartModal input[name="charttodate"]').datepicker('setStartDate', null);
        $('#poChartModal #glcode').val('');
        $('#poChartModal #chartfromdate').val('');
        $('#poChartModal #charttodate').val('');
        allcharts();
    });
     $(document).on('click', '.btnRefreshChartFilter', function(){
        
        allcharts();
    });
    
    $(document).on('change', '.chartfilter', function() {
            
       allcharts();
    });
    
});   

function allcharts()
{
    
    var filterdata={};
         
    filterdata.glcode=$('#poChartModal #glcode').val();
    filterdata.fromdate=$('#poChartModal #chartfromdate').val();
    filterdata.todate=$('#poChartModal #charttodate').val();
    
    $('#poChartModal .loading-div').show()
    $('#poChartModal .chartdiv').hide()
    
    
    $.get( base_url+"purchaseorders/loadchart", filterdata, function( response ) {
        if(response.success){
            lineChartbasic(filterdata, response.data.lineChart);
            barChartCross(filterdata, response.data.barChart);
        }
        else{
            bootbox.alert(response.message);
        }
        $('#poChartModal .loading-div').hide()
        $('#poChartModal .chartdiv').show()
    });
  
}

function lineChartbasic(filterdata,data) {
    
    $('#poChartModal #lineChart').highcharts({
                title: {
                    text: data.title,
                    x: -20 //center
                },
                subtitle: {
                    text:filterdata.subtitle,
                    x: -20
                },
                credits: {
                   enabled: false
                },

                xAxis: {
                    categories: data.xAxiscate
                },
                yAxis: {
                    title: {
                        text: data.yAxistitle
                    },
                    plotLines: [{
                        value: 0,
                        width: 0,
                        color: '#808080'
                    }]
                },

                tooltip: {
                    valueSuffix: '$',
                    headerFormat: '<span style="font-size:11px">{point.x}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{series.name}</span>: <b>${point.y:.2f}</b><br/>'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },

                series: data.seriesdata
    });                
    
}
 
function barChartCross(filterdata,data) {
    
    $('#poChartModal #barChart').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: data.title,
            x: -20
        },
        subtitle: {
            text: filterdata.subtitle,
            x: -20
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: data.xAxiscate,
            crosshair: true
        },
        yAxis: {
            title: {
                text: data.yAxistitle
            }

        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        }, 
        plotOptions: {
            column: {
                pointPadding: 0.0,
                borderWidth: 0
            } 
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>$ {point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },

        series: data.seriesdata
    });
    
}