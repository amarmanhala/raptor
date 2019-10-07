<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
/**
 * Project: ETP Falcon
 * Package: CI
 * Subpackage: Controllers\Jobs
 * File: Jobs.php
 * Description: This is a Schedule class for manage Schedule for Company/Customer 
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */

class Schedule extends MY_Controller {
 
   
    function __construct()
    {
        parent::__construct();

        
        //load custom class library
        $this->load->library('job/JobClass');
        $this->load->library('schedule/ScheduleClass');
  
        $this->data['sidebaractive'] = 'schedule';
        $this->data['schedulelayout'] = $this->scheduleclass->getDefaultScheduleLayout($this->data['loggeduser']->contactid);
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
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/fullcalendar_n/lib/moment.min.js'),
            base_url('plugins/fullcalendar_n/fullcalendar2.3.1.min.js'),
            base_url('plugins/fullcalendar_n/fullcalendar-columns.js')
        );
        
        //include custom js for this page after all require theme js
        $this->data['viewJsToLoad'] = array(
            base_url('assets/js/schedule/schedule.index.js')
        );

        $this->data['job'] = array('jobid' => 0,'jobnumber' => '','statusid' => '','stage' => '');
  
        $this->data['calenderdate'] = date('Y-m-d', time());
        $this->data['createcalenderevent'] = 0;
        $this->data['createinternaltask'] = 1;
 
        $this->data['etp_activity'] = $this->scheduleclass->getActivity();
        $this->data['schedule_items'] = $this->scheduleclass->getScheduleItems($this->data['loggeduser']->customerid);
        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
        $this->data['internaljobs'] = array();//$this->jobclass->internalJobs($this->data['loggeduser']->customerid);

        
         //Load View in template file
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Schedules')
            ->set_layout($this->layout)  
            ->set('page_title', 'Schedule')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Schedule', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('schedule/index', $this->data);
    }
        
    /**
    * @desc This function use for load schedule calender for selected jobid and logged customer
    * @param none
    * @return void 
    */
    public function allocate($jobid) {

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
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/fullcalendar_n/lib/moment.min.js'),
            base_url('plugins/fullcalendar_n/fullcalendar2.3.1.min.js'),
            base_url('plugins/fullcalendar_n/fullcalendar-columns.js')
        );
        
        //include custom js for this page after all require theme js
        $this->data['viewJsToLoad'] = array(
            base_url('assets/js/schedule/schedule.index.js')
        );
           
        //Load Job Data
        $jobData = $this->jobclass->getJobById($jobid);
 
        if (count($jobData) > 0) {
            $location = array();
            if (trim($jobData['siteline1']) != '') {
                array_push($location, $jobData['siteline1']);
            }
            if (trim($jobData['siteline2']) != '') {
                array_push($location, $jobData['siteline2']);
            }
            if (trim($jobData['sitesuburb']) != '') {
                array_push($location, $jobData['sitesuburb']);
            }
            if (trim($jobData['sitestate']) != '') {
                array_push($location, $jobData['sitestate']);
            }
            if (trim($jobData['sitepostcode']) != '') {
                array_push($location, $jobData['sitepostcode']);
            }

            if (trim($jobData['se_trade_name']) == null) {
                $jobData['se_trade_name'] = '';
            }

            $jobData['location'] = implode(", ", $location);
            $jobData['sitecontact'] = $jobData['sitecontact'].' - ('.$jobData['sitephone'].')';
            $jobData['attendbydate'] = format_datetime($jobData['attendby'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $jobData['completebydate'] = format_datetime($jobData['completeby'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $jobData['startdate'] = format_datetime($jobData['startdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $jobData['finishdate'] = format_datetime($jobData['finishdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $jobData['responsedate'] = format_datetime($jobData['responsedate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
        }
           
        $this->data['job'] = $jobData;
        $this->data['calenderdate'] = date('Y-m-d', time());
        $this->data['createcalenderevent'] = 1;
        $this->data['createinternaltask'] = 0;
           
       
        $this->data['etp_activity'] = $this->scheduleclass->getActivity();
        $this->data['schedule_items'] = $this->scheduleclass->getScheduleItems($this->data['loggeduser']->customerid);
        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
        $this->data['internaljobs'] = array();//$this->jobclass->internalJobs($this->data['loggeduser']->customerid);
              
    
        $jobnumber= $jobData['jobnumber'] == NULL ? $jobData['jobid'] : $jobData['jobnumber'];
        //Load View in template file
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Schedules')
            ->set_layout($this->layout)
            ->set('page_title', 'Schedules')
            ->set('page_sub_title', 'Job allocate | Job '.$jobnumber)
            ->set_breadcrumb('Schedules', site_url('schedule'))
            ->set_breadcrumb('Allocate', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('schedule/index', $this->data);
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
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $technician = $this->input->post('technician');
            $technicians = $this->input->post('technicians');
            $timeslot = $this->input->post('timeslot');
            $view = $this->input->post('calenderview');
            $jobid = $this->input->post('jobid');
            
            if( !isset($jobid) )
                $message = 'Job Id cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) {
        
                $insertorupdate = array(
                    'timeslot'   => $timeslot,
                    'view'       => $view,
                    'singletech' => $technician,
                    'multitech'  => array()
                );

                //$start = '2015-11-01';
                //$end = '2015-12-13';

                $ids=array();
                if (trim($technician) != "") {
                    $ids[] = $technician;
                }
                if ($technicians) {
                    $ids = $technicians;
                    $insertorupdate['multitech'] = $ids;
                }
                $insertorupdate = json_encode($insertorupdate);
                $this->sharedclass->saveSettings($this->data['loggeduser']->contactid, 'ScheduleLayout', $insertorupdate);

                $getstatus = $this->jobclass->getETPJobStatusByName('NOTIFIED');
                $schedulecolors = $this->scheduleclass->getScheduleItems($this->data['loggeduser']->customerid);

                $request = array(
                    'customerid' => $this->data['loggeduser']->customerid,
                    'startdate'  => $start,
                    'enddate'    => $end,  
                    'ids'        => $ids,  
                    'apptid'     => null,
                    'jobid'      => $jobid
                );

                $scheduleData = $this->scheduleclass->getSchedules($request);
                $calendertechmulti = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);

                foreach ($scheduleData as $value) {

                    $jobcolor = $this->scheduleclass->getSechdulecolor($schedulecolors, $value, $getstatus);
                    $column = 0;
                    if (count($calendertechmulti) > 0) {
                        foreach ($calendertechmulti as $key => $tech) {
                            if ($tech['contactid'] == $value['contactid']) {
                                $column = $key;
                                break;
                            }
                        }
                    }

                    $data[] = array(
                        "id"             => $value['apptid'],
                        "title"          => $value['firstname'],
                        "jobcolor"       => $jobcolor,
                        "start"          => $value['dte'].'T'.$value['start'],
                        "end"            => $value['dte'].'T'.$value['end'],
                        "status"         => $value['status'],
                        "jobid"          => $value['jobid'],
                        "jobnumber"      => $value['jobnumber'] == NULL ? $value['jobid']:$value['jobnumber'],
                        "siteline1"      => $value['siteline1'].' ('.$value['sitesuburb'].')',
                        "islocked"       => $value['islocked'],
                        "column"         => $column,
                        "isinternal"     => $value['isinternal'],
                        "completebydate" => format_datetime($value['completebydate'].' '.$value['completebytime'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT)
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
    * @desc This function use for load task data on edit (calender)
    * @param none
    * @return json
    */       
    public function loadAppointment() {

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
            $apptid = $this->input->get('id');
            
            if ( !isset($apptid) )
                $message = 'apptid no cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                $diaryData = $this->scheduleclass->getSchedule($apptid);
                $data = $this->scheduleclass->formatAppointment($diaryData);
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
    * @desc This function use for start appointment
    * @param none
    * @return json
    */            
    public function startAppointment() {

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
            $apptid = $this->input->post('id');
            $jobid = $this->input->post('jobid');
            
            if ( !isset($apptid) )
                $message = 'apptid no cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                     
                //request array for update accept new job
                $request = array(
                    'jobid'     => $jobid,
                    'apptid'    => $apptid,
                    'logged_contactid' => $this->data['loggeduser']->contactid,

                );
                $this->scheduleclass->startAppointment($request);
                $diaryData = $this->scheduleclass->getSchedule($apptid);
                $data = $this->scheduleclass->formatAppointment($diaryData);
                $success -> setData($data);
               
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
    * @desc This function use for load data for stop appointment dialog
    * @param none
    * @return json
    */               
    public function loadStopAppointment() {
           
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
            $apptid = $this->input->post('id');
            
            if ( !isset($apptid) )
                $message = 'apptid no cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                $data = $this->scheduleclass->getSchedule($apptid);
                foreach ($data as $key => $value) {
                    $time = time();
                    $date = $value['dte'];
                    $end = date('Y-m-d H:i:s', $time);
                    $start = $date.' '.$value['start'];
                    $startdate = strtotime($date);
                    $enddate = date('Y-m-d', $time);
                    $timeformat = timeDiffTimeFormat($start, $end);

                    $contactid = $value['contactid'];
                    $jobid = $value['jobid'];
                    $data[$key]['closeradio'] = 1;

                    $queryParams = array(
                        "apptid!="  => $apptid, 
                        'dte'       => $date, 
                        'jobid'     => $jobid, 
                        'status!='  => '2', 
                        'contactid' => $contactid
                    );
                    $dairyData = $this->scheduleclass->getDairyData($queryParams);
                    if (count($dairyData) > 0) {
                        $data[$key]['closeradio'] = 0;
                    }

                    $data[$key]['end'] = date(RAPTOR_DISPLAY_DATEFORMAT.' H:i', strtotime($end));
                    $data[$key]['start'] = date(RAPTOR_DISPLAY_DATEFORMAT.' H:i', strtotime($start));
                    $data[$key]['duration'] = $timeformat[0].':'.$timeformat[1];
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
    * @desc This function use for pause appointment
    * @param none
    * @return json
    */      
    public function pauseAppointment() {
        
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
            $apptid = $this->input->post('id');
            
            if ( !isset($apptid) )
                $message = 'apptid no cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                 
                //request array for update accept new job
                $request = array(
                    'apptid'    => $apptid,
                    'logged_contactid' => $this->data['loggeduser']->contactid,
                );

                $this->scheduleclass->pauseAppointment($request);
                $diaryData = $this->scheduleclass->getSchedule($apptid);
                $data = $this->scheduleclass->formatAppointment($diaryData);
                
               
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
    * @desc This function use for adjust appointment time for selected task for close or allocate or jobcomplete
    * @param none
    * @return json
    */
    public function adjustAppointment() {

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
            $apptid = $this->input->post('id');
            $tech = $this->input->post('tech');
            $task = trim($this->input->post('task'));
            
            if( !isset($apptid) )
                $message = 'apptid cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) {

                $nowdate = date('Y-m-d', time());
                $nowtime = date('H:i:s', time());

                $data = $this->scheduleclass->getSchedule($apptid);
                foreach ($data as $key => $value) {
                   $time = time();
                   $date = $value['dte'];
                   $start = $date.' '.$value['start'];
                   $end = date('Y-m-d H:i:s', $time);
                   if ($task == 'reallocate'){
                       
                        $dbdur = $value['duration']*60;
                        $diff = timeDiffTimeFormat($start, $end);
                        
                        if ($diff[1]> 0) {
                            $dbdur1 = ($diff[0].'.'.$diff[1])*60;
                            if ($dbdur1>$dbdur) {
                               $dbdur = $dbdur1;
                            }
                        }
                        
                        $endtime = strtotime($start.'+'.($dbdur).'minutes');
                        $end = date('Y-m-d H:i:s', $endtime);

                        $ecstime = $value['start'];
                        $ecetime = date('H:i:00', $endtime);
                         
                        $filterdata = $this->scheduleclass->getAdjustAppointment($tech, $date, $ecstime, $ecetime);

                        if (count($filterdata) > 0) {
                            $filterstart = $filterdata[0]['start'];
                            $filterend = $value['end'];
                            $filterduration = $value['duration'];
                            $inminutes = $filterduration*60;
                            $start = $date.' '.$filterstart;

                            if ($inminutes != 0) {
                                $time = strtotime($start);
                                $time = $time - ($inminutes * 60);
                                $start = date("Y-m-d H:i", $time);
                            }
                            $endtime = strtotime($start.'+'.($inminutes).' minutes');
                            $end = date('Y-m-d H:i:s', $endtime);

                        }
                   } else {
                       $dbdur = $value['duration']*60;
                        $diff = timeDiffTimeFormat($start, $end);
                        if ($diff[1]> 0) {
                            $dbdur1 = ($diff[0].'.'.$diff[1])*60;
                            if ($dbdur1>$dbdur) {
                               $dbdur = $dbdur1;
                            }
                        }
                       $endtime = strtotime($start.'+'.($dbdur).'minutes');
                       $end = date('Y-m-d H:i:s', $endtime);
                   }

                   $timeformat = timeDiffTimeFormat($start, $end);
                   $data[$key]['end'] = date(RAPTOR_DISPLAY_DATEFORMAT.' H:i', strtotime($end));
                   $data[$key]['start'] = date(RAPTOR_DISPLAY_DATEFORMAT.' H:i', strtotime($start));
                   $data[$key]['duration'] = $timeformat[0].':'.$timeformat[1];
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
    * @desc This function use for close appointment as close or allocate or jobcomplete
    * @param none
    * @return json
    */       
    public function closeAppointment() {

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
            $apptid = $this->input->post('id');
            $tech = $this->input->post('tech');
            $task = trim($this->input->post('task'));
            $adjust = trim($this->input->post('adjust'));
            $notes = trim($this->input->post('notes'));
            
            if( !isset($apptid) )
                $message = 'apptid cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) {

                $nowdate = date('Y-m-d', time());
                $nowtime = date('H:i:s', time());
                $successType = 1;

                $resultData=array();
                $validateError = FALSE; 
                
                $data = $this->scheduleclass->getSchedule($apptid); 
                $value = $data[0];

                if ($task == 'complete') {

                    $queryParams = array(
                        "apptid!="  => $apptid, 
                        'isdeleted' => 0, 
                        'jobid'     => $data[0]['jobid'], 
                        'status'    => '0'
                    );
                    $notStartedDairyData = $this->scheduleclass->getDairyData($queryParams);
                    $queryParams['status'] = 1;
                    $inProgressDairyData = $this->scheduleclass->getDairyData($queryParams);

                    if ((count($notStartedDairyData) + count($inProgressDairyData) )> 0)
                    {
                        $msg = 'Job not fully completed, because other task for this job is started/In Progress.';
                        $successType = 3;
                        $resultData = array(
                            'success' => $successType,
                            'msg'     => $msg,
                            'data'    => $data  
                        );
                        $validateError = TRUE;
                    }
                }
                
                if(!$validateError){
                    
                    $time = time();
                    $orgtime = time();
                    $date = $value['dte'];
                    $end = date('Y-m-d H:i:s', $time);
                    $start = $date.' '.$value['start'];
                    $allocatestart = '';
                    $allocateend = '';
                    
                    if ($adjust == "1") {
                        
                        if ($task == 'reallocate') {
                            
                            $dbdur = $value['duration']*60;
                            $diff = timeDiffTimeFormat($start, $end);
                            if ($diff[1]> 0) {
                                $dbdur1 = ($diff[0].'.'.$diff[1])*60;
                                if ($dbdur1>$dbdur) {
                                   $dbdur = $dbdur1;
                                }
                            }
                            $time = strtotime($start.'+'.($dbdur).'minutes');
                            $end = date('Y-m-d H:i:s', $time);
                            $allocatestart = $value['start'];
                            $allocateend = date('H:i:s', $time);

                            $ecstime = $value['start'];
                            $ecetime = date('H:i:00', $time);

                            $filterdata =  $this->scheduleclass->getAdjustAppointment($tech, $date, $ecstime, $ecetime);

                            if (count($filterdata) > 0) {

                                $filterstart = $filterdata[0]['start'];
                                $filterend = $value['end'];
                                $filterduration = $value['duration'];
                                $inminutes = $filterduration*60;
                                $start = $date.' '.$filterstart;

                                if ($inminutes != 0) {
                                    $time1 = strtotime($start);
                                    $time1 = $time1 - ($inminutes * 60);
                                    $start = date("Y-m-d H:i", $time1);
                                    $allocatestart = date('H:i:s', $time1);
                                }
                                $time = strtotime($start.'+'.($inminutes).' minutes');
                                $end = date('Y-m-d H:i:s', $time);
                                $allocateend = date('H:i:s', $time);

                            }


                        } else {
                            $dbdur = $value['duration']*60;
                            $diff = timeDiffTimeFormat($start, $end);
                            if ($diff[1]> 0) {
                                $dbdur1 = ($diff[0].'.'.$diff[1])*60;
                                if ($dbdur1>$dbdur) {
                                   $dbdur = $dbdur1;
                                }
                            }
                            $time = strtotime($start.'+'.($dbdur).'minutes');
                            $end = date('Y-m-d H:i:s', $time);
                        }
                    }
                    $startdate = strtotime($date);
                    $enddate = strtotime(date('Y-m-d', $time));

                    $timeformat = timeDiffTimeFormat($start, $end);
                    $data[0]['end'] = date(RAPTOR_DISPLAY_DATEFORMAT.' H:i', strtotime($end));
                    $data[0]['start'] = date(RAPTOR_DISPLAY_DATEFORMAT.' H:i', strtotime($start));
                    $data[0]['duration'] = $timeformat[0].':'.$timeformat[1];

                    if ($startdate == $enddate) {
                        $successType = 1;
                    } else {
                        $msg = 'Start and End Date must be in same day.';
                        $successType = 0;
                        $resultData = array(
                            'success' => $successType,
                            'msg'     => $msg,
                            'data'    => $data  
                        );
                        
                        $validateError = TRUE;
                    }
                    
                    if(!$validateError){
                        
                        if ($time>strtotime($start) && ($time-strtotime($start)) >= 1) {
                            $successType = 1;
                        } else {
                            $msg = 'The end time must be after the start time. You must adjust times accordingly before the job can be closed';
                            $successType = 0;
                            $resultData = array(
                                'success' => $successType,
                                'msg'     => $msg,
                                'data'    => $data  
                            );

                            $validateError = TRUE;
                        }
                        
                        if(!$validateError){
                             
                            if ($task == 'reallocate') {
                                $ecstime = $value['start'];
                                $ecetime = date('H:i:00', $time);

                                if ($allocatestart!= '' && $allocateend != '') {
                                    $ecstime = $allocatestart;
                                    $ecetime = $allocateend;
                                }

                                $filterdata = $this->scheduleclass->getAdjustAppointment($tech, $date, $ecstime, $ecetime);
                                if (count($filterdata) > 0) {
                                    $msg = 'Start and End times overlap another task. You must adjust times to avoid the overlap before saving';
                                    $successType = 0;
                                    $resultData = array(
                                        'success' => $successType,
                                        'msg'     => $msg.$ecstime.$ecetime,
                                        'data'    => $data  
                                    );
                                   $validateError = TRUE;
                                }
                            }
                 
                            if(!$validateError){
                               
                                if ($adjust == "1") {
                                    $nowtime = date('H:i:s', $orgtime);
                                }

                                if ($task != "reallocate") {
                                    $nowtime = date('H:i:00', $time);
                                }

                                
                                //request array for update
                                $request = array(
                                    'apptid'        => $apptid,
                                    'logged_contactid'     => $this->data['loggeduser']->contactid,
                                    'task'          => $task,
                                    'nowdate'       => $nowdate,
                                    'nowtime'       => $nowtime,
                                    'technicianid'  => $tech,
                                    'allocatestart' => $allocatestart,
                                    'allocateend'   => $allocateend,
                                    'notes'         => $notes
                                );

                                $this->scheduleclass->closeAppointment($request);
                                 
                                $resultData = array(
                                    'success' => $successType
                                );
                            }
                        }
                    }
                }
                $data = $resultData;
                $success -> setData($data);
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
    * @desc This function use for bump task to next date
    * @param none
    * @return json
    */            
    public function bumpSchedule() {
        
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
            
            if ( !isset($contactid) ) {
                $message = 'contactid cannot be null.';
            }
    
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                //create next day date
                $nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));

                //request array for update
                $request = array(
                    'date'    => $date,
                    'logged_contactid' => $this->data['loggeduser']->contactid,
                    'task_contactid' => $contactid,
                    'nextdate' => $nextdate
                );

                $this->scheduleclass->bumpSchedule($request);
                
                $data = array(
                    'nextdate' => $nextdate
                );
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
    * @desc This function use for sort tasks by date
    * @param none
    * @return json
    */     
    public function sortSchedule() {
        
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
            
            if ( !isset($contactid) ) {
                $message = 'contactid cannot be null.';
            }
    
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                //get data
                $queryParams = array(
                    'dte'       => $date,
                    'contactid' => $contactid,
                    'status'    => '0',
                    'islocked'  => '0'
                );
                
                $dairyData = $this->scheduleclass->getDairyData($queryParams);
                     
                //apply sorting
                $start = time();
                foreach ($dairyData as $key => $value) {
                    $end = strtotime('+30 minutes', $start);
                    
                    $request = array(
                        'dte'    => $date,
                        'start'  => date('H:i:s', $start),
                        'end'    => date('H:i:s', $end),
                        'apptid' => $value['apptid'],
                        'logged_contactid'   => $this->data['loggeduser']->contactid,
                    );
                        
                    // update task time
                    $this->scheduleclass->updateSchedule($request);
                    $start = $end;
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
                    $timesheetData[$key]['jobnumber']=$value['jobnumber'] == NULL ? $value['jobid']:$value['jobnumber'];
                    $timesheetData[$key]['durmins'] = number_format(($value['duration']*60), 2);
                    $timesheetData[$key]['notes'] = limitTexts($value['notes'], 50);
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

         $heading=array('Date', 'Apptid', 'Job No', 'Sitesuburb','Start','Duration','Durmins','Notes');
         $this->load->library('excel');

         $name = 'timesheet';
         foreach ($timesheet as $row1)
         { 
             $result=array();

             $result[] = $row1['dte'];
             $result[] = $row1['apptid'];
             $result[] = $row1['jobnumber'] == NULL ? $row1['jobid']:$row1['jobnumber'];
             $result[] = $row1['sitesuburb'];
             $result[] = $row1['start'];
             $result[] = $row1['duration'];
             $result[] = number_format(($row1['duration']*60), 2);;
             $result[] = $row1['notes'];
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
    
    /**
    * @desc This function use for create internal task
    * @param none
    * @return json
    */
    public function createInternalTask() {
            
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
            $jobid = $this->input->post('jobid');
            $technician = $this->input->post('technician');
            $activity = $this->input->post('activity');
            $date = $this->input->post('date');
            $starttime = $this->input->post('starttime');
            $duration = $this->input->post('duration');
            $description = $this->input->post('description');
            $labelid = $this->input->post('ilabelid');
            
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) {
                
                //request array for create internal task
                $request = array(
                    'jobid'       => $jobid,
                    'logged_contactid'   => $this->data['loggeduser']->contactid,
                    'technician'  => $technician,
                    'activity'    => $activity,
                    'date'        => $date,
                    'starttime'   => $starttime,
                    'duration'    => $duration,
                    'description' => $description,
                    'labelid'     => $labelid
                );

                $result = $this->scheduleclass->createInternalTask($request);
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
    * @desc This function use for create task
    * @param none
    * @return json
    */
    public function createSchedule() {
        
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
            $jobid = $this->input->post('jobid');
            $technician = $this->input->post('technician');
            $date = $this->input->post('date');
            $maindate = $date;
            $view = trim($this->input->post('view'));
            $column = trim($this->input->post('column'));
            $restartjob = trim($this->input->post('restartjob'));
            
            if ( !isset($jobid) )
                $message = 'Jobid Id cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess ) {

                if ($view!='') {
                    $view = strtolower($view);
                }

                if ($view == 'multi') {
                    $multitech = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
                    foreach ($multitech as $key => $value) {
                        if ($key == $column) {
                            $technician = $value['contactid'];
                            break;
                        }
                    }
                }


                $jobData = $this->jobclass->getJobById($jobid);

                $dataar = explode('T', $date);
                $sdate = $dataar[0].' '.$dataar[1];
                $dbsdate = $dataar[0];
                $dbstime = $dataar[1];
 
                $queryParams = array(
                        'jobid'     => $jobid,
                        'isdeleted' => 0, 
                        'contactid' => $technician,
                        'dte'       => $dbsdate,
                        'status!='  => 2
                );
                $etp_diarydata = $this->scheduleclass->getDairyData($queryParams);
                
                if (count($etp_diarydata) == 0) {
                    $default_task_duration = '+60minutes';
                    $duration = $this->sharedclass->etp_settings('default_task_duration', $jobData['supplierid']);
                    if (!$duration) {
                        $duration = '60';
                    }
                    $default_task_duration = '+'.$duration.'minutes';
                    $duration = number_format(($duration/60), 2);


                    $time = strtotime($sdate.$default_task_duration);
                    $newdate = date('Y-m-d', $time).'T'.date('H:i', $time);
                    $dbedate = date('Y-m-d', $time);
                    $dbetime = date('H:i', $time);

                    $currentdatetime = date('Y-m-d H:i:s',time());

                    $request = array(
                        'jobid'         => $jobid,
                        'dbsdate'       => $dbsdate,
                        'duration'      => $duration,
                        'dbstime'       => $dbstime,
                        'technician'    => $technician,
                        'dbetime'       => $dbetime,
                        'restartjob'    => $restartjob,
                        'logged_contactid'     => $this->data['loggeduser']->contactid
                    );

                    $result = $this->scheduleclass->createSchedule($request);
                    $apptid = $result['diaryId'];
                     
                    $nextdate = date('Y-m-d', strtotime('+1 days', strtotime($dbsdate)));
                    $request = array(
                        'customerid'    => $this->data['loggeduser']->customerid,
                        'startdate'     => $dbsdate,
                        'enddate'       => $nextdate,  
                        'ids'           => array(),  
                        'apptid'        => $apptid,
                        'jobid'         => $jobid
                    );

                    $data = $this->scheduleclass->getSchedules($request); 
                  
                    $getstatus = $this->jobclass->getJobStatusByName('NOTIFIED');
                    $schedulecolors = $this->scheduleclass->getScheduleItems($this->data['loggeduser']->customerid);
                    $jobcolor = $this->scheduleclass->getSechdulecolor($schedulecolors, $data[0], $getstatus);

                    $res = array(
                        'success'   => true,
                        'jobcolor'  => $jobcolor,  
                        'start' => $date,
                        'end' => $newdate,
                        'id' => $apptid,
                        'islocked' => $data[0]['islocked'],
                        'status' => $data[0]['status'],
                        'column' => $column,  
                        'jobid' => $jobid,
                        'jobnumber' => $jobData['jobnumber'] == NULL ? $jobData['jobid']:$jobData['jobnumber'],
                        'isinternal' => $data[0]['isinternal'],  
                        'siteline1'  => $jobData['siteline1'].' ('.$jobData['sitesuburb'].')',
                        'completebydate'  => format_datetime($jobData['completeby'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT)  
                    );
                }
                else {
                    $res = array(
                        'success'   => false,
                        'message'   => 'Technician Already Allocate this job of selected date.',
                        'title'     => 'Task Not Created'
                    );
                }
                $data = $res;
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
    * @desc This function use for delete task
    * @param none
    * @return json
    */
    public function deleteSchedule() {
        
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
            $apptid = $this->input->post('apptid');
            
            if( !isset($apptid) )
                $message = 'apptid cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) {
                
                //request array for update accept new job
                $request = array(
                    'apptid'    => $apptid,
                    'logged_contactid' => $this->data['loggeduser']->contactid,
                );

                $this->scheduleclass->deleteSchedule($request);
                
 
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
    * @desc This function use for move task via calender drag n drop
    * @param none
    * @return json
    */    
    public function updateSchedule() {

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
            $apptid = $this->input->post('id');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            
            if ( !isset($apptid) ) {
                $message = 'ApptId cannot be null.';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess ) {
                //format date time
                $datear = explode('T', $start);
                $sdate = $datear[0];
                $stime = $datear[1];

                $datear = explode('T', $end);
                $edate = $datear[0];
                $etime = $datear[1];
                
                //get task
                $data = $this->scheduleclass->getSchedule($apptid);
                if (count($data) > 0) {
                    
                    $params = array(
                        'contactid' => $data[0]['contactid'],
                        'sdate'     => $sdate, 
                        'apptid'    => $apptid, 
                        'stime'    => $stime, 
                        'etime'    => $etime
                    );
                    
                    $diaryData = $this->scheduleclass->checkTaskOverlap($params);
                    
                    //format data 
                    if (count($diaryData) == 0) {

                        $request = array(
                            'dte'    => $sdate,
                            'start'  => $stime,
                            'end'    => $etime,
                            'apptid' => $apptid,
                            'logged_contactid' => $this->data['loggeduser']->contactid,
                                
                        );
                        
                        //task not overlap another task then update task time
                        $this->scheduleclass->updateSchedule($request);

                        $response = array(
                          'success' => true,
                          'start'   => $start,
                          'end'     => $end
                        );
                    }
                    else {
                        //task overlaped another task then send message
                        $response = array(
                          'success' => false,
                          'start'   => $start,
                          'end'     => $end,
                          'title'   => 'Appointment Not Moved',
                          'message' => 'Task is overlap so cannot move Appointment.'
                        );
                    }
                }
                else {
                    //task not found in table for this apptid 
                    $response = array(
                      'success' => false,
                      'start'   => $start,
                      'end'     => $end,
                      'title'   => 'Not Found',
                      'message' => 'Task not found for apptid '.$apptid  
                    );
                }
                $data = $response;
                $success -> setData($data);
                $success -> setTotal(count($data));
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
    * @desc This function use for lock/unlock appointment 
    * @param none
    * @return json
    */
    public function lockAppointment() {

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
            $apptid = $this->input->post('apptid');
            $reason = $this->input->post('reason');
            $islock = $this->input->post('islock');
            
            if ( !isset($apptid) )
                $message = 'apptid cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess ) {
                
                //request array for update
                $request = array(
                    'apptid'    => $apptid,
                    'logged_contactid' => $this->data['loggeduser']->contactid,
                    'reason' => $reason,
                    'islock' => $islock
                );

                $this->scheduleclass->lockAppointment($request);
                
                $data = $this->scheduleclass->getSchedule($apptid);
                $data = $this->scheduleclass->formatAppointment($data);
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
    * @desc This function use invite contact for selected appointment
    * @param none
    * @return json
    */           
    public function inviteAppointment() {

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
            $apptid = $this->input->post('apptid');
            $contactid = $this->input->post('contactid');
            $date = $this->input->post('date');
            
            if ( !isset($apptid) )
                $message = 'Apptid Id cannot be null.';
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess ) {

                $data = $this->scheduleclass->getSchedule($apptid);
 
                $invitedata = array();

                if (count($data) == 1)
                {
                    $data = $data[0];
                    
                    $queryParams = array(
                        'jobid'     => $data['jobid'],
                        'isdeleted' => 0, 
                        'contactid' => $contactid,
                        'dte'       => to_mysql_date($date, RAPTOR_DISPLAY_DATEFORMAT, "Y-m-d"),
                        'status!='  => 2
                    );
                    $etp_diarydata = $this->scheduleclass->getDairyData($queryParams);
                     
                    if (count($etp_diarydata) == 0) {
 
                        $supplierid = $data['supplierid'];
                        $parent_contactid = $data['contactid'];
                         
                        
                        //request array for update accept new job
                        $request = array(
                            'apptid'        => $apptid,
                            'technicianid'  => $contactid,
                            'dte'               => to_mysql_date($date, RAPTOR_DISPLAY_DATEFORMAT, "Y-m-d"),
                            'logged_contactid' => $this->data['loggeduser']->contactid,
                        );

                        $this->scheduleclass->inviteAppointment($request);
                        
                        $invitedata = $this->scheduleclass->getInvites($supplierid, $parent_contactid, $apptid);
                        $data = array(
                            'success' => true,
                            'invites' => $invitedata,
                            'title'   => '',
                            'message' => ''
                        );
                    }
                    else {
                        $data = array(
                            'success' => false,
                            'invites' => $invitedata,
                            'title'   => 'Technician Not Invited',
                            'message' => 'Technician Already Allocate this job of selected date.'
                        );
                    }
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
    * @desc This function use for update activity from edit appointment dialog
    * @param none
    * @return json
    */       
    public function updateActivity() {
        
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
            $activityid = $this->input->post('activityid');
            $apptid = $this->input->post('apptid');
            
            if ( !isset($apptid) ) {
                $message = 'apptid cannot be null.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if ( $isSuccess )
            {
                //request array for update
                $request = array(
                    'logged_contactid'  => $this->data['loggeduser']->contactid,
                    'activityid' => $activityid,
                    'apptid'     => $apptid
                );

                $this->scheduleclass->updateActivity($request);
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
    
}