<?php 
/**
 * quotes Controller Class
 *
 * This is a quotes controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * quotes Controller Class
 *
 * This is a quotes controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            quotes
 * @filesource          quotes.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Quotes extends MY_Controller {

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
        $this->load->library('quote/QuoteClass');
    }

    /**
    * This function use for show My Quotes
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
            base_url('assets/js/quotes/my-quotes.js'),
            base_url('assets/js/quotes/quotes.inprogress.js'),
            base_url('assets/js/quotes/quotes.pendingapproval.js'),
            base_url('assets/js/quotes/quotes.waitingdcfmreview.js') 
        );
    
	
        $canapprove = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'QUOTE_APPROVE');
        
        //See if logged in contact is in approved contact string.
//        $canapprove = 0;
//        if (isset($this->data['ContactRules']["restrict_quote_recipient"]) && strlen($this->data['ContactRules']["restrict_quote_recipient"])> 0){
//            $contacts = $this->data['ContactRules']["restrict_quote_recipient"];
//            $conarray = explode(",",$contacts);
//            foreach ($conarray as $conid){
//                if($conid == $this->data['loggeduser']->contactid){
//                    $canapprove=1;
//                }
//            }
//	}	
//	else {
//            $canapprove = 1;	
//	}	
        
         
        
        
        
        $this->data['canapprove'] = $canapprove;
        $this->data['states'] = $this->sharedclass->getStates(1);
        
        $this->data['inprogressQuoteSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'inprogressquote');
        $this->data['pendingApprovalQuoteSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'pendingapprovalquote');
        $this->data['waitingdcfmreviewQuoteSuburb'] = $this->jobclass->getJobCountBySuburb($this->data['loggeduser']->contactid, 'waitingdcfmreviewquote');
        $this->data['pendingApprovalQuoteSiteFM'] = $this->jobclass->getJobCountBySitefm($this->data['loggeduser']->contactid, 'pendingapprovalquote');
        
        $this->data['variationDeclineReasons'] = $this->jobclass->getVariationDeclineReasons($this->data['loggeduser']->customerid);
        
        $this->load->library('budget/BudgetClass');
        $this->data['budgetWidgetCaption'] = $this->budgetclass->getBudgetWidgetCaption($this->data['loggeduser']->customerid);
         $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($this->data['loggeduser']->customerid, 'E');
        $this->data['budgetWidgetGlCode'] = $this->data['glcodes'];
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Quotes')
            ->set_layout($this->layout)
            ->set('page_title', 'My Quotes')
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Quotes', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('quotes/index', $this->data);
              
    }
    
    
    /**
    * This function use for load In Progress Jobs in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadPendingApprovalQuotes() {
        
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
                $field = 'duedate';
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
                $quoteData = $this->jobclass->getPendingApprovalQuotes($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
                $trows = $quoteData['trows'];
                $data = $quoteData['data'];
 
                //Foreach for data formating
                foreach ($data as $key => $value) {
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['leaddate'] = format_date($value['leaddate']);
                    $data[$key]['duedate'] = format_date($value['duedate']);
                    $data[$key]['qduedate'] = format_date($value['qduedate']);
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
    * This function use for load In Progress Quotes in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadInProgressQuotes() {
        
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
                $quoteData = $this->jobclass->getInProgressQuotes($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
                $trows = $quoteData['trows'];
                $data = $quoteData['data'];
 
                //Foreach for data formating
                foreach ($data as $key => $value) {
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['leaddate'] = format_date($value['leaddate']);
                    $data[$key]['duedate'] = format_date($value['duedate']);
                    $data[$key]['qduedate'] = format_date($value['qduedate']);
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
    * This function use for load In Progress Quotes in uigrid for logged customer
    *  
    * @return json 
    */
    public function loadWaitingDCFMReviewQuotes() {
        
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
                $quoteData = $this->jobclass->getWaitingDCFMReviewQuotes($this->data['loggeduser']->contactid, $size, $start, $field, $order, $filter, $params);
                $trows = $quoteData['trows'];
                $data = $quoteData['data'];
 
                //Foreach for data formating
                foreach ($data as $key => $value) {
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['leaddate'] = format_date($value['leaddate']);
                    $data[$key]['duedate'] = format_date($value['duedate']);
                    $data[$key]['qduedate'] = format_date($value['qduedate']);
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
        if($type == "pendingapproval"){
            $jobsData = $this->jobclass->getPendingApprovalQuotes($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        }
       
        elseif($type == "inprogress"){
            $jobsData = $this->jobclass->getInProgressQuotes($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
           
        }
        elseif($type == "waitingdcfmreview"){
            $jobsData = $this->jobclass->getWaitingDCFMReviewQuotes($this->data['loggeduser']->contactid, NULL, 0, $field, $order, $filter, $params);
        } 
          
       
        foreach ($jobsData['data'] as $row1) {
            $result = array();

            $result[] = $row1['jobid'];
            $result[] = $row1['custordref'];
            $result[] = str_replace('<br>', PHP_EOL, $row1['site']) ;
            $result[] = $row1['sitesuburb']; 
            $result[] = $row1['sitefm']; 
            $result[] = $row1['jobdescription'];
            $result[] = $row1['portaldesc']; 
            $result[] = format_date($row1['qduedate'], RAPTOR_DISPLAY_DATEFORMAT);

            $excelData[] = $result;
        }

         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); 
         
        
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

        $file_name='my_'.$type.'_quotes.xls';
      
        $this->excel->Exportexcel("My Quotes", $dir, $file_name, $heading, $excelData);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($file_name, $data);

       
    }
    
     /**
     * this function use for update Job approval process
     * 
     * @return json
     */
    public function updateQuoteApproval() {
 
 
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
//                if($duetime != ''){
//                    $duetime = to_mysql_date(date('Y-m-d').' '. $duetime, RAPTOR_DISPLAY_DATEFORMAT.' '. RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s");
//                }
//                else{
//                    $duetime = NULL;
//                } 
                
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

                $this->jobclass->approveQuote($request);
              
                
                $message = 'Request Quote approved by ' . $this->session->userdata('raptor_email');
        
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
    public function updateQuoteDecline() {
 
 
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

                $this->jobclass->declineQuote($request);
                 
                
                $message = 'Request Quote Decline by ' . $this->session->userdata('raptor_email');
        
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
    
    public function quotepdf($jobid){
        
        redirect($this->config->item('quote_path').'quote_'.$jobid.'.pdf');
    }
    
}