<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Project: Raptor
 * Package: CI
 * Subpackage: Controller
 * File: techmap.php
 * Description: This is a tech map  controller class
 * Created by : Prakash Saud <saud.prakash@gmail.com>
 *
 */
class Techmap extends MY_Controller {

     
    function __construct(){
        parent::__construct();
        
        //  Load libraries 
        $this->load->library('job/JobClass');
        
        
        //$this->load->model('budgets_model'); 
        $this->load->helper('getcenterlatlong');
		
    }
 
    /**
    * @desc This function index show jobs loccation with markers on the map
    * @param none
    * @return none 

    */ 
    public function index() 
    {
       
            
             
        $this->data['states'] = $this->sharedclass->getStates(1);

        /*	load css required for the techmap page	*/
        $this->data['cssToLoad'] = array(  
            base_url('plugins/iCheck/square/grey.css'),
            base_url('plugins/select2/select2.min.css'),
            base_url('plugins/datepicker/datepicker3.css')
        );

        $this->data['jsToLoad'] = array(
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
            base_url('plugins/iCheck/icheck.min.js'),
            base_url('plugins/select2/select2.full.min.js'), 
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('assets/js/techmap/techmap.index.js'),
        );

        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Tech Map')
            ->set_layout($this->layout)
            ->set('page_title', 'Tech Map')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Tech Map', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('techmap/index', $this->data);

         
    }
    
    
      /**
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function loadMapData() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $contactId = $this->session->userdata('raptor_contactid');
            $status = trim($this->input->get('status')); 
            $selectedState = trim($this->input->get('fstate')); 
            $fromDate = trim($this->input->get('fromdate')); 
            $toDate = trim($this->input->get('todate'));
            $adhocs = trim($this->input->get('showadhocjobs'));
            $contracts = trim($this->input->get('showcontractjobs'));
            if($fromDate != ''){
                $fromDate = to_mysql_date($fromDate, RAPTOR_DISPLAY_DATEFORMAT);
            }
            
            if($toDate != ''){
                $toDate = to_mysql_date($toDate, RAPTOR_DISPLAY_DATEFORMAT);
            }
             
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $data['jobs'] = $this->jobclass->getTechMapJobs($contactId, $status, $selectedState, $fromDate, $toDate, $adhocs, $contracts);

                if($this->input->get('showtechnicians')){
                    $data['techs'] = $this->jobclass->getJobsTecList($data['jobs']);
                }
                else{
                    $data['techs'] = array();
                }

                $data['centerlatlong']= getCenterLatLong($data['jobs']);
                
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

/* End of file techmap.php*/
/* Location: ./application/controller/techmap.php */