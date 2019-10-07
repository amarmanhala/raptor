<?php 
/**
 * Admin Controller Class
 *
 * This is a Admin controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Controller Class
 *
 * This is a Admin controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Admin
 * @filesource          Admin.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Admin extends MY_Controller {

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

    public function index()
    {
       
        
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Admin Dashboard')
            ->set_layout($this->layout)
            ->set('page_title', 'Dashboard')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Admin', '')
            ->set_breadcrumb('Dashboard', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('admin/index', $this->data);
        
        
        
    }
        
 
    /**
     * Site Module
     * 
     * @return void
     */
    public function site()
    {

                
        $this->data['site_module'] = $this->adminclass->getSiteModule();
        
        if(!$this->input->post('sitemodule'))
        {
            
                    //render
                $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Site Status')
                    ->set_layout($this->layout)
                    ->set('page_title', 'Site Status')
                    ->set('page_sub_title', '')
                    ->set_breadcrumb('Admin', '')
                    ->set_breadcrumb('Site Status', '')
                    ->set_partial('page_header', 'shared/page_header')
                    ->set_partial('header', 'shared/header')
                    ->set_partial('navigation', 'shared/navigation')
                    ->set_partial('footer', 'shared/footer')
                    ->build('admin/sitemodule', $this->data);
        }
        else
        {
            $insertData = array(
                'sitestatus'        => (int)$this->input->post('sitestatus'),
                'sitemessagestatus' => (int)$this->input->post('sitemessagestatus'),
                'sitemessage'       => trim($this->input->post('sitemessage')),
                'sitemessagedate'   => date('Y-m-d H:i:s')
            );
            
             

            //check Add/Edit Mode
             if(count($this->data['site_module'])>0){
                $request = array(
                    'siteid'            => $this->data['site_module']['id'],
                    'updateData'        => $insertData,
                    'logged_contactid'  => $this->data['loggeduser']->contactid
                );

                $this->adminclass->updateSiteModule($request);

            }
            else{

                $request = array(
                    'insertData'        => $insertData, 
                    'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                );
                $this->adminclass->insertSiteModule($request);


            }
             
            
            $this->session->set_flashdata('success', "Site Module Updated.");
            redirect('admin/site');
        }
    }
        
    
     /**
     * activity logs
     * 
     * @return void
     */
    public function activityLogs()
    {
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.activitylog.js')
        );
         
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Activity Log')
                ->set_layout($this->layout)
                ->set('page_title', 'Activity Log')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Activity Log', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/activitylog', $this->data);
    }
    
    /**
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadActivityLogs() {
        
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
                $field = 'login';
                $filter = '';
                $params = array();
                $fromdate = '';
                $todate = '';

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('customerid')) != '') {
                    $params['c.customerid'] = $this->input->get('customerid');
                }
                
                if (trim($this->input->get('contactid')) != '') {
                    $params['c.contactid'] = $this->input->get('contactid');
                }
                if (trim($this->input->get('success')) != '') {
                    $params['l.success'] = $this->input->get('success');
                }
                
                if (trim($this->input->get('fromdate')) != '') {
                    $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if (trim($this->input->get('todate')) != '') {
                    $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
                }

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get audit Log Data
                $activityLogData = $this->adminclass->getActivityLog($size, $start, $field, $order, $fromdate, $todate, $params);
        
                $trows = $activityLogData['trows'];
                $data = $activityLogData['data'];
                
                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['login'] = format_datetime($value['login'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportActivityLogs() {
        
        
        $order = 'desc';
        $field = 'login';
  
        $fromdate = '';
        $todate = '';
        $params = array();

        if (trim($this->input->get('customerid')) != '') {
            $params['c.customerid'] = $this->input->get('customerid');
        }

        if (trim($this->input->get('contactid')) != '') {
            $params['c.contactid'] = $this->input->get('contactid');
        }
        if (trim($this->input->get('success')) != '') {
            $params['l.success'] = $this->input->get('success');
        }

        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        //get customerid
        $activityLogData = $this->adminclass->getActivityLog(NULL, 0, $field, $order, $fromdate, $todate, $params);
        
 

        $data_array = array();

        $heading = array('Company', 'Contact Name', 'UserName', 'Login Time', 'Success', 'IP Address');
        $this->load->library('excel');

  
        
        //format data for excel
        foreach ($activityLogData['data'] as $row)
        { 
            $result = array();
            
            $result[] = $row['companyname'];
            $result[] = $row['firstname'];
            $result[] = $row['username'];
            $result[] = format_datetime($row['login'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
            $result[] = $row['success'] == 1 ? 'Yes' : 'No';
            $result[] = $row['ipaddress'];
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "activitylogs.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Activity Logs", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('activitylogs.xls', $data);
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
     * show addresses
     * 
     * @return void
     */
    public function customerContacts() {
        
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
    
    
    public function announcements() {
        
        
         $this->data['cssToLoad'] = array(
            base_url('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );
            
        $this->data['jsToLoad'] = array(
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('plugins/jquery-validator/validation.js'),
            base_url('plugins/ckeditor/ckeditor.js'),
            base_url('plugins/ckeditor/config.js'),
            base_url('plugins/daterangepicker/moment.min.js'),
            base_url('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js'),
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.announcements.js')
        );
          
 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Announcements')
                ->set_layout($this->layout)
                ->set('page_title', 'Announcements')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Announcements', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/announcements', $this->data);
        
    }
    
    /**
    * This function use for load Portal Settings Audit log in uigrid
    * 
    * @return json 
    */
    public function loadAnnouncements() {
        
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
                $field = 'activationdate';
                
                $params = array();
                
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
             
                if (trim($this->input->get('status')) == 'active') {
                    $params['isactive'] = 1;
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get audit Log Data
                $auditLogData = $this->adminclass->getAnnouncements($size, $start, $field, $order, $params);

                $trows = $auditLogData['trows'];
                $data = $auditLogData['data'];

                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['browser'] = $value['browser'] == NULL ||  $value['browser'] =='' ? 'All Browser' : $value['browser'];
                    $data[$key]['activationdate'] = format_datetime($value['activationdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function addEditAnnouncement() {
            
        //Validate ajax request or not
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
            $AnnouncemenData = array();
            if (trim($this->input->post('mode')) == 'edit') {
                $AnnouncemenData = $this->adminclass->getAnnouncementById(trim($this->input->post('announcementid')));
                if(count($AnnouncemenData)>0){
                    if($this->input->post('caption') != $AnnouncemenData['caption']) {
                        $is_unique =  '|is_unique[cp_message.caption]';
                     } else {
                        $is_unique =  '';
                     }
                }
                
            }
            else{
                $is_unique =  '|is_unique[cp_message.caption]';
            }
            $this->form_validation->set_rules('caption', 'Caption', 'required|trim'.$is_unique);
 
            $this->form_validation->set_rules('activationdate', 'Activation Date', 'required|trim');
            $this->form_validation->set_rules('content', 'Content', 'required|trim');
 
            //validate form
            if ($this->form_validation->run() == FALSE)
            {
                $message =  validation_errors();
                
            }
            else{
              
                if (trim($this->input->post('mode')) != 'edit') {
                    $activationdate =  to_mysql_date(trim($this->input->post('activationdate')), RAPTOR_DISPLAY_DATEFORMAT.' h:i A', 'Y-m-d H:i:s');
                    if( time()>= strtotime($activationdate)){
                        $message =  'Activation Date must be greater from current date/time.';
                    }
                }
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess)
            {
                 
                $activeAnnouncemenData = array();
                if (trim($this->input->post('mode')) == 'edit') {
                    $activeAnnouncemenData = $this->adminclass->getActiveAnnouncement(trim($this->input->post('browser')),trim($this->input->post('browser_version')), trim($this->input->post('announcementid')));
                }
                else{
                    $activeAnnouncemenData = $this->adminclass->getActiveAnnouncement(trim($this->input->post('browser')),trim($this->input->post('browser_version')));
                }
                if(count($activeAnnouncemenData) > 0 && trim($this->input->post('reset')) != 'yes' && (int)trim($this->input->post('isactive')) == 1&& (int)trim($this->input->post('ispersistent')) != 1){
                    $data = array (
                        'success' => FALSE,
                        'message' => 'Announcement "'.$activeAnnouncemenData['caption'].'" is already active. Make this the active announcement?'
                    );
                }
                else{
                    if(count($activeAnnouncemenData) > 0 && trim($this->input->post('reset')) == 'yes' && (int)trim($this->input->post('isactive')) == 1){
                        //Create array for update announcement data
                        $updateData = array(  
                            'isactive'       => 0 
                        );
                        $request = array(
                            'announcementid'    => $activeAnnouncemenData['id'],
                            'updateData'        => $updateData,
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );

                        $this->adminclass->updateAnnouncement($request);
                    }
                
                    
                    $activationdate =  to_mysql_date(trim($this->input->post('activationdate')), RAPTOR_DISPLAY_DATEFORMAT.' h:i A', 'Y-m-d H:i:s');

                    //Create array for insert GL CODE data
                    $updateData = array( 
                        'caption'        => trim($this->input->post('caption')),
                        'isactive'       => (int)trim($this->input->post('isactive')),
                        'browser'        => trim($this->input->post('browser')),
                        'browser_version'=> trim($this->input->post('browser_version')),
                        'activationdate' => $activationdate,
                        'ispersistent '  => (int)trim($this->input->post('ispersistent')),
                        'content'        => trim($this->input->post('content'))
                    );
                    
                    if($updateData['browser'] == ''){
                        $updateData['browser_version'] = 0;
                    }

                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $request = array(
                            'announcementid'    => $this->input->post('announcementid'),
                            'updateData'        => $updateData,
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );

                        $this->adminclass->updateAnnouncement($request);

                    }
                    else{
                       
                        $request = array(
                            'insertData'        => $updateData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                        );
                        $this->adminclass->insertAnnouncement($request);


                    }

                    $message = 'Announcement updated.';
                    $data = array (
                            'success'   => TRUE,
                            'message'   => ''
                    );
                }
                
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
    * This function use for Delete Cost Centre
    * 
    * @return void
    */
    public function deleteAnnouncement() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $announcementid = $this->input->post('id');
             
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
               
                $request = array(
                    'announcementid'    => $announcementid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->adminclass->deleteAnnouncement($request);
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
     * load User Portal Setup Views
     * 
     * @return void
     */
    public function portalSetup() {

        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

         $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.portalsetup.js')
        );
        
        
        $this->data['customer_rulenames'] = $this->customerclass->getCustomerRuleNames();
 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Portal Setup')
                ->set_layout($this->layout)
                ->set('page_title', 'Portal Setup')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Portal Setup', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/portalsetup', $this->data);
    }
    
    /**
    * This function use for load Portal Settings Audit log in uigrid
    * 
    * @return json 
    */
    public function loadPortalAuditLog() {
        
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
                $field = 'dateadded';
                $fromdate = '';
                $todate = '';
                $params = array();
                
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
             
                if (trim($this->input->get('rulename')) != '') {
                    $params['cr.rulename'] = $this->input->get('rulename');
                }
                
                if (trim($this->input->get('fromdate')) != '') {
                    $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if (trim($this->input->get('todate')) != '') {
                    $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                
                $customerid =0;
                if (trim($this->input->get('customerid')) != '') {
                    $customerid = $this->input->get('customerid');
                    $params['pal.customerid'] = $customerid;

                    //intialize start page for uigrid
                    $start = ($page - 1) * $size;

                    //get audit Log Data
                    $auditLogData = $this->customerclass->getPortalAuditLog($size, $start, $field, $order, $fromdate, $todate, $params);

                    $trows = $auditLogData['trows'];
                    $data = $auditLogData['data'];

                    //format data
                    foreach($data as $key=>$value) {
                        $data[$key]['dateadded'] = format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
                    }
                
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
    * This function use for export Portal Auditlog
    * 
    * @return void 
    */
    public function exportPortalAuditLog() {
        
        
        $params = array();
        $order = 'desc';
        $field = 'dateadded';
        $fromdate = '';
        $todate = '';
        
        if (trim($this->input->get('rulename')) != '') {
            $params['cr.rulename'] = $this->input->get('rulename');
        }
        
        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }
        $customerid =0;
        if (trim($this->input->get('customerid')) != '') {
            $customerid = $this->input->get('customerid');
            $params['pal.customerid'] = $customerid;

            //get audit Log Data
            $auditLogData = $this->customerclass->getPortalAuditLog(NULL, 0, $field, $order, $fromdate, $todate, $params);
 
            $data = $auditLogData['data'];
        }
        else{
            $data = array();
        }

        $data_array = array();

        $heading = array('Date', 'Setting', 'Old Value', 'New Value', 'Edited By');
        $this->load->library('excel');

        $name = 'settings';
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = format_date($row['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = $row['setting'];
            $result[] = $row['oldvalue'];
            $result[] = $row['newvalue'];
            $result[] = $row['editedby'];

            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "portalauditlog.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Audit_log", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($name.'_portalauditlog.xls', $data);
    }
    
    /**
    * This function use for load Portal Settings in uigrid
    * 
    * @return json 
    */
    public function loadPortalSettings() {
        
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
                //get customerid
                $customerid =0;
                if (trim($this->input->get('customerid')) != '') {
                    $customerid = $this->input->get('customerid');
                    $filter = '';
                    if (trim($this->input->get('filtertext')) != '') {
                        $filter = $this->input->get('filtertext');
                    }
                    //get User Security Data
                    $portalSettingsData = $this->customerclass->getPortalSettings($customerid, $filter);

                    $trows = count($portalSettingsData);
                    $data = array();
 
                    //format data
                    foreach($portalSettingsData as $key=>$value) {

                        $data[] = array(
                            'rulename_id'       => $value['rulename_id'],
                            'caption'           => $value['caption'],
                            'valuetype'         => $value['valuetype'],
                            'value'             => $value['value'] == NULL ? $value['default_rule_value'] : $value['value'],
                            'is_sitecontact'    => $value['is_sitecontact'] == NULL ? $value['default_is_sitecontact'] : $value['is_sitecontact'],
                            'is_sitefm'         => $value['is_sitefm'] == NULL ? $value['default_is_sitefm'] : $value['is_sitefm'],
                            'is_master'         => $value['is_master'] == NULL ? $value['default_is_master'] : $value['is_master'] 
                        );

                    }
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
    * This function use for export Portal Auditlog
    * 
    * @return void 
    */
    public function exportPortalSettings() {
        
        //get customerid
        $customerid =0;
        $portalSettingsData = array();
        if (trim($this->input->get('customerid')) != '') {
            $customerid = $this->input->get('customerid'); 
            $filter = '';
            if (trim($this->input->get('filtertext')) != '') {
                $filter = $this->input->get('filtertext');
            }

            //get User Security Data
            $portalSettingsData = $this->customerclass->getPortalSettings($customerid, $filter); 
  
        }
        $data_array = array();

        $heading = array('ID','Setting', 'Value', 'For Master', 'For Site FM', 'For Site Contact');
        $this->load->library('excel');
 
        //format data for excel
        foreach ($portalSettingsData as $row)
        { 
            $result = array();
            $value = $row['value'] == NULL ? $row['default_rule_value'] : $row['value'];
            $is_sitecontact = $row['is_sitecontact'] == NULL ? $row['default_is_sitecontact'] : $row['is_sitecontact'];
            $is_sitefm = $row['is_sitefm'] == NULL ? $row['default_is_sitefm'] : $row['is_sitefm'];
            $is_master = $row['is_master'] == NULL ? $row['default_is_master'] : $row['is_master'];
            $result[] = $row['rulename_id'];
            $result[] = $row['caption'];
            if($row['valuetype'] == 'S'){
                $result[] = $value;
            }
            elseif($row['valuetype'] == 'B'){
                if($value == 1){
                    $result[] = 'Yes';
                }
                else{
                    $result[] = 'No';
                }
            }
            if($is_master == 1){
                $result[] = 'Yes';
            }
            else{
                $result[] = 'No';
            }
            
            if($is_sitefm == 1){
                $result[] = 'Yes';
            }
            else{
                $result[] = 'No';
            }
            
            if($is_sitecontact == 1){
                $result[] = 'Yes';
            }
            else{
                $result[] = 'No';
            }
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "portal_settings.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Portal Settings", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('portal_settings.xls', $data);
    }
    
    /**
    * This function use for update portal settings
    *
    * @return json
    */
    public function updatePortalSettings() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $rulename_id = $this->input->post('rulename_id');
            if(!$rulename_id) {
               $message = 'id can not be null'; 
            }
            $customerid = $this->input->post('customerid');
            if(!$customerid) {
               $message = 'customer can not be null'; 
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $updateData = array(
                    'rule_value' => trim($this->input->post('rule_value')),
                    'is_master' => trim($this->input->post('is_master')),
                    'is_sitefm' => trim($this->input->post('is_sitefm')),
                    'is_sitecontact' => trim($this->input->post('is_sitecontact')),
                    'modify_date' => date('Y-m-d H:i:s', time()),
                    'modify_user' => $this->session->userdata('raptor_contactid')
                );
                //get post data
                $rulename_id = $this->input->post('rulename_id');
                 
                $portalData = $this->customerclass->getPortalSettingsByRuleID($customerid, $rulename_id);
                if(count($portalData) > 0){
                    
                    $request = array(
                        'rulename_id'       => $rulename_id,
                        'customerid'        => $customerid,
                        'updateData'        => $updateData,
                        'logged_contactid'  => $this->session->userdata('raptor_contactid')
                    );
                    $this->customerclass->updatePortalSettings($request);
                }
                else{
                    
                    $updateData['customerid'] = $customerid;
                    $updateData['customer_rule_id'] = $rulename_id;
                    $updateData['create_date'] = date('Y-m-d H:i:s', time());
                    $updateData['create_user'] = $this->session->userdata('raptor_contactid');
                   
                    $request = array( 
                        'insertData'        => $updateData,
                        'logged_contactid'  => $this->session->userdata('raptor_contactid')
                    );
                    $this->customerclass->insertPortalSettings($request);
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
  
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
    
    
    public function helps() {
        
        
         $this->data['cssToLoad'] = array( 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );
            
        $this->data['jsToLoad'] = array(
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('plugins/jquery-validator/validation.js'),
            base_url('plugins/ckeditor/ckeditor.js'),
            base_url('plugins/ckeditor/config.js'), 
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.helps.js')
        );
          
 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Manage Help')
                ->set_layout($this->layout)
                ->set('page_title', 'Manage Help')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Manage Help', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/helps', $this->data);
        
    }
    
    /**
    * This function use for load Portal Settings Audit log in uigrid
    * 
    * @return json 
    */
    public function loadHelps() {
        
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
                $field = 'last_updated';
                
                $params = array();
                
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
             
                if (trim($this->input->get('status')) == 'active') {
                    $params['isactive'] = 1;
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get audit Log Data
                $auditLogData = $this->adminclass->getHelps($size, $start, $field, $order, $params);

                $trows = $auditLogData['trows'];
                $data = $auditLogData['data'];

                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['last_updated'] = format_datetime($value['last_updated'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
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
    * This function use for add new contact
    * @return void 
    */
    public function addHelp() {
        
        $this->form_validation->set_rules('route', 'Route', 'required|trim|is_unique[cp_help.route]');
        if(trim($this->input->post('route')) == 'other'){
            $this->form_validation->set_rules('other', 'Other Route', 'required|trim|is_unique[cp_help.route]');
        }
        $this->form_validation->set_rules('caption', 'Caption', 'required|trim|is_unique[cp_help.caption]');
        $this->form_validation->set_rules('content', 'Content', 'required|trim');

        //validate form
        if ($this->form_validation->run() == FALSE)
        {
            //include required css for this view
            $this->data['cssToLoad'] = array( 
            );

            //include required js for this view
            $this->data['jsToLoad'] = array(
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('plugins/ckeditor/ckeditor.js'),
                base_url('plugins/ckeditor/config.js'),
                base_url('assets/js/admin/admin.addedithelp.js') 

            );
            
            
            //intialize variables 
            $this->data['routes'] =$this->adminclass->getMenuModule();
            

            //generate view
           $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Add Help Topic')
                ->set_layout($this->layout)
                ->set('page_title', 'Help Topic')
                ->set('page_sub_title', 'Add New')
                ->set_breadcrumb('Helps', site_url('admin/helps'))
                ->set_breadcrumb('Add Help Topic', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/addhelp', $this->data);
        }
        else {
            
            $isactive = 0;
            if($this->input->post('isactive')) {
                $isactive = 1;
            }
           
            //intialize array for insert
            $insertHelpData = array(
                'route'         => trim($this->input->post('route')) == 'other'  ? trim($this->input->post('other')) : trim($this->input->post('route')), 
                'caption'       => trim($this->input->post('caption')), 
                'content'       => trim($this->input->post('content')),                
                'last_updated'  => date('Y-m-d H:i:s', time()),  
                'isactive'      => $isactive
            );
  
            $insertHelpData['route'] =  rtrim($insertHelpData['route'],'/');
            //insert
            $request = array(
                'insertHelpData'    => $insertHelpData,
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            $response = $this->adminclass->insertHelp($request);

            $this->session->set_flashdata('success', 'Help Topic Added successfully.');
            
            //redirect to contacts
            redirect('admin/edithelp/'.$response['helpid']);
        }
    }
    
    /**
    * This function use for edit contact 
    * @param integer $id - id for selected help which one edit
    * @return void 
    */
    public function editHelp($id) {
        
        
        //get data for selected contact
        $this->data['help'] = $this->adminclass->getHelpById($id);
        
        if(count($this->data['help'])==0){
            show_404();
        }
         
        //set form validation rule
        //set form validation rule
        
        if($this->input->post('route') != $this->data['help']['route']) {
            $is_unique1 =  '|is_unique[cp_help.route]';
        } else {
            $is_unique1 =  '';
        }

        $this->form_validation->set_rules('route', 'Route', 'required|trim'.$is_unique1);
        if(trim($this->input->post('route')) == 'other'){
            if($this->input->post('other') != $this->data['help']['route']) {
                $is_unique1 =  '|is_unique[cp_help.route]';
            } else {
                $is_unique1 =  '';
            }
            $this->form_validation->set_rules('other', 'Other Route', 'required|trim'.$is_unique1);
        }
        if($this->input->post('caption') != $this->data['help']['caption']) {
            $is_unique =  '|is_unique[cp_help.caption]';
        } else {
            $is_unique =  '';
        }
        $this->form_validation->set_rules('caption', 'Caption', 'required|trim'.$is_unique);
        $this->form_validation->set_rules('content', 'Content', 'required|trim');
       
        //check form validation
        if ($this->form_validation->run() == FALSE)
        { 
            //include required css for this view
            $this->data['cssToLoad'] = array( 
            );

            //include required js for this view
            $this->data['jsToLoad'] = array(
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('plugins/ckeditor/ckeditor.js'),
                base_url('plugins/ckeditor/config.js'),
                base_url('assets/js/admin/admin.addedithelp.js') 

            );

            //intialize variables 
            $this->data['routes'] = $this->adminclass->getMenuModule();
            $find = false;
            foreach ($this->data['routes'] as $key => $value) {
                if($value['route'] == $this->data['help']['route']){
                    
                    $find = TRUE;
                    break;
                }
            }
            if(!$find){
                $this->data['help']['other'] = $this->data['help']['route'];
                $this->data['help']['route'] = 'other';
            }
            else{
                $this->data['help']['other'] = '';
            }
            
            $this->data['help_links'] = $this->adminclass->getHelpLinks($id); 
            
           //generate view
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Edit Help')
                ->set_layout($this->layout)
                ->set('page_title', 'Helps')
                ->set('page_sub_title', 'Edit Help : ' .$id)
                ->set_breadcrumb('Helps', site_url('admin/helps'))
                ->set_breadcrumb('Edit Help', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/edithelp', $this->data);
            
        } else {
            $isactive = 0;
            if($this->input->post('isactive')) {
                $isactive = 1;
            }
           
            //intialize array for insert
            $updateHelpData = array(
                'route'         => trim($this->input->post('route')) == 'other'  ? trim($this->input->post('other')) : trim($this->input->post('route')), 
                'caption'       => trim($this->input->post('caption')), 
                'content'       => trim($this->input->post('content')),                
                'last_updated'  => date('Y-m-d H:i:s', time()),  
                'isactive'      => $isactive
            );
            $updateHelpData['route'] =  rtrim($updateHelpData['route'],'/');
            //insert
            $request = array(
                'helpid'            => $id,
                'updateHelpData'    => $updateHelpData,
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            
            $this->adminclass->updateHelp($request);
              
            $this->session->set_flashdata('success', 'Help Topic updated successfully.');
            redirect('admin/helps');
            
        }
    }
    
    
    
    
    /**
    * This function use for Delete Help
    * 
    * @return void
    */
    public function deleteHelp() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $helpid = $this->input->post('id');
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'helpid'    => $helpid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->adminclass->deleteHelp($request);
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
    * @desc This function use for update Help Link
    * @param none
    * @return json 
    */
    public function addEditHelpLink() {
            
        //Validate ajax request or not
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

            if( $isSuccess )
            {
   
                //intialize array for insert
                $helpLinkData = array(
                    'caption'       => trim($this->input->post('caption')), 
                    'link'          => trim($this->input->post('link')),
                    'helpid'        => trim($this->input->post('helpid')),
                    'sortorder'        => trim($this->input->post('sortorder')),
                    'isvideo'       => (int)(trim($this->input->post('isvideo'))),  
                    'isactive'      => (int)(trim($this->input->post('isactive'))),
                );

                //check Add/Edit Mode
                if (trim($this->input->post('mode')) == 'edit') {
                    $request = array(
                        'helplinkid'        => $this->input->post('helplinkid'),
                        'helpLinkData'      => $helpLinkData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );

                    $this->adminclass->updateHelpLink($request);
                 
                }
                else{
                    $updateData['isactive'] = 1;
                    $request = array(
                        'helpLinkData'      => $helpLinkData, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                    );
                    $this->adminclass->insertHelpLink($request);
                    

                }

                $message = 'Cost Centre updated.';
                
                
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
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateHelpLink() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            
            $helplinkid = $this->input->post('id');
          
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data  
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                $helpLinkData = array(
                    $field => $value
                );
                
                $request = array(
                    'helplinkid' => $helplinkid,
                    'helpLinkData'      => $helpLinkData, 
                    'logged_contactid'   => $this->data['loggeduser']->contactid
                );

                $this->adminclass->updateHelpLink($request);
                 
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
    * This function use for Delete Help
    * 
    * @return void
    */
    public function deleteHelpLink() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $helplinkid = $this->input->post('id');
           
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess ) { 
                $request = array(
                    'helplinkid'        => $helplinkid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->adminclass->deleteHelpLink($request);
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
    
    
    
    public function modules() {
        
        
         $this->data['cssToLoad'] = array( 
            base_url('plugins/uigrid/ui-grid-stable.min.css'),
            base_url('plugins/colorpicker/bootstrap-colorpicker.min.css')
        );
            
        $this->data['jsToLoad'] = array(
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('plugins/jquery-validator/validation.js'),
            base_url('plugins/colorpicker/bootstrap-colorpicker.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.modules.js')
        ); 
        $this->data['routes'] =$this->adminclass->getMenuModule();
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Modules')
                ->set_layout($this->layout)
                ->set('page_title', 'Modules')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Modules', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/modules', $this->data);
        
    }
    
    /**
    * This function use for load Portal Settings Audit log in uigrid
    * 
    * @return json 
    */
    public function loadModules() {
        
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
             

                //get audit Log Data
                $auditLogData = $this->adminclass->getMenuModule();

                $trows = count($auditLogData);
                $data = $auditLogData;
 
                 
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function addEditModule() {
            
        //Validate ajax request or not
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

            if( $isSuccess)
            {
                 
                    //Create array for insert GL CODE data
                    $updateData = array( 
                        'name'        => trim($this->input->post('name')),
                        'parentid'       => trim($this->input->post('parentid')),
                        'url1'       => trim($this->input->post('url1')),
                        'url2'       => trim($this->input->post('url2')),
                        'url3'       => trim($this->input->post('url3')),
                        'showcounter'       => (int)trim($this->input->post('showcounter')),
                        'counter_keyword'   => trim($this->input->post('counter_keyword')),
                        'counter_color'       => trim($this->input->post('counter_color')),
                        'counter_bgcolor'        => trim($this->input->post('counter_bgcolor')),
                        'menu_icontype'       => trim($this->input->post('menu_icontype')),
                        'menu_icon'       => trim($this->input->post('menu_icon')),
                        'menu_image'       => trim($this->input->post('menu_image')),
                        'sortorder'       => trim($this->input->post('sortorder')),
                        'isactive'       => (int)trim($this->input->post('isactive')),
                        
                        'target'       => trim($this->input->post('target'))
                    );
//                    if($updateData['menu_icontype'] == 'ICON'){
//                        $updateData['menu_image']       = '';
//                    }
//                    else{
//                        $updateData['menu_icon']       = '';
//                    }
                    
                    $updateData['masteraccess']       = (int)trim($this->input->post('masteraccess'));
                    $updateData['fmaccess']       = (int)trim($this->input->post('fmaccess'));
                    $updateData['sitecontactaccess']       = (int)trim($this->input->post('sitecontactaccess'));
                   
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        
                        $request = array(
                            'menuid' => $this->input->post('menuid'),
                            'updateMenuModuleData'      => $updateData, 
                            'logged_contactid'   => $this->data['loggeduser']->contactid
                        );

                        $this->adminclass->updateMenuModule($request);
                        

                    }
                    else{
                       
                        $request = array(
                            'insertMenuModuleData'        => $updateData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                        );
                        $this->adminclass->insertMenuModule($request);


                    }

                    
                
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
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateModule() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            
            $menuid = $this->input->post('id');
          
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data  
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                $updateMenuModuleData = array(
                    $field => $value
                );
                
                $request = array(
                    'menuid' => $menuid,
                    'updateMenuModuleData'      => $updateMenuModuleData, 
                    'logged_contactid'   => $this->data['loggeduser']->contactid
                );

                $this->adminclass->updateMenuModule($request);
                 
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
    
    
     
    public function customerSummary() {
        
        
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