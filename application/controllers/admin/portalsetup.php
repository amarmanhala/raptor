<?php 
/**
 * Portalsetup Controller Class
 *
 * This is a Portalsetup controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portalsetup Controller Class
 *
 * This is a Portalsetup controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Portalsetup
 * @filesource          Portalsetup.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Portalsetup extends MY_Controller {

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
     * load User Portal Setup Views
     * 
     * @return void
     */
    public function index() {

        $this->data['cssToLoad'] = array( 
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

         $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.portalsetup.js')
        );
        
        
        $this->data['customer_rulenames'] = $this->customerclass->getCustomerRuleNames();
 
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Portal Setup')
                ->set_layout($this->layout)
                ->set('page_title', 'Portal Setup')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Portal Setup', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/portalsetup', $this->data);
    }
    
    /**
    * This function use for load Portal Settings Audit log in uigrid
    * 
    * @return json 
    */
    public function loadPortalAuditLog() {
        
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
                $fromdate = '';
                $todate = '';
                $params = array();
                
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
             
                if (trim($this->input->get('rulename')) != '') {
                    $params['cr.rulename'] = $this->input->get('rulename');
                }
                
                if (trim($this->input->get('fromdate')) != '') {
                    $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if (trim($this->input->get('todate')) != '') {
                    $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                
                $customerid =0;
                if (trim($this->input->get('customerid')) != '') {
                    $customerid = $this->input->get('customerid');
                    $params['pal.customerid'] = $customerid;

                    //intialize start page for uigrid
                    $start = ($page - 1) * $size;

                    //get audit Log Data
                    $auditLogData = $this->customerclass->getPortalAuditLog($size, $start, $field, $order, $fromdate, $todate, $params);

                    $trows = $auditLogData['trows'];
                    $data = $auditLogData['data'];

                    //format data
                    foreach($data as $key=>$value) {
                        $data[$key]['dateadded'] = format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
                    }
                
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
    * This function use for export Portal Auditlog
    * 
    * @return void 
    */
    public function exportPortalAuditLog() {
        
        
        $params = array();
        $order = 'desc';
        $field = 'dateadded';
        $fromdate = '';
        $todate = '';
        
        if (trim($this->input->get('rulename')) != '') {
            $params['cr.rulename'] = $this->input->get('rulename');
        }
        
        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }
        $customerid =0;
        if (trim($this->input->get('customerid')) != '') {
            $customerid = $this->input->get('customerid');
            $params['pal.customerid'] = $customerid;

            //get audit Log Data
            $auditLogData = $this->customerclass->getPortalAuditLog(NULL, 0, $field, $order, $fromdate, $todate, $params);
 
            $data = $auditLogData['data'];
        }
        else{
            $data = array();
        }

        $data_array = array();

        $heading = array('Date', 'Setting', 'Old Value', 'New Value', 'Edited By');
        $this->load->library('excel');

        $name = 'settings';
        
        //format data for excel
        foreach ($data as $row)
        { 
            $result = array();
            
            $result[] = format_date($row['dateadded'], RAPTOR_DISPLAY_DATEFORMAT);
            $result[] = $row['setting'];
            $result[] = $row['oldvalue'];
            $result[] = $row['newvalue'];
            $result[] = $row['editedby'];

            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "portalauditlog.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Audit_log", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download($name.'_portalauditlog.xls', $data);
    }
    
    /**
    * This function use for load Portal Settings in uigrid
    * 
    * @return json 
    */
    public function loadPortalSettings() {
        
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
                //get customerid
                $customerid =0;
                if (trim($this->input->get('customerid')) != '') {
                    $customerid = $this->input->get('customerid');
                    $filter = '';
                    if (trim($this->input->get('filtertext')) != '') {
                        $filter = $this->input->get('filtertext');
                    }
                    //get User Security Data
                    $portalSettingsData = $this->customerclass->getPortalSettings($customerid, $filter);

                    $trows = count($portalSettingsData);
                    $data = array();
 
                    //format data
                    foreach($portalSettingsData as $key=>$value) {

                        $data[] = array(
                            'rulename_id'       => $value['rulename_id'],
                            'caption'           => $value['caption'],
                            'valuetype'         => $value['valuetype'],
                            'value'             => $value['value'] == NULL ? $value['default_rule_value'] : $value['value'],
                            'is_sitecontact'    => $value['is_sitecontact'] == NULL ? $value['default_is_sitecontact'] : $value['is_sitecontact'],
                            'is_sitefm'         => $value['is_sitefm'] == NULL ? $value['default_is_sitefm'] : $value['is_sitefm'],
                            'is_master'         => $value['is_master'] == NULL ? $value['default_is_master'] : $value['is_master'] 
                        );

                    }
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
    * This function use for export Portal Auditlog
    * 
    * @return void 
    */
    public function exportPortalSettings() {
        
        //get customerid
        $customerid =0;
        $portalSettingsData = array();
        if (trim($this->input->get('customerid')) != '') {
            $customerid = $this->input->get('customerid'); 
            $filter = '';
            if (trim($this->input->get('filtertext')) != '') {
                $filter = $this->input->get('filtertext');
            }

            //get User Security Data
            $portalSettingsData = $this->customerclass->getPortalSettings($customerid, $filter); 
  
        }
        $data_array = array();

        $heading = array('ID','Setting', 'Value', 'For Master', 'For Site FM', 'For Site Contact');
        $this->load->library('excel');
 
        //format data for excel
        foreach ($portalSettingsData as $row)
        { 
            $result = array();
            $value = $row['value'] == NULL ? $row['default_rule_value'] : $row['value'];
            $is_sitecontact = $row['is_sitecontact'] == NULL ? $row['default_is_sitecontact'] : $row['is_sitecontact'];
            $is_sitefm = $row['is_sitefm'] == NULL ? $row['default_is_sitefm'] : $row['is_sitefm'];
            $is_master = $row['is_master'] == NULL ? $row['default_is_master'] : $row['is_master'];
            $result[] = $row['rulename_id'];
            $result[] = $row['caption'];
            if($row['valuetype'] == 'S'){
                $result[] = $value;
            }
            elseif($row['valuetype'] == 'B'){
                if($value == 1){
                    $result[] = 'Yes';
                }
                else{
                    $result[] = 'No';
                }
            }
            if($is_master == 1){
                $result[] = 'Yes';
            }
            else{
                $result[] = 'No';
            }
            
            if($is_sitefm == 1){
                $result[] = 'Yes';
            }
            else{
                $result[] = 'No';
            }
            
            if($is_sitecontact == 1){
                $result[] = 'Yes';
            }
            else{
                $result[] = 'No';
            }
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "portal_settings.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); 
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Portal Settings", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('portal_settings.xls', $data);
    }
    
    /**
    * This function use for update portal settings
    *
    * @return json
    */
    public function updatePortalSettings() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $rulename_id = $this->input->post('rulename_id');
            if(!$rulename_id) {
               $message = 'id can not be null'; 
            }
            $customerid = $this->input->post('customerid');
            if(!$customerid) {
               $message = 'customer can not be null'; 
            }
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                 
                $updateData = array(
                    'rule_value' => trim($this->input->post('rule_value')),
                    'is_master' => trim($this->input->post('is_master')),
                    'is_sitefm' => trim($this->input->post('is_sitefm')),
                    'is_sitecontact' => trim($this->input->post('is_sitecontact')),
                    'modify_date' => date('Y-m-d H:i:s', time()),
                    'modify_user' => $this->session->userdata('raptor_contactid')
                );
                //get post data
                $rulename_id = $this->input->post('rulename_id');
                 
                $portalData = $this->customerclass->getPortalSettingsByRuleID($customerid, $rulename_id);
                if(count($portalData) > 0){
                    
                    $request = array(
                        'rulename_id'       => $rulename_id,
                        'customerid'        => $customerid,
                        'updateData'        => $updateData,
                        'logged_contactid'  => $this->session->userdata('raptor_contactid')
                    );
                    $this->customerclass->updatePortalSettings($request);
                }
                else{
                    
                    $updateData['customerid'] = $customerid;
                    $updateData['customer_rule_id'] = $rulename_id;
                    $updateData['create_date'] = date('Y-m-d H:i:s', time());
                    $updateData['create_user'] = $this->session->userdata('raptor_contactid');
                   
                    $request = array( 
                        'insertData'        => $updateData,
                        'logged_contactid'  => $this->session->userdata('raptor_contactid')
                    );
                    $this->customerclass->insertPortalSettings($request);
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
  
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }
    
}