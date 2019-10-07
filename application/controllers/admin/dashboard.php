<?php 
/**
 * Dashboard Controller Class
 *
 * This is a Dashboard controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller Class
 *
 * This is a Dashboard controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Dashboard
 * @filesource          Dashboard.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Dashboard extends MY_Controller {

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

    public function index()
    {
       
        
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Admin Dashboard')
            ->set_layout($this->layout)
            ->set('page_title', 'Dashboard')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Admin', '')
            ->set_breadcrumb('Dashboard', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('admin/index', $this->data);
        
        
        
    }
    
}