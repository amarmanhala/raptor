<?php 
/**
 * Purchase Orders Controller Class
 *
 * This is a Purchase Orders controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Purchase Orders Controller Class
 *
 * This is a Purchase Orders controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            PurchaseOrders
 * @filesource          purchaseorders.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class PurchaseOrders extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();  
        
        $this->load->library('purchaseorder/PurchaseOrderClass');
        
    }

    public function index()
    { 
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/uigrid/ui-grid-stable.min.css')
        );


        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('plugins/input-mask/jquery.inputmask.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'), 
            base_url('plugins/highcharts/js/highcharts.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/purchaseorders/purchaseorders.index.js'),
            base_url('assets/js/purchaseorders/purchaseorders.chart.js')
        );
        
       
        $contactid = $this->session->userdata('raptor_contactid');
        $customerid = $this->session->userdata('raptor_customerid');
        $this->data['status'] =$this->purchaseorderclass->getCustomerPurchaseOrderStatus(1);
        $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($customerid);
  
        
        
        $this->data['ADD_PURCHASE_ORDER'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_PURCHASE_ORDER');
        $this->data['EXPORT_PURCHASE_ORDER'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_PURCHASE_ORDER');
        $this->data['EDIT_PURCHASE_ORDER'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_PURCHASE_ORDER');
        $this->data['DELETE_PURCHASE_ORDER'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_PURCHASE_ORDER');
        
        
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Purchase Orders')
                ->set_layout($this->layout)
                ->set('page_title', 'Purchase Orders')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('purchaseorders/index', $this->data);
    }
    
    /**
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadPurchaseOrders() {
        
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
                $field = 'date_added';
                $filter = '';
                $fromdate = '';
                $todate = '';
                $params = array();  
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('glcode')) != '') {
                    $params['cpo.glcode'] = $this->input->get('glcode');
                }
             
                
                if (trim($this->input->get('status')) != '') {
                    $params['cpo.status_id'] = $this->input->get('status');
                }

                if (trim($this->input->get('fromdate')) != '') {
                    $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if (trim($this->input->get('todate')) != '') {
                    $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data
                $contactData = $this->purchaseorderclass->getCustomerPurchaseOrders($customerid, $size, $start, $field, $order, $fromdate, $todate, $filter, $params);
                 
                $trows  = $contactData['trows'];
                $podata = $contactData['data'];
            
                //format data
                foreach($podata as $key=>$value) {
                    $podata[$key]['fromdate'] = format_date($value['fromdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $podata[$key]['todate'] = format_date($value['todate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $podata[$key]['date_added'] = format_date($value['date_added'], RAPTOR_DISPLAY_DATEFORMAT);
                
                    $podata[$key]['amount_ex_tax_str'] = format_amount($value['amount_ex_tax']);
                    $podata[$key]['amount_used_str'] = format_amount($value['amount_used']);
                    $podata[$key]['amount_remaining_str'] = format_amount($value['amount_remaining']);
                }
                
                $ContactRules=$this->data['ContactRules'];
                $CUSTOMER_PO_WARNING_AMOUNT = isset($ContactRules["CUSTOMER_PO_WARNING_AMOUNT"]) ? (float)$ContactRules["CUSTOMER_PO_WARNING_AMOUNT"] : 0;
                
                $totlremainexc = $this->purchaseorderclass->getCustomerTotalRemainingExcWip($customerid);
                $totlremaininc = $this->purchaseorderclass->getCustomerTotalRemainingIncWip($customerid);
                $remaining = array(
                    'totlremainexc' => format_amount($totlremainexc),
                    'totlremaininc' => format_amount($totlremaininc),
                );
                if($totlremainexc <= 0){ 
                    $remaining['totlremainexccolor'] =  'label-danger'; 
                    
                } elseif ($totlremainexc <= $CUSTOMER_PO_WARNING_AMOUNT){ 
                    $remaining['totlremainexccolor'] =  'label-warning';
                    
                }  elseif ($totlremainexc > $CUSTOMER_PO_WARNING_AMOUNT){ 
                    $remaining['totlremainexccolor'] =  'label-success';
                    
                } else{    
                    $remaining['totlremainexccolor'] =  'label-success';
                    
                }
                
                if($totlremaininc <= 0){ 
                    $remaining['totlremaininccolor'] =  'label-danger'; 
                    
                } elseif ($totlremaininc <= $CUSTOMER_PO_WARNING_AMOUNT){ 
                    $remaining['totlremaininccolor'] =  'label-warning';
                    
                }  elseif ($totlremaininc > $CUSTOMER_PO_WARNING_AMOUNT){ 
                    $remaining['totlremaininccolor'] =  'label-success';
                    
                } else{    
                    $remaining['totlremaininccolor'] =  'label-success';
                    
                }
                $data= array(
                    'podate' => $podata,
                    'remaining' => $remaining
                );
                
                $success->setData($data); 
                $success->setTotal($trows);
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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportPurchaseOrders() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_PURCHASE_ORDER');
        if(!$export_excel) {
            show_404();
        }
        
        $order = 'desc';
        $field = 'date_added';
        $filter = '';
        $fromdate = '';
        $todate = '';
        $params = array();  
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('glcode')) != '') {
            $params['cpo.glcode'] = $this->input->get('glcode');
        }


        if (trim($this->input->get('status')) != '') {
            $params['cpo.status_id'] = $this->input->get('status');
        }

        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }
        //get customerid
        $customerid =$this->session->userdata('raptor_customerid');

        //get contacts data
        $contactData = $this->purchaseorderclass->getCustomerPurchaseOrders($customerid, NULL, 0, $field, $order, $fromdate, $todate, $filter, $params);
               
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('PO Number', 'From', 'To', 'Amount', 'GL Code', 'Added Date', 'Amount Used', 'Remaining', 'Status');
        $this->load->library('excel');

      
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = $row['ponumber'];
            $result[] = format_date($row['fromdate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = format_date($row['todate'], RAPTOR_DISPLAY_DATEFORMAT); 
            $result[] = format_amount($row['amount_ex_tax']);
            $result[] = $row['glcode'];
            $result[] = format_date($row['date_added'], RAPTOR_DISPLAY_DATEFORMAT);;
            $result[] = format_amount($row['amount_used']);
            $result[] = format_amount($row['amount_remaining']);
            $result[] = $row['status'];
            
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "purchaseorders.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Purchase Orders", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('PurchaseOrders.xls', $data);
    }
    
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deletePurchaseOrder() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customer_po_id = $this->input->post('id');
             $ponumber = $this->input->post('ponumber');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_PURCHASE_ORDER');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Purchase Order.';
            }
             
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $request = array(
                    'customer_po_id'    => $customer_po_id,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->purchaseorderclass->deleteCustomerPurchaseOrder($request);
                
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'ponumber'              => $ponumber,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->deleteCustomerPurchaseOrderJob($request);
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
    * This function use for add new contact for Supplier
    * 
    * @return json 
    */
    public function savePurchaseOrder() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_PURCHASE_ORDER');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_PURCHASE_ORDER');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Purchase Order.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
               
                //set form validation rule
                $this->form_validation->set_rules('ponumber', 'PO Number', 'trim|required|callback_check_customerponumber');
                $this->form_validation->set_rules('amount_ex_tax', 'Amount', 'trim|required');
                if (isset($this->data['ContactRules']["GL_CODE_MANDATORY"]) && $this->data['ContactRules']["GL_CODE_MANDATORY"] == "1") {
                    $this->form_validation->set_rules('glcode', 'GL Code', 'trim|required');
                }
                
                
                $this->form_validation->set_rules('fromdate', 'From Date', 'trim|required');
                $this->form_validation->set_rules('todate', 'To Date', 'trim|required'); 
                
               
                
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors()
                    );
                }
                else {
                     $gsrate =(float)$this->sharedclass->getSysValue('gstrate');
                     $tax = (float)trim($this->input->post('amount_ex_tax'))*$gsrate;
                     $amount_inc_tax = $tax + (float)trim($this->input->post('amount_ex_tax'));
                    //intialize array for insert
                    $contactData = array(
                        'customerid'    => $this->data['loggeduser']->customerid, 
                        'ponumber'     => trim($this->input->post('ponumber')), 
                        'amount_ex_tax'       => trim($this->input->post('amount_ex_tax')),
                        'tax'               => $tax,
                        'amount_inc_tax'    => $amount_inc_tax,
                        'glcode'      => trim($this->input->post('glcode')),
                        'description'       => trim($this->input->post('description')),
                        'fromdate'        => to_mysql_date(trim($this->input->post('fromdate'))),
                        'todate'        => to_mysql_date(trim($this->input->post('todate'))), 
                        'status_id'         => $this->purchaseorderclass->getPCustomerurchaseOrderStatusID('OPEN'),  
                        'date_added'      => date('Y-m-d H:i:s', time())
                    );
                     
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $customer_po_id = $this->input->post('customer_po_id');
                        $request = array(
                            'customer_po_id'          => $customer_po_id,
                            'updateCustomerPOData'  => $contactData,
                            'logged_contactid'   => $this->data['loggeduser']->contactid
                        );

                        $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                        $message = 'Purchase Order Updated successfully.';
                    }
                    else{
                         
                       //insert
                        $request = array(
                            'insertCustomerPOData' => $contactData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );
                        $response = $this->purchaseorderclass->insertCustomerPurchaseOrder($request);
                        $customer_po_id = $response['customer_po_id'];
                        $message = 'Purchase Order Added successfully.';
                    }
                  
                    $data = array (
                        'success'   => TRUE,
                        'data'      => array(),
                        'message'   => ''
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
     * check_technician
     * @param string $userid
     * @return boolean
    */
    public function check_customerponumber($ponumber)
    {

        if (empty($ponumber)){
            return FALSE;
        }
        
        $customer_po_id = 0;
        $customerid = $this->data['loggeduser']->customerid;
        if (trim($this->input->post('mode')) == 'edit') {
            $customer_po_id = trim($this->input->post('customer_po_id'));
            
        }
        
        
        if($this->purchaseorderclass->checkCustomerPONumber($customerid, $ponumber, $customer_po_id)){
            $this->form_validation->set_message('check_customerponumber', 'PO Number Already exist for this contract');
            return false;
        }
        else
        {
            return TRUE;
        }

    }
    
    
    /**
    * This function use for Cancel Purchase order
    * 
    * @return void
    */
    public function cancelPurchaseOrder() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customer_po_id = $this->input->post('id');
            $ponumber = $this->input->post('ponumber');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_PURCHASE_ORDER');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to Cancel Purchase Order.';
            }
             
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $contactData = array(
                    'status_id' => $this->purchaseorderclass->getPCustomerurchaseOrderStatusID('CANCELLED')
                );
                           
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'updateCustomerPOData'  => $contactData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                
                
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'ponumber'              => $ponumber,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->deleteCustomerPurchaseOrderJob($request);
                
                 $message = 'Purchase Order CANCELLED successfully.';
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
    * This function use for Cancel Purchase order
    * 
    * @return void
    */
    public function closePurchaseOrder() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customer_po_id = $this->input->post('id');
            $ponumber = $this->input->post('ponumber');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_PURCHASE_ORDER');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to Close Purchase Order.';
            }
             
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $contactData = array(
                    'status_id' => $this->purchaseorderclass->getPCustomerurchaseOrderStatusID('CLOSED')
                );
                           
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'updateCustomerPOData'  => $contactData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                
                 $message = 'Purchase Order CLOSED successfully.';
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
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadPurchaseOrderJobs() {
        
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
                $loggeddate = '';   
                $ponumber = $this->input->get('ponumber');

                if (trim($this->input->get('loggeddate')) != '') {
                    $loggeddate = to_mysql_date($this->input->get('loggeddate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                $customerid =$this->session->userdata('raptor_customerid');
                 
                $unAllocatedJobs = $this->purchaseorderclass->getCustomerPOUnAllocatedJobs($customerid, $loggeddate);
               
                //format data
                foreach($unAllocatedJobs as $key=>$value) {
                    $unAllocatedJobs[$key]['temp_leaddate'] = strtotime($value['leaddate']);
                    $unAllocatedJobs[$key]['temp_jcompletedate'] = strtotime($value['jcompletedate']);
                    $unAllocatedJobs[$key]['leaddate'] = format_date($value['leaddate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $unAllocatedJobs[$key]['jcompletedate'] = format_date($value['jcompletedate'], RAPTOR_DISPLAY_DATEFORMAT);
                   
                    $unAllocatedJobs[$key]['wip_str'] = format_amount($value['wip']);
                    $unAllocatedJobs[$key]['invoiced_str'] = format_amount($value['invoiced']);
                    
                }
                 
                $allocatedJobs = $this->purchaseorderclass->getCustomerPOAllocatedJobs($customerid, $ponumber);
             
                //format data
                foreach($allocatedJobs as $key=>$value) {
                    $unAllocatedJobs[$key]['temp_leaddate'] = strtotime($value['leaddate']);
                    $unAllocatedJobs[$key]['temp_duedate'] = strtotime($value['duedate']);
                    $allocatedJobs[$key]['leaddate'] = format_date($value['leaddate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $allocatedJobs[$key]['duedate'] = format_date($value['duedate'], RAPTOR_DISPLAY_DATEFORMAT);
                    
                    $allocatedJobs[$key]['wip_str'] = format_amount($value['wip']);
                    $allocatedJobs[$key]['invoiced_str'] = format_amount($value['invoiced']);
                }
                
                $data = array(
                    'unAllocatedJobs'   => $unAllocatedJobs,
                    'allocatedjobs'     => $allocatedJobs
                );
                
                $success->setData($data);  
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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportPurchaseOrderAllocatedJobs() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_PURCHASE_ORDER');
        if(!$export_excel) {
            show_404();
        }
        
      
      
        $ponumber = $this->input->get('ponumber');
      
 
        //get customerid
        $customerid =$this->session->userdata('raptor_customerid');

        //get contacts data
        $allocatedJobs = $this->purchaseorderclass->getCustomerPOAllocatedJobs($customerid, $ponumber);
               
        $data = $allocatedJobs['data']; 

        $data_array = array();
        $ContactRules=$this->data['ContactRules'];
        $heading = array(
            'PO Number', 
            isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',
            'Job ID',
            'Log Date',
            'Due Date',
            'State',
            'Suburb',
            'Cost Center', 
            'Site Ref',
            'Job Stage',
            'Quoted',
            'WIP',
            'Invoiced',
            'Invoice No'
            );
        $this->load->library('excel');

      
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = $row['ponumber'];
            $result[] = $row['custordref'];
            $result[] = $row['jobid'];
            $result[] = format_date($row['leaddate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = format_date($row['duedate'], RAPTOR_DISPLAY_DATEFORMAT); 
            $result[] = $row['sitestate'];
            $result[] = $row['sitesuburb'];
            $result[] = $row['accountname'];
            $result[] = $row['siteref'];
            $result[] = $row['portaldesc'];
            $result[] = $row['quoterqd']=='on'? 'Yes':'No';
            $result[] = format_amount($row['wip']);
            $result[] = format_amount($row['invoiced']);
            $result[] = $row['custinvoiceno'];
            
            
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "purchaseorderallocatedJobs.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Purchase Orders Allocated jobs", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('purchaseorderallocatedJobs.xls', $data);
    }
    
     /**
    * This function use for allocate Job To PO
    * 
    * @return void
    */
    public function allocateJobToPO() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customer_po_id = $this->input->post('id');
            $jobids = $this->input->post('jobids');
            $ponumber = $this->input->post('ponumber');
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                
                $amount = 0;
                $insertCustomerPOJobData = array();
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                $selectedJobdData =  $this->purchaseorderclass->getCustomerPOUnAllocatedJobs($customerid, "", $jobids);
                
                foreach ($selectedJobdData as $key => $value) {
                    $amount = $amount + (float)$value['wip']+(float)$value['invoiced'];
                    $insertCustomerPOJobData[] = array(
                        'customerid'    => $customerid,
                        'jobid'    => $value['jobid'],
                        'ponumber'    => $ponumber
                    );
                   
                }
                 
                $request = array(
                    'insertCustomerPOJobData'        => $insertCustomerPOJobData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->insertCustomerPurchaseOrderJob($request);
                
                $poData= $this->purchaseorderclass->getCustomerPOById($customer_po_id);
                
                $contactData = array(
                    'amount_used' => $poData['amount_used'] + $amount
                );
                           
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'updateCustomerPOData'  => $contactData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                
               
                
                $message = 'Job Allocated To PO.';
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
    * This function use for Cancel Purchase order
    * 
    * @return void
    */
    public function removeJobToPO() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customer_po_id = $this->input->post('id');
            $jobids = $this->input->post('jobids');
            $ponumber = $this->input->post('ponumber');
             
            $isSuccess = ( $message == "" ); 
            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                
                $amount = 0;
               
                $customerid =$this->session->userdata('raptor_customerid');
                $selectedJobdData =  $this->purchaseorderclass->getCustomerPOAllocatedJobs($customerid, $ponumber, $jobids);
                
                foreach ($selectedJobdData as $key => $value) {
                    $amount = $amount + (float)$value['wip']+(float)$value['invoiced'];
                     
                   
                }
                
                $request = array(
                    'jobid'        => $jobids,
                    'ponumber'              => $ponumber,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->deleteCustomerPurchaseOrderJob($request);
                
                $poData= $this->purchaseorderclass->getCustomerPOById($customer_po_id);
                
                $contactData = array(
                    'amount_used' => $poData['amount_used'] - $amount
                );
                           
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'updateCustomerPOData'  => $contactData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                 
                 $message = 'Job Removed From Purchase Order successfully.';
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
    * This function use for Cancel Purchase order
    * 
    * @return void
    */
    public function recalculatePOTotal() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customer_po_id = $this->input->post('id');
            $ponumber = $this->input->post('ponumber');
         
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                 
                $contactData = array(
                    'amount_invoiced' => $this->purchaseorderclass->getCustomerPOTotalInvoicedAmount($this->data['loggeduser']->customerid, $ponumber),
                    'amount_wip'      => $this->purchaseorderclass->getCustomerPOTotalWIPAmount($this->data['loggeduser']->customerid, $ponumber)
                    
                );
                $contactData['amount_used'] = $contactData['amount_invoiced'] + $contactData['amount_wip'];
                $request = array(
                    'customer_po_id'        => $customer_po_id,
                    'updateCustomerPOData'  => $contactData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                 
                $message = 'Purchase Order Recalculated successfully.';
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
    * This function use for loadchart
    * 
    * @return void
    */
    public function loadChart() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $glcode = $this->input->get('glcode');
            $fromdate = $this->input->get('fromdate');
            $todate = $this->input->get('todate');
             
            if($fromdate == ''){
                $fromdate = format_date(date('Y-01-01'), RAPTOR_DISPLAY_DATEFORMAT);
            }
            if($todate == ''){
                $todate = format_date(date('Y-12-31'), RAPTOR_DISPLAY_DATEFORMAT);
            }
            $isSuccess = ( $message == "" ); 
            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                
                $amount = 0;
               
                $customerid =$this->session->userdata('raptor_customerid');
                $tempFromDate = strtotime(to_mysql_date($fromdate));
                $tempToDate = strtotime(to_mysql_date($todate));
                $montharray = array();
                $POValueamtarray = array();
                $Actualamtarray = array();
                
                $Budgetarray = array();
                $Spendarray = array();
                 

               
                
                while($tempFromDate<=$tempToDate)
                {
                    $fdate = $tempFromDate;
                    $montharray[]=date('M', $tempFromDate);
                    $tempFromDate=strtotime("+1 month", $tempFromDate);
                    if(date('d', $tempFromDate)>1){
                        $tempFromDate=strtotime(date('Y-m-1', $tempFromDate));
                    }
                    if($tempFromDate<$tempToDate){
                        $tdate = strtotime("-1 day", $tempFromDate);
                    }
                    else{
                        $tdate= $tempToDate;
                    }
                    $povalue = $this->purchaseorderclass->getCustomerPOValues($customerid, date('Y-m-d',$fdate), date('Y-m-d',$tdate), $glcode);   
                    $spend = $this->purchaseorderclass->getCustomerPOSpendValue($customerid, date('Y-m-d',$fdate), date('Y-m-d',$tdate), $glcode);  
                    $POValueamtarray[]=  (int)$povalue;   
                    $Actualamtarray[]= (int)$spend; 
                    
                    $Budgetarray[]= (int)$povalue;   
                    $Spendarray[]= (int)$spend; 
                    
                }
  
                
                $barChartSeriesData=  array(
                    array(
                        'name'=>'PO Value',
                        'data'=>$Budgetarray
                    ),
                    array(
                        'name'=>'Actual',
                        'data'=>$Spendarray
                    )
                );
                
                $barChart =array(
                    'title'         => 'PO Value v Actual',
                    'xAxiscate'     => $montharray,
                    'yAxistitle'    => 'Amount $',
                    'seriesdata'     => $barChartSeriesData,
                );
                
                $lineChartSeriesData=  array(
                    array(
                        'name'  => 'PO Value',
                        'data'  => $POValueamtarray
                    ),
                    array(
                        'name'  => 'Actual',
                        'data'  => $Actualamtarray
                    )
                );
                $lineChart =array(
                    'title'         => '',
                    'xAxiscate'     => $montharray,
                    'yAxistitle'    => 'Amount $',
                    'seriesdata'     => $lineChartSeriesData,
                );
                
                $data['barChart']=$barChart;
                $data['lineChart']=$lineChart; 
                
                
               
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