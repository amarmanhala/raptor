<?php 
/**
 * Customersummary Controller Class
 *
 * This is a Customersummary controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customersummary Controller Class
 *
 * This is a Customersummary controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Customersummary
 * @filesource          Customersummary.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Customersummary extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();  
        if($this->data['loggeduser']->israptoradmin != 1){
            show_404();
        }
        
        $this->load->library('admin/AdminClass');
         
    }

    /**
    * This function use for show customer summary
    * 
    * @return void
    */ 
    public function index() {
        
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css')
        );


        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.customersummary.js')
        );
        
        $this->data['routes'] =$this->adminclass->getMenuModule();
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Customer Summary')
                ->set_layout($this->layout)
                ->set('page_title', 'Customer Summary')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Customer Summary', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/customersummary', $this->data);
        
    }
    
    /**
    * This function use for load Portal Settings Audit log in uigrid
    * 
    * @return json 
    */
    public function loadCustomerSummary() {
        
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
                $size = 10;
                $order = 'asc';
                $field = 'companyname';
          
                $params = array(); 

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }

                 

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
             
                if (trim($this->input->get('customerid')) != '') {
                    $params['c.customerid'] = $this->input->get('customerid');
                }
                //get contacts data
                $contactData = $this->adminclass->getCustomerSummary($size, $start, $field, $order, $params);

                $trows  = $contactData['trows'];
                $data = $contactData['data'];

                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['lastcplogin'] = format_datetime($value['lastcplogin'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                
                    
                }
 
                 
               
                
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
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    
     /**
     * this function use for assign Customer CP
     * 
     * @return json
     */
    public function assignCustomerCP() {
 
 
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
            
            $customerid = $this->input->post('customerid');
           
            if ($customerid == '')  {
                $message = 'Please Select Customer';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $this->adminclass->assignCustomerClientPortal($customerid);
                 
                
                $message = 'Successfullu Assigned to the client portal';
        
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
     * this function use for update Customer CP
     * 
     * @return json
     */
    public function updateCustomerCPStatus() {
 
 
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
            
            $customerid = $this->input->post('customerid');
           
            if ($customerid == '')  {
                $message = 'Please Select Customer';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $status = $this->input->post('status');
                if($status == 1){
                    $updateCustomerData = array(
                        'cpstatus'      => 1,
                        'cpstatusnote'  => 'Activated on : ' . date(RAPTOR_DISPLAY_DATEFORMAT .' '. RAPTOR_DISPLAY_TIMEFORMAT)
                    );
                }
                else{
                    $updateCustomerData = array(
                        'cpstatus'      => 0,
                        'cpstatusnote'  => 'Deactivated on : ' . date(RAPTOR_DISPLAY_DATEFORMAT .' '. RAPTOR_DISPLAY_TIMEFORMAT)
                    );
                }
               

                $request = array(
                    'customerid'            => $customerid,
                    'updateCustomerData'    => $updateCustomerData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->customerclass->updateCustomer($request);
                 
                
                $message = 'Status changed for access to the client portal';
        
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