<?php 
/**
 *  budget dashboard Controller Class
 *
 * This is a  budget dashboard controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  budget dashboard Controller Class
 *
 * This is a dashboard2 controller class for budget dashboard
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Dashboard2
 * @filesource          Dashboard2.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Dashboard2 extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
        
        //Load libraries
        $this->load->library('budget/BudgetClass');
        
        $customerid =$this->session->userdata('raptor_customerid'); 
        $this->data['budget_setting']= $this->budgetclass->getBudgetSettingByCustomerid($customerid);
      
    }

    /**
    * This function use for show Budget Dashboard
    * 
    * @return void 
    */
    public function index()
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
        $this->data['sites'] =$this->customerclass->getCustomerSitesByRole($customerid, $contactid, $un, $this->session->userdata('raptor_role'));
   
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

        $this->data['states'] = $this->sharedclass->getStates(1);

        $this->data['cssToLoad'] = array(
                base_url('plugins/select2/select2.min.css') 
         );


        $this->data['jsToLoad'] = array(
            base_url('plugins/select2/select2.full.min.js') ,
            //base_url('plugins/chartjs/Chart.min.js'),
            base_url('plugins/highcharts/js/highcharts.js'),

            //base_url('plugins/highcharts/js/modules/exporting.js')
          base_url('assets/js/dashboard/dashboard.budget.js')
        );

        $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Dashboard - Budget')
            ->set_layout($this->layout)
            ->set('page_title', 'Dashboard - Budget')
            ->set('page_sub_title', '')
            ->set_breadcrumb('Dashboard - Budget', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('dashboard/budget-dashboard', $this->data);

    }

  
    /**
     * monthlybudget chart
     * 
     * @return json 
     */
    public function loadMonthlyBudget()
    {
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            $startmonth=1;
            $totalrecord = 12;
            $budgetopt = 'monthly';
            if(count($this->data['budget_setting'])>0){
                $startmonth= (int)$this->data['budget_setting']['startmonth'];
                $totalrecord =  $this->data['budget_setting']['totalrecord'];
                $budgetopt =  $this->data['budget_setting']['budgetopt'];
            }
            $FromYear=date('Y');
            $FromMonth=$startmonth;

            if($this->input->post('fyear')!=null)
            {
                $year =  trim($this->input->post('fyear'));
                $year=  explode('-', trim($this->input->post('fyear')));
                $FromYearMonth=$year[0];
                $FromYear=  (int)substr($FromYearMonth, 0,4);
                $FromMonth=(int)substr($FromYearMonth, 4,2);

            }
            if($FromMonth>9){
                $fromdate=$FromYear.'-'.$FromMonth.'-01';
            }
            else{
                $fromdate=$FromYear.'-0'.$FromMonth.'-01';
            }

            $fromdate=strtotime($fromdate); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                

                $montharray = array();
                $monthlyamtarray = array();
                $YTDamtarray = array();
                for($i=1;$i<=$totalrecord;$i++){
                    if($totalrecord==12){
                        $montharray[]=date('M', $fromdate);
                    }
                    else{
                        $todate=strtotime("+".(12/$totalrecord)." month", $fromdate);
                        $montharray[]=date('M', $fromdate).'-'.date('M', $todate);

                    }
                    $fromdate=strtotime("+".(12/$totalrecord)." month", $fromdate);
                    $monthlyamtarray[]=0;   
                    $YTDamtarray[]=0; 
                }


                $parame = array();
                if($this->input->get_post('fyear')!=null)
                {
                    $parame['fyear'] =$this->input->get_post('fyear');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $parame['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $parame['fsite'] =$this->input->get_post('fsite');
                }
                
                $budgetData=$this->budgetclass->getAnnualBudget($this->data['loggeduser']->contactid, $parame, true);
                $total=0;
                if(count($budgetData)>0){
                    $monthlyamtarray = array();
                    $YTDamtarray = array();
                    foreach ($budgetData as $key => $value) {

                        $total=$total + (int)$value['annualbudget'];
                        $monthlyamtarray[]=(int)$value['annualbudget'];
                        $YTDamtarray[]=$total;
                    }

                }
            
            
                $monthlyarray = array(
                    'name'  => strtoupper($budgetopt),
                    'data'  => $monthlyamtarray
                );
                $YTDarray = array(
                    'name'  => 'YTD',
                    'data'  => $YTDamtarray
                );

               
                $data['title']="12 month, ".$budgetopt." and YTD";
                $data['xAxiscate']=$montharray;
                $data['yAxistitle']='Spend $';
                $data['seriesdata'] = array($monthlyarray, $YTDarray);
                 
                $success->setData($data);
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
     * monthlyspend_ytd chart
     * 
     * @return json 
     */
    public function loadMonthlySpendYTD()
    {
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            $customerid =$this->session->userdata('raptor_customerid');
              
            $totalrecord=12;
            $title="12 month, monthly and YTD";
           
            $startmonth=1;
            if(count($this->data['budget_setting'])>0){
                 $startmonth=$this->data['budget_setting']['startmonth'];
            }
            $FromYear=date('Y');
            $FromMonth=$startmonth;
                       
            if($this->input->post('fyear')!=null)
            {
                $year =  trim($this->input->post('fyear'));
                $year=  explode('-', trim($this->input->post('fyear')));
                $FromYearMonth=$year[0];
                $FromYear=  (int)substr($FromYearMonth, 0,4);
                $FromMonth=(int)substr($FromYearMonth, 4,2);
                
            }
             if($FromMonth>9){
                $fromdate=$FromYear.'-'.$FromMonth.'-01';
            }
            else{
                $fromdate=$FromYear.'-0'.$FromMonth.'-01';
            }
           
            $fromdate=strtotime($fromdate); 

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                

                $montharray = array();
                $monthYeaarray = array();

                for($i=1;$i<=$totalrecord;$i++){

                    $montharray[]=date('M', $fromdate);
                    $monthlryamtarray[date('Ym', $fromdate)]=0;
                    $fromdate=strtotime("+".(12/$totalrecord)." month", $fromdate);

                }

                $parame = array();
                if($this->input->get_post('fyear')!=null)
                {
                    $parame['fyear'] =$this->input->get_post('fyear');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $parame['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $parame['fsite'] =$this->input->get_post('fsite');
                }
                $budgetData=$this->budgetclass->getYTDActual($this->data['loggeduser']->contactid, $parame, true);
                $total=0;
                if(count($budgetData)>0){

                    foreach ($budgetData as $key => $value) {

                         $ym= (((int)$value['yearno'])*100)+((int)$value['monthno']);
                         $monthlryamtarray[$ym]=(int)$value['spendamt'];

                    }

                }
                $monthlyamtarray = array();
                $YTDamtarray = array();
                foreach ($monthlryamtarray as $key => $value) {
                    $total=$total + (int)$value;
                    $monthlyamtarray[]=(int)$value;
                    $YTDamtarray[]=$total;
                }



                $monthlyarray = array('name'=>'Monthly', 'data' => $monthlyamtarray);
                $YTDarray = array('name'=>'YTD', 'data'=>$YTDamtarray);

               
                $data['title']="12 month, monthly and YTD";
                $data['xAxiscate']=$montharray;
                $data['yAxistitle']='Spend $';
                $data['seriesdata'] = array($monthlyarray, $YTDarray);
                 
                $success->setData($data);
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
     * budget_vs_actual_ytd chart
     * 
     * @return json 
     */
    public function loadBudgetVsActualYTD()
    {
        
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
                
                $parame = array();
                if($this->input->get_post('fyear') != null)
                {
                    $parame['fyear'] =$this->input->get_post('fyear');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $parame['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $parame['fsite'] =$this->input->get_post('fsite');
                }
                $budgetData=$this->budgetclass->getAnnualBudget($this->data['loggeduser']->contactid, $parame);

                $budgetamount=0;

                if(count($budgetData)>0){
                    if($budgetData[0]['annualbudget']!=null){
                        $budgetamount=(int)$budgetData[0]['annualbudget']; 
                    }
                }

                $ytdamount=0;
                $budgetData = $this->budgetclass->getYTDActual($this->data['loggeduser']->contactid, $parame);

                if(count($budgetData)>0){
                    if($budgetData[0]['spendamt']!=null){
                        $ytdamount=(int)$budgetData[0]['spendamt']; 
                    }

                }

                $seriesdata=  array(
                    array(
                        'name'=>'Budget',
                        'data'=> array(
                            array(
                                'name'=>'BUDGET',
                                'y'=>$budgetamount
                            ),
                            array(
                                'name'=>'YTD',
                                'y'=>$ytdamount
                            )
                        )
                    )
                );

 
                $data['title']='Budget v Actual Total';
                $data['yAxistitle']='Budget $';
                $data['seriesdata']=$seriesdata;
                $success->setData($data);
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
     * budget_vs_actual_ytd_by_fm chart
     * 
     * @return json 
     */
    public function loadBudgetVsActualYTDByFM()
    {
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
                
                $parame = array();
                if($this->input->get_post('fyear')!=null)
                {
                    $parame['fyear'] =$this->input->get_post('fyear');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $parame['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $parame['fsite'] =$this->input->get_post('fsite');
                }
                
                $budgetdata = $this->budgetclass->getAnnualBudget($this->data['loggeduser']->contactid, $parame, false, false, true);
                $spanddata = $this->budgetclass->getYTDActual($this->data['loggeduser']->contactid, $parame, false, false, true);
                
                $budgetamount=0;
                $FMarray = array();
                $FMBudgetarray = array();
                $FMSpendarray = array();
                foreach ($budgetdata as $key => $value) 
                {
                    $FMarray[$value['contactid']]=$value['firstname'];
                    $FMBudgetarray[$value['contactid']]=(int)$value['annualbudget'];    
                }
                foreach ($spanddata as $key => $value) 
                {
                    $FMarray[$value['contactid']]=$value['firstname'];
                    $FMSpendarray[$value['contactid']]=(int)$value['spendamt'];     
                }

                $xAxiscate = array();
                $Budgetarray = array();
                $Spendarray = array();

                foreach ($FMarray as $key => $value) 
                {
                    $xAxiscate[]=$value;
                    if(isset($FMBudgetarray[$key])){
                       $Budgetarray[]=$FMBudgetarray[$key]; 
                    }
                    else{
                        $Budgetarray[]=0;
                    }
                    if(isset($FMSpendarray[$key])){
                       $Spendarray[]=$FMSpendarray[$key]; 
                    }
                    else{
                        $Spendarray[]=0;
                    }

                }
                $seriesdata=  array(
                    array(
                        'name'=>'BUDGET',
                        'data'=>$Budgetarray
                    ),
                    array(
                        'name'=>'YTD',
                        'data'=>$Spendarray
                    )
                );

 
                $data['title']='Budget v Actual Total By FM';
                $data['xAxiscate']=$xAxiscate;
                $data['yAxistitle']='Budget $';
                $data['seriesdata']=$seriesdata;
                
                $success->setData($data);
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
     * budget_by_fm chart
     * 
     * @return json 
     */
    public function loadBudgetByFM()
    {
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
                
                $parame = array();
                if($this->input->get_post('fyear')!=null)
                {
                    $parame['fyear'] =$this->input->get_post('fyear');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $parame['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $parame['fsite'] =$this->input->get_post('fsite');
                } 
                $budgetData = $this->budgetclass->getAnnualBudget($this->data['loggeduser']->contactid, $parame);

                $budgetamount=0;

                if(count($budgetData)>0){
                    if($budgetData[0]['annualbudget']!=null){
                        $budgetamount=(int)$budgetData[0]['annualbudget']; 
                    }
                }

                $budgetData = $this->budgetclass->getAnnualBudget($this->data['loggeduser']->contactid, $parame,false,false,true);

                $seriesdata = array();
                foreach ($budgetData as $key => $value) 
                {
                    $bper=((int)$value['annualbudget']*100)/$budgetamount;
                    $seriesdata[] = array(
                        'name'  => trim($value['firstname']),
                        'y'     => $bper
                    );

                }
 
                $data['title']='Budget by Manager';

                $data['yAxistitle']= 'Budget ';
                $data['seriesdata']=$seriesdata;
                
                $success->setData($data);
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
     * spend_by_fm chart
     * 
     * @return json 
     */
    public function loadSpendByFM()
    {
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
                
                $parame = array();
                if($this->input->get_post('fyear')!=null)
                {
                    $parame['fyear'] =$this->input->get_post('fyear');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $parame['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $parame['fsite'] =$this->input->get_post('fsite');
                }
                
                $ytdamount=0;
                $budgetData=$this->budgetclass->getYTDActual($this->data['loggeduser']->contactid, $parame);

                if(count($budgetData)>0){
                    if($budgetData[0]['spendamt']!=null){
                        $ytdamount = (int)$budgetData[0]['spendamt']; 
                    }

                }
                
                $spanddata=$this->budgetclass->getYTDActual($this->data['loggeduser']->contactid, $parame, false, false, true);
                $seriesdata = array();
                foreach ($spanddata as $key => $value) 
                {
                    $bper = ((int)$value['spendamt']*100)/$ytdamount;
                    $seriesdata[] = array(
                        'name'  => trim($value['firstname']),
                        'y'     => $bper
                    );
                }
                
                $data['title']='Spend by Manager';

                $data['yAxistitle']= 'Spend ';
                $data['seriesdata']=$seriesdata;
                
                $success->setData($data);
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
     * site_spend chart
     * 
     * @return json 
     */
    public function loadSiteSpend()
    {
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {  
            $year=  explode('-', trim($this->input->post('fyear')));
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);
            
             
            if($FromMonth>9){
                $fromdate=$FromYear.'-'.$FromMonth.'-01';
            }
            else{
                $fromdate=$FromYear.'-0'.$FromMonth.'-01';
            }
             
            $todate=strtotime("+1 Years",strtotime($fromdate));
            $todate=strtotime("-1 Days", $todate);
            $todate=date("Y-m-d", $todate);
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            { 
                $contactid =$this->session->userdata('raptor_contactid'); 
                $param = array();
                if($this->input->get_post('exclude0site')!=null)
                {
                    $param['exclude0site'] =$this->input->get_post('exclude0site');
                }
                if($this->input->get_post('fcontactid')!=null)
                {
                    $param['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $param['fsite'] =$this->input->get_post('fsite');
                }
                $param['orderby'] = 'pctspend';
                $budgetData = $this->budgetclass->getSiteSpend($contactid, $FromYearMonth, $ToYearMonth, $fromdate, $todate, $param);
          
                $seriesdata = array();
                foreach ($budgetData as $key => $value) {
                    $seriesdata[] = array(
                        'name'  => trim($value['siteaddress']),
                        'data'  => array((float)$value['pctspend'])
                    );
                }
 
                $data['title']='Site Spend';
                $data['xAxiscategories'] = array('');
                $data['xAxistitle']= 'Sites';
                $data['yAxistitle']= '% Budget Spend';
                $data['seriesdata']=$seriesdata;
                
                $success->setData($data);
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
     * budget_by_sites chart
     * 
     * @return json 
     */
    public function loadBudgetBySites()
    {
        
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {   
            $year=  explode('-', trim($this->input->post('fyear')));
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);
            
             
            if($FromMonth>9){
                $fromdate=$FromYear.'-'.$FromMonth.'-01';
            }
            else{
                $fromdate=$FromYear.'-0'.$FromMonth.'-01';
            }
             
            $todate=strtotime("+1 Years",strtotime($fromdate));
            $todate=strtotime("-1 Days", $todate);
            $todate=date("Y-m-d", $todate);
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            { 
                $contactid =$this->session->userdata('raptor_contactid');
                $param = array();

                if($this->input->get_post('fcontactid')!=null)
                {
                    $param['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $param['fsite'] =$this->input->get_post('fsite');
                }
                $param['orderby'] = 'actualbudget';
                $budgetData = $this->budgetclass->getSiteSpend($contactid, $FromYearMonth, $ToYearMonth, $fromdate, $todate, $param);

                $seriesdata = array();
                foreach ($budgetData as $key => $value) {
                    $seriesdata[] = array(
                        'name'  => trim($value['siteaddress']),
                        'y'     => (int)$value['actualbudget']);
                }

 
                $data['title']='Budget by Site';

                $data['yAxistitle']= 'Budget ';
                $data['seriesdata']=$seriesdata;
             
                $success->setData($data);
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
     * spend_by_sites chart
     * 
     * @return json 
     */
    public function loadSpendBySites()
    {
         //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {   
            $year=  explode('-', trim($this->input->post('fyear')));
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);

            $ToYear=  (int)substr($ToYearMonth, 0,4);
            $ToMonth=(int)substr($ToYearMonth, 4,2);
            
             
            if($FromMonth>9){
                $fromdate=$FromYear.'-'.$FromMonth.'-01';
            }
            else{
                $fromdate=$FromYear.'-0'.$FromMonth.'-01';
            }
             
            $todate=strtotime("+1 Years",strtotime($fromdate));
            $todate=strtotime("-1 Days", $todate);
            $todate=date("Y-m-d", $todate);
            
            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            { 
                $contactid =$this->session->userdata('raptor_contactid');
                $param = array();

                if($this->input->get_post('fcontactid')!=null)
                {
                    $param['fcontactid'] =$this->input->get_post('fcontactid');
                }
                if($this->input->get_post('fsite')!=null)
                {
                    $param['fsite'] =$this->input->get_post('fsite');
                }
                $param['orderby'] = 'pctspend';
                $budgetData = $this->budgetclass->getSiteSpend($contactid, $FromYearMonth, $ToYearMonth, $fromdate, $todate, $param);

                $seriesdata = array();
                foreach ($budgetData as $key => $value) {
                    $seriesdata[] = array(
                        'name'  => trim($value['siteaddress']),
                        'y'     => (float)$value['pctspend']
                    );
                   
                }

 
                $data['title']='Spend by Site';

                $data['yAxistitle']= 'Spend ';
                $data['seriesdata']=$seriesdata;
             
                $success->setData($data);
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