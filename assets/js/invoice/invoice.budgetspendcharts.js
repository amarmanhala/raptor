/* global base_url, parseFloat, bootbox */

var renderBudgetSpendChart = function(spendbudgettype, data) {
    
    if(spendbudgettype == 'glcode') {
        spendbudgettype = 'BUDGET SPEND BY GL ACCOUNT';
    } else {
        spendbudgettype = 'BUDGET SPEND BY SITE';
    }
    
    $('#budgetSpendChart').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false
        },
        title: {
            text: spendbudgettype
        },
        xAxis: {
            categories: data.categories 
        },
        yAxis: {
            min: 0,
            /*title: {
                text: 'Total fruit consumption'
            },*/
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: data.series
    });
};

//['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
/*[{
       name: 'John',
       data: [5, 3, 4, 7, 2]
   }, {
       name: 'Jane',
       data: [2, 2, 3, 2, 1]
   }, {
       name: 'Joe',
       data: [3, 4, 4, 2, 5]
   }]*/
