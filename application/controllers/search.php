<?php 
/**
 * Search Controller Class
 *
 * This is a Search class for display Search information 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Search Controller Class
 *
 * This is a Search class for display Search information 
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Search
 * @filesource          Search.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class Search extends MY_Controller {
 
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
    * This function use for show full search form
    * 
    * @return void 
    */
    public function index()
    {
        $this->data['cssToLoad'] = array(
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/timepicker/bootstrap-timepicker.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'), 
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/search/search.fullsearch.js') 
        );
        
        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['jobstages'] = $this->jobclass->getJobStageForSearch(1);
         
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Full Search')
            ->set_layout($this->layout)
            ->set('page_title', 'Full Search')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Full Search', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('search/index', $this->data);

    }
    
     
    /**
    * This function use for show Quick search form
    * 
    * @return void 
    */
    public function quick()
    {
        
        $this->data['cssToLoad'] = array(  
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array( 
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/search/search.quicksearch.js') 
        );
        $searchtext= $this->input->get_post('searchtext'); 
        $this->data['searchtext'] = $searchtext;
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Quick Search')
            ->set_layout($this->layout)
            ->set('page_title', 'Quick Search')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Quick Search', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('search/quick-search', $this->data);
    }
      
}