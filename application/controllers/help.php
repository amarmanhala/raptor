<?php 
/**
 * Help Controller Class
 *
 * This is a Help controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Help Controller Class
 *
 * This is a Help controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Help
 * @filesource          Help.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Help extends CI_Controller {

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
        $this->load->library('admin/AdminClass');
        
        $this->data['loggeduser'] = $this->ion_auth->user()->row(); 
        $this->ContactRules=$this->sharedclass->getCustomerRules($this->data['loggeduser']->customerid, $this->session->userdata('raptor_role'));

    }
    
    public function index($helpid)
    {
        
        $this->data['help'] = $this->adminclass->getHelpById($helpid); 
        $this->data['help_links'] = $this->adminclass->getHelpLinks($helpid); 
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Help')
                ->set('page_title', 'Help')
                ->build('help/index', $this->data);
        
    }
    
    /**
    * This function use for update Customer Job Document
    * 
    * @return void
    */
    public function submitFeedback() {

        
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
                //get post data 
                $helpid= $this->input->post('helpid');
                $rating = trim($this->input->post('rating'));
                    
                $updateData = array(
                    'contactid'     => $this->session->userdata('raptor_contactid'),
                    'dateadded'     => date('Y-m-d H:i:s'),
                    'helpid'        => $helpid,
                    'rating'        => $rating
                );
               
                $this->adminclass->SubmitHelpFeedback($updateData);
                
                $message = 'Thanks for submiting your feedback.';
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