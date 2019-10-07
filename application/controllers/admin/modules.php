<?php 
/**
 * Modules Controller Class
 *
 * This is a Modules controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modules Controller Class
 *
 * This is a Modules controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Modules
 * @filesource          Modules.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Modules extends MY_Controller {

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
    * This function use for show modules
    * 
    * @return void 
    */
    public function index() {
        
        
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
    
}