<?php 
/**
 * Customers Controller Class
 *
 * This is a Customers controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customers Controller Class
 *
 * This is a Customers controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Customers
 * @filesource          customers.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Customers extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();  
         //  Load libraries 
        $this->load->library('budget/BudgetClass');
        $this->load->library('asset/AssetClass');
        
    }

    public function index()
    {
        redirect('dashboard');
    }
    
    
    /**
     * show addresses
     * 
     * @return void
     */
    public function contacts() {
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css')
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/customers/customers.contacts.js')
        );
        
        $customerid =$this->session->userdata('raptor_customerid');
        $contactid = $this->session->userdata('raptor_contactid');
        
        $this->data['states'] =$this->sharedclass->getStates(1);
        $this->data['contact_roles'] = $this->customerclass->getContactRoles($customerid);
        $this->data['customer'] = $this->customerclass->getCustomerById($customerid);
        $this->data['reportstocontacts'] = $this->customerclass->getOrganisationContacts($customerid);
        
        $this->data['add_contact'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_CONTACT');
        $this->data['export_contact'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_CONTACT');
        $this->data['import_contact'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'IMPORT_CONTACT');
        $this->data['invite_contact'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'INVITE_CONTACT');
        $this->data['edit_contact'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_CONTACT');

        
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Contacts')
                ->set_layout($this->layout)
                ->set('page_title', 'Contacts')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('customers/contacts', $this->data);
    }
    
    /**
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadContacts() {
        
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
                $field = 'c.firstname';
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
                if (trim($this->input->get('status')) != '') {
                    $params['c.active'] = $this->input->get('status');
                }
                if (trim($this->input->get('state')) != '') {
                    $params['c.state'] = $this->input->get('state');
                }
                
                if (trim($this->input->get('bossid')) != '') {
                    $params['c.bossid'] = $this->input->get('bossid');
                }

                if (is_array($this->input->get('role'))) {
                    $role = $this->input->get('role');
                    foreach ($role as $key => $value) {
                       $rolea[] = $value; 
                    }
                   $params['c.role'] = $rolea;
                }
                else {
                    $params['c.role'] = $this->input->get('role');
                }

                 $customerid =$this->session->userdata('raptor_customerid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data
                $contactData = $this->customerclass->getContacts($customerid, $size, $start, $field, $order, $filter, $params);
                 
                $trows  = $contactData['trows'];
                $data = $contactData['data'];
            
                //format data
//                foreach($data as $key=>$value) {
//                     
//                }
 
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
    public function exportContacts() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTACT');
        if(!$export_excel) {
            show_404();
        }
        
        $order = 'desc';
        $field = 'created_on';
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('state')) != '') {
            $params['c.state'] = $this->input->get('state');
        }
        if (trim($this->input->get('status')) != '') {
            $params['c.active'] = $this->input->get('status');
        }
        if (is_array($this->input->get('role'))) {
            $role = $this->input->get('role');
            foreach ($role as $key => $value) {
               $rolea[] = $value; 
            }
           $params['c.role'] = $rolea;
        }
        else {
            $params['c.role'] = $this->input->get('role');
        }
        $customerid = $this->session->userdata('raptor_customerid');

        //get contacts data
        $contactData = $this->customerclass->getContacts($customerid, NULL, 0, $field, $order, $filter, $params);
 
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('Contact Id', 'Name', 'Position', 'Role', 'Phone', 'Mobile', 'Email', 'Territory', 'State', 'Reports To');
        $this->load->library('excel');

        $name = 'contacts';
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = $row['contactid'];
            $result[] = $row['contactname'];
            $result[] = $row['position'];
            $result[] = $row['role'];
            $result[] = $row['phone'];
            $result[] = $row['mobile'];
            $result[] = $row['email'];
            $result[] = $row['territory'];
            $result[] = $row['state'];
            $result[] = $row['reportsto'];

            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "contacts.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(32);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Contacts", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($name.'_contacts.xls', $data);
    }
    
    
    /**
    * This function use for add new contact
    * @return void 
    */
    public function addContact() {
        
        //check permission
        $add_contact = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_CONTACT');
        if(!$add_contact) {
            show_404();
        }
        
        //set form validation rule
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');
        $this->form_validation->set_rules('email', "Email", 'required|valid_email|is_unique[contact.email]');

        //validate form
        if ($this->form_validation->run() == FALSE)
        {
            //include required css for this view
            $this->data['cssToLoad'] = array(
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css')
            );

            //include required js for this view
            $this->data['jsToLoad'] = array(
                base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'), 
                base_url('plugins/input-mask/jquery.inputmask.js'),
                base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('assets/js/customers/customers.addcontact.js') 

            );
            
            
            //intialize variables
            //$data['trades'] = $this->sharedclass->getTrades();
            //$data['accesslevel'] = $this->customerclass->getAccessLevel();
            
            $customerid =$this->session->userdata('raptor_customerid');
            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['customer'] = $this->customerclass->getCustomerById($customerid);
            $this->data['reportstocontacts'] = $this->customerclass->getOrganisationContacts($customerid);
            $this->data['role'] = $this->customerclass->getContactRole();
            $this->data['mailgroups'] = $this->customerclass->mailGroups($customerid);
            $this->data['default_country'] = 'AUSTRALIA';

            //generate view
           $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Add Contact')
                ->set_layout($this->layout)
                ->set('page_title', 'Contacts')
                ->set('page_sub_title', $this->data['customer']['companyname'])
                ->set_breadcrumb('Contacts', site_url('customers/contacts'))
                ->set_breadcrumb('Add Contact', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('customers/addcontact', $this->data);
        }
        else {
            
            $active = 0;
            if($this->input->post('active')) {
                $active = 1;
            }
            
            $customerid =$this->session->userdata('raptor_customerid');

            //intialize array for insert
            $additionalData = array(
                'firstname'     => trim($this->input->post('firstname')), 
                'email'         => trim($this->input->post('email')), 
                'street1'       => trim($this->input->post('street1')), 
                'street2'       => trim($this->input->post('street2')), 
                'suburb'        => trim($this->input->post('suburb')), 
                'state'         => trim($this->input->post('state')), 
                'postcode'      => trim($this->input->post('postcode')), 
                'position'      => trim($this->input->post('position')), 
                'mobile'        => trim($this->input->post('mobile')), 
                'phone'         => trim($this->input->post('phone')), 
                'territory'     => trim($this->input->post('territory')), 
                'bossid'        => trim($this->input->post('bossid')), 
                'role'          => trim($this->input->post('role')), 
                'customerid'    => $customerid, 
                'created_on'    => date('Y-m-d H:i:s', time()), 
                'origin'        => 'Raptor', 
                'dateadded'     => date('Y-m-d H:i:s', time()),
                'active'        => $active
            );

            $uploadMessage = '';
            if(isset($_FILES['profilepic'])){
                //image formats 
                $allowed = array('png', 'jpg', 'jpeg', 'gif');

                //get filename
                $filename = $_FILES['profilepic']['name'];
                $docformat = pathinfo($filename, PATHINFO_EXTENSION);

                if ($filename != '') {

                    //get file location from config
                    $filelocation= $this->config->item('userphotos_dir');

                    // set permisions to upload folder
                    if (!is_dir($filelocation)) {
                        mkdir($filelocation, 0755, TRUE);
                    }

                    if (in_array($docformat, $allowed)) {

                        //set image config
                        $filename = time();
                        $config['upload_path'] =  $filelocation;
                        $config['allowed_types'] = implode('|', $allowed);
                        $config['file_name'] = $filename;
                        $config['overwrite'] = TRUE;

                        //load image library
                        $this->load->library('upload', $config);

                        // upload image
                        if (!$this->upload->do_upload("profilepic"))
                        {
                            $uploadMessage = $this->upload->display_errors();
                        }
                        else {
                            $additionalData['photodocid'] = $filename.'.'.$docformat;
                        }
                    }
                }
            }
            
            $mailGroupData = array();
            //check mailgroup and insert record
            $mailgroup = $this->input->post('mailgroup');

            if (!is_array($mailgroup) && trim($mailgroup) != '') {
                $mailgroup = explode(",", $mailgroup);
            }

            if (is_array($mailgroup)) {
                foreach ($mailgroup as $value) {
                    $mailGroupData[] = array(
                        'mailgroupdesc' => $value, 
                        'customerid'    => $customerid
                    );
                }
            }

            //insert
            $request = array(
                'insertContactData' => $additionalData, 
                'mailGroupData'     => $mailGroupData,
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            $response = $this->customerclass->insertContact($request);

            $this->session->set_flashdata('success', 'Contact Added successfully.');
            
            //redirect to contacts
            redirect('customers/contacts');
        }
    }
    
    /**
    * This function use for edit contact 
    * @param integer $id - id for selected contact which one edit
    * @return void 
    */
    public function editContact($id) {
        
        //check permission
        $edit_contact = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_CONTACT');
        if(!$edit_contact) {
            show_404();
        }
        
        //get data for selected contact
        $this->data['contact'] = $this->customerclass->getContactById($id);
        $customerid =$this->session->userdata('raptor_customerid'); 
 	 
        if(count($this->data['contact'])==0){
            show_404();
        }
        if($this->data['contact']['customerid']!=$customerid){
            show_404();
        }
        
        //set form validation rule
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');
        
        //check form validation
        if ($this->form_validation->run() == FALSE)
        {
             //include required css for this view
            $this->data['cssToLoad'] = array(
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css')
            );

            //include required js for this view
            $this->data['jsToLoad'] = array(
                base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'), 
                base_url('plugins/input-mask/jquery.inputmask.js'),
                base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('assets/js/customers/customers.addcontact.js') 

            );

           //intialize variables
         
            $customerid =$this->session->userdata('raptor_customerid');
            
            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['customer'] = $this->customerclass->getCustomerById($customerid);
            $this->data['reportstocontacts'] = $this->customerclass->getOrganisationContacts($customerid);
            $this->data['role'] = $this->customerclass->getContactRole();
            $this->data['mailgroups'] = $this->customerclass->mailGroups($customerid);
            $this->data['default_country'] = 'AUSTRALIA';
           
           //generate view
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Contacts - Edit Contact')
                ->set_layout($this->layout)
                ->set('page_title', 'Contacts')
                ->set('page_sub_title', $this->data['contact']['firstname'])
                ->set_breadcrumb('Contacts', site_url('customers/contacts'))
                ->set_breadcrumb('Edit Contact', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('customers/editcontact', $this->data);
        } else {

            $photodocid = $this->data['contact']['photodocid'];
            $customerid =$this->session->userdata('raptor_customerid');
            
            $active = 0;
            if($this->input->post('active')) {
                $active = 1;
            }
            
            //intialize array for update
            $additionalData = array(
                'firstname'     => trim($this->input->post('firstname')), 
                'street1'       => trim($this->input->post('street1')), 
                'street2'       => trim($this->input->post('street2')), 
                'suburb'        => trim($this->input->post('suburb')), 
                'state'         => trim($this->input->post('state')), 
                'postcode'      => trim($this->input->post('postcode')), 
                'position'      => trim($this->input->post('position')), 
                'mobile'        => trim($this->input->post('mobile')), 
                'phone'         => trim($this->input->post('phone')), 
                'territory'     => trim($this->input->post('territory')), 
                'bossid'        => trim($this->input->post('bossid')), 
                'role'          => trim($this->input->post('role')),
                'editdate'      => date('Y-m-d H:i:s', time()),
                'active'        => $active
            );

            $allowed = array('png', 'jpg', 'jpeg', 'gif');
            $filename = $_FILES['profilepic']['name'];
            $docformat = pathinfo($filename, PATHINFO_EXTENSION);

            if ($filename != '') {
                $filelocation= $this->config->item('userphotos_dir');
                if (!is_dir($filelocation)) {
                    mkdir($filelocation, 0755, TRUE);
                }
                if (in_array($docformat, $allowed) ) {

                    $filename = time();
                    $config['upload_path'] =  $filelocation;
                    $config['allowed_types'] = implode('|', $allowed);
                    $config['file_name'] = $filename;
                    $config['overwrite'] = TRUE;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload("profilepic")) {
                        $this->session->set_flashdata('error', $this->upload->display_errors()); 
                    }
                    else { 
                        $additionalData['photodocid'] = $filename.'.'.$docformat;
                        if (trim($photodocid) != '') {
                            @unlink($filelocation.''.$photodocid);
                        }
                    }
                }
            }
            
            $mailGroupData = array();
            //check mailgroup and insert record
            $mailgroup = $this->input->post('mailgroup');

            if (!is_array($mailgroup) && trim($mailgroup) != '') {
                $mailgroup = explode(",", $mailgroup);
            }

            if (is_array($mailgroup)) {
                foreach ($mailgroup as $value) {
                    $mailGroupData[] = array(
                        'mailgroupdesc' => $value, 
                        'customerid'    => $customerid
                    );
                }
            }
            
            $request = array(
                'updateContactData' => $additionalData, 
                'mailGroupData'     => $mailGroupData,
                'contactid'         => $id,  
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            
            $this->customerclass->updateContact($request);
   
            $this->session->set_flashdata('success', 'Contact updated successfully.');
            redirect('customers/contacts');
        }
    }
    
    
    /**
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateContact() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_CONTACT');
            
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
                    'updateContactData' => $updateData, 
                    'contactid'         => $contactid,  
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );

                $this->customerclass->updateContact($request);
                
               
                    
                
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
    * This function use for add new contact for customer
    * 
    * @return json 
    */
    public function saveContact() {

        
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
               
                //set form validation rule
                $this->form_validation->set_rules('firstname', 'First Name', 'required');
                $this->form_validation->set_rules('role', 'Role', 'required');
                $this->form_validation->set_rules('email', "Email", 'required|valid_email|is_unique[contact.email]');

                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors(),
                        'email'   => form_error('email')
                    );
                }
                else {

                    //check checkbox TRUE/FALSE
                    if ($this->input->post('primarycontact')) {
                        $isprimary = 1;
                    } else {
                        $isprimary = 0;
                    }

                    if ($this->input->post('orgchart')) {
                        $orgchart = 1;
                    } else {
                        $orgchart = 0;
                    }

                    //get customerid
                    $customerid =$this->session->userdata('raptor_customerid');

                    //intialize array for insert
                    $additionalData = array(
                        'firstname'     => trim($this->input->post('firstname')), 
                        'surname'       => trim($this->input->post('surname')), 
                        'email'         => trim($this->input->post('email')), 
                        'street1'       => trim($this->input->post('street1')), 
                        'street2'       => trim($this->input->post('street2')), 
                        'suburb'        => trim($this->input->post('suburb')), 
                        'state'         => trim($this->input->post('state')), 
                        'postcode'      => trim($this->input->post('postcode')), 
                        'position'      => trim($this->input->post('position')), 
                        'mobile'        => trim($this->input->post('mobile')), 
                        'phone'         => trim($this->input->post('phone')), 
                        'territory'     => trim($this->input->post('territory')), 
                        'bossid'        => trim($this->input->post('bossid')), 
                        'role'          => trim($this->input->post('role')), 
                        'customerid'    => $customerid, 
                        'created_on'    => date('Y-m-d H:i:s', time()), 
                        'origin'        => 'tiger', 
                        'dateadded'     => date('Y-m-d H:i:s', time())
                    );
                    
                    $uploadMessage = '';
                    if(isset($_FILES['profilepic'])){
                        //image formats 
                        $allowed = array('png', 'jpg', 'jpeg', 'gif');

                        //get filename
                        $filename = $_FILES['profilepic']['name'];
                        $docformat = pathinfo($filename, PATHINFO_EXTENSION);

                        if ($filename != '') {

                            //get file location from config
                            $filelocation= $this->config->item('userphotos_dir');

                            // set permisions to upload folder
                            if (!is_dir($filelocation)) {
                                mkdir($filelocation, 0755, TRUE);
                            }

                            if (in_array($docformat, $allowed)) {

                                //set image config
                                $filename = time();
                                $config['upload_path'] =  $filelocation;
                                $config['allowed_types'] = implode('|', $allowed);
                                $config['file_name'] = $filename;
                                $config['overwrite'] = TRUE;

                                //load image library
                                $this->load->library('upload', $config);

                                // upload image
                                if (!$this->upload->do_upload("profilepic"))
                                {
                                    $uploadMessage = $this->upload->display_errors();
                                }
                                else {
                                    $additionalData['photodocid'] = $filename.'.'.$docformat;
                                }
                            }
                        }
                    }
                    $mailGroupData = array();
                    //check mailgroup and insert record
                    $mailgroup = $this->input->post('mailgroup');
                    
                    if (!is_array($mailgroup) && trim($mailgroup) != '') {
                        $mailgroup = explode(",", $mailgroup);
                    }
                    
                    if (is_array($mailgroup)) {
                        foreach ($mailgroup as $value) {
                            
                            $mailGroupData[] = array(
                                'mailgroupdesc' => $value, 
                                'customerid'    => $customerid
                            );
                           
                        }
                    }
                    
                     
                    //insert
                    $request = array(
                        'insertContactData' => $additionalData, 
                        'mailGroupData'     => $mailGroupData,
                        'logged_contactid'  => $this->session->userdata('raptor_contactid')
                    );
                    $response = $this->customerclass->insertContact($request);
                    
                    if(isset($response['contactid'])){
                        $this->ion_auth->update_password($response['contactid'], 'dcfm');
                    }
                    //check isprimary  and update
                    if ($isprimary == 1) {
                        $this->customerclass->updatePrimaryContact($customerid, $response['contactid']);
                    }
                    
                    if(trim($this->input->post('fromaddress')) == 'yes' && trim($this->input->post('labelid'))!=''){
                        $updateData = array(
                            'sitecontactid' => $response['contactid'],
                            'sitecontact'=> trim($this->input->post('firstname')) 
                        );
                        
                        $request = array(
                            'updateData'       => $updateData,
                            'labelid'          => trim($this->input->post('labelid')),  
                            'logged_contactid' => $this->session->userdata('raptor_contactid')
                        );
                        $this->customerclass->updateAddress($request);
                    }
                    
                    $message = 'Contact Added successfully.';
                    $data = array (
                        'success'       => TRUE,
                        'data'          => $this->customerclass->getContactById($response['contactid']),
                        'uploadmessage' => $uploadMessage
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
     * show addresses
     * 
     * @return void
     */
    public function addresses() {
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
         );


        $this->data['jsToLoad'] = array(
            //'https://maps.googleapis.com/maps/api/js?v=3&sensor=false&libraries=geometry',
            //https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/customers/customers.addresses.js')
            
        );
        
        $customerid =$this->session->userdata('raptor_customerid');
        $contactid = $this->session->userdata('raptor_contactid');
        
        $this->data['states'] =$this->sharedclass->getStates(1);
        $this->data['sitefmcontacts'] =$this->customerclass->getCustomerSiteFM($customerid);
        $this->data['customerData'] = $this->customerclass->getCustomerById($customerid);
        
        $this->data['add_address'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_ADDRESS');
        $this->data['export_address'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_ADDRESS');
        $this->data['edit_address'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_ADDRESS');
        $this->data['ADDRESS_UPLOAD'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADDRESS_UPLOAD');
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Site Addresses')
                ->set_layout($this->layout)
                ->set('page_title', 'Site Addresses')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('customers/addresses', $this->data);
    }
    
    /**
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadAddresses() {
        
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
                if (trim($this->input->get('sitefm')) != '') {
                    $params['a.contactid'] = $this->input->get('sitefm');
                }
                if (trim($this->input->get('status')) != '') {
                    $params['a.isactive'] = $this->input->get('status');
                }
                

                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get site addresses data
                $addressData = $this->customerclass->getSiteAddresses($customerid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $addressData['trows'];
                $data = $addressData['data'];
                
                //format data
                foreach ($data as $key => $value) {
                    //$data[$key]['site'] = $this->customerclass->getFormattedSiteAddress($value['labelid']);
                    $data[$key]['siteaddress'] = urlencode($data[$key]['site']);
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
    public function exportAddress() {
        
        
        $export_address = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_ADDRESS');
        if(!$export_address) {
            show_404();
        }
        
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('state')) != '') {
            $params['a.sitestate'] = $this->input->get('state');
        }
        
        if (trim($this->input->get('sitefm')) != '') {
            $params['a.contactid'] = $this->input->get('sitefm');
        }
        if (trim($this->input->get('status')) != '') {
            $params['a.isactive'] = $this->input->get('status');
        }
        $customerid = $this->session->userdata('raptor_customerid'); 

        $order = 'desc';
        $field = 'sitesuburb';
        $addressData = $this->customerclass->getSiteAddresses($customerid, NULL, 0, $field, $order, $filter, $params);
                 
        $data = $addressData['data'];
  

        $data_array = array();

        $heading = array('Label Id', 'Site Ref', 'Company Name', 'Street Address', 'Suburb', 'State', 'Post Code', 'Site FM', 'Site Contact', 'Status');
        $this->load->library('excel');

        $name = 'customer';
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = $row['labelid'];
            $result[] = $row['siteref'];
            $result[] = $row['siteline1'];
            $result[] = $row['siteline2'];
            $result[] = $row['sitesuburb'];
            $result[] = $row['sitestate'];
            $result[] = $row['sitepostcode'];
            $result[] = $row['sitefm'];
            $result[] = $row['sitecontact'];
            $result[] = $row['isactive'] == 1 ? 'Active' : 'Inactive';

            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "address.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Address", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($name.'_address.xls', $data);
    }
    
    
    /**
     * download import file
     *      
     * @return void
     */
    public function downloadAddressTemplate() 
    {
      
        $heading = array('Site Ref', 'Name', 'Street1', 'Street2', 'Suburb', 'State', 'PostCode', 'Site FM', 'Site Contact', 'Site Phone', 'Site Email');  
        
        $this->load->library('excel'); 
        $data_array = array();
        
        $file_name="address_import_template.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); 
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(30); 
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(30); 
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30);  
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }
        $this->excel->Exportexcel("Address", $dir, $file_name, $heading, $data_array);

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
    public function importAddressExcel() 
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerid =$this->session->userdata('raptor_customerid');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'ADDRESS_UPLOAD');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to upload Address.';
            }
            else{
 
     
                $dir = "./temp";
                if(!is_dir($dir))
                {
                        mkdir($dir, 0755, true);
                }
                 $filen = $_FILES['importfile']['name'];

                $ext = pathinfo($filen, PATHINFO_EXTENSION);
                $filename="import_address.".$ext;
                $config['upload_path'] = $dir;
                $config['allowed_types'] = "xls|xlsx";
                $config['file_name'] = "import_address";
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("importfile")){
                      $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();

                }
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
                    $customerid =$this->session->userdata('raptor_customerid');
                    $updatedCount = 0;
                    $addedCount = 0;
                    foreach($sheetData as $key=>$value) {

                        if($key == 1) {
                            if(count($value) != 11){
                                $success = SuccessClass::initialize(FALSE);
                                $message="Invalid Import Excel Address format";
                                break;
                            }
                            continue;
                        }
 
                        if(trim($value['A']) != '' && trim($value['B']) != '' && trim($value['C']) != '' && trim($value['H']) != ''){
                            
                            $siteFMData = $this->customerclass->getContactByName($customerid, trim($value['H']));
                            $siteContactData = $this->customerclass->getContactByName($customerid, trim($value['I']));
                            
                            $updateData = array( 
                                'customerid'        => $customerid,
                                'siteref'           => trim($value['A']),
                                'siteline1'         => trim($value['B']),
                                'siteline2'         => trim($value['C']),
                                'siteline3'         => trim($value['D']),
                                'sitesuburb'        => trim($value['E']),
                                'sitestate'         => trim($value['F']),
                                'sitepostcode'      => trim($value['G']),
                                'territory'         => $this->sharedclass->getLowestTerritory(trim($value['E']), trim($value['F']), trim($value['G'])),
                                'contactid'         => isset($siteFMData['contactid'])? $siteFMData['contactid'] : 0,
                                'sitecontactid'     => isset($siteContactData['contactid'])? $siteContactData['contactid'] : 0,
                                'sitecontact'       => trim($value['I'])==''? (isset($siteContactData['firstname'])? $siteContactData['firstname'] : '') : trim($value['I']),
                                'sitephone'         => trim($value['J'])==''? (isset($siteContactData['phone'])? $siteContactData['phone'] : '') : trim($value['J']),
                                'sitemobile'        => isset($siteContactData['mobile'])? $siteContactData['mobile'] : '',
                                'siteemail'         => trim($value['K'])==''? (isset($siteContactData['email'])? $siteContactData['email'] : '') : trim($value['K']),
                            );
 
                            $location = getLocationFromAddress($updateData['siteline1'] . '+' .$updateData['siteline2'] . '+' . $updateData['sitesuburb'] . '+' . $updateData['sitestate'] .'+'. $updateData['sitepostcode']);
                            if (isset($location->lat) && isset($location->lng)) {
                                $updateData['latitude_decimal'] = $location->lat;
                                $updateData['longitude_decimal'] = $location->lng;
                            }
                             
                            $addressdata = $this->customerclass->getAddressBySiteRef($customerid, $value['A']);
                            if(count($addressdata)>0){
                                
                                $updateData['editdate'] = date('Y-m-d H:i:s', time());
                                $request = array(
                                    'updateData'       => $updateData,
                                    'labelid'          => $addressdata['labelid'],  
                                    'logged_contactid' => $this->data['loggeduser']->contactid
                                );
                                $this->customerclass->updateAddress($request);
                                 
                                $updatedCount = $updatedCount + 1;
                            }
                            else{ 
                                $updateData['dateadded'] = date('Y-m-d H:i:s', time());
                                $request = array(
                                    'insertAddressData' => $updateData, 
                                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                                );
                                $this->customerclass->insertAddress($request);
                                
                                $addedCount = $addedCount + 1;
                            }
                        }
                    }
 
                    $message =$addedCount. ' Address added and '. $updatedCount . 'Address updated'; 

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
     * add addresses
     * 
     * @return void
     */
    public function addAddress() {

         
        //check permission
        $add_address = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_ADDRESS');
        if(!$add_address) {
            show_404();
        }
        
        //Set Validation Rules
        $this->form_validation->set_rules('siteref', 'Siteref', 'trim|required');
        $customerid =$this->session->userdata('raptor_customerid');
        //Check Form Validation
        if ($this->form_validation->run() == FALSE)
        {
            
            $this->data['cssToLoad'] = array(
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            );

            
            //include required js for this view
            $this->data['jsToLoad'] = array( 
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
                base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
                base_url('plugins/input-mask/jquery.inputmask.js'),
                base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                base_url('plugins/jquery-validator/jquery.validate.min.js'), 
                base_url('assets/js/customers/customers.addaddress.js')
            );
          
             

            //intialize variables
            
            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['sitefm_contacts'] = $this->customerclass->getCustomerSiteFM($customerid);
            $this->data['site_contacts'] = $this->customerclass->getCustomerSiteContact($customerid);
            $this->data['customer'] = $this->customerclass->getCustomerById($customerid);
            $this->data['reportstocontacts'] = $this->customerclass->getOrganisationContacts($customerid);
            $this->data['role'] = $this->customerclass->getContactRole();
            $this->data['mailgroups'] = $this->customerclass->mailGroups($customerid);
            $this->data['default_country'] = 'AUSTRALIA';

            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Add Address')
                    ->set_layout($this->layout)
                    ->set('page_title', 'Site Addresses')
                    ->set('page_sub_title', 'Add Address')
                    ->set_breadcrumb('Site Addresses', site_url('customers/addresses'))
                    ->set_breadcrumb('Add Address', '')
                    ->set_partial('page_header', 'shared/page_header')
                    ->set_partial('header', 'shared/header')
                    ->set_partial('navigation', 'shared/navigation')
                    ->set_partial('footer', 'shared/footer')
                    ->build('customers/addaddress', $this->data);
        }
        else {
            
            $insertData = array(
                'dateadded'         => date('Y-m-d H:i:s', time()),
                'customerid'        => $customerid,
                'siteline1'         => $this->input->post('customername'),
                'sitecontactid'     => $this->input->post('sitecontactid'),
                'sitecontact'       => $this->input->post('sitecontact'),
                'contactid'         => $this->input->post('contactid'),
                'siteline2'         => $this->input->post('siteline1'),
                'siteline3'         => $this->input->post('siteline2'),
                'sitesuburb'        => $this->input->post('sitesuburb'),
                'sitestate'         => $this->input->post('sitestate'),
                'sitepostcode'      => $this->input->post('sitepostcode'),
                'territory'         => $this->input->post('territory'),
                'sitephone'         => $this->input->post('sitephone'),
                'sitemobile'        => $this->input->post('sitemobile'),
                'siteemail'         => $this->input->post('siteemail'),
                'siteref'           => $this->input->post('siteref'),
                'latitude_decimal'  => $this->input->post('latitude_decimal'),
                'longitude_decimal' => $this->input->post('longitude_decimal'),
                'isactive'          => (int)$this->input->post('isactive')
            );
          
            if($insertData['latitude_decimal'] == "" || $insertData['longitude_decimal']){
                $location = getLocationFromAddress($this->input->post('siteline1') . '+' . $this->input->post('sitesuburb') . '+' . $this->input->post('sitestate') .'+'. $this->input->post('sitepostcode'));
                if (isset($location->lat) && isset($location->lng)) {
                  $insertData['latitude_decimal'] = $location->lat;
                  $insertData['longitude_decimal'] = $location->lng;
                }
            }
            $request = array(
                'insertAddressData' => $insertData, 
                'logged_contactid'  => $this->session->userdata('raptor_contactid')
            );
            $response = $this->customerclass->insertAddress($request);
            
            $this->session->set_flashdata('success', 'Address added successfully.');
            
            if($this->input->get('from')){  
                redirect($this->input->get('from'));
            }
            else{  
                redirect("customers/addresses");
            } 
            
            
        }
   
    }
    
   
    
    /**
     * edit addresses
     * 
     * @return void
     */
    public function editAddress($labelid) {

        
        //check permission
        $edit_address = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_ADDRESS');
        if(!$edit_address) {
            show_404();
        }
        
        $address = $this->customerclass->getAddressById($labelid);
        $customerid =$this->session->userdata('raptor_customerid'); 
 	 
        if(count($address)==0){
            show_404();
        }
        if($address['customerid']!=$customerid){
            show_404();
        }
        
        //Set Validation Rules
        $this->form_validation->set_rules('siteref', 'Siteref', 'trim|required');

        //Check Form Validation
        if ($this->form_validation->run() == FALSE)
        {
            //include required css for this view
            $this->data['cssToLoad'] = array(
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
                base_url('plugins/uigrid/ui-grid-stable.min.css') 
            );

            
            //include required js for this view
            $this->data['jsToLoad'] = array( 
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
                base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
                base_url('plugins/input-mask/jquery.inputmask.js'),
                base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('plugins/uigrid/angular.min.js'), 
                base_url('plugins/uigrid/ui-grid-stable.min.js'), 
                base_url('assets/js/customers/customers.editaddress.js'),
                base_url('assets/js/customers/customers.addressattributes.js')
            );

            //intialize variables
            
            //echo '<pre>';
            //print_r($address);
            //exit;

            if(count($address) > 0) {
                $address['dateadded'] = format_datetime($address['dateadded'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                $address['editdate'] = format_datetime($address['editdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            }

            $this->data['address'] = $address;

            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['sitefm_contacts'] = $this->customerclass->getCustomerSiteFM($customerid);
            $this->data['site_contacts'] = $this->customerclass->getCustomerSiteContact($customerid);
            $this->data['customer'] = $this->customerclass->getCustomerById($customerid);
            $this->data['reportstocontacts'] = $this->customerclass->getOrganisationContacts($customerid);
            $this->data['role'] = $this->customerclass->getContactRole();
            $this->data['mailgroups'] = $this->customerclass->mailGroups($customerid);
            $this->data['default_country'] = 'AUSTRALIA';
            $addressAttributes = $this->customerclass->getAddressAttributes($customerid, NULL, 0, 'name', 'asc', '', array());
            $this->data['address_attributes'] = $addressAttributes['data'];// $this->customerclass->getAttributes($customerid);
            $this->data['attribute_types'] = $this->customerclass->getAttributeTypes();
            
            $contactid = $this->session->userdata('raptor_contactid');
            
            $this->data['DELETE_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_ADDRESS_ATTRIBUTE');
            $this->data['ADD_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_ADDRESS_ATTRIBUTE');
            $this->data['EDIT_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_ADDRESS_ATTRIBUTE');
         
            $this->data['CREATE_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'CREATE_ADDRESS_ATTRIBUTE');

            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Edit Address')
                ->set_layout($this->layout)
                ->set('page_title', 'Site Addresses')
                ->set('page_sub_title', 'Edit Address')
                ->set_breadcrumb('Site Addresses', site_url('customers/addresses'))
                ->set_breadcrumb('Edit Address', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('customers/editaddress', $this->data);
        }
        else {

            $updateData = array(
                'editdate'          => date('Y-m-d H:i:s', time()),
                'siteline1'         => $this->input->post('customername'),
                'sitecontactid'     => $this->input->post('sitecontactid'),
                'sitecontact'       => $this->input->post('sitecontact'),
                'contactid'         => $this->input->post('contactid'),
                'siteline2'         => $this->input->post('siteline1'),
                'siteline3'         => $this->input->post('siteline2'),
                'sitesuburb'        => $this->input->post('sitesuburb'),
                'sitestate'         => $this->input->post('sitestate'),
                'sitepostcode'      => $this->input->post('sitepostcode'),
                'territory'         => $this->input->post('territory'),
                'sitephone'         => $this->input->post('sitephone'),
                'sitemobile'        => $this->input->post('sitemobile'),
                'siteemail'         => $this->input->post('siteemail'),
                'siteref'           => $this->input->post('siteref'),
                'latitude_decimal'  => $this->input->post('latitude_decimal'),
                'longitude_decimal' => $this->input->post('longitude_decimal'),
                'isactive'          => (int)$this->input->post('isactive')
            );

             if($updateData['latitude_decimal'] == "" || $updateData['longitude_decimal']){
                $location = getLocationFromAddress($this->input->post('siteline1') . '+' . $this->input->post('sitesuburb') . '+' . $this->input->post('sitestate') .'+'. $this->input->post('sitepostcode'));
                if (isset($location->lat) && isset($location->lng)) {
                  $updateData['latitude_decimal'] = $location->lat;
                  $updateData['longitude_decimal'] = $location->lng;
                }
            }
            
            $request = array(
                'updateData'       => $updateData,
                'labelid'          => $labelid,  
                'logged_contactid' => $this->session->userdata('raptor_contactid')
            );
            $this->customerclass->updateAddress($request);
 
            $this->session->set_flashdata('success', 'Address updated successfully.');
            redirect("customers/addresses");
            
             
        }
   
    }
    
     /**
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateAddress() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_ADDRESS');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to edit Address.';
            }
 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data 
                $labelid = $this->input->post('id');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                
                
                $updateData = array(
                    $field => $value
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadAddressAttributesValues() {
        
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
                $field = 'aa.name';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }

                if (trim($this->input->get('labelid')) != '') {
                    $params['aav.labelid'] = $this->input->get('labelid');
                }

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                $contactid = $this->session->userdata('raptor_contactid');
                $delete_attribute = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_ADDRESS_ATTRIBUTE');
                $edit_attribute = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_ADDRESS_ATTRIBUTE');

                //get site addresses data
                $addressData = $this->customerclass->getAddressAttributesValues($size, $start, $field, $order, $params);
                
                $trows = $addressData['trows'];
                $data = $addressData['data'];
                
                //format data
                foreach ($data as $key => $value) {
                    $data[$key]['delete_attr'] = $delete_attribute;
                    $data[$key]['edit_attr'] = $edit_attribute;
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
    * This function use for create address attributes
    * 
    * @return json 
    */
    public function createAddressAttributeValue() {

        
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
                
                //set form validation rule
                $this->form_validation->set_rules('attribute', 'Attribute', 'required');
                $this->form_validation->set_rules('value', 'caption', 'required');

                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors()
                    );
                }
                else {

                    $AddressAttributeData = $this->customerclass->getAddressAttributeValueByAttributeId(trim($this->input->post('attribute')), trim($this->input->post('labelid')));
                    if(count($AddressAttributeData) > 0){
                        $data = array (
                            'success' => FALSE,
                            'message' => 'Address Attribute Already Exist.'
                        );
                    }
                    else{
                        $status = 0;
                        if($this->input->post('status')) {
                            $status = 1;
                        }

                        $type = trim($this->input->post('type'));
                        $value = trim($this->input->post('value'));

                        //intialize array for insert
                        $attributeData = array(
                            'attributeid'   => trim($this->input->post('attribute')),
                            'status'        => $status,
                            'labelid'       => trim($this->input->post('labelid'))
                        );

                        if($type == 'int') {
                            $attributeData['value_int'] = $value;
                        } else {
                            $attributeData['value_string'] = $value;
                        }

                        $request = array(
                            'attributeData'     => $attributeData,
                            'customerid'        => $this->session->userdata('raptor_customerid'), 
                            'logged_contactid'  => $this->session->userdata('raptor_contactid')
                        );

                        $response = $this->customerclass->createAddressAttributeValue($request);
                        $message = 'Attribute Value Created.';

                        $data = array (
                            'success'   => TRUE
                        );
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
    * This function use for update address attributes value
    * 
    * @return json 
    */
    public function updateAddressAttributeValue() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $id = trim($this->input->get('id')); 
            if( !isset($id) ) {
                $message = 'id cannot be null';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $updateData = array();
                
                if($this->input->get('status') || $this->input->get('status') == "0") {
                    $updateData['status'] = trim($this->input->get('status')); 
                }
                
                if($this->input->get('value')) {
                    $type = trim($this->input->get('type'));
                    $value = trim($this->input->get('value')); 
                    if($type == 'int') {
                        $updateData['value_int'] = $value; 
                    } else {
                        $updateData['value_string'] = $value; 
                    }
                }
                
                
                $request = array(
                    'updateData'        => $updateData, 
                    'id'                => $id,  
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
            
                $this->customerclass->updateAddressAttributeValue($request);
                $message = 'Attribute value updated.';
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
    * This function use for update address attributes value
    * 
    * @return json 
    */
    public function deleteAddressAttributeValue() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $id = trim($this->input->get('id')); 
            if( !isset($id) ) {
                $message = 'id cannot be null';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {

                $request = array(
                    'id'                => $id,  
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
            
                $this->customerclass->deleteAddressAttributeValue($request);

                $message = 'Attribute value deleted.';
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
    * This function use for show GL Codes
    * 
    * @return void 
    */
    public function addressAttributes() {
        
        $contactid = $this->session->userdata('raptor_contactid'); 
        
        $this->data['DELETE_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_ADDRESS_ATTRIBUTE');
        $this->data['ADD_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_ADDRESS_ATTRIBUTE');
        $this->data['EDIT_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_ADDRESS_ATTRIBUTE');
        $this->data['EXPORT_ADDRESS_ATTRIBUTE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_ADDRESS_ATTRIBUTE');
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),  
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('plugins/uigrid/angular.min.js'),  
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/customers/customers.addressattributes.js')
        );
         
        $this->data['attribute_types'] = $this->customerclass->getAttributeTypes();
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Address Attributes')
            ->set_layout($this->layout)
            ->set('page_title', 'Address Attributes')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Settings', '')
            ->set_breadcrumb('Address Attributes', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer') 
            ->build('customers/addressattributes', $this->data);
    }
    
    
    /**
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadAddressAttributes() {
        
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
                $field = 'name';
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
 
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get GL Codes data
                $GlCodeData = $this->customerclass->getAddressAttributes($customerid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $GlCodeData['trows'];
                $data = $GlCodeData['data'];
                
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function exportAddressAttributes() {
        
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_ADDRESS_ATTRIBUTE');
        if(!$export_excel) {
            show_404();
        }
        
        
        //default settings for uigrid
        $order = 'asc';
        $field = 'name';
        $params = array();

        //intialize uigrid request params
        $filter = $this->input->get('filtertext');
 
 
        //get customerid
        $customerid =$this->session->userdata('raptor_customerid');
  
        //get GL Codes data
        $GlCodeData = $this->customerclass->getAddressAttributes($customerid, NULL, 0, $field, $order, $filter, $params);
        $data = $GlCodeData['data'];
       // showasset
        
        $data_array = array();
        
        
        $heading = array('Attibute Name', 'Caption', 'Type', 'Highlighted','Active');
    
        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = $row['name'];
            $result[] = $row['caption'];
            $result[] = $row['type'];
            $result[] = $row['highlighted']==1? 'Yes':'No';
            $result[] = $row['status']==1? 'Yes':'No';
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "addressattributes.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Address Attributes", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $filedata = file_get_contents(base_url()."temp/".$file_name);
        force_download('addressattributes.xls', $filedata);       
                 
    }
     
    /**
    * This function use for Add Edit address attributes
    * 
    * @return json 
    */
    public function addEditAddressAttribute() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_ADDRESS_ATTRIBUTE');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_ADDRESS_ATTRIBUTE');
            }
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Address Attribute.';
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
                //set form validation rule
                $this->form_validation->set_rules('newattribute', 'Attribute', 'required');
                $this->form_validation->set_rules('caption', 'caption', 'required');
                $this->form_validation->set_rules('attributetypeid', 'Attribute Type', 'required');
                
                
                //validate form
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array (
                        'success' => FALSE,
                        'message' => validation_errors()
                    );
                }
                else {
                    
                    $customerid = $this->session->userdata('raptor_customerid');
                    $AddressAttributeData = array();
                    if (trim($this->input->post('mode')) == 'edit') {
                        $AddressAttributeData = $this->customerclass->checkAddressAttributeName($customerid, trim($this->input->post('newattribute')), trim($this->input->post('addressattributeid')));
                    }
                    else{
                        $AddressAttributeData = $this->customerclass->checkAddressAttributeName($customerid, trim($this->input->post('newattribute')));
                    }
                    if(count($AddressAttributeData) > 0){
                        $data = array (
                            'success' => FALSE,
                            'message' => 'Attribute Name Already Exist.'
                        );
                    }
                    else{
                    
                        $hightlighted = 0;
                        $status = 0;
                        if($this->input->post('highlighted')) {
                            $hightlighted = 1;
                        }
                        if($this->input->post('status')) {
                            $status = 1;
                        }

                        //intialize array for insert
                        $attributeData = array(
                            'name'              => trim($this->input->post('newattribute')),
                            'attributetypeid'   => trim($this->input->post('attributetypeid')),
                            'caption'           => trim($this->input->post('caption')),
                            'highlighted'       => $hightlighted,
                            'status'            => $status,
                        );

                        $addressattributeid = '';
                        //check Add/Edit Mode
                        if (trim($this->input->post('mode')) == 'edit') {
                            $addressattributeid = $this->input->post('addressattributeid');
                            $request = array(
                                'addressattributeid' => $addressattributeid,
                                'attributeData'      => $attributeData,
                                'customerid'         => $customerid, 
                                'logged_contactid'   => $this->data['loggeduser']->contactid
                            );

                            $this->customerclass->updateAddressAttribute($request);

                        }
                        else{
                            $attributeData['customerid'] = $this->session->userdata('raptor_customerid');
                            $request = array(
                                'attributeData'     => $attributeData,
                                'customerid'        => $customerid, 
                                'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                            );
                            $response = $this->customerclass->insertAddressAttribute($request);
                            $addressattributeid = (int)$response['addressattributeid'];
                        }

                        $message = 'Address Attribute Updated.'; 
                        $addressAttributes = $this->customerclass->getAddressAttributes($customerid, NULL, 0, 'name', 'asc', '', array());
                        $data = array (
                            'success'   => TRUE,
                            'data'      => $addressAttributes['data'],
                            'id'        => $addressattributeid
                        );
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
    * This function use for Delete Cost Centre
    * 
    * @return void
    */
    public function deleteAddressAttribute() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $addressattributeid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_ADDRESS_ATTRIBUTE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Address Attribute.';
            }
            else{
                $addressAttributeData = $this->customerclass->getAddressAttributeById($this->data['loggeduser']->customerid, $addressattributeid);
                if (count($addressAttributeData)== 0) {
                    $message = 'Invalid Address Attribute.';
                }
                else{
                    $checkData = $this->customerclass->getAddressAttributeValueByAttributeId($addressattributeid);
                    if(count($checkData)>0){
                        $message = "Address Attribute ".$addressAttributeData['name']." is assigned to site address, cannot be deleted.";
                    }
                }
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'addressattributeid'    => $addressattributeid,
                    'logged_contactid'      => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->deleteAddressAttribute($request);
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
    public function updateAddressAttribute() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            
            $addressattributeid = $this->input->post('id');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_ADDRESS_ATTRIBUTE');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to edit Address Atribute.';
            }
            else{
                $addressAttributeData = $this->customerclass->getAddressAttributeById($this->data['loggeduser']->customerid, $addressattributeid);
                if (count($addressAttributeData)==0) {
                    $message = 'Invalid Address Attribute.';
                }
                
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data 
                $customerid = $this->session->userdata('raptor_customerid');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                $attributeData = array(
                    $field => $value
                );
                
                $request = array(
                    'addressattributeid' => $addressattributeid,
                    'attributeData'      => $attributeData,
                    'customerid'         => $customerid, 
                    'logged_contactid'   => $this->data['loggeduser']->contactid
                );

                $this->customerclass->updateAddressAttribute($request);
                 
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
    * This function use for send portal invitations
    * 
    * @return json 
    */
    public function sendPortalInvitations() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $contactids = $this->input->post('contactids'); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $emailData = $this->sharedclass->getEmailRuleByRuleName('raptor_invitation');
                if(count($emailData) == 0) {
                    $success = SuccessClass::initialize(FALSE);
                    $message = 'Email rule not found.';
                } else {
                    $emailSubject = $emailData['emailsubject'];
                    $emailBody1 = $emailData['emailbody'];
                    
                    
                    $recepients = array();
                    $emailData = array();
                 
                    foreach($contactids as $val) {
                        $emailBody = $emailBody1;
                        $contactData = $this->customerclass->getContactById($val);
                        $email = $contactData['email'];
                        //$password = random_password();
                        $password = $this->config->item('raptor_welcome_password');
                        $raptor_support_email = $this->config->item('raptor_support_email');
                        $raptor_welcome_timeout=(int)$this->config->item('raptor_welcome_timeout');
                        if ($raptor_welcome_timeout==0) {
                            $raptor_welcome_timeout=60;
                        } 
                        
                        if(trim($email) != '') {
                            //update password
                            $this->ion_auth->update_password($contactData['contactid'], $password);
                            $updateData = array(
                                'cp_invitesendtime' => date('Y-m-d H:i:s'),
                                'active' => 1
                            );
                           
                            $request = array(
                                'updateContactData' => $updateData,  
                                'contactid'         => $contactData['contactid'],  
                                'logged_contactid'  => $this->session->userdata('raptor_contactid')
                            );

                            $this->customerclass->updateContact($request);
                            
                            
                            
                            $emailBody = str_replace("<ContactName>", $contactData['contactname'], $emailBody);
                            $emailBody = str_replace("<firstname>", $contactData['contactname'], $emailBody);
                            
                            $emailBody = str_replace("<ContactEmail>", $email, $emailBody);
                            $emailBody = str_replace("<email>", $email, $emailBody);
                            
                            $emailBody = str_replace("<raptor_welcome_password>", $password, $emailBody); 
                            $emailBody = str_replace("<raptor_welcome_timeout>", $raptor_welcome_timeout, $emailBody);
                            $emailBody = str_replace("<raptor_support_email>", $raptor_support_email, $emailBody);
                            
                            $emailData[] = array(
                                'email'     => $email,
                                'subject'   => $emailSubject,
                                'message'   => $emailBody
                            ); 
                        }
                    }
                    
                    foreach($emailData as $key => $val) {
                        
                        $this->email->clear();
                        $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                        $this->email->to($val['email']);
                        $this->email->subject($val['subject']);
                        $this->email->message($val['message']);
                        $this->email->send();
                         
                    }

                    $message = 'Portal invitations sent successfully.';
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
    * This function use for show GL Codes
    * 
    * @return void 
    */
    public function glcodes() {
        
        $contactid = $this->session->userdata('raptor_contactid');
        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['IMPORT_GLCODE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'IMPORT_GLCODE');
        $this->data['DELETE_GLCODE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_GLCODE');
        $this->data['ADD_GLCODE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_GLCODE');
        $this->data['EDIT_GLCODE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_GLCODE');
        $this->data['EXPORT_GLCODE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_GLCODE');
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/iCheck/square/grey.css'),
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/iCheck/icheck.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/customers/customers.glcodes.js')
        );
        
        $this->data['sites'] = $this->customerclass->getCustomerSiteAddress($customerid);
        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['jobtypes'] = $this->sharedclass->getJobTypes();
        $this->data['budgetcategories'] = $this->budgetclass->getBudgetCategories();
        $this->data['budgetitems'] = $this->budgetclass->getBudgetItems();
        $this->data['trades'] = $this->sharedclass->getTrades(1);
        $this->data['works'] = $this->sharedclass->getWorks(1);
        $this->data['subworks'] = $this->sharedclass->getSubWorks(1);
        $this->data['assetcategories'] = $this->assetclass->getAssetCategories();
        $this->data['accounttypes'] = $this->sharedclass->getAccountTypes();
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | GL Codes')
            ->set_layout($this->layout)
            ->set('page_title', 'GL Codes')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Settings', '')
            ->set_breadcrumb('GL Codes', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer') 
            ->build('customers/glcodes', $this->data);
    }
    
    
    /**
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadGLCodes() {
        
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
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = $this->input->get('filtertext');
                }

                if ($this->input->get('state') != NULL) {
                    $params['al.sitestate'] = $this->input->get('state');
                }
                if (trim($this->input->get('accounttype')) != '') {
                    $params['gl.accounttype'] = $this->input->get('accounttype');
                } 
                if (trim($this->input->get('labelid')) != '') {
                    $params['gl.labelid'] = $this->input->get('labelid');
                }
                if (trim($this->input->get('jobtypeid')) != '') {
                    $params['gl.jobtypeid'] = $this->input->get('jobtypeid');
                }
                if (trim($this->input->get('budget_categoryid')) != '') {
                    $params['gl.budget_categoryid'] = $this->input->get('budget_categoryid');
                }
                if (trim($this->input->get('budget_itemid')) != '') {
                    $params['gl.budget_itemid'] = $this->input->get('budget_itemid');
                }
                if (trim($this->input->get('se_tradeid')) != '') {
                    $params['gl.se_tradeid'] = $this->input->get('se_tradeid');
                }
                if (trim($this->input->get('se_worksid')) != '') {
                    $params['gl.se_worksid'] = $this->input->get('se_worksid');
                }
                if (trim($this->input->get('se_subworksid')) != '') {
                    $params['gl.se_subworksid'] = $this->input->get('se_subworksid');
                }
                if (trim($this->input->get('asset_categoryid')) != '') {
                    $params['gl.asset_categoryid'] = $this->input->get('asset_categoryid');
                }
                
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get GL Codes data
                $GlCodeData = $this->customerclass->getGLCodes($customerid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $GlCodeData['trows'];
                $data = $GlCodeData['data'];
                
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function exportGLCodes() {
        
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_GLCODE');
        if(!$export_excel) {
            show_404();
        }
        
        
        //default settings for uigrid
        $order = 'desc';
        $field = 'id';
        $params = array();

        //intialize uigrid request params
        $filter = $this->input->get('filtertext');


        if ($this->input->get('state') != NULL) {
            $params['al.sitestate'] = $this->input->get('state');
        }
        if (trim($this->input->get('accounttype')) != '') {
            $params['gl.accounttype'] = $this->input->get('accounttype');
        } 
        if (trim($this->input->get('labelid')) != '') {
            $params['gl.labelid'] = $this->input->get('labelid');
        }
        if (trim($this->input->get('jobtypeid')) != '') {
            $params['gl.jobtypeid'] = $this->input->get('jobtypeid');
        }
        if (trim($this->input->get('budget_categoryid')) != '') {
            $params['gl.budget_categoryid'] = $this->input->get('budget_categoryid');
        }
        if (trim($this->input->get('budget_itemid')) != '') {
            $params['gl.budget_itemid'] = $this->input->get('budget_itemid');
        }
        if (trim($this->input->get('se_tradeid')) != '') {
            $params['gl.se_tradeid'] = $this->input->get('se_tradeid');
        }
        if (trim($this->input->get('se_worksid')) != '') {
            $params['gl.se_worksid'] = $this->input->get('se_worksid');
        }
        if (trim($this->input->get('se_subworksid')) != '') {
            $params['gl.se_subworksid'] = $this->input->get('se_subworksid');
        }
        if (trim($this->input->get('asset_categoryid')) != '') {
            $params['gl.asset_categoryid'] = $this->input->get('asset_categoryid');
        }
 
        //get customerid
        $customerid =$this->session->userdata('raptor_customerid');
  
        //get GL Codes data
        $GlCodeData = $this->customerclass->getGLCodes($customerid, NULL, 0, $field, $order, $filter, $params);
        $data = $GlCodeData['data'];
       // showasset
        $showasset= $this->input->get('showasset');
        $data_array = array();
        
        if($showasset == 'true'){
            $heading = array('Site Address', 'Asset', 'Asset Category', 'Job Type', 'Categories', 'Items', 'Trades', 'Works', 'Sub Works', 'Type', 'GL Code', 'Account Description', 'Auto Select', 'Active');
        }
        else{
            $heading = array('Site Address', 'Job Type', 'Categories', 'Items', 'Trades', 'Works', 'Sub Works', 'Type', 'GL Code', 'Account Description', 'Auto Select', 'Active');
        }
        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = $row['address'];
            if($showasset == 'true'){
            $result[] = $row['asset'];
            $result[] = $row['assetcategory'];
            }
            $result[] = $row['jobtype'];
            $result[] = $row['budgetcategory'];
            $result[] = $row['budgetitem'];
            $result[] = $row['trade'];
            $result[] = $row['works'];
            $result[] = $row['subworks'];
            $result[] = $row['accounttype'];
            $result[] = $row['accountcode'];
            $result[] = $row['accountname'];
            $result[] = $row['isautoselect']==1? 'Yes':'No';
            $result[] = $row['isactive']==1? 'Yes':'No';
            
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "glcodes.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("GL Codes", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $filedata = file_get_contents(base_url()."temp/".$file_name);
        force_download('glcodes.xls', $filedata);       
                 
    }
    
    
    /**
     * import budget excel
     * 
     * @return json
     * 
     */
    public function importGlCodeExcel() 
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerid =$this->session->userdata('raptor_customerid');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'IMPORT_GLCODE');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to import GL Code.';
            }
            else{
 
     
                $dir = "./temp";
                if(!is_dir($dir))
                {
                        mkdir($dir, 0755, true);
                }
                 $filen = $_FILES['importfile']['name'];

                $ext = pathinfo($filen, PATHINFO_EXTENSION);
                $filename="import_glcodefile.".$ext;
                $config['upload_path'] = $dir;
                $config['allowed_types'] = "xls|xlsx";
                $config['file_name'] = "import_glcodefile";
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("importfile")){
                      $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();

                }
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
                        'customerid'        => $this->data['loggeduser']->customerid,
                        'labelid'           => (int)trim($this->input->post('labelid')),
                        'assetid'           => (int)trim($this->input->post('assetid')),
                        'asset_categoryid'  => (int)trim($this->input->post('asset_categoryid')),
                        'jobtypeid'         => (int)trim($this->input->post('jobtypeid')),
                        'budget_categoryid' => (int)trim($this->input->post('budget_categoryid')),
                        'budget_itemid'     => (int)trim($this->input->post('budget_itemid')),
                        'se_tradeid'        => (int)trim($this->input->post('se_tradeid')),
                        'se_worksid'        => (int)trim($this->input->post('se_worksid')),
                        'se_subworksid'     => (int)trim($this->input->post('se_subworksid')),
                        'accounttype'       => trim($this->input->post('accounttype')),
                        'accountcode'       => trim($this->input->post('accountcode')),
                        'accountname'       => trim($this->input->post('accountname')) 
                    );
                    $updatedCount = 0;
                    $addedCount = 0;
                    foreach($sheetData as $key=>$value) {

                        if($key == 1) {
                            if(count($value)!=2){
                                $success = SuccessClass::initialize(FALSE);
                                $message="Invalid Import Excel GL Code format ";
                                break;
                            }
                            continue;
                        }
 
                        if(trim($value['A'])!='' && trim($value['B'])!=''){
                            
                            $updateData['accountcode'] = $value['A'];
                            $updateData['accountname'] = $value['B'];
                            $glCodedata = $this->customerclass->getGLCodeByCode($customerid, $value['A']);
                            if(count($glCodedata)>0){
                               unset($updateData['isactive']);
                                $request = array(
                                    'glcodeid'          => $glCodedata['id'],
                                    'updateData'        => $updateData,
                                    'logged_contactid'  => $this->data['loggeduser']->contactid
                                );

                                $this->customerclass->updateGLCodes($request);
                                $updatedCount = $updatedCount + 1;
                            }
                            else{
                                $updateData['isactive'] = 1;
                                $request = array(
                                    'insertData'        => $updateData, 
                                    'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                                );
                                $this->customerclass->insertGLCodes($request);
                                
                                $addedCount = $addedCount + 1;
                            }
                        }
                    }
 
                    $message =$addedCount. ' GL Code added, '. $updatedCount . ' updated'; 

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
     * download import file
     * 
     * @param type $FromYearMonth
     * @param type $ToYearMonth
     *      
     * @return void
     */
    public function downloadGLCodeTemplate() 
    {
      
        $heading = array('GL Code','Description');  
        
        $this->load->library('excel'); 
        $data_array = array();
        
        $file_name="glcode_import_template.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); 
        
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }
        $this->excel->Exportexcel("GL Codes", $dir, $file_name, $heading, $data_array);

        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($file_name, $data);
   	 	  
    }
    
    
    /**
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function updateGLCodes() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_GLCODE');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to edit GL Code.';
            }
 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data 
                $glcodeid = $this->input->post('id');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                
                
                $updateData = array(
                    $field => $value
                );
                
                $request = array(
                    'glcodeid'          => $glcodeid,
                    'updateData'        => $updateData,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->updateGLCodes($request);
                    
                
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function addEditGLCode() {
            
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
            
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_GLCODE');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_GLCODE');
            }
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' GL Code.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
   
                //Create array for insert GL CODE data
                $updateData = array(
                    'customerid'        => $this->data['loggeduser']->customerid,
                    'labelid'           => (int)trim($this->input->post('labelid')),
                    'assetid'           => (int)trim($this->input->post('assetid')),
                    'asset_categoryid'  => (int)trim($this->input->post('asset_categoryid')),
                    'jobtypeid'         => (int)trim($this->input->post('jobtypeid')),
                    'budget_categoryid' => (int)trim($this->input->post('budget_categoryid')),
                    'budget_itemid'     => (int)trim($this->input->post('budget_itemid')),
                    'se_tradeid'        => (int)trim($this->input->post('se_tradeid')),
                    'se_worksid'        => (int)trim($this->input->post('se_worksid')),
                    'se_subworksid'     => (int)trim($this->input->post('se_subworksid')),
                    'accounttype'       => trim($this->input->post('accounttype')),
                    'accountcode'       => trim($this->input->post('accountcode')),
                    'accountname'       => trim($this->input->post('accountname')) 
                );

                //check Add/Edit Mode
                if (trim($this->input->post('mode')) == 'edit') {
                    $request = array(
                        'glcodeid'          => $this->input->post('glcodeid'),
                        'updateData'        => $updateData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );

                    $this->customerclass->updateGLCodes($request);
                 
                }
                else{
                    $updateData['isactive'] = 1;
                    $request = array(
                        'insertData'        => $updateData, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                    );
                    $this->customerclass->insertGLCodes($request);
                    

                }

                $message = 'GL Code updated.';
                
                
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
    
    
    //Cost Centre
    
      /**
    * This function use for show GL Codes
    * 
    * @return void 
    */
    public function costcentres() {
        
        $contactid = $this->session->userdata('raptor_contactid');
        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['IMPORT_COSTCENTRE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'IMPORT_COSTCENTRE');
        $this->data['DELETE_COSTCENTRE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_COSTCENTRE');
        $this->data['ADD_COSTCENTRE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_COSTCENTRE');
        $this->data['EDIT_COSTCENTRE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_COSTCENTRE');
        $this->data['EXPORT_COSTCENTRE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_COSTCENTRE');
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/customers/customers.costcentres.js')
        );
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Cost Centres')
            ->set_layout($this->layout)
            ->set('page_title', 'Cost Centres')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Settings', '')
            ->set_breadcrumb('Cost Centres', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer') 
            ->build('customers/costcentres', $this->data);
    }
    
    
    /**
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadCostCentres() {
        
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
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = $this->input->get('filtertext');
                }

                
                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get GL Codes data
                $GlCodeData = $this->customerclass->getCostCentres($customerid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $GlCodeData['trows'];
                $data = $GlCodeData['data'];
                
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function exportCostCentres() {
        
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_COSTCENTRE');
        if(!$export_excel) {
            show_404();
        }
        
        
        //default settings for uigrid
        $order = 'desc';
        $field = 'id';
        $params = array();

        //intialize uigrid request params
        $filter = $this->input->get('filtertext');
 
 
        //get customerid
        $customerid =$this->session->userdata('raptor_customerid');
  
        //get GL Codes data
        $GlCodeData = $this->customerclass->getCostCentres($customerid, NULL, 0, $field, $order, $filter, $params);
        $data = $GlCodeData['data'];
       // showasset
        
        $data_array = array();
        
        
        $heading = array('Cost Centre', 'Description', 'Active');
    
        $this->load->library('excel');
 
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = $row['costcentre'];
            $result[] = $row['description'];
            $result[] = $row['isactive']==1? 'Yes':'No';
            
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "costcentres.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("costcentres", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $filedata = file_get_contents(base_url()."temp/".$file_name);
        force_download('costcentres.xls', $filedata);       
                 
    }
    
    
     /**
     * import budget excel
     * 
     * @return json
     * 
     */
    public function importCostCentreExcel() 
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerid =$this->session->userdata('raptor_customerid');
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'IMPORT_COSTCENTRE');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to import Cost Centre.';
            }
            else{
 
     
                $dir = "./temp";
                if(!is_dir($dir))
                {
                        mkdir($dir, 0755, true);
                }
                 $filen = $_FILES['importfile']['name'];

                $ext = pathinfo($filen, PATHINFO_EXTENSION);
                $filename="import_costcentrefile.".$ext;
                $config['upload_path'] = $dir;
                $config['allowed_types'] = "xls|xlsx";
                $config['file_name'] = "import_costcentrefile";
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("importfile")){
                      $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();

                }
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
                        'customerid'        => $this->data['loggeduser']->customerid,
                    );
                    $updatedCount = 0;
                    $addedCount = 0;
                    foreach($sheetData as $key=>$value) {

                        if($key == 1) {
                            if(count($value)!=2){
                                $success = SuccessClass::initialize(FALSE);
                                $message="Invalid Import Excel Cost Centre format ";
                                break;
                            }
                            continue;
                        }
 
                        if(trim($value['A'])!='' && trim($value['B'])!=''){
                            
                            $updateData['costcentre'] = $value['A'];
                            $updateData['description'] = $value['B'];
                            $glCodedata = $this->customerclass->getCostCentreByCode($customerid, $value['A']);
                            if(count($glCodedata)>0){
                               unset($updateData['isactive']);
                                $request = array(
                                    'costcentreid'      => $glCodedata['id'],
                                    'updateData'        => $updateData,
                                    'logged_contactid'  => $this->data['loggeduser']->contactid
                                );

                                $this->customerclass->updateCostCentre($request);
                                $updatedCount = $updatedCount + 1;
                            }
                            else{
                                $updateData['isactive'] = 1;
                                $request = array(
                                    'insertData'        => $updateData, 
                                    'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                                );
                                $this->customerclass->insertCostCentre($request);
                                
                                $addedCount = $addedCount + 1;
                            }
                        }
                    }
 
                    $message =$addedCount. ' Cost Centre added, '. $updatedCount . ' updated'; 

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
     * download import file
     * 
     * @param type $FromYearMonth
     * @param type $ToYearMonth
     *      
     * @return void
     */
    public function downloadCostCentreTemplate() 
    {
      
        $heading = array('Cost Centre','Description');  
        
        $this->load->library('excel'); 
        $data_array = array();
        
        $file_name="costcentre_import_template.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); 
        
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }
        $this->excel->Exportexcel("Cost Centre", $dir, $file_name, $heading, $data_array);

        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($file_name, $data);
   	 	  
    }
        
    /**
    * This function use for Delete Cost Centre
    * 
    * @return void
    */
    public function deleteCostCentre() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $costcentreid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_COSTCENTRE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Cost Centre.';
            }
            else{
                $costCentreData = $this->customerclass->getCostCentreById($this->data['loggeduser']->customerid, $costcentreid);
                if (count($costCentreData)==0) {
                    $message = 'Invalid Cost Centre Id.';
                }
                else{
                    $checkData = $this->customerclass->checkCostCentre($this->data['loggeduser']->customerid, $costCentreData['costcentre']);
                    if($checkData['asset']>0 || $checkData['invoice']>0){
                        $message = "Cost centre ".$costCentreData['costcentre']." is assigned to ".$checkData['asset']." assets (and/or) ".$checkData['asset']." invoices and cannot be deleted.";
                    }
                }
                
                
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
               
                $request = array(
                    'costcentreid'      => $costcentreid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->deleteCostCentre($request);
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
    public function updateCostCentre() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'EDIT_COSTCENTRE');
            
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to edit Cost Centre.';
            }
 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data 
                $costcentreid = $this->input->post('id');
                $field = trim($this->input->post('field'));
                $value = trim($this->input->post('value'));
                    
                $updateData = array(
                    $field => $value
                );
                
                $request = array(
                    'costcentreid'      => $costcentreid,
                    'updateData'        => $updateData,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->updateCostCentre($request);
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function addEditCostCentre() {
            
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
            
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_COSTCENTRE');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_COSTCENTRE');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Cost Centre.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
   
                //Create array for insert GL CODE data
                $updateData = array(
                    'customerid'        => $this->data['loggeduser']->customerid,
                    'costcentre'        => trim($this->input->post('costcentre')),
                    'description'       => trim($this->input->post('description')) 
                );

                //check Add/Edit Mode
                if (trim($this->input->post('mode')) == 'edit') {
                    $request = array(
                        'costcentreid'      => $this->input->post('costcentreid'),
                        'updateData'        => $updateData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );

                    $this->customerclass->updateCostCentre($request);
                 
                }
                else{
                    $updateData['isactive'] = 1;
                    $request = array(
                        'insertData'        => $updateData, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                    );
                    $this->customerclass->insertCostCentre($request);
                    

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
    * This function use for load Org Contacts
    * 
    * @return json 
    */
    public function loadOrgContacts() { 
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
         
            $customerid = trim($this->input->get('customerid')); 
            if(empty($customerid)){
                $customerid = $this->session->userdata('raptor_customerid');
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                 
                $data = $this->customerclass->getOrgContacts($customerid);
                  
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
    
}