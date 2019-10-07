<?php 
/**
 * Invoice Controller Class
 *
 * This is a Invoice controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Invoice Controller Class
 *
 * This is a Invoice controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Invoice
 * @filesource          statements.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Statements extends MY_Controller {

   /**
    * Class constructor
    *
    * @return	void
    */
    function __construct() {
        
        parent::__construct();
        
        //  Load libraries
        $this->load->library('job/JobClass');
        $this->load->library('invoice/InvoiceClass');
          
    }
    
    /**
     * load My statement Views
     * 
     * @return void
     */
    public function index() {
        
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/highcharts/js/highcharts.js'),
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/invoice/my-statements.js'),
            base_url('assets/js/invoice/invoice.budgetspendcharts.js'),
            base_url('assets/js/invoice/invoice.fmapprovalinvoices.js'),
            base_url('assets/js/invoice/invoice.finalisedinvoices.js'),
            base_url('assets/js/invoice/invoice.finalapprovalinvoices.js'),
            base_url('assets/js/invoice/invoice.openinvoices.js'),
            base_url('assets/js/invoice/invoice.historyinvoices.js'),
            base_url('assets/js/invoice/invoice.batchhistory.js'),
            
        );
        $customerid = $this->session->userdata('raptor_customerid'); //11165
   
        $this->data['cuglchart'] =  $this->customerclass->getCustomerGLChart($customerid);
        $this->data['create_batchinvoice'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'CREATE_BATCHINVOICE');
       
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Statements')
                ->set_layout($this->layout)
                ->set('page_title', 'My Statements')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('statements/invoicelist', $this->data);
    }

    /**
    * This function use for load Finalised Invoices in uigrid
    * 
    * @return json 
    */
    public function loadFinalisedInvoices() {
        
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
                $order = 'desc';
                $field = 'invoiceno';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
              
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Invoice data
                $invoiceDate = $this->invoiceclass->getFinalisedInvoices($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
                $cuglchart =  $this->customerclass->getCustomerGLChart($this->session->userdata('raptor_customerid'));
                $trows = $invoiceDate['trows'];
                $data = $invoiceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
				    $data[$key]['Invoiced'] = format_amount($value['Invoiced']);
                    $data[$key]['amount'] = format_amount($value['taxval'] + $value['netval']);
                    $data[$key]['invoicedate'] = format_date($value['invoicedate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['shortdescription'] = limitTexts($value['jobdescription'], 200);
                    $data[$key]['siteaddress'] = $value['siteline2'] . '<br/>' . $value['sitesuburb'] . ' ' . $value['sitestate'] . ' ' . $value['sitepostcode'] . '';
                    $editglcode = '<select name="glCode[' . $value["invoiceno"] . ']" id="glCode_' . $value['invoiceno'] . '" data-inv="' . $value['invoiceno'] . '" data-jobid="' . $value['jobid'] . '" class="addchange-glCode">';
                    foreach ($cuglchart as $key1 => $value1) {
                        $selected = "";
                        if ($value1['expenseaccount'] == $value['glCode']) {
                            $selected = "selected";
                        }
                        $editglcode .='<option value="' . $value1['expenseaccount'] . '"  '. $selected .'>'. $value1['name'] .'</option>';
                    }

                    $editglcode .='</select>';
                    $data[$key]['editglcode'] = $editglcode;
                    
                    $data[$key]['allow_address'] =  isset($this->data['ContactRules']["allow_address_entry_in_client_portal"]) && $this->data['ContactRules']["allow_address_entry_in_client_portal"] == "1" ? TRUE : FALSE;
                    
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    /**
    * This function use for load History Invoices in uigrid
    * 
    * @return json 
    */
    public function loadFMApprovalInvoices() {
        
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
                $order = 'desc';
                $field = 'invoiceno';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
             
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Invoice data
                $invoiceDate = $this->invoiceclass->getFMApprovalInvoices($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $invoiceDate['trows'];
                $data = $invoiceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['invoicedate'] = format_date($value['invoicedate'],RAPTOR_DISPLAY_DATEFORMAT);
                       
                    $data[$key]['Invoiced'] = format_amount($value['Invoiced']);
                 
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    /**
    * This function use for load History Invoices in uigrid
    * 
    * @return json 
    */
    public function loadFinalApprovalInvoices() {
        
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
                $order = 'desc';
                $field = 'invoiceno';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
             
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Invoice data
                $invoiceDate = $this->invoiceclass->getFinalApprovalInvoices($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $invoiceDate['trows'];
                $data = $invoiceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['invoicedate'] = format_date($value['invoicedate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['approvaldate'] = format_datetime($value['approvaldate'],RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                      
                    $data[$key]['Invoiced'] = format_amount($value['Invoiced']);
                 
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    /**
    * This function use for load Open Invoices in uigrid
    * 
    * @return json 
    */
    public function loadOpenInvoices() {
        
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
                $order = 'desc';
                $field = 'invoiceno';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
              
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Invoice data
                $invoiceDate = $this->invoiceclass->getOpenInvoices($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $invoiceDate['trows'];
                $data = $invoiceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['invoicedate'] = format_date($value['invoicedate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['esentdate'] = format_datetime($value['esentdate'],RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    $data[$key]['approvaldate'] = format_datetime($value['approvaldate'],RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                     
                    $data[$key]['balance'] = format_amount($value['balance']);
                    $data[$key]['Invoiced'] = format_amount($value['Invoiced']);
                 
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
   
    /**
    * This function use for load History Invoices in uigrid
    * 
    * @return json 
    */
    public function loadHistoryInvoices() {
        
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
                $order = 'desc';
                $field = 'invoiceno';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
             
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Invoice data
                $invoiceDate = $this->invoiceclass->getHistoryInvoices($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $invoiceDate['trows'];
                $data = $invoiceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['invoicedate'] = format_date($value['invoicedate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['esentdate'] = format_datetime($value['esentdate'],RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    $data[$key]['paymentdate'] = format_date($value['paymentdate'],RAPTOR_DISPLAY_DATEFORMAT);
                    
                    $data[$key]['Net'] = format_amount($value['Net']);
                    $data[$key]['GST'] = format_amount($value['GST']);
                    $data[$key]['Invoiced'] = format_amount($value['Invoiced']);
                 
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
     
     /**
    * This function use for load Batch History in uigrid
    * 
    * @return json 
    */
    public function loadBatchHistory() {
        
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
                $order = 'desc';
                $field = 'custbatchid';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
             
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get Invoice data
                $invoiceDate = $this->invoiceclass->getBatchHistory($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $invoiceDate['trows'];
                $data = $invoiceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['batchdate'] = format_datetime($value['batchdate'],RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    $data[$key]['esentdate'] = format_datetime($value['esentdate'],RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
  
                    $data[$key]['totalvalue'] = format_amount($value['totalvalue']); 
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
     
    public function invoicePDF($invoiceno) {
        
        
        $request = array(
                'invoiceno'        => $invoiceno,
                'open'             => true,
                'logged_contactid' => $this->data['loggeduser']->contactid
        );

        //generate pdf and show it
        $this->invoiceclass->createInvoicePDF($request);
     
    }
   
    public function batchInvoicePdf($batchid) {
        
        if(!is_numeric($batchid)) {
            die('Invalid batch id');
        }
        
        $request = array(
                'batchid'        => $batchid,
                'open'             => true,
                'logged_contactid' => $this->data['loggeduser']->contactid
        );

        //generate pdf and show it
        $this->invoiceclass->createBatchPDF($request);
       
    }
    
    
    /**
     * this function use for download invoices in excel
     * 
     * @return excel
     */
    public function downloadExcel($status) {
 
        $data_array = array();
        $heading = array();
        $this->load->library('excel');

        if ($status == "finalised") {
            
            $params = array();
            $order = 'desc';
            $field = 'invoiceno';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getFinalisedInvoices($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
            $ContactRules=$this->data['ContactRules'];
            $heading = array('Invoice No.', 'GL Code', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3', 'Invoice Date', 'Job ID', 'Suburb', 'Job Desc', 'Site FM');

            foreach ($invoiceData['data'] as $row1) {
                $result = array();
                $result[] = $row1['invoiceno'];
                $result[] = $row1['glCode'];
                $result[] = $row1['custordref'];
                $result[] = $row1['custordref2'];
                $result[] = $row1['custordref3'];
                $result[] = format_date($row1['invoicedate '], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['jobid'];
                $result[] = $row1['siteline2'] . PHP_EOL . $row1['sitesuburb'] . PHP_EOL . $row1['sitestate'] . PHP_EOL . $row1['sitepostcode'];
                $result[] = $row1['jobdescription '];
                $result[] = $row1['sitefm '];

                $data_array[] = $result;
            }
            
            //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
        }
        //For FM Approval
        elseif ($status == "fmapproval") {
            
            $params = array();
            $order = 'desc';
            $field = 'invoiceno';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getFMApprovalInvoices($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
  
            $heading = array('Invoice No.', 'Invoice Date', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1', isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2', isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3', 'Amount ($)', 'Gl Code', 'Site FM', 'Approved By', 'Approved Date', 'Suburb', isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"] : 'Site Ref');

            foreach ($invoiceData['data'] as $row1) {
                $result = array();

                $result[] = $row1['invoiceno'];
                $result[] = format_date($row1['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['custordref'];
                $result[] = $row1['custordref2'];
                $result[] = $row1['custordref3'];
                $result[] = format_amount($row1['Invoiced']);
                $result[] = $row1['glCode'];
                $result[] = $row1['sitefm']; 
                $result[] = $row1['sitesuburb'];
                $result[] = $row1['siteref'];
                 
                $data_array[] = $result;
            }
            
             //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); 
        }
        //For Final Approval
        elseif ($status == "finalapproval") {

            $params = array();
            $order = 'desc';
            $field = 'invoiceno';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getFinalApprovalInvoices($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
 
            $heading = array('Invoice No.', 'Invoice Date', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1', isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2', isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3',
                'Amount ($)', 'Gl Code', 'Site FM', 'Approved By', 'Approved Date', 'Suburb', isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"] : 'Site Ref');

            foreach ($invoiceData['data'] as $row1) {
                $result = array();

                $result[] = $row1['invoiceno'];
                $result[] = format_date($row1['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['custordref'];
                $result[] = $row1['custordref2'];
                $result[] = $row1['custordref3'];
                $result[] = format_amount($row1['Invoiced']);
                $result[] = $row1['glCode'];
                $result[] = $row1['sitefm'];
                $result[] = $row1['approvedby'];
                $result[] = format_datetime($row1['approvaldate'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = $row1['sitesuburb'];
                $result[] = $row1['siteref'];
                

                $data_array[] = $result;
            }
            
            //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        }
        //For Final Approval
        elseif ($status == "forapproval") {

            $params = array();
            $order = 'desc';
            $field = 'invoiceno';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getFinalApprovalInvoices($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
             
            $heading = array('Invoice No.', 'Invoice Date', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1', isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2', isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3',
                'Amount ($)', 'Gl Code', 'Site FM', 'Approved By', 'Approved Date', 'Suburb', isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"] : 'Site Ref');

            foreach ($invoiceData['data'] as $row1) {
                $result = array();

                $result[] = $row1['invoiceno'];
                $result[] = format_date($row1['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['custordref'];
                $result[] = $row1['custordref2'];
                $result[] = $row1['custordref3'];
                $result[] = format_amount($row1['Invoiced']);
                $result[] = $row1['glCode'];
                $result[] = $row1['sitefm'];
                $result[] = $row1['approvedby'];
                $result[] = format_datetime($row1['approvaldate'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = $row1['sitesuburb'];
                $result[] = $row1['siteref'];
                

                $data_array[] = $result;
            }
            
             //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        }

        //For Open Invoice
        elseif ($status == "open") {
            
            $params = array();
            $order = 'desc';
            $field = 'invoiceno';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getOpenInvoices($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
 
            
            $heading = array('Invoice No.', 'Invoice Date', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1', '$ Invoiced', '$ Balanced', 'Gl Code', 'Approval Date', 'Emailed', 'SiteFM', 'State', isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"] : 'Site Ref', 'Job ID');
            foreach ($invoiceData['data'] as $row1) {
                $result = array();

                $result[] = $row1['invoiceno'];
                $result[] = format_date($row1['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['custordref'];
                $result[] = format_amount($row1['Invoiced']);
                $result[] = format_amount($row1['balance']);
                $result[] = $row1['glCode'];
                $result[] = format_datetime($row1['approvaldate'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = format_datetime($row1['esentdate'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = $row1['sitefm'];
                $result[] = $row1['sitestate'];
                $result[] = $row1['siteref'];
                $result[] = $row1['jobid'];
             

                $data_array[] = $result;
            }
            
              
             //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        }
        //For invoiceshistory
        elseif ($status == "invoiceshistory") {
            
            $params = array();
            $order = 'desc';
            $field = 'invoiceno';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getHistoryInvoices($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
 
            
            $heading = array('Invoice No.', 'Invoice Date', 'Send Date', 'Payment Date', 'Days', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1', isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2', isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3', 'Exc. GST', 'GST', 'Total Amount', 'Gl Code', 'Site FM', 'State', isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"] : 'Site Ref', 'Job ID');

            foreach ($invoiceData['data'] as $row1) {
                $result = array();

                $result[] = $row1['invoiceno'];
                $result[] = format_date($row1['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = format_datetime($row1['esentdate'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = format_date($row1['paymentdate'], RAPTOR_DISPLAY_DATEFORMAT);
                $result[] = $row1['days'];
                $result[] = $row1['custordref'];
                $result[] = $row1['custordref2'];
                $result[] = $row1['custordref3'];
                $result[] = format_amount($row1['Net']);
                $result[] = format_amount($row1['GST']);
                $result[] = format_amount($row1['Invoiced']);
                $result[] = $row1['glCode'];
                $result[] = $row1['sitefm'];
                $result[] = $row1['sitestate'];
                $result[] = $row1['siteref'];
                $result[] = $row1['jobid'];
                
                $data_array[] = $result;
            }
            
            //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            
        }
         //For batchhistory
        elseif ($status == "batchhistory") {
            
            $params = array();
            $order = 'desc';
            $field = 'custbatchid';
            $filter = '';
            if (trim($this->input->get('filterText')) != '') {
                $filter = $this->input->get('filterText');
            }
            $invoiceData = $this->invoiceclass->getBatchHistory($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
 
            $heading = array('Batch Id', 'Batch Date', 'Created By', 'Recipients', 'Sent Date', 'No. Invoices', 'Total Value');

            foreach ($invoiceData['data'] as $row1) {
                $result = array();

                $result[] = $row1['custbatchid'];
                $result[] = format_datetime($row1['batchdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = $row1['createdby'];
                $result[] = $row1['recipients'];
                $result[] = format_datetime($row1['esentdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                $result[] = $row1['invoicecount'];
                $result[] = format_amount($row1['totalvalue']);
                
                $data_array[] = $result;
            }
            
            //set excel configurations
            $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); 
        }
        
        
        //Load the file helper and write the file to your server
        $dir = $this->config->item('invoicedir');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        

        $file_name = $status . "_invoice.xls";
        $this->excel->Exportexcel("My Statements", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents($this->config->item('invoicepath'). $file_name);
 
        force_download($file_name, $data);
    }

    /**
     * this function use for update job detail
     * 
     * @return json
     */
    public function updateJobSuburb() {

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
            $state = $this->input->post('state');
            $postcode = $this->input->post('postcode');
            $suburb = $this->input->post('suburb');
            $siteline2 = $this->input->post('siteline2');
            
            if (!isset($jobid) || $jobid == '') {
                $message = 'Job ID cannot be null.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $jobUpdateData = array(
                    'sitesuburb'    => $suburb,
                    'sitestate'     => $state,
                    'sitepostcode'  => $postcode,
                    'siteline2'     => $siteline2
                );
                
                $request = array(
                    'updateData'       => $jobUpdateData,
                    'jobid'            => $jobid,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->jobclass->updateJob($request); 
           
        
                $message = 'Request Data Updated';
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
     * this function use for update finalise Invoice
     * 
     * @return json
     */
    public function updateFinalise() {

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
            
            $invoicenos = $this->input->post('invoices'); 
            
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                if (isset($this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"]) && $this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"] == "1") 
                {
                
                    foreach ($invoicenos as $key => $value) {

                        $request = array(
                            'invoiceno'        => $value,
                            'open'             => false,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        //generate pdf and show it
                        $this->invoiceclass->createInvoicePDF($request);
                    }
                }
                $request = array(
                    'invoicenos'                    => $invoicenos,
                    'auto_send_finalised_invoice'   => isset($this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"]) ? $this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"] : FALSE,
                    'logged_contactid'              => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->invoiceFinalised($request);   
                 
                $message = 'Request invoices finalised.';
        
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
     * this function use for update finalise Approve Invoice
     * 
     * @return json
     */
    public function updateFinaliseApprove() {

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
            
            $invoicenos = $this->input->post('invoices'); 
            
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                if (isset($this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"]) && $this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"] == "1") {
                
                    foreach ($invoicenos as $key => $value) { 
                        $request = array(
                            'invoiceno'        => $value,
                            'open'             => false,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        //generate pdf and show it
                        $this->invoiceclass->createInvoicePDF($request);
                    }
                    
                }
                $request = array(
                    'invoicenos'                    => '',
                    'expectval'                     => '', 
                    
                    'invoicenos'                    => $invoicenos,
                    'auto_send_approved_invoice'   => isset($this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"]) ? $this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"] : FALSE,
                    'auto_send_finalised_invoice'   => isset($this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"]) ? $this->data['ContactRules']["auto_send_finalised_invoice_from_clientportal"] : FALSE,
                    'logged_contactid'              => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->invoiceFinalisedApprove($request);   
                 
                $message = 'Request invoices finalised.';
        
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
     * this function use for update glcode in invoice
     * 
     * @return json
     */
    public function updateGlCode() {

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
            
            $invoiceno = $this->input->post('invoiceno');
            $glCode = $this->input->post('glCode');
            
            if (!isset($invoiceno) || $invoiceno == '') {
                $message = 'Invoice cannot be null.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                 
                 
                $invoiceUpdateData = array(
                    'glCode' => $glCode
                );
                
                $request = array(
                    'updateData'       => $invoiceUpdateData,
                    'invoiceno'        => $invoiceno,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->updateInvoice($request);  
                
        
                $message = 'Invoice glcode successfylly updated.';
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
     * this function use for update invoice ref value
     * 
     * @return json
     */
    public function updateOrderRefs() {

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
            $invoiceno = $this->input->post('invoiceno');
            $custordref = $this->input->post('custordref');
            $custordref2 = $this->input->post('custordref2');
            $custordref3 = $this->input->post('custordref3');
            
            if (!isset($jobid) || $jobid == '') {
                $message = 'Job ID cannot be null.';
            }

            if (!isset($invoiceno) || $invoiceno == '') {
                $message = 'Invoice cannot be null.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $jobUpdateData = array(
                    'custordref'  => $custordref,
                    'custordref2' => $custordref2,
                    'custordref3' => $custordref3
                );
                if (isset($this->data['ContactRules']["allow_address_entry_in_client_portal"]) && $this->data['ContactRules']["allow_address_entry_in_client_portal"] == "1") {
                    if($this->input->post('sitesuburb')!=NULL){
                        if($this->input->post('tableid') == 'finalisedinvoicestbl'){
                        
                            $jobUpdateData['siteline2'] = trim($this->input->post('siteline2'));
                            $jobUpdateData['sitesuburb'] = trim($this->input->post('sitesuburb'));
                            $jobUpdateData['sitestate'] = trim($this->input->post('sitestate'));
                            $jobUpdateData['sitepostcode'] = trim($this->input->post('sitepostcode'));
                        }
                    }
                }
                $request = array(
                    'updateData'       => $jobUpdateData,
                    'jobid'            => $jobid,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->jobclass->updateJob($request); 
                
                 
                $invoiceUpdateData = array(
                    'custordref' => $custordref
                );
                if($this->input->post('glcode')!=NULL){

                    $invoiceUpdateData['glCode'] = trim($this->input->post('glcode'));
                }
               
                $request = array(
                    'updateData'       => $invoiceUpdateData,
                    'invoiceno'        => $invoiceno,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->updateInvoice($request);  
                
        
                $message = 'Invoice Cust Order Fields successfylly updated.';
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
     * this function use for send query of selected invoice
     * 
     * @return json
     */
    public function sendQueryInvoice() {

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
            
            $erecipients = array($this->input->post('recipients'));
            $esubject = $this->input->post('subject');
            $emessage = $this->input->post('message');
            
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
  
                $request = array(
                    'recipients'        => $erecipients,
                    'subject'           => $esubject,
                    'message'           => $emessage,
                    'logged_contactid'  => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->invoiceQuery($request);   
               
                $message = 'Query Invoice Send Successfully.';
        
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
     * this function use for update FM approval Process
     * 
     * @return json
     */
    public function updateFmApproval() {

       

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
            
            $invoicenos = $this->input->post('invoices');
            $expectval = $this->input->post('expectval');
            
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                if (isset($this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"]) && (int)$this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"] == 1) 
                {
                
                    foreach ($invoicenos as $key => $value) {

                        $request = array(
                            'invoiceno'        => $value,
                            'open'             => false,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        //generate pdf and show it
                        $this->invoiceclass->createInvoicePDF($request);
                    }
                }
  
                $request = array(
                    'invoicenos'       => $invoicenos,
                    'expectval'        => $expectval, 
                    'auto_send_approved_invoice'   => isset($this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"]) ? $this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"] : FALSE,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->invoiceFMApproval($request);   
                
                
                $message = 'Request invoices approved by ' . $this->session->userdata('raptor_email'); 
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
     * this function use for update invoice final approval process
     * 
     * @return json
     */
    public function updateApproval() {
 
 
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
            
            $invoicenos = $this->input->post('invoices');
            $expectval = $this->input->post('expectval');
            $estPayDate = $this->input->post('estpaydate');
            
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                if ($estPayDate != "" && $estPayDate != null) {
                    $estPayDate = to_mysql_date($estPayDate, RAPTOR_DISPLAY_DATEFORMAT);
                }
                if (isset($this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"]) && (int)$this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"] == 1) 
                {
                
                    foreach ($invoicenos as $key => $value) {

                        $request = array(
                            'invoiceno'        => $value,
                            'open'             => false,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        //generate pdf and show it
                        $this->invoiceclass->createInvoicePDF($request);
                    }
                }
                
                
                $request = array(
                    'invoicenos'                    => $invoicenos,
                    'expectval'                     => $expectval,
                    'estPayDate'                    => $estPayDate,
                    'auto_send_approved_invoice'    => isset($this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"]) ? $this->data['ContactRules']["auto_send_approved_invoice_from_clientportal"] : FALSE,
                    'logged_contactid'              => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->invoiceApproval($request);   
                 
                $message = 'Request invoices approved by ' . $this->session->userdata('raptor_email');
        
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
     * this function use for load selected invoices detail
     * 
     * @return json
     */
    public function loadSelectedInvoices() {

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
            
            $invoicenos = $this->input->post('invoices');
            $type = $this->input->post('type');
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $customerid = $this->session->userdata('raptor_customerid');
             
                $selectedInvoiceData = $this->invoiceclass->getInvoices($invoicenos,$customerid);
                
                foreach ($selectedInvoiceData as $key => $value) {
                    
                    $selectedInvoiceData[$key]['esentdate'] = format_date($value['esentdate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $selectedInvoiceData[$key]['formatedInvoiced'] = format_amount($value['Invoiced']);
                }
                $data['selectedinv'] = $selectedInvoiceData;
                $data['estpaydate'] = date(RAPTOR_DISPLAY_DATEFORMAT, strtotime('+7 days', time()));
                
                if($type == 'email'){
                    
                    $emailData = array();
                  
                    $recipients = $this->customerclass->getContactsByMailGroup($customerid, 'invoice');
                    $recip = array();
                    foreach ($recipients as $key => $value) {
                        $recip[] = $value['email']; 
                    }
                    $emailData['recipients'] = implode(';', $recip);
                    $emailData['subject'] = 'Invoices for '.$this->data['banner_array']['companyname'].' from DCFM Australia Pty Ltd';
                    $emailData['message'] = 'Please find '. count($selectedInvoiceData) .' invoices attached. '. PHP_EOL.PHP_EOL.' Should there be any issue with regards to these please contact DCFM Australia Pty Ltd at your earliest convenience.'. PHP_EOL.PHP_EOL.PHP_EOL.'Thanks and regards,'. PHP_EOL.PHP_EOL.'Team DCFM!'. PHP_EOL.'Ph:     02 9460 7676 '. PHP_EOL.'Fax:    02 9460 8913 '. PHP_EOL.'Email:  dcfm@dcfm.com.au '. PHP_EOL.'DCFM Australia Pty Ltd '. PHP_EOL.'ABN   69 122487 076';
                    $data['emailData'] = $emailData;
                }
                
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
     * this function use for send email for selected invoices
     * 
     * @return json
     */
    public function sendEmailInvoices() {

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
            
            $invoicenos = $this->input->post('invoices');
            $esubject = $this->input->post('subject');
            $emessage = $this->input->post('message');
            $erecipients = $this->input->post('recipients');
            
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $customerid = $this->session->userdata('raptor_customerid');
            
                $docA = array();

                foreach ($invoicenos as $key => $value) {
                    $request = array(
                        'invoiceno'        => $value,
                        'open'             => false,
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );

                    //generate pdf and show it
                    $this->invoiceclass->createInvoicePDF($request);
                    $doc = array();
                    $doc['fname'] = "invoice_" . $value . ".pdf";
                    $doc['dname'] = "invoice_" . $value . ".pdf";
                    $doc['relpath'] = $this->config->item('invoicedir');
                    $docA[] = $doc;
                }

                $emailData = array(
                    'recipient'   => $erecipients, 
                    'cc'          => "dcfm@dcfm.com.au", 
                    'customerid'  => $customerid, 
                    'subject'     => $esubject,
                    'message'     => $emessage,
                    'docsA'       => $docA,
                    'replyto'     => "dcfm@dcfm.com.au" 
                );
  
                $request = array(
                    'emailData'        => $emailData,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->sharedclass->createEmailLog($request);   
                 
                $message = 'Invoice Emailed to recipients customer email ids.';
        
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
     * this function use for load budget spend chart
     * 
     * @return json
     */
    public function loadBudgetSpend() {

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
            
            $spendbudgettype = $this->input->post('spendbudgettype');
            $fromdate = $this->input->post('fromdate');
            $todate = $this->input->post('todate');
            $customerid = $this->session->userdata('raptor_customerid');
            
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $singleLevelApporval = isset($this->ContactRules["show_invoicefinalize_tab_in_clientportal"]) && $this->ContactRules["show_invoicefinalize_tab_in_clientportal"] == 1 ? true : false;
                $twoLevelApporval = isset($this->ContactRules["show_final_approval_tab_in_clientportal"]) && $this->ContactRules["show_final_approval_tab_in_clientportal"] == 1 ? true : false;

                $approvals = array(
                    'singleLevelApporval' => $singleLevelApporval,
                    'twoLevelApporval'    => $twoLevelApporval
                );

                $data = $this->invoiceclass->getBudgetSpendData($customerid, $spendbudgettype, to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT), to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT), $approvals);

                $chartCategories = array();
                $chartSeries = array(
                    array(
                        'name' => 'Remaining',
                        'data' => array()   
                    ),
                    array(
                        'name' => 'Open WO',
                        'data' => array()   
                    ),
                    array(
                        'name' => 'Quoted',
                        'data' => array()   
                    ),
                    array(
                        'name' => 'Unapproved',
                        'data' => array()   
                    ),
                    array(
                        'name' => 'Approved',
                        'data' => array()   
                    )
                );

                foreach($data['openwo'] as $key=>$value) {
                    if(is_array($value)) {
                        if($spendbudgettype == 'glcode') {
                            $chartCategories[] = $value['glcode'];
                            $chartSeries[1]['data'][] = (double)$value['netval'];
                        } else {
                            $chartCategories[] = $value['siteref'];
                            $chartSeries[1]['data'][] = (double)$value['netval'];
                        }
                    }
                }

                foreach($data['quoted'] as $key=>$value) {
                    if(is_array($value)) {
                        if($spendbudgettype == 'glcode') {
                            $chartCategories[] = $value['glcode'];
                            $chartSeries[2]['data'][] = (double)$value['netval'];
                        } else {
                            $chartCategories[] = $value['siteref'];
                            $chartSeries[2]['data'][] = (double)$value['netval'];
                        }
                    }
                }

                foreach($data['unapproved'] as $key=>$value) {
                    if(is_array($value)) {
                        if($spendbudgettype == 'glcode') {
                            $chartCategories[] = $value['glcode'];
                            $chartSeries[3]['data'][] = (double)$value['netval'];
                        } else {
                            $chartCategories[] = $value['siteref'];
                            $chartSeries[3]['data'][] = (double)$value['netval'];
                        }
                    }
                }

                foreach($data['approved'] as $key=>$value) {
                    if(is_array($value)) {
                        if($spendbudgettype == 'glcode') {
                            $chartCategories[] = $value['glcode'];
                            $chartSeries[4]['data'][] = (double)$value['netval'];
                        } else {
                            $chartCategories[] = $value['siteref'];
                            $chartSeries[4]['data'][] = (double)$value['netval'];
                        }
                    }
                }

                $chartCategories = array_unique($chartCategories);
                $chartCategories =  array_values($chartCategories);

                for ($x = 0; $x < count($chartCategories); $x++)
                {
                    array_push($chartSeries[0]['data'], 0);
                }

                foreach($chartCategories as $key=>$value) {
                    $amount = 0;
                    foreach($chartSeries as $key1=>$value1) {
                        if($value1['name'] == 'remaining') {
                            continue;
                        }
                        if(count($value1['data']) > 0) {
                            if(isset($value1['data'][$key])) {
                                $amount = $amount + (double)$value1['data'][$key];
                            }
                        }
                    }

                    foreach($data['budget'] as $key2=>$value2) {
                        if($spendbudgettype == 'glcode') {
                            if(isset($value2['glcode']) && $value2['glcode'] == $value) {
                                $chartSeries[0]['data'][$key] = (double)$value2['netval'] - $amount;
                            }
                        } else {
                            if(isset($value2['siteref']) && $value2['siteref'] == $value) {
                                $chartSeries[0]['data'][$key] = (double)$value2['netval'] - $amount;
                            }
                        }
                    }
                }

                foreach($data['budget'] as $key=>$value) {
                    if($spendbudgettype == 'glcode') {
                        if(isset($value['glcode']) && !in_array($value['glcode'], $chartCategories)) {
                            $chartCategories[] = $value['glcode'];
                            $key1 = array_search($value['glcode'], $chartCategories);
                            $chartSeries[0]['data'][] = (double)$value['netval'];
                        }
                    } 
                    else {
                        if(isset($value['siteref']) && !in_array($value['siteref'], $chartCategories)) {
                            $data['budget']['ee'] = $value['siteref'];
                            $chartCategories[] = $value['siteref'];
                            $key1 = array_search($value['siteref'], $chartCategories);
                            $chartSeries[0]['data'][] = (double)$value['netval'];
                        }
                    }
                }
 
                $data = array(
                    'categories' => $chartCategories,
                    'series'     => $chartSeries 
                );
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
     * this function use for create Batch Invoices
     * 
     * @return json
     */
    public function createBatchInvoices() {
        
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
            
            $invoicenos = $this->input->post('invoices');
             
            if (count($invoicenos)==0)  {
                $message = 'Please Select invoices';
            }
 
            $create_batchinvoice =  $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'CREATE_BATCHINVOICE');
            if(!$create_batchinvoice) {
                $message = 'You have not permission to create batch invoice.';
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $request = array(
                    'invoicenos'       => $invoicenos,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $response = $this->invoiceclass->createBatchInvoice($request);
              
                if(isset($response['batchid'])){
                    
                    $recipients = isset($this->data['ContactRules']["batchinvoice_recipients"]) ? trim($this->data['ContactRules']["batchinvoice_recipients"]) : '';
                    if(trim($recipients) != '') {
                        
                        $this->exportBatchInvoice($response['batchid'], false);
                        $request = array(
                            'batchid'          => $response['batchid'],
                            'recipients'       => $recipients,
                            'open'             => FALSE,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        //generate pdf and Email
                        $this->invoiceclass->batchInvoiceEmail($request); 
                        
                        $updateBatchData = array(
                            'esentdate' => date('Y-m-d H:i:s', time())
                        );

                        $request = array(
                            'batchid'           => $response['batchid'],
                            'updateBatchData'  => $updateBatchData,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $this->invoiceclass->updateBatchInvoice($request);
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
     * this function use for Export Batch Invoice 
     * 
     * @param integer $batchid
     * @param boolean $download
     * 
     * @return void
     */
    public function exportBatchInvoice($batchid, $download = true) {
        
        if(!is_numeric($batchid)) {
            die('Invalid batch id');
        }
         
        $this->load->library('excel');
        
        $invoiceData = $this->invoiceclass->getBatchInvoice($batchid);
        
        $heading = array('Invoice No.', 'Invoice Date', 'Order Ref 1', 'Order Ref 2', 'Order Ref 3',
                    'Job id', 'Amount', 'Site FM', 'Approved by', 'Approvaldate', 'Suburb',
                    'State', 'Site Ref', 'Cost Centre', 'GL Code');
        
        $data_array = array();
        foreach ($invoiceData as $row1) {
            $result = array();

            $result[] = $row1['invoiceno'];
            $result[] = format_date($row1['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = $row1['custordref'];
            $result[] = $row1['custordref2'];
            $result[] = $row1['custordref3'];
            $result[] = $row1['jobid'];
            $result[] = format_amount($row1['amount']);
            $result[] = $row1['sitefm'];
            $result[] = $row1['approvedby'];
            $result[] = $row1['approvaldate'];
            $result[] = $row1['sitesuburb'];
            $result[] = $row1['sitestate'];
            $result[] = $row1['siteref'];
            $result[] = $row1['costcentre'];
            $result[] = $row1['glcode'];

            $data_array[] = $result;
        }
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(17);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
            
        //Load the file helper and write the file to your server
        //$dir = "./temp";
        $dir = $this->config->item('invoicedir');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file_name =  "batch_invoice_".$batchid.".xls";
        $this->excel->Exportexcel("Batch Invoices", $dir, $file_name, $heading, $data_array);
        if($download == true) {
            $this->load->helper('download'); 
            $data = file_get_contents($this->config->item('invoicepath'). $file_name);
            force_download($file_name, $data);
        }
    }
    
    public function batchInvoiceEmail() {
        
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
            
            $batchid = $this->input->post('batchid');
            $recipient = $this->input->post('recipient');
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $this->exportBatchInvoice($batchid, false);
                $request = array(
                    'batchid'          => $batchid,
                    'recipients'       => $recipient,
                    'open'             => FALSE,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );

                //generate pdf and Email
                $this->invoiceclass->batchInvoiceEmail($request);
               
                $message = 'Request invoices Send to recipients';
                 
             
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
   
    public function updateBatchInvoice() {
        
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
            
            $batchid = $this->input->post('batchid');
             
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $updateBatchData = array(
                    'esentdate' => date('Y-m-d H:i:s', time())
                );
                 
                $request = array(
                    'batchid'       => $batchid,
                    'updateBatchData'  => $updateBatchData,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->invoiceclass->updateBatchInvoice($request);
                
                $message = 'Send date updated.'; 
             
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
