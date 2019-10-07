<?php 
/**
 * Keywordsetup Controller Class
 *
 * This is a Keywordsetup controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keywordsetup Controller Class
 *
 * This is a Keywordsetup controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Keywordsetup
 * @filesource          Keywordsetup.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Keywordsetup extends MY_Controller {

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
            base_url('assets/js/admin/admin.keywordsetup.js')
        );
        
      
        $this->data['states'] =$this->sharedclass->getStates(1);
        $this->data['contact_roles'] = $this->customerclass->getContactRole();
        $this->data['trades'] = $this->sharedclass->getTrades();
        $this->data['works'] = $this->sharedclass->getWorks();
        $this->data['subworks'] = $this->sharedclass->getSubWorks();
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Keyword Maintenance')
                ->set_layout($this->layout)
                ->set('page_title', 'Keyword Maintenance')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Keyword Maintenance', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/keywordsetup', $this->data);
    }

    /**
    * This function use for update weighting
    * 
    * @return json 
    */
    public function updateWeighting() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $keywordids = $this->input->post('ids');
            
            if( !is_array($keywordids) )
                $message = 'Keyword cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //get post data  
                $weighting = trim($this->input->post('weighting'));
               
                foreach($keywordids as $value) {
                    
                    $updateData = array(
                        'weighting' => $weighting
                    );

                    $request = array(
                        'id'                => $value,
                        'updateData'        => $updateData, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );
                    
                    $this->adminclass->updateKeywordWorks($request);
                }
               
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
    public function loadKeywords() {
        
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
                $field = 'kw.word';
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

                if (trim($this->input->get('trade')) != '') {
                    $params['t.id'] = $this->input->get('trade');
                }
                if (trim($this->input->get('works')) != '') {
                    $params['w.id'] = $this->input->get('works');
                }
                if (trim($this->input->get('subworks')) != '') {
                    $params['sw.id'] = $this->input->get('subworks');
                }

                /*if (is_array($this->input->get('role'))) {
                    $role = $this->input->get('role');
                    foreach ($role as $key => $value) {
                       $rolea[] = $value; 
                    }
                    $params['c.role'] = $rolea;
                } else {
                    $params['c.role'] = $this->input->get('role');
                }*/

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get contacts data
                $contactData = $this->adminclass->getKeywords($size, $start, $field, $order, $filter, $params);

                $trows  = $contactData['trows'];
                $data = $contactData['data'];

                /*//format data
                foreach($data as $key=>$value) {
                    $data[$key]['cp_invitesendtime'] = format_datetime($value['cp_invitesendtime'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                    if($value['last_login'] == NULL || $value['last_login'] ==0){
                         $data[$key]['last_login'] = '';
                    }
                    else{
                         $data[$key]['last_login'] = date(RAPTOR_DISPLAY_DATEFORMAT .' '. RAPTOR_DISPLAY_TIMEFORMAT, $value['last_login']);
                    }
                }*/
                
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
    public function exportKeywordWorks() {
        
        
        $order = 'asc';
        $field = 'kw.word';
        $filter = '';

        $params = array();

        if (trim($this->input->get('trade')) != '') {
            $params['t.id'] = $this->input->get('trade');
        }
        if (trim($this->input->get('works')) != '') {
            $params['w.id'] = $this->input->get('works');
        }
        if (trim($this->input->get('subworks')) != '') {
            $params['sw.id'] = $this->input->get('subworks');
        }
        
        $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;

        $keywordData = $this->adminclass->getKeywords(NULL, 0, $field, $order, $filter, $params);

        $data_array = array();

        $heading = array('Trade', 'Works', 'Sub-Works', 'Keyword', 'Weighting');
        $this->load->library('excel');

        //format data for excel
        foreach ($keywordData['data'] as $row)
        { 
            $result = array();
            
            $result[] = $row['se_trade_name'];
            $result[] = $row['se_works_name'];
            $result[] = $row['se_subworks_name'];
            $result[] = $row['word'];
            $result[] = $row['weighting'];
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "keywordsubworks.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Keyword Maintenance", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('keywordsubworks.xls', $data);
    }
    
        
    /**
    * @desc This function use for add/update keyword works
    * @param none
    * @return json 
    */
    public function addEditKeywordWorks() {
            
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
    public function deleteKeywordWorks() {

        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            //get post data 
            $id = $this->input->post('id');
            if( !isset($id) || $id == '' || $id == 0)
                $message = 'Id cannot be null.'; 
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {

                $request = array(
                    'id'                => $id,
                    'logged_contactid'  => $this->session->userdata('raptor_contactid')
                );
                
                //$this->adminclass->deleteKeywordWorks($request);
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