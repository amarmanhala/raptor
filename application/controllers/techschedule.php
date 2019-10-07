<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
/**
 * Project: Raptor
 * Package: CI
 * Subpackage: Controllers\Jobs
 * File: Jobs.php
 * Description: This is a Tech Schedule class for manage Schedule for Company/Customer 
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */

class Techschedule extends MY_Controller {
 
   
    function __construct()
    {
        parent::__construct();

        
        //load custom class library
        $this->load->library('job/JobClass');
        $this->load->library('schedule/ScheduleClass');
        $this->load->library('contractor/ContractorClass');
  
    }

    /**
    * @desc This function use for load schedule calender for logged customer
    * @param none
    * @return void 
    */
    public function index()
    {
        //include custom css for this page
        $this->data['cssToLoad'] = array(
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/fullcalendar_n/fullcalendar.min.css'),
            array(
                'src'   => base_url('plugins/fullcalendar_n/fullcalendar.print.css'),
                'media' => 'print'
            )
        );
        
        //include custom js for this page
        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('assets/js/bootbox.min.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/fullcalendar_n/lib/moment.min.js'),
            base_url('plugins/fullcalendar_n/fullcalendar2.3.1.min.js'),
            base_url('plugins/fullcalendar_n/fullcalendar-columns.js'),
            base_url('assets/js/schedule/schedule.techschedule.js')
        );
  
        $this->data['calenderdate'] = date('Y-m-d', time());
       
        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['contracts'] = $this->contractorclass->getDashboardContracts($this->session->userdata('raptor_customerid'));
        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->session->userdata('raptor_customerid'));
        $this->data['sites'] =$this->customerclass->getCustomerSitesByRole($this->session->userdata('raptor_customerid'), $this->session->userdata('raptor_contactid'), $this->session->userdata('raptor_email'), $this->session->userdata('raptor_role'));

         //Load View in template file
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Tech Schedule')
            ->set_layout($this->layout)
            ->set('page_title', 'Tech Schedule')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Tech Schedule', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('schedule/techschedule', $this->data);
    }
      
    
    /**
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function getSites() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
           
            $state = $this->input->get('state'); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $data = $this->customerclass->getCustomerSitesByRole($this->session->userdata('raptor_customerid'), $this->session->userdata('raptor_contactid'), $this->session->userdata('raptor_email'), $this->session->userdata('raptor_role'), 0, $state); 
                
                 
                $success->setData($data);
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
    * @desc This function use for get schedules for calender
    * @param none
    * @return json
    */
    public function getSchedules() {
        
        //validate for ajax request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
          
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) {
         
                
                $customerid = $this->session->userdata('raptor_customerid');
                $start = $this->input->post('start');
                $end = $this->input->post('end');
                $sitestate = $this->input->post('state');
                $labelids = $this->input->post('site');
                $contract = $this->input->post('contract');
                $technicians = $this->input->post('technicians');
               
                
                $scheduleData = $this->scheduleclass->getSchedules($customerid, $start, $end, $sitestate, $technicians, $contract, $labelids);
                //$calendertechmulti = $this->scheduleclass->getTechnicians($customerid, $start, $end, $sitestate, $technicians, $contract);
                 $calendertechmulti = $this->scheduleclass->getTechnicians($customerid);
                $this->data['technicians'] = array();//$this->scheduleclass->getScheduleTechnicians($this->session->userdata('raptor_customerid'), $this->session->userdata('raptor_contactid'));

                foreach ($scheduleData as $value) {

                    $jobcolor = '';
                    if($value['completed'] == 'on'){
                       $jobcolor = 'blue';
                    }
                    else if($value['inprogress'] == 'on' && strtotime($value['duedate'])< time()){
                        $jobcolor = 'red';
                    }
                    else if($value['inprogress'] == 'on'){
                        $jobcolor = 'green';
                    }
                    else{
                       $jobcolor = 'teal';
                    }
                    $column = 0;
                    if (count($calendertechmulti) > 0) {
                        foreach ($calendertechmulti as $key => $tech) {
                            if ($tech['userid'] == $value['userid']) {
                                $column = $key;
                                break;
                            }
                        }
                    }
 
                    $data[] = array(
                        "id"             => $value['apptid'],
                        "title"         => $value['userid'],
                        "userid"         => $value['userid'],
                        "jobcolor"       => $jobcolor,
                        "start"          => $value['dte'].'T'.$value['start'],
                        "end"            => $value['dte'].'T'.$value['end'],
                        'duration'       => $value['duration'],
                        "inprogress"     => $value['inprogress'],
                        "completed"      => $value['completed'],
                        "jobid"          => $value['jobid'],
                        "custordref"     => $value['custordref'],
                        "siteline1"      => $value['sitesuburb'] .' - '. $value['sitestate'], 
                        "column"         => $column, 
                        "contractid"     => $value['contractid'],
                        "custordref1_label"     => isset($this->data['ContactRules']["custordref1_label"]) ? $this->data['ContactRules']["custordref1_label"]:'Order Ref 1'
                    );
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
    * @desc This function use for get timesheet for selected contact
    * @param none
    * @return json
    */    
    public function timeSheetSchedule() {
        
        //check ajax request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
         
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $date = $this->input->post('activedate');
            $contactid = $this->input->post('contactid');
            
            if ( !isset($contactid) )
                $message = 'contactid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                //get timesheet data for selected contactid
                $timesheetData = $this->scheduleclass->getTimeSheet($contactid, $date);
                
                //format data
                foreach ($timesheetData as $key => $value) {
                    
                    $timesheetData[$key]['durmins'] = number_format(($value['duration']*60), 2);
                  
                    $timesheetData[$key]['dtetimestamp'] = strtotime($value['dte']);
                }
                $data = $timesheetData;
                $success->setData($data); 
                $success->setTotal(count($data));
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
    * @desc This function use for export time sheet (excel)
    * @param none
    * @return void
    */       
    public function exportTimesheet($contactid, $datetimestamp) {

         $date = date('Y-m-d', $datetimestamp);
         $timesheet = $this->scheduleclass->getTimeSheet($contactid, $date);
         $data_array=array();
 
         $heading=array('Date', 'Apptid', 'Job ID', isset($this->data['ContactRules']["custordref1_label"]) ? $this->data['ContactRules']["custordref1_label"]:'Order Ref 1', 'Sitesuburb','Start','Duration','Durmins');
         $this->load->library('excel');

         $name = 'timesheet';
         foreach ($timesheet as $row1)
         { 
             $result=array();

             $result[] = $row1['dte'];
             $result[] = $row1['apptid'];
             $result[] = $row1['jobid'];
             $result[] = $row1['custordref'];
             $result[] = $row1['sitesuburb'];
             $result[] = $row1['start'];
             $result[] = $row1['duration'];
             $result[] = number_format(($row1['duration']*60), 2);;
             
             $name = $row1['firstname'];
             $data_array[]=$result;
         }

         $dir = "./temp";
         if (!is_dir($dir))
         {
                 mkdir($dir, 0755, true);
         }

         $file_name="timesheet.xls";

         $this->excel->getDefaultStyle()->getAlignment()->setWrapText(true);
         $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(120);
         $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(14); 
         $style = array(
             'alignment' => array(
                 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
             )
         );

         $this->excel->getDefaultStyle()->applyFromArray($style);

         $this->excel->Exportexcel("Timesheet",$dir,$file_name,$heading,$data_array);
         $this->load->helper('download');
         $data = file_get_contents(base_url()."temp/".$file_name);
         force_download($name.'_timesheet.xls', $data);
    }
    
   
     
    
     
    
}