
<button id='b'>Run Code</button>
<div id="container"></div>
<script src="assets/js/jquery-1.8.2.min.js"></script>
<script>
    $(function () {
    $("#b").click(testPOST);
    
    var exportUrl = 'http://export.highcharts.com/';

    function testPOST() {
        
        var optionsStr = JSON.stringify({
            chart: {
              type: 'pie'  
            },
            
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        distance: -30,
                        color:'white'
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [{
                    name: 'Microsoft Internet Explorer',
                    y: 56.33
                    }, {
                        name: 'Chrome',
                        y: 24.03,
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Firefox',
                        y: 10.38
                    }, {
                        name: 'Safari',
                        y: 4.77
                    }, {
                        name: 'Opera',
                        y: 0.91
                    }, {
                        name: 'Proprietary or Undetectable',
                        y: 0.2
                }]
            }]
            /*xAxis: {
                categories: ["Jan", "Feb", "Mar"]
            },
            series: [{
                data: [29.9, 71.5, 106.4]
            }]*/
        });
        //dataString = encodeURI('async=true&type=jpeg&width=400&options=' + optionsStr);
        dataString = 'async=true&type=jpeg&width=400&options=' + optionsStr;
        //console.log(dataString);
        //return false;

            $.ajax({
                type: 'POST',
                data: dataString,
                url: exportUrl,
                success: function (data) {
                    console.log('get the file from relative url: ', data);
                    $('#container').html('<img src="' + exportUrl + data + '"/>');
                },
                error: function (err) {
                    debugger;
                    console.log('error', err.statusText)
                }
            });
  

    }
});
</script>    
<?php

    
    
        $optionStr = array(
                'chart'=> array(
                    'type'=> 'pie'
                ),
                'title'=> array(
                    'text'=> ''
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'plotOptions' => array(
                    'pie' => array(
                        'allowPointSelect' => TRUE,
                        'cursor' => 'pointer',
                        'dataLabels' => array(
                            'enabled' => FALSE
                        ),
                        'showInLegend' => TRUE
                    )
                ),
                'legend' => array(
                    'layout' => 'horizontal',
                    'align' => 'bottom',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'bottom',
                    'borderWidth' => 0,
                ),
                'series'=> array(array(
                    'data'=> array(array(
                        'name'=> 'Microsoft Internet Explorer',
                        'y'=> 56.33
                    ), array(
                        'name'=> 'Chrome',
                        'y'=> 24.03
                    ), array(
                        'name'=> 'Firefox',
                        'y'=> 10.38
                    ), array(
                        'name'=> 'Safari',
                        'y'=> 4.77
                    ), array(
                        'name'=> 'Opera',
                        'y'=> 0.91
                    ), array(
                        'name'=> 'Proprietary or Undetectable',
                        'y'=> 0.2
                    ))
                ))
            );
            
            //print_r($optionStr);
            $optionStr = json_encode($optionStr);

    
    /*$dataString = 'async=true&type=jpeg&width=640&options=' . $optionStr;
    $exportUrl = 'http://export.highcharts.com/';
    
    //echo $dataString;
    //exit;
    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    
    curl_setopt($ch,CURLOPT_URL,$exportUrl);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //execute post
    $result = curl_exec($ch);
    $success = true;
    if(curl_error($ch))
    {
        echo 'error:' . curl_error($ch);
        $success = false;
    }
    //close connection
    curl_close($ch);
    
    if($success) {
        $url = $exportUrl.$result;
        echo $url;
        echo '<img src="' .$url . '"/>';
        $path_parts = pathinfo($url);
        $filename = $path_parts['basename'];
        file_put_contents($filename, file_get_contents($url));
        //print_r($result);
    }*/
    
   

    
?>