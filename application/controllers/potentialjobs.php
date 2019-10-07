<?php 
/**
 * My Potential Jobs Controller Class
 *
 * This is a My Potential Jobs controller class 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * My Potential Jobs Controller Class
 *
 * This is a My Potential Jobs controller class 
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            PotentialJobs
 * @filesource          PotentialJobs.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class PotentialJobs extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
 
        //  Load libraries 
        $this->load->library('job/JobClass');
        
    }
    
    /**
    * This function use for show My Jobs
    * 
    * @return void 
    */
    public function index()
    {
         
         $this->data['cssToLoad'] = array(  
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/jobs/jobs.potentialjobs.js'),
           
        );
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Potential Jobs')
                ->set_layout($this->layout)
                ->set('page_title', 'My Potential Jobs')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('jobs/potentialjobs', $this->data);
         
                 
    }
    
    /**
    * This function use for load Waiting Approval Jobs in uigrid
    * 
    * @return json 
    */
    public function loadWaitingApprovalJobs() {
        
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
                $field = 'leaddate';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
              
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get job data
                $jobDate = $this->jobclass->getWaitingApprovalLeadJobs($this->session->userdata('raptor_customerid'), $size, $start, $field, $order, $filter, $params);
               
                $trows = $jobDate['trows'];
                $data = $jobDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['leaddate'] = format_date($value['leaddate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['shortdescription'] = limitTexts($value['jobleaddescription'], 200);
                    //$data[$key]['material_estimate'] = format_amount($value['material_estimate_value']);
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
    * This function use for load Declined Jobs in uigrid
    * 
    * @return json 
    */
    public function loadDeclinedJobs() {
        
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
                $field = 'leaddate';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
             
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get data
                $jobDate = $this->jobclass->getDeclinedLeadJobs($this->session->userdata('raptor_customerid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $jobDate['trows'];
                $data = $jobDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['leaddate'] = format_date($value['leaddate'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['shortdescription'] = limitTexts($value['jobleaddescription'], 200);
                    //$data[$key]['material_estimate'] = format_amount($value['material_estimate_value']);
                 
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
     * this function use for download invoices in excel
     * 
     * @return excel
     */
    public function downloadExcel($status) {
 
        $data_array = array();
    
        $this->load->library('excel');
        $params = array();
        $order = 'desc';
        $field = 'leaddate';
        $filter = '';
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }
        
        $heading = array('Job Lead ID', 'Job Description', 'Site', 'Suburb', 'State', 'Technician', 'Date Added', 'Labour Est. (h)', 'Materials Est. ($)', 'Works Type');
        $jobDate = array();
        if ($status == "declined") {
            $data = $this->jobclass->getDeclinedLeadJobs($this->session->userdata('raptor_customerid'), NULL, 0, $field, $order, $filter, $params);
            $jobDate = $data['data'];
        } 
        elseif ($status == "waitingapproval") {
            $data = $this->jobclass->getWaitingApprovalLeadJobs($this->session->userdata('raptor_customerid'), NULL, 0, $field, $order, $filter, $params);
            $jobDate = $data['data'];
        }
        
        foreach ($jobDate as $row1) {
            $result = array();
            $result[] = $row1['joblead_id'];
            $result[] = $row1['jobleaddescription'];
            $result[] = $row1['siteline2'];
            $result[] = $row1['suburb'];
            $result[] = $row1['sitestate'];
            $result[] = $row1['leaduserid'];
             
            $result[] = format_date($row1['leaddate'], RAPTOR_DISPLAY_DATEFORMAT);
            
            $result[] = $row1['labour_estimate'];
            $result[] = $row1['material_estimate'];
            $result[] = $row1['se_works_name'];

            $data_array[] = $result;
        }

        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        //Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }


        $file_name = $status . "_jobs.xls";
        $this->excel->Exportexcel("My Potential Jobs", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/". $file_name);
 
        force_download($file_name, $data);
    }
    
     /**
     * this function use for update invoice final approval process
     * 
     * @return json
     */
    public function declineJobLead() {
 
 
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
            
            $joblead_id = $this->input->post('jobleadid'); 
            
            if(!isset($joblead_id) || $joblead_id == '')  {
                $message = 'Please Select Job';
            }
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $updateData = array(
                    'declineddate'  => date('Y-m-d H:i:s'),
                    'declinedby'    => $this->data['loggeduser']->contactid
                );
               $request = array(
                    'joblead_id'        => $joblead_id,
                    'updateData'        => $updateData,
                    'logged_contactid'  => $this->data['loggeduser']->contactid
                );
                $this->jobclass->updateLeadJob($request);   
                 
               
                $message = 'Potential Job Declined';
        
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