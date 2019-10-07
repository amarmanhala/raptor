<?php 
/**
 * Site Controller Class
 *
 * This is a Site controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Site Controller Class
 *
 * This is a Site controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Site
 * @filesource          Site.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Site extends MY_Controller {

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
     * Site Module
     * 
     * @return void
     */
    public function index()
    {

                
        $this->data['site_module'] = $this->adminclass->getSiteModule();
        
        if(!$this->input->post('sitemodule'))
        {
            
                    //render
                $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Site Status')
                    ->set_layout($this->layout)
                    ->set('page_title', 'Site Status')
                    ->set('page_sub_title', '')
                    ->set_breadcrumb('Admin', '')
                    ->set_breadcrumb('Site Status', '')
                    ->set_partial('page_header', 'shared/page_header')
                    ->set_partial('header', 'shared/header')
                    ->set_partial('navigation', 'shared/navigation')
                    ->set_partial('footer', 'shared/footer')
                    ->build('admin/sitemodule', $this->data);
        }
        else
        {
            $insertData = array(
                'sitestatus'        => (int)$this->input->post('sitestatus'),
                'sitemessagestatus' => (int)$this->input->post('sitemessagestatus'),
                'sitemessage'       => trim($this->input->post('sitemessage')),
                'sitemessagedate'   => date('Y-m-d H:i:s')
            );
            
             

            //check Add/Edit Mode
             if(count($this->data['site_module'])>0){
                $request = array(
                    'siteid'            => $this->data['site_module']['id'],
                    'updateData'        => $insertData,
                    'logged_contactid'  => $this->data['loggeduser']->contactid
                );

                $this->adminclass->updateSiteModule($request);

            }
            else{

                $request = array(
                    'insertData'        => $insertData, 
                    'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                );
                $this->adminclass->insertSiteModule($request);


            }
             
            
            $this->session->set_flashdata('success', "Site Module Updated.");
            redirect('admin/site');
        }
    }
    
}