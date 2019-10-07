<?php 
/**
 * Activitylogs Controller Class
 *
 * This is a Activitylogs controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Activitylogs Controller Class
 *
 * This is a Activitylogs controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Activitylogs
 * @filesource          Activitylogs.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Activitylogs extends MY_Controller {

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
     * activity logs
     * 
     * @return void
     */
    public function index()
    {
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/datepicker/datepicker3.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
        );

        $this->data['jsToLoad'] = array(
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/angular-bootstrap/ui-bootstrap-tpls-1.2.5.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/admin/admin.activitylog.js')
        );
         
        
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Activity Log')
                ->set_layout($this->layout)
                ->set('page_title', 'Activity Log')
                ->set('page_sub_title', '')
                ->set_breadcrumb('Admin', '')
                ->set_breadcrumb('Activity Log', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('admin/activitylog', $this->data);
    }
    
    /**
    * This function use for load contacts in uigrid
    * 
    * @return json 
    */
    public function loadActivityLogs() {
        
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
                $field = 'login';
                $filter = '';
                $params = array();
                $fromdate = '';
                $todate = '';

                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                    $filter = trim($this->input->get('filtertext')) != '' ? trim($this->input->get('filtertext')) : $filter;
                }

                if (trim($this->input->get('customerid')) != '') {
                    $params['c.customerid'] = $this->input->get('customerid');
                }
                
                if (trim($this->input->get('contactid')) != '') {
                    $params['c.contactid'] = $this->input->get('contactid');
                }
                if (trim($this->input->get('success')) != '') {
                    $params['l.success'] = $this->input->get('success');
                }
                
                if (trim($this->input->get('fromdate')) != '') {
                    $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
                }
                
                if (trim($this->input->get('todate')) != '') {
                    $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
                }

                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                 
                //get audit Log Data
                $activityLogData = $this->adminclass->getActivityLog($size, $start, $field, $order, $fromdate, $todate, $params);
        
                $trows = $activityLogData['trows'];
                $data = $activityLogData['data'];
                
                //format data
                foreach($data as $key=>$value) {
                    $data[$key]['login'] = format_datetime($value['login'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                }
 
                $success->setData($data); 
                $success->setTotal($trows);
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
    * This function use for export filtered grid
    * 
    * @return void 
    */
    public function exportActivityLogs() {
        
        
        $order = 'desc';
        $field = 'login';
  
        $fromdate = '';
        $todate = '';
        $params = array();

        if (trim($this->input->get('customerid')) != '') {
            $params['c.customerid'] = $this->input->get('customerid');
        }

        if (trim($this->input->get('contactid')) != '') {
            $params['c.contactid'] = $this->input->get('contactid');
        }
        if (trim($this->input->get('success')) != '') {
            $params['l.success'] = $this->input->get('success');
        }

        if (trim($this->input->get('fromdate')) != '') {
            $fromdate = to_mysql_date($this->input->get('fromdate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        if (trim($this->input->get('todate')) != '') {
            $todate = to_mysql_date($this->input->get('todate'), RAPTOR_DISPLAY_DATEFORMAT);
        }

        //get customerid
        $activityLogData = $this->adminclass->getActivityLog(NULL, 0, $field, $order, $fromdate, $todate, $params);
        
 

        $data_array = array();

        $heading = array('Company', 'Contact Name', 'UserName', 'Login Time', 'Success', 'IP Address');
        $this->load->library('excel');

  
        
        //format data for excel
        foreach ($activityLogData['data'] as $row)
        { 
            $result = array();
            
            $result[] = $row['companyname'];
            $result[] = $row['firstname'];
            $result[] = $row['username'];
            $result[] = format_datetime($row['login'], RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
            $result[] = $row['success'] == 1 ? 'Yes' : 'No';
            $result[] = $row['ipaddress'];
            $data_array[] = $result;
        }
        
        // make temporary directory if not exists
        $dir = "./temp";
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, TRUE);
        }
        
        $file_name = "activitylogs.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $this->excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);

        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        //export data to excel and force download to user
        $this->excel->Exportexcel("Activity Logs", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('activitylogs.xls', $data);
    }
    
    /**
    * This function search customers for autocomplete
    * 
    * @return json 
    */
    public function loadCustomerSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $searchKeyword = trim($this->input->get('search'));
            $custtype = 'client';
             
            if( !isset($searchKeyword) ){
                $message = 'Search Keyword cannot be null.';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->customerclass->getCustomerSearch($searchKeyword, $custtype);
                $success->setData($data);
                $success->setTotal(count($data));
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
    * This function search contacts for autocomplete
    * 
    * @return json 
    */
    public function loadContactSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $searchKeyword = trim($this->input->get('search'));
            $customerid= 0;
            if($this->input->get('customerid')) {
                $customerid = trim($this->input->get('customerid'));
            }
            
            if( !isset($searchKeyword) )
                $message = 'Search Keyword cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $data = $this->customerclass->getCustomerContacts($customerid, 0, $searchKeyword);
               
                $success->setData($data);
                $success->setTotal(count($data));
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