<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Project: Raptor
 * Package: CI
 * Subpackage: Controller
 * File: contracts.php
 * Description: This is a contracts controller class  
 * Created by : Andrew Kitchen
 *
 */

class Contracts extends MY_Controller {
 
    function __construct()
    {
        parent::__construct();

        $this->load->library('contractor/ContractorClass');
        $this->load->library('job/JobClass');

    }

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
            base_url('assets/js/contracts/contracts.index.js')
        );
        
       
        $contactid = $this->session->userdata('raptor_contactid');
        $customerid =$this->session->userdata('raptor_customerid');
        $this->data['managers'] =$this->customerclass->getCustomerContacts($customerid);
        $this->data['contracttypes'] = $this->contractorclass->getContractTypes();
        
        $this->data['ADD_CONTRACT'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_CONTRACT');
        $this->data['EXPORT_CONTRACT'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_CONTRACT');
        $this->data['EDIT_CONTRACT'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_CONTRACT');
        $this->data['DELETE_CONTRACT'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_CONTRACT');
                
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Contracts')
                ->set_layout($this->layout)
                ->set('page_title', 'My Contracts')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('contracts/index', $this->data);
    }
    
    /**
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadContracts() {
        
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
                $field = 'name';
                $filter = '';
                $params = array();
                 
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('contracttypeid')) != '') {
                    $params['c.contracttypeid'] = $this->input->get('contracttypeid');
                }
                if (trim($this->input->get('managerid')) != '') {
                    $params['c.managerid'] = $this->input->get('managerid');
                }
                 
 

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data
                $customerid =$this->session->userdata('raptor_customerid');
                $contactData = $this->contractorclass->getContracts($customerid, $size, $start, $field, $order, $filter, $params);
                 
                $trows  = $contactData['trows'];
                $data = $contactData['data'];
            
                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['startdate'] = format_date($value['startdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['enddate'] = format_date($value['enddate'], RAPTOR_DISPLAY_DATEFORMAT); 
                    
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
    public function exportContracts() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTRACT');
        if(!$export_excel) {
            show_404();
        }
        
        $order = 'asc';
        $field = 'name';
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('contracttypeid')) != '') {
            $params['c.contracttypeid'] = $this->input->get('contracttypeid');
        }
        if (trim($this->input->get('managerid')) != '') {
            $params['c.managerid'] = $this->input->get('managerid');
        }
        $customerid =$this->session->userdata('raptor_customerid');
     

        //get contacts data
        $contactData = $this->contractorclass->getContracts($customerid, NULL, 0, $field, $order, $filter, $params);
               
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('Contract Name', 'Type', 'Contract Ref', 'Start Date', 'End Date', 'Site', 'Active');
        $this->load->library('excel');

      
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = $row['name'];
            $result[] = $row['typename'];
            $result[] = $row['contractref']; 
            $result[] = format_date($row['startdate']);
            $result[] = format_date($row['enddate']);
            $result[] = $row['sitecount']; 
            $result[] = $row['status']==1? 'Yes':'No';
            
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "Contracts.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(12); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("My Contract", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Contracts.xls', $data);
    }
    
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deleteContract() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $contractid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_CONTRACT');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Contract.';
            }
             
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $request = array(
                    'contractid'        => $contractid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->contractorclass->deleteContract($request);
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
    * This function use for add new Supplier
    * @return void 
    */
    public function add() {
        
        //check permission
        $add_CONTRACT = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_CONTRACT');
        if(!$add_CONTRACT) {
            show_404();
        }
         
        //set form validation rule
        $this->form_validation->set_rules('name', 'Contract Name', 'trim|required');
        $this->form_validation->set_rules('contractref', 'Contract Ref', 'trim|required');
        $this->form_validation->set_rules('contracttypeid', 'Type', 'trim|required');
        $this->form_validation->set_rules('startdate', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('enddate', 'End Date', 'trim|required');
        $this->form_validation->set_rules('managerid', 'Manager', 'trim|required');
       
        //validate form
        if ($this->form_validation->run() == FALSE)
        {
            //include required css for this view
            $this->data['cssToLoad'] = array(
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
                base_url('plugins/datepicker/datepicker3.css')
            );

            //include required js for this view
            $this->data['jsToLoad'] = array(
                base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),  
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('plugins/datepicker/bootstrap-datepicker.js'), 
                base_url('assets/js/contracts/contracts.informationadd.js') 

            );
             

            $customerid =$this->session->userdata('raptor_customerid');
            $this->data['managers'] =$this->customerclass->getCustomerContacts($customerid);
            $this->data['contracttypes'] = $this->contractorclass->getContractTypes();

            //generate view
           $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Add Contract')
                ->set_layout($this->layout)
                ->set('page_title', 'My Contracts')
                ->set('page_sub_title', 'Add')
                ->set_breadcrumb('My Contracts', site_url('contracts'))
                ->set_breadcrumb('Add Contract', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('contracts/addcontract', $this->data);
        }
        else {
           
            $customerid =$this->session->userdata('raptor_customerid');
 
            // intialize array for insert
            $fields = array(  
                'customerid'        => $customerid,
                'name'              => trim($this->input->post('name')), 
                'contracttypeid'    => trim($this->input->post('contracttypeid')), 
                'contractref'       => trim($this->input->post('contractref')), 
                'managerid'         => trim($this->input->post('managerid')), 
                'startdate'         => to_mysql_date(trim($this->input->post('startdate')), RAPTOR_DISPLAY_DATEFORMAT),
                'enddate'           => to_mysql_date(trim($this->input->post('enddate')), RAPTOR_DISPLAY_DATEFORMAT),
                'status'            => (int)trim($this->input->post('status')), 
                'create_datetime'   => date('Y-m-d H:i:s', time()),
                'modifieddate'      => date('Y-m-d H:i:s', time()),
                'createdby'         => $this->session->userdata('raptor_contactid'),
                'modifiedby'        => $this->session->userdata('raptor_contactid')
            );
              
            //insert
            $request = array(
                'insertContractData'  => $fields,  
                'logged_contactid'    => $this->session->userdata('raptor_contactid')
            );
            
            $this->contractorclass->insertContract($request);

            $this->session->set_flashdata('success', 'Contacts Added successfully.');
            
            //redirect to contacts
            redirect("contracts");
            
     
        }
    }
    
    /**
    * This function use for edit Supplier 
    * @param integer $id - id for selected contact which one edit
    * @return void 
    */
    public function edit($id) {
        
        //check permission
        $edit_CONTRACT = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_CONTRACT');
        if(!$edit_CONTRACT) {
            show_404();
        }
        
        $customerid =$this->session->userdata('raptor_customerid'); 
 	$contactid = $this->session->userdata('raptor_contactid');
        //get data for selected contact
        $this->data['contract'] = $this->contractorclass->getContractById($id);
        $this->data['customer'] = $this->customerclass->getCustomerById($customerid);
      
        if(count($this->data['contract'])==0){
            show_404();
        }
        if($this->data['contract']['customerid']!=$customerid){
            show_404();
        }
        
        //set form validation rule
        $this->form_validation->set_rules('contract_post_type', 'Data', 'required');
        if( $this->input->post('contract_post_type') == "rules") {
         
        }
        else{
            $this->form_validation->set_rules('name', 'Contract Name', 'trim|required');
            $this->form_validation->set_rules('contractref', 'Contract Ref', 'trim|required');
            $this->form_validation->set_rules('contracttypeid', 'Type', 'trim|required');
            $this->form_validation->set_rules('startdate', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('enddate', 'End Date', 'trim|required');
            $this->form_validation->set_rules('managerid', 'Manager', 'trim|required');
        }
       
        
        
        //check form validation
        if ($this->form_validation->run() == FALSE)
        {
            
            $this->data['cssToLoad'] = array( 
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
                base_url('plugins/datepicker/datepicker3.css'), 
                base_url('plugins/uigrid/ui-grid-stable.min.css')
            );
 
            //include required js for this view
            $this->data['jsToLoad'] = array(
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
                base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),  
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('plugins/datepicker/bootstrap-datepicker.js'),
                base_url('assets/js/jquery.form.js'),
                base_url('plugins/uigrid/angular.min.js'), 
                base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
                base_url('plugins/uigrid/ui-grid-stable.min.js'), 
                base_url('assets/js/contracts/contracts.informationedit.js'),
                base_url('assets/js/contracts/contracts.sites.js'),
                base_url('assets/js/contracts/contracts.schedule.js'),
                base_url('assets/js/contracts/contracts.rules.js'),
                base_url('assets/js/contracts/contracts.workorders.js'),
                base_url('assets/js/contracts/contracts.technicians.js'),
                base_url('assets/js/contracts/contracts.parentjobs.js'),
                base_url('assets/js/editlog.js')
            );

           //intialize variables
           $customerid =$this->session->userdata('raptor_customerid');
            
            $this->data['editlog_tablename'] = 'con_contract';
            $this->data['editlog_recordid'] = $id;
            $this->data['editlogfieldname'] = $this->sharedclass->getEditLogFieldNames($id, 'con_contract');
           
            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['managers'] =$this->customerclass->getCustomerContacts($customerid);
            $this->data['contracttypes'] = $this->contractorclass->getContractTypes();
            $this->data['sitefm_contacts'] = $this->customerclass->getCustomerSiteFM($customerid);
			
		    $this->data['contractedhours'] = $this->contractorclass->getContractedHours();
            $this->data['conContractedhours'] = $this->contractorclass->getConContractedHours($id);
            $this->data['subJobMethods'] = $this->contractorclass->getParentJobidMethods();
            $this->data['billingMethods'] = $this->contractorclass->getBiilingMethods();
            $this->data['workorderMethods'] = $this->contractorclass->getWorkOrderMethods();
            $this->data['contractServices'] = $this->contractorclass->getContractServices($id);
            
            //For Sites
            $this->data['contractsitesuburb'] = $this->contractorclass->getContractSiteSuburb($id);
            $this->data['addressgroups'] = $this->contractorclass->getContractAddressGroups($customerid);
            
            $this->data['ADD_CONTRACT_SITE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_CONTRACT_SITE');
            $this->data['EXPORT_CONTRACT_SITE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_CONTRACT_SITE');
            $this->data['EDIT_CONTRACT_SITE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_CONTRACT_SITE');
            $this->data['DELETE_CONTRACT_SITE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_CONTRACT_SITE');
            $this->data['ADDRESS_SAVE_GPS'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADDRESS_SAVE_GPS');
    
            
            //For Schedule
            $this->data['subworks'] = $this->contractorclass->getContractSubWorks($id);
            $this->data['seasons'] = $this->contractorclass->getContractSeasons();
            $this->data['servicetypes'] = $this->contractorclass->getServiceTypes($customerid);
            
            $this->data['users'] = $this->contractorclass->getUserTechnicians();
            
            
            $this->data['ADD_CONTRACT_SCHEDULE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_CONTRACT_SCHEDULE');
            $this->data['EXPORT_CONTRACT_SCHEDULE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_CONTRACT_SCHEDULE');
            $this->data['EDIT_CONTRACT_SCHEDULE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_CONTRACT_SCHEDULE');
            $this->data['DELETE_CONTRACT_SCHEDULE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_CONTRACT_SCHEDULE');
            $this->data['MAKE_CONTRACT_SCHEDULE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'MAKE_CONTRACT_SCHEDULE');
    
            $this->data['jobstages'] = $this->contractorclass->getJobStages();
            
            $this->data['ADD_CONTRACT_PARENT_JOB'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_CONTRACT_PARENT_JOB');
            $this->data['DELETE_CONTRACT_PARENT_JOB'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_CONTRACT_PARENT_JOB');
            
            
           //generate view
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Contracts - Edit')
                ->set_layout($this->layout)
                ->set('page_title', 'My Contracts')
                ->set('page_sub_title', 'Edit')
                ->set_breadcrumb('My Contracts', site_url('contracts'))
                ->set_breadcrumb('Edit', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('contracts/editcontract', $this->data);
            
        } else {

            if( $this->input->post('contract_post_type') == "rules") {
                // intialize array for insert
                $updateContractData = array(
//                    'parentjobid'       => trim($this->input->post('parentjobid')), 
//                    'custordref1'       => trim($this->input->post('custordref1')), 
//                    'custordref2'       => trim($this->input->post('custordref2')), 
//                    'custordref3'       => trim($this->input->post('custordref3')),
                    'subjobmethodid'    => trim($this->input->post('subjobmethodid')), 
                    'billingmethodid'   => trim($this->input->post('billingmethodid')),
                    'modifieddate'      => date('Y-m-d H:i:s', time()), 
                    'modifiedby'        => $this->session->userdata('raptor_contactid')
                );
            }
            else{
                // intialize array for insert
                $updateContractData = array(
                    'name'              => trim($this->input->post('name')), 
                    'contracttypeid'    => trim($this->input->post('contracttypeid')), 
                    'contractref'       => trim($this->input->post('contractref')), 
                    'managerid'         => trim($this->input->post('managerid')), 
					'contracted_hoursid'=> trim($this->input->post('contracted_hoursid')), 
                    'startdate'         => to_mysql_date(trim($this->input->post('startdate')), RAPTOR_DISPLAY_DATEFORMAT),
                    'enddate'           => to_mysql_date(trim($this->input->post('enddate')), RAPTOR_DISPLAY_DATEFORMAT),
                    'status'            => (int)trim($this->input->post('status')),  
                    'modifieddate'      => date('Y-m-d H:i:s', time()), 
                    'modifiedby'        => $this->session->userdata('raptor_contactid')
                );
            }
            $request = array(
                'updateContractData' => $updateContractData,  
                'contractid'         => $id,  
                'logged_contactid'   => $this->session->userdata('raptor_contactid')
            );
            
            $this->contractorclass->updateContract($request);
   
            $this->session->set_flashdata('success', 'Contract updated successfully.');
            redirect('contracts');
        }
    }
    
    
    /**
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateContract() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
             
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_CONTRACT');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to edit Contact.';
            }
 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data 
                $contactid = $this->input->post('id');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                
                
                $updateData = array(
                    $field => $value
                );
                
                $request = array(
                    'updateContractData' => $updateData,  
                    'contractid'         => $contactid,  
                    'logged_contactid'   => $this->session->userdata('raptor_contactid')
                );

                $this->contractorclass->updateContract($request);
                
                    
                
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
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function getAvailableSites() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $contractid = trim($this->input->get('contractid')); 
            $state = trim($this->input->get('state')); 
            $labelid = trim($this->input->get('labelid')); 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $contractLabelids = $this->contractorclass->getContractSiteLabelids($contractid);
                if(($key = array_search($labelid, $contractLabelids)) !== false) {
                    unset($contractLabelids[$key]);
                } 
              
                $data = $this->customerclass->getCustomerSites($this->session->userdata('raptor_customerid'), $contractLabelids, $state);
                 
                $success->setData($data);
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadContractSites() {
        
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
                $field = 'sitesuburb';
                $filter = '';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = $this->input->get('filtertext');
                }

                if (trim($this->input->get('suburb')) != '') {
                    $params['a.sitesuburb'] = $this->input->get('suburb');
                }
                if (trim($this->input->get('state')) != '') {
                    $params['a.sitestate'] = $this->input->get('state');
                }
                
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                $contractid = $this->input->get('contractid');
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get site addresses data
                $addressData = $this->contractorclass->getContractSites($customerid, $contractid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $addressData['trows'];
                $data = $addressData['data'];
                
                 //format data
                foreach ($data as $key => $value) {
                   
                    $data[$key]['siteaddress'] = urlencode($data[$key]['address']);
                   
                    $data[$key]['sitegroupids'] = $this->contractorclass->getSiteAddressGroupids($customerid, $value['labelid']);
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
    public function exportContractSites() {
        
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTRACT_SITE');
        if(!$export_excel) {
            show_404();
        }
        
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('suburb')) != '') {
            $params['a.sitesuburb'] = $this->input->get('suburb');
        }
        if (trim($this->input->get('state')) != '') {
            $params['a.sitestate'] = $this->input->get('state');
        }
        
        
        $customerid = $this->session->userdata('raptor_customerid'); 
        $contractid = $this->input->get('contractid');
        
        $order = 'desc';
        $field = 'sitesuburb';
        $addressData = $this->contractorclass->getContractSites($customerid, $contractid, NULL, 0, $field, $order, $filter, $params);
                 
        $data = $addressData['data'];
  

        $data_array = array();

        $heading = array('Site Ref', 'Street Address', 'Suburb', 'State', 'Post Code', 'Active');
        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = $row['siteref']; 
            $result[] = $row['siteline2'];
            $result[] = $row['sitesuburb'];
            $result[] = $row['sitestate'];
            $result[] = $row['sitepostcode']; 
            $result[] = $row['isactive']==1? 'Yes':'No';
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "Contract_sites.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Contract Sites", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Contract_sites.xls', $data);
    }
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deleteContractSite() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $siteid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_CONTRACT_SITE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Contract Site.';
            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'contractid'        => trim($this->input->post('contractid')),
                    'siteid'            => $siteid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->contractorclass->deleteContractSite($request);
                $message = 'Site deleted successfully.';
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
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function updateSiteStatus() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $siteid = $this->input->post('id');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_CONTRACT_SITE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to update Site status.';
            }
           
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess) {
                 
                $siteData = array( 
                    'isactive'  => (int)$this->input->post('isactive')
                );
  
                $request = array(
                    'contractid'       => trim($this->input->post('contractid')), 
                    'siteid'           => $siteid,
                    'updateConSiteData'=> $siteData,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );

                $this->contractorclass->updateContractSite($request);
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
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function updateSiteLatLong() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $labelid = $this->input->post('labelid');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'ADDRESS_SAVE_GPS');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to Save GPS.';
            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $updateData = array( 
                    'latitude_decimal'  => $this->input->post('latitude'),
                    'longitude_decimal' => $this->input->post('longitude') 
                );
  
                $request = array(
                    'updateData'       => $updateData,
                    'labelid'          => $labelid,  
                    'logged_contactid' => $this->session->userdata('raptor_contactid')
                );
                $this->customerclass->updateAddress($request);
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
    public function saveContractSite() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_CONTRACT_SITE');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_CONTRACT_SITE');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Site.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $this->form_validation->set_rules('labelid', "Site", 'trim|required|callback_check_site');
             
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $isSuccess = FALSE;
                    $success = SuccessClass::initialize($isSuccess);
                    $message = validation_errors();
                    
                }
                else {
 
                    //intialize array for insert
                    $siteData = array(
                        'contractid'    => trim($this->input->post('contractid')), 
                        'labelid'       => trim($this->input->post('labelid')),
                        'dateadded'     => date('Y-m-d H:i:s'),
                        'isactive'      => 1//(int)trim($this->input->post('isactive'))
                    );
                     
                    //check mailgroup and insert record
                    $groupids = $this->input->post('groupid');

                    if (!is_array($groupids) && trim($groupids) != '') {
                        $groupids = explode(",", $groupids);
                    }
                    $addressGroupData = array();
                    if (is_array($groupids)) {
                        foreach ($groupids as $value) {
                            $addressGroupData[] = array(
                                'groupid'   => $value, 
                                'labelid'   => trim($this->input->post('labelid')),
                            );
                        }
                    }
                    
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $siteid = $this->input->post('siteid');
                        $request = array(
                            'contractid'       => trim($this->input->post('contractid')), 
                            'siteid'           => $siteid,
                            'updateConSiteData'=> $siteData,
                            'addressGroupData' => $addressGroupData,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        $this->contractorclass->updateContractSite($request);
                        $message = 'Site updated successfully.';
                    }
                    else{
                         
                       //insert
                        $request = array(
                            'insertConSiteData'    => $siteData, 
                            'addressGroupData'   => $addressGroupData,
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );
                        $response = $this->contractorclass->insertContractSite($request);
                        $siteid = $response['siteid'];
                        $message = 'Site added successfully.';
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
     * check_email
     * @param string $labelid
     * @return boolean
    */
    public function check_site($labelid)
    {

        if (empty($labelid)){
            return FALSE;
        }
        
        $siteid = 0;
        $contractid = trim($this->input->post('contractid'));
        if (trim($this->input->post('mode')) == 'edit') {
            $siteid = trim($this->input->post('siteid'));
            
        }
        
        
        if($this->contractorclass->checkContractSite($contractid, $labelid, $siteid)){
            $this->form_validation->set_message('check_site', 'Site Already exist for this contract');
            return false;
        }
        else
        {
            return TRUE;
        }

    }
    
    
     /**
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadContractSchedules() {
        
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
                $field = 'id';
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
                    $filter = $this->input->get('filtertext');
                }

                if (trim($this->input->get('suburb')) != '') {
                    $params['a.sitesuburb'] = $this->input->get('suburb');
                }
                if (trim($this->input->get('state')) != '') {
                    $params['a.sitestate'] = $this->input->get('state');
                }
                if (trim($this->input->get('fromdate')) != '') {
                    $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if (trim($this->input->get('todate')) != '') {
                    $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                $contractid = $this->input->get('contractid');
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get site addresses data
                $addressData = $this->contractorclass->getContractSchedule($customerid, $contractid, $size, $start, $field, $order, $fromdate, $todate, $filter, $params);
                
                $trows = $addressData['trows'];
                $data = $addressData['data'];
                
                 //format data
                foreach ($data as $key => $value) {
                   
                    $data[$key]['season_start_date'] = format_date($value['season_start_date'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['season_end_date'] = format_date($value['season_end_date'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['firstjobdate'] = format_date($value['firstjobdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['last_scheduled'] = format_date($value['last_scheduled'], RAPTOR_DISPLAY_DATEFORMAT);
                     
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
    public function exportContractSchedules() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTRACT_SCHEDULE');
        if(!$export_excel) {
            show_404();
        }
         
        $order = 'desc';
        $field = 'id';
        $fromdate = '';
        $todate = '';
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('suburb')) != '') {
            $params['a.sitesuburb'] = $this->input->get('suburb');
        }
        if (trim($this->input->get('state')) != '') {
            $params['a.sitestate'] = $this->input->get('state');
        }
        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }
        
        $customerid = $this->session->userdata('raptor_customerid'); 
        $contractid = $this->input->get('contractid');
         
        $addressData = $this->contractorclass->getContractSchedule($customerid, $contractid, NULL, 0, $field, $order, $fromdate, $todate, $filter, $params);
                 
        $data = $addressData['data'];
  

        $data_array = array();

        $heading = array('Name', 'Season', 'Works', 'Start Date', 'End Date', 'Frequency', 'Period', 'Visits/Year', 'First Job', 'Last Scheduled', 'Active');
        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = $row['name']; 
            $result[] = $row['season'];
            $result[] = $row['works'];
            $result[] = format_date($row['season_start_date'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = format_date($row['season_end_date'], RAPTOR_DISPLAY_DATEFORMAT); 
            $result[] = $row['frequency_count']; 
            $result[] = $row['period'];
            $result[] = $row['visitsperyear'];
            $result[] = format_date($row['firstjobdate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = format_date($row['last_scheduled'], RAPTOR_DISPLAY_DATEFORMAT); 
            $result[] = $row['isactive']==1? 'Yes':'No';
            $data_array[] = $result;
           
                     
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir)){
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "Contract_Schedules.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Contract Schedules", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Contract_Schedules.xls', $data);
    }
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deleteContractSchedule() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $scheduleid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_CONTRACT_SCHEDULE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Contract Schedule.';
            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'contractid'        => trim($this->input->post('contractid')),
                    'scheduleid'        => $scheduleid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->contractorclass->deleteContractSchedule($request);
                $message = 'Schedule deleted successfully.';
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
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function updateScheduleStatus() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $siteid = $this->input->post('id');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_CONTRACT_SCHEDULE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to update Schedule status.';
            }
           
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess) {
                 
                $scheduleData = array( 
                    'isactive'  => (int)$this->input->post('isactive')
                );
  
                $request = array(
                    'contractid'            => trim($this->input->post('contractid')), 
                    'scheduleid'            => $siteid,
                    'updateConScheduleData' => $scheduleData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->contractorclass->updateContractSchedule($request);
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
    * This function use for add new contact Schedule
    * 
    * @return json 
    */
    public function saveContractSchedule() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_CONTRACT_SCHEDULE');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_CONTRACT_SCHEDULE');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Schedule.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $this->form_validation->set_rules('name', "Name", 'trim|required');
             
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $isSuccess = FALSE;
                    $success = SuccessClass::initialize($isSuccess);
                    $message = validation_errors();
                    
                }
                else {
 
                    $season_start_date = NULL;
                    $season_end_date = NULL;
                    $firstjobdate = NULL;
                    $last_scheduled = NULL;
                    if (trim($this->input->post('season_start_date')) != '') {
                        $season_start_date = to_mysql_date($this->input->post('season_start_date'), RAPTOR_DISPLAY_DATEFORMAT);
                    }

                    if (trim($this->input->post('season_end_date')) != '') {
                        $season_end_date = to_mysql_date($this->input->post('season_end_date'), RAPTOR_DISPLAY_DATEFORMAT);
                    }
                    if (trim($this->input->post('firstjobdate')) != '') {
                        $firstjobdate = to_mysql_date($this->input->post('firstjobdate'), RAPTOR_DISPLAY_DATEFORMAT);
                    }
                    if (trim($this->input->post('last_scheduled')) != '') {
                        $last_scheduled = to_mysql_date($this->input->post('last_scheduled'), RAPTOR_DISPLAY_DATEFORMAT);
                    }
                    
                    //intialize array for insert
                    $scheduleData = array(
                        'contractid'        => trim($this->input->post('contractid')),
                        'servicetypeid'     => trim($this->input->post('servicetypeid')),
                        'subworksid'        => trim($this->input->post('subworksid')),
                        'name'              => trim($this->input->post('name')),
                        'seasonid'          => trim($this->input->post('seasonid')),
                        'season_start_date' => $season_start_date,
                        'season_end_date'   => $season_end_date,
                        'frequency_count'   => trim($this->input->post('frequency_count')),
                        'frequency_period'  => trim($this->input->post('frequency_period')),
                        'visitsperyear'     => trim($this->input->post('visitsperyear')),
                        'firstjobdate'      => $firstjobdate,
                        'maxfloat'          => trim($this->input->post('maxfloat')),
                        'sun_ok'            => (int)trim($this->input->post('sun_ok')),
                        'mon_ok'            => (int)trim($this->input->post('mon_ok')),
                        'tue_ok'            => (int)trim($this->input->post('tue_ok')),
                        'wed_ok'            => (int)trim($this->input->post('wed_ok')),
                        'thu_ok'            => (int)trim($this->input->post('thu_ok')),
                        'fri_ok'            => (int)trim($this->input->post('fri_ok')),
                        'sat_ok'            => (int)trim($this->input->post('sat_ok')),
                        'last_scheduled'    => $last_scheduled,
                        'isactive'          => (int)trim($this->input->post('isactive'))
                    );
                     
                  
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $scheduleid = $this->input->post('scheduleid');
                        $request = array(
                            'contractid'            => trim($this->input->post('contractid')), 
                            'scheduleid'            => $scheduleid,
                            'updateConScheduleData' => $scheduleData,
                            'logged_contactid'      => $this->data['loggeduser']->contactid
                        );

                        $this->contractorclass->updateContractSchedule($request);
                        $message = 'Schedule updated successfully.';
                    }
                    else{
                         
                       //insert
                        $request = array(
                            'insertConScheduleData' => $scheduleData,  
                            'logged_contactid'      => $this->data['loggeduser']->contactid
                        );
                        $response = $this->contractorclass->insertContractSchedule($request);
       
                        $message = 'Schedule added successfully.';
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadContractScheduleSites() {
        
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

            if( $isSuccess ) {
                
                //default settings for uigrid
                $state = $this->input->get('state');
                $scheduleid = $this->input->get('scheduleid');
                $contractid = $this->input->get('contractid');
                $customerid = $this->data['loggeduser']->customerid;
                $data = $this->contractorclass->getContractScheduleSites($customerid, $contractid, $scheduleid, $state);
             
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
    * This function use for add new contact Schedule
    * 
    * @return json 
    */
    public function saveContractScheduleSites() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
      
            $scheduleid = $this->input->post('scheduleid');
         
            if (empty($scheduleid)) {
                $message = 'Please Select Schedule.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                
                $addlabelids = $this->input->post('addlabelids');
                $removelabelids = $this->input->post('removelabelids');
                
                
                $insertScheduleAddress = array();
                $deleteScheduleAddress = array();
                
                if(is_array($addlabelids)){
                    foreach ($addlabelids as $key => $value) {
                        $insertScheduleAddress[] = array(
                            'schedule_def_id'   => $scheduleid,
                            'labelid'           => $value
                        );
                    }
                }
                else{
                    if($addlabelids!=''){
                        $insertScheduleAddress[] = array(
                            'schedule_def_id'   => $scheduleid,
                            'labelid'           => $addlabelids
                        );
                    }
                    
                }
                
                if(is_array($removelabelids)){
                    foreach ($removelabelids as $key => $value) {
                        $deleteScheduleAddress[] = array(
                            'schedule_def_id'   => $scheduleid,
                            'labelid'           => $value
                        );
                    }
                }
                else{
                    if($removelabelids!=''){
                        $deleteScheduleAddress[] = array(
                            'schedule_def_id'   => $scheduleid,
                            'labelid'           => $removelabelids
                        );
                    }
                    
                }
                
                $message = '';
                
                if(count($deleteScheduleAddress)> 0){
                    //delete
                    $request = array(
                        'deleteScheduleAddressData' => $deleteScheduleAddress,  
                        'logged_contactid'      => $this->data['loggeduser']->contactid
                    );
                    $this->contractorclass->deleteContractScheduleSites($request);

                    $message = count($deleteScheduleAddress). ' Sites deleted successfully.<br>';
                }
                if(count($insertScheduleAddress)> 0){
                    //delete
                    $request = array(
                        'insertScheduleAddressData' => $insertScheduleAddress,  
                        'logged_contactid'      => $this->data['loggeduser']->contactid
                    );
                    $this->contractorclass->insertContractScheduleSites($request);

                    $message = count($insertScheduleAddress). ' Sites added successfully.';
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadContractScheduleDetail() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            
            $scheduleid = $this->input->get('scheduleid');
         
            if (empty($scheduleid)) {
                $message = 'Please Select Schedule.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                  
                $data = $this->contractorclass->getContractScheduleById($scheduleid);
         
                
                 //format data
                if(count($data)>0) {
                   
                    $data['season_start_date'] = format_date($data['season_start_date'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data['season_end_date'] = format_date($data['season_end_date'], RAPTOR_DISPLAY_DATEFORMAT);
                   
                    if($data['last_scheduled'] != NULL && $data['last_scheduled'] != ''){
                        if($data['frequency_period'] == 'D'){
                            
                            $data['schedule_from_date'] = date(RAPTOR_DISPLAY_DATEFORMAT, strtotime('+'. $data['frequency_count'] .' days', strtotime($data['last_scheduled'])));
                        }
                        elseif($data['frequency_period'] == 'W'){
                            $data['schedule_from_date'] = date(RAPTOR_DISPLAY_DATEFORMAT, strtotime('+'. $data['frequency_count'] .' weeks', strtotime($data['last_scheduled'])));
                        }
                        else{
                            $data['schedule_from_date'] = date(RAPTOR_DISPLAY_DATEFORMAT, strtotime('+'. $data['frequency_count'] .' months', strtotime($data['last_scheduled'])));
                        }
                        
                    }
                    else{
                        $data['schedule_from_date'] = format_date($data['firstjobdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    }
                    
                    $data['firstjobdate'] = format_date($data['firstjobdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    
                    
                    $data['last_scheduled'] = format_date($data['last_scheduled'], RAPTOR_DISPLAY_DATEFORMAT);
                    
                }
                
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
    * This function use for Delete Contract Schedule Work
    * 
    * @return void
    */
    public function deleteContractScheduleWork() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $contractid = $this->input->post('contractid');
            $scheduleid = $this->input->post('scheduleid');
            $fromdate = $this->input->post('schedule_start_date');
            $todate = $this->input->post('schedule_end_date');
            
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_CONTRACT_SCHEDULE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Contract Schedule.';
            }
            else{
                if($scheduleid =='' || $fromdate == '' || $todate == ''){
                    $message = 'require From/To date is missing for delete Contract Schedule.';
                }
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT); 
                $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
                
                $data = $this->contractorclass->getContractScheduleWorksByDate($contractid, $scheduleid, $fromdate, $todate);
                
                $request = array(
                    'contractid'        => $contractid,
                    'scheduleid'        => $scheduleid,
                    'fromdate'          => $fromdate,
                    'todate'            => $todate,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->contractorclass->deleteContractScheduleWorks($request);
                $message = count($data). ' visits deleted.';
                $scheduleDefData = $this->contractorclass->getContractScheduleById($scheduleid);
                $scheduledata = $this->contractorclass->getContractScheduleWorksByScheduleid($contractid, $scheduleid);
                $updateConScheduleData = array(
                    'last_scheduled'    => NULL
                );
                if(count($scheduledata)>0){
                    $updateConScheduleData['last_scheduled'] = $scheduledata[count($scheduledata)-1]['startdate'];
                }
                $schedulerequest = array(
                    'contractid'            => $contractid, 
                    'scheduleid'            => $scheduleid,
                    'updateConScheduleData' => $updateConScheduleData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->contractorclass->updateContractSchedule($schedulerequest);
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
    * This function use for Create Contract Schedule Work
    * 
    * @return void
    */
    public function createContractScheduleWork() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $contractid = $this->input->post('contractid');
            $scheduleid = $this->input->post('scheduleid');
            $fromdate = $this->input->post('schedule_start_date');
            $todate = $this->input->post('schedule_end_date');
            
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'MAKE_CONTRACT_SCHEDULE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to create Contract Schedule.';
            }
            else{
                if($scheduleid =='' || $fromdate == '' || $todate == ''){
                    $message = 'require From/To date is missing for delete Contract Schedule.';
                }
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
                $insertScheduleWorkData = array();
                
                $scheduleDefData = $this->contractorclass->getContractScheduleById($scheduleid);
                 
                $customerid = $this->data['loggeduser']->customerid;
                $Sitedata = $this->contractorclass->getContractScheduleSites($customerid, $contractid, $scheduleid);
              
                $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT); 
                $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
                 
                $startTime = strtotime($fromdate);
                $endTime = strtotime($todate);
                 
                while ($startTime < $endTime) {  
                    
                    $sequenceno = 0;
                    $sdate = date('Y-m-d', $startTime);
                    if($scheduleDefData['frequency_period'] != 'D'){
                            
                        for($i=0; $i<7; $i++){
                            $day = strtolower(date('D', strtotime('+'. $i .' days', $startTime)));
                            if($scheduleDefData[$day.'_ok'] == 1){ 
                                $sdate = date('Y-m-d', strtotime('+'. $i .' days', $startTime));
                                break;
                            }
                        }
                    }
                     
                    $edate = date('Y-m-d', strtotime('+'. ($scheduleDefData['maxfloat']-1) .' days', strtotime($sdate)));
                    
                    if(strtotime($edate) > $endTime){
                        break;
                    }
                    
                    foreach ($Sitedata['selectedSite'] as $key => $value) {
                        $sequenceno = $sequenceno + 1;
                        $insertScheduleWorkData[] = array(
                            'contractid'        => $contractid,
                            'servicetypeid'     => $scheduleDefData['servicetypeid'],
                            'labelid'           => $value['labelid'],
                            'startdate'         => $sdate,
                            'enddate'           => $edate,
                            'schedule_def_id'   => $scheduleid,
                            'sequenceno'        => $sequenceno,
                            'yearno'            => date('Y', $startTime),
                            'statusid'          => '1' 
                        );
                    }
                    
                    if($scheduleDefData['frequency_period'] == 'D'){
                            
                        $startTime = strtotime('+'. $scheduleDefData['frequency_count'] .' days', $startTime);
                    }
                    elseif($scheduleDefData['frequency_period'] == 'W'){
                        $startTime = strtotime('+'. $scheduleDefData['frequency_count'] .' weeks', $startTime);
                    }
                    else{
                        $startTime = strtotime('+'. $scheduleDefData['frequency_count'] .' months', $startTime);
                    }
                }
               
                $message = count($insertScheduleWorkData) .' schedule work records created.';
                
                $scheduleworkrequest = array( 
                    'insertScheduleWorkData' => $insertScheduleWorkData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->contractorclass->insertContractScheduleWorks($scheduleworkrequest);
                
                $updateScheduleWorkData = array();
                foreach ($Sitedata['selectedSite'] as $key => $value) {
                    $sequenceno = 0;
                    $scheduledata = $this->contractorclass->getContractScheduleWorksByScheduleid($contractid, $scheduleid, $value['labelid']);
                    foreach ($scheduledata as $key1 => $value1) {
                        $sequenceno = $sequenceno + 1;
                        $updateScheduleWorkData[] = array(
                            'id'         => $value1['id'], 
                            'sequenceno' => $sequenceno 
                        );
                    }
                     
                }
                if(count($updateScheduleWorkData)>0){
                    $scheduleworkrequest = array( 
                        'updateScheduleWorkData' => $updateScheduleWorkData,
                        'logged_contactid'      => $this->data['loggeduser']->contactid
                    );

                    $this->contractorclass->updateContractScheduleWorks($scheduleworkrequest);
                }
                
                $scheduledata = $this->contractorclass->getContractScheduleWorksByScheduleid($contractid, $scheduleid);
                $updateConScheduleData = array(
                    'last_scheduled'    => NULL
                );
                if(count($scheduledata)>0){
                    $updateConScheduleData['last_scheduled'] = $scheduledata[count($scheduledata)-1]['startdate'];
                }
                $schedulerequest = array(
                    'contractid'            => $contractid, 
                    'scheduleid'            => $scheduleid,
                    'updateConScheduleData' => $updateConScheduleData,
                    'logged_contactid'      => $this->data['loggeduser']->contactid
                );

                $this->contractorclass->updateContractSchedule($schedulerequest);
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
    public function saveParentJob() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $customerid =$this->session->userdata('raptor_customerid'); 
            $contactid = $this->session->userdata('raptor_contactid');
            //Check Add/Edit Mode
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_CONTRACT');
            $contractData = array();
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Parent Job.';
            }
            else{
                $contractid = trim($this->input->post('contractid'));
                if($contractid){
                    $contractData = $this->contractorclass->getContractById($contractid); 
                    if($contractData==0){
                        $message = 'Contract Detail is invalid.';
                    }
                    if($contractData['customerid'] != $customerid){
                        $message = 'Contract Detail is invalid.';
                    }
                }
                 else{
                      $message = 'Contract Detail is required.';
                 }
            } 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                
                $ContactRules=$this->data['ContactRules'];
                //intialize array for insert
                $siteFMData = $this->customerclass->getContactById(trim($this->input->post('contactid')));
                $addresslabeldetails = $this->customerclass->getAddressById(trim($this->input->post('labelid')));
                            
                $insertdata = array(
                    'customerid'            => $this->session->userdata('raptor_customerid'), //trim($this->input->post('customerid')),
                    'duedate'               => $contractData['enddate'],
                    'duetime'               => '17:00:00',
                    'internalduedate'       => $contractData['enddate'],
                    'jdaysbuffer'           => '0',
                    'origin'                => 'RAPTOR',
                    'jobdescription'        => trim($this->input->post('description')),
                    'userid'                => $this->session->userdata('raptor_email'),
                    'jobstage'              => 'Client_notified',
                    'custordref'            => trim($this->input->post('custordref1')),
                    'custordref2'           => trim($this->input->post('custordref2')),
                    'custordref3'           => trim($this->input->post('custordref3')),
                    'custordrefLabel'       => isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',
                    'custordrefLabel2'      => isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',
                    'custordrefLabel3'      => isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3',
                    'notexceed'             => 0,
                    'dcfmbufferv'           => 0,
                    'internalqlimit'        => 0,
                    'nonchargeable'         => 'on',
                    'labelid'               => trim($this->input->post('labelid')),
                    'siteline1'             => isset($addresslabeldetails["siteline1"]) ? $addresslabeldetails["siteline1"] : '',
                    'siteline2'             => isset($addresslabeldetails["siteline2"]) ? $addresslabeldetails["siteline2"] : '',
                    'sitesuburb'            => isset($addresslabeldetails["sitesuburb"]) ? $addresslabeldetails["sitesuburb"] : '',
                    'sitestate'             => isset($addresslabeldetails["sitestate"]) ? $addresslabeldetails["sitestate"] : '',
                    'sitepostcode'          => isset($addresslabeldetails["sitepostcode"]) ? $addresslabeldetails["sitepostcode"] : '',
                    'territory'             => isset($addresslabeldetails["territory"]) ? $addresslabeldetails["territory"] : '',
                    //'country'               => trim($this->input->post('country')),
                    'sitecontactid'         => isset($addresslabeldetails["sitecontactid"]) ? $addresslabeldetails["sitecontactid"] : '',
                    'sitecontact'           => isset($addresslabeldetails["sitecontact"]) ? $addresslabeldetails["sitecontact"] : '',
                    'sitephone'             => isset($addresslabeldetails["sitephone"]) ? $addresslabeldetails["sitephone"] : '',
                    'siteemail'             => isset($addresslabeldetails["siteemail"]) ? $addresslabeldetails["siteemail"] : '',
                    'contactid'             => trim($this->input->post('contactid')),
                    'sitefm'                => isset($siteFMData["firstname"]) ? $siteFMData["firstname"] : '',
                    'sitefmph'              => isset($siteFMData["mobile"]) ? $siteFMData["mobile"] : (isset($siteFMData["phone"]) ? $siteFMData["phone"] : ''),
                    'sitefmemail'           => isset($siteFMData["email"]) ? $siteFMData["email"] : '',
                    'clientnotified'        => 'on', 
                    'recurring'             => 'on',
                    'closed'                => 'on',
                    'iscontract'            => 'on',
                    'internalqlimit'        => 1
                );
                 
                //check Add/Edit Mode
                if (trim($this->input->post('mode')) == 'edit') {
                    
                    $jobid = $this->input->post('jobid');
                    
                    $request = array(
                        'jobid'            => $jobid,
                        'updateData'       => $insertdata, 
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );

                    $this->jobclass->updateJob($request);
                    
                    $message = 'Parent Job updated successfully.';
                }
                else{
                    
                    $insertdata["leaddate"] = date('Y-m-d',time());// $leaddate->format('Y-m-d');
                    $insertdata["datenotified"] = date('Y-m-d H:i:s', time());
                     
                    $request = array(
                        'jobData'           => $insertdata, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );
                    $response = $this->jobclass->createJob($request);
                    
                    $jobid= $response['jobid'];
                    $insertParentJobData = array(
                        'customerid'  => $this->session->userdata('raptor_customerid'),
                        'contractid'  => trim($this->input->post('contractid')),
                        'contactid'   => trim($this->input->post('contactid')),
                        'parentjobid' => $jobid,
                        'monthofyear' => date('m'),
                        'year'        => date('Y'),
                        'custordref'  => trim($this->input->post('custordref1')), 
                        'custordref2' => trim($this->input->post('custordref2')), 
                        'custordref3' => trim($this->input->post('custordref3')),
                        'status'      => 1
                    ); 
                    $request = array(
                        'insertParentJobData'  => $insertParentJobData, 
                        'logged_contactid'     => $this->data['loggeduser']->contactid
                    );
                    $this->contractorclass->insertConParentJob($request);
                    
                    $message = 'Parent Job added successfully.';
                }
                
                $updateContractData = array(
                    'parentjobid'       => $jobid,
                    'labelid'           => trim($this->input->post('labelid')),
                    'contactid'         => trim($this->input->post('contactid')),
                    'custordref1'       => trim($this->input->post('custordref1')), 
                    'custordref2'       => trim($this->input->post('custordref2')), 
                    'custordref3'       => trim($this->input->post('custordref3')),
                    'description'       => trim($this->input->post('description')),  
                    'modifieddate'      => date('Y-m-d H:i:s', time()), 
                    'modifiedby'        => $this->session->userdata('raptor_contactid')
                );
                
                $request = array(
                    'updateContractData' => $updateContractData,  
                    'contractid'         => trim($this->input->post('contractid')),  
                    'logged_contactid'   => $this->session->userdata('raptor_contactid')
                );

                $this->contractorclass->updateContract($request);
               
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
    * This function use for load Site Parent Job Orders
    * 
    * @return json 
    */
    public function loadSiteParentJobOrders() {
        
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
                $field = 'sitesuburb';
                $filter = '';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = $this->input->get('filtertext');
                }
 
                if (trim($this->input->get('state')) != '') {
                    $params['a.sitestate'] = $this->input->get('state');
                }
                
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                $contractid = $this->input->get('contractid');
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get site addresses data
                $addressData = $this->contractorclass->getSiteParentJobOrders($customerid, $contractid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $addressData['trows'];
                $data = $addressData['data'];
                
                
                
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
    public function exportSiteParentJobOrders() {
        
        
        //check export excel access
//        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTRACT');
//        if(!$export_excel) {
//            show_404();
//        }
        
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        
        if (trim($this->input->get('state')) != '') {
            $params['a.sitestate'] = $this->input->get('state');
        }
        
        
        $customerid = $this->session->userdata('raptor_customerid'); 
        $contractid = $this->input->get('contractid');
        
        $order = 'desc';
        $field = 'sitesuburb';
        $addressData = $this->contractorclass->getSiteParentJobOrders($customerid, $contractid, NULL, 0, $field, $order, $filter, $params);
                 
        $data = $addressData['data'];
  

        $data_array = array();
        $ContactRules=$this->data['ContactRules'];
        $heading = array('Labelid', 'Site Ref', 'Street Address', 'Suburb', 'State', 'Post Code', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3');

        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            $result[] = $row['labelid']; 
            $result[] = $row['siteref']; 
            $result[] = $row['siteline2'];
            $result[] = $row['sitesuburb'];
            $result[] = $row['sitestate'];
            $result[] = $row['sitepostcode']; 
            $result[] = $row1['custordref'];
            $result[] = $row1['custordref2'];
            $result[] = $row1['custordref3'];
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "Contract_Site_Parent_Job_Orders.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Site Parent Job Orders", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Contract_Site_Parent_Job_Orders.xls', $data);
    }
    
    
     /**
    * This function use for load Site Parent Job Orders
    * 
    * @return json 
    */
    public function loadSiteOrders() {
        
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
                $field = 'scheduledate';
                $filter = '';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = $this->input->get('filtertext');
                }

                if (trim($this->input->get('serviceid')) != '') {
                    $params['cor.contract_service_id'] = $this->input->get('serviceid');
                }
             
                if (trim($this->input->get('labelid')) != '') {
                    $params['cor.labelid'] = $this->input->get('labelid');
                }
                 
                if (trim($this->input->get('month')) != '') {
                    $params['cor.monthofyear'] = $this->input->get('month');
                }
                if (trim($this->input->get('year')) != '') {
                    $params['cor.year'] = $this->input->get('year');
                }
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                $contractid = $this->input->get('contractid');
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get site addresses data
                $addressData = $this->contractorclass->getSiteOrders($customerid, $contractid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $addressData['trows'];
                $data = $addressData['data'];
                
                 //format data
                foreach ($data as $key => $value) {
                   
                    $data[$key]['scheduledate'] = format_date($value['scheduledate'], RAPTOR_DISPLAY_DATEFORMAT);
                   
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
    public function exportSiteOrders() {
        
        
        //check export excel access
//        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTRACT');
//        if(!$export_excel) {
//            show_404();
//        }
        
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('serviceid')) != '') {
            $params['cor.contract_service_id'] = $this->input->get('serviceid');
        }

        if (trim($this->input->get('labelid')) != '') {
            $params['cor.labelid'] = $this->input->get('labelid');
        }

        if (trim($this->input->get('month')) != '') {
            $params['cor.monthofyear'] = $this->input->get('month');
        }
        if (trim($this->input->get('year')) != '') {
            $params['cor.year'] = $this->input->get('year');
        }
        
        
        $customerid = $this->session->userdata('raptor_customerid'); 
        $contractid = $this->input->get('contractid');
        
        $order = 'desc';
        $field = 'scheduledate';
        $addressData = $this->contractorclass->getSiteOrders($customerid, $contractid, NULL, 0, $field, $order, $filter, $params);
                 
        $data = $addressData['data'];
  

        $data_array = array();
        $ContactRules=$this->data['ContactRules'];
        $heading = array('Date', 'Service', isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3', 'Amount');
 
        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = format_date($row['scheduledate'], RAPTOR_DISPLAY_DATEFORMAT); 
            $result[] = $row['name'];
            $result[] = $row['customer_order_reference1'];
            $result[] = $row['customer_order_reference2'];
            $result[] = $row['customer_order_reference2']; 
            $result[] = $row['orderamount'];
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "Contract_Site_Orders.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Site Orders", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Contract_Site_Orders.xls', $data);
    }
    
    
    /**
    * This function use for add new contact Schedule
    * 
    * @return json 
    */
    public function createSiteOrders() {

        
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
                $this->form_validation->set_rules('serviceid', "Service", 'trim|required');
             
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $isSuccess = FALSE;
                    $success = SuccessClass::initialize($isSuccess);
                    $message = validation_errors();
                    
                }
                else {
                    
                    $insertCustomerSiteOrderData = array(); 
                    $contractid = $this->input->post('contractid'); 
                    $siteData = $this->contractorclass->getContractSiteAddresses($contractid);
                    foreach ($siteData as $row) {
                        
                        $d = $this->contractorclass->getConCustomerSiteOrderRefData($contractid, $row['labelid'], trim($this->input->post('month')), trim($this->input->post('year')), trim($this->input->post('serviceid')));
                        
                        if(count($d) == 0){
                            //intialize array for insert
                            $insertCustomerSiteOrderData[] = array(
                                'customerid'            => $this->data['loggeduser']->customerid,
                                'contractid'            => trim($this->input->post('contractid')),
                                'contract_service_id'   => trim($this->input->post('serviceid')),
                                'scheduledate'          => trim($this->input->post('year')). '-'. trim($this->input->post('month')).'-1',
                                'monthofyear'           => trim($this->input->post('month')),
                                'year'                  => trim($this->input->post('year')),
                                'labelid'               => $row['labelid'], 
                                'status'                => 1
                            );
                        }
                    }
                     
                    if(count($insertCustomerSiteOrderData)>0){
                        //insert
                        $request = array(
                             'insertCustomerSiteOrderData'  => $insertCustomerSiteOrderData,  
                             'logged_contactid'             => $this->data['loggeduser']->contactid
                        );
                        $this->contractorclass->insertConCustomerSiteOrderRef($request);
                    }
                    $message = count($insertCustomerSiteOrderData).' rows created.';

                   
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
     * download import file
     * 
     * @param type $FromYearMonth
     * @param type $ToYearMonth
     *      
     * @return void
     */
    public function downloadSiteOrderTemplate() 
    {
      
        $ContactRules=$this->data['ContactRules'];
        $heading = array('Label Id', 'siteref', 'siteline2', 'sitesuburb', 'sitestate', 'scheduldate', 'month', 'year',  isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3', 'Amount');  
        
        //default settings for uigrid
     
        $contractid = $this->input->get('contractid'); 
        $siteData = $this->contractorclass->getContractSiteAddresses($contractid);
        $this->load->library('excel');
        $data_array = array();
        //format data for excel
        foreach ($siteData as $row)
        { 
            $result = array();
              
            $result[] = $row['labelid']; 
            $result[] = $row['siteref']; 
            $result[] = $row['siteline2'];
            $result[] = $row['sitesuburb'];
            $result[] = $row['sitestate'];
            
            $data_array[] = $result;
        }
      
        
        $file_name="site_customer_order.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }
        $this->excel->Exportexcel("Site Order", $dir, $file_name, $heading, $data_array);

        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($file_name, $data);
   	 	  
    }
      
    
     
     /**
     * import budget excel
     * 
     * @return json
     * 
     */
    public function importSiteOrderExcel() 
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerid =$this->session->userdata('raptor_customerid');
             
 
     
            $dir = "./temp";
            if(!is_dir($dir))
            {
                    mkdir($dir, 0755, true);
            }
             $filen = $_FILES['importfile']['name'];

            $ext = pathinfo($filen, PATHINFO_EXTENSION);
            $filename="import_customer_order.".$ext;
            $config['upload_path'] = $dir;
            $config['allowed_types'] = "xls|xlsx";
            $config['file_name'] = "import_customer_order";
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("importfile")){
                  $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();

            }
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
            if( $isSuccess )
            {
                
                $this->load->library('excel');

                $objPHPExcel = PHPExcel_IOFactory::load($dir.'/'.$filename);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
           
                if(count($sheetData)<=1){
                    $success = SuccessClass::initialize(FALSE);
                    $message="Invalid Excel File";
                }
                else{
                    
                    //Create array for insert GL CODE data
                    $updateData = array(
                        'customerid'            => $this->data['loggeduser']->customerid,
                        'contractid'            => trim($this->input->post('contractid')),
                        'contract_service_id'   => trim($this->input->post('serviceid')),
                        'scheduledate'          => date('Y-m-d'),
                        'status'                => 1
                    );
                    $updatedCount = 0;
                    $addedCount = 0;
                    
                    $insertCustomerSiteOrderData = array(); 
                    $updateCustomerSiteOrderData = array(); 
                    
                    $contractid = $this->input->post('contractid'); 
                     
                    foreach($sheetData as $key=>$value) {

                        if($key == 1) {
                            if(count($value)!= 12){
                                $success = SuccessClass::initialize(FALSE);
                                $message="Invalid Import Excel Site order format ";
                                break;
                            }
                            continue;
                        }
 
                        if(trim($value['A'])!='' && trim($value['G'])!='' && trim($value['H'])!=''){
                            
                            $updateData['labelid'] = $value['A'];
                            $updateData['monthofyear'] = $value['G'];
                            $updateData['year'] = $value['H'];
                            
                            $updateData['scheduledate'] = $updateData['year']. '-'. $updateData['monthofyear'].'-01';
                            $updateData['customer_order_reference1'] = $value['I'];
                            $updateData['customer_order_reference2'] = $value['J'];
                            $updateData['customer_order_reference3'] = $value['K'];
                            $updateData['orderamount'] = (int)$value['L'];
                            $d = $this->contractorclass->getConCustomerSiteOrderRefData($contractid, $updateData['labelid'], $updateData['monthofyear'], $updateData['year'], trim($this->input->post('serviceid')));
                            
                            if(count($d)>0){
                                $updateData['id'] = $d['id'];
                                $updateCustomerSiteOrderData[] = $updateData;
                                $updatedCount = $updatedCount + 1;
                            }
                            else{
                                
                                unset($updateData['id']);
                                $insertCustomerSiteOrderData[] = $updateData;
                                $addedCount = $addedCount + 1;
                            }
                        }
                    }
 
                    if(count($insertCustomerSiteOrderData)>0){
                        //insert
                        $request = array(
                             'insertCustomerSiteOrderData'  => $insertCustomerSiteOrderData,  
                             'logged_contactid'             => $this->data['loggeduser']->contactid
                        );
                        $this->contractorclass->insertConCustomerSiteOrderRef($request);
                    }
                    
                    if(count($updateCustomerSiteOrderData)>0){
                        //insert
                        $request = array(
                             'updateCustomerSiteOrderData'  => $updateCustomerSiteOrderData,  
                             'logged_contactid'             => $this->data['loggeduser']->contactid
                        );
                        $this->contractorclass->updateConCustomerSiteOrderRef($request);
                    }
                    $message =$addedCount. ' rows created and '. $updatedCount . ' rows updated'; 

                    $success->setData($data);
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
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateCustomerSiteOrder() {

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
                //get post data 
                $id = $this->input->post('id');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                
                
                $updateCustomerSiteOrderData = array(array(
                    $field => $value,
                    'id'    => $id
                ));
                
                $request = array(
                    'updateCustomerSiteOrderData'  => $updateCustomerSiteOrderData,  
                    'logged_contactid'             => $this->data['loggeduser']->contactid
                );
                $this->contractorclass->updateConCustomerSiteOrderRef($request);
                
                    
                
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
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateParentJobOrder() {

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
                //get post data 
                $id = $this->input->post('id');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                
                $updateData = array(
                    $field => $value
                );
                
                $this->contractorclass->updateConParentJob($id, $updateData);
               
                    
                
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
    
    
    //Technicians
    /**
    * This function use for load Contract Technicians in uigrid
    * 
    * @return json 
    */
    public function loadContractTechnicians() {
        
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
                $order = 'asc';
                $field = 'userid';
                $filter = '';
                $params = array();
              
               
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }
 
                $customerid =$this->session->userdata('raptor_customerid');
                $contractid = $this->input->get('contractid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data 
                $contactData = $this->contractorclass->getContractTechnicians($customerid, $contractid, $size, $start, $field, $order, $filter, $params);
                  
                $trows  = $contactData['trows'];
                $data = $contactData['data'];
             
                //format data
                foreach($data as $key=>$value) {
                     
                    $data[$key]['startdate'] = format_date($value['startdate'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['enddate'] = format_date($value['enddate'], RAPTOR_DISPLAY_DATEFORMAT);
                    
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
    public function exportContractTechnicians() {
        
//        //check export excel access
//        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_SUPPLIER_CONTACT');
//        if(!$export_excel) {
//            show_404();
//        }
        
        $order = 'asc';
        $field = 'userid';
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';
 
        $contractid = $this->input->get('contractid');
        $customerid =$this->session->userdata('raptor_customerid');
        //get contacts data
        $contactData = $this->contractorclass->getContractTechnicians($customerid, $contractid, NULL, 0, $field, $order, $filter, $params);
               
        
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('User', 'Contact', 'Normal Rate', 'Week A/H', 'Saturday', 'Sunday', 'Public Holiday', 'Start', 'End', 'Active');
        $this->load->library('excel');

        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            $result[] = $row['userid'];
            $result[] = $row['contact'];
            $result[] = $row['normal_rate'];
            $result[] = $row['weekah_rate'];
            $result[] = $row['saturday_rate'];
            $result[] = $row['sunday_rate'];
            $result[] = $row['pubhol_rate'];
            $result[] = format_date($row['startdate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = format_date($row['enddate'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = $row['isactive'] == 1 ? 'Yes' : 'No';
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "ContractTechnicians.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Contract Technicians", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('ContractTechnicians.xls', $data);
        
    }
    
    /**
    * This function use for Delete Contract Technicians
    * 
    * @return void
    */
    public function deleteContractTechnician() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $contechnicianid = $this->input->post('id');
            
//            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_SUPPLIER_CONTACT');
//          
//            //Check Add Rights exist or not
//            if (!$userRights) {
//                $message = 'You are not allow to delete Contract Technician.';
//            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'contechnicianid'      => $contechnicianid,
                    'contractid'    => trim($this->input->post('contractid')),
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->contractorclass->deleteContractTechnician($request);
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
    public function saveContractTechnician() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
//            if (trim($this->input->post('mode')) == 'edit') {
//                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_SUPPLIER_CONTACT');
//            }
//            else{
//                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_SUPPLIER_CONTACT');
//            }
//        
//            //Check Add Rights exist or not
//            if (!$userRights) {
//                $message = 'You are not allow to ' . $this->input->post('mode') . ' Contact.';
//            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
               
                //set form validation rule
                $this->form_validation->set_rules('userid', "Technician", 'trim|required|callback_check_technician');
           
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors() 
                    );
                }
                else {
                    $startDate = NULL;
                    $endDate = NULL;
                    if(trim($this->input->post('startdate')) != ''){
                        $startDate = to_mysql_date(trim($this->input->post('startdate')), RAPTOR_DISPLAY_DATEFORMAT);
                    }
                    if(trim($this->input->post('enddate')) != ''){
                        $endDate = to_mysql_date(trim($this->input->post('enddate')), RAPTOR_DISPLAY_DATEFORMAT);
                    }
                    //intialize array for insert
                    $contactData = array(
                        'contractid'    => trim($this->input->post('contractid')), 
                        'userid'     => trim($this->input->post('userid')), 
                        'normal_rate'   => (float)trim($this->input->post('normal_rate')),
                        'weekah_rate'   => (float)trim($this->input->post('weekah_rate')),
                        'saturday_rate'   => (float)trim($this->input->post('saturday_rate')),
                        'sunday_rate'   => (float)trim($this->input->post('sunday_rate')),
                        'pubhol_rate'   => (float)trim($this->input->post('pubhol_rate')),
                        'startdate'       => $startDate,
                        'enddate'        => $endDate, 
                        'notes'      => trim($this->input->post('notes')),   
                        'isactive'        => (int)trim($this->input->post('isactive')) 
                    );
                     
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $contechnicianid = $this->input->post('contechnicianid');
                        $request = array(
                            'contechnicianid'          => $contechnicianid,
                            'contractid'    => trim($this->input->post('contractid')),
                            'updateConTechnicianData'  => $contactData,
                            'logged_contactid'   => $this->data['loggeduser']->contactid
                        );

                        $this->contractorclass->updateContractTechnician($request);
                        $message = 'Contract Technician updated successfully.';
                    }
                    else{
                         
                       //insert
                        $request = array(
                            'insertConTechnicianData' => $contactData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );
                        $response = $this->contractorclass->insertContractTechnician($request);
                        //$contechnicianid = $response['contechnicianid'];
                         $message = 'Contract Technician Added successfully.';
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
    public function check_technician($userid)
    {

        if (empty($userid)){
            return FALSE;
        }
        
        $contechnicianid = 0;
        $contractid = trim($this->input->post('contractid'));
        if (trim($this->input->post('mode')) == 'edit') {
            $contechnicianid = trim($this->input->post('contechnicianid'));
            
        }
        
        
        if($this->contractorclass->checkContractTechnician($contractid, $userid, $contechnicianid)){
            $this->form_validation->set_message('check_technician', 'Technician Already exist for this contract');
            return false;
        }
        else
        {
            return TRUE;
        }

    }
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function updateTechnicianStatus() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $contechnicianid = $this->input->post('id');
//            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_CONTRACT_SITE');
//          
//            //Check Add Rights exist or not
//            if (!$userRights) {
//                $message = 'You are not allow to update Technician status.';
//            }
           
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess) {
                 
                $contactData = array( 
                    'isactive'  => (int)$this->input->post('isactive')
                );
  
                $request = array(
                    'contechnicianid'          => $contechnicianid,
                    'updateConTechnicianData'  => $contactData,
                    'logged_contactid'   => $this->data['loggeduser']->contactid
                );

                $this->contractorclass->updateContractTechnician($request);
               
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
    
    
    //Parent Job
    /**
    * This function use for load Contract Technicians in uigrid
    * 
    * @return json 
    */
    public function loadContractParentJobs() {
        
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
                $order = 'asc';
                $field = 'userid';
                $filter = '';
                $params = array();
              
               
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }
 
                $customerid =$this->session->userdata('raptor_customerid');
                $contractid = $this->input->get('contractid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data 
                $contactData = $this->contractorclass->getContractParentJobs($customerid, $contractid, $size, $start, $field, $order, $filter, $params);
                  
                $trows  = $contactData['trows'];
                $data = $contactData['data'];
             
                //format data
                foreach($data as $key=>$value) {
                     
                    $data[$key]['leaddate'] = format_date($value['leaddate'], RAPTOR_DISPLAY_DATEFORMAT); 
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
    public function exportContractParentJobs() {
        
//        //check export excel access
//        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_SUPPLIER_CONTACT');
//        if(!$export_excel) {
//            show_404();
//        }
        
        $order = 'asc';
        $field = 'userid';
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';
 
        $contractid = $this->input->get('contractid');
        $customerid =$this->session->userdata('raptor_customerid');
        //get contacts data
        $contactData = $this->contractorclass->getContractParentJobs($customerid, $contractid, NULL, 0, $field, $order, $filter, $params);
               
        
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('Month', 'Year', 'Parent Job Id', 'Contract order', 'Job Order Ref1', 'Job Order Ref2', 'Job Order Ref3', 'Created');
        $this->load->library('excel');

        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            $result[] = $row['monthofyear'];
            $result[] = $row['year'];
            $result[] = $row['parentjobid'];
            $result[] = $row['service'];
            $result[] = $row['custordref'];
            $result[] = $row['custordref2'];
            $result[] = $row['custordref3'];
            $result[] = format_date($row['leaddate'], RAPTOR_DISPLAY_DATEFORMAT);
            
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "ContractParentJobs.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Contract ParentJobs", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('ContractParentJobs.xls', $data);
        
    }
    
    /**
    * This function use for Delete Contract Technicians
    * 
    * @return void
    */
    public function deleteContractParentJob() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $id = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_CONTRACT_PARENT_JOB');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Contract Parent Jobs.';
            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $this->contractorclass->updateConParentJob($id, array('status' => 0));
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
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function getContractParentJobRules() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $contractid = trim($this->input->get('contractid')); 
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $data = array();
                $ruleData = $this->contractorclass->getContParentJobRuleDefinition($contractid);
                
                if(count($ruleData) == 0){
                    $successdata = FALSE;
                    $ruleData = array();
                } 
                else{
                    foreach ($ruleData as $key => $value) {
                        if($value == NULL){
                            $ruleData[$key] = '';
                        }
                    }
                    $successdata = TRUE;
                   
                }
                $data = array(
                    'success'  => $successdata,
                    'data'  => $ruleData,
                    'monthofyear' => date('m', strtotime('+1 month', time())),
                    'year' => date('Y', strtotime('+1 month', time())),
                    'attendancedate' => date(str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT), strtotime('+1 month', time())),
                    'completiondate' => date(str_replace('d', 't', RAPTOR_DISPLAY_DATEFORMAT), strtotime('+1 month', time()))
                );
               
                $success->setData($data);
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
    public function saveContractParentJobs() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $customerid =$this->session->userdata('raptor_customerid'); 
            $contactid = $this->session->userdata('raptor_contactid');
            //Check Add/Edit Mode
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_CONTRACT_PARENT_JOB');
            $contractData = array();
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to create Parent Job.';
            }
            else{
                $contractid = trim($this->input->post('contractid'));
                if($contractid){
                    $contractData = $this->contractorclass->getContractById($contractid); 
                    if($contractData==0){
                        $message = 'Contract Detail is invalid.';
                    }
                    if($contractData['customerid'] != $customerid){
                        $message = 'Contract Detail is invalid.';
                    }
                }
                 else{
                      $message = 'Contract Detail is required.';
                 }
            } 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                
                $ContactRules=$this->data['ContactRules'];
                $contParentJobRuledata = $this->contractorclass->getContParentJobRuleDefinition($contractid);
                $contParentJobValuedata = $this->contractorclass->getContractParentJobValues($contractid);
                
                $attendancedate = NULL;
                $completiondate = NULL;
                if(trim($this->input->post('attendancedate')) != ''){
                    $attendancedate = to_mysql_date(trim($this->input->post('attendancedate')), RAPTOR_DISPLAY_DATEFORMAT);
                }
                if(trim($this->input->post('completiondate')) != ''){
                    $completiondate = to_mysql_date(trim($this->input->post('completiondate')), RAPTOR_DISPLAY_DATEFORMAT);
                }
                $labourjobid = 0;
                //Labour Job
                if((int)$this->input->post('islabourjob') == 1) 
                {
                    $insertdata = array(
                        'customerid'            => isset($contParentJobValuedata["customerid"]) ? $contParentJobValuedata["customerid"] : $this->session->userdata('raptor_customerid'),
                        'custordref'            => trim($this->input->post('custordref1value')),
                        'custordref2'           => trim($this->input->post('custordref2value')),
                        'custordref3'           => trim($this->input->post('custordref3value')),
                        'custordrefLabel'       => isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',
                        'custordrefLabel2'      => isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',
                        'custordrefLabel3'      => isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3',
                        'parentid'              => isset($contParentJobValuedata["parentjobid"]) ? $contParentJobValuedata["parentjobid"] : 0,
                        'jobdescription'        => isset($contParentJobValuedata["jobdescription"]) ? $contParentJobValuedata["jobdescription"] : trim($this->input->post('monthofyear')).' '.trim($this->input->post('year')).' Labour',
                        'contactid'             => isset($contParentJobValuedata["contactid"]) ? $contParentJobValuedata["contactid"] : 0,
                        'labelid'               => isset($contParentJobValuedata["labelid"]) ? $contParentJobValuedata["labelid"] : 0,
                        'sitefm'                => isset($contParentJobValuedata["sitefm"]) ? $contParentJobValuedata["sitefm"] : '',
                        'leaddate'              => date('Y-m-d',time()),
                        'jrespdate'             => $attendancedate,
                        'jrespintdate'          => $attendancedate,
                        'duedate'               => $completiondate,
                        'duetime'               => '17:00:00',
                        'internalduedate'       => $completiondate,
                        'jdaysbuffer'           => '0',
                        'responseduedate'       => $attendancedate,
                        'responseduetime'       => '08:00:00',
                        'origin'                => 'CONTRACT',
                        'jobstage'              => trim($this->input->post('jobstage')),
                        'siteline1'             => isset($contParentJobValuedata["siteaddress"]) ? $contParentJobValuedata["siteaddress"] : '',
                        'siteline1'             => isset($contParentJobValuedata["siteline1"]) ? $contParentJobValuedata["siteline1"] : '',
                        'siteline2'             => isset($contParentJobValuedata["siteline2"]) ? $contParentJobValuedata["siteline2"] : '',
                        'sitesuburb'            => isset($contParentJobValuedata["sitesuburb"]) ? $contParentJobValuedata["sitesuburb"] : '',
                        'sitestate'             => isset($contParentJobValuedata["sitestate"]) ? $contParentJobValuedata["sitestate"] : '',
                        'sitepostcode'          => isset($contParentJobValuedata["sitepostcode"]) ? $contParentJobValuedata["sitepostcode"] : '',
                        'territory'             => isset($contParentJobValuedata["territory"]) ? $contParentJobValuedata["territory"] : '',
                        'estimatedsell'         => (float)trim($this->input->post('estimated_sell')),
                        'notexceed'             => (float)trim($this->input->post('estimated_sell')),
                        'dcfmbufferv'           => (float)trim($this->input->post('internal_buffer')),
                        'internalqlimit'        => (float)trim($this->input->post('estimated_sell')) - (float)trim($this->input->post('internal_buffer')),
                        'nonchargeable'         => (int)$this->input->post('ischargeable') !=1 ? 'on' : NULL,
                        'recurring'             => 'on', 
                        'iscontract'            => 'on',
                        'contractid'            => $contractid,
                        'sitefmemail'           => '',
                        'siteemail'           => ''
                    );
                  
                     
                    $request = array(
                        'jobData'           => $insertdata, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );
                    $response = $this->jobclass->createJob($request);
                    
                    $jobid= $response['jobid'];
					$labourjobid = $jobid;
                    $insertParentJobData = array(
                        'customerid'  => $this->session->userdata('raptor_customerid'),
                        'contract_service_id'    => isset($contParentJobRuledata["contract_service_id"]) ? $contParentJobRuledata["contract_service_id"] : 0,
                        'contractid'  => trim($this->input->post('contractid')),
                        'contactid'             => isset($contParentJobValuedata["contactid"]) ? $contParentJobValuedata["contactid"] : $contactid,
                        'parentjobid' => $jobid,
                        'monthofyear' => trim($this->input->post('monthofyear')),
                        'year'        => trim($this->input->post('year')),
                        'custordref'  => trim($this->input->post('custordref1value')), 
                        'custordref2' => trim($this->input->post('custordref2value')), 
                        'custordref3' => trim($this->input->post('custordref3value')),
                        'status'      => 1
                    ); 
                    $request = array(
                        'insertParentJobData'  => $insertParentJobData, 
                        'logged_contactid'     => $this->data['loggeduser']->contactid
                    );
                    $this->contractorclass->insertConParentJob($request);
                }
                   
                
                //Material Job
                if((int)$this->input->post('ismaterialsjob') == 1) 
                {
                    $insertdata = array(
                        'customerid'            => isset($contParentJobValuedata["customerid"]) ? $contParentJobValuedata["customerid"] : $this->session->userdata('raptor_customerid'),
                        'custordref'            => trim($this->input->post('custordref1value')),
                        'custordref2'           => trim($this->input->post('custordref2value')),
                        'custordref3'           => trim($this->input->post('custordref3value')),
                        'custordrefLabel'       => isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1',
                        'custordrefLabel2'      => isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2',
                        'custordrefLabel3'      => isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3',
                        'parentid'              => isset($contParentJobValuedata["parentjobid"]) ? $contParentJobValuedata["parentjobid"] : 0,
                        'jobdescription'        => isset($contParentJobValuedata["jobdescription"]) ? $contParentJobValuedata["jobdescription"] : trim($this->input->post('monthofyear')).' '.trim($this->input->post('year')).' Material',
                        'contactid'             => isset($contParentJobValuedata["contactid"]) ? $contParentJobValuedata["contactid"] : 0,
                        'labelid'               => isset($contParentJobValuedata["labelid"]) ? $contParentJobValuedata["labelid"] : 0,
                        'sitefm'                => isset($contParentJobValuedata["sitefm"]) ? $contParentJobValuedata["sitefm"] : '',
                        'leaddate'              => date('Y-m-d',time()),
                        'jrespdate'             => $attendancedate,
                        'jrespintdate'          => $attendancedate,
                        'duedate'               => $completiondate,
                        'duetime'               => '17:00:00',
                        'internalduedate'       => $completiondate,
                        'jdaysbuffer'           => '0',
                        'responseduedate'       => $attendancedate,
                        'responseduetime'       => '08:00:00',
                        'origin'                => 'CONTRACT',
                        'jobstage'              => trim($this->input->post('jobstage')),
                        'siteline1'             => isset($contParentJobValuedata["siteaddress"]) ? $contParentJobValuedata["siteaddress"] : '',
                        'siteline1'             => isset($contParentJobValuedata["siteline1"]) ? $contParentJobValuedata["siteline1"] : '',
                        'siteline2'             => isset($contParentJobValuedata["siteline2"]) ? $contParentJobValuedata["siteline2"] : '',
                        'sitesuburb'            => isset($contParentJobValuedata["sitesuburb"]) ? $contParentJobValuedata["sitesuburb"] : '',
                        'sitestate'             => isset($contParentJobValuedata["sitestate"]) ? $contParentJobValuedata["sitestate"] : '',
                        'sitepostcode'          => isset($contParentJobValuedata["sitepostcode"]) ? $contParentJobValuedata["sitepostcode"] : '',
                        'territory'             => isset($contParentJobValuedata["territory"]) ? $contParentJobValuedata["territory"] : '',
                        'estimatedsell'         => (float)trim($this->input->post('estimated_sell')),
                        'notexceed'             => (float)trim($this->input->post('estimated_sell')),
                        'dcfmbufferv'           => (float)trim($this->input->post('internal_buffer')),
                        'internalqlimit'        => (float)trim($this->input->post('estimated_sell')) - (float)trim($this->input->post('internal_buffer')),
                        'nonchargeable'         => (int)$this->input->post('ischargeable') !=1 ? 'on' : NULL,
                        'recurring'             => 'on', 
                        'iscontract'            => 'on',
                        'contractid'            => $contractid,
                        'sitefmemail'           => '',
                        'siteemail'           => ''
                    );
                  
                     
                    $request = array(
                        'jobData'           => $insertdata, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );
                    $response = $this->jobclass->createJob($request);
                    
                    $jobid= $response['jobid'];
                    $insertParentJobData = array(
                        'customerid'  => $this->session->userdata('raptor_customerid'),
                        'contractid'  => trim($this->input->post('contractid')),
                        'contract_service_id'    => isset($contParentJobRuledata["contract_service_id"]) ? $contParentJobRuledata["contract_service_id"] : 0,
                        'contactid'             => isset($contParentJobValuedata["contactid"]) ? $contParentJobValuedata["contactid"] : $contactid,
                        'parentjobid' => $jobid,
                        'monthofyear' => trim($this->input->post('monthofyear')),
                        'year'        => trim($this->input->post('year')),
                        'custordref'  => trim($this->input->post('custordref1value')), 
                        'custordref2' => trim($this->input->post('custordref2value')), 
                        'custordref3' => trim($this->input->post('custordref3value')),
                        'status'      => 1
                    ); 
                    $request = array(
                        'insertParentJobData'  => $insertParentJobData, 
                        'logged_contactid'     => $this->data['loggeduser']->contactid
                    );
                    $this->contractorclass->insertConParentJob($request);
                } 
                
				//safetysheetjob Job
                if((int)$this->input->post('issafetysheetjob') == 1) 
                {
                    if($labourjobid ==0){
                        $labourjobid = $this->contractorclass->getContParentJobId($contractid, trim($this->input->post('monthofyear')), trim($this->input->post('year')));
                    }
                   
                    if($labourjobid > 0){
                        $techs = $this->contractorclass->getActiveContractTechnicians($contractid);
                        $contractedHours = $this->contractorclass->getConContractedHours($contractid);
                        
                        if(count($contractedHours)>0 && count($techs)>0){
							
                            include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/diary.class.php");
                            
                            $diary = new diary(AppType::JobTracker, $this->data['loggeduser']->email); 
                            foreach ($techs as $key => $value) {

                                $fromdate = mktime(0, 0, 0, trim($this->input->post('monthofyear')), 1, trim($this->input->post('year')));
                                $todate = strtotime(date("Y-m-t", $fromdate));
								 
                                while ( $fromdate  <=  $todate ) {
                                    
                                    $dte = date('Y-m-d', $fromdate);
                                    $start = isset($contractedHours[strtolower(date('D', $fromdate)) .'_from']) ? $contractedHours[strtolower(date('D', $fromdate)) .'_from'] : '07:00:00'; 
                                    $duration = 0.25; 
                                    $jobid = $labourjobid; 
                                    $origin  = "Raptor"; 
                                    $notes  = "Complete Daily Safety Sheet"; 
									$labelid= isset($contParentJobValuedata["labelid"]) ? $contParentJobValuedata["labelid"] : 0;
                                    $diary->CreateDiaryJRQDocsRequired($value['userid'], $this->data['loggeduser']->customerid, $dte,$start,$duration,$jobid,$origin,$notes, $labelid);
                                    
                                    $fromdate =  strtotime("+1 day", $fromdate) ;
                                }
                            }
                        }
                    }
                }  
				
                $message = 'Parent Job added successfully.';
              
                
                
               
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
    
	
      //contracted hours
	/**
    * This function use for show contracted hours
    * 
    * @return void 
    */
    public function contractedHours()
    {

        $contactid = $this->session->userdata('raptor_contactid');
        $this->data['EDIT_CONTRACT'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_CONTRACT');
         

          //include custom css for this page
        $this->data['cssToLoad'] = array( 
            base_url('plugins/uigrid/ui-grid-stable.min.css'),
            base_url('plugins/timepicker/bootstrap-timepicker.min.css')
        );
            
        //include custom js for this page after all require theme js 
        $this->data['jsToLoad'] = array(    
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('plugins/timepicker/bootstrap-timepicker.min.js'), 
            base_url('assets/js/contracts/contracts.contractedhours.js') 
        );

        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Contracted Hours')
                ->set_layout($this->layout)
                ->set('page_title', 'Contracted Hours')
                ->set('page_sub_title', '') 
                ->set_breadcrumb('Contracted Hours', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('contracts/contractedhours', $this->data);
    }
        
    /**
    * This function use for load data in uigrid
    * 
    * @return json 
    */
    public function loadContractedHours() {
            
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
                $order = 'asc';
                $field = 'sortorder';
                 
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }


                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                $siteData = $this->contractorclass->getContractedHoursData($size, $start, $field, $order);

                $trows = $siteData['trows'];
                $data = $siteData['data'];
                foreach ($data as $key => $value) {
                      
                    $data[$key]['sun_from'] = format_time($data[$key]['sun_from']);
                    $data[$key]['mon_from'] = format_time($data[$key]['mon_from']);
                    $data[$key]['tue_from'] = format_time($data[$key]['tue_from']);
                    $data[$key]['wed_from'] = format_time($data[$key]['wed_from']);
                    $data[$key]['thu_from'] = format_time($data[$key]['thu_from']);
                    $data[$key]['fri_from'] = format_time($data[$key]['fri_from']);
                    $data[$key]['sat_from'] = format_time($data[$key]['sat_from']);
                    
                    $data[$key]['sun_to'] = format_time($data[$key]['sun_to']);
                    $data[$key]['mon_to'] = format_time($data[$key]['mon_to']);
                    $data[$key]['tue_to'] = format_time($data[$key]['tue_to']);
                    $data[$key]['wed_to'] = format_time($data[$key]['wed_to']);
                    $data[$key]['thu_to'] = format_time($data[$key]['thu_to']);
                    $data[$key]['fri_to'] = format_time($data[$key]['fri_to']);
                    $data[$key]['sat_to'] = format_time($data[$key]['sat_to']);
                    
                    
                    $data[$key]['sun'] = $data[$key]['sun_from'].' - '.$data[$key]['sun_to'];
                    $data[$key]['mon'] = $data[$key]['mon_from'].' - '.$data[$key]['mon_to'];
                    $data[$key]['tue'] = $data[$key]['tue_from'].' - '.$data[$key]['tue_to'];
                    $data[$key]['wed'] = $data[$key]['wed_from'].' - '.$data[$key]['wed_to'];
                    $data[$key]['thu'] = $data[$key]['thu_from'].' - '.$data[$key]['thu_to'];
                    $data[$key]['fri'] = $data[$key]['fri_from'].' - '.$data[$key]['fri_to'];
                    $data[$key]['sat'] = $data[$key]['sat_from'].' - '.$data[$key]['sat_to'];
                    
                    
                }
 
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
    * This function use for ontractHours 
    * 
    * @return json 
    */
    public function getContractedHours() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $id = trim($this->input->get('id')); 
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $data = $this->contractorclass->getContractedHoursDetail($id);
                
                if(count($data) == 0){
                 $success = SuccessClass::initialize(FALSE);
                    $data = array();
                } 
                else{
                     
                    $data['sun_from'] = format_time($data['sun_from']);
                    $data['mon_from'] = format_time($data['mon_from']);
                    $data['tue_from'] = format_time($data['tue_from']);
                    $data['wed_from'] = format_time($data['wed_from']);
                    $data['thu_from'] = format_time($data['thu_from']);
                    $data['fri_from'] = format_time($data['fri_from']);
                    $data['sat_from'] = format_time($data['sat_from']);
                    
                    $data['sun_to'] = format_time($data['sun_to']);
                    $data['mon_to'] = format_time($data['mon_to']);
                    $data['tue_to'] = format_time($data['tue_to']);
                    $data['wed_to'] = format_time($data['wed_to']);
                    $data['thu_to'] = format_time($data['thu_to']);
                    $data['fri_to'] = format_time($data['fri_to']);
                    $data['sat_to'] = format_time($data['sat_to']);
                    
                }
                 
               
                $success->setData($data);
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
    public function saveContractedHours() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
 
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_CONTRACT');
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Contacted Hours.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
               
                //set form validation rule
                if (trim($this->input->post('mode')) == 'edit') {
                    $conData = $this->contractorclass->getContractedHoursDetail(trim($this->input->post('contractedhoursid')));
                    if(count($conData)>0){
                    if($this->input->post('name') != $conData['name']) {
                            $is_unique =  '|is_unique[con_contracted_hours.name]';
                         } else {
                            $is_unique =  '';
                         }
                    }

                }
                else{
                    $is_unique =  '|is_unique[con_contracted_hours.name]';
                }
                $this->form_validation->set_rules('name', "Name", 'trim|required'.$is_unique);
            
                 
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors() 
                    );
                }
                else {
                      
                    
                    //intialize array for insert
                    $ContractedHoursData = array(
                        'name'    => trim($this->input->post('name')),  
                        'sun_from' => to_mysql_date(trim($this->input->post('sun_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'mon_from' => to_mysql_date(trim($this->input->post('mon_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'tue_from' => to_mysql_date(trim($this->input->post('tue_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'wed_from' => to_mysql_date(trim($this->input->post('wed_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'thu_from' => to_mysql_date(trim($this->input->post('thu_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'fri_from' => to_mysql_date(trim($this->input->post('fri_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'sat_from' => to_mysql_date(trim($this->input->post('sat_from')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'sun_to' => to_mysql_date(trim($this->input->post('sun_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'mon_to' => to_mysql_date(trim($this->input->post('mon_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'tue_to' => to_mysql_date(trim($this->input->post('tue_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'wed_to' => to_mysql_date(trim($this->input->post('wed_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'thu_to' => to_mysql_date(trim($this->input->post('thu_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'fri_to' => to_mysql_date(trim($this->input->post('fri_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'sat_to' => to_mysql_date(trim($this->input->post('sat_to')), RAPTOR_DISPLAY_TIMEFORMAT, "H:i:s"),
                        'sortorder'      => trim($this->input->post('sortorder')),   
                        'isactive'        => (int)trim($this->input->post('isactive')) 
                    );
                     
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $contractedhoursid = $this->input->post('contractedhoursid');
                        $request = array(
                            'contractedhoursid'          => $contractedhoursid, 
                            'updateContractedHoursData'  => $ContractedHoursData,
                            'logged_contactid'   => $this->data['loggeduser']->contactid
                        );

                        $this->contractorclass->updateContractedHours($request);
                        $message = 'Contracted Hours updated successfully.';
                    }
                    else{
                         
                       //insert
                        $request = array(
                            'insertContractedHoursData' => $ContractedHoursData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );
                        $response = $this->contractorclass->insertContractedHours($request);
                        //$contechnicianid = $response['contechnicianid'];
                         $message = 'Contracted Hours Added successfully.';
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
    
    
    
    public function map()
    {		

        $customerid = $this->session->userdata('raptor_customerid');

        //$customerid = 7902;
        $this->data['css']['markerlabel'] = 'markerLabelHidden';
        $this->data['css']['infoWindow'] = 'infoWindow500x90';
        $this->data['sitefmcontacts'] = $this->customerclass->getCustomerAddressLabelSiteFM($customerid);
        $this->data['contractsitestatus'] = $this->contractorclass->getContractSiteStatus();
        $this->data['grounds_standards'] = $this->contractorclass->getContractServiceStandardByServiceId(1);
        $this->data['pest_standards'] = $this->contractorclass->getContractServiceStandardByServiceId(2);


        $selectedSite = array(0);
        $sortOrder = $this->input->get_post('sortorder') !=NULL ? $this->input->get_post('sortorder') : 3;

        $siteFMIds = $this->input->get_post('sitefm') !=NULL ? $this->input->get_post('sitefm') : array();
        $contractsitestatusIds = $this->input->get_post('contractsitestatus') !=NULL ? $this->input->get_post('contractsitestatus') : array();
        $programMonth = $this->input->get_post('programMonth') !=NULL ? $this->input->get_post('programMonth') : date('m') *2;
        $contractGroundServicesIds = $this->input->get_post('contractGroundServices') !=NULL ? $this->input->get_post('contractGroundServices') : array();
        $contractPestServicesIds = $this->input->get_post('contractPestServices') !=NULL ? $this->input->get_post('contractPestServices') : array();
        $addressLabelAttributeIds = array();
        $labelIds = array();
        $this->data['rows'] = $this->contractorclass->getContractsMapData(1, $selectedSite , $sortOrder, $customerid, $programMonth, $siteFMIds, $contractsitestatusIds, $contractGroundServicesIds, $contractPestServicesIds, $addressLabelAttributeIds, $labelIds);

        $this->data['mapid']= 0;
        $this->data['overlays']= array('site_list_marker');

        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css') 
         );


        $this->data['headerJsToLoad'] = array( 
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig&v=3.exp&libraries=geometry,visualization,drawing',
            base_url('plugins/gmaps/markerwithlabel.js'),
            base_url('plugins/gmaps/gmap_utilities.js'),
            base_url('plugins/gmaps/oms.min.js') 
        );
         $this->data['jsToLoad'] = array(  
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('assets/js/contracts/contracts.map.js') 
        );

        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Contracts')
                ->set_layout($this->layout)
                ->set('page_title', 'My Contracts')
                ->set('page_sub_title', 'Map')
                ->set_breadcrumb('My Contracts', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('contracts/map', $this->data);

    }
      
    //scheduled work
	/**
    * This function use for show My Service schedule
    * 
    * @return void 
    */
    public function scheduled()
    {

        $customerid = $this->session->userdata('raptor_customerid');

        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['contracts'] = $this->contractorclass->getCustomerContracts($customerid);
        $this->data['sites'] = $this->customerclass->getCustomerSites($customerid);
        $this->data['servicetypes'] = $this->contractorclass->getServiceTypes($customerid);
        $this->data['servicestatus'] = $this->contractorclass->getServiceStatus();

        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
         );


        $this->data['jsToLoad'] = array( 
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/fullcalendar_n/lib/moment.min.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'), 
            base_url('assets/js/contracts/contracts.scheduled.js') 
        );

          $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Scheduled Services')
                ->set_layout($this->layout)
                ->set('page_title', 'Scheduled Services')
                ->set('page_sub_title', '')
                ->set_breadcrumb('My Serviceschedule', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('contracts/scheduled', $this->data);
    }
        
    /**
    * This function use for load data in uigrid
    * 
    * @return json 
    */
    public function loadScheduledServices() {
            
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
                $order = '';
                $field = '';
                $contractid = '';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }

                $show = trim($this->input->get('show'));
                $fromdate = trim($this->input->get('fromdate'));
                $todate = trim($this->input->get('todate'));
                
                $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT);
                $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
                $year = format_date($fromdate, 'Y');
                
                if(format_date($fromdate, 'm') == format_date($todate, 'm')) {
                    $month = array(format_date($fromdate, 'm'));
                } else {
                    $month = array(format_date($fromdate, 'm'), format_date($todate, 'm'));
                }

                if ($this->input->get('state') != NULL) {
                    $params['a.sitestate'] = $this->input->get('state');
                }

                if ($this->input->get('contract') != NULL) {
                    $params['s.contractid'] = $this->input->get('contract');
                    $contractid = $params['s.contractid'];
                }

                if ($this->input->get('site') != NULL) {
                    $params['s.labelid'] = $this->input->get('site');
                }

                if ($this->input->get('servicetype') != NULL) {
                    $params['t.id'] = $this->input->get('servicetype');
                }

                if ($this->input->get('jobstatus') != NULL) {
                    $params['ss.id'] = $this->input->get('jobstatus');
                }

                //$params = array();

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                $customerid = $this->session->userdata('raptor_customerid');
                
                //create dynamic columns for uigrid
                $columns = $this->createScheduledServicesColumns($month, $year, $show);

                $params['a.customerid'] = $customerid;
                $siteData = $this->contractorclass->getSites($size, $start, $field, $order, $show, $params);

                $trows = $siteData['trows'];
                $data = $siteData['data'];
                // getSiteData query with filter fromdate and filter todate and contractid, labelid in $siteData array record

                $labelids = array();
                foreach ($data as $key => $siterow) {
                    $labelids[] = $siterow['labelid'];
                } 

                if(count($labelids) > 0) {
                    $siteData = $this->contractorclass->getSiteData($contractid, $fromdate, $todate, $labelids);
                
                    //format data for uigrid
                    $data = $this->formatScheduledServicesData($columns, $data, $siteData, $year, $show);
                }
                

                $data = array(
                    'data' => $data,
                    'columns' => $columns
                );

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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportScheduled() {
        
        $params = array();

        $show = trim($this->input->get('show'));
        $fromdate = trim($this->input->get('fromdate'));
        $todate = trim($this->input->get('todate'));
        $contractid = 0;

        $fromdate = to_mysql_date($fromdate, RAPTOR_DISPLAY_DATEFORMAT);
        $todate = to_mysql_date($todate, RAPTOR_DISPLAY_DATEFORMAT);
        $year = format_date($fromdate, 'Y');

        if(format_date($fromdate, 'm') == format_date($todate, 'm')) {
            $month = array(format_date($fromdate, 'm'));
        } else {
            $month = array(format_date($fromdate, 'm'), format_date($todate, 'm'));
        }

        if ($this->input->get('state') != NULL) {
            $params['a.sitestate'] = $this->input->get('state');
        }

        if ($this->input->get('contract') != NULL) {
            $params['s.contractid'] = $this->input->get('contract');
            $contractid = $params['s.contractid'];
        }

        if ($this->input->get('site') != NULL) {
            $params['s.labelid'] = $this->input->get('site');
        }

        if ($this->input->get('servicetype') != NULL) {
            $params['t.id'] = $this->input->get('servicetype');
        }

        if ($this->input->get('jobstatus') != NULL) {
            $params['ss.id'] = $this->input->get('jobstatus');
        }
		$customerid = $this->session->userdata('raptor_customerid');
        $params['a.customerid'] = $customerid;

        $order = NULL;
        $field = NULL;
        $size = NULL;
        $start = 0;
        
        //create dynamic columns for uigrid
        $columns = $this->createScheduledServicesColumns($month, $year, $show);

        $siteData = $this->contractorclass->getSites($size, $start, $field, $order, $show, $params);

        $trows = $siteData['trows'];
        $data = $siteData['data'];
        // getSiteData query with filter fromdate and filter todate and contractid, labelid in $siteData array record

        $labelids = array();
        foreach ($data as $key => $siterow) {
            $labelids[] = $siterow['labelid'];
        } 

        if(count($labelids) > 0) {
            $siteData = $this->contractorclass->getSiteData($contractid, $fromdate, $todate, $labelids);

            //format data for uigrid
            $data = $this->formatScheduledServicesData($columns, $data, $siteData, $year, $show);
        }

        //echo '<pre>';
        //print_r($columns);
        ///print_r($data);
        //exit;
        /*$data = array(
            'data' => $data,
            'columns' => $columns
        );*/
  

        $data_array = array();

        $this->load->library('excel');
        
        $wd = 'Week';
        if($show === 'week') {
            $wd = 'Week';
        } else if($show === 'day') {
            $wd = 'Day';
        } else {
            $wd = 'Month';
        }
        
         //set excel configurations
        $heading = array('', $wd);
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->getProperties()->setCreator("DCFM")
			     ->setLastModifiedBy("DCFM")
			     ->setTitle("Scheduled Services")
			     ->setSubject("DCFM Client Portal :: Scheduled Services")
		   	     ->setDescription("DCFM Client Portal :: Scheduled Services")
			      ->setKeywords('Scheduled Services')
		      	     ->setCategory('Scheduled Services');
        $this->excel->getActiveSheet()->setTitle('Scheduled Services');
        
        //Loop Heading
        $rowNo = 1;
        $colH1 = 'C';
        $this->excel->getActiveSheet()->setCellValue('A1', 'Ref');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Site');
        $colH = 'C';
        
        foreach ($columns as $key => $value) {  
            $t = 0;
            
            if((bool)$value['m']) {
                $t++;
                $heading[] = '';
                $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(18); 
                $colH++;
            }
            
            if((bool)$value['d']) {
                foreach($value['days'] as $w) {
                     $t++;
                    $heading[] = $w;
                    $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(18); 
                    $colH++;
                }
            }
            if((bool)$value['w']) {
                foreach($value['weeks'] as $w) {
                     $t++;
                    $heading[] = $w['day'];
                    $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(18); 
                    $colH++;
                }
            }
            
            if($t > 0) {
                
                $this->excel->getActiveSheet()->setCellValue($colH1.$rowNo, $value['month']);
                
                $colH2 = $colH1;
                for($i = 1; $i< $t; $i++){
                    $colH2++;
                }
                
                $this->excel->getActiveSheet()->mergeCells($colH1.$rowNo.":".$colH2.$rowNo);
                
                $colH1 = $colH;
            }
           
        }
       
        $rowNo++;
        $colH = 'A';
        foreach($heading as $h){
            $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $h);
            $colH++;    
        }
        
        //Loop Result
        $rowNo++;
        foreach($data as $row1) {
            
            $this->excel->getActiveSheet()->setCellValue('A'.$rowNo, $row1['siteref']);
            $this->excel->getActiveSheet()->setCellValue('B'.$rowNo, $row1[$show.'_sitesuburb']);
            
            $colH = 'C';
            foreach ($columns as $key2 => $value) {  

                if((bool)$value['m']) {
                    $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1['ex_mon_' . ($key2+1)]);
                    $colH++;
                }
                
                if((bool)$value['d']) {
                    foreach($value['days'] as $w) {
                        $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1['ex_d_' . $value['monthNumeric'] . '_' . $w]);
                        $colH++;
                    }
                }
                
                if((bool)$value['w']) {
                    foreach($value['weeks'] as $w) {
                        $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1['ex_d_' . $value['monthNumeric'] . '_' . $w['day']]);
                        $colH++;
                    }
                }
                
            } 
            $rowNo++;
        }

        $colH1--;
        
        //Freeze pane
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.'1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'CCCCCC')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            )
        );
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.'2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->freezePane('A3');
        //Cell Style
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

       
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.($trows+2))->applyFromArray($styleArray);
        //Save as an Excel BIFF (xls) file
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel,'Excel5');
        
        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="scheduled_services.xls";
 
        $objWriter->save($dir.'/'.$file_name);

        $this->load->helper('download');
        $filedata = file_get_contents(base_url()."temp/".$file_name);
        force_download('scheduled_services.xls', $filedata);
    }
    
    /**
    * This function create dynamic columns for uigrid
    * @param array $month - array of month numbers
    * @param integer $year - year
    * @param string show - day/month/week data
    * 
    * @return array 
    */
    private function createScheduledServicesColumns($month, $year, $show) {

        $columns = array();
        //$year = 2016;

        //$show = 'week';
        //$show = 'month';
        for($i = 1; $i <= 12; $i++) {
            $weekNumbersArr = getWeeksOfMonth($i, $year);
            $weeks = array();
            foreach($weekNumbersArr as $w) {
                $weekStartEndDateArr = getStartAndEndDate($year, $w);
                $weeks[] = array(
                    'weekDate' => $weekStartEndDateArr[0],
                    'day' => ordinal(date_format(date_create($weekStartEndDateArr[0]), 'j')),
                    'weekS' => $weekStartEndDateArr[0],
                    'weekE' => $weekStartEndDateArr[1],
                    'weekNumber' => date("W", strtotime($weekStartEndDateArr[0]))
                );
            }

            if($show == 'week') {
                $columns[] = array(
                    'month' => date('F', mktime(0, 0, 0, $i, 10)),
                    'monthNumeric' => $i,
                    'weeks' => $weeks,
                    'm' => 0,
                    'w' => 1,
                    'd' => 0
                );
            } else if($show == 'month') {
                $columns[] = array(
                    'month' => date('F', mktime(0, 0, 0, $i, 10)),
                    'monthNumeric' => $i,
                    'start' => date('Y-m-01', mktime(0, 0, 0, $i, 10)),
                    'end' => date('Y-m-t', mktime(0, 0, 0, $i, 10)),
                    'm' => 1,
                    'w' => 0,
                    'd' => 0
                );
            }
        }

        //$show = 'day';
        //day condition
        //$month = array(4, 5);
        if($show == 'day') {
            foreach($month as $m) {
                $startDay = 1;
                $endDay = date('t', mktime(0, 0, 0, $m, 1, $year));
                $dayArr = range($startDay, $endDay);
                $columns[] = array(
                    'month' => date('F', mktime(0, 0, 0, $m, 10, $year)),
                    'monthNumeric' => $m,
                    'days' => array_map("ordinal", $dayArr),
                    'm' => 0,
                    'w' => 0,
                    'd' => 1
                );
            }
        }

        return $columns;
    }
    
    /**
    * This function format data for uigrid
    * @param array $columns - uigrid dynamic columns
    * @param array $data - data
    * @param array $siteData - site data based on $data
    * @param integer $year - year
    * @param string show - day/month/week data
    * 
    * @return array 
    */
    private function formatScheduledServicesData($columns, $data, $siteData, $year, $show) {
        
        //format data for uigrid
        foreach ($data as $key => $siterow) {
            $labelid = $siterow['labelid'];

            if($show == 'week') {
                foreach($columns as $k=>$c) {
                    foreach($c['weeks'] as $w) {
                        $coldate = strtotime($w['weekS']);
                        $colenddate = strtotime($w['weekE']);
                        $showIcon = '';
                        $statusName = array();

                        $statusid = '';
                        $iconArray = array();
                        $i = -1;
                        foreach($siteData as $service) {

                            if($labelid == $service['labelid']) {
                                $startTime = strtotime($service['startdate']);
                                $endTime = strtotime($service['enddate']);

                                $wno = date('W', $startTime);
                                $wDateArr = getStartAndEndDate($year, $wno);
                                $startTime = strtotime($wDateArr[0]);

                                $tWeeks = array();

                                while ($startTime < $endTime) {  
                                    $tWeeks[] = date('W', $startTime); 
                                    $startTime += strtotime('+1 week', 0);
                                }

                                foreach($tWeeks as $tw) {
                                    if($tw == $w['weekNumber']) {
                                        $icon = $service['icon'];
                                        $thisicon = "<i class='fa $icon'></i>";

                                        if($statusid != $service['statusid']) {
                                            $statusid = $service['statusid'];
                                            $i++;
                                            $iconArray[$i][] = $service;
                                        } else {
                                            $iconArray[$i][] = $service;
                                        }
                                    }
                                }
                            }
                        }

                        foreach($iconArray as $iconValue) {
                            $itemcount = 0;
                            foreach($iconValue as $ic=>$v) {
                                if($ic > 0) {
                                    break;
                                }
                                $itemcount = count($iconValue);
                                $thisicon = "<i class='fa ".$v['icon']."'></i>";
                                $statusName[] = $v['statusname'].'- '.$itemcount;
                                //$showIcon = $showIcon.'<button type="button" class="btn btn-default btn-flat btn-sm" style ="background-color:'.$v['color'].';color:'.$v['textcolor'].';" title = "'.$v['name'].'">'.$itemcount.'&nbsp;'.$thisicon;
                                $showIcon = $showIcon.'<button type="button" class="btn btn-default btn-flat btn-sm" style ="background-color:'.$v['color'].';color:'.$v['textcolor'].';" title = "'.$v['name'].'">'.$thisicon;
                            }
                        }

                        $data[$key]['d_' . $c['monthNumeric'] . '_' . $w['day']] = $showIcon;
                        //for excel
                        $data[$key]['ex_d_' . $c['monthNumeric'] . '_' . $w['day']] = implode(PHP_EOL, $statusName);
                    }
                }
            }

            if($show == 'month') {
                foreach($columns as $k=>$c) {
                    $statusName = array();

                    $coldate = strtotime($c['start']);
                    $colenddate = strtotime($c['end']);
                    $showIcon = '';

                    $statusid = '';
                    $iconArray = array();
                    $i = -1;
                    foreach($siteData as $service) {
                        if($labelid == $service['labelid']) {

                            $startTime = $coldate;
                            $tWeeks = array();
                            $monthArray = getMonthsInRange($service['startdate'], $service['enddate']);
                            foreach($monthArray as $mth) {  
                                $tWeeks[] = $mth['month']; 
                            }

                            foreach($tWeeks as $tw) {
                                if($tw == $c['monthNumeric']) {
                                    $icon = $service['icon'];
                                    $thisicon = "<i class='fa $icon'></i>";

                                    if($statusid != $tw) {
                                        $statusid = $tw;
                                        $i++;
                                        $iconArray[$i][] = $service;
                                    } else {
                                        $iconArray[$i][] = $service;
                                    }
                                }
                            }
                        }
                    }

                    foreach($iconArray as $iconValue) {
                        $itemcount = 0;
                        foreach($iconValue as $ic=>$v) {
                            if($ic > 0) {
                                break;
                            }
                            $itemcount = count($iconValue);
                            $thisicon = "<i class='fa ".$v['icon']."'></i>";
                            $statusName[] = $v['statusname'].'- '.$itemcount;
                            //$showIcon = $showIcon.'<button type="button" class="btn btn-default btn-flat btn-sm" style ="background-color:'.$v['color'].';color:'.$v['textcolor'].';" title = "'.$v['name'].'">'.$itemcount.'&nbsp;'.$thisicon;
                            $showIcon = $showIcon.'<button type="button" class="btn btn-default btn-flat btn-sm" style ="background-color:'.$v['color'].';color:'.$v['textcolor'].';" title = "'.$v['name'].'">'.$thisicon;
                        }
                    }

                    $data[$key]['mon_' . ($k+1)] = $showIcon;
                    //for excel
                    $data[$key]['ex_mon_' . ($k+1)] = implode(PHP_EOL, $statusName);
                }
            }

            if($show == 'day') {
                foreach($columns as $k=>$c) {
                    foreach($c['days'] as $w) {
                        $statusName = array();

                        $showIcon = '';

                        $statusid = '';
                        $iconArray = array();
                        $i = -1;
                        $cDay = $year.'-'.$c['monthNumeric'].'-'.(int)$w;

                        foreach($siteData as $service) {
                            if($labelid == $service['labelid']) {

                                $tWeeks = getDatesFromRange($service['startdate'], $service['enddate'], $step = '+1 day', $output_format = 'Y-m-d');

                                foreach($tWeeks as $tw) {

                                    if(strtotime($tw) == strtotime($cDay)) {


                                        $icon = $service['icon'];
                                        $thisicon = "<i class='fa $icon'></i>";

                                        if($statusid != strtotime($tw)) {

                                            $statusid = strtotime($tw);
                                            $i++;
                                            $iconArray[$i][] = $service;
                                        } else {
                                            $labelids = $tw;
                                            $iconArray[$i][] = $service;
                                        }
                                    }
                                }
                            }
                        }

                        foreach($iconArray as $iconValue) {
                            $itemcount = 0;
                            foreach($iconValue as $ic=>$v) {
                                if($ic > 0) {
                                    break;
                                }
                                $itemcount = count($iconValue);
                                $thisicon = "<i class='fa ".$v['icon']."'></i>";
                                $statusName[] = $v['statusname'].'- '.$itemcount;
                                //$showIcon = $showIcon.'<button type="button" class="btn btn-default btn-flat btn-sm" style ="background-color:'.$v['color'].';color:'.$v['textcolor'].';" title = "'.$v['name'].'">'.$itemcount.'&nbsp;'.$thisicon;
                                $showIcon = $showIcon.'<button type="button" class="btn btn-default btn-flat btn-sm" style ="background-color:'.$v['color'].';color:'.$v['textcolor'].';" title = "'.$v['name'].'">'.$thisicon;
                            }
                        }

                        $data[$key]['d_' . $c['monthNumeric'] . '_' . $w] = $showIcon;
                        //for excel
                        $data[$key]['ex_d_' . $c['monthNumeric'] . '_' . $w] = implode(PHP_EOL, $statusName);
                    }
                }
            }
        }
        
        return $data;
    }
        
      
}