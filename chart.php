
<button id='b'>Run Code</button>
<div id="container"></div>
<script src="assets/js/jquery-1.8.2.min.js"></script>
<script>
    $(function () {
    $("#b").click(testPOST);
    
    var exportUrl = 'http://export.highcharts.com/';

    function testPOST() {
        
        var optionsStr = JSON.stringify({
            "xAxis": {
                "categories": ["Jan", "Feb", "Mar"]
            },
                "series": [{
                "data": [29.9, 71.5, 106.4]
            }]
        }),
        //dataString = encodeURI('async=true&type=jpeg&width=400&options=' + optionsStr);
        dataString = 'async=true&type=jpeg&width=400&options=' + optionsStr;
        console.log(dataString);
        return false;

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
    /*$optionStr = array(
            'xAxis' => array(
                'categories' => array('Jan', 'Feb', 'Mar')),
            'series' => array(
                array(
                    'name' => 'japan',
                    'data' => array(19.9, 21.5, 56.4)
                ),
                array(
                    'name' => 'india',
                    'data' => array(29.9, 71.5, 106.4)
                )
            )
    );*/
    
    
        $optionStr = array(
            'chart' => array(
                'style' => array(
                    'fontFamily' => 'Helvetica', 
                    'fontSize' => '10px',
                    'fontWeight' => 'bold'
                )
            ),
            'xAxis' => array(
                'categories' => array('Jan', 'Feb', 'Mar')
            ),
            'title' => array(
                'text' => '<b style="font-family:Helvetica;font-size:10px;">No. of Job Extended</b>',
                'useHTML' => true
            ),
            'credits' => array(
                'enabled' => false
            ),
            'legend' => array(
                'layout' => 'vertical',
                'align' => 'left',
                'itemStyle' => array(
                    'fontSize'=> '10px;'
                ),
                'floating' => true,
                'x' => 70,
                'y' =>30,
                'verticalAlign' => 'top',
                'borderWidth' => 0,
            ),
            'series' => array(
                array(
                    'name' => 'japan',
                    'data' => array(19.9, 21.5, 56.4)
                ),
                array(
                    'name' => 'india',
                    'data' => array(29.9, 71.5, 106.4)
                )
            )
    );
    
    /*legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },*/
    
    $optionStr = json_encode($optionStr);
 
    
    $dataString = 'async=true&type=jpeg&width=640&options=' . $optionStr;
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
    }
    
   

    
?>