<?php 
/**
 * Budgets Controller Class
 *
 * This is a Budgets controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Budgets Controller Class
 *
 * This is a Budgets controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Budgets
 * @filesource          Budgets.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Budgets extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
 
        //  Load libraries 
        $this->load->library('budget/BudgetClass');
        
        $customerid =$this->session->userdata('raptor_customerid'); 
        $this->data['budget_setting']= $this->budgetclass->getBudgetSettingByCustomerid($customerid);
    }
    
    /**
     * 
     * @return void
     */
    public function index()
    {
        redirect('budgets/bysite');
    }

    /**
     * show budget grid by site
     * 
     * @return void
     */
    public function bySite()
    {
        $un= $this->session->userdata('raptor_email'); 
        $contactid =$this->session->userdata('raptor_contactid');
        $customerid =$this->session->userdata('raptor_customerid');
 
      
        if($this->session->userdata('raptor_role') == 'site contact') {
            $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid, 'contactid'=>$contactid), 'contactid, firstname');
        }
        elseif ($this->session->userdata('raptor_role') == 'sitefm') {
            $subordinate_emails = $this->customerclass->getSubordinateEmails($un);
            $this->data['contacts'] =$this->customerclass->getSelfSubordinateContact($customerid, $contactid , $subordinate_emails);
        }
        else{
            $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid,'role'=>'sitefm'),'contactid, firstname');
        }
        
        
        $startmonth=1;
        if(count($this->data['budget_setting'])>0){
             $startmonth= (int)$this->data['budget_setting']['startmonth'];
        }

        if($startmonth>9){
            $fromdate=date('Y').'-'.$startmonth.'-01';
        }
        else{
            $fromdate=date('Y').'-0'.$startmonth.'-01';
        }
        $fromdate=strtotime($fromdate);
        $todate=strtotime("+1 Years", $fromdate);
        $todate=strtotime("-1 Days", $todate);
        $defaultFY=date('Ym', $fromdate).'-'.date('Ym', $todate);

        $this->data['defaultFY']=$defaultFY;
        $this->data['sites'] = $this->customerclass->getCustomerSitesByRole($customerid, $contactid, $un, $this->session->userdata('raptor_role'));
 
        $this->data['states'] = $this->sharedclass->getStates(1);

         $this->data['cssToLoad'] = array(
            base_url('plugins/datatables/dataTables.bootstrap.css'),
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/select2/select2.min.css'),
            base_url('plugins/iCheck/square/grey.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/datatables/jquery.dataTables.min.js'),
            base_url('plugins/datatables/dataTables.bootstrap.min.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),

            base_url('plugins/select2/select2.full.min.js'),
            base_url('plugins/iCheck/icheck.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('assets/js/budgets/budgets.index.js'),
            base_url('assets/js/budgets/budgets.bysite.js')
        );
            
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Budgets')
            ->set_layout($this->layout)
            ->set('page_title', 'My Budgets (By Site)')
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Budgets', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('budgets/budgetlist', $this->data);
          
    }
    
    /**
     * show budget grid by site
     * 
     * @return void
     */
    public function byGlCode()
    {
        $un= $this->session->userdata('raptor_email'); 
        $contactid =$this->session->userdata('raptor_contactid');
        $customerid =$this->session->userdata('raptor_customerid');
 
      
        if($this->session->userdata('raptor_role') == 'site contact') {
            $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid, 'contactid'=>$contactid), 'contactid, firstname');
        }
        elseif ($this->session->userdata('raptor_role') == 'sitefm') {
            $subordinate_emails = $this->customerclass->getSubordinateEmails($un);
            $this->data['contacts'] =$this->customerclass->getSelfSubordinateContact($customerid, $contactid , $subordinate_emails);
        }
        else{
            $this->data['contacts'] =$this->customerclass->getContactsByParams(array('customerid'=>$customerid,'role'=>'sitefm'),'contactid, firstname');
        }
        
        
        $startmonth=1;
        if(count($this->data['budget_setting'])>0){
             $startmonth= (int)$this->data['budget_setting']['startmonth'];
        }

        if($startmonth>9){
            $fromdate=date('Y').'-'.$startmonth.'-01';
        }
        else{
            $fromdate=date('Y').'-0'.$startmonth.'-01';
        }
        $fromdate=strtotime($fromdate);
        $todate=strtotime("+1 Years", $fromdate);
        $todate=strtotime("-1 Days", $todate);
        $defaultFY=date('Ym', $fromdate).'-'.date('Ym', $todate);

        $this->data['defaultFY']=$defaultFY;
        $this->data['sites'] = $this->customerclass->getCustomerSitesByRole($customerid, $contactid, $un, $this->session->userdata('raptor_role'));
        $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($this->data['loggeduser']->customerid, 'E');
        
        $this->data['states'] = $this->sharedclass->getStates(1);

         $this->data['cssToLoad'] = array(
            base_url('plugins/datatables/dataTables.bootstrap.css'),
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/select2/select2.min.css'),
            base_url('plugins/iCheck/square/grey.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/datatables/jquery.dataTables.min.js'),
            base_url('plugins/datatables/dataTables.bootstrap.min.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'),

            base_url('plugins/select2/select2.full.min.js'),
            base_url('plugins/iCheck/icheck.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('assets/js/budgets/budgets.index.js'),
            base_url('assets/js/budgets/budgets.byglcode.js')
        );
            
        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Budgets')
            ->set_layout($this->layout)
            ->set('page_title', 'My Budgets (By GL Code)')
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Budgets', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('budgets/budgetbyglcode', $this->data);
          
    }
    
     /**
    * This function use for load Budget By Site in uigrid
    * 
    * @return json 
    */
    public function loadBudgetsBySite() {
        
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
                $order = 'asc';
                $field = 'labelid';
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
                    $params['a.sitestate'] = $this->input->get('state');
                }
                
                if (trim($this->input->get('contactid')) != '') {
                    $params['a.contactid'] = $this->input->get('contactid');
                }
                if($this->input->get('year')!=null)
                {
                    $selectedyear =  trim($this->input->get('year'));
                    $year =  explode('-', $selectedyear);
                    $FromYearMonth=$year[0];
                    $ToYearMonth=$year[1];
                    $FromYear=  (int)substr($FromYearMonth, 0,4);
                    $FromMonth=(int)substr($FromYearMonth, 4,2);

                    $ToYear=  (int)substr($ToYearMonth, 0,4);
                    $ToMonth=(int)substr($ToYearMonth, 4,2);
                }
                else{
                    $FromYear=date('Y');
                    $startmonth=1;
                    if(count($this->data['budget_setting'])>0){
                         $startmonth= (int)$this->data['budget_setting']['startmonth'];
                    }
                    $FromMonth = $startmonth;

                }


                if($FromMonth>9){
                    $fromdate=$FromYear.'-'.$FromMonth.'-01';
                }
                else{
                    $fromdate=$FromYear.'-0'.$FromMonth.'-01';
                }

                $todate=strtotime("+1 Years",strtotime($fromdate));
                $todate=strtotime("-1 Days", $todate);
                $ToYear=date('Y', $todate);
                $ToMonth=date('m', $todate);
                $todate=date("Y-m-d", $todate);
                $FromYearMonth=($FromYear*100)+$FromMonth;
                $ToYearMonth=($ToYear*100)+$ToMonth; 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
              
                $budgetdata = $this->budgetclass->getBudgetsDataBySite($this->session->userdata('raptor_contactid'), $FromYearMonth, $ToYearMonth, $fromdate, $todate, $this->input->get('band'), $size, $start, $field, $order, $filter, $params);
       
                $trows = $budgetdata['trows'];
                $data = $budgetdata['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['lastupdated'] = format_datetime($value['lastupdated'],RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                    $data[$key]['detail'] =  count($this->data['budget_setting'])>0 && $this->data['budget_setting']['isannual']!=1 ? TRUE : FALSE;
 
                    $data[$key]['annualbudget'] = format_amount($value['annualbudget']);
                    $data[$key]['actual'] = format_amount($value['actual']);
                    $data[$key]['remaining'] = format_amount($value['remaining']);
                    $data[$key]['pctspend'] = number_format($value['pctspend'], 2);
                     
                    if($value['pctspend'] >= 100){
                        $data[$key]['textcolor']= 'text-red';
                    }
                    elseif($value['pctspend'] >= 75){
                        $data[$key]['textcolor']= 'text-red';
                      
                    }
                    elseif($value['pctspend'] >= 50){
                        $data[$key]['textcolor']= 'text-orange';
                    }
                    else{
                        $data[$key]['textcolor']= '';
                       
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
    * This function use for load Budget By Gl Code in uigrid
    * 
    * @return json 
    */
    public function loadBudgetsByGlCode() {
        
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
                $order = 'asc';
                $field = 'g.accountcode';
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

                if($this->input->get('year')!=null)
                {
                    $selectedyear =  trim($this->input->get('year'));
                    $year =  explode('-', $selectedyear);
                    $FromYearMonth=$year[0];
                    $ToYearMonth=$year[1];
                    $FromYear=  (int)substr($FromYearMonth, 0,4);
                    $FromMonth=(int)substr($FromYearMonth, 4,2);

                    $ToYear=  (int)substr($ToYearMonth, 0,4);
                    $ToMonth=(int)substr($ToYearMonth, 4,2);
                }
                else{
                    $FromYear=date('Y');
                    $startmonth=1;
                    if(count($this->data['budget_setting'])>0){
                         $startmonth= (int)$this->data['budget_setting']['startmonth'];
                    }
                    $FromMonth = $startmonth;

                }


                if($FromMonth>9){
                    $fromdate=$FromYear.'-'.$FromMonth.'-01';
                }
                else{
                    $fromdate=$FromYear.'-0'.$FromMonth.'-01';
                }

                $todate=strtotime("+1 Years",strtotime($fromdate));
                $todate=strtotime("-1 Days", $todate);
                $ToYear=date('Y', $todate);
                $ToMonth=date('m', $todate);
                $todate=date("Y-m-d", $todate);
                $FromYearMonth=($FromYear*100)+$FromMonth;
                $ToYearMonth=($ToYear*100)+$ToMonth; 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
              
                $budgetdata = $this->budgetclass->getBudgetsDataByGlCode($this->session->userdata('raptor_contactid'), $FromYearMonth, $ToYearMonth, $fromdate, $todate, $this->input->get('band'), $size, $start, $field, $order, $filter, $params);
       
                $trows = $budgetdata['trows'];
                $data = $budgetdata['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    $data[$key]['lastupdated'] = format_datetime($value['lastupdated'],RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);
                    $data[$key]['detail'] =  count($this->data['budget_setting'])>0 && $this->data['budget_setting']['isannual']!=1 ? TRUE : FALSE;
 
                    $data[$key]['annualbudget'] = format_amount($value['annualbudget']);
                    $data[$key]['actual'] = format_amount($value['actual']);
                    $data[$key]['remaining'] = format_amount($value['remaining']);
                    $data[$key]['pctspend'] = number_format($value['pctspend'], 2);
                     
                    if($value['pctspend'] >= 100){
                        $data[$key]['textcolor']= 'text-red';
                    }
                    elseif($value['pctspend'] >= 75){
                        $data[$key]['textcolor']= 'text-red';
                      
                    }
                    elseif($value['pctspend'] >= 50){
                        $data[$key]['textcolor']= 'text-orange';
                    }
                    else{
                        $data[$key]['textcolor']= '';
                       
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
     * create annual budget by site
     * 
     * @return json
     */
    public function createAnnualBudgetBySite() { 
            
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $amount=$this->input->post('annualbudget');
            $siteid=$this->input->post('siteid');
            $selyear=$this->input->post('selyear');
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $year=  explode('-', $selyear);
                $FromYearMonth=$year[0];
                $ToYearMonth=$year[1];
                
                
                if($siteid != 0){
                    $budgets_data = $this->budgetclass->getSiteBudgetDetail($siteid, $FromYearMonth, $ToYearMonth);
                }
                else {
                    $customerid =$this->session->userdata('raptor_customerid');
                    $budgets_data = $this->budgetclass->getCustomerBudgetDetail($customerid, $FromYearMonth, $ToYearMonth);
                }
                 
                if(count($budgets_data)>0){
                    $data = array('success'=>FALSE,'recordid'=>$budgets_data[0]['recordid']);  
                    $message = 'Monthly budgets already exist for this site';
                }
                else{
                    
                    $insertData = array(
                        'financialyear' => $selyear,
                        'amount'        => $amount,
                        'siteid'        => $siteid
                        
                    );
               
                    $request = array(
                        'insertData'       => $insertData,  
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
                    $this->budgetclass->insertAnnualBudgetBySite($request);
                     
                    $data = array('success'=>TRUE,'recordid'=>'');
                    $message = 'Annual Budget Created Successfully';
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
     * update annual budget by site
     * 
     * @return json
     */
    public function updateAnnualBudgetBySite() { 
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $amount=$this->input->post('annualbudget');
            $siteid=$this->input->post('siteid');
            $selyear=$this->input->post('selyear');
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $year=  explode('-', $selyear);
                $FromYearMonth=$year[0];
                $ToYearMonth=$year[1];
                
               

                if($siteid != 0){
                    $request = array(
                        'recordid'         => $siteid, 
                        'FromYearMonth'    => $FromYearMonth,
                        'ToYearMonth'      => $ToYearMonth,
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
                    $this->budgetclass->deleteBudget($request);
                }
                else {
                    $sitedata = $this->customerclass->getCustomerSitesByRole($this->data['loggeduser']->customerid, $this->data['loggeduser']->contactid, $this->data['loggeduser']->email, $this->session->userdata('raptor_role'));
  
                    foreach ($sitedata as $key => $value) {
                        $request = array(
                            'recordid'         => $value['id'], 
                            'FromYearMonth'    => $FromYearMonth,
                            'ToYearMonth'      => $ToYearMonth,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $this->budgetclass->deleteBudget($request);
                  
                    }
                     
                }
 
                $insertData = array(
                    'financialyear' => $selyear,
                    'amount'        => $amount,
                    'siteid'        => $siteid
                );

                $request = array(
                    'insertData'       => $insertData,  
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->budgetclass->insertAnnualBudgetBySite($request);
                
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
     * add annual budget
     * 
     * @return json
     */
    public function addAnnualBudgetBySite() { 

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $amount=$this->input->post('annualbudget');
            $siteid=$this->input->post('siteid');
            $selyear=$this->input->post('selyear');
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $insertData = array(
                    'financialyear' => $selyear,
                    'amount'        => $amount,
                    'siteid'        => $siteid

                );

                $request = array(
                    'insertData'       => $insertData,  
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->budgetclass->insertAnnualBudgetBySite($request);
                 
                
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
     * update budget data
     * 
     * @return void
     */   
        
    public function updateBudget() {
        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $recordid=$this->input->post('recordid');
            $selectedyear=$this->input->post('year');
            $amounts=$this->input->post('amount');    
            
            if($recordid == "" || count($amounts) == 0){
                $message = 'Request Data invalid';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $updateData = array();
                
                foreach ($amounts as $key => $value) {
                    $updateData[] = array(
                        "id"         => $key,
                        'amount'     => $value,
                        'modifyuser' => $this->session->userdata('raptor_email'),
                        'modifydate' => date('Y-m-d H:i:s') 
                    );
                }
                
                $request = array(
                    'updateData'       => $updateData,  
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                
                $this->budgetclass->updateBudget($request);
                $message = 'Request Data updated'; 
 
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
     * get budget detail by site
     * @param string $recordid
     * @param string $year
     * 
     * @return json
     */ 
    public function loadBudgetDetailBySite($recordid, $year)
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $selectedyear=$year;
            $year=  explode('-', $year);
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];

            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $budgetData = $this->budgetclass->getSiteBudgetDetail($recordid, $FromYearMonth, $ToYearMonth);
                
                $data = array();
                foreach($budgetData as $row){

                        $date=$row['yearno'].'-'.$row['monthno']. '-01';
                        $date=  strtotime($date);
                        $yearmonth=date('Ym', $date);

                        $monthname=date('M Y', $date);
                        if(count($this->data['budget_setting'])>0){  
                            if($this->data['budget_setting']['ismonthly']==1){
                                $monthname=date('M Y', $date);
                            } elseif($this->data['budget_setting']['isquarterly'] == 1){ 
                                 $todate=strtotime("+3 month", $date);
                                 $todate=strtotime("-1 Days", $todate);
                                 $monthname=date('M Y', $date).' to '.date('M Y', $todate);
                            }elseif($this->data['budget_setting']['isannual']==1){ 
                                $todate=strtotime("+1 Years", $date);
                                $todate=strtotime("-1 Days", $todate);
                                $monthname=date('M Y', $date).' to '.date('M Y', $todate);
                            }  
                        }  
                        $data[] = array(
                            'month'     => $monthname,
                            'id'        => $row['id'],
                            'amount'    => $row['amount']
                        );
                        
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
     * download excel by site
     * 
     * @return excel
     */ 
    public function downloadExcelBySite() 
    {
        $selectedyear ='';
        if($this->input->get_post('year')!=null)
        {
            $selectedyear =  trim($this->input->get_post('year'));
            $year =  explode('-', $selectedyear);
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);
        }
        else{
            $FromYear=date('Y');
            $FromMonth = (int) $this->data['budget_setting']['startmonth'];
             
        }

        
        if($FromMonth>9){
            $fromdate=$FromYear.'-'.$FromMonth.'-01';
        }
        else{
            $fromdate=$FromYear.'-0'.$FromMonth.'-01';
        }

        $todate=strtotime("+1 Years",strtotime($fromdate));
        $todate=strtotime("-1 Days", $todate);
        $ToYear=date('Y', $todate);
        $ToMonth=date('m', $todate);
        $todate=date("Y-m-d", $todate);
        $FromYearMonth=($FromYear*100)+$FromMonth;
        $ToYearMonth=($ToYear*100)+$ToMonth;
        
        $params = array();
       
        $order = 'asc';
        $field = 'labelid';
        if($this->input->get_post('contactid')!=null)
        {
            $params['a.contactid'] =$this->input->get_post('contactid');
        }
        if($this->input->get_post('state')!=null)
        {
            $params['a.sitestate'] =trim($this->input->get_post('state'));
        }
       
        
        
        $budgets_data = $this->budgetclass->getBudgetsDataBySite($this->session->userdata('raptor_contactid'), $FromYearMonth, $ToYearMonth, $fromdate, $todate, $this->input->get_post('band'), NULL, 0, $field, $order, '', $params);
      
        $data_array = array();
        $heading = array('Address','Suburb','State','Budget','Actual Spend','Remaining','%age Spent','Manager','Site Ref 1','Site Ref 2','Last Updated');
        $this->load->library('excel');

        foreach($budgets_data['data'] as $row1)
        { 
            $result = array();

            $result[]=str_replace('<br>', ' ', $row1['siteaddress']);
            $result[]=$row1['sitesuburb'];
            $result[] =$row1['sitestate'];
          
            
            $result[]=format_amount($row1['annualbudget']);
            $result[]=format_amount($row1['actual']);
            $result[]=format_amount($row1['remaining']);
            $result[]=number_format($row1['pctspend'],  2). ' %';
            $result[]=$row1['sitefm'];
            $result[]=$row1['siteref1'];
            $result[]=$row1['siteref2'];
            $result[]=format_datetime($row1['lastupdated'],RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);

            $data_array[]=$result;
        }

        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }

        $file_name="my_budget.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
      
        
        $this->excel->Exportexcel("My Budget", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('my_budget'.$selectedyear.'.xls', $data);
    }
    
        /**
     * download excel by gl code
     * 
     * @return excel
     */ 
    public function downloadExcelByGlCode() 
    {
        $selectedyear ='';
        if($this->input->get_post('year')!=null)
        {
            $selectedyear =  trim($this->input->get_post('year'));
            $year =  explode('-', $selectedyear);
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);
        }
        else{
            $FromYear=date('Y');
            $FromMonth = (int) $this->data['budget_setting']['startmonth'];
             
        }

        
        if($FromMonth>9){
            $fromdate=$FromYear.'-'.$FromMonth.'-01';
        }
        else{
            $fromdate=$FromYear.'-0'.$FromMonth.'-01';
        }

        $todate=strtotime("+1 Years",strtotime($fromdate));
        $todate=strtotime("-1 Days", $todate);
        $ToYear=date('Y', $todate);
        $ToMonth=date('m', $todate);
        $todate=date("Y-m-d", $todate);
        $FromYearMonth=($FromYear*100)+$FromMonth;
        $ToYearMonth=($ToYear*100)+$ToMonth;
        
        $params = array();
       
        $order = 'asc';
        $field = 'g.accountcode';
        
        $budgets_data = $this->budgetclass->getBudgetsDataByGlCode($this->session->userdata('raptor_contactid'), $FromYearMonth, $ToYearMonth, $fromdate, $todate, $this->input->get_post('band'), NULL, 0, $field, $order, '', $params);
      
        $data_array = array();
        $heading = array('GL Code','Description','Annual Budget','Actual Spend','Remaining','%age Spent','Last Updated');
        $this->load->library('excel');

        foreach($budgets_data['data'] as $row1)
        { 
            $result = array();

            $result[]= $row1['accountcode'];
            $result[]=$row1['accountname'];
            $result[]=format_amount($row1['annualbudget']);
            $result[]=format_amount($row1['actual']);
            $result[]=format_amount($row1['remaining']);
            $result[]=number_format($row1['pctspend'],  2). ' %';
            $result[]=format_datetime($row1['lastupdated'],RAPTOR_DISPLAY_DATEFORMAT,RAPTOR_DISPLAY_TIMEFORMAT);

            $data_array[]=$result;
        }

        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }

        $file_name="my_budget.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
      
        
        $this->excel->Exportexcel("My Budget", $dir, $file_name, $heading, $data_array);
        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('my_budget'.$selectedyear.'.xls', $data);
    }

    /**
     * import budget excel by site
     * 
     * @return json
     * 
     */
    public function importBudgetExcelBySite() 
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerid =$this->session->userdata('raptor_customerid');
    
            $financialYear=$this->input->post('ifyear');
            $updateoption=$this->input->post('updateoption');
            $year=  explode('-', $financialYear);
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);

            $dir = "./temp";
            if(!is_dir($dir))
            {
                    mkdir($dir, 0755, true);
            }
            $filen = $_FILES['importfile']['name'];
            $ext = pathinfo($filen, PATHINFO_EXTENSION);
            $filename="import_file.".$ext;
            $config['upload_path'] = $dir;
            $config['allowed_types'] = "xls|xlsx";
            $config['file_name'] = "import_file";
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("importfile")){
                  $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();

            }
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $this->load->library('excel');

                $objPHPExcel = PHPExcel_IOFactory::load($dir.'/'.$filename);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
           
                if(count($sheetData)<=1){
                    $success = SuccessClass::initialize(FALSE);
                    $message="Invalid Excel File";
                }
                else{
                    $startmonth=1;
                    $totalrecord = 12;
                    $budgetopt = 'monthly';
                    if(count($this->data['budget_setting'])>0){
                        $startmonth= (int)$this->data['budget_setting']['startmonth'];
                        $totalrecord =  $this->data['budget_setting']['totalrecord'];
                        $budgetopt =  $this->data['budget_setting']['budgetopt'];
                    }
                    
                    
                    $customerid =$this->session->userdata('raptor_customerid');
         
                    $subworksid=0;
                    $itemid=0;
                    $categoryid=0;
                    $typeid=0;

                    if(count($this->data['budget_setting'])>0){  

                        if($this->data['budget_setting']['useworkstype']!=0){
                            $type_data=$this->budgetclass->getBudgetOption($customerid,'subworks');
                            if(count($type_data)>0)
                            {
                                $subworksid=$type_data[0]['id'];
                            }
                        }
                        if($this->data['budget_setting']['useitem']!=0){
                            $type_data=$this->budgetclass->getBudgetOption($customerid,'item');
                            if(count($type_data)>0)
                            {
                                $itemid=$type_data[0]['id'];
                            }
                        } 
                        if($this->data['budget_setting']['usecategory']!=0){
                            $type_data=$this->budgetclass->getBudgetOption($customerid,'category');
                            if(count($type_data)>0)
                            {
                                $categoryid=$type_data[0]['id'];
                            }
                        } 
                        $type_data=$this->budgetclass->getBudgetOption($customerid,'type');
                        if(count($type_data)>0)
                        {
                            $typeid=$type_data[0]['id'];
                        }
                    }

                    
                    $insertData = array();
                    
                    foreach($sheetData as $key=>$value) {

                        if($key == 1) {
                            if(count($value)!=$totalrecord+7){
                                $success = SuccessClass::initialize(FALSE);
                                $message="Invalid Import Excel budget format ";
                                break;
                            }
                            continue;
                        }


                        $site_data = $this->customerclass->getAddressByParams(array('customerid'=>$customerid, 'labelid'=>$value['A']));

                        if(count($site_data)==0){
                            $success = SuccessClass::initialize(FALSE);
                            $message="Invalid Site Id in Excel row no. ".$key;
                            break;
                        }

                        $budgets_data = $this->budgetclass->getSiteBudgetDetail($value['A'],$FromYearMonth,$ToYearMonth);
                        if(count($budgets_data)==0 || $updateoption==1){
                            
                            if(count($budgets_data)>0){
                                $request = array(
                                    'recordid'         => $value['A'], 
                                    'FromYearMonth'    => $FromYearMonth,
                                    'ToYearMonth'      => $ToYearMonth,
                                    'logged_contactid' => $this->data['loggeduser']->contactid
                                );
                                $this->budgetclass->deleteBudget($request);
                            }
                            
                            $date=$FromYear.'-'.$FromMonth. '-01';
                            $date=  strtotime($date);
                           
                            $colH = 'G';
                            for($i=1;$i<=$totalrecord;$i++){
                                $colH++; 
                                if($i!=1){
                                    $date=strtotime("+".(12/$totalrecord)." month", $date);
                                }
                                if(isset($value[$colH])){
                                    $bamount = (int)$value[$colH];
                                }
                                else{
                                    $bamount = 0;
                                }
                                
                                $insertData[] = array(
                                    'recordid'  => $value['A'],
                                    'monthno'   => date('m', $date),
                                    'yearno'    => date('Y', $date),
                                    'monthsort' => $i,
                                    'subworksid'=> $subworksid,
                                    'itemid'    => $itemid,
                                    'typeid'    => $typeid,
                                    'categoryid'=> $categoryid,
                                    'amount'    => $bamount,
                                    'hours'     => isset($this->data['budget_setting']['showhours'])?$this->data['budget_setting']['showhours']:0,
                                    'createuser'=> $this->session->userdata('raptor_email'),
                                    'createdate'=> date('Y-m-d H:i:s') 
                                );
                            }
                            
                        }
                    }

                    if(count($insertData)>0){
                        $request = array(
                            'insertData'       => $insertData,  
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $this->budgetclass->insertMonthlyBudget($request);
                    }
                    $message = 'Document Data upload successfully.'; 

                    $success->setData($data);
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
     * download import file by site
     * 
     * @param type $FromYearMonth
     * @param type $ToYearMonth
     *      
     * @return void
     */
    public function downloadImportFileBySite($FromYearMonth, $ToYearMonth) 
    {
            
        $financialYear=$FromYearMonth.'-'.$ToYearMonth;
 
        $FromYear=  (int)substr($FromYearMonth, 0,4);
        $FromMonth=(int)substr($FromYearMonth, 4,2);

        $ToYear=  (int)substr($ToYearMonth, 0,4);
        $ToMonth=(int)substr($ToYearMonth, 4,2);
        $customerid =$this->session->userdata('raptor_customerid');
         
        $heading = array('labelid','Address','Suburb','State','Site Ref 1','Site Ref 2','Manager');  
        $date=$FromYear.'-'.$FromMonth. '-01';
        $date=  strtotime($date);
       
        $totalrecord = 0;

        if(count($this->data['budget_setting'])>0){
        
            $totalrecord = $this->data['budget_setting']['totalrecord'];
            if($this->data['budget_setting']['ismonthly']==1){
                 for($i=1;$i<=12;$i++){
                    $heading[]=date('M Y', $date);
                    $date=strtotime("+1 month", $date);

                 }

            } elseif($this->data['budget_setting']['isquarterly'] == 1){ 
                for($i=1;$i<=4;$i++){
                    $todate=strtotime("+3 month", $date);
                    $todate=strtotime("-1 Days", $todate);
                    $heading[]=date('M Y', $date).' to '.date('M Y', $todate);
                    $date=strtotime("+3 month", $date);
                }
            }elseif($this->data['budget_setting']['isannual']==1){ 
                $todate=strtotime("+1 Years", $date);
                $todate=strtotime("-1 Days", $todate);
                $heading[]=date('M Y', $date).' to '.date('M Y', $todate);
            } 
        }

        $this->load->library('excel');
 
        $budgets_data = $this->customerclass->getCustomerSiteAddress($customerid, '', '', TRUE);
        
        $data_array = array();
        //Loop Result

        foreach($budgets_data as $row1)
        { 
            $result = array();
            $result[]=$row1['labelid'];
            $result[]= $row1['siteline1'] .' '. $row1['siteline2'];
            $result[]=$row1['sitesuburb'];
            $result[] =$row1['sitestate'];
            $result[]=$row1['siteref1'];
            $result[]=$row1['siteref2'];
            $result[]=$row1['sitefm'];


            $data = $this->budgetclass->getSiteBudgetDetail($row1['labelid'], $FromYearMonth, $ToYearMonth);
            foreach($data as $rowc){
                $result[]=$rowc['amount'];
            }

            $data_array[]=$result;
        }


        $file_name="my_budget_import_file_by_site.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        
        $colH = 'G';
        for($i=1;$i<=$totalrecord;$i++){
            $colH++; 
            $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(10);
        }
      
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }
        $this->excel->Exportexcel("My Budget", $dir, $file_name, $heading, $data_array);

        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('my_budget_import_file_by_site'.$financialYear.'.xls', $data);
   	 	  
    }
    
    /**
     * get budget widget data
     * 
     * @return json
     */
    public function getBudgetWidgetData() { 
            
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            $selectedMonthDate = trim($this->input->post('month'));
            
            if( !isset($selectedMonthDate) ) {
                $message = 'month cannot be null.';
            }
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $customerid =$this->session->userdata('raptor_customerid');
                $currentDate =  trim($this->input->post('cdate'));
                $nextDate =  trim($this->input->post('ndate'));
                $jobid =  trim($this->input->post('jobid'));
                $glcodeid =  trim($this->input->post('glcode'));
                $glcode = '';
                
                if($glcodeid == '' && ($jobid != '' || $jobid != 0)) {
                    $this->load->library('job/JobClass');
                    $jobData = $this->jobclass->getJobById($jobid);
                    $glcodeid = $jobData['glcodeid'];
                    $glcode = $jobData['glcode'];
                } else {
                    $glCodeData = $this->budgetclass->getGlcodeById($glcodeid);
                    if(count($glCodeData) > 0) {
                        $glcodeid = $glCodeData['id'];
                        $glcode = $glCodeData['glcode'];
                    }
                }
                
                $xaxis = array();
                $series = array();
                $colors = array('green', 'orange', 'blue', 'grey');

                $series = array(
                    array(
                        'index' => 3,
                        'name' => 'Invoiced',
                        'data' => array(0, 0)
                    ),
                    array(
                        'index' => 2,
                        'name' => 'Work in Progress',
                        'data' => array(0, 0)
                    ),
                    array(
                        'index' => 1,
                        'name' => 'Amount',
                        'data' => array(0, 0)
                    ),
                    array(
                        'index' => 0,
                        'name' => 'Budget',
                        'data' => array(0, 0)
                    )
                );
                
                $monthArray = array(
                    array(
                        'month' => format_date($currentDate, 'm'),
                        'year' => format_date($currentDate, 'Y'),
                        'name' => format_date($currentDate, 'M')
                    ),
                    array(
                        'month' => format_date($nextDate, 'm'),
                        'year' => format_date($nextDate, 'Y'),
                        'name' => format_date($nextDate, 'M')
                    )
                );
                
                $wait = FALSE;
                $currentSelectedMonth = 0;
                if($selectedMonthDate == 'wait') {
                    $wait = TRUE;
                } else {
                    $currentSelectedMonth = format_date($selectedMonthDate, 'm');
                }
                
                $remainingBudget = array(0, 0);
                foreach($monthArray as $key=>$value) {
                    $budgetWidgetData = $this->budgetclass->getBudgetWidgetData($customerid, $value['month'], $value['year'], $jobid, $glcodeid, $glcode);
                    
                    
                    //$budgetWidgetData['invoiced'] = 3000;
                    //$budgetWidgetData['workinprogress'] = 500;
                    //$budgetWidgetData['amount'] = 1500;
                    //$budgetWidgetData['budget'] = 4000;
                    
                    
                    $series[0]['data'][$key] = $budgetWidgetData['invoiced'];
                    $series[1]['data'][$key] = $budgetWidgetData['workinprogress'];
                    if($currentSelectedMonth == $value['month']) {
                        $series[2]['data'][$key] = $budgetWidgetData['amount'];
                    }
                    $series[3]['data'][$key] = $budgetWidgetData['budget'];
                    $leftBudget = $budgetWidgetData['budget'] - $budgetWidgetData['invoiced'];
                    if($leftBudget < 0) {
                        array_push($xaxis, 'n-'.strtoupper($value['name']));
                        $colors[3] = 'red';
                    } else {
                        array_push($xaxis, strtoupper($value['name']));
                    }
                    
                    /*if($key == 0) {
                        $remainingBudget[$key] = -44.66;//$leftBudget;
                        array_push($xaxis, 'n-'.strtoupper($value['name']));
                    } else {
                        $remainingBudget[$key] = 22.66;//$leftBudget;
                        array_push($xaxis, ''.strtoupper($value['name']));
                    }*/
                    $remainingBudget[$key] = $leftBudget;
                }
                
                $data = array(
                    'xaxis'             => $xaxis,
                    'series'            => $series,
                    'remainingBudget'   => $remainingBudget,
                    'colors'            => $colors
                );
                
                $success -> setData($data);
                $success -> setTotal(count($data));
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
     * create annual budget by glcode
     * 
     * @return json
     */
    public function createAnnualBudgetByGlCode() { 
            
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $amount=$this->input->post('annualbudget');
            $glcodeid=$this->input->post('siteid');
            $selyear=$this->input->post('selyear');
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $year=  explode('-', $selyear);
                $FromYearMonth=$year[0];
                $ToYearMonth=$year[1];
                
                
                if($glcodeid != 0 || $glcodeid != ''){
                    $budgets_data = $this->budgetclass->getGlCodeBudgetDetail($glcodeid, $FromYearMonth, $ToYearMonth);
                }
                else {
                    $budgets_data = array();
                    //$customerid =$this->session->userdata('raptor_customerid');
                    //$budgets_data = $this->budgetclass->getCustomerBudgetDetail($customerid, $FromYearMonth, $ToYearMonth);
                }
                 
                if(count($budgets_data)>0){
                    $data = array('success'=>FALSE,'recordid'=>$budgets_data[0]['recordid']);  
                    $message = 'Monthly budgets already exist for this glcode';
                }
                else{
                    
                    $insertData = array(
                        'financialyear' => $selyear,
                        'amount'        => $amount,
                        'glcodeid'      => $glcodeid
                        
                    );
               
                    $request = array(
                        'insertData'       => $insertData,  
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
                    $this->budgetclass->insertAnnualBudgetByGlCode($request);
                     
                    $data = array('success'=>TRUE,'recordid'=>'');
                    $message = 'Annual Budget Created Successfully';
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
     * download import file by gl code
     * 
     * @param type $FromYearMonth
     * @param type $ToYearMonth
     *      
     * @return void
     */
    public function downloadImportFileByGlCode($FromYearMonth, $ToYearMonth) 
    {
            
        $financialYear=$FromYearMonth.'-'.$ToYearMonth;
 
        $FromYear=  (int)substr($FromYearMonth, 0,4);
        $FromMonth=(int)substr($FromYearMonth, 4,2);

        $ToYear=  (int)substr($ToYearMonth, 0,4);
        $ToMonth=(int)substr($ToYearMonth, 4,2);
        $customerid =$this->session->userdata('raptor_customerid');
         
        $heading = array('GL Code','Description');  
        $date=$FromYear.'-'.$FromMonth. '-01';
        $date=  strtotime($date);
       
        $totalrecord = 0;

        if(count($this->data['budget_setting'])>0){
        
            $totalrecord = $this->data['budget_setting']['totalrecord'];
            if($this->data['budget_setting']['ismonthly']==1){
                 for($i=1;$i<=12;$i++){
                    $heading[]=date('M Y', $date);
                    $date=strtotime("+1 month", $date);

                 }

            } elseif($this->data['budget_setting']['isquarterly'] == 1){ 
                for($i=1;$i<=4;$i++){
                    $todate=strtotime("+3 month", $date);
                    $todate=strtotime("-1 Days", $todate);
                    $heading[]=date('M Y', $date).' to '.date('M Y', $todate);
                    $date=strtotime("+3 month", $date);
                }
            }elseif($this->data['budget_setting']['isannual']==1){ 
                $todate=strtotime("+1 Years", $date);
                $todate=strtotime("-1 Days", $todate);
                $heading[]=date('M Y', $date).' to '.date('M Y', $todate);
            } 
        }

        $this->load->library('excel');
 
        $budgets_data = $this->customerclass->getCustomerGLChart($customerid, 'E');
        
        $data_array = array();
        //Loop Result

        foreach($budgets_data as $row1)
        { 
            $result = array();
            $result[]= $row1['accountcode'];
            $result[]=$row1['accountname'];


            $data = $this->budgetclass->getGlCodeBudgetDetail($row1['id'], $FromYearMonth, $ToYearMonth);
            foreach($data as $rowc){
                $result[]=$rowc['amount'];
            }

            $data_array[]=$result;
        }

        $file_name="my_budget_import_file_by_glcode.xls";
        
        //set excel configurations
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        
        $colH = 'B';
        for($i=1;$i<=$totalrecord;$i++){
            $colH++; 
            $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(10);
        }
      
        
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }
        $this->excel->Exportexcel("My Budget", $dir, $file_name, $heading, $data_array);

        $this->load->helper('download');
        $data = file_get_contents(base_url()."temp/".$file_name);
        force_download('my_budget_import_file_by_glcode'.$financialYear.'.xls', $data);
   	 	  
    }
    
    /**
     * import budget excel by gl code
     * 
     * @return json
     * 
     */
    public function importBudgetExcelByGlCode() 
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $customerid =$this->session->userdata('raptor_customerid');
    
            $financialYear=$this->input->post('ifyear');
            $updateoption=$this->input->post('updateoption');
            $year=  explode('-', $financialYear);
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);

            $dir = "./temp";
            if(!is_dir($dir))
            {
                    mkdir($dir, 0755, true);
            }
            $filen = $_FILES['importfile']['name'];
            $ext = pathinfo($filen, PATHINFO_EXTENSION);
            $filename="import_file.".$ext;
            $config['upload_path'] = $dir;
            $config['allowed_types'] = "xls|xlsx";
            $config['file_name'] = "import_file";
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("importfile")){
                  $message = "File cannot be upload, Please Try again.Error : ".$this->upload->display_errors();

            }
            
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $this->load->library('excel');

                $objPHPExcel = PHPExcel_IOFactory::load($dir.'/'.$filename);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            
                if(count($sheetData)<=1){
                    $success = SuccessClass::initialize(FALSE);
                    $message="Invalid Excel File";
                }
                else{
                    
                    $startmonth=1;
                    $totalrecord = 12;
                    $budgetopt = 'monthly';
                    $budgetsettingid = 0;
                    if(count($this->data['budget_setting'])>0){
                        $startmonth= (int)$this->data['budget_setting']['startmonth'];
                        $totalrecord =  $this->data['budget_setting']['totalrecord'];
                        $budgetopt =  $this->data['budget_setting']['budgetopt'];
                        $budgetsettingid =  $this->data['budget_setting']['id'];
                    }
                    
                    
                    $customerid =$this->session->userdata('raptor_customerid');
         
                    $subworksid=0;
                    $itemid=0;
                    $categoryid=0;
                    $typeid=0;

                    /*if(count($this->data['budget_setting'])>0){  

                        if($this->data['budget_setting']['useworkstype']!=0){
                            $type_data=$this->budgetclass->getBudgetOption($customerid,'subworks');
                            if(count($type_data)>0)
                            {
                                $subworksid=$type_data[0]['id'];
                            }
                        }
                        if($this->data['budget_setting']['useitem']!=0){
                            $type_data=$this->budgetclass->getBudgetOption($customerid,'item');
                            if(count($type_data)>0)
                            {
                                $itemid=$type_data[0]['id'];
                            }
                        } 
                        if($this->data['budget_setting']['usecategory']!=0){
                            $type_data=$this->budgetclass->getBudgetOption($customerid,'category');
                            if(count($type_data)>0)
                            {
                                $categoryid=$type_data[0]['id'];
                            }
                        } 
                        $type_data=$this->budgetclass->getBudgetOption($customerid,'type');
                        if(count($type_data)>0)
                        {
                            $typeid=$type_data[0]['id'];
                        }
                    }*/

                    
                    $insertData = array();
                    foreach($sheetData as $key=>$value) {

                        if($key == 1) {
                            if(count($value)!=$totalrecord+2){
                                $success = SuccessClass::initialize(FALSE);
                                $message="Invalid Import Excel budget format ";
                                break;
                            }
                            continue;
                        }


                        $site_data = $this->customerclass->getGlCodeByParams(array('customerid'=>$customerid, 'accounttype'=>'E', 'accountcode'=>$value['A']));
                        
                        if(count($site_data)==0){
                            $success = SuccessClass::initialize(FALSE);
                            $message="Invalid Site Id in Excel row no. ".$key;
                            break;
                        }
                        
                        $glcodeid = $site_data['id'];
                        $labelid = $site_data['labelid'];
                       
                        $budgets_data = $this->budgetclass->getGlCodeBudgetDetail($glcodeid,$FromYearMonth,$ToYearMonth);
                        if(count($budgets_data)==0 || $updateoption==1){
                            
                            if(count($budgets_data)>0){
                                $request = array(
                                    'glcodeid'         => $budgets_data[0]['glcodeid'], 
                                    'FromYearMonth'    => $FromYearMonth,
                                    'ToYearMonth'      => $ToYearMonth,
                                    'logged_contactid' => $this->data['loggeduser']->contactid
                                );
                                $this->budgetclass->deleteBudget($request);
                            }
                            
                            $date=$FromYear.'-'.$FromMonth. '-01';
                            $date=  strtotime($date);
                           
                            $colH = 'B';
                            for($i=1;$i<=$totalrecord;$i++){
                                $colH++; 
                                if($i!=1){
                                    $date=strtotime("+".(12/$totalrecord)." month", $date);
                                }
                                if(isset($value[$colH])){
                                    $bamount = (int)$value[$colH];
                                }
                                else{
                                    $bamount = 0;
                                }
                                
                                $insertData[] = array(
                                    'recordid'          => $labelid,
                                    'monthno'           => date('m', $date),
                                    'yearno'            => date('Y', $date),
                                    'monthsort'         => $i,
                                    'subworksid'        => $subworksid,
                                    'itemid'            => $itemid,
                                    'typeid'            => $typeid,
                                    'categoryid'        => $categoryid,
                                    'glcodeid'          => $glcodeid,
                                    'budget_settingid'  => $budgetsettingid,
                                    'amount'            => $bamount,
                                    'hours'             => isset($this->data['budget_setting']['showhours'])?$this->data['budget_setting']['showhours']:0,
                                    'createuser'        => $this->session->userdata('raptor_email'),
                                    'createdate'        => date('Y-m-d H:i:s') 
                                );
                            }
                            
                        }
                    }

                    if(count($insertData)>0){
                        $request = array(
                            'insertData'       => $insertData,  
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $this->budgetclass->insertMonthlyBudget($request);
                    }
                    $message = 'Document Data upload successfully.'; 
                    $success->setData($data);
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
     * get budget detail by glcode
     * @param string $glcodeid
     * @param string $year
     * 
     * @return json
     */ 
    public function loadBudgetDetailByGlCode($glcodeid, $year)
    {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $selectedyear=$year;
            $year=  explode('-', $year);
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];

            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $budgetData = $this->budgetclass->getGlCodeBudgetDetail($glcodeid, $FromYearMonth, $ToYearMonth);
                
                $data = array();
                foreach($budgetData as $row){

                        $date=$row['yearno'].'-'.$row['monthno']. '-01';
                        $date=  strtotime($date);
                        $yearmonth=date('Ym', $date);

                        $monthname=date('M Y', $date);
                        if(count($this->data['budget_setting'])>0){  
                            if($this->data['budget_setting']['ismonthly']==1){
                                $monthname=date('M Y', $date);
                            } elseif($this->data['budget_setting']['isquarterly'] == 1){ 
                                 $todate=strtotime("+3 month", $date);
                                 $todate=strtotime("-1 Days", $todate);
                                 $monthname=date('M Y', $date).' to '.date('M Y', $todate);
                            }elseif($this->data['budget_setting']['isannual']==1){ 
                                $todate=strtotime("+1 Years", $date);
                                $todate=strtotime("-1 Days", $todate);
                                $monthname=date('M Y', $date).' to '.date('M Y', $todate);
                            }  
                        }  
                        $data[] = array(
                            'month'     => $monthname,
                            'id'        => $row['id'],
                            'amount'    => $row['amount']
                        );
                        
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
     * update annual budget by gl code
     * 
     * @return json
     */
    public function updateAnnualBudgetByGlCode() { 
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            
            $amount=$this->input->post('annualbudget');
            $glcodeid=$this->input->post('siteid');
            $selyear=$this->input->post('selyear');
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                
                $year=  explode('-', $selyear);
                $FromYearMonth=$year[0];
                $ToYearMonth=$year[1];
                
               

                if($glcodeid != 0){
                    $request = array(
                        'glcodeid'         => $glcodeid, 
                        'FromYearMonth'    => $FromYearMonth,
                        'ToYearMonth'      => $ToYearMonth,
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
                    $this->budgetclass->deleteBudget($request);
                }
                /*else {
                    $sitedata = $this->customerclass->getCustomerSitesByRole($this->data['loggeduser']->customerid, $this->data['loggeduser']->contactid, $this->data['loggeduser']->email, $this->session->userdata('raptor_role'));
  
                    foreach ($sitedata as $key => $value) {
                        $request = array(
                            'recordid'         => $value['id'], 
                            'FromYearMonth'    => $FromYearMonth,
                            'ToYearMonth'      => $ToYearMonth,
                            'logged_contactid' => $this->data['loggeduser']->contactid
                        );
                        $this->budgetclass->deleteBudget($request);
                  
                    }
                     
                }*/
 
                $insertData = array(
                    'financialyear' => $selyear,
                    'amount'        => $amount,
                    'glcodeid'      => $glcodeid
                );

                $request = array(
                    'insertData'       => $insertData,  
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );
                $this->budgetclass->insertAnnualBudgetByGlCode($request);
                
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