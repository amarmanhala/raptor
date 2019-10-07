<?php
/**
 * My Controller Class
 *
 * This is a My Controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once ($_SERVER['DOCUMENT_ROOT'] . "/dcfm.inc");
require_once(APPPATH . "libraries/SuccessClass.php");
require_once(APPPATH . "libraries/LogClass.php");  
/**
 * My Controller Class
 *
 *  This is a My Controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Controllers
 * @filesource          MY_Controller.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class MY_Controller extends CI_Controller {

  /**
     * intialize default layout name 
     * @var string 
     */
    protected $layout = 'main_layout';
 
    /** 
      * Create log for request operation
      * 
      * @var $logClass  
     */
    protected $logClass;
    
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
        
            // if not already at signin or fogotpassword forward to signin page
            if( end($this->uri->segments) != 'activate' && end($this->uri->segments) != 'resetpassword' && end($this->uri->segments) != 'forgotpassword' && $this->uri->segment($this->uri->total_segments()-1) != 'login' && end($this->uri->segments) != 'login' && end($this->uri->segments) != 'logout'){
                redirect('auth/login/' . urlencode(urlencode(current_url())), 'refresh');
            }
            else{
                //redirect them to the login page
                redirect('auth/login', 'refresh');
            }
            
        }
        
        $this->output->nocache();
        
        //Load custom library Classes
        $this->load->library('shared/SharedClass');
        $this->load->library('customer/CustomerClass');
        $this->load->library('admin/AdminClass');
        
        
        $this->data['loggeduser'] = $this->ion_auth->user()->row(); 
        $this->config->load('raptor_config');
        
        $controller = $this->uri->segment(1);
        $function = $this->uri->segment(2);
        
         //Log Class object 
        $this->logClass= new LogClass('jobtracker', $controller . ' - ' .$function, $this->session->userdata('raptor_email'));

 
        
         //get sidebar navigation according to contact accesslevel
        if($this->data['loggeduser']->israptoradmin == 1){
            $navigation = $this->sharedclass->getAdminNavigation();
        }
        else{
            $navigation = $this->customerclass->getContactMenuModule($this->session->userdata('raptor_contactid'));
            if(count($navigation) == 0){
                 
                $navigation = $this->sharedclass->getNavigation($this->session->userdata('raptor_customerid'),$this->session->userdata('raptor_role'));
            }
        }
        
        $this->data['navigation'] = $navigation;
        $this->data['menucounter'] = $this->sharedclass->getMenuCounterValue($this->session->userdata('raptor_contactid'));
         
        //default access level
        $access1 = false;
        $access2 = false;
        
        //check navigation is active or not
        if ($function == 'quick' || $function == 'jobdetail' || $controller == 'EditableReport') {
            $access1 = true;
            $access2 = true;
        } else {
            foreach ($navigation as $key => $value) {
                $url1 = $value['url1'];
                $url2= $value['url2'];
                foreach($navigation as $key=>$value) {


                    if($function != '') {
                        if(($url1 == $controller && $url2 == $function) || $url1 == $controller) {
                            $access2 = true;
                            $access1 = true;
                            break;
                        }
                    }
                    else{
                        if($url1 == $controller) {
                            $access1 = true;
                            $access2 = true;
                        }

                    }
                }

            }
        }
        if(!$this->data['loggeduser']->israptoradmin){
            if (!$this->input->is_ajax_request()) 
            {
                if($access1 == false && $access2 == false) {
                     show_404();
                }
            }
        }
        
        

        $route = $controller;
        if($function != ''){
            $route = $route .'/'. $function;
        }
        
        $this->data['help'] = $this->adminclass->getHelpByRoute($route);   
        $this->data['announcement'] = array();
        if($this->session->flashdata('showannouncement')){
            
            $this->load->library('user_agent');
            $this->data['announcement'] = $this->sharedclass->getAnnouncement($this->session->userdata('raptor_contactid'));
            $announcement = $this->sharedclass->getBrowserAnnouncement($this->session->userdata('raptor_contactid'), $this->agent->browser());
             
            $announcementRow = array();
            foreach ($announcement as $key => $value) {
                if($value['browser_version'] <= $this->agent->version() && $value['browser'] != ''){
                    $announcementRow = $value;
                }
            }
          
            $this->data['browserannouncement'] = $announcementRow;
         }
        
        $this->data['banner_array'] = $this->customerclass->getCustomerById($this->session->userdata('raptor_customerid'));     
        $this->data['banner_array']['active'] = 0;
        $this->data['banner_array']['logo'] = '';
        $this->data['banner_array']['header'] = '';
        $this->data['banner_array']['footer'] = '';
                
        $this->data['ContactRules']=$this->sharedclass->getCustomerRules($this->data['loggeduser']->customerid, $this->session->userdata('raptor_role'));
 
        
//        $this->load->helper('url');
//$this->load->library('user_agent');
//echo $this->agent->version();
//echo $this->agent->browser();exit;
 
//        $this->load->library('BrowserClass');
//        $user_os = $this->browserclass->getOS();
//        $user_browser = $this->browserclass->getBrowser();exit;
    }
  
}