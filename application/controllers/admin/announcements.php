<?php 
/**
 * Announcements Controller Class
 *
 * This is a Announcements controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Announcements Controller Class
 *
 * This is a Announcements controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Announcements
 * @filesource          Announcements.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Announcements extends MY_Controller {

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
    * This function use for show announcements
    * 
    * @return json 
    */
    public function index() {
        
        
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
    
}