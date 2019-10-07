<?php 
/**
 * grids Controller Class
 *
 * This is a grids controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * grids Controller Class
 *
 * This is a grids controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            grids
 * @filesource          grids.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Grids extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();

        //Load custom Library
        $this->load->library('job/JobClass'); 
        $this->load->library('invoice/InvoiceClass'); 
      
    }

    /**
    * @desc This function use for show pre grids  for logged customer
    * @param none
    * @return void 
    */
    public function index()
    {
        
        //include custom css for this page
        $this->data['cssToLoad'] = array(
            base_url('plugins/uigrid/ui-grid-stable.min.css')
        );

        //include custom js for this page
        $this->data['jsToLoad'] = array(
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/bootbox.min.js'),
            base_url('assets/js/grids/grids.index.js')
        );
                
       
            
        //Load Grid data with count
        $this->data['jobgrid'] = $this->jobclass->getJobCountByStage($this->data['loggeduser']->contactid);
        $this->data['quotegrid'] = $this->jobclass->getQuoteCountByStage($this->data['loggeduser']->contactid);
        $this->data['invoicegrid'] = $this->invoiceclass->getInvoiceCountByStage($this->data['loggeduser']->contactid);
    

         //Load View in template file
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Grids')
            ->set_layout($this->layout)
            ->set('page_title', 'Grids')
            ->set('page_sub_title', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('grids/index', $this->data);
    }
  
}