<?php 
/**
 * Suppliers Controller Class
 *
 * This is a Suppliers controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Suppliers Controller Class
 *
 * This is a Suppliers controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Suppliers
 * @filesource          Suppliers.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Suppliers extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();  
        
        
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
            base_url('assets/js/suppliers/suppliers.index.js')
        );
        
       
        $contactid = $this->session->userdata('raptor_contactid');
        
        $this->data['states'] =$this->sharedclass->getStates(1);
        $this->data['se_trades'] = $this->sharedclass->getTrades(1);
        $this->data['suppliertypes'] = $this->customerclass->getSupplierTypes();
        
        
        
        $this->data['ADD_SUPPLIER'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_SUPPLIER');
        $this->data['EXPORT_SUPPLIERS'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_SUPPLIERS');
        $this->data['EDIT_SUPPLIER'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_SUPPLIER');
        $this->data['DELETE_SUPPLIER'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_SUPPLIER');
        $this->data['ALLOW_ETP_LOGIN'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ALLOW_ETP_LOGIN');

        
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Suppliers')
                ->set_layout($this->layout)
                ->set('page_title', 'My Suppliers')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('suppliers/index', $this->data);
    }
    
    /**
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadSuppliers() {
        
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
                $filter = '';
                $params = array();
                $rolea = array();
                $contactid = $this->session->userdata('raptor_contactid');
                $ALLOW_ETP_LOGIN =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ALLOW_ETP_LOGIN');

                $customerid =$this->session->userdata('raptor_customerid');
                $params['ownercustomerid'] = $customerid;
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('state')) != '') {
                    $params['s.shipstate'] = $this->input->get('state');
                }
                if (trim($this->input->get('typeid')) != '') {
                    $params['s.typeid'] = $this->input->get('typeid');
                }
                
                
                if (trim($this->input->get('status')) != '') {
                    $params['s.isactive'] = $this->input->get('status');
                }

                if (is_array($this->input->get('tradeids'))) {
                    $role = $this->input->get('tradeids');
                    foreach ($role as $key => $value) {
                       $rolea[] = $value; 
                    }
                   $params['s.tradeid'] = $rolea;
                }
                else {
                    $params['s.tradeid'] = $this->input->get('tradeids');
                }

                

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data
                $contactData = $this->customerclass->getCustomers($size, $start, $field, $order, $filter, $params);
                 
                $trows  = $contactData['trows'];
                $data = $contactData['data'];
            
                //format data
                foreach($data as $key=>$value) {
                    if(!$ALLOW_ETP_LOGIN){
                        $data[$key]['primarycontactid'] = '';
                    }
                    else{
                        if($value['primarycontactid'] == NULL || $value['primarycontactid'] == ''){
                            $data[$key]['primarycontactid'] = '';
                        }
                        else{
                            $data[$key]['primarycontactid'] = encrypt_decrypt('encrypt', $value['primarycontactid']);
                        }
                    }
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
    public function exportSuppliers() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_SUPPLIERS');
        if(!$export_excel) {
            show_404();
        }
        
        $order = 'asc';
        $field = 'companyname';
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('state')) != '') {
            $params['s.shipstate'] = $this->input->get('state');
        }

        if (trim($this->input->get('status')) != '') {
            $params['s.isactive'] = $this->input->get('status');
        }
         if (trim($this->input->get('typeid')) != '') {
            $params['s.typeid'] = $this->input->get('typeid');
        }
        if (is_array($this->input->get('tradeids'))) {
            $role = $this->input->get('tradeids');
            foreach ($role as $key => $value) {
               $rolea[] = $value; 
            }
           $params['s.tradeid'] = $rolea;
        }
        else {
            $params['s.tradeid'] = $this->input->get('tradeids');
        }
        $customerid =$this->session->userdata('raptor_customerid');
        $params['ownercustomerid'] = $customerid;

        //get contacts data
        $contactData = $this->customerclass->getCustomers(NULL, 0, $field, $order, $filter, $params);
               
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('Company Name', 'Primary Trade', 'Phone', 'Email', 'Suburb', 'State', 'Primary Contact', 'Active', 'Portal Access', 'Balance');
        $this->load->library('excel');

      
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
             
            $result[] = $row['companyname'];
            $result[] = $row['se_trade_name'];
            $result[] = $row['phone']; 
            $result[] = $row['email'];
            $result[] = $row['shipsuburb'];
            $result[] = $row['shipstate'];
            $result[] = $row['primarycontact'];
            $result[] = $row['isactive']==1? 'Yes':'No';
            $result[] = $row['hasetpaccess']==1? 'Yes':'No';
            $result[] = format_amount($row['currentbalance']);
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "suppliers.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("My Supplier", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Sontacts.xls', $data);
    }
    
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deleteSupplier() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $customerid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_SUPPLIER');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Supplier.';
            }
             
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            { 
                $request = array(
                    'customerid'      => $customerid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->deleteCustomer($request);
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
        $add_SUPPLIER = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_SUPPLIER');
        if(!$add_SUPPLIER) {
            show_404();
        }
         
        //set form validation rule
        $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required');
        $this->form_validation->set_rules('structure', 'Structure', 'trim|required');
        $this->form_validation->set_rules('shipping1', 'Business Address', 'trim|required');
        $this->form_validation->set_rules('shipsuburb', 'Business City', 'trim|required');
        $this->form_validation->set_rules('shipstate', 'Business State', 'trim|required');
        $this->form_validation->set_rules('shippostcode', 'Business Post Code', 'trim|required');
        $this->form_validation->set_rules('mail1', 'Mailing Address', 'trim|required');
        $this->form_validation->set_rules('mailsuburb', 'Mailing City', 'trim|required');
        $this->form_validation->set_rules('state', 'Mailing State', 'trim|required');
        $this->form_validation->set_rules('postcode', 'Mailing Post Code', 'trim|required');
        $this->form_validation->set_rules('email', "Email", 'trim|required|valid_email'); 
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
                base_url('assets/js/suppliers/suppliers.addsupplier.js') 

            );
             
            
            $customerid =$this->session->userdata('raptor_customerid');
            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['se_trades'] = $this->sharedclass->getTrades(1);
            $this->data['suppliertypes'] = $this->customerclass->getSupplierTypes();

            //generate view
           $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Add Supplier')
                ->set_layout($this->layout)
                ->set('page_title', 'My Suppliers')
                ->set('page_sub_title', 'Add')
                ->set_breadcrumb('My Suppliers', site_url('suppliers'))
                ->set_breadcrumb('Add Supplier', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('suppliers/addsupplier', $this->data);
        }
        else {
            
            $active = 0;
            if($this->input->post('isactive')) {
                $active = 1;
            }
            
            $customerid =$this->session->userdata('raptor_customerid');
 
            // intialize array for insert
            $fields = array(
                'custtype'          => 'supplier',
                'typeid'            => trim($this->input->post('typeid')), 
                'ownercustomerid'   => $customerid,
                'companyname'       => trim($this->input->post('companyname')), 
                'tradingname'       => trim($this->input->post('companyname')), 
                'structure'         => trim($this->input->post('structure')), 
                'abn'               => trim($this->input->post('abn')), 
                'isgstregistered'   => (int)trim($this->input->post('isgstregistered')),
                'primarytrade'      => trim($this->input->post('primarytrade')), 
                'tradeid'           => trim($this->input->post('tradeid')), 
                'shipping1'         => trim($this->input->post('shipping1')), 
                'shipping2'         => trim($this->input->post('shipping2')), 
                'shipsuburb'        => trim($this->input->post('shipsuburb')), 
                'shipstate'         => trim($this->input->post('shipstate')), 
                'shippostcode'      => trim($this->input->post('shippostcode')), 
                'mail1'             => trim($this->input->post('mail1')), 
                'mail2'             => trim($this->input->post('mail2')), 
                'mailsuburb'        => trim($this->input->post('mailsuburb')), 
                'state'             => trim($this->input->post('state')), 
                'postcode'          => trim($this->input->post('postcode')), 
                'country'           => trim($this->input->post('country')),
                'phone'             => trim($this->input->post('phone')), 
                'mobile'            => trim($this->input->post('mobile')), 
                
                'fax'               => trim($this->input->post('fax')), 
                'email'             => trim($this->input->post('email')), 
                'url'               => trim($this->input->post('url')), 
                'isactive'          => 1, 
                'dateadded'         => date('Y-m-d H:i:s', time()),
                'datemodified'      => date('Y-m-d H:i:s', time()),
                'origin'            => 'Raptor',
                'addedby'           => $this->session->userdata('raptor_contactid'),
                'modifiedby'        => $this->session->userdata('raptor_contactid')
            );
              
            //insert
            $request = array(
                'insertCustomerData'  => $fields,  
                'logged_contactid'    => $this->session->userdata('raptor_contactid')
            );
            
            $response = $this->customerclass->insertCustomer($request);

            $this->session->set_flashdata('success', 'Supplier Added successfully.');
            
            //redirect to contacts
            if($this->input->get('from')){  
                redirect($this->input->get('from'));
            }
            else{  
                redirect("suppliers");
            } 
     
        }
    }
    
    
    
    
    /**
    * This function use for edit Supplier 
    * @param integer $id - id for selected contact which one edit
    * @return void 
    */
    public function edit($id) {
        
        //check permission
        $edit_SUPPLIER = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_SUPPLIER');
        if(!$edit_SUPPLIER) {
            show_404();
        }
        
        //get data for selected contact
        $this->data['supplier'] = $this->customerclass->getCustomerById($id);
        $customerid =$this->session->userdata('raptor_customerid'); 
 	$contactid = $this->session->userdata('raptor_contactid');
        if(count($this->data['supplier'])==0){
            show_404();
        }
        if($this->data['supplier']['ownercustomerid']!=$customerid){
            show_404();
        }
        
        //set form validation rule
        $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required');
        $this->form_validation->set_rules('structure', 'Structure', 'trim|required');
        $this->form_validation->set_rules('shipping1', 'Business Address', 'trim|required');
        $this->form_validation->set_rules('shipsuburb', 'Business City', 'trim|required');
        $this->form_validation->set_rules('shipstate', 'Business State', 'trim|required');
        $this->form_validation->set_rules('shippostcode', 'Business Post Code', 'trim|required');
        $this->form_validation->set_rules('mail1', 'Mailing Address', 'trim|required');
        $this->form_validation->set_rules('mailsuburb', 'Mailing City', 'trim|required');
        $this->form_validation->set_rules('state', 'Mailing State', 'trim|required');
        $this->form_validation->set_rules('postcode', 'Mailing Post Code', 'trim|required');
        $this->form_validation->set_rules('email', "Email", 'required|valid_email'); 
        
        //check form validation
        if ($this->form_validation->run() == FALSE)
        {
            
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
                base_url('plugins/uigrid/angular.min.js'), 
                base_url('plugins/uigrid/ui-grid-stable.min.js'),
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('assets/js/suppliers/suppliers.editsupplier.js'),
                base_url('assets/js/suppliers/suppliers.contacts.js'),
                base_url('assets/js/suppliers/suppliers.sites.js') 
            );

           //intialize variables
           
            
            $this->data['states'] =$this->sharedclass->getStates(1);
            $this->data['se_trades'] = $this->sharedclass->getTrades(1);
            $this->data['reportstocontacts'] = $this->customerclass->getOrganisationContacts($id);
            $this->data['suppliertypes'] = $this->customerclass->getSupplierTypes();
            //$this->data['sites'] = $this->customerclass->getCustomerSiteAddress($customerid);
            
            
            $this->data['ADD_SUPPLIER_CONTACT'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_SUPPLIER_CONTACT');
            $this->data['EXPORT_SUPPLIER_CONTACT'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_SUPPLIER_CONTACT');
            $this->data['EDIT_SUPPLIER_CONTACT'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_SUPPLIER_CONTACT');
            $this->data['DELETE_SUPPLIER_CONTACT'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_SUPPLIER_CONTACT');
            $this->data['ALLOW_ETP_LOGIN'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ALLOW_ETP_LOGIN');

            $this->data['ADD_SUPPLIER_SITE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ADD_SUPPLIER_SITE');
            $this->data['EXPORT_SUPPLIER_SITE'] = $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_SUPPLIER_SITE');
            $this->data['EDIT_SUPPLIER_SITE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EDIT_SUPPLIER_SITE');
            $this->data['DELETE_SUPPLIER_SITE'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'DELETE_SUPPLIER_SITE');
    
            
           //generate view
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Suppliers - Edit Supplier')
                ->set_layout($this->layout)
                ->set('page_title', 'My Suppliers')
                ->set('page_sub_title', 'Supplier')
                ->set_breadcrumb('My Suppliers', site_url('suppliers'))
                ->set_breadcrumb('Edit Supplier', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('suppliers/editsupplier', $this->data);
            
        } else {

            // intialize array for insert
            $fields = array(
                'typeid'            => trim($this->input->post('typeid')), 
                'companyname'       => trim($this->input->post('companyname')), 
                'tradingname'       => trim($this->input->post('companyname')), 
                'structure'         => trim($this->input->post('structure')), 
                'abn'               => trim($this->input->post('abn')), 
                'isgstregistered'   => (int)trim($this->input->post('isgstregistered')),
                'primarytrade'      => trim($this->input->post('primarytrade')), 
                'tradeid'           => trim($this->input->post('tradeid')), 
                'shipping1'         => trim($this->input->post('shipping1')), 
                'shipping2'         => trim($this->input->post('shipping2')), 
                'shipsuburb'        => trim($this->input->post('shipsuburb')), 
                'shipstate'         => trim($this->input->post('shipstate')), 
                'shippostcode'      => trim($this->input->post('shippostcode')), 
                'mail1'             => trim($this->input->post('mail1')), 
                'mail2'             => trim($this->input->post('mail2')), 
                'mailsuburb'        => trim($this->input->post('mailsuburb')), 
                'state'             => trim($this->input->post('state')), 
                'postcode'          => trim($this->input->post('postcode')), 
                'country'           => trim($this->input->post('country')),
                'phone'             => trim($this->input->post('phone')),
                'mobile'            => trim($this->input->post('mobile')),
                'fax'               => trim($this->input->post('fax')), 
                'email'             => trim($this->input->post('email')), 
                'url'               => trim($this->input->post('url')), 
                'datemodified'      => date('Y-m-d H:i:s', time()),  
                'modifiedby'        => $this->session->userdata('raptor_contactid')
            );
           
            $request = array(
                'updateCustomerData' => $fields,  
                'customerid'         => $id,  
                'logged_contactid'   => $this->session->userdata('raptor_contactid')
            );
            
            $this->customerclass->updateCustomer($request);
   
            $this->session->set_flashdata('success', 'Supplier updated successfully.');
            redirect('suppliers');
        }
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
            $supplierid = trim($this->input->get('supplierid')); 
            $state = trim($this->input->get('state')); 
            $labelid = trim($this->input->get('labelid')); 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $supplierLabelids = $this->customerclass->getSupplierSiteLabelids($supplierid);
                if(($key = array_search($labelid, $supplierLabelids)) !== false) {
                    unset($supplierLabelids[$key]);
                } 
              
                $data = $this->customerclass->getCustomerSites($this->session->userdata('raptor_customerid'), $supplierLabelids, $state);
                 
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
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadSupplierContacts() {
        
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
                $field = 'contactname';
                $filter = '';
                $params = array();
                $rolea = array();
                $contactid = $this->session->userdata('raptor_contactid');
                $ALLOW_ETP_LOGIN =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'ALLOW_ETP_LOGIN');

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('state')) != '') {
                    $params['c.state'] = $this->input->get('state');
                }
                
                if (trim($this->input->get('tradeid')) != '') {
                    $params['c.tradeid'] = $this->input->get('tradeid');
                }
 
                $supplierid = $this->input->get('supplierid');

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get contacts data 
                $contactData = $this->customerclass->getContacts($supplierid, $size, $start, $field, $order, $filter, $params);
                  
                $trows  = $contactData['trows'];
                $data = $contactData['data'];
             
                //format data
                foreach($data as $key=>$value) {
                    if(!$ALLOW_ETP_LOGIN){
                        $data[$key]['primarycontactid'] = '';
                    }
                    else{ 
                        $data[$key]['primarycontactid'] = encrypt_decrypt('encrypt', $value['contactid']);
                       
                    }
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
    public function exportSupplierContacts() {
        
        //check export excel access
        $export_excel = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_SUPPLIER_CONTACT');
        if(!$export_excel) {
            show_404();
        }
        
        $order = 'asc';
        $field = 'contactname';
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('state')) != '') {
            $params['c.state'] = $this->input->get('state');
        }

        if (trim($this->input->get('tradeid')) != '') {
            $params['c.tradeid'] = $this->input->get('tradeid');
        }
 
        $supplierid = $this->input->get('supplierid');

        //get contacts data
        $contactData = $this->customerclass->getContacts($supplierid, NULL, 0, $field, $order, $filter, $params);
        
        $data = $contactData['data']; 

        $data_array = array();

        $heading = array('Name', 'Position', 'Trade', 'Mobile', 'Email', 'Suburb', 'State', 'Reports To');
        $this->load->library('excel');

        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            $result[] = $row['contactname'];
            $result[] = $row['position'];
            $result[] = $row['trade'];
            $result[] = $row['mobile'];
            $result[] = $row['etp_email'];
            $result[] = $row['suburb'];
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
        $this->excel->Exportexcel("Supplier Contacts", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('supplier_contacts.xls', $data);
        
    }
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deleteSupplierContact() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $contactid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_SUPPLIER_CONTACT');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Supplier Contact.';
            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'contactid'      => $contactid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->deleteContact($request);
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
    public function saveSupplierContact() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_SUPPLIER_CONTACT');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_SUPPLIER_CONTACT');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Contact.';
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
               
                //set form validation rule
                $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
                $this->form_validation->set_rules('surname', 'Last Name', 'trim|required');
                $this->form_validation->set_rules('position', 'Position', 'trim|required');
                
                $this->form_validation->set_rules('street1', 'Address 1', 'trim|required');
                $this->form_validation->set_rules('suburb', 'Suburb', 'trim|required'); 
                
                if (trim($this->input->post('mode')) == 'edit') {
                    $conData = $this->customerclass->getContactById(trim($this->input->post('contactid')));
                    if(count($conData)>0){
                        if($this->input->post('email') != $conData['etp_email']) {
                            $is_unique =  '|is_unique[contact.etp_email]';
                         } else {
                            $is_unique =  '';
                         }
                    }

                }
                else{
                    $is_unique =  '|is_unique[contact.etp_email]';
                }
                $this->form_validation->set_rules('email', "Email", 'trim|required|valid_email'.$is_unique);
            
                
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
 
                    //intialize array for insert
                    $contactData = array(
                        'customerid'    => trim($this->input->post('customerid')), 
                        'firstname'     => trim($this->input->post('firstname')), 
                        'surname'       => trim($this->input->post('surname')),
                        'position'      => trim($this->input->post('position')),
                        'tradeid'       => trim($this->input->post('tradeid')),
                        'bossid'        => trim($this->input->post('bossid')),
                        'mobile'        => trim($this->input->post('mobile')), 
                        'phone'         => trim($this->input->post('phone')), 
                        'etp_email'     => trim($this->input->post('email')),
                        'email'         => trim($this->input->post('email')),
                        'street1'       => trim($this->input->post('street1')), 
                        'street2'       => trim($this->input->post('street2')), 
                        'suburb'        => trim($this->input->post('suburb')), 
                        'state'         => trim($this->input->post('state')), 
                        'postcode'      => trim($this->input->post('postcode')), 
                        'primarycontact'=> (int)trim($this->input->post('primarycontact')), 
                        'active'        => (int)trim($this->input->post('active')),
                        'etp_onschedule'=> (int)trim($this->input->post('etp_onschedule')),
                        'editdate'      => date('Y-m-d H:i:s', time()),  
                        'editedby'      => $this->data['loggeduser']->contactid 
                    );
                     
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $contactid = $this->input->post('contactid');
                        $request = array(
                            'contactid'          => $contactid,
                            'updateContactData'  => $contactData,
                            'logged_contactid'   => $this->data['loggeduser']->contactid
                        );

                        $this->customerclass->updateContact($request);

                    }
                    else{
                        
                        $contactData['origin'] = 'Raptor';
                        $contactData['created_on'] =date('Y-m-d H:i:s', time());
                        $contactData['dateadded'] = time();
                        $contactData['addedby'] = $this->data['loggeduser']->contactid;
                       //insert
                        $request = array(
                            'insertContactData' => $contactData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );
                        $response = $this->customerclass->insertContact($request);
                        $contactid = $response['contactid'];

                    }
                    
                    //check isprimary  and update
                    if ((int)trim($this->input->post('primarycontact')) == 1) {
                        $this->customerclass->updatePrimaryContact(trim($this->input->post('supplierid')), $contactid);
                        $this->customerclass->updateETPAccessLevelContact(trim($this->input->post('supplierid')), $contactid);
                    }
                    
                    
                    
                    $message = 'Contact Added successfully.';
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
    * This function use for load addresses in uigrid
    * 
    * @return json 
    */
    public function loadSites() {
        
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
                $supplierid = $this->input->get('supplierid');
                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get site addresses data
                $addressData = $this->customerclass->getSupplierSites($customerid, $supplierid, $size, $start, $field, $order, $filter, $params);
                
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
    public function exportSites() {
        
        $params = array();
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : '';

        if (trim($this->input->get('state')) != '') {
            $params['a.sitestate'] = $this->input->get('state');
        }
        
        
        $customerid = $this->session->userdata('raptor_customerid'); 
        $supplierid = $this->input->get('supplierid');
        
        $order = 'desc';
        $field = 'sitesuburb';
        $addressData = $this->customerclass->getSupplierSites($customerid, $supplierid, NULL, 0, $field, $order, $filter, $params);
                 
        $data = $addressData['data'];
  

        $data_array = array();

        $heading = array('Site Ref', 'Street Address', 'Suburb', 'State', 'Post Code');
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

            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "Supplier_sites.xls";
        
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
        $this->excel->Exportexcel("Supplier Sites", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('Supplier_sites.xls', $data);
    }
    
    /**
    * This function use for Delete supplier
    * 
    * @return void
    */
    public function deleteSupplierSite() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $siteid = $this->input->post('id');
            
            $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid, 'DELETE_SUPPLIER_SITE');
          
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to delete Supplier Site.';
            }
           
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $request = array(
                    'siteid'      => $siteid,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                $this->customerclass->deleteSupplierSite($request);
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
    public function saveSupplierSite() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_SUPPLIER_SITE');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_SUPPLIER_SITE');
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
                        'supplierid'    => trim($this->input->post('supplierid')), 
                        'labelid'       => trim($this->input->post('labelid')), 
                        'isactive'      => (int)trim($this->input->post('isactive'))
                    );
                     
                    //check Add/Edit Mode
                    if (trim($this->input->post('mode')) == 'edit') {
                        $siteid = $this->input->post('siteid');
                        $request = array(
                            'siteid'           => $siteid,
                            'updateSiteData'   => $siteData,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );

                        $this->customerclass->updateSupplierSite($request);

                    }
                    else{
                         
                       //insert
                        $request = array(
                            'insertSiteData'    => $siteData, 
                            'logged_contactid'  => $this->data['loggeduser']->contactid
                        );
                        $response = $this->customerclass->insertSupplierSite($request);
                        $siteid = $response['siteid'];

                    }
                    
                    
                    $message = 'Site Added successfully.';
                    
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
        $supplierid = trim($this->input->post('supplierid'));
        if (trim($this->input->post('mode')) == 'edit') {
            $siteid = trim($this->input->post('siteid'));
            
        }
        
        
        if($this->customerclass->checkSupplierSite($supplierid, $labelid, $siteid)){
            $this->form_validation->set_message('check_site', 'Site Already exist for this supplier');
            return false;
        }
        else
        {
            return TRUE;
        }

    }
}