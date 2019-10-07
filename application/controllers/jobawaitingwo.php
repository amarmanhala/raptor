<?php 
/**
 * jobawaitingwo Controller Class
 *
 * This is a jobawaitingwo controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * jobawaitingwo Controller Class
 *
 * This is a jobawaitingwo controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            jobawaitingwo
 * @filesource          jobawaitingwo.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class JobAwaitingWo extends MY_Controller {

  
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
    * This function use for show My Jobs
    * 
    * @return void 
    */
    public function index()
    {
        $this->data['cssToLoad'] = array(
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'), 
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/jobs/my-jobs.js'),
            base_url('assets/js/jobs/jobs.reviewwaitingwojobs.js'),
            base_url('assets/js/jobs/jobs.waitingwoapprovalhistory.js'),
            base_url('assets/js/jobs/jobs.waitingwodeclinehistory.js') 
        );
        
        $this->data['states'] = $this->sharedclass->getStates(1);
            
        $this->data['reviewwaitingwoJobsSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'reviewwaitingwo');
        $this->data['waitingwoapprovalhistoryJobSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'waitingwoapprovalhistory');
        $this->data['waitingwodeclinehistorySuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'waitingwodeclinehistory');
        
        
         $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Job Awaiting Wo')
            ->set_layout($this->layout)
            ->set('page_title', 'Job Awaiting Wo')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Job Awaiting Wo', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('jobs/jobawaitingwo', $this->data);
                 
    }
    
    
    /**
    * This function use for load Review Waiting WO Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadReviewWaitingWOJobs() {
        
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
 
                //set Default Value for uigrid
                $page = 1;
                $size = 25;
                $order = 'desc';
                $field = 'jobid';
                $params = array();
                $filter = '';

                //check uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field')!= '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order')!= '' ? $this->input->get('order') : $order;
                    
                }
 
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
                if (trim($this->input->get('state')) != '') {
                    $params['j.sitestate'] = $this->input->get('state');
                }
                if (trim($this->input->get('suburb')) != '') {
                    $params['j.sitesuburb'] = $this->input->get('suburb');
                }
                if (trim($this->input->get('contactid')) != '') {
                    $params['a.contactid'] = $this->input->get('contactid');
                }
                if (trim($this->input->get('sitefm')) != '') {
                    $params['j.sitefm'] = $this->input->get('sitefm');
                }
                $start = ($page - 1) * $size;

                //Load Job Data for selected customerid and jobstatus
                $jobsData = $this->jobclass->getReviewWaitingWOJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
                $trows = $jobsData['trows'];
                $data = $jobsData['data'];
 
                //Foreach for data formating
                foreach ($data as $key => $value) {
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['leaddate'] = format_date($value['leaddate']);
                    $data[$key]['duedate'] = format_date($value['duedate']);
                
                }
                
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
       
       
    }
    
    
    /**
    * This function use for load Waiting WO Approval History Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadWaitingWOApprovalHistoryJobs() {
        
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
 
                //set Default Value for uigrid
                $page = 1;
                $size = 25;
                $order = 'desc';
                $field = 'jobid';
                $params = array();
                $filter = '';

                //check uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field')!= '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order')!= '' ? $this->input->get('order') : $order;
                     
                }
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
                if (trim($this->input->get('state')) != '') {
                    $params['j.sitestate'] = $this->input->get('state');
                }
                if (trim($this->input->get('suburb')) != '') {
                    $params['j.sitesuburb'] = $this->input->get('suburb');
                }
                if (trim($this->input->get('contactid')) != '') {
                    $params['a.contactid'] = $this->input->get('contactid');
                }
                if (trim($this->input->get('sitefm')) != '') {
                    $params['j.sitefm'] = $this->input->get('sitefm');
                }
                $start = ($page - 1) * $size;

                //Load Job Data for selected customerid and jobstatus
                $jobsData = $this->jobclass->getWaitingWOApprovalHistoryJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
                $trows = $jobsData['trows'];
                $data = $jobsData['data'];
 
                //Foreach for data formating
                foreach ($data as $key => $value) {
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['leaddate'] = format_date($value['leaddate']);
                    $data[$key]['duedate'] = format_date($value['duedate']);
                    $data[$key]['jobConvertedDate'] = format_date($value['jobConvertedDate']);
                }
                
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
       
       
    }
    
    /**
    * This function use for load Waiting WO Decline History Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadWaitingWODeclineHistoryJobs() {
        
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
 
                //set Default Value for uigrid
                $page = 1;
                $size = 25;
                $order = 'desc';
                $field = 'jobid';
                $params = array();
                $filter = '';

                //check uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field')!= '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order')!= '' ? $this->input->get('order') : $order;
                    
                }
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
                if (trim($this->input->get('state')) != '') {
                    $params['j.sitestate'] = $this->input->get('state');
                }
                if (trim($this->input->get('suburb')) != '') {
                    $params['j.sitesuburb'] = $this->input->get('suburb');
                }
                if (trim($this->input->get('contactid')) != '') {
                    $params['a.contactid'] = $this->input->get('contactid');
                }
                if (trim($this->input->get('sitefm')) != '') {
                    $params['j.sitefm'] = $this->input->get('sitefm');
                }
                $start = ($page - 1) * $size;

                //Load Job Data for selected customerid and jobstatus
                $jobsData = $this->jobclass->getWaitingWODeclineHistoryJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
                $trows = $jobsData['trows'];
                $data = $jobsData['data'];
 
                //Foreach for data formating
                foreach ($data as $key => $value) {
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['leaddate'] = format_date($value['leaddate']);
                    $data[$key]['duedate'] = format_date($value['duedate']);
                    $data[$key]['leadDeclinedDate'] = format_date($value['leadDeclinedDate']);
                       
                }
                
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
       
       
    }
    
    
     /**
     * this function use for download jobs in excel
     * 
     * @return excel
     */
    public function exportExcel($type) {
        
        
        
        $ContactRules=$this->data['ContactRules'];
        $excelData = array();
        $heading = array('Job Id', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1', 'Date In', 'Due Date', 'Site', 'Suburb', 'Site FM', 'Job Description', 'Job Stage');

        $this->load->library('excel');
        
        $order = 'desc';
        $field = 'jobid';
        $params = array();
        $filter = '';

        //check uigrid request params
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }

        if (trim($this->input->get('state')) != '') {
            $params['j.sitestate'] = $this->input->get('state');
        }
        
        if (trim($this->input->get('suburb')) != '') {
            $params['j.sitesuburb'] = $this->input->get('suburb');
        }
        
        if (trim($this->input->get('contactid')) != '') {
            $params['a.contactid'] = $this->input->get('contactid');
        }
        if (trim($this->input->get('sitefm')) != '') {
            $params['j.sitefm'] = $this->input->get('sitefm');
        }
        $jobsData = array();
          
        
        if($type == "reviewwaitingwo"){
            $jobsData = $this->jobclass->getReviewWaitingWOJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        }
        elseif($type == "waitingwoapprovalhistory"){
            $heading[] = 'Approved Date';
            $heading[] = 'Approved By';
            $jobsData = $this->jobclass->getWaitingWOApprovalHistoryJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        }
        elseif($type == "waitingwodeclinehistory"){
            $heading[] = 'Declined Date';
            $heading[] = 'Declined By';
            $jobsData = $this->jobclass->getWaitingWODeclineHistoryJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
           
        }
       
       
        foreach ($jobsData['data'] as $row1) {
            $result = array();

            $result[] = $row1['jobid'];
            $result[] = $row1['custordref'];
            $result[] = format_date($row1['leaddate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = format_date($row1['duedate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = str_replace('<br>', PHP_EOL, $row1['site']) ;
            $result[] = $row1['sitesuburb']; 
            $result[] = $row1['sitefm']; 
            $result[] = $row1['jobdescription'];
            $result[] = $row1['portaldesc'];
            if($type == "waitingwoapprovalhistory"){
                $result[] = format_date($row1['jobConvertedDate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['jobConvertedBy'];
            }
            if($type == "waitingwodeclinehistory"){
                $result[] = format_date($row1['leadDeclinedDate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['leadDeclinedBy'];
            }
            $excelData[] = $result;
        }

         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(40); 
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name='my_'.$type.'_jobs.xls';
      
        $this->excel->Exportexcel("Job Awaiting Wo", $dir, $file_name, $heading, $excelData);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($file_name, $data);

       
    }
    
    /**
     * this function use for update Job approval process
     * 
     * @return json
     */
    public function updateWOJobApprove() {
 
 
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
            
            $jobids = $this->input->post('jobids');
           
            if (count($jobids)==0)  {
                $message = 'Please Select Job';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                foreach ($jobids as $key => $value) {

                    $request = array(
                        'jobid'            => $value, 
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
 
                    $this->jobclass->approveWOJob($request);
                }
                
                $message = 'Request Jobs approved by ' . $this->session->userdata('raptor_email');
        
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
     * this function use for update Job Decline process
     * 
     * @return json
     */
    public function updateWOJobDecline() {
 
 
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
            
            $jobids = $this->input->post('jobids');
           
            if (count($jobids)==0)  {
                $message = 'Please Select Job';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                foreach ($jobids as $key => $value) {

                    $request = array(
                        'jobid'            => $value, 
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
 
                    $this->jobclass->declineWOJob($request);
                }
                
                $message = 'Request Jobs Decline by ' . $this->session->userdata('raptor_email');
        
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
     * this function use for update Job Decline process
     * 
     * @return json
     */
    public function updateWOJobRequestQuote() {
 
 
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
            
            $jobids = $this->input->post('jobids');
           
            if (count($jobids)==0)  {
                $message = 'Please Select Job';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                foreach ($jobids as $key => $value) {

                    $request = array(
                        'jobid'            => $value, 
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
 
                    $this->jobclass->requestQuoteWOJobs($request);
                }
                
                $message = 'Job Request Quote by ' . $this->session->userdata('raptor_email');
        
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
    
    public function report($jobid){
        
        redirect($this->config->item('report_path').'JobReports/'.$jobid.'.pdf');
    }
     /**
    * This function use for show Job Awaiting Wo
    * 
    * @return void 
    */
    public function index2()
    {
        $this->data['dcfm_client_iframe_url']=$this->config->item('client_portal').'contractor/Panel/dashboard1.php#clientAwaitingWO.php'; 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Job Awaiting Wo')
            ->set_layout($this->layout)
            ->set('page_title', 'Job Awaiting Wo')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Job Awaiting Wo', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('shared/iframe', $this->data);

    }
}