<?php 
/**
 * Helps Controller Class
 *
 * This is a Helps controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helps Controller Class
 *
 * This is a Helps controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Helps
 * @filesource          Helps.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Helps extends MY_Controller {

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
    
    public function index() {
        
        
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
            redirect('admin/helps/edithelp/'.$response['helpid']);
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
    
}