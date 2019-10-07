
/* global google, bootbox, base_url */

'use strict';
google.charts.load('current', {packages:["orgchart"]});


function drawChart(data) {
  

  // For each orgchart box, provide the name, manager, and tooltip to show.
//  data.addRows([
//    [{v:'Mike', f:'Mike<div style="color:red; font-style:italic">President</div>'},
//     '', 'The President'],
//    [{v:'Jim', f:'Jim<div style="color:red; font-style:italic">Vice President</div>'},
//     'Mike', 'VP'],
//    ['Alice', 'Mike', ''],
//    ['Bob', 'Jim', 'Bob Sponge'],
//    ['Carol', 'Bob', '']
//  ]);
     
  // Create the chart.
  var chart = new google.visualization.OrgChart(document.getElementById('orgchart_div'));
  // Draw the chart, setting the allowHtml option to true for the tooltips.
  var options = {
          tooltip: {isHtml: true},
          allowHtml:true 
        };
  chart.draw(data, options);
}

var showOrgChart = function (customerid){
    
    //google.charts.setOnLoadCallback(drawChart);
    $("#OrgChartModal").modal();
    $("#OrgChartModal #loading-img").show();
    $("#OrgChartModal #sitegriddiv").hide();
    $.get( base_url+"customers/loadorgcontacts", {'customerid':customerid }, function( response ) {
        if(response.success){
            
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');
            //data.addColumn({type: 'string', role: 'tooltip'});
            var chartRows = [];
            var ContactData = response.data;
       
            
            var count = 0;
            $.each(ContactData, function( key, val ) {
                if(count === 0){
                    chartRows.push([{v:'0', f:val.companyname}, '', val.companyname]);
                }
                if(val.position === null){
                    val.position = '';
                }
                var displayText ='<div style="font-size:12px;"><b>'+val.firstname+'</b></div><div style="font-size:12px;">'+val.position+'</div>';
         
                if(val.mobile!== ''){
                    displayText = displayText +'<div style="font-size:11px;"><i class="fa fa-mobile"></i>&nbsp;'+val.mobile+'</div>';
                }
                else{
                    if(val.phone!== ''){
                        displayText = displayText +'<div style="font-size:11px;"><i class="fa fa-phone"></i>&nbsp;'+val.phone+'</div>';
                    }
                }
                if(val.bossid === null || val.bossid === '0'){
                    chartRows.push([{v:val.contactid, f:displayText},  '0', displayText]);
                }
                else{
                    chartRows.push([{v:val.contactid, f:displayText},  val.bossid, displayText]);
                }
                 
                count = count + 1;
                        
            });
            
            data.addRows(chartRows);
            var count = 0;
            $.each(ContactData, function( key, val ) {
                if(count === 0){
                   data.setRowProperty(0, 'style', 'font-size:20px;'); 
                }
                count = count + 1;
               
                if(val.orgchart_color !== null || val.orgchart_color !== ''){
                    data.setRowProperty(count, 'style', 'background-color:'+ val.orgchart_color); 
                }
                        
            });
            $("#OrgChartModal #loading-img").hide();
            $("#OrgChartModal #sitegriddiv").show();
            drawChart(data);
            $('.google-visualization-orgchart-node').tooltip({
                html: true,
                container: 'body',
                placement:'auto bottom'
            });
        }
        else{
            bootbox.alert(response.message);
        }
         
    }, 'json');
    
};