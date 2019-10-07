<?php 
/**
 * reports Controller Class
 *
 * This is a reports controller class  
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * reports Controller Class
 *
 * This is a reports controller class  
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            reports
 * @filesource          reports.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Reports extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
        
        //  Load libraries 
        $this->load->library('report/ReportClass');
    }

    /**
    * This function use for show Report
    * 
    * @return void 
    */
    public function index()
    {
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/reports/reports.index.js')
        );
        
        $customerid =$this->session->userdata('raptor_customerid');
        $contactid = $this->session->userdata('raptor_contactid');
        
        $this->data['periods']=getPeriods(RAPTOR_DISPLAY_DATEFORMAT);
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Reports')
            ->set_layout($this->layout)
            ->set('page_title', 'My Reports')
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Reports', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('reports/index', $this->data);
        
    }
    
    /**
    * This function use for load reports in uigrid
    * 
    * @return json 
    */
    public function loadReports() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;
        try
        { 
            $message = '';
            $isSuccess = TRUE; 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //default settings for uigrid
                $page = 1;
                $size = 25;
                $order = '';
                $field = '';
                $params = array();
                
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }

                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Report Data
                $reportData = $this->reportclass->getReports($size, $start, $field, $order);
        
                $trows = $reportData['trows'];
                $data = $reportData['data'];
                
                $success -> setData($data);
                $success -> setTotal($trows);
                
            }
        }
        catch( Exception $e ) {
            
            $success = SuccessClass::initialize(FALSE);
            $message = $e->getMessage();
            $message = $message . " - " . $e->getTraceAsString(); 
             //log the exception
            $this->logClass->log("exception : ", $message);
            $code = SuccessClass::$CODE_EXCEPTION_OCCURED;
        }

        //set the variables
        $success -> setMessage($message);
        $success -> setCode($code);
        $success -> setData($data);
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    
    
    /**
    * This function use for generate reports
    * @param string $reportname - it is use for report name
    * @return pdf 
    */
    public function generateReports() {
        
        $controller = $this->uri->segment(1);
        $function = $this->uri->segment(2);
        $reportfamily = $this->uri->segment(3);
     
        $report = $this->uri->segment(4);
   

        if(trim($reportfamily) == '' && trim($report) == '') {
            show_404();
        }
        
        $fromdate = $this->input->get('fromdate');
        $todate = $this->input->get('todate');
        $rp = json_decode(rawurldecode($this->input->get('rp')), TRUE);
        
        if(!is_array($rp)) {
            $rp = array();
        }
        
        $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT);
        $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);

        $request = array(
            'fromdate'          => $fromdate,
            'todate'            => $todate,
            'report'            => $report,
            'rp'                => $rp,
            'open'              => true,
            'logged_contactid'  => $this->data['loggeduser']->contactid
        );

        //generate pdf and show it
        $this->reportclass->createPDF($request);
    }
    
    /**
    * This function use for process report data for chart images
    * 
    * @return json 
    */
    public function processReportData() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        $this->session->set_userdata('chartReportImages', array());

        try
        {
            $fromdate = $this->input->get('fromdate');
            $todate = $this->input->get('todate');
            $reportroute = trim($this->input->get('reportroute'));
            
            if( !isset($fromdate) || !isset($todate) || !isset($reportroute)) {
                $message = 'fromdate and todate and reportroute cannot be null';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT);
                $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
                
                if($reportroute == 'extensionsummary') {
                    $response = $this->extensionSummaryReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'ontimeattendance') {
                    $response = $this->onTimeAttendanceReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'jobcounts') {
                    $response = $this->jobCountsReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'salehistory') {
                    $response = $this->saleHistoryReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'quoteconversion') {
                    /*$date = DateTime::createFromFormat("Y-m-d", $fromdate);
                    $fY = $date->format("Y");
                    $date = DateTime::createFromFormat("Y-m-d", $todate);
                    $tY = $date->format("Y");
                    
                    if($fY == $tY) {
                        $tY = (int)$fY+1;
                    }
                    
                    $fromdate = $fY.'-06-01';
                    $todate = $tY.'-05-31';*/
                    
                    $response = $this->quoteConversionReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'workcompletion') {
                    $response = $this->workCompletionReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'jobextensions') {
                    $response = $this->jobExtensionReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'ontimecompletion') {
                    $response = $this->onTimeCompletionReport($fromdate, $todate);
                    $data = $response;
                }
                if($reportroute == 'reconciliation') {
                    $data = array();
                }
                if($reportroute == 'extensiondetail') {
                    $data = array();
                }
                
                
            }
        }
        catch( Exception $e )
        {
            $success = SuccessClass::initialize(FALSE);
            $message = $e->getMessage();
            $message = $message . " - " . $e->getTraceAsString(); 
             //log the exception
            $this->logClass->log("exception : ", $message);
            $code = SuccessClass::$CODE_EXCEPTION_OCCURED;
        }

        //set the variables
        $success -> setMessage($message);
        $success -> setCode($code);
        $success -> setData($data);
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    /**
    * This function use for curl request for getting chart image
    * @param string $url -  url
    * @param string $dataString - data string
    * @return String 
    */
    private function curlPost($url, $dataString) {
        
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //execute post
        $result = curl_exec($ch);
        $success = true;
        if(curl_error($ch))
        {
            return array('curlError' => TRUE, 'message' => curl_error($ch));
            $success = false;
        }
        //close connection
        curl_close($ch);

        if($success) {
            return array('curlError' => FALSE, 'message' => $result);
        }
    }
    
    /**
    * This function use for generate chart images for extension summary report
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function extensionSummaryReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $extensionByMonth = $this->reportclass->getExtensionByMonth($this->data['loggeduser']->customerid, $fromdate, $todate);
                
        foreach($extensionByMonth as $key=>$value) {
            $chartXAxis = array();
            $chartSeries = array(
                array(
                    'name' => 'Extended',
                    'data' => array()
                ),
                array(
                    'name' => 'Not Extended',
                    'data' => array()
                )
            );
            $chartSeriesPercentage = array(
                array(
                    'name' => 'Extended',
                    'data' => array()
                ),
                array(
                    'name' => 'Not Extended',
                    'data' => array()
                )
            );
            foreach($value as $key1=>$value1) {
                array_push($chartXAxis, date('M', mktime(0, 0, 0, $value1['m'], 10)));
                array_push($chartSeries[0]['data'], (int)$value1['extended']);
                array_push($chartSeries[1]['data'], (int)$value1['notextended']);

                //for percentage
                array_push($chartSeriesPercentage[0]['data'], $value1['extended']/$value1['totaljobs']*100);
                array_push($chartSeriesPercentage[1]['data'], $value1['notextended']/$value1['totaljobs']*100);
            }

            $optionStr = array(
                'chart' => array(
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">No. of Job Extended</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $chartOptions = array();
            $chartOptions[] = $optionStr;

            // % report
            $optionStr['title']['text'] = "<b style='font-family:Helvetica;font-size:10px;'>Job Extended Percentage</b>";
            $optionStr['series'] = $chartSeriesPercentage;
            $chartOptions[] = $optionStr;

            array_push($chartReportImages, array('key' => $key, 'values' => array()));
            foreach($chartOptions as $chartOpt) {
                $chartOpt = json_encode($chartOpt);
                $dataString = 'async=true&type=jpeg&width=640&options=' . $chartOpt;
                $url = 'http://export.highcharts.com/';

                $response = $this->curlPost($url, $dataString);

                if($response['curlError']) {
                    echo $response['message'];
                } else {
                    $file = $response['message'];
                    $path_parts = pathinfo($file);
                    $filename = $path_parts['basename'];

                    $dir = "./temp";
                    if (!is_dir($dir))
                    {
                        mkdir($dir, 0755, TRUE);
                    }

                    file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                    array_push($chartReportImages[$key]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
                }
                sleep(1);
            }
        }
        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for on tim attendance reprot
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function onTimeAttendanceReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $onTimeAttendance = $this->reportclass->getOnTimeAttendance($this->data['loggeduser']->contactid, $fromdate, $todate);
         
        $chartXAxis = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($chartXAxis, date('M', mktime(0, 0, 0, $i, 10)));
        }
        $chartSeries = array();
        foreach($onTimeAttendance as $key=>$value) {
            
            array_push($chartSeries, array(
                'name' => '',
                'data' => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
            ));
            
            foreach($value as $key1=>$value1) {
                $chartSeries[$key]['name'] = $value1['y'];
                $index = (int)$value1['m']-1;
                $chartSeries[$key]['data'][$index] = (int)$value1['otp'];
            }
        }
        
        if(count($onTimeAttendance) > 0) {
            array_push($chartReportImages, array('key' => 0, 'values' => array()));
             
            $optionStr = array(
                'chart' => array(
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">Percentage On Time</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $optionStr = json_encode($optionStr);
            $dataString = 'async=true&type=jpeg&width=640&options=' . $optionStr;
            $url = 'http://export.highcharts.com/';

            $response = $this->curlPost($url, $dataString);

            if($response['curlError']) {
                echo $response['message'];
            } else {
                $file = $response['message'];
                $path_parts = pathinfo($file);
                $filename = $path_parts['basename'];

                $dir = "./temp";
                if (!is_dir($dir))
                {
                    mkdir($dir, 0755, TRUE);
                }

                file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                array_push($chartReportImages[0]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
            }
        }
        
        
        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for job counts report
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function jobCountsReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $jobCounts = $this->reportclass->getJobCounts($this->data['loggeduser']->contactid, $fromdate, $todate);
        //echo '<pre>';
        //print_r($jobCounts);
        //exit;
         
        $chartXAxis = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($chartXAxis, date('M', mktime(0, 0, 0, $i, 10)));
        }
        $chartSeries = array();
        foreach($jobCounts as $key=>$value) {
            
            array_push($chartSeries, array(
                'name' => '',
                'data' => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
            ));
            
            foreach($value as $key1=>$value1) {
                $chartSeries[$key]['name'] = $value1['y'];
                $index = (int)$value1['m']-1;
                $chartSeries[$key]['data'][$index] = (int)$value1['jobcount'];
            }
        }
        
        if(count($jobCounts) > 0) {
            array_push($chartReportImages, array('key' => 0, 'values' => array()));
            $optionStr = array(
                'chart' => array(
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">Job Counts</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $optionStr = json_encode($optionStr);
            $dataString = 'async=true&type=jpeg&width=640&options=' . $optionStr;
            $url = 'http://export.highcharts.com/';

            $response = $this->curlPost($url, $dataString);

            if($response['curlError']) {
                echo $response['message'];
            } else {
                $file = $response['message'];
                $path_parts = pathinfo($file);
                $filename = $path_parts['basename'];

                $dir = "./temp";
                if (!is_dir($dir))
                {
                    mkdir($dir, 0755, TRUE);
                }

                file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                array_push($chartReportImages[0]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));   
            }
        }
        
        
        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for sale history report
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function saleHistoryReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $saleHistory = $this->reportclass->getSaleHistory($this->data['loggeduser']->contactid, $fromdate, $todate);
         
        $chartXAxis = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($chartXAxis, date('M', mktime(0, 0, 0, $i, 10)));
        }
        $chartSeries = array();
        foreach($saleHistory as $key=>$value) {
            
            array_push($chartSeries, array(
                'name' => '',
                'data' => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
            ));
            
            foreach($value as $key1=>$value1) {
                $chartSeries[$key]['name'] = $value1['y'];
                $index = (int)$value1['m']-1;
                $chartSeries[$key]['data'][$index] = (double)$value1['netval'];
            }
        }
        
        if(count($saleHistory) > 0) {
            array_push($chartReportImages, array('key' => 0, 'values' => array()));
            $optionStr = array(
                'chart' => array(
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">Sales Report</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $optionStr = json_encode($optionStr);
            $dataString = 'async=true&type=jpeg&width=640&options=' . $optionStr;
            $url = 'http://export.highcharts.com/';

            $response = $this->curlPost($url, $dataString);

            if($response['curlError']) {
                echo $response['message'];
            } else {
                $file = $response['message'];
                $path_parts = pathinfo($file);
                $filename = $path_parts['basename'];

                $dir = "./temp";
                if (!is_dir($dir))
                {
                    mkdir($dir, 0755, TRUE);
                }

                file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                array_push($chartReportImages[0]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
            }
        }
        
        
        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for quote conversion report
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function quoteConversionReport($fromdate, $todate) {
                
        $chartReportImages = array();
        
        $quoteConversion = $this->reportclass->getQuoteConversion($this->data['loggeduser']->contactid, $fromdate, $todate);
        
        foreach($quoteConversion as $key=>$value) {
            
            $chartXAxis = array();
            
            $chartSeries = array(
                array(
                    'name' => 'Accepted',
                    'data' => array()
                ),
                array(
                    'name' => 'Declined',
                    'data' => array()
                ),
                array(
                    'name' => 'To Submit',
                    'data' => array()
                ),
                array(
                    'name' => 'Waiting',
                    'data' => array()
                )
            );
            
            foreach($value as $key1=>$value1) {
                array_push($chartXAxis, date('M', mktime(0, 0, 0, $value1['m'], 10)));
                
                if($value1['accepted'] == 0) {
                    $value1['accepted'] = '';
                }
                if($value1['declined'] == 0) {
                    $value1['declined'] = '';
                }
                if($value1['submission'] == 0) {
                    $value1['submission'] = '';
                }
                if($value1['waiting'] == 0) {
                    $value1['waiting'] = '';
                }

                array_push($chartSeries[0]['data'], (int)$value1['accepted']);
                array_push($chartSeries[1]['data'], (int)$value1['declined']);
                array_push($chartSeries[2]['data'], (int)$value1['submission']);
                array_push($chartSeries[3]['data'], (int)$value1['waiting']);
            }

            $optionStr = array(
                'chart' => array(
                    'type' => 'column',
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">Current Quote Status by Original Period (Count)</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $chartOptions = array();
            $chartOptions[] = $optionStr;

            array_push($chartReportImages, array('key' => $key, 'values' => array()));
            foreach($chartOptions as $chartOpt) {
                $chartOpt = json_encode($chartOpt);
                $dataString = 'async=true&type=jpeg&width=640&options=' . $chartOpt;
                $url = 'http://export.highcharts.com/';

                $response = $this->curlPost($url, $dataString);

                if($response['curlError']) {
                    echo $response['message'];
                } else {
                    $file = $response['message'];
                    $path_parts = pathinfo($file);
                    $filename = $path_parts['basename'];

                    $dir = "./temp";
                    if (!is_dir($dir))
                    {
                        mkdir($dir, 0755, TRUE);
                    }

                    file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                    array_push($chartReportImages[$key]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
                }
                sleep(1);
            }
        }

        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for work completion report
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function workCompletionReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $workCompletion = $this->reportclass->getWorkCompletion($this->data['loggeduser']->contactid, $fromdate, $todate);

        $chartSeries = array(
            array(
                'data' => array(
                    array(
                        'name' => 'q',
                        'y' => 10
                    ),
                    array(
                        'name' => 'w',
                        'y' => 20
                    ),
                    array(
                        'name' => 'e',
                        'y' => 30
                    ),
                    array(
                        'name' => 'r',
                        'y' => 40
                    ),
                    array(
                        'name' => 't',
                        'y' => 50
                    ),
                    array(
                        'name' => 'y',
                        'y' => 60
                    ),
                    array(
                        'name' => 'u',
                        'y' => 70
                    ),
                    array(
                        'name' => 'i',
                        'y' => 70
                    ),
                    array(
                        'name' => 'd',
                        'y' => 80
                    ),
                    array(
                        'name' => 'r',
                        'y' => 90
                    )
                )
            )    
        );
        
        if(count($workCompletion) > 0) {
            
            $chartSeries[0]['data'][0]['name'] = 'Completed Before Target Date ('.$workCompletion['early'].')';
            $chartSeries[0]['data'][0]['y'] = (int)$workCompletion['early'];
            
            $chartSeries[0]['data'][1]['name'] = 'Completed On Time '.$workCompletion['ontime'].'';
            $chartSeries[0]['data'][1]['y'] = (int)$workCompletion['ontime'];
            
            $chartSeries[0]['data'][2]['name'] = 'Completed > 1 Day ('.$workCompletion['1day'].')';
            $chartSeries[0]['data'][2]['y'] = (int)$workCompletion['1day'];
            
            $chartSeries[0]['data'][3]['name'] = 'Completed > 2 Days ('.$workCompletion['2days'].')';
            $chartSeries[0]['data'][3]['y'] = (int)$workCompletion['2days'];
            
            $chartSeries[0]['data'][4]['name'] = 'Completed > 3 Days ('.$workCompletion['3days'].')';
            $chartSeries[0]['data'][4]['y'] = (int)$workCompletion['3days'];
            
            $chartSeries[0]['data'][5]['name'] = 'Completed 4-7 Days ('.$workCompletion['4_7days'].')';
            $chartSeries[0]['data'][5]['y'] = (int)$workCompletion['4_7days'];
            
            $chartSeries[0]['data'][6]['name'] = 'Completed 8-14 Days ('.$workCompletion['8_14days'].')';
            $chartSeries[0]['data'][6]['y'] = (int)$workCompletion['8_14days'];
            
            $chartSeries[0]['data'][7]['name'] = 'Completed 15-30 Days ('.$workCompletion['15_30days'].')';
            $chartSeries[0]['data'][7]['y'] = (int)$workCompletion['15_30days'];
            
            $chartSeries[0]['data'][8]['name'] = 'Completed 31-60 Days ('.$workCompletion['31_60days'].')';
            $chartSeries[0]['data'][8]['y'] = (int)$workCompletion['31_60days'];
            
            $chartSeries[0]['data'][9]['name'] = 'Completed > 60 Days ('.$workCompletion['g60days'].')';
            $chartSeries[0]['data'][9]['y'] = (int)$workCompletion['g60days'];

            array_push($chartReportImages, array('key' => 0, 'values' => array()));
            
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
                            'enabled' => FALSE,
                            'distance'=> -30,
                            'color'=>'white'
                        ),
                        'showInLegend' => TRUE
                    )
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'right',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => FALSE,
                    //'x' => 70,
                    //'y' =>30,
                    'verticalAlign' => 'middle',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $optionStr = json_encode($optionStr);
            $dataString = 'async=true&type=jpeg&width=540&options=' . $optionStr;
            $url = 'http://export.highcharts.com/';

            $response = $this->curlPost($url, $dataString);

            if($response['curlError']) {
                echo $response['message'];
            } else {
                $file = $response['message'];
                $path_parts = pathinfo($file);
                $filename = $path_parts['basename'];

                $dir = "./temp";
                if (!is_dir($dir))
                {
                    mkdir($dir, 0755, TRUE);
                }

                file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                array_push($chartReportImages[0]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
            }
        }
        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for job extension report
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function jobExtensionReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $jobExtension = $this->reportclass->getJobExtension($this->data['loggeduser']->contactid, $fromdate, $todate);

        foreach($jobExtension as $key=>$value) {
            $chartXAxis = array();
            $chartSeries = array(
                array(
                    'name' => 'Extended',
                    'data' => array()
                ),
                array(
                    'name' => 'Not Extended',
                    'data' => array()
                )
            );
            $chartSeriesPercentage = array(
                array(
                    'name' => 'Extended',
                    'data' => array()
                ),
                array(
                    'name' => 'Not Extended',
                    'data' => array()
                )
            );
            foreach($value as $key1=>$value1) {
                array_push($chartXAxis, date('M', mktime(0, 0, 0, $value1['m'], 10)));
                array_push($chartSeries[0]['data'], (int)$value1['extended']);
                array_push($chartSeries[1]['data'], (int)$value1['notextended']);

                //for percentage
                array_push($chartSeriesPercentage[0]['data'], $value1['extended']/$value1['totaljobs']*100);
                array_push($chartSeriesPercentage[1]['data'], $value1['notextended']/$value1['totaljobs']*100);
            }

            $optionStr = array(
                'chart' => array(
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">No. of Job Extended</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $chartOptions = array();
            $chartOptions[] = $optionStr;

            // % report
            $optionStr['title']['text'] = "<b style='font-family:Helvetica;font-size:10px;'>Job Extended Percentage</b>";
            $optionStr['series'] = $chartSeriesPercentage;
            $chartOptions[] = $optionStr;

            array_push($chartReportImages, array('key' => $key, 'values' => array()));
            foreach($chartOptions as $chartOpt) {
                $chartOpt = json_encode($chartOpt);
                $dataString = 'async=true&type=jpeg&width=640&options=' . $chartOpt;
                $url = 'http://export.highcharts.com/';

                $response = $this->curlPost($url, $dataString);

                if($response['curlError']) {
                    echo $response['message'];
                } else {
                    $file = $response['message'];
                    $path_parts = pathinfo($file);
                    $filename = $path_parts['basename'];

                    $dir = "./temp";
                    if (!is_dir($dir))
                    {
                        mkdir($dir, 0755, TRUE);
                    }

                    file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                    array_push($chartReportImages[$key]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
                }
                sleep(1);
            }
        }
        return $chartReportImages;
    }
    
    /**
    * This function use for generate chart images for on time completion reprot
    * @param string $params - required parameters for report
    * @return Json 
    */
    private function onTimeCompletionReport($fromdate, $todate) {
                
        $chartReportImages = array();
        $onTimeCompletion = $this->reportclass->getOnTimeCompletion($this->data['loggeduser']->contactid, $fromdate, $todate);
         
        $chartXAxis = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($chartXAxis, date('M', mktime(0, 0, 0, $i, 10)));
        }
        $chartSeries = array();
        foreach($onTimeCompletion as $key=>$value) {
            
            array_push($chartSeries, array(
                'name' => '',
                'data' => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
            ));
            
            foreach($value as $key1=>$value1) {
                $chartSeries[$key]['name'] = $value1['y'];
                $index = (int)$value1['m']-1;
                $chartSeries[$key]['data'][$index] = (int)$value1['otp'];
            }
        }
        
        if(count($onTimeCompletion) > 0) {
            array_push($chartReportImages, array('key' => 0, 'values' => array()));
             
            $optionStr = array(
                'chart' => array(
                    'style' => array(
                        'fontFamily' => 'Helvetica', 
                        'fontSize' => '10px',
                        'fontWeight' => 'bold'
                    )
                ),
                'xAxis' => array(
                    'categories' => $chartXAxis
                ),
                'title' => array(
                    'text' => '<b style="font-family:Helvetica;font-size:10px;">Percentage On Time</b>',
                    'useHTML' => true
                ),
                'credits' => array(
                    'enabled' => FALSE
                ),
                'legend' => array(
                    'layout' => 'vertical',
                    'align' => 'left',
                    'itemStyle' => array(
                        'fontSize'=> '10px;'
                    ),
                    'floating' => TRUE,
                    'x' => 70,
                    'y' =>30,
                    'verticalAlign' => 'top',
                    'borderWidth' => 0,
                ),
                'series' => $chartSeries
            );

            $optionStr = json_encode($optionStr);
            $dataString = 'async=true&type=jpeg&width=640&options=' . $optionStr;
            $url = 'http://export.highcharts.com/';

            $response = $this->curlPost($url, $dataString);

            if($response['curlError']) {
                echo $response['message'];
            } else {
                $file = $response['message'];
                $path_parts = pathinfo($file);
                $filename = $path_parts['basename'];

                $dir = "./temp";
                if (!is_dir($dir))
                {
                    mkdir($dir, 0755, TRUE);
                }

                file_put_contents($dir.'/'.$filename, file_get_contents($url.$file));
                array_push($chartReportImages[0]['values'], array('file' => $path_parts['basename'], 'ext' =>$path_parts['extension']));  
            }
        }
        
        
        return $chartReportImages;
    }
}