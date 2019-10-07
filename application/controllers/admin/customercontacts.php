<?php 
/**
 * Customercontacts Controller Class
 *
 * This is a Customercontacts controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customercontacts Controller Class
 *
 * This is a Customercontacts controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Customercontacts
 * @filesource          Customercontacts.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Customercontacts extends MY_Controller {

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
     * show addresses
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
            base_url('assets/js/admin/admin.customercontacts.js')
        );
        
      
        $this->data['states'] =$this->sharedclass->getStates(1);
        $this->data['contact_roles'] = $this->customerclass->getContactRole();
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Customer Contacts')
                ->set_layout($this->layout)
                ->set('page_title', 'Customer Contacts')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Customer Contacts', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/customercontacts', $this->data);
    }
    
    /**
    * This function search customers for autocomplete
    * 
    * @return json 
    */
    public function loadCustomerSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $searchKeyword = trim($this->input->get('search'));
            $custtype = 'client';
             
            if( !isset($searchKeyword) ){
                $message = 'Search Keyword cannot be null.';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->customerclass->getCustomerSearch($searchKeyword, $custtype);
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
    * This function search contacts for autocomplete
    * 
    * @return json 
    */
    public function loadContactSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $searchKeyword = trim($this->input->get('search'));
            $customerid= 0;
            if($this->input->get('customerid')) {
                $customerid = trim($this->input->get('customerid'));
            }
            
            if( !isset($searchKeyword) )
                $message = 'Search Keyword cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->customerclass->getCustomerContacts($customerid, 0, $searchKeyword);
               
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
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadCustomerContacts() {
        
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
                $order = 'desc';
                $field = 'created_on';
                $filter = '';
                $params = array();
                $rolea = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('updatestatuscontactid')) != '') {
                    $updatestatuscontactid = $this->input->get('updatestatuscontactid');
                    $contactData = $this->customerclass->getContactById($updatestatuscontactid);
                    if(count($contactData) > 0){
                        $updateData = array();
                        if($contactData['cp_invitesendtime'] != NULL){
                            $updateData['cp_invitesendtime'] = NULL;
                            $updateData['active'] = 0;
                        }
                        else{
                            if($contactData['active'] == 1){
                                $updateData['active'] = 0;
                            }else{
                                $updateData['active'] = 1;
                            }
                        }
                        
                        $request = array(
                            'updateContactData' => $updateData,  
                            'contactid'         => $updatestatuscontactid,  
                            'logged_contactid'  => $this->session->userdata('raptor_contactid')
                        );

                        $this->customerclass->updateContact($request);
                    }
                }
                
                if (trim($this->input->get('state')) != '') {
                    $params['c.state'] = $this->input->get('state');
                }
                $extraWhere ='';
                if (trim($this->input->get('status')) != '') {
                    if (trim($this->input->get('status')) == 'Invited') {
                         
                        $extraWhere  = 'c.cp_invitesendtime IS NOT NULL' ;
                    }
                    elseif (trim($this->input->get('status')) == 'Inactive') {
                        $params['c.active'] = '0';
                    }
                    elseif (trim($this->input->get('status')) == 'Active') {
                        $params['c.active'] = 1;
                    }
                     
                }

                if (is_array($this->input->get('role'))) {
                    $role = $this->input->get('role');
                    foreach ($role as $key => $value) {
                       $rolea[] = $value; 
                    }
                    $params['c.role'] = $rolea;
                } else {
                    $params['c.role'] = $this->input->get('role');
                }

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                $customerid =0;
                if (trim($this->input->get('customerid')) != '') {
                    $customerid = $this->input->get('customerid');
                    
                    //get contacts data
                    $contactData = $this->customerclass->getContacts($customerid, $size, $start, $field, $order, $filter, $params, $extraWhere);

                    $trows  = $contactData['trows'];
                    $data = $contactData['data'];

                    //format data
                    foreach($data as $key=>$value) {
                        $data[$key]['cp_invitesendtime'] = format_datetime($value['cp_invitesendtime'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                        if($value['last_login'] == NULL || $value['last_login'] ==0){
                             $data[$key]['last_login'] = '';
                        }
                        else{
                             $data[$key]['last_login'] = date(RAPTOR_DISPLAY_DATEFORMAT .' '. RAPTOR_DISPLAY_TIMEFORMAT, $value['last_login']);
                        }
                    }
 
                }
                else{
                    $trows  = 0;
                    $data = array();
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
  
       //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));  
    }
    
    /**
    * This function search contacts for autocomplete
    * 
    * @return json 
    */
    public function loadContactMenu() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $contactid = trim($this->input->get('contactid'));
            
            
            if( !isset($contactid) )
                $message = 'Contact cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data['contactData'] =  $this->customerclass->getContactById($contactid);
                $data['roles'] = $this->sharedclass->getRole();
                
                $findRole = FALSE;
                foreach ($data['roles'] as $key => $value) {
                     
                    if($value['role'] == $data['contactData']['role']){
                        $findRole = TRUE;
                        break;
                    }
                    
                }
                if ($findRole == FALSE){
                    $data['roles'][] = array('role'=>$data['contactData']['role']);
                }
                $modules = $this->adminclass->getMenuModule();
                 
                $RoleMenuModule = $this->sharedclass->getNavigation($data['contactData']['customerid'], $data['contactData']['role']);
                $contactMenuModule = $this->customerclass->getContactMenuModule($contactid);
                $overrideModuleData = $RoleMenuModule;
                if(count($contactMenuModule)>0){
                    $overrideModuleData = $contactMenuModule;
                }
                foreach ($overrideModuleData as $key => $value) {
                    foreach ($modules as $key2 => $value2) {
                        if($value['id'] == $value2['id']){
                            $modules[$key2]['visible'] = 1;
                            break;
                        }
                    }
                }
                $data['modules'] = $modules;
                
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
    * This function use for update Contact Menu Access
    *
    * @return json
    */
    public function updateContactMenuAccess() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $contactid = $this->input->post('contactid');
            $moduleids = $this->input->post('moduleid');
      
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
                //create update Contact Menu Access
                $updateData = array();
                
                if (is_array($moduleids)) {
                    
                    foreach ($moduleids as $key => $value) {
                        $updateData[] = array(
                            'contactid'  => $contactid,
                            'moduleid'   => $value,
                            'isactive'   => 1
                        ); 
                    }
                   
                } else {
                    $updateData[] = array(
                        'contactid'  => $contactid,
                        'moduleid'   => $moduleids,
                        'isactive'   => 1
                    ); 
                }
                
                $this->adminclass->deleteContactModule($contactid);
                $this->adminclass->InsertContactModule($updateData);
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
  
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
}