<?php 
/**
 * asset Controller Class
 *
 * This is a asset controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * asset Controller Class
 *
 * This is a asset controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            asset
 * @filesource          asset.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Asset extends MY_Controller {

     /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
 
        //  Load libraries 
        $this->load->library('asset/AssetClass');
        $this->load->library('document/DocumentClass');
        $this->load->library('contractor/ContractorClass');
        $contactid =$this->session->userdata('raptor_contactid');
        $this->data['ADD_ASSET'] = $this->sharedclass->getFunctionalSecurityAccess($contactid,'ADD_ASSET');
        $this->data['EDIT_ASSET'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid,'EDIT_ASSET');
  

    }
 
    /**
     * this function use for show assets grid
     * @return void
     */
    public function index()
    {

        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['categories'] = $this->assetclass->getCategories($customerid);
        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['sites'] = $this->customerclass->getCustomerSiteAddress($customerid); 
        $this->data['contracts'] = $this->contractorclass->getCustomerContracts($customerid);
         
        $this->data['conServiceType'] = $this->contractorclass->getServiceTypes();
        $this->data['checklists'] = $this->assetclass->getChecklists();
        $this->data['joblists'] = $this->assetclass->getJoblists($customerid);
        $this->data['assetActivities'] = $this->assetclass->getAssetActivities();
        
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );
 

        $this->data['jsToLoad'] = array( 
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/asset/asset.index.js') 
        );

          $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Assets')
                ->set_layout($this->layout)
                ->set('page_title', 'My Assets')
                ->set('page_sub_title', '')
                ->set_breadcrumb('My Assets', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('asset/index', $this->data);

    }
    
    /**
    * This function use for load Asset in uigrid
    * 
    * @return json 
    */
    public function loadAssets() {
        
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
                $field = 'assetid';
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
             
                if (trim($this->input->get('state')) != '') {
                    $params['ad.sitestate'] = $this->input->get('state');
                }
                
                if (trim($this->input->get('categoryid')) != '') {
                    $params['a.category_id'] = $this->input->get('categoryid');
                }
                if (trim($this->input->get('labelid')) != '') {
                    $params['a.labelid'] = $this->input->get('labelid');
                }
                if (trim($this->input->get('contractid')) != '') {
                    $params['ac.contractid'] = $this->input->get('contractid');
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $assetDate = $this->assetclass->getAssetData($this->session->userdata('raptor_customerid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $assetDate['trows'];
                $data = $assetDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['purchase_date'] = format_date($value['purchase_date'],RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['edit'] = (boolean)$this->data['EDIT_ASSET'];
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
     * downoad assets
     */
    public function exportAssets() 
    {
         
         
        $params = array();
        $order = 'asc';
        $field = 'assetid';
        $filter = '';
        if($this->input->get_post('state')!=null)
        {
            $params['ad.sitestate'] =$this->input->get_post('state');
        }
        if($this->input->get_post('categoryid')!=null)
        {
            $params['a.category_id'] =trim($this->input->post('categoryid'));
        }
        if(trim($this->input->get('labelid')) != '') {
            $params['a.labelid'] = $this->input->get('labelid');
        }
        if (trim($this->input->get('contractid')) != '') {
            $params['ac.contractid'] = $this->input->get('contractid');
        }
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }
        
        $assetDate = $this->assetclass->getAssetData($this->session->userdata('raptor_customerid'), NULL, 0, $field, $order, $filter, $params);
       
        
        $dataArray = array();
        $heading = array('Street','Suburb','State','Location','Category','Manufacturer','Service Tag','Purchased');
        $this->load->library('excel');
 
        foreach($assetDate['data'] as $row1)
        { 
            $result = array();
  
            $result[]=$row1['siteline2'];
            $result[]=$row1['sitesuburb'];
            $result[] =$row1['sitestate'];
            $result[]=$row1['location'];
            $result[]=$row1['category_name'];
            $result[]=$row1['manufacturer'];
            $result[]=$row1['service_tag'];
            $result[]=format_date($row1['purchase_date'],RAPTOR_DISPLAY_DATEFORMAT);

            $dataArray[]=$result;
        }

        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="my_asset.xls";
        
         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); 
        
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->Exportexcel("My Assets", $dir, $file_name, $heading, $dataArray);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('my_asset.xls', $data);


    }
    
    /**
     * this function use for show add assets data form
     * 
     * @return void
     */
    public function add() {
      
        if(!isset($this->data['ADD_ASSET']) || !$this->data['ADD_ASSET']){
            redirect('asset/');
        }
       
        $customerID =$this->session->userdata('raptor_customerid');
 
       
  
        $this->form_validation->set_rules('asset_form_post', 'Data', 'required');
        $this->form_validation->set_rules('labelid', 'Site Address', 'trim|required');
        //$this->form_validation->set_rules('location_id', 'Location', 'trim|required');
        $this->form_validation->set_rules('manufacturer', 'Manufacturer', 'trim|required');
        $this->form_validation->set_rules('purchase_date', 'Acquired', 'trim|required');
        $contactid =$this->session->userdata('raptor_contactid');
        if($this->form_validation->run() == FALSE)
        {
            
            $this->data['ADD_ASSET_LOCATION'] = $this->sharedclass->getFunctionalSecurityAccess($contactid,'ADD_ASSET_LOCATION');
            $this->data['EDIT_ASSET_LOCATION'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid,'EDIT_ASSET_LOCATION');
            $this->data['ADD_ASSET_SUBLOCATION'] = $this->sharedclass->getFunctionalSecurityAccess($contactid,'ADD_ASSET_SUBLOCATION');
            $this->data['EDIT_ASSET_SUBLOCATION'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid,'EDIT_ASSET_SUBLOCATION');

            $this->data['site_address'] = $this->assetclass->getSiteAddress($customerID);
            $this->data['location'] = $this->assetclass->getAssetLocations($this->input->post('labelid'));
            $this->data['sublocation'] = $this->assetclass->getAssetSubLocations($this->input->post('location_id'));
            $this->data['asset_category'] = $this->assetclass->getAssetCategories();
            $this->data['asset_ohsrisk'] = $this->assetclass->getOHSRisk();
            $this->data['frequency'] = $this->assetclass->getFrequency();

            $this->data['asset_doctype'] = $this->assetclass->getAssetDocType();

            $asset_custom_labels = array(
                'custom1'=>'',
                'custom2'=>'',
                'custom3'=>'',
                'custom4'=>'',
                'custom5'=>''
            );

            $this->data['asset_categoryname'] = '';
            if($this->input->post('category_id')!='') {
                $cat_data = $this->assetclass->getAssetCategories($this->input->post('category_id'));
                if(count($cat_data)>0) {
                    $asset_custom_labels['custom1'] = $cat_data[0]['customlabel1'] == NULL || $cat_data[0]['customlabel1'] == 'null' ? '' : $cat_data[0]['customlabel1'];
                    $asset_custom_labels['custom2'] = $cat_data[0]['customlabel2'] == NULL || $cat_data[0]['customlabel2'] == 'null' ? '' : $cat_data[0]['customlabel2'];
                    $asset_custom_labels['custom3'] = $cat_data[0]['customlabel3'] == NULL || $cat_data[0]['customlabel3'] == 'null'? '' : $cat_data[0]['customlabel3'];
                    $asset_custom_labels['custom4'] = $cat_data[0]['customlabel4'] == NULL || $cat_data[0]['customlabel4'] == 'null' ? '' : $cat_data[0]['customlabel4'];
                    $asset_custom_labels['custom5'] = $cat_data[0]['customlabel5'] == NULL || $cat_data[0]['customlabel5'] == 'null' ? '' : $cat_data[0]['customlabel5'];
                    $this->data['asset_categoryname'] = $cat_data[0]['category_name'];
                }
            }



            $this->data['asset_custom_labels'] = $asset_custom_labels;  
        
            $this->data['cssToLoad'] = array(
                base_url('plugins/select2/select2.min.css'),
                base_url('plugins/datepicker/datepicker3.css') 
            );
            
            $this->data['jsToLoad'] = array(
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
                base_url('plugins/input-mask/jquery.inputmask.js'),
                base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                base_url('plugins/jquery-validator/jquery.validate.min.js'),
                base_url('plugins/jquery-validator/validation.js'),
                base_url('plugins/datepicker/bootstrap-datepicker.js'), 
                base_url('plugins/select2/select2.full.min.js'),
                base_url('assets/js/jquery.form.js'),
                base_url('assets/js/asset/assets.js')
                
            );
            
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Add New - Assets')
                ->set_layout($this->layout)
                ->set('page_title', 'My Assets')
                ->set('page_sub_title', 'Add New Asset')
                ->set_breadcrumb('My Assets', site_url('asset'))
                ->set_breadcrumb('Add New Asset', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('asset/addasset', $this->data);
            
		
        }
        else
        {
            $asset_form_post = $this->input->post('asset_form_post');
            $warranty_expiry_date = $this->input->post('warranty_expiry_date');
            if(trim($warranty_expiry_date)!='') {
                $warranty_expiry_date = to_mysql_date($warranty_expiry_date, RAPTOR_DISPLAY_DATEFORMAT);

            } else {
                $warranty_expiry_date = null;
            }
            $disposal_date = $this->input->post('disposal_date');
            if(trim($disposal_date)!='') {
                $disposal_date = to_mysql_date($disposal_date, RAPTOR_DISPLAY_DATEFORMAT);
 
            } else {
                $disposal_date = null;
            }
            $purchase_date = $this->input->post('purchase_date');
            if(trim($purchase_date)!='') {
                $purchase_date = to_mysql_date($purchase_date, RAPTOR_DISPLAY_DATEFORMAT);
            } else {
                $purchase_date = null;
            }
	 		
            $insertData = array(
                'customerid'            => $this->session->userdata('raptor_customerid'),
                'custom1'               => trim($this->input->post('custom1')),
                'custom2'               => trim($this->input->post('custom2')),
                'custom3'               => trim($this->input->post('custom3')),
                'custom4'               => trim($this->input->post('custom4')),
                'custom5'               => trim($this->input->post('custom5')),
                'purchase_date'         => $purchase_date,
                'disposal_date'         => $disposal_date,
                'warranty_expiry_date'  => $warranty_expiry_date,
                'category_id'           => (int)trim($this->input->post('category_id')),
                'category_name'         => trim($this->input->post('category_name')),
                'service_tag'           => trim($this->input->post('service_tag')),
                'serial_no'             => trim($this->input->post('serial_no')),
                'model'                 => trim($this->input->post('model')),
                'manufacturer'          => trim($this->input->post('manufacturer')),
                'sublocation_id'        => (int)trim($this->input->post('sublocation_id')),
                'sublocation_text'      => trim($this->input->post('sublocation_text')),
                'location_id'           => (int)trim($this->input->post('location_id')),
                'location_text'         => trim($this->input->post('location_text')),
                'site_address'          => trim($this->input->post('site_address')),
                'labelid'               => (int)trim($this->input->post('labelid')),
                'latitude_decimal'      => (float)trim($this->input->post('latitude_decimal')),
                'longitude_decimal'     => (float)trim($this->input->post('longitude_decimal')) 
            );
		 	 
            $request = array(
                'insertData'        => $insertData,
                'logged_contactid'  => $this->data['loggeduser']->contactid
            );
            $response = $this->assetclass->insertAsset($request); 
                
            $assetid = $response['assetid'];			
 
            $this->session->set_flashdata('success', "Asset Data Successfully Added.");
            redirect('asset/edit/'.$assetid);
        } 
    }
    
     /**
     * this function use for show edit assets data form
     * 
     * @return void
     */
    public function edit($assetid) {
        
        if(!isset($this->data['EDIT_ASSET']) || !$this->data['EDIT_ASSET']){
            redirect('asset');
        }
        $customerID =$this->session->userdata('raptor_customerid');
        $this->data['asset']=$this->assetclass->getAssetById($assetid);
 	 
        if(count($this->data['asset'])==0){
            show_404();
        }
        if($this->data['asset']['customerid']!=$customerID){
            show_404();
        }
        
         $contactid =$this->session->userdata('raptor_contactid');

        $diff = abs(strtotime(date('Y-m-d')) - strtotime($this->data['asset']['purchase_date']));
 
        $days =floor($diff / (60*60*24)); //floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
         
        $this->data['asset']['current_age'] = number_format($days/365, 2);
        
        
        $this->form_validation->set_rules('asset_form_post', 'Data', 'required');
        if( $this->input->post('asset_form_post') == "1") {
        
            $this->form_validation->set_rules('labelid', 'Site Address', 'trim|required');
            //$this->form_validation->set_rules('location_id', 'Location', 'trim|required');
            $this->form_validation->set_rules('manufacturer', 'Manufacturer', 'trim|required');
            $this->form_validation->set_rules('purchase_date', 'Acquired', 'trim|required');
        }
        if($this->form_validation->run() == FALSE)
        {
			
            $this->data['ADD_ASSET_LOCATION'] = $this->sharedclass->getFunctionalSecurityAccess($contactid,'ADD_ASSET_LOCATION');
            $this->data['EDIT_ASSET_LOCATION'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid,'EDIT_ASSET_LOCATION');
            $this->data['ADD_ASSET_SUBLOCATION'] = $this->sharedclass->getFunctionalSecurityAccess($contactid,'ADD_ASSET_SUBLOCATION');
            $this->data['EDIT_ASSET_SUBLOCATION'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid,'EDIT_ASSET_SUBLOCATION');
        
            $this->data['editlog_tablename'] = 'asset';
            $this->data['editlog_recordid'] = $assetid;
            $this->data['editlogfieldname'] = $this->sharedclass->getEditLogFieldNames($assetid, 'asset');
            
            $this->data['site_address'] = $this->assetclass->getSiteAddress($customerID);
            $this->data['location'] = $this->assetclass->getAssetLocations($this->data['asset']['labelid']);
            $this->data['sublocation'] = $this->assetclass->getAssetSubLocations($this->data['asset']['location_id']);
            $this->data['asset_category'] = $this->assetclass->getAssetCategories();
            $this->data['asset_ohsrisk'] = $this->assetclass->getOHSRisk();

            $this->data['depreciation_methods'] = $this->assetclass->getAssetDepmethods();
            $this->data['costcentres'] = $this->customerclass->getCustomerCostCentre($customerID);
            $this->data['assetaccounts'] = $this->customerclass->getCustomerGLChart($customerID, 'A');
            $this->data['expenseaccounts'] = $this->customerclass->getCustomerGLChart($customerID, 'E');
            
            $this->data['frequency'] = $this->assetclass->getFrequency();

            $this->data['asset_doctype'] = $this->assetclass->getAssetDocType();
 
            $this->data['asset_document'] = $this->assetclass->getAssetDocument($assetid);
            $this->data['asset_history_grid'] = $this->assetclass->getAssetHistory($assetid);
            $this->data['asset_activity'] = $this->assetclass->getActivityCategory($assetid);

            $this->data['asset_conditions'] = $this->assetclass->getAssetConditions($customerID);

            $asset_custom_labels = array(
                                        'custom1'=>'',
                                        'custom2'=>'',
                                        'custom3'=>'',
                                        'custom4'=>'',
                                        'custom5'=>''
                        );

            $this->data['asset_categoryname'] = '';
            if($this->data['asset']['category_id']!='') {
                $cat_data = $this->assetclass->getAssetCategories($this->data['asset']['category_id']);
                if(count($cat_data)>0) {
                    $asset_custom_labels['custom1'] = $cat_data[0]['customlabel1'] == NULL || $cat_data[0]['customlabel1'] == 'null' ? '' : $cat_data[0]['customlabel1'];
                    $asset_custom_labels['custom2'] = $cat_data[0]['customlabel2'] == NULL || $cat_data[0]['customlabel2'] == 'null' ? '' : $cat_data[0]['customlabel2'];
                    $asset_custom_labels['custom3'] = $cat_data[0]['customlabel3'] == NULL || $cat_data[0]['customlabel3'] == 'null'? '' : $cat_data[0]['customlabel3'];
                    $asset_custom_labels['custom4'] = $cat_data[0]['customlabel4'] == NULL || $cat_data[0]['customlabel4'] == 'null' ? '' : $cat_data[0]['customlabel4'];
                    $asset_custom_labels['custom5'] = $cat_data[0]['customlabel5'] == NULL || $cat_data[0]['customlabel5'] == 'null' ? '' : $cat_data[0]['customlabel5'];
                    $this->data['asset_categoryname'] = $cat_data[0]['category_name'];
                }
            }


            $this->data['asset_custom_labels'] = $asset_custom_labels;
            $this->data['cssToLoad'] = array(
                base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
                base_url('plugins/select2/select2.min.css'),
                base_url('plugins/datepicker/datepicker3.css'),
                base_url('plugins/uigrid/ui-grid-stable.min.css') 
            );
            
            $this->data['jsToLoad'] = array(
                
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig',
                 base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
                 base_url('plugins/input-mask/jquery.inputmask.js'),
                 base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                 base_url('plugins/jquery-validator/jquery.validate.min.js'),
                 base_url('plugins/jquery-validator/validation.js'),
                 base_url('plugins/datepicker/bootstrap-datepicker.js'), 
                 base_url('plugins/select2/select2.full.min.js'),
                 base_url('assets/js/jquery.form.js'),
                 base_url('plugins/uigrid/angular.min.js'), 
                 base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
                 base_url('plugins/uigrid/ui-grid-stable.min.js'),
                
                 base_url('assets/js/asset/assets.js'),
                 base_url('assets/js/editlog.js'),
                 base_url('assets/js/asset/asset.history.js'),
                 base_url('assets/js/asset/asset.document.js'),
                 base_url('assets/js/asset/asset.history.js'),
                 base_url('assets/js/asset/asset.supplier.js'),
                 base_url('assets/js/asset/asset.information.js'),
                 base_url('assets/js/asset/asset.financial.js'),
                 base_url('assets/js/asset/asset.service.js')
            );
            
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Edit - Assets')
                ->set_layout($this->layout)
                ->set('page_title', 'My Assets')
                ->set('page_sub_title', 'Edit')
                ->set_breadcrumb('My Assets', site_url('asset'))
                ->set_breadcrumb('Edit', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('asset/editasset', $this->data);
            
		
        }
        else
        {
            $asset_form_post = $this->input->post('asset_form_post');
            $assetid = $this->input->post('assetid');
	 
            if($asset_form_post == "financial") {
		 
		$updateData = array( 
                    'purchase_price'            => trim($this->input->post('purchase_price')),
                    'life_expectancy'           => trim($this->input->post('life_expectancy')),
                    'annual_depreciation_rate'  => trim($this->input->post('annual_depreciation_rate')),
                    'depreciation_method_id'    => trim($this->input->post('depreciation_method_id')),
                    'current_value'             => trim($this->input->post('current_value')),
                    'salvage_value'             => trim($this->input->post('salvage_value')),
                    'replacement_value'         => trim($this->input->post('replacement_value')),
                    'service_cost_to_date'      => trim($this->input->post('service_cost_to_date')),
                    'costcentre'                => trim($this->input->post('costcentre')),
                    'assetaccount'              => trim($this->input->post('assetaccount')),
                    'expenseaccount'            => trim($this->input->post('expenseaccount'))
                );
            }

            
            if($asset_form_post == "5") {
			 
		$updateData = array(
                    'haslogbook'        => trim($this->input->post('haslogbook')),
                    'logbooklocation'   => trim($this->input->post('logbooklocation')),
                    'asset_criticality' => trim($this->input->post('asset_criticality')),
                    'isreportrequired'  => trim($this->input->post('isreportrequired')),
                    'annual_visits'     => trim($this->input->post('annual_visits')),
                    'visit_frequency'   => trim($this->input->post('visit_frequency'))
                );
            }
			
            if($asset_form_post == "4") {
		 
                $updateData = array(
                    'description'   => trim($this->input->post('description')),
                    'ohs_risk'      => trim($this->input->post('ohs_risk')),
                    'quantity'      => trim($this->input->post('quantity')),
                    'length'        => trim($this->input->post('length')),
                    'width'         => trim($this->input->post('width')),
                    'height'        => trim($this->input->post('height')) 
                );
            }

            if($asset_form_post == "3") {
		 
		$updateData = array(
                    'condition'                 => trim($this->input->post('condition')),
                    'desired_condition'         => trim($this->input->post('desired_condition')),
                    'isworking'                 => trim($this->input->post('isworking')),
                    'isdamaged'                 => trim($this->input->post('isdamaged')),
                    'notes'                     => trim($this->input->post('notes'))
                );
            }

            if($asset_form_post == "2") {
		
		$updateData = array(
                    'supplier_name'                 => trim($this->input->post('supplier_name')),
                    'supplier_address'              => trim($this->input->post('supplier_address')),
                    'supplier_phone'                => trim($this->input->post('supplier_phone')),
                    'supplier_contact_name'         => trim($this->input->post('supplier_contact_name')),
                    'supplier_email'                => trim($this->input->post('supplier_email')),
                    'supplier_website'              => trim($this->input->post('supplier_website')),
                    'preferred_contractor'          => trim($this->input->post('preferred_contractor')),
                    'preferred_contractor_phone'    => trim($this->input->post('preferred_contractor_phone')),
                    'preferred_contractor_email'    => trim($this->input->post('preferred_contractor_email'))
                );
            }

            if($asset_form_post == "1") {
            	
		$warranty_expiry_date = $this->input->post('warranty_expiry_date');
                if(trim($warranty_expiry_date)!='') {
                    $warranty_expiry_date = to_mysql_date($warranty_expiry_date,RAPTOR_DISPLAY_DATEFORMAT);

                } else {
                    $warranty_expiry_date = null;
                }
                $disposal_date = $this->input->post('disposal_date');
                if(trim($disposal_date)!='') {
                    $disposal_date = to_mysql_date($disposal_date,RAPTOR_DISPLAY_DATEFORMAT);

                } else {
                    $disposal_date = null;
                }
                $purchase_date = $this->input->post('purchase_date');
                if(trim($purchase_date)!='') {
                    $purchase_date = to_mysql_date($purchase_date,RAPTOR_DISPLAY_DATEFORMAT,'Y-m-d H:i:s');
                } else {
                    $purchase_date = null;
                }
				
		$updateData = array(
                    'custom1'               => trim($this->input->post('custom1')),
                    'custom2'               => trim($this->input->post('custom2')),
                    'custom3'               => trim($this->input->post('custom3')),
                    'custom4'               => trim($this->input->post('custom4')),
                    'custom5'               => trim($this->input->post('custom5')),
                    'purchase_date'         => $purchase_date,
                    'disposal_date'         => $disposal_date,
                    'warranty_expiry_date'  => $warranty_expiry_date,
                    'category_id'           => (int)trim($this->input->post('category_id')),
                    'category_name'         => trim($this->input->post('category_name')),
                    'service_tag'           => trim($this->input->post('service_tag')),
                    'serial_no'             => trim($this->input->post('serial_no')),
                    'model'                 => trim($this->input->post('model')),
                    'manufacturer'          => trim($this->input->post('manufacturer')),
                    'sublocation_id'        => (int)trim($this->input->post('sublocation_id')),
                    'sublocation_text'      => trim($this->input->post('sublocation_text')),
                    'location_id'           => (int)trim($this->input->post('location_id')),
                    'location_text'         => trim($this->input->post('location_text')),
                    'site_address'          => trim($this->input->post('site_address')),
                    'labelid'               => (int)trim($this->input->post('labelid')),
                    'latitude_decimal'      => (float)trim($this->input->post('latitude_decimal')),
                    'longitude_decimal'     => (float)trim($this->input->post('longitude_decimal'))
               );
		 			
            }
		
             
            $request = array(
                'updateData'        => $updateData,
                'assetid'           => $assetid,
                'logged_contactid'  => $this->data['loggeduser']->contactid
            );
            $this->assetclass->updateAsset($request); 
         
            $this->session->set_flashdata('success', "Asset Data updated.");
            redirect('asset');
        } 
    }
    
   /**
    * This function use for load Asset in uigrid
    * 
    * @return json 
    */
    public function loadAssetHistory() {
        
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
                $field = 'activity_date';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
               
                $assetid = $this->input->get('assetid');
                
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $assetDate = $this->assetclass->getAssetHistoryData($this->session->userdata('raptor_customerid'), $assetid, $size, $start, $field, $order, $filter, $params);
        
                $trows = $assetDate['trows'];
                $data = $assetDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['activity_date'] = format_date($value['activity_date'],RAPTOR_DISPLAY_DATEFORMAT);
                 
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
    * This function use for load Asset in uigrid
    * 
    * @return json 
    */
    public function loadAssetDocuments() {
        
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
                $field = 'documentid';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
               
                $assetid = $this->input->get('assetid');
                
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $assetDate = $this->assetclass->getAssetDocumentData($this->session->userdata('raptor_customerid'), $assetid, $size, $start, $field, $order, $filter, $params);
        
                $trows = $assetDate['trows'];
                $data = $assetDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['dateadded'] = format_date($value['dateadded'],RAPTOR_DISPLAY_DATEFORMAT);
                 
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
     * this function use for load job id
     * 
     * @return json
     */
    public function loadJobIds() {
        
       
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerID =$this->session->userdata('raptor_customerid');
            $query = trim($this->input->get('search'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
            
            if( $isSuccess )
            {
                $data = $this->assetclass->getJobID($customerID, $query);
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
     * this function use for load poref
     * 
     * @return json
     */
    public function loadPoRef() {
        
         
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $query = trim($this->input->get('search'));
            $jobid =  trim($this->input->get('jobid'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
         
            if($isSuccess){
                $data = $this->assetclass->getPOref($jobid, $query);
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
     * this function use for load address
     * 
     * @return json
     */
    public function loadAddress() {
            
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
            
            $query = trim($this->input->get('search'));
            $customerID =$this->session->userdata('raptor_customerid');
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $data = $this->assetclass->getSiteAddress($customerID, $query);
 
        
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
     * this function use for load locations
     * 
     * @return json
     */
    public function loadLocations() {
            
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
            
            $query = trim($this->input->get('search'));
            $labelid = trim($this->input->get('labelid'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $data = $this->assetclass->getAssetLocations($labelid, $query);
 
        
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
     * this function use for load sub locations
     * 
     * @return json
     */
    public function loadSubLocations() {
            
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
            
            $query = trim($this->input->get('search'));
            $locationid = trim($this->input->get('locationid'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
            
            if( $isSuccess )
            {
                 $data = $this->assetclass->getAssetSubLocations($locationid, $query);
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
     * this function use for load manufacturers
     * 
     * @return json
     */
    public function loadManufacturers() {
            
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
            
            $query = trim($this->input->get('search'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
            
            if( $isSuccess )
            {
                $data = $this->assetclass->getManufacturers($query);
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
     * this function use for approve document
     * 
     * @return void
     */
    public function approveDocument() {
       
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
            
            $id = trim($this->input->post('id'));	
            $chk = trim($this->input->post('chk'));	

            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $documentData = array('approved'=>$chk);
                $request = array(
                    'documentData'     => $documentData,
                    'documentid'       => $id,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->documentclass->updateDocument($request); 
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
     * this function use for get document data
     * 
     * @return json
     */
    public function loadDocumentData() {
         
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
            
            $id = trim($this->input->post('id'));	
            $assetid = trim($this->input->post('assetid'));	

            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $data = $this->assetclass->getAssetDocument($assetid, $id);
       
                $success -> setData($data);
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
     * this function use for savedocument
     * 
     * @return json
     */
    public function saveDocument() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $assetid = trim($this->input->post('assetid'));
            $documentdesc = trim($this->input->post('documentdesc'));
            $assetdoctypename = trim($this->input->post('assetdoctypename'));
           
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                  //Store Upload File location from config
                $filelocation = $this->config->item("document_dir");
             
                $uploadMessage = '';
                $file_name= time();
                $fileUploaded = false;
                    
                //Check dir exist or not
                if (!is_dir($filelocation)) {
                    //Create Dir if not exist
                    mkdir($filelocation, 0755, TRUE);
                }
                
                $filename = $_FILES['docfileupload']['name'];
                //get file type
                $docformat = pathinfo($filename, PATHINFO_EXTENSION);
                //set allow file type
                $allowed = array('pdf','docx','xls','xlsx','txt','doc','png','jpg','jpeg','gif');
                
                
                //validated file is valid or not
                if (in_array($docformat, $allowed) ) {

                    $file_name= 'temp_'.time();
                    //set config for upload
                    $config['upload_path'] = $filelocation;
                    $config['allowed_types'] = implode('|', $allowed);
                    $config['file_name'] = $file_name;
                    $config['overwrite'] = TRUE;

                    //load upload library and initilize
                    $this->load->library('upload', $config);

                    //do upload file
                    if (!$this->upload->do_upload("docfileupload"))
                    {
                        $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();
                        $data = array(
                            'success' => FALSE,
                            'message' => $message
                        ); 
                    }
                    else
                    {
                        
                        $uploadedData = $this->upload->data();
                        $filesizekb = (double)$uploadedData['file_size'];
                      
                        
                        $documentData =  array(
                            'documentdesc'  => $this->input->post('documentdesc'),
                            'doctype'       => $this->input->post('assetdoctypename'),
                            'docformat'     => $docformat,
                            'dateadded'     => date('Y-m-d H:i:s',time()),
                            'pdate'         => date('Y-m-d H:i:s',time()),
                            'userid'        => $this->session->userdata('raptor_email'),
                            'xrefid'        => trim($this->input->post('assetid')),
                            'xreftable'     => 'asset',
                            'origin'        => 'raptor',
                            'docname'       => $filename,
                            'filesize'      => number_format($filesizekb, 0)
                        );
                        
                        $request = array(
                            'documentData'     => $documentData,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $response = $this->documentclass->createDocument($request); 

                        $documentid = $response['documentid'];
                       
                                
                        $insertData =  array(
                            'assetid'    => $assetid,
                            'documentid' => $documentid
                        );
                        
                        $request = array(
                            'insertData'       => $insertData,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $this->assetclass->insertAssetDocument($request); 
                        
                        
                        $source_path = $filelocation  . $file_name.'.'.$documentData['docformat'];
                        $newfile = $filelocation  . $documentid.'.'.$documentData['docformat'];
                        @rename($source_path, $newfile);
                        if($docformat == 'jpg' || $docformat == 'png'  || $docformat == 'jpeg'|| $docformat == 'gif') {
                            $dir = $filelocation.'thumb/';
                            if(!is_dir($dir)) {
                                mkdir($dir, 0755, true);
                            }
                            
                            $source_path = $filelocation . $documentid.'.'.$docformat;
                            $target_path = $filelocation.'thumb/';
                            $config_manip = array(
                                'image_library'  => 'gd2',
                                'source_image'   => $source_path,
                                'new_image'      => $target_path,
                                'maintain_ratio' => TRUE,
                                'create_thumb'   => TRUE,
                                'thumb_marker'   => '_thumb',
                                'width'          => 120,
                                'height'         => 120
                            );
                            $this->load->library('image_lib', $config_manip);
                            if (!$this->image_lib->resize()) {
                                $newfile= $target_path  . $filelocation.'_thumb.'.$docformat;
                                @copy($source_path, $newfile);
                            }
                        }
                                       
                        $message = 'Document updated successfully.';
                        $document_data = $this->assetclass->getAssetDocument($assetid);
                        foreach ($document_data as $key => $value) {
                            $document_data[$key]['image_path'] = $this->config->item("document_path").'thumb/';
                            $document_data[$key]['dateadded'] = format_date($document_data[$key]['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
                             
                        }
                         
                        $data = array(
                            'success' => TRUE,
                            'message' => $message,
                            'data'    => $document_data
                        );
                    }
                }
                else{
                    $message="Sorry invalid file extension, allowed extensions are: ". implode(", ", $allowed);
                    $data = array(
                        'success' => FALSE,
                        'message' => $message
                    );
                }
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
     * this function use for udpatehistory
     * 
     * @return json
     */
    public function udpateHistory() {
		
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
            
            $activity_date = trim($this->input->post('activity_date'));	
            $jobid = trim($this->input->post('jobid'));
            $assetid = trim($this->input->post('assetid'));
            $desc = trim($this->input->post('description'));
            $poref = trim($this->input->post('poref'));
            $category = trim($this->input->post('activity_category'));

            if(trim($activity_date)!='') {
                $activity_date = to_mysql_date($activity_date,RAPTOR_DISPLAY_DATEFORMAT);
            } else {
                $activity_date = null;
            }	

            $customerID =$this->session->userdata('raptor_customerid');
            $jobData = $this->assetclass->getJobID($customerID,NULL, $jobid);
            if(count($jobData)==0){

                $message = 'Job Id Is invalid';
                 
            }

            $poData = $this->assetclass->getPOref($jobid,NULL, $poref);
            if(count($poData)==0){
                $message .= '<br/>PO Ref Is invalid'; 
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $insertData = array(
                    'create_date'           => date('Y-m-d H:i:s'),
                    'create_user'           => $this->session->userdata('raptor_email'),
                    'asset_id'              => $assetid,
                    'jobid'                 => $jobid,
                    'poref'                 => $poref,
                    'activity_description'  => $desc,
                    'activity_date'         => $activity_date,
                    'activity_category'     => $category
                );

                $request = array(
                    'insertData'       => $insertData,  
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $response = $this->assetclass->insertAssetHistory($request);
              
                $data = $this->assetclass->getAssetHistory($assetid);

                foreach ($data as $key => $value) {
                    if($data[$key]['activity_name'] == null) {
                                $data[$key]['activity_name'] = '';	
                    }
                    $data[$key]['activity_date'] = format_date($data[$key]['activity_date'], RAPTOR_DISPLAY_DATEFORMAT);
                }
              
                $success -> setData($data);
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
     * this function use for udpatedocumentdata
     * 
     * @return json
     */
    public function udpateDocumentData() {
        
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
            
            $docnote = trim($this->input->post('mdocnote'));	
            $desc = trim($this->input->post('mdocumentdesc'));
            $assetid = trim($this->input->post('assetid'));
            $docid = trim($this->input->post('hdocumentid'));
 
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $documentData = array(
                    'docnote'=>$docnote,
                    'documentdesc'=>$desc
                );
                 
                $request = array(
                    'documentData'     => $documentData,
                    'documentid'       => $docid,
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->documentclass->updateDocument($request); 
               
                $data = $this->assetclass->getAssetDocument($assetid, $docid);
       
                $success -> setData($data);
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function addEditAssetLocation() {
            
        //Validate ajax request or not
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
            
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_ASSET_LOCATION');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_ASSET_LOCATION');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Asset Location.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
   
                //Create array for insert GL CODE data
                $updateData = array(
                    'customerid'        => $this->data['loggeduser']->customerid,
                    'labelid'        => trim($this->input->post('labelid')),
                    'location'       => trim($this->input->post('location')),
                    'notes'        => trim($this->input->post('notes')),
                    'latitude_decimal'      => (float)trim($this->input->post('latitude_decimal')),
                    'longitude_decimal'     => (float)trim($this->input->post('longitude_decimal')),
                    'is_active'             => (int)trim($this->input->post('is_active')),
                    'modify_date'           => date('Y-m-d H:i:s'),
                    'modifiedby'           => $this->data['loggeduser']->contactid
                    
                );

                //check Add/Edit Mode
                if (trim($this->input->post('mode')) == 'edit') {
                    $asset_location_id = $this->input->post('asset_location_id');
                    $request = array(
                        'asset_location_id'      => $this->input->post('asset_location_id'),
                        'updateLocationData'        => $updateData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );

                    $this->assetclass->updateAssetLocation($request);
                 
                }
                else{
                    $updateData['create_date'] = date('Y-m-d H:i:s');
                    $updateData['createdby'] = $this->data['loggeduser']->contactid;
                    
                    $request = array(
                        'insertLocationData'        => $updateData, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                    );
                    $response = $this->assetclass->insertAssetLocation($request);
                    
                    $asset_location_id = $response['asset_location_id'];
                }

                $data = $this->assetclass->getAssetLocations($this->input->post('labelid'));
                $success -> setData($data);
                $success -> setTotal($asset_location_id);
                $message = 'Location updated.';
                
                
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function addEditAssetSubLocation() {
            
        //Validate ajax request or not
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
            
            //Check Add/Edit Mode
            if (trim($this->input->post('mode')) == 'edit') {
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'EDIT_ASSET_SUBLOCATION');
            }
            else{
                $userRights= $this->sharedclass->getFunctionalSecurityAccess($this->data['loggeduser']->contactid,'ADD_ASSET_SUBLOCATION');
            }
        
            //Check Add Rights exist or not
            if (!$userRights) {
                $message = 'You are not allow to ' . $this->input->post('mode') . ' Asset Sub Location.';
            }

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
   
                //Create array for insert GL CODE data
                $updateData = array( 
                    'location_id'       => trim($this->input->post('location_id')),
                    'sublocation'       => trim($this->input->post('sublocation')),
                    'notes'             => trim($this->input->post('notes')), 
                    'is_active'         => (int)trim($this->input->post('is_active')),
                    'modify_date'       => date('Y-m-d H:i:s'),
                    'modifiedby'        => $this->data['loggeduser']->contactid
                    
                );

                //check Add/Edit Mode
                if (trim($this->input->post('mode')) == 'edit') {
                    $asset_sublocation_id = $this->input->post('asset_sublocation_id');
                    $request = array(
                        'asset_sublocation_id'      => $this->input->post('asset_sublocation_id'),
                        'updateSubLocationData'        => $updateData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );

                    $this->assetclass->updateAssetSubLocation($request);
                 
                }
                else{
                    $updateData['create_date'] = date('Y-m-d H:i:s');
                    $updateData['createdby'] = $this->data['loggeduser']->contactid;
                    
                    $request = array(
                        'insertSubLocationData'        => $updateData, 
                        'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                    );
                    $response = $this->assetclass->insertAssetSubLocation($request);
                    
                    $asset_sublocation_id = $response['asset_sublocation_id'];

                }
                //ALTER TABLE `asset_sublocation` ADD `creatdedby` INT NOT NULL AFTER `modify_user`, ADD `modifiedby` INT NOT NULL AFTER `creatdedby`;
                $data = $this->assetclass->getAssetSubLocations($this->input->post('location_id'));
                $success -> setData($data);
                $success -> setTotal($asset_sublocation_id);
                $message = 'Sub Location updated.';
                
                
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
     * this function use for load locations
     * 
     * @return json
     */
    public function loadAssetLocation() {
            
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
            
           
            $id = trim($this->input->get('id'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $data = $this->assetclass->getAssetLocationById($id);
 
        
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
     * this function use for load sub locations
     * 
     * @return json
     */
    public function loadAssetSubLocation() {
            
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
            $id = trim($this->input->get('id'));
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
            
            if( $isSuccess )
            {
                 $data = $this->assetclass->getAssetSubLocationById($id);
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
    * This function use for load Asset in uigrid
    * 
    * @return json 
    */
    public function loadAssetForSchedules() {
        
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
                
                $order = 'desc';
                $field = 'assetid';
                $params = array();
                $filter = '';
               
                
                if (trim($this->input->get('category')) != '') {
                    $params['a.category_id'] = $this->input->get('category');
                }
                
                //get document data
                $assetDate = $this->assetclass->getAssetData($this->session->userdata('raptor_customerid'), NULL, 0, $field, $order, $filter, $params);
        
                $trows = $assetDate['trows'];
                $data = array();
                
                //format data for uigrid
                foreach ($assetDate['data'] as $key => $value) {
                    $data[] = array(
                        'assetid'   => $value['assetid'],
                        'location_text'   => $value['location_text'],
                        'sublocation_text'   => $value['sublocation_text'],
                        'manufacturer'   => $value['manufacturer'],
                        'service_tag'   => $value['service_tag'],
                        'serial_no'   => $value['serial_no'],
                        'client_asset_id'   => $value['client_asset_id'],
                        'last_service_date' => format_date($value['last_service_date'],RAPTOR_DISPLAY_DATEFORMAT)
                    );
                    
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
    * @desc This function use for update contact rate
    * @param none
    * @return json 
    */
    public function saveAssetService() {
            
        //Validate ajax request or not
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
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $updateData = array();
                
                $assetdata = $this->input->post('assetdata');
                //check Add/Edit Mode
                $tempjobid = '';
                $jobid = NULL;
                if(trim($this->input->post('allocateto')) == 'new'){
                    $tempjobid = 'tmp_'.time(); 
                }
                else{
                    $jobid = trim($this->input->post('jobid'));
                }
                foreach ($assetdata as $key => $value) {
                    if(!$this->assetclass->checkAssetService($value['assetid'], $value['servicetypeid'], $value['checklistid'], $value['activityid'], $jobid)){ 
                      
                        $updateData[] = array(
                            'customerid'          => $this->data['loggeduser']->customerid,
                            'assetid'             => $value['assetid'],
                            'servicetypeid'       => $value['servicetypeid'],
                            'checklistid'         => $value['checklistid'],
                            'activityid'          => $value['activityid'],
                            'jobid'               => $jobid,
                            'tempjobid'           => $tempjobid,
                            'create_date'         => date('Y-m-d H:i:s'),
                            'createdby'           => $this->data['loggeduser']->contactid

                        );
                    }
                    
                }    
              
                
                $request = array(
                    'assetServiceData'  => $updateData, 
                    'logged_contactid'  => $this->data['loggeduser']->contactid //logged contact id
                );
                $this->assetclass->insertAssetService($request);
 
                $success -> setData($data);
                $success -> setTotal($tempjobid);
                $message = 'Asset Service updated.';
                
                
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
    
    
}