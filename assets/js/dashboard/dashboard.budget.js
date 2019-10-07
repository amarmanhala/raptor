/* global base_url, bootbox */

$( document ).ready(function() {
   
    
    allcharts();
    $(document).on('change', '.chartfilter', function() {
            
       allcharts();
    });
    $('#exclude0site').change(function() {
	
        
        var filterdata={};
         
        filterdata.fyear=$('#fyear').val();
        if($('#fcontactid').length) {
             filterdata.fcontactid=$('#fcontactid').val();
        }
        if($('#fsite').length) {
             filterdata.fsite=$('#fsite').val();
        }
        if($('#exclude0site').is(':checked')) {
             filterdata.exclude0site=1;	 
        }
         
        filterdata.subtitle =$('#fyear option:selected').text() +  ' - '+ $('#fcontactid option:selected').text() + " - " +  $('#fsite option:selected').text();
      
        if($('#steckedbarchart_sitespend').length){
            stackbarChar(filterdata,'steckedbarchart_sitespend');
        }
    });
    
});   

function allcharts()
{
    
    var filterdata={};
         
    filterdata.fyear=$('#fyear').val();
    if($('#fcontactid').length) {
         filterdata.fcontactid=$('#fcontactid').val();
    }
    if($('#fsite').length) {
         filterdata.fsite=$('#fsite').val();
    }
    if($('#exclude0site').length) {
        if($('#exclude0site').is(':checked')) {
             filterdata.exclude0site=1;
        }
    }
    filterdata.subtitle =$('#fyear option:selected').text() +  ' - '+ $('#fcontactid option:selected').text() + " - " +  $('#fsite option:selected').text();
        
    if($('#lineChart_12month').length){
        $.post( base_url+"dashboard2/loadmonthlyspendytd", filterdata, function( response ) {
            if(response.success){
                lineChartbasic(filterdata, response.data, 'lineChart_12month');
            }
            else{
                bootbox.alert(response.message);
            }
            
        });
    }
    if($('#barChart_budgetvsactual').length){
        $.post( base_url+"dashboard2/loadbudgetvsactualytd", filterdata, function( response ) {
            if(response.success){
                barChartbasic(filterdata, response.data, 'barChart_budgetvsactual');
            }
            else{
                bootbox.alert(response.message);
            } 
        });
    }
    if($('#barChart_budgetvsactualfm').length){
        $.post( base_url+"dashboard2/loadbudgetvsactualytdbyfm", filterdata, function( response ) {
            if(response.success){
                barChartCross(filterdata, response.data, 'barChart_budgetvsactualfm');
            }
            else{
                bootbox.alert(response.message);
            }  
        });
    }
    if($('#piechart_budgetbyfm').length){
        $.post( base_url+"dashboard2/loadbudgetbyfm", filterdata, function( response ) {
            if(response.success){
                pieChartbasic(filterdata, response.data, 'piechart_budgetbyfm');
            }
            else{
                bootbox.alert(response.message);
            }  
        });
    }
    if($('#piechart_spendbyfm').length){
        $.post( base_url+"dashboard2/loadspendbyfm", filterdata, function( response ) {
            if(response.success){
                pieChartbasic(filterdata, response.data, 'piechart_spendbyfm');
            }
            else{
                bootbox.alert(response.message);
            } 
            
        });
    }
    if($('#steckedbarchart_sitespend').length){
        $.post( base_url+"dashboard2/loadsitespend", filterdata, function( response ) {
            if(response.success){
                stackbarChar(filterdata, response.data, 'steckedbarchart_sitespend');
            }
            else{
                bootbox.alert(response.message);
            }  
        });
    }
    if($('#piechart_budgetbysite').length){
        $.post( base_url+"dashboard2/loadbudgetbysites", filterdata, function( response ) {
            if(response.success){
                pieChartbasic(filterdata, response.data, 'piechart_budgetbysite');
            }
            else{
                bootbox.alert(response.message);
            }   
        });
    }
    
    if($('#piechart_spendbysite').length){
        $.post( base_url+"dashboard2/loadspendbysites", filterdata, function( response ) {
            if(response.success){
                pieChartbasic(filterdata, response.data, 'piechart_spendbysite');
            }
            else{
                bootbox.alert(response.message);
            }  
        });
    }
    
    
    
    
}

function lineChartbasic(filterdata,data,chartdivid) {
    
    $('#'+chartdivid).highcharts({
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

function barChartbasic(filterdata,data,chartdivid) {
      
    $('#'+chartdivid).highcharts({
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
            type: 'category'
        },
        yAxis: {
            title: {
                text: data.yAxistitle
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '${point.y:.2f}'
                }
            }
        },

        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:.2f}</b><br/>'
        },

        series: data.seriesdata
    });                
  
}

function pieChartbasic(filterdata,data,chartdivid) {
     
    $('#'+chartdivid).highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text:data.title,
                x: -20
            },
            subtitle: {
                text: filterdata.subtitle,
                x: -20
            },
             
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
           /*legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },*/
            series: [{
                name: data.yAxistitle,
                colorByPoint: true,
                data: data.seriesdata
            }]
        });             
  
}

function stackbarChar(filterdata,data,chartdivid) {
     
    $('#'+chartdivid).highcharts({
        chart: {
            type: 'bar'
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
            categories: data.xAxiscategories,
            title: {
                text: data.xAxistitle 
            }
        },
        yAxis: {
            min: 0,
            max: 200,
            tickInterval: 50,
            title: {
                text:data.yAxistitle 
            } 
        },
         
        tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">{series.name}</span>: <b>%{point.y:.2f}</b><br/>'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    format: '%{point.y:.2f}'
                }
            }
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

function barChartCross(filterdata,data,chartdivid) {
    
    $('#'+chartdivid).highcharts({
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