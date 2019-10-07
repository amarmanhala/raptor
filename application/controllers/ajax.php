<?php 
/**
 * ajax Controller Class
 *
 * This is a ajax controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ajax Controller Class
 *
 * This is a ajax controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Search
 * @filesource          Search.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Ajax extends CI_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in())
        {
                //redirect them to the login page
            return;
        }
        $this->config->load('raptor_config');
        
        //Load custom library Classes
        $this->load->library('shared/SharedClass');
        $this->load->library('customer/CustomerClass');
         
        $this->data['loggeduser'] = $this->ion_auth->user()->row(); 
        $this->ContactRules=$this->sharedclass->getCustomerRules($this->data['loggeduser']->customerid, $this->session->userdata('raptor_role'));

    }
    
    /**
     * load postcode for auto complete
     * 
     * @param string $q
     */
    public function loadNavigationCounter() {

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
                $data = $this->sharedclass->getMenuCounterValue($this->session->userdata('raptor_contactid'));
               
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
     * load postcode for auto complete
     * 
     * @param string $q
     */
    public function loadPostCode() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $searchKeyword = trim($this->input->get('search'));
            
            //search type (Suburb/postcode)
            $type = trim($this->input->get('type'));
            
            if( !isset($searchKeyword) )
                $message = 'Search Keyword cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->sharedclass->getSuburbPostCode($searchKeyword, $type);
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
    * @desc This function use for getting subworks for selected work_id
    * @param 
    * @return json data 
    */
    public function loadTradeWorks() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $tradeid = $this->input->get('tradeid');
            
            if( !isset($tradeid) )
                $message = 'Trade cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->sharedclass->getTradeWork($tradeid);
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
    * @desc This function use for getting subworks for selected work_id
    * @param 
    * @return json data 
    */
    public function loadSubWorks() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $workid = $this->input->get('workid');
            
            if( !isset($workid) )
                $message = 'work cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->sharedclass->getWorkSubWork($workid);
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
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function loadSiteSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $query = trim($this->input->get('search')); 
            $state = trim($this->input->get('state')); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $sql_w = '';
                if($state != ''){
                    $sql_w = " AND a.sitestate = '".$state."' ";
                }
                $data = $this->customerclass->getCustomerSiteAddress($this->session->userdata('raptor_customerid'), $query, '', FALSE, $sql_w);
                 
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
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function loadAssetSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $query = trim($this->input->get('search')); 
            $asset_categoryid = trim($this->input->get('asset_categoryid')); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $this->load->library('asset/AssetClass');
                $data = $this->assetclass->getAssetSearch($this->session->userdata('raptor_customerid'), $asset_categoryid, $query);
                 
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
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function dontShowAnnouncement() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $messageid = trim($this->input->post('messageid')); 
            $chk = trim($this->input->post('chk')); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $messageContactData = array(
                    'messageid' => $messageid,
                    'contactid' => $this->session->userdata('raptor_contactid')
                );
                if($chk == 1){
                    $this->sharedclass->InsertMessageContact($messageContactData);
                }
                else{
                    $this->sharedclass->DeleteMessageContact($messageContactData);
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
    * This function use for load editlogs in uigrid
    * 
    * @return json 
    */
    public function loadEditLogs() {
        
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
                $order = 'desc';
                $field = 'editdate';
                $filter = '';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }

                if ($this->input->get('fieldname')) {
                    $params['fieldname'] = $this->input->get('fieldname');
                }

                $recordid = $this->input->get("recordid");
                $table = $this->input->get("table");

                //intialize start page for uigrid
                $start = ($page - 1) * $size;

                //get edit log data
                $editLogdata = $this->sharedclass->getEditLogs($table, $recordid, $size, $start, $field, $order, $params);

                $trows  = $editLogdata['trows'];
                $data = $editLogdata['data'];
         
                //intialize array of fields for uigrid
                foreach ($data as $key => $value) {
                    $data[$key]['editdate'] = format_datetime($value['editdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
                     
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
    * @desc This function use for load marketing content
    * @param none
    * @return json 
    */ 
    public function getMarketingContent()
    {
        //check ajax request
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
            //search item type
            $isSuccess = TRUE; 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
            
            if( $isSuccess ) {
                $positionThird = trim($this->input->post('positionThird'));
                
                $params = array(
                    'positionThird' => $positionThird,
                    'customerid'    => $this->data['loggeduser']->customerid
                );
                
                $marketingMessages = $this->sharedclass->getMarketingMessages();
                $marketingContent = $this->sharedclass->getMarketingContent($params);
                
                $data = array(
                    'marketing_content'     => $marketingContent,
                    'marketing_messages'    => $marketingMessages
                );
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