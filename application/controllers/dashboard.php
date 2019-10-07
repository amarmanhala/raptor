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
 * @category            Dashboard
 * @filesource          Dashboard.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class Dashboard extends MY_Controller {

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
 
        if($this->session->userdata('raptor_role') == 'site contact') {
            $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid, 'contactid'=>$contactid), 'contactid, firstname');
        }
        elseif ($this->session->userdata('raptor_role') == 'sitefm') {
            $subordinate_emails = $this->customerclass->getSubordinateEmails($un);
            $this->data['contacts'] = $this->customerclass->getSelfSubordinateContact($customerid, $contactid , $subordinate_emails);
        }
        else{
            $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid,'role'=>'sitefm'),'contactid, firstname');
        }
        $this->data['sites'] =$this->customerclass->getCustomerSitesByRole($customerid, $contactid, $un, $this->session->userdata('raptor_role'));
        

        $this->data['periods']=getPeriods(RAPTOR_DISPLAY_DATEFORMAT);

        $this->data['states'] = $this->sharedclass->getStates(1);

        $this->data['cssToLoad'] = array(
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/select2/select2.min.css') 
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/select2/select2.full.min.js'), 
            base_url('plugins/highcharts/js/highcharts.js'),
           //base_url('plugins/highcharts/js/modules/exporting.js')
            base_url('assets/js/dashboard/dashboard.job.js')
        );
//        if(!$this->session->userdata('dcfm_c_l'))
//        { 
//            $this->data['dcfm_client_iframe_url']=$this->config->item('client_portal').'client/dcfmlogin.php?tcr='.encrypt_decrypt('encrypt', $this->session->userdata('raptor_contactid')); 
//            $this->session->set_userdata('dcfm_c_l', 'done');
//        }
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Dashboard - Job')
            ->set_layout($this->layout)
            ->set('page_title', 'Dashboard - Job')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Dashboard - Job', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('dashboard/job-dashboard', $this->data);

    }
    
    /**
    * @desc This function use for get job stage for logged customer
    * @param none
    * @return json 
    */
    public function loadJobDashboardChart() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            
            $fromdate = trim($this->input->get_post('fromdate'));
            $todate = trim($this->input->get_post('todate'));
            $manager = trim($this->input->get_post('manager'));
            $state = trim($this->input->get_post('state'));
            $site = trim($this->input->get_post('site'));
            
            
            $message = '';
            $isSuccess = TRUE; 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $jobStage = array();
                $jobCompletion = array();
                $jobAttendance = array();
                $jobTrand = array();
            
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
                

                //get jobstatus
                $jobCountByStatusData = $this->jobclass->getJobStageCount($this->data['loggeduser']->contactid, $fromdate, $todate, $manager, $state, $site);

                foreach ($jobCountByStatusData as $key => $value) {
                  
                    $jobStage[]  = array('name'=> $value['stage'] .' ('.(int)$value['count'].')','y'=>(int)$value['count']);

                }
                
                
                //get jobstatus
                $jobCompletionData = $this->jobclass->getJobCompletionChartData($this->data['loggeduser']->contactid, $fromdate, $todate, $manager, $state, $site);
                if(count($jobCompletionData)>0){
                    $jobCompletion[]  = array('name'=> 'Early ('.(int)$jobCompletionData['early'].')','y'=>(int)$jobCompletionData['early']);
                    $jobCompletion[]  = array('name'=> 'On-time ('.(int)$jobCompletionData['ontime'].')','y'=>(int)$jobCompletionData['ontime']);
                    $jobCompletion[]  = array('name'=> '1-7 Days ('.(int)$jobCompletionData['L7days'].')','y'=>(int)$jobCompletionData['L7days']);
                    $jobCompletion[]  = array('name'=> '7+ Days ('.(int)$jobCompletionData['g7days'].')','y'=>(int)$jobCompletionData['g7days']);
                   
                }
                
                $jobAttendanceData = $this->jobclass->getJobAttendanceChartData($this->data['loggeduser']->contactid, $fromdate, $todate, $manager, $state, $site);
                if(count($jobAttendanceData)>0){
                    $jobAttendance[]  = array('name'=> 'Early ('.(int)$jobAttendanceData['early'].')','y'=>(int)$jobAttendanceData['early']);
                    $jobAttendance[]  = array('name'=> 'On-time ('.(int)$jobAttendanceData['ontime'].')','y'=>(int)$jobAttendanceData['ontime']);
                    $jobAttendance[]  = array('name'=> '1-7 Days ('.(int)$jobAttendanceData['L7days'].')','y'=>(int)$jobAttendanceData['L7days']);
                    $jobAttendance[]  = array('name'=> '7+ Days ('.(int)$jobAttendanceData['g7days'].')','y'=>(int)$jobAttendanceData['g7days']);
                   
                }
                
                 //get jobstatus
                $jobCountByMonthData = $this->jobclass->getMonthlyJobCounts($this->data['loggeduser']->contactid, $fromdate, $todate, $manager, $state, $site);

                foreach ($jobCountByMonthData as $key => $value) {
                    $jobTrand[]  = array($value['monthname'] .'-'.(int)$value['year'],(int)$value['count']); 

                }
                 
                
                $data = array(
                    'jobStage'      =>$jobStage,
                    'jobCompletion' => $jobCompletion,
                    'jobAttendance' => $jobAttendance,
                    'jobTrand'      => $jobTrand
                );
                $success->setData($data); 
                $success->setTotal(count($data));
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
   
        
}