<?php 
/**
 * Settings Controller Class
 *
 * This is a Settings controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Controller Class
 *
 * This is a Settings controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Settings
 * @filesource          Settings.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Settings extends MY_Controller {

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
        //var_dump($this->db->last_query());
    }
        
 
    /**
     * changepassword
     * 
     * @return void
     */
    public function changepassword()
    {
   
           
        
            $this->data['sidebaractive'] = 'changepassword';
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            if (isset($this->data['ContactRules']["allow_simple_password"]) && $this->data['ContactRules']["allow_simple_password"] == 1){
                $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
            }
            else{
                $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|valid_pass');
            }
            $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required|matches[new]');

            
            
            $user = $this->ion_auth->user()->row();

            if($this->form_validation->run() == false)
            {

                    //render
                    $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Change Password')
                    ->set_layout($this->layout)
                    ->set('page_title', 'Change Password')
                    ->set('page_sub_title', '')
                    ->set_breadcrumb('Settings', '')
                    ->set_breadcrumb('Change Password', '')
                    ->set_partial('page_header', 'shared/page_header')
                    ->set_partial('header', 'shared/header')
                    ->set_partial('navigation', 'shared/navigation')
                    ->set_partial('footer', 'shared/footer')
                    ->build('settings/change_password', $this->data);
            }
            else
            {
                    $identity = $this->session->userdata('raptor_identity');

                    $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

                    if ($change)
                    {
                            //if the password was successfully changed $this->ion_auth->messages()
                            $this->session->set_flashdata('success', 'Password updated successfully.');
                            redirect('settings/changepassword');
                    }
                    else
                    {
                            $this->session->set_flashdata('error', $this->ion_auth->errors());
                            redirect('settings/changepassword');
                    }
            }
    }
        
    /**
     * profile
     * 
     * @return void
     */
    public function profile() {
           
        
        $contactid = $this->session->userdata('raptor_contactid');
         
        $this->data['UPLOAD_LOGO'] = $this->sharedclass->getFunctionalSecurityAccess($contactid,'UPLOAD_LOGO');
        $this->data['sidebaractive'] = 'profile';

        $this->data['cssToLoad'] = array(
            //base_url(). 'assets/css/jquery.bxslider.css'
         );

        $this->data['jsToLoad'] = array(
             base_url('plugins/input-mask/jquery.inputmask.js'),
             base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
             base_url('plugins/jquery-validator/jquery.validate.min.js'),
             base_url('assets/js/settings/settings.profile.js')
        );

        $this->form_validation->set_rules('name', 'Name', 'required');

        $this->data['states'] = $this->sharedclass->getStates(1);
        $oldBrand = $this->sharedclass->getBrandingImageByCustomerid($this->data['loggeduser']->customerid, 'L', 'P');
        if($this->form_validation->run() == FALSE)
        {
                $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Profile')
                ->set_layout($this->layout)
                ->set('page_title', 'Profile')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Settings', '')
                ->set_breadcrumb('Profile', '')        
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('settings/profile', $this->data);
        } 
        else {
            $id = $this->session->userdata('raptor_contactid');
            $data = array(
                        'firstname'=>trim($this->input->post('name')),
                        'street1'=>trim($this->input->post('street1')),
                        'street2'=>trim($this->input->post('street2')),
                        'suburb'=>trim($this->input->post('suburb')),
                        'state'=>trim($this->input->post('state')),
                        'postcode'=>trim($this->input->post('postcode')),
                        'mobile'=>trim($this->input->post('mobile')),
                        'phone'=>trim($this->input->post('phone'))
                    );


            $allowed = array('png','jpg','jpeg','gif');
            $filename = $_FILES['profilepic']['name'];

            if($filename!=""){

                $filelocation=$this->config->item('userphotos_dir');
                if (!is_dir($filelocation)) {
                    mkdir($filelocation, 0755, TRUE);
                }
                $docformat = pathinfo($filename, PATHINFO_EXTENSION);
                if(in_array($docformat, $allowed) ) {

                    $config['upload_path'] =  $filelocation;
                    $config['allowed_types'] = implode('|', $allowed);
                    $config['file_name'] = $id;
                    $config['overwrite'] = TRUE;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload("profilepic"))
                    {
                        $this->session->set_flashdata('error', $this->upload->display_errors()); 
                        
                    }
                    else {

                         $data['photodocid'] = $id.'.'.$docformat;
                    }
                }
            }

            if(isset($_FILES['companylogo']) && $this->data['UPLOAD_LOGO']){
                //image formats 
                $allowed = array('png', 'jpg', 'jpeg', 'gif');

                //get filename
                $filename = $_FILES['companylogo']['name'];
                $docformat = pathinfo($filename, PATHINFO_EXTENSION);

                if ($filename != '') {

                    //get file location from config
                    $filelocation= $this->config->item('branding_dir');

                    // set permisions to upload folder
                    if (!is_dir($filelocation)) {
                        mkdir($filelocation, 0755, TRUE);
                    }

                    if (in_array($docformat, $allowed)) {

                        //set image config
                        
                        if(count($oldBrand)==0){
                            $filename = time();
                        }
                        else{
                            $filename = $oldBrand['documentid']; 
                        }
                        $config['upload_path'] =  $filelocation;
                        $config['allowed_types'] = implode('|', $allowed);
                        $config['file_name'] = $filename;
                        $config['overwrite'] = TRUE;

                        //load image library
                        $this->load->library('upload', $config);

                        // upload image
                        if ($this->upload->do_upload("companylogo")) {
                            
							//get brandtype and brandlocation
							$brandLocationData = $this->sharedclass->getBrandLocationByCode('L');
							$brandLocationId = 0;
							if(count($brandLocationData) > 0) {
								$brandLocationId = $brandLocationData['id'];
							}
							
							$brandTypeData = $this->sharedclass->getBrandTypeByCode('P');
							$brandTypeId = 0;
							if(count($brandTypeData) > 0) {
								$brandTypeId = $brandTypeData['id'];
							}
							
                            //create insert data array
                            $brandingData = array(
                                'brandtypeid'     => $brandTypeId,
                                'brandlocationid' => $brandLocationId,
                                'isactive'        => 1,
                                'documentid'      => $filename,
                                'docformat'       => $docformat,
                                'customerid'      => $this->data['loggeduser']->customerid
                            );
                            if(count($oldBrand)==0){
                                $brandid = $this->sharedclass->insertBranding($brandingData);
                            }
                            else{
                                $brandid = $oldBrand['id'];
                                $this->sharedclass->updateBranding($brandid, $brandingData);
                            }
                            
                        }
                    }
                }
            }
            
            $this->ion_auth->update($id, $data);

            $this->session->set_flashdata('success', $this->ion_auth->messages());
            redirect("settings/profile", 'refresh');
        }
    }
	
    /**
     * load User Security Views
     * 
     * @return void
     */
    public function usersecurity() {

        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/settings/settings.index.js'),
            base_url('assets/js/settings/settings.usersecurity.js'),
            base_url('assets/js/settings/settings.auditlog.js')
        );
        
        $customerid = $this->session->userdata('raptor_customerid'); 
   
        $this->data['export_contactsecurity'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTACTSECURITY');
        $this->data['export_contactsecurity_auditlog'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTACTSECURITY_AUDITLOG');
        $this->data['view_contactsecurity_auditlog'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'VIEW_CONTACTSECURITY_AUDITLOG');
        
        $this->data['contacts'] = $this->customerclass->getContactsByParams(array('customerid' => $customerid), 'contactid, firstname, role');
        $this->data['role'] = $this->sharedclass->getRole();
        $this->data['functions'] = $this->sharedclass->getFunctions($customerid);
 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Contact Security')
                ->set_layout($this->layout)
                ->set('page_title', 'Contact Security')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('settings/usersecurity', $this->data);
    }
    
    /**
    * This function use for load User Security in uigrid
    * 
    * @return json 
    */
    public function loadUserSecurity() {
        
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
                $field = 'createdate';
                $params = array();
                
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
             
                if (trim($this->input->get('contact')) != '') {
                    $params['cs.contactid'] = $this->input->get('contact');
                }
                if (trim($this->input->get('function')) != '') {
                    $params['csf.functionname'] = $this->input->get('function');
                }
                if (trim($this->input->get('role')) != '') {
                    $params['c.role'] = $this->input->get('role');
                }

                //get customerid
                $customerid =$this->session->userdata('raptor_customerid');
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get User Security Data
                $userSecurityData = $this->customerclass->getUserFunctions($customerid, $size, $start, $field, $order, $params);
        
                $trows = $userSecurityData['trows'];
                $data = $userSecurityData['data'];
                
                //format data
                foreach ($data as $key => $value) {
                    $data[$key]['createdate'] = format_datetime($value['createdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
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
    * This function use for update user security
    * 
    * @return json 
    */
    public function updateUserSecurity() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {   

            $id = trim($this->input->post('id')); 
            if( !isset($id) ) {
                $message = 'id cannot be null';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $updateData = array();
                if($this->input->post('id')) {
                    $updateData['hasaccess'] = trim($this->input->post('hasaccess'));
                    $updateData['id'] = $id;
                    $updateData = array($updateData);
                }

                if($this->input->post('ids')) {
                    $ids = $this->input->post('ids');
                    if(is_array($ids)) {
                        foreach($ids as $val) {
                            $updateData1 = array(); 
                            $updateData1['id'] = $val;
                            if($this->input->post('giveaccess')) {
                                $updateData1['hasaccess'] = 1;
                            } else {
                                $updateData1['hasaccess'] = 0;
                            }
                            $updateData1['modifydate'] = date('Y-m-d H:i:s', time());
                            $updateData[] = $updateData1;
                        }
                    }
                }
                
                $data  = $updateData;
                $request = array(
                    'updateData'        => $updateData, 
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
            
                $this->customerclass->updateUserSecurity($request);
                $message = 'User Security updated.';
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
    * This function use for delete user security
    * 
    * @return json 
    */
    public function deleteUserSecurity() {

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
            
                //$this->customerclass->deleteUserSecurity($request);

                $message = 'User Security deleted.';
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
    * This function use for load Contact Security Audit log in uigrid
    * 
    * @return json 
    */
    public function loadAuditLog() {
        
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
             
                if (trim($this->input->get('contact')) != '') {
                    $params['al.contactid'] = $this->input->get('contact');
                }
                
                if (trim($this->input->get('function')) != '') {
                    $params['csf.functionname'] = $this->input->get('function');
                }
                
                if (trim($this->input->get('role')) != '') {
                    $params['c1.role'] = $this->input->get('role');
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
                $auditLogData = $this->customerclass->getAuditLog($size, $start, $field, $order, $fromdate, $todate, $params);
        
                $trows = $auditLogData['trows'];
                $data = $auditLogData['data'];
                
                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['dateadded'] = format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportSecurity() {
        
        $export_contactsecurity = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTACTSECURITY');
        if($export_contactsecurity != 1) {
            show_404();
        }
        
        $params = array();

        if (trim($this->input->get('contact')) != '') {
            $params['cs.contactid'] = $this->input->get('contact');
        }
        
        if (trim($this->input->get('function')) != '') {
            $params['csf.functionname'] = $this->input->get('function');
        }
        
        if (trim($this->input->get('role')) != '') {
            $params['c.role'] = $this->input->get('role');
        }

        //get customerid
        $params['c.customerid'] =$this->session->userdata('raptor_customerid');

        $data = $this->customerclass->getUserSecurityForExcel($params);

        $data_array = array();

        $heading = array('Contact Name', 'Role', 'Function', 'Description', 'Has Access');
        $this->load->library('excel');

        $name = 'settings';
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = $row['firstname'];
            $result[] = $row['role'];
            $result[] = $row['functionname'];
            $result[] = $row['description'];
            $result[] = $row['isactive'] == 1 ? 'Has access' : 'No access';

            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "contactsecurity.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Contact_security", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($name.'_contactsecurity.xls', $data);
    }
    
    /**
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportAuditLog() {

        $export_contactsecurity = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_CONTACTSECURITY_AUDITLOG');
        if($export_contactsecurity != 1) {
            show_404();
        }
         
        $params = array();

        if (trim($this->input->get('contact')) != '') {
            $params['al.contactid'] = $this->input->get('contact');
        }

        if (trim($this->input->get('function')) != '') {
            $params['csf.functionname'] = $this->input->get('function');
        }

        if (trim($this->input->get('role')) != '') {
            $params['c1.role'] = $this->input->get('role');
        }
        
        $fromdate = '';
        $todate = '';
        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        $data = $this->customerclass->getAuditLogForExcel($params, $fromdate, $todate);

        $data_array = array();

        $heading = array('Date', 'Contact Name', 'Role', 'Function', 'Description', 'Old Value', 'New Value', 'Edited By');
        $this->load->library('excel');

        $name = 'settings';
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = format_date($row['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = $row['firstname'];
            $result[] = $row['role'];
            $result[] = $row['functionname'];
            $result[] = $row['description'];
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
        
        $file_name = "auditlog.xls";
        
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
        force_download($name.'_auditlog.xls', $data);
    }
	
    /**
    * This function use for get user security
    * 
    * @return json 
    */
    public function getContactSecurityData() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {   

            $contactid = trim($this->input->post('contactid')); 
            if( !isset($contactid) ) {
                $message = 'contactid cannot be null';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $role = strtolower(trim($this->input->post('role')));
                $roleaccess = 'sitecontactaccess';
                if($role == 'sitefm') {
                    $roleaccess = 'fmaccess';
                } else if($role == 'site contact') {
                    $roleaccess = 'sitecontactaccess';
                } else if($role == 'master') {
                    $roleaccess = 'masteraccess';
                }
                
                $request = array(
                    'contactid'     => $contactid,
                    'roleaccess'    => $roleaccess,  
                    'customerid'    => $this->session->userdata('raptor_customerid')
                );
            
                $data = $this->customerclass->getContactSecurityData($request);
                
                //format data
                foreach($data['noaccess'] as $key=>$value) {
                    foreach($data['hasaccess'] as $key1=>$value1) {
                        if($value['id'] == $value1['id']) {
                            unset($data['noaccess'][$key]);
                        }
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
    * This function use for update user security
    * 
    * @return json 
    */
    public function saveContactSecurityData() {

        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {   

            $contactid = trim($this->input->post('contactid')); 
            if( !isset($contactid) ) {
                $message = 'contactid cannot be null';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $functionids = $this->input->post('functionids');

                if(!is_array($functionids)) {
                    $functionids = array();
                }
                
                $userSecurityData = $this->customerclass->getUserFunctionByParams(array('contactid' => $contactid), 'contactid, functionid');
                if(count($userSecurityData) == 0) {
                    if(count($functionids) > 0) {
                        $insertData = array();
                        foreach($functionids as $value) {
                            array_push($insertData, array(
                                    'functionid' => $value,
                                    'contactid' => $contactid,
                                    'createdate' => date('Y-m-d H:i:s', time()),
                                    'modifydate' => date('Y-m-d H:i:s', time()),
                                    'hasaccess' => 1
                                )
                            );
                        }
                        $request = array(
                            'insertData'        => $insertData,
                            'logged_contactid'  => $this->session->userdata('raptor_contactid')
                        );
                        $this->customerclass->insertUserSecurity($request);
                    }
                    
                } else {
                    $insertData = array();
                    foreach($functionids as $value) {
                        $userSecurityData = $this->customerclass->getUserFunctionByParams(array('contactid' => $contactid, 'functionid' => $value), 'contactid, functionid');
                        if(count($userSecurityData) == 0) {
                            array_push($insertData, array(
                                    'functionid' => $value,
                                    'contactid' => $contactid,
                                    'createdate' => date('Y-m-d H:i:s', time()),
                                    'modifydate' => date('Y-m-d H:i:s', time()),
                                    'hasaccess' => 1
                                )
                            );
                        }
                    }
                    
                    if(count($insertData) > 0) {
                        $request = array(
                            'insertData'        => $insertData,
                            'logged_contactid'  => $this->session->userdata('raptor_contactid')
                        );
                        $this->customerclass->insertUserSecurity($request);
                    }
                    
                    $userSecurityData = $this->customerclass->getUserFunctionByParams(array('contactid' => $contactid), 'id, contactid, functionid');
                    $updateData = array();
                    foreach($userSecurityData as $value) {
                        $hasaccess = 0;
                        if(in_array($value['functionid'], $functionids)) {
                            $hasaccess = 1;
                        }
                        array_push($updateData, array(
                            'id' => $value['id'],
                            'functionid' => $value['functionid'],
                            'contactid' => $value['contactid'],
                            'modifydate' => date('Y-m-d H:i:s', time()),
                            'hasaccess' => $hasaccess
                        ));
                    }
                    
                    $request = array(
                        'updateData'        => $updateData, 
                        'logged_contactid'  => $this->session->userdata('raptor_contactid')
                    );

                    $this->customerclass->updateUserSecurity($request);
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
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/settings/settings.index.js'),
            base_url('assets/js/settings/settings.portalsettings.js'),
            base_url('assets/js/settings/settings.portalauditlog.js')
        );
         
        
        $this->data['edit_portalsettings'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_PORTALSETTINGS');
        $this->data['export_portalsettings'] = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_PORTALSETTINGS');
        $this->data['customer_rulenames'] = $this->customerclass->getCustomerRuleNames();
 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Portal Setup')
                ->set_layout($this->layout)
                ->set('page_title', 'Portal Setup')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('settings/portalsetup', $this->data);
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
                
                $params['pal.customerid'] = $this->session->userdata('raptor_customerid');
                 
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
        
        $export_portalsettings = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_PORTALSETTINGS');
        if(!$export_portalsettings) {
            show_404();
        }
        
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
        
        $params['pal.customerid'] = $this->session->userdata('raptor_customerid');

         //get audit Log Data
        $auditLogData = $this->customerclass->getPortalAuditLog(NULL, 0, $field, $order, $fromdate, $todate, $params);
 
        $data = $auditLogData['data'];
  

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
                $customerid =$this->session->userdata('raptor_customerid');
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
        
        $export_portalsettings = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EXPORT_PORTALSETTINGS');
        if(!$export_portalsettings) {
            show_404();
        }
         
        $customerid = $this->session->userdata('raptor_customerid');
        //get User Security Data
        $filter = '';
        if (trim($this->input->get('filtertext')) != '') {
            $filter = $this->input->get('filtertext');
        }

        //get User Security Data
        $portalSettingsData = $this->customerclass->getPortalSettings($customerid, $filter); 
        
        $data_array = array();

        $heading = array('ID','Setting', 'Value', 'For Master', 'For Site FM', 'For Site Contact');
        $this->load->library('excel');

        $name = 'settings';
        
        
        
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
            
            $editaccess = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_PORTALSETTINGS');
            if(!$editaccess) {
                $message = 'You have not permission to edit.'; 
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
                

                $customerid = $this->session->userdata('raptor_customerid');
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
    
   
}