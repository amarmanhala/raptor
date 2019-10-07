<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Controller {

	protected $layout = 'main_layout';
	var $user;
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('users_model');
		
		//$this->user = $this->users_model->get_user($this->ion_auth->user()->row()->customerid);
		
		$this->data['loggeduser'] = $this->ion_auth->user()->row(); 
	 
		//print_r($this->ion_auth->user()->row());exit;
		//print_r($this->ion_auth->user()->row()->contactid);exit;
		//echo $this->session->userdata('user_id');exit;
	}

	public function index()
	{
		$this->data['sidebaractive'] = 'invoices'; 
		$this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Invoices')
                ->set_layout($this->layout)
                ->set('page_title', 'Invoices')
                ->set('page_sub_title', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('invoices/index', $this->data);
                
                ////var_dump($this->db->last_query());
	}
}