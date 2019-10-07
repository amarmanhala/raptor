<?php 
/**
 * jobs Controller Class
 *
 * This is a jobs controller class 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * jobs Controller Class
 *
 * This is a jobs controller class 
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            jobs
 * @filesource          Jobs.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Jobs extends MY_Controller {

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
        $this->load->library('schedule/ScheduleClass');
        $this->load->library('document/DocumentClass');
        $this->load->library('purchaseorder/PurchaseOrderClass');
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
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/timepicker/bootstrap-timepicker.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/highcharts/js/highcharts.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/timepicker/bootstrap-timepicker.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/jobs/my-jobs.js'),
            base_url('assets/js/jobs/jobs.waitingapproval.js'),
            base_url('assets/js/jobs/jobs.waitingdcfmreview.js'),
            base_url('assets/js/jobs/jobs.inprogress.js'),
            base_url('assets/js/jobs/jobs.waitingvariationapproval.js'),
            base_url('assets/js/jobs/jobs.onhold.js'),
            base_url('assets/js/jobs/jobs.completed.js')
        );
        
        $this->data['states'] = $this->sharedclass->getStates(1);
            
        $this->data['waitingapprovalSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'waitingapproval');
        $this->data['waitingdcfmreviewJobSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'waitingdcfmreview');
        $this->data['inprogressJobSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'inprogress');
        $this->data['waitingvariationapprovalJobSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'waitingvariationapproval');
        $this->data['completedJobSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'completed');
        $this->data['onHoldJobSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'onhold');
        
//        $this->data['inprogressJobSuppliers'] = $this->jobclass->getJobCountBySupplier($this->data['loggeduser']->contactid, 'inprogress');
//        $this->data['completedJobSuppliers'] = $this->jobclass->getJobCountBySupplier($this->data['loggeduser']->contactid, 'completed');
        
        
        $this->data['waitingvariationapprovalJobSiteFM'] = $this->jobclass->getJobCountBySitefm($this->data['loggeduser']->contactid, 'waitingvariationapproval');
        $this->data['variationDeclineReasons'] = $this->jobclass->getVariationDeclineReasons($this->data['loggeduser']->customerid);
        
        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
        $this->data['suppliers'] = $this->customerclass->getCustomerSuppliers($this->data['loggeduser']->customerid);
        $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($this->data['loggeduser']->customerid, 'E');
        
        
        $this->load->library('budget/BudgetClass');
        $this->data['budgetWidgetCaption'] = $this->budgetclass->getBudgetWidgetCaption($this->data['loggeduser']->customerid);
        
        $this->data['budgetWidgetGlCode'] = $this->data['glcodes'];
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Jobs')
            ->set_layout($this->layout)
            ->set('page_title', 'My Jobs')
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Jobs', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('jobs/index', $this->data);
                 
    }
    
    
    /**
    * This function use for load Waiting Approval Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadWaitingApprovalJobs() {
        
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
                $jobsData = $this->jobclass->getWaitingApprovalJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
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
    * This function use for load Waiting DCFM Review Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadWaitingDCFMReviewJobs() {
        
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
                $jobsData = $this->jobclass->getWaitingDCFMReviewJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
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
    * This function use for load In Progress Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadInProgressJobs() {
        
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
                $field = 'qduedate';
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
                if (trim($this->input->get('supplierid')) != '') {
                    if(trim($this->input->get('supplierid')) == 'O'){
                        $params['j.cpallocated'] = 'O';
                    }
                    else{
                        $params['po.supplierid'] = $this->input->get('supplierid');
                    }
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
                $jobsData = $this->jobclass->getInProgressJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
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
    * This function use for load Waiting Variation Approval Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadWaitingVariationApprovalJobs() {
        
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
                $jobsData = $this->jobclass->getWaitingVariationApprovalJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
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
    * This function use for load Completed Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadCompletedJobs() {
        
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
                
                if (trim($this->input->get('supplierid')) != '') {
                    if(trim($this->input->get('supplierid')) == 'O'){
                        $params['j.cpallocated'] = 'O';
                    }
                    else{
                        $params['po.supplierid'] = $this->input->get('supplierid');
                    }
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
                $jobsData = $this->jobclass->getCompletedJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
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
    * This function use for load Completed Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadOnHoldJobs() {
        
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
                $jobsData = $this->jobclass->getOnHoldJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
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
    * @desc This function use for load pre start documents in uigrid for logged customer
    * @param none
    * @return json 
    */
    public function loadJobSearchResult() {
        
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
                $jobstage = '';
                $params = array();
                $filter = '';
                $otherLikes = array();
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
             
                if (trim($this->input->get('jobid')) != '') {
                    $params['j.jobid'] = $this->input->get('jobid');
                }
                if (trim($this->input->get('fromleaddate')) != '') {
                    $params['j.leaddate >='] =  to_mysql_date($this->input->get('fromleaddate'));
                }
                if (trim($this->input->get('toduedate')) != '') {
                    $params['j.leaddate <='] =  to_mysql_date($this->input->get('toleaddate'));
                }
                
                if (trim($this->input->get('fromduedate')) != '') {
                    $params['j.duedate >='] =  to_mysql_date($this->input->get('fromduedate'));
                }
                if (trim($this->input->get('toduedate')) != '') {
                    $params['j.duedate <='] =  to_mysql_date($this->input->get('toduedate'));
                }
                
                if ($this->input->get('state')!= NULL) {
                    $params['j.sitestate'] = $this->input->get('state');
                }
                
                if ($this->input->get('jobstages')!= NULL) {
                    $params['js.portaldesc'] = $this->input->get('jobstages');
                }
                
                if (trim($this->input->get('jobstage')) != '') {
                    $otherLikes[] = "(js.portaldesc='". $this->input->get('jobstage')."' or j.jobstage ='". $this->input->get('jobstage')."')";
               
                }
                
                if (trim($this->input->get('custordref')) != '') {
                    $otherLikes[] = "(j.custordref LIKE '%".$this->db->escape_str($this->input->get('custordref'))."%')";
                }
                if (trim($this->input->get('custordref2')) != '') {
                    $otherLikes[] = "(j.custordref2 LIKE '%".$this->db->escape_str($this->input->get('custordref2'))."%')";
                }
                if (trim($this->input->get('custordref3')) != '') {
                    $otherLikes[] = "(j.custordref3 LIKE '%".$this->db->escape_str($this->input->get('custordref3'))."%')";
                }
                if (trim($this->input->get('suburb')) != '') {
                    $otherLikes[] = "(j.sitesuburb LIKE '%".$this->db->escape_str($this->input->get('suburb'))."%')";
                }
                if (trim($this->input->get('jobdescription')) != '') {
                    $otherLikes[] = "(j.jobdescription LIKE '%".$this->db->escape_str($this->input->get('jobdescription'))."%')";
                }
                if (trim($this->input->get('siteaddress')) != '') {
                    $otherLikes[] = "(j.siteline2 LIKE '%".$this->db->escape_str($this->input->get('siteaddress'))."%' or j.siteline3 LIKE '%".$this->db->escape_str($this->input->get('siteaddress'))."%')";
                }
                
                $start = ($page - 1) * $size;

                //Load Job Data for selected customerid and jobstatus
                $jobsData = $this->jobclass->getSearchJobs($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params, $otherLikes);
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
        if($type == "waitingapproval"){
            $jobsData = $this->jobclass->getWaitingApprovalJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        }
        elseif($type == "waitingdcfmreview"){
            $jobsData = $this->jobclass->getWaitingDCFMReviewJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        }
        elseif($type == "inprogress"){
         
            if (trim($this->input->get('supplierid')) != '') {
                if(trim($this->input->get('supplierid')) == 'O'){
                    $params['j.cpallocated'] = 'O';
                }
                else{
                    $params['po.supplierid'] = $this->input->get('supplierid');
                }
            }
             
            $jobsData = $this->jobclass->getInProgressJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
           
        }
        elseif($type == "waitingvariationapproval"){
            $jobsData = $this->jobclass->getWaitingVariationApprovalJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        } 
        elseif($type == "completed"){
            
            if (trim($this->input->get('supplierid')) != '') {
                if(trim($this->input->get('supplierid')) == 'O'){
                    $params['j.cpallocated'] = 'O';
                }
                else{
                    $params['po.supplierid'] = $this->input->get('supplierid');
                }
            }
            
            $jobsData = $this->jobclass->getCompletedJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
            
        }    
        elseif($type == "onhold"){ 
             
            $jobsData = $this->jobclass->getOnHoldJobs($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
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
      
        $this->excel->Exportexcel("My Jobs", $dir, $file_name, $heading, $excelData);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($file_name, $data);

       
    }
    
    /**
    * This function use for getting Job Detail
    *  
    * @return json data 
    */ 
    public function loadJobDetail() { 
        
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
            $jobid = $this->input->get('jobid');
            
            if( !isset($jobid) )
                $message = 'Jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->jobclass->getJobById($jobid,$this->data['loggeduser']->customerid);
                
                if(count($data)>0){
                     
                    $data['leaddate'] = format_date($data['leaddate']);
                    $data['duedate'] = format_date($data['duedate']);
                    $data['vapprovaldate'] = format_datetime($data['vapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    $data['qdateaccepted'] = format_datetime($data['qdateaccepted'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    $data['jobapprovaldate'] = format_datetime($data['jobapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    $data['address'] = str_replace('<br>', ' ', $data['site']);
                    
                    foreach ($data as $key => $value) {
                        if($value == NULL){
                            $data[$key] = '';
                        }
                    }
                    
                    $success->setData($data);
                    $success->setTotal(count($data));
                }
                else{
                    $success = SuccessClass::initialize(FALSE);
                    $message = "Job doesn't exist. jobid: ". $jobid;
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
     * this function use for update Job approval process
     * 
     * @return json
     */
    public function updateJobApprove() {
 
 
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
 
                    $this->jobclass->approveJob($request);
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
     * this function use for update Job approval process
     * 
     * @return json
     */
    public function updateJobAllocation() {
 
 
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
            
            $jobid = trim($this->input->post('jobid'));
            $allocateto = trim($this->input->post('allocateto'));
            $supplierid = '';
            if($allocateto == 'Supplier'){
                $supplierid = trim($this->input->post('supplierid'));
            }
            if($allocateto == 'Internal'){
                 $supplierid = trim($this->input->post('internasupplierid'));
            }
            if ($jobid == '')  {
                $message = 'Please Select Job';
            }
            else{
                
                $jobData = $this->jobclass->getJobById($jobid, $this->data['loggeduser']->customerid);
                if(count($jobData)==0){
                    $message = 'Invalid Job id';
                }
                
                if(($allocateto == 'Supplier' || $allocateto == 'Internal') &&  $supplierid== ''){
                    $message = 'Please Select supplier';
                }
                 
                if($allocateto == 'Internal'){
                    $technicians = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid, $supplierid);
                    if(count($technicians) == 0){
                        $message = 'No contacts have been set up for job allocation.';
                    }
                }
                
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $allcationSuccess = TRUE;
                $allcationErrMessage = '';
                if($allocateto == 'Landlord'){
                    $landlords = $this->customerclass->getCustomerSiteLandlords($this->data['loggeduser']->customerid, $jobData['labelid']);
                    if(count($landlords)!=1){
                        $allcationSuccess = FALSE;
                        if(count($landlords)==0){
                            $allcationErrMessage = '"Landlord" unknown for this site. Set up the landlord now?';
                        }
                        else{
                            $allcationErrMessage = '"Landlord" is multiple for this site. manage the landlord now?';
                        }
                        
                    }
                    else{
                        $supplierid = $landlords[0]['supplierid'];
                    }
               }
                
                if($allcationSuccess){
                
                    $request = array(
                        'jobid'            => $jobid,
                        'allocateto'       => $allocateto,
                        'supplierid'       => $supplierid, 
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );

                    $this->jobclass->allocateJob($request);
                    $message = 'Request Jobs allocated by ' . $this->session->userdata('raptor_email');
                }
                
                $data = array (
                    'success' => $allcationSuccess,
                    'message' => $allcationErrMessage 
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
     * this function use for update Job Decline process
     * 
     * @return json
     */
    public function updateJobDecline() {
 
 
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
 
                    $this->jobclass->declineJob($request);
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
    public function updateJobRequestQuote() {
 
 
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
 
                    $this->jobclass->requestQuote($request);
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
    
    
    
     /**
     * this function use for update Job approval process
     * 
     * @return json
     */
    public function updateJobVariationApproval() {
 
 
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
            $duedate = $this->input->post('duedate');
            $duetime = $this->input->post('duetime');
            $custordref = $this->input->post('custordref');
            $custordref2 = $this->input->post('custordref2');
            $custordref3 = $this->input->post('custordref3');
            $notes = $this->input->post('notes');
           
            if (count($jobid)==0)  {
                $message = 'Please Select Job';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                if($duedate != ''){
                    $duedate = to_mysql_date($duedate, RAPTOR_DISPLAY_DATEFORMAT);
                }
                else{
                    $duedate = NULL;
                }
                if($duetime != ''){
                    $duetime =trim($this->input->post('attendtime'));
                    //$duetime = to_mysql_date(date('Y-m-d').' '. $duetime, RAPTOR_DISPLAY_DATEFORMAT.' '. RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s");
                }
                else{
                    $duetime = NULL;
                } 
                
                $request = array(
                    'jobid'            => $jobid, 
                    'duedate'          => $duedate, 
                    'duetime'          => $duetime, 
                    'custordref'       => $custordref, 
                    'custordref2'      => $custordref2, 
                    'custordref3'      => $custordref3, 
                    'notes'            => $notes,  
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );

                $this->jobclass->approveJobVariation($request);
              
                
                $message = 'Request Job Variation approved by ' . $this->session->userdata('raptor_email');
        
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
    public function updateJobVariationDecline() {
 
 
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
            $reason = $this->input->post('reason');
            $notes = $this->input->post('notes');
           
            if (count($jobid)==0)  {
                $message = 'Please Select Job';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                 

                $request = array(
                    'jobid'            => $jobid,
                    'reason'           => $reason, 
                    'notes'            => $notes, 
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );

                $this->jobclass->declineJobVariation($request);
                 
                
                $message = 'Request Job Variation Decline by ' . $this->session->userdata('raptor_email');
        
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
    
    public function variationPDF($jobid){
        
        redirect($this->config->item('variation_path').'var_'.$jobid.'.pdf');
    }
     
    /**
    * This function use for show jobdetail
    * 
    * @return void 
    */
    public function jobdetail($jobid) {

        //Load Job Detail Data
        $customerid =$this->session->userdata('raptor_customerid'); 
        $data = $this->jobclass->getJobById($jobid);
        if(count($data)==0){
            show_404();
        }
        if($data['customerid']!=$customerid){
            show_404();
        }
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/timepicker/bootstrap-timepicker.min.css'), 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css')
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/timepicker/bootstrap-timepicker.min.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/jobs/jobs.jobdetail.js'),
            base_url('assets/js/quotes/quotes.pendingapproval.js'),
            base_url('assets/js/jobs/jobs.jobnote.js'),
            base_url('assets/js/jobs/jobs.jobdocuments.js'),
            base_url('assets/js/jobs/jobs.jobtask.js'),
            base_url('assets/js/jobs/jobs.jobreport.js'),
            base_url('assets/js/emaildialog.js')
        );
        
        $contactid = $this->session->userdata('raptor_contactid');
        $canapprove = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'QUOTE_APPROVE');
        $this->data['canapprove'] = $canapprove;
        $this->data['variationDeclineReasons'] = $this->jobclass->getVariationDeclineReasons($this->data['loggeduser']->customerid);
        
        
        $ContactRules=$this->data['ContactRules'];
        $this->data['jobDocFolder'] = $this->documentclass->getDocFolder('doc');
        $this->data['jobImagesFolder'] = $this->documentclass->getDocFolder('images');
        $this->data['documentImages'] = $this->documentclass->documentImages($jobid, $contactid);
        $this->data['addtask_security'] = 1;$this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_TASK');
        
        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
        $this->data['suppliers'] = $this->customerclass->getCustomerSuppliers($this->data['loggeduser']->customerid);
        
        $this->data['EDIT_REPORT'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_REPORT');
        
        
        $allocatedto = array();
        $defaultTaskAllocateUser = $this->sharedclass->getSettingValue('cp_default_task_allocate_user');
        if(trim($defaultTaskAllocateUser) != '') {
            array_push($allocatedto, $defaultTaskAllocateUser);
        }
        array_push($allocatedto, $this->session->userdata('raptor_email'));
        $recipients = isset($this->data['ContactRules']["cp_task_allocation"]) ? trim($this->data['ContactRules']["cp_task_allocation"]) : '';
        if($recipients != '') {
            $allocatedto = array_merge($allocatedto, explode(";", $recipients));
        }

        $this->data['taskallocatedto'] = $allocatedto;
        
        $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($customerid, 'E');
            
        //Job Data formating for display
        if(count($data)>0){
            $location = array();
            if (trim($data['siteline2']) != '') {
                array_push($location, $data['siteline2']);
            }
            if (trim($data['sitesuburb']) != '') {
                array_push($location, $data['sitesuburb']);
            }
            if (trim($data['sitestate']) != '') {
                array_push($location, $data['sitestate']);
            }
            /*if (trim($data['sitepostcode']) != '') {
                array_push($location, $data['sitepostcode']);
            }*/
 
            $data['location'] = implode(", ", $location);
            
            $data['sitecontact'] = '';
            if($data['sitecontact'] != '') {
                $data['sitecontact'] = $data['sitecontact'];
                if($data['sitephone'] != '') {
                    $data['sitecontact'] = $data['sitecontact'].' - ('.$data['sitephone'].')';
                }
            }
            
            $data['notexceed'] = format_amount($data['notexceed']);
            $data['pdate'] = format_datetime($data['pdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['duedate'] = format_date($data['duedate'], RAPTOR_DISPLAY_DATEFORMAT);
            $data['duetime'] = format_time($data['duetime'], RAPTOR_DISPLAY_TIMEFORMAT);
            $data['jcompletedate'] = format_datetime($data['jcompletedate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            
            $data['vapprovaldate'] = format_datetime($data['vapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['qdateaccepted'] = format_datetime($data['qdateaccepted'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['jobapprovaldate'] = format_datetime($data['jobapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            
            $data['custordref1_label'] = isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1';
            $data['custordref2_label'] = isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2';
            $data['custordref3_label'] = isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3';
            
            $data['custordref3_access'] = isset($ContactRules["hide_custordref3_in_client_portal"]) ? $ContactRules["hide_custordref3_in_client_portal"] : 0;
        }
           
        $this->data['job'] = $data;
        $this->data['poData'] = $this->purchaseorderclass->getPurchaseOrderByJobid($jobid);
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Job Detail')
            ->set_layout($this->layout)
            ->set('page_title', 'Job Detail : JOB No '.$jobid)
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Jobs', site_url('jobs'))
            ->set_breadcrumb('Job Detail', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('jobs/jobdetail', $this->data);
    }  
    
    /**
    * @desc This function use for getting job Notes
    * 
    * @return json
    */
    public function loadJobNotes() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            $jobid = $this->input->get('jobid');
            
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
 
                //Set Default value for ui grid
                $page = 1;
                $size = 10;
                $order = 'desc';
                $field = 'jobnoteid';

                //check uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field')!= '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order')!= '' ? $this->input->get('order') : $order;
                    $jobid = $this->input->get('jobid');
                }
                
                $customerid =$this->session->userdata('raptor_customerid');

                //set page number
                $start = ($page - 1) * $size;

                $noteTypes = array('client', 'completion', 'internal.extend', 'portal');
                //load Job Note data
                $jobNotesData = $this->jobclass->getJobNotes($customerid, $jobid, $noteTypes, $size, $start, $field, $order);

                $trows = $jobNotesData['trows'];
                $data = $jobNotesData['data'];

                //create display data with require formating
                foreach ($data as $key => $value) {
                    $data[$key]['formatednotes'] = nl2br($value['notes']);
                    
                    $data[$key]['date'] = format_date($value['date'], RAPTOR_DISPLAY_DATEFORMAT);
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
    * @desc This function use for create Job Note
    * 
    * @return json
    */
    public function createJobNote() {
            
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
            $notes = trim($this->input->post('notes'));
            $jobid = $this->input->post('jobid');
            
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {

                //request array for create new jobnote
                
                $jobNoteData = array(
                    'jobid'      => $jobid,
                    'ntype'      => 'portal',
                    'origin'     => 'portal',
                    'userid'     => $this->session->userdata('email'),
                    'notes'      => $notes,
                    'notetype'   => 'portal',
                    'date'       => date('Y-m-d', time()),
                    'contactid'  => $this->session->userdata('raptor_contactid'),
                    'supplierid' => $this->session->userdata('raptor_customerid'),
                );
                
                $request = array(
                    'jobNoteData'   => $jobNoteData,
                    'contactid'     => $this->session->userdata('raptor_contactid')
                );
                $this->jobclass->createJobNote($request);
                $message = 'Note added successfully.';
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
    * @desc This function use for getting job tasks
    * 
    * @return json
    */
    public function loadJobTasks() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            $jobid = $this->input->get('jobid');
            
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
 
                //Set Default value for ui grid
                $page = 1;
                $size = 10;
                $order = 'desc';
                $field = 'tdate';

                //check uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field')!= '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order')!= '' ? $this->input->get('order') : $order;
                    $jobid = $this->input->get('jobid');
                }
                
                $customerid =$this->session->userdata('raptor_customerid');
                $contactid =$this->session->userdata('raptor_contactid');

                //set page number
                $start = ($page - 1) * $size;

                //load Job Task data
                $jobTaskData = $this->jobclass->getJobTasks($size, $start, $field, $order, $jobid, $customerid, $contactid);

                $trows = $jobTaskData['trows'];
                $data = $jobTaskData['data'];

                //create display data with require formating
                foreach ($data as $key => $value) {
                    $data[$key]['tdate'] = format_date($value['tdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['followupdate'] = format_date($value['followupdate'], RAPTOR_DISPLAY_DATEFORMAT);
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
    * This function use for getting job detail pdf
     * 
    * @return pdf
    */
    public function printJob() {
        
        $jobid = trim($this->input->get('jobid'));
         //Load Selected document data
        $data = $this->jobclass->getJobById($jobid);
        if (count($data) > 0) {
            
            $printOptions = trim($this->input->get('op'));
            $request = array(
                'jobid'             => $jobid,
                'print_options'      => $printOptions,     
                'open'              => true,
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            
            //generate pdf and show it
            $this->jobclass->createPDF($request);
        
        }
        else{ 
            throw new Exception("job doesn't exist");
        }
       
    }
    
         
    /**
    * This function use for getting email dialog data
    * 
    * @return json 
    */
    public function loadJobEmailData() {
       
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
          
            $jobid = $this->input->get('jobid');
            $poref = $this->input->get('poref');
            $type = $this->input->get('type');
            $jobnoteid = $this->input->get('custom');
            if( !isset($jobid) )
                $message = 'Job ID cannot be null.';

            $isSuccess = ( $message == "" ); 
          
            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $customerid = $this->session->userdata('raptor_customerid');

                $documents = $this->documentclass->getJobDocuments($jobid);
                
                $otherdocuments = array();
                foreach($documents as $val) {
                    $doc = array();
                    $doc['fname'] =  $val['documentid'].'.'.$val['docformat'];
                    $doc['doctype'] = $val['doctype'];
                    $doc['documentdesc'] = $val['documentdesc'];
                    $doc['dateadded'] = format_date($val['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
                    $doc['dname'] = $val['docname'];
                    $doc['documentid'] = $val['documentid'];
                    $doc['docname'] = $val["docname"];
                    $doc['filesizekb'] = round($val["filesize"]);
                    $doc['relpath'] = $this->config->item('document_dir');

                    $otherdocuments[] = $doc;
                    
                }
                
                $jobData = $this->jobclass->getJobById($jobid);
                
                
                if($type == 'jobtask') {
                    $jobTaskData = $this->jobclass->getJobTaskById($jobnoteid);
                    $mailData = array(
                        'message' => $jobTaskData['detail'],
                        'subject' => $jobTaskData['subject']
                    );
                } else {
                    $jobNoteData = $this->jobclass->getJobNoteById($jobnoteid);
                    $mailData = array(
                        'message' => $jobNoteData['notes'],
                        'subject' => 'Note entered via DCFM Portal -  Your Job: '.$jobData['custordref'].' from DCFM ('.$jobData['sitesuburb'].')'
                    );
                }
                
                
                $data = array(
                    'mailData'          => $mailData,
                    'jobData'           => $jobData,
                    'customerContacts'  => $this->customerclass->getCustomerContacts($customerid),
                    'internalContacts'  => $this->customerclass->getOrganisationContacts($customerid),
                    'otherDocuments'    => $otherdocuments
                );
                  
                $success -> setData($data);
                
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
    * This function use for send Job Emails
    * 
    * @return json 
    */
    public function sendJobEmails() {
         
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

            if( $isSuccess )
            {
                
                $this->form_validation->set_rules('recipients', 'Recipients', 'trim|required|callback_send_email_validation');
                $this->form_validation->set_rules('cc', 'CC', 'trim|callback_send_email_validation');
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
                $this->form_validation->set_rules('message', 'Message Body', 'trim|required');
              
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors()
                    );
                    
                }
                else{
                   
                    $jobid = $this->input->post('jobid');
                    
                    $docsA = array();
                    
                    $attachOtherDocuments = $this->input->post('attachOtherDocuments');
                    
                    if (is_array($attachOtherDocuments)) {
                        
                        foreach ($attachOtherDocuments as $value) {
                            $doc = array();
                            $doc['fname'] = $value['fname'];
                            $doc['dname'] = $value['dname'];
                            $doc['relpath'] = $value['relpath'];

                            $docsA[] = $doc;
                        }
                    }
                  
                    $emailData  = array(
                        'recipient'   => trim($this->input->post('recipients')), 
                        'cc'          => trim($this->input->post('cc')),
                        'subject'     => trim($this->input->post('subject')), 
                        'message'     => trim($this->input->post('message')),
                        'sender'      => $this->data['loggeduser']->email, 
                        'docsA'       => $docsA
                    );
                     
                    $jobData = $this->jobclass->getJobById($jobid);
 
                    if (count($jobData)) {
                        $emailData['customerid'] = $jobData['customerid'];
                        $emailData['xreftable'] = 'jobs';
                        $emailData['xrefid'] = $jobData['jobid'];
                    }
                    
                    
                    $request = array(
                        'emailData'         => $emailData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );
                    $response = $this->sharedclass->createEmailLog($request);

                    $message = 'Email sent successfully.';
                    $data = array (
                        'success' => TRUE
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
    * Send Mail Email validation
    * 
    * @param string $emails
    * @return void
    */
    public function send_email_validation($emails) {
        
        if(trim($emails)!=""){
            $emailarray = explode(';', $emails);
            $invalidEmailMsgArray = array();
            foreach ($emailarray as $key => $value) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $invalidEmailMsgArray[] = "Email address ".$value." is not valid. Remove or update this address.";
                }
            }
            
            if(count($invalidEmailMsgArray)==0){
                return TRUE;
            }
            else{
                $this->form_validation->set_message('send_email_validation', implode('<br>', $invalidEmailMsgArray));
                return FALSE;
            }
             
        }
        else{
            return TRUE;
        }
            
         
    }
    
    /**
    * @desc This function use for create Job Task
    * 
    * @return json
    */
    public function createJobTask() {
            
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
            $jobid = $this->input->post('jobid');
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
                //request array for create new job task
                $jobTaskData = array(
                    'jobid'         => $jobid,
                    'customerid'    => $this->session->userdata('raptor_customerid'),
                    'contactid'     => $this->session->userdata('raptor_contactid'),
                    'area'          => 'Client Portal',
                    'tdate'         => date('Y-m-d', time()),
                    'followupdate'  => to_mysql_date(trim($this->input->post('followupdate')), RAPTOR_DISPLAY_DATEFORMAT),
                    'subject'       => 'Entered  via Client Portal',
                    'raisedby'      => $this->session->userdata('raptor_email'),
                    'allocatedto'   => trim($this->input->post('allocatedto')),
                    'detail'        => trim($this->input->post('description')),
                    'source'        => 'CP'   
                );
                
                $request = array(
                    'jobTaskData'   => $jobTaskData,
                    'contactid'     => $this->session->userdata('raptor_contactid')
                );

                $this->jobclass->createJobTask($request);
                $message = 'Job Task added successfully.';
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
    * This function use for getting JobRequest pdf
     * 
    * @return pdf
    */
    public function printPO($poref) {
         
         //Load Selected document data
        $data = $this->purchaseorderclass->getPurchaseOrderByPoref($poref);
        if (count($data) > 0) {
           
            
            $request = array(
                'poref'             => $poref,
                'open'              => true,
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            
            //generate pdf and show it
            $this->purchaseorderclass->createPDF($request);
        
        }
        else{ 
            throw new Exception("poref doesn't exist. poref: " . $poref);
        }
       
    }
    
    /**
     * this function use for update Gl Code
     * 
     * @return json
     */
    public function updateGLCode() {
 
 
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
            
            $glcodeid = $this->input->post('glcode');
           
            if ($glcodeid == '' || $glcodeid == 0)  {
                $message = 'Please select glcode';
            }
            
            if($this->data['ContactRules']['EDIT_JOB_GLCODE'] == 0) {
                $message = 'You have not permission to edit';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $jobid = $this->input->post('jobid');
                
                $updateData = array(
                    'custglchartid' => $glcodeid
                );
                
                $request = array(
                    'jobid'            => $jobid,
                    'updateData'       => $updateData, 
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );

                $this->jobclass->updateJob($request);
                
                $message = 'Gl Code updated ';
        
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
     * this function use for update Gl Code to multiple jobs
     * 
     * @return json
     */
    public function updateMultipleGLCodes() {
 
 
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
            
            $glcodeid = $this->input->post('glcode');
           
            if ($glcodeid == '' || $glcodeid == 0)  {
                $message = 'Please select glcode';
            }
            
            $jobids = $this->input->post('jobids');
           
            if (count($jobids)==0)  {
                $message = 'Please Select Job';
            }
            
            if($this->data['ContactRules']['EDIT_JOB_GLCODE'] == 0) {
                $message = 'You have not permission to edit';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $updateData = array(
                    'custglchartid' => $glcodeid
                );
                foreach ($jobids as $key => $value) {

                    $request = array(
                        'jobid'            => $value,
                        'updateData'       => $updateData, 
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );

                    $this->jobclass->updateJob($request);
                }
                
                $message = 'Gl Code updated ';
        
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