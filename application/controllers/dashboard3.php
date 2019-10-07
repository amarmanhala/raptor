<?php 
/**
 * Dashboard Controller Class
 *
 * This is a dashboard controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller Class
 *
 * This is a dashboard controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Dashboard3
 * @filesource          Dashboard3.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class Dashboard3 extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();

        //  Load libraries 
        $this->load->library('job/JobClass');
        $this->load->library('contractor/ContractorClass');
    }

    /**
    * This function use for show Job Dashboard
    * 
     * @return void 
    */
    public function index()
    {

        $un= $this->session->userdata('raptor_email'); 
        $contactid =$this->session->userdata('raptor_contactid');
        $customerid =$this->session->userdata('raptor_customerid');

        $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid,'role'=>'sitefm'),'contactid, firstname');

        $this->data['periods']=getPeriods(RAPTOR_DISPLAY_DATEFORMAT);

        $this->data['states'] = $this->sharedclass->getStates(1);
        
        $this->data['contracts'] = $this->contractorclass->getDashboardContracts($customerid);

        $this->data['cssToLoad'] = array(
            base_url('plugins/uigrid/ui-grid-stable.min.css'),
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/select2/select2.min.css') 
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/select2/select2.full.min.js'), 
            base_url('plugins/highcharts/js/highcharts.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/dashboard/dashboard.labour.js')
        );

        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Dashboard - Labour')
            ->set_layout($this->layout)
            ->set('page_title', 'Dashboard - Labour')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Dashboard - Labour', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('dashboard/labour-dashboard', $this->data);

    }
    
    /**
    * This function use for load labour dashboard data
    * 
    * @return json 
    */
    public function loadPageData() {
        
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
 
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                
                $fromdate = trim($this->input->get_post('fromdate'));
                $todate = trim($this->input->get_post('todate'));
                $state = trim($this->input->get_post('state'));
                
                $technicians = $this->contractorclass->getTechnicians($customerid, $fromdate, $todate);
                $sites = $this->contractorclass->getLabourDashboardSites($customerid, $fromdate, $todate, $state);
                      
                $data = array(
                    'technicians' => $technicians,
                    'sites' => $sites
                );
                
                $success -> setData($data);
                $success -> setTotal(count($data));
                
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
    * This function use for load labour dashboard data in uigrid
    * 
    * @return json 
    */
    public function loadLabourDashboardData() {
        
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
                
                $fromdate = trim($this->input->get_post('fromdate'));
                $todate = trim($this->input->get_post('todate'));
                
                $manager = trim($this->input->get_post('manager'));
                $state = trim($this->input->get_post('state'));
                $site = trim($this->input->get_post('site'));
                $contract = trim($this->input->get_post('contract'));
                
                if ($manager != NULL) {
                    $params['j.contactid'] = $manager;
                }
                
                if ($state != NULL) {
                    $params['a.sitestate'] = $state;
                }
                
                if ($site != NULL) {
                    $params['a.labelid'] = $site;
                }
                
                if ($contract != NULL) {
                    $params['j.contractid'] = $contract;
                }
                
                $groupby = trim($this->input->get_post('groupby'));
            
                if($fromdate == NULL || $todate == '' ){
                    $firstDateFormat = str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT);
                    $fromdate = date($firstDateFormat);
                }
                else{
                    $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if($todate == NULL || $todate == '' ){
                    $firstDateFormat = str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT);
                    $todate = date($firstDateFormat);
                }
                else{
                    $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                $dashboardData = $this->contractorclass->getLabourDashboardData($customerid, $size, $start, $field, $order, $fromdate, $todate, $groupby, $params);

                //create dynamic columns for uigrid
                $columns = $this->createLabourGridColumns($groupby);

                $trows = $dashboardData['trows'];
                $data = $dashboardData['data'];
                
                $chartData = array();
                
                //get jobstatus
                /*$jobCountByStatusData = $this->jobclass->getJobStageCount($this->data['loggeduser']->contactid, $fromdate, $todate, $manager, $state, $site);
                
                foreach ($jobCountByStatusData as $key => $value) {
                //foreach ($data as $key => $value) {
                    $chartData[]  = array('name'=> $value['stage'] .' ('.(int)$value['count'].')','y'=>(int)$value['count']);
                    
                    if($groupby == 'bysite') {
                        //$chartData[] = array('name'=> $value['siteline2'], 'y'=>(int)$value['hours']);
                    }
                    
                }*/
                
                $data = array(
                    'chartData' => $chartData,
                    'data' => $data,
                    'columns' => $columns
                );
                
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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportDashboardData() {
        
        $order = '';
        $field = '';
        $params = array();

        //get customerid
        $customerid =$this->session->userdata('raptor_customerid');

        $fromdate = trim($this->input->get_post('fromdate'));
        $todate = trim($this->input->get_post('todate'));

        $manager = trim($this->input->get_post('manager'));
        $state = trim($this->input->get_post('state'));
        $site = trim($this->input->get_post('site'));
        $contract = trim($this->input->get_post('contract'));

        if ($manager != NULL) {
            $params['j.contactid'] = $manager;
        }

        if ($state != NULL) {
            $params['a.sitestate'] = $state;
        }

        if ($site != NULL) {
            $params['a.labelid'] = $site;
        }

        if ($contract != NULL) {
            $params['j.contractid'] = $contract;
        }

        $groupby = trim($this->input->get_post('groupby'));

        if($fromdate == NULL || $todate == '' ){
            $firstDateFormat = str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT);
            $fromdate = date($firstDateFormat);
        }
        else{
            $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT);
        }

        if($todate == NULL || $todate == '' ){
            $firstDateFormat = str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT);
            $todate = date($firstDateFormat);
        }
        else{
            $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
        }

        $dashboardData = $this->contractorclass->getLabourDashboardData($customerid, NULL, 0, $field, $order, $fromdate, $todate, $groupby, $params);

        //create dynamic columns for uigrid
        $columns = $this->createLabourGridColumns($groupby);

        $trows = $dashboardData['trows'];
        $data = $dashboardData['data'];

        $data_array = array();

        $this->load->library('excel');
        
         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->getProperties()->setCreator("DCFM")
			     ->setLastModifiedBy("DCFM")
			     ->setTitle("Labour Dashboard")
			     ->setSubject("DCFM Client Portal :: Labour Dashboard")
		   	     ->setDescription("DCFM Client Portal :: Labour Dashboard")
			      ->setKeywords('Labour Dashboard')
		      	     ->setCategory('Labour Dashboard');
        $this->excel->getActiveSheet()->setTitle('Labour Dashboard');
        
        //Loop Heading
        $rowNo = 1;
        $colH1 = 'A';
        $colH = 'A';
        $heading = array();
        foreach ($columns as $key => $value) {  
            $t = 0;
            
            $t++;
            $heading[] = '';
            $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(18); 
            $colH++;
            
            if($t > 0) {
                
                $this->excel->getActiveSheet()->setCellValue($colH1.$rowNo, $value['displayfield']);
                
                $colH2 = $colH1;
                for($i = 1; $i< $t; $i++){
                    $colH2++;
                }
                
                $colH1 = $colH;
            }
           
        }
       
        $rowNo++;
        $colH = 'A';
        foreach($heading as $h){
            $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $h);
            $colH++;    
        }
        
        //Loop Result
        $rowNo = 2;
        foreach($data as $row1) {
            
            $colH = 'A';
            foreach ($columns as $key2 => $value) {  
                
                $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1[$value['name']]);
                $colH++;
            } 
            $rowNo++;
        }

        $colH1--;
        
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.'1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'CCCCCC')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            )
        );

        //Cell Style
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

       
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.($trows+2))->applyFromArray($styleArray);
        //Save as an Excel BIFF (xls) file
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel,'Excel5');
        
        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="labour_dashboard.xls";
 
        $objWriter->save($dir.'/'.$file_name);

        $this->load->helper('download');
        $filedata = file_get_contents(base_url()."temp/".$file_name);
        force_download('labour_dashboard.xls', $filedata);
    }
   
    
    /**
    * This function create dynamic columns for uigrid
    * @param array $month - array of month numbers
    * @param integer $year - year
    * @param string show - day/month/week data
    * 
    * @return array 
    */
    private function createLabourGridColumns($groupby) {

        $columns = array();
        
        if($groupby == 'bysite') {
            $c = array(
                array(
                   'displayfield' => 'Site Address',
                    'name' => 'siteline2' 
                ),
                array(
                   'displayfield' => 'Site Ref',
                    'name' => 'siteref' 
                ),
                array(
                   'displayfield' => 'State',
                    'name' => 'state' 
                ),
                array(
                   'displayfield' => 'FM',
                    'name' => 'fm' 
                ),
                array(
                   'displayfield' => 'Jobs',
                    'name' => 'jobs' 
                ),
                array(
                   'displayfield' => 'Labour Hrs',
                    'name' => 'hours' 
                ),
                array(
                   'displayfield' => 'Labour $',
                    'name' => 'rate' 
                ),
                array(
                   'displayfield' => 'Material $',
                    'name' => 'materialcosts' 
                ),
                array(
                   'displayfield' => 'Total $',
                    'name' => 'billamt' 
                )
            );
            
            foreach($c as $k=>$v) {
                $c[$k]['site'] = 1;
                $c[$k]['tech'] = 0;
                $c[$k]['job'] = 0;
            }
            $columns = $c;
        }
        
        if($groupby == 'bytech') {
            $c = array(
                array(
                   'displayfield' => 'Technician',
                    'name' => 'technician' 
                ),
                array(
                   'displayfield' => 'Jobs',
                    'name' => 'jobs' 
                ),
                array(
                   'displayfield' => 'Labour Hrs',
                    'name' => 'hours' 
                ),
                array(
                   'displayfield' => 'Labour $',
                    'name' => 'rate' 
                ),
            );
            
            foreach($c as $k=>$v) {
                $c[$k]['site'] = 0;
                $c[$k]['tech'] = 1;
                $c[$k]['job'] = 0;
            }
            $columns = $c;
        }
        
        if($groupby == 'byjob') {
            $c = array(
                array(
                   'displayfield' => 'Jobid',
                    'name' => 'jobid' 
                ),
                array(
                   'displayfield' => 'Site Address',
                    'name' => 'siteline2' 
                ),
                array(
                   'displayfield' => 'Site Ref',
                    'name' => 'siteref' 
                ),
                array(
                   'displayfield' => 'State',
                    'name' => 'state' 
                ),
                array(
                   'displayfield' => 'FM',
                    'name' => 'fm' 
                ),
                array(
                   'displayfield' => 'Labour Hrs',
                    'name' => 'hours' 
                ),
                array(
                   'displayfield' => 'Labour $',
                    'name' => 'rate' 
                ),
                array(
                   'displayfield' => 'Material $',
                    'name' => 'materialcosts' 
                ),
                array(
                   'displayfield' => 'Total $',
                    'name' => 'billamt' 
                )
            );
            
            foreach($c as $k=>$v) {
                $c[$k]['site'] = 0;
                $c[$k]['tech'] = 0;
                $c[$k]['job'] = 1;
            }
            $columns = $c;
        }
        
        return $columns;
    }
}