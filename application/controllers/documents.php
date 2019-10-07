<?php 
/**
 * Documents Controller Class
 *
 * This is a Documents controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Documents Controller Class
 *
 * This is a Documents controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Documents
 * @filesource          Documents.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Documents extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
         //  Load libraries
 
        $this->load->library('document/DocumentClass');
        
        $contactid =$this->session->userdata('raptor_contactid');
        $this->data['EXPORT_DOC'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'EXPORT_DOCLIBRARY');
    }

    /**
    * This function use for show Documents
    * 
    * @return void 
    */
    public function index()
    {
        show_404();
//        $this->data['dcfm_client_iframe_url']=$this->config->item('client_portal').'contractor/Panel/docLibrary.php'; 
//        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Doc Library')
//                ->set_layout($this->layout)
//                ->set('page_title', 'Document Library')
//                ->set('page_sub_title', '')
//                ->set_breadcrumb('Document Library', '')
//                ->set_partial('page_header', 'shared/page_header')
//                ->set_partial('header', 'shared/header')
//                ->set_partial('navigation', 'shared/navigation')
//                ->set_partial('footer', 'shared/footer')
//                ->build('shared/iframe', $this->data);

    } 
    
    /**
    * This function use for show Documents
    * 
    * @return void 
    */
    public function jobDocs()
    {
       
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
        
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/documents/documents.jobsdocuments.js')
            
        );
        
        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['docType'] = $this->documentclass->getJobDocumentTypes($customerid);
        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['monthDocCount'] = $this->documentclass->getJobMonthCounts($customerid);
        $this->data['sites'] = $this->documentclass->getSites($customerid);
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Job Documents Library')
            ->set_layout($this->layout)
            ->set('page_title', 'Job Documents')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Document Library', '')
            ->set_breadcrumb('Job Documents', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ///->build('shared/iframe', $this->data);
            ->build('documents/jobsdocuments', $this->data);
    }
    
     /**
    * This function use for show Documents
    * 
    * @return void 
    */
    public function customerDocs()
    {
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'), 
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/documents/documents.customerdocuments.js')
            
        );
        
        $customerid = $this->session->userdata('raptor_customerid');
        $contactid =$this->session->userdata('raptor_contactid');
        $this->data['UPLOAD_CUSTOMER_DOCUMENT'] =  $this->sharedclass->getFunctionalSecurityAccess($contactid, 'UPLOAD_CUSTOMER_DOCUMENT');
        $this->data['docFolder'] = $this->documentclass->getDocFolder();
        $this->data['docType'] = $this->documentclass->getCustomerDocumentTypes($customerid);
        $this->data['monthDocCount'] = $this->documentclass->getCustomerMonthCounts($customerid);
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Customer Documents Library')
            ->set_layout($this->layout)
            ->set('page_title', 'Customer Documents')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Document Library', '')
            ->set_breadcrumb('Customer Documents', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ///->build('shared/iframe', $this->data);
            ->build('documents/customerdocuments', $this->data);
    }
    
    /**
    * This function use for show Documents
    * 
    * @return void 
    */
    public function assetDocs()
    {
       
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
        
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/documents/documents.assetdocuments.js')
            
        );
        
        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['docType'] = $this->documentclass->getAssetDocumentTypes();
        $this->data['states'] = $this->sharedclass->getStates(1);
        $this->data['monthDocCount'] = $this->documentclass->getJobMonthCounts($customerid);
        $this->data['sites'] = $this->documentclass->getSites($customerid);
        $this->data['assetCategory'] = $this->documentclass->getAssetCategory();
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Asset Documents Library')
            ->set_layout($this->layout)
            ->set('page_title', 'Asset Documents')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Document Library', '')
            ->set_breadcrumb('Asset Documents', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ///->build('shared/iframe', $this->data);
            ->build('documents/assetdocuments', $this->data);
    }
    
     /**
    * This function use for load Job Documents in uigrid
    * 
    * @return json 
    */
    public function loadJobDocuments() {
        
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
                $field = 'dateadded';
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
             
                if ($this->input->get('state') != NULL) {
                    $params['j.sitestate'] = $this->input->get('state');
                }
                if (trim($this->input->get('suburb')) != '') {
                    $params['j.sitesuburb'] = $this->input->get('suburb');
                }
                if ($this->input->get('documenttype') != NULL) {
                    $params['d.doctype'] = $this->input->get('documenttype');
                }
                if ($this->input->get('site') != NULL) {
                    $params['j.labelid'] = $this->input->get('site');
                }
                
                
                if ($this->input->get('monthyear') != NULL) {
                    $params['DATE_FORMAT(d.dateadded,\'%Y-%m\')'] = $this->input->get('monthyear');
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $documentDate = $this->documentclass->getJobDocumentsData($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $documentDate['trows'];
                $data = $documentDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['dateadded'] = format_date($value['dateadded']); 
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
    * This function use for load Customer Documents in uigrid
    * 
    * @return json 
    */
    public function loadCustomerDocuments() {
        
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
                $field = 'dateadded';
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
              
                if ($this->input->get('documenttype') != NULL) {
                    $params['d.doctype'] = $this->input->get('documenttype');
                }
                
                if ($this->input->get('monthyear') != NULL) {
                    $params['DATE_FORMAT(d.dateadded,\'%Y-%m\')'] = $this->input->get('monthyear');
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $documentDate = $this->documentclass->getCustomerDocumentsData($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $documentDate['trows'];
                $data = $documentDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['dateadded'] = format_date($value['dateadded']); 
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
    * This function use for load Asset Documents in uigrid
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
                $field = 'dateadded';
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
             
                /*if ($this->input->get('state') != NULL) {
                    $params['j.sitestate'] = $this->input->get('state');
                }*/
                if (trim($this->input->get('suburb')) != '') {
                    $params['a.sitesuburb'] = $this->input->get('suburb');
                }
                if ($this->input->get('documenttype') != NULL) {
                    $params['d.doctype'] = $this->input->get('documenttype');
                }
                if ($this->input->get('site') != NULL) {
                    $params['a.labelid'] = $this->input->get('site');
                }
                if ($this->input->get('category') != NULL) {
                    $params['at.category_id'] = $this->input->get('category');
                }
                
                
                if ($this->input->get('monthyear') != NULL) {
                    $params['DATE_FORMAT(d.dateadded,\'%Y-%m\')'] = $this->input->get('monthyear');
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $documentDate = $this->documentclass->getAssetDocumentsData($this->session->userdata('raptor_contactid'), $size, $start, $field, $order, $filter, $params);
        
                $trows = $documentDate['trows'];
                $data = $documentDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['dateadded'] = format_date($value['dateadded']); 
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
     * downoad Job Document Data in excel
     */
    public function exportJobDocuments() 
    {
        if(!$this->data['EXPORT_DOC']) {
            show_404();
        }
         
        $params = array();
        $order = 'desc';
        $field = 'dateadded';
        $filter = '';
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }

        if ($this->input->get('state') != NULL) {
            $params['j.sitestate'] = $this->input->get('state');
        }
        if (trim($this->input->get('suburb')) != '') {
            $params['j.sitesuburb'] = $this->input->get('suburb');
        }
        if ($this->input->get('documenttype') != NULL) {
            $params['d.doctype'] = $this->input->get('documenttype');
        }

        if ($this->input->get('monthyear') != NULL) {
            $params['DATE_FORMAT(d.dateadded,\'%Y-%m\')'] = $this->input->get('monthyear');
        }
        if ($this->input->get('site') != NULL) {
            $params['j.labelid'] = $this->input->get('site');
        }
        
        $documentDate = $this->documentclass->getJobDocumentsData($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
        
        
        $dataArray = array();
        $heading = array('Job ID', 'Job Description', 'Customer Ref', 'Date Added', 'Document Type', 'Document Name', 'Description', 'Suburb', 'State', 'Site FM', 'File Size');
        $this->load->library('excel');
 
        foreach($documentDate['data'] as $row1)
        { 
            $result = array();
  
            $result[] = $row1['jobid'];
            $result[] = $row1['jobdescription'];
            $result[] = $row1['custordref'];
            $result[] = format_datetime($row1['dateadded']);
            $result[] = $row1['doctype'];
            $result[] = $row1['docname'];
            $result[] = $row1['documentdesc'];
            $result[] = $row1['sitesuburb'];
            $result[] = $row1['sitestate'];
            $result[] = $row1['sitefm'];
            $result[] = $row1['filesize'];
            
            $dataArray[]=$result;
        }

        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="jobdocuments.xls";
        
         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->Exportexcel("Job Documents", $dir, $file_name, $heading, $dataArray);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('jobdocuments.xls', $data);


    }
    
    /**
     * downoad Customer Document Data in excel
     */
    public function exportCustomerDocuments() 
    {
        if(!$this->data['EXPORT_DOC']) {
            show_404();
        }
         
        $params = array();
        $order = 'desc';
        $field = 'dateadded';
        $filter = '';
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }

         
        if ($this->input->get('documenttype') != NULL) {
            $params['d.doctype'] = $this->input->get('documenttype');
        }

        if ($this->input->get('monthyear') != NULL) {
            $params['DATE_FORMAT(d.dateadded,\'%Y-%m\')'] = $this->input->get('monthyear');
        }
        
        $documentDate = $this->documentclass->getCustomerDocumentsData($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
        
        
        $dataArray = array();
        $heading = array('Date Added', 'Document Type', 'Document Name', 'Description', 'File Size');
        $this->load->library('excel');
 
        foreach($documentDate['data'] as $row1)
        { 
            $result = array();
   
            $result[] = format_datetime($row1['dateadded']);
            $result[] = $row1['doctype'];
            $result[] = $row1['docname'];
            $result[] = $row1['documentdesc'];
            
            $result[] = $row1['filesize'];
            
            $dataArray[]=$result;
        }

        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="customerdocuments.xls";
        
         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(35); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(35); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->Exportexcel("Customer Documents", $dir, $file_name, $heading, $dataArray);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('customerdocuments.xls', $data);


    }
    
     /**
     * downoad Asset Document Data in excel
     */
    public function exportAssetDocuments() 
    {
        if(!$this->data['EXPORT_DOC']) {
            show_404();
        }
         
        $params = array();
        $order = 'desc';
        $field = 'dateadded';
        $filter = '';
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }

        /*if ($this->input->get('state') != NULL) {
                    $params['j.sitestate'] = $this->input->get('state');
                }*/
                if (trim($this->input->get('suburb')) != '') {
                    $params['a.sitesuburb'] = $this->input->get('suburb');
                }
                if ($this->input->get('documenttype') != NULL) {
                    $params['d.doctype'] = $this->input->get('documenttype');
                }
                if ($this->input->get('site') != NULL) {
                    $params['a.labelid'] = $this->input->get('site');
                }
                if ($this->input->get('category') != NULL) {
                    $params['at.category_id'] = $this->input->get('category');
                }

        if ($this->input->get('monthyear') != NULL) {
            $params['DATE_FORMAT(d.dateadded,\'%Y-%m\')'] = $this->input->get('monthyear');
        }
        if ($this->input->get('site') != NULL) {
            $params['a.labelid'] = $this->input->get('site');
        }
        
        $documentDate = $this->documentclass->getAssetDocumentsData($this->session->userdata('raptor_contactid'), NULL, 0, $field, $order, $filter, $params);
        
        
        $dataArray = array();
        $heading = array('Asset ID', 'Asset Category', 'Client Asset ID', 'Service Tag', 'Date Added', 'Suburb', 'Document Type', 'Document Name', 'Description');
        $this->load->library('excel');
 
        foreach($documentDate['data'] as $row1)
        { 
            $result = array();
  
            //$result[] = $row1['jobid'];
            //$result[] = $row1['jobdescription'];
            $result[] = $row1['assetid'];
            $result[] = $row1['category_name'];
            $result[] = $row1['client_asset_id'];
            $result[] = $row1['service_tag'];
            $result[] = format_datetime($row1['dateadded']);
            $result[] = $row1['sitesuburb'];
            $result[] = $row1['doctype'];
            $result[] = $row1['docname'];
            $result[] = $row1['documentdesc'];
            
            //$result[] = $row1['sitestate'];
            //$result[] = $row1['sitefm'];
            $result[] = $row1['filesize'];
            
            $dataArray[]=$result;
        }

        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="assetdocuments.xls";
        
         //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        //$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        //$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20); 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); 
        //$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        //$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->Exportexcel("Asset Documents", $dir, $file_name, $heading, $dataArray);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('assetdocuments.xls', $data);


    }
    
    /**
    * This function use for download documents
    * 
    * @param integer $documentid 
    * @return void 
    */
    public function download($documentid) {
        
        
        //Load Selected document data
        $data = $this->documentclass->getDocumentById($documentid);
        if (count($data) > 0) {
          
            $docname = $data['documentid'].'.'.$data['docformat'];
            $path = $this->config->item('document_dir').$docname;
            if (file_exists($path)) {
                $content = file_get_contents($path);
                $docname = $data['docname'];
                
                //Load download helper 
                $this->load->helper('download');
        
                force_download($docname, $content);
            }
            else{
                throw new Exception("document doesn't exist. Documentid: " . $documentid);
            }
        
        }
        else{
            throw new Exception("document doesn't exist. Documentid: " . $documentid);
        }
    }
    
    /**
    * This function use for download report documents
    * 
    * @param integer $documentid 
    * @return void 
    */
    public function downloadReport($documentid) {
        
        $documentid = encrypt_decrypt('decrypt', $documentid);
        
        //Load Selected document data
        $data = $this->documentclass->getDocumentById($documentid);
        if (count($data) > 0) {
          
            $docname = $data['docname'];
            $path = $this->config->item('document_dir').$docname;
            if (file_exists($path)) {
                $content = file_get_contents($path);
                $docname = $data['docname'];
                
                //Load download helper 
                $this->load->helper('download');
        
                force_download($docname, $content);
            }
            else{
                echo "document doesn't exist.";
                //throw new Exception("document doesn't exist. Documentid: " . $documentid);
            }
        
        }
        else{
            echo "document doesn't exist.";
            //throw new Exception("document doesn't exist. Documentid: " . $documentid);
        }
    }
    
    /**
    * This function use for view documents
    * 
    * @param integer $documentid 
    * @return void 
    */
    public function viewDocument($documentid) {
        
        //Load Selected document data
        $data = $this->documentclass->getDocumentById($documentid);
        if (count($data) > 0) {
            $docname = $data['documentid'].'.'.$data['docformat'];
            $path = $this->config->item('document_dir').$docname;
            if (file_exists($path)) {
                redirect($this->config->item('document_path').$docname);
            } else {
                throw new Exception("document doesn't exist. Documentid: " . $documentid);
            }
        }
        else{
            throw new Exception("document doesn't exist. Documentid: " . $documentid);
        }
    }
    
     /**
    * This function use for load Job Documents in uigrid
    * 
    * @return json 
    */
    public function checkDocument() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;
        try
        { 
            $documentid = $this->input->get('documentid');
            
            if( !isset($documentid) ||  $documentid == '')
                $message = 'documentid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->documentclass->getDocumentById($documentid);
                if (count($data) > 0) {
                    $docname = $data['documentid'].'.'.$data['docformat'];
                    $path = $this->config->item('document_dir').$docname;
                    if (file_exists($path)) {
                      $message = 'success';  
                    } else {
                        $success = SuccessClass::initialize(FALSE);
                        $message = "Document doesn't exist. Doc id: " . $documentid . ")";
                    }
                }
                else{
                    $success = SuccessClass::initialize(FALSE);
                    $message = "Document doesn't exist. (Doc id: " . $documentid . ")";
                }
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
    * This function use for load Job Documents in uigrid
    * 
    * @return json 
    */
    public function loadJobDocumentsByJobId() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;
        try
        { 
            $jobid = $this->input->get('jobid');
            
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //default settings for uigrid
                $page = 1;
                $size = 25;
                $order = 'desc';
                $field = 'doctype, dateadded';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                $contactid = $this->session->userdata('raptor_contactid');
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $documentDate = $this->documentclass->getJobDocumentsByJobId($size, $start, $field, $order, $jobid, $contactid);
        
                $trows = $documentDate['trows'];
                $data = $documentDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                    $data[$key]['docurl'] = $this->config->item('document_path').$value['documentid'].'.'.$value['docformat']; 
                    $data[$key]['dateadded'] = format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT); 
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
    * @desc This function use for upload new job Document
    * @param none
    * @return json 
    */        
    public function uploadJobDocument() {
        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
          
            $type = $this->input->post('uploadtype');
            $filesize = $_FILES['fileup']['size']/1024;
            
            $imageformat = array('jpg','png','bmp','jpeg','gif');
            $filename = $_FILES['fileup']['name'];
            $docformat = pathinfo($filename, PATHINFO_EXTENSION);
            $allowed = array('jpg','png','bmp','jpeg','gif');
            if ($type =="adddoc") {
                $allowed = array('pdf','docx');
            }
            else{
                $allowed = array('jpg','png','bmp','jpeg','gif');
            }
            
            //check allowed file type
            if( !in_array($docformat, $allowed))
                $message = "Sorry invalid file extension, allowed extensions are: ". implode(", ", $allowed);

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
                $filelocation = $this->config->item('document_dir');
                if (!is_dir($filelocation)) {
                    mkdir($filelocation, 0755, TRUE);
                }
                $tempf_name= time();
                $config['upload_path'] = $this->config->item('document_dir');
                $config['allowed_types'] = implode("|", $allowed);
                $config['file_name'] = $tempf_name;
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);

                //Do upload file 
                if ($this->upload->do_upload("fileup"))
                {
                    $documentData = array(
                        'documentdesc'  => $this->input->post('description'),
                        'doctype'       => $this->input->post('doctype'),
                        'docformat'     => $docformat,
                        'dateadded'     => date('Y-m-d H:i:s',time()),
                        'userid'        => $this->session->userdata('email'),
                        'xrefid'        => $this->input->post('jobid'),
                        'xreftable'     => 'jobs',
                        'docname'       => $filename,
                        'pdate'         => date('Y-m-d H:i:s',time()),
                        'origin'        => 'Client Portal',
                        'docfolder'     => $this->input->post('foldername'),
                        'filesize'      => number_format($filesize, 2, '.', ''),
                    );

                    //request array for update extend new job
                    $request = array(
                        'documentData'     => $documentData,
                        'logged_contactid' => $this->session->userdata('raptor_contactid'),
                        'customerid'       => $this->session->userdata('raptor_customerid'),
                    );
                    $result = $this->documentclass->createDocument($request);
                    if( isset($result['documentid'])){
                     
                        $documentid = $result['documentid'];
                        $source_path = $filelocation  . $tempf_name.'.'.$docformat;
                        $newfile = $filelocation  . $documentid.'.'.$docformat;
                        @rename($source_path, $newfile);
                        
                        if (in_array($docformat, $imageformat) ) {
                            $source_path = $this->config->item('document_dir')  . $documentid.'.'.$docformat;
                            $target_path = $this->config->item('document_dir');
                            $config_manip = array(
                                'image_library'  => 'gd2',
                                'source_image'   => $source_path,
                                'new_image'      => $target_path,
                                'maintain_ratio' => TRUE,
                                'create_thumb'   => TRUE,
                                'thumb_marker'   => '_thumb',
                                'width'          => 230,
                                'height'         => 150
                            );
                            $this->load->library('image_lib', $config_manip);
                            if (!$this->image_lib->resize()) {
                                //echo $this->image_lib->display_errors();
                            }
                        }
                        
                        if ($type =="adddoc") {
                            $message = "Document upload successfully.";
                        } else {
                            $message = "Image upload successfully.";
                        }
                    }
                    else{
                        $success = SuccessClass::initialize(FALSE);
                        $message = "File cannot be upload, Please Try again.Error : ";
                    }
                }
                else{
                    $success = SuccessClass::initialize(FALSE);
                    $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();
                }
                
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
    * This function use for load Job Documents in uigrid
    * 
    * @return json 
    */
    public function loadJobReports() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;
        try
        { 
            $jobid = $this->input->get('jobid');
            
            if( !isset($jobid) )
                $message = 'jobid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //default settings for uigrid
                $page = 1;
                $size = 25;
                $order = 'desc';
                $field = 'doctype, dateadded';
                $params = array();

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                $editReport = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'EDIT_REPORT');
                
                //get document data
                $documentDate = $this->documentclass->getJobReports($size, $start, $field, $order, $jobid);
        
                $trows = $documentDate['trows'];
                $data = $documentDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {

                    $docname = $value['docname'];
                    $reportid = '';
                    if($docname != '' && $editReport == "1") {
                        $docnameArr = explode(".", $docname);
                        $docnameArr = explode("_", $docnameArr[0]);
                        if(isset($docnameArr[1]) && $docnameArr[1] != '') {
                            $reportid = (int)$docnameArr[1];
                        }
                    }
                    //$data[$key]['docurl'] = $this->config->item('document_path').$value['documentid'].'.'.$value['docformat']; 
                    $data[$key]['dateadded'] = format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
                    $data[$key]['reportid'] = $reportid;
                    $data[$key]['edit_report'] = $editReport;
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
    * @desc This function use for upload new Customer Document
    
    * @return json 
    */        
    public function uploadCustomerDocument() {
        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
          
           
            $filesize = $_FILES['fileup']['size']/1024;
             
            $filename = $_FILES['fileup']['name'];
            $docformat = pathinfo($filename, PATHINFO_EXTENSION);
            $allowed = array('pdf','docx', 'jpg','png','bmp','jpeg','gif');
            $imageformat = array('jpg','png','bmp','jpeg','gif');
 
            
            //check allowed file type
            if( !in_array($docformat, $allowed))
                $message = "Sorry invalid file extension, allowed extensions are: ". implode(", ", $allowed);

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                
                $filelocation = $this->config->item('document_dir');
                if (!is_dir($filelocation)) {
                    mkdir($filelocation, 0755, TRUE);
                }
                $tempf_name= time();
                $config['upload_path'] = $this->config->item('document_dir');
                $config['allowed_types'] = implode("|", $allowed);
                $config['file_name'] = $tempf_name;
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);

                //Do upload file 
                if ($this->upload->do_upload("fileup"))
                {
                    $documentData = array(
                        'documentdesc'  => $this->input->post('description'),
                        'doctype'       => $this->input->post('doctype'),
                        'docformat'     => $docformat,
                        'dateadded'     => date('Y-m-d H:i:s',time()),
                        'userid'        => $this->session->userdata('raptor_email'),
                        'contactid'     => $this->session->userdata('raptor_contactid'),
                        'xrefid'        => $this->session->userdata('raptor_customerid'),
                        'xreftable'     => 'customer',
                        'docname'       => $filename,
                        'pdate'         => date('Y-m-d H:i:s',time()),
                        'origin'        => 'Client Portal',
                        'docfolder'     => $this->input->post('foldername'),
                        'filesize'      => number_format($filesize, 2, '.', ''),
                        'approved'      => '1',
                        'docnote'       => 'Uploaded from client portal'
                    );

                    //request array for update extend new job
                    $request = array(
                        'documentData'     => $documentData,
                        'logged_contactid' => $this->session->userdata('raptor_contactid'),
                        'customerid'       => $this->session->userdata('raptor_customerid')
                    );
                    $result = $this->documentclass->createDocument($request);
                    if( isset($result['documentid'])){
                     
                        $documentid = $result['documentid'];
                        $source_path = $filelocation  . $tempf_name.'.'.$docformat;
                        $newfile = $filelocation  . $documentid.'.'.$docformat;
                        @rename($source_path, $newfile);
                        
                        if (in_array($docformat, $imageformat) ) {
                            $source_path = $this->config->item('document_dir')  . $documentid.'.'.$docformat;
                            $target_path = $this->config->item('document_dir');
                            $config_manip = array(
                                'image_library'  => 'gd2',
                                'source_image'   => $source_path,
                                'new_image'      => $target_path,
                                'maintain_ratio' => TRUE,
                                'create_thumb'   => TRUE,
                                'thumb_marker'   => '_thumb',
                                'width'          => 230,
                                'height'         => 150
                            );
                            $this->load->library('image_lib', $config_manip);
                            if (!$this->image_lib->resize()) {
                                //echo $this->image_lib->display_errors();
                            }
                        }
                        
                        $message = "Document upload successfully.";
                    }
                    else{
                        $success = SuccessClass::initialize(FALSE);
                        $message = "File cannot be upload, Please Try again.Error : ";
                    }
                }
                else{
                    $success = SuccessClass::initialize(FALSE);
                    $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();
                }
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