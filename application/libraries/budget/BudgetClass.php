<?php 
/**
 * Budget Libraries Class
 *
 * This is a Budget class for Budget Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  

/**
 * Budget Libraries Class
 *
 * This is a Budget class for Budget Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Budget
 * @filesource          BudgetClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 class BudgetClass extends MY_Model
{
    /**
    * Log class 
    * 
    * @var class
    */
    private $LogClass;
    
    /**
    * Shared class 
    * 
    * @var class
    */
    private $sharedClass;
    
    
    /**
    * customer class 
    * 
    * @var class
    */
    private $customerClass;

    
    /**
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'BudgetClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
       
    
    /**
    * This function use for get Budget Data by site
    * 
     * @param integer $contactid - logged user contact id
     * @param integer $FromYearMonth - formated yearmonth
     * @param integer $ToYearMonth - formated yearmonth
     * @param date $fromdate
     * @param date $todate
     * @param integer $band
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getBudgetsDataBySite($contactid, $FromYearMonth, $ToYearMonth, $fromdate, $todate, $band, $size, $start, $field, $order, $filter, $params) {
 
      
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
         
        
        $this->db->select(array("a.labelid, concat(a.siteline1,'<br>',a.siteline2) as siteaddress, a.siteline1, a.siteline2, a.sitesuburb, a.sitestate, "
            . " c.value_string AS siteref1, c2.value_string AS siteref2, a.sitefm, a.contactid, SUM(g.amount) AS annualbudget, "
            . " getSiteActualAmount(a.labelid,'$fromdate','$todate') AS actual, (SUM(g.amount)-getSiteActualAmount(a.labelid, '$fromdate','$todate')) AS remaining, "
            . " ((getSiteActualAmount(a.labelid, '$fromdate','$todate') / SUM(g.amount)) * 100) AS pctspend, MAX(modifydate) AS lastupdated, MAX(createdate) AS lastcreatedate"));
        
        $this->db->from('addresslabel a');
        $this->db->join('contact d', 'a.contactid = d.contactid', 'inner');
        $this->db->join('addresslabel_attribute b', "b.name='siteref1'", 'left');
        $this->db->join('addresslabel_attribute_value c', 'b.id=c.attributeid AND a.labelid=c.labelid', 'left');
        $this->db->join('addresslabel_attribute b2', "b2.name='siteref2'", 'left');
        $this->db->join('addresslabel_attribute_value c2', "b2.id=c2.attributeid AND a.labelid=c2.labelid", 'left');
        $this->db->join('budget g', 'a.labelid=g.recordid', 'left'); 
 
        $this->db->where('d.customerid', $loggedUserData['customerid']);
        $this->db->where('((g.yearno*100)+g.monthno)>=', $FromYearMonth);
        $this->db->where('((g.yearno*100)+g.monthno)<=', $ToYearMonth);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if($loggedUserData['role'] == 'site contact')
        {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']);
            $where .= "  and  (a.sitecontactid=".$loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        if ($filter != '') {
            $this->db->where("(a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitefm LIKE '%".$this->db->escape_str($filter)."%' or c.value_string LIKE '%".$this->db->escape_str($filter)."%' or c2.value_string LIKE '%".$this->db->escape_str($filter)."%' )");
        }
     
        $this->db->group_by('a.labelid');
        if($band != null)
        {
             
            if($band == 1){
                $this->db->having('pctspend < 50'); 
            } 
            elseif($band == 2) {
                $this->db->having('pctspend>=50 and pctspend<75');
            }
            elseif($band == 3) {
                $this->db->having('pctspend>=75 and pctspend<=100');
            }
            elseif($band == 4) {
                $this->db->having('pctspend>100');
            }

        }
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("a.labelid, concat(a.siteline1,'<br>',a.siteline2) as siteaddress, a.siteline1, a.siteline2, a.sitesuburb, a.sitestate, "
            . " c.value_string AS siteref1, c2.value_string AS siteref2, a.sitefm, a.contactid, SUM(g.amount) AS annualbudget, "
            . " getSiteActualAmount(a.labelid,'$fromdate','$todate') AS actual, (SUM(g.amount)-getSiteActualAmount(a.labelid, '$fromdate','$todate')) AS remaining, "
            . " ((getSiteActualAmount(a.labelid, '$fromdate','$todate') / SUM(g.amount)) * 100) AS pctspend, MAX(modifydate) AS lastupdated, MAX(createdate) AS lastcreatedate"));
        
        
        $this->db->from('addresslabel a');
        $this->db->join('contact d', 'a.contactid = d.contactid', 'inner');
        $this->db->join('addresslabel_attribute b', "b.name='siteref1'", 'left');
        $this->db->join('addresslabel_attribute_value c', 'b.id=c.attributeid AND a.labelid=c.labelid', 'left');
        $this->db->join('addresslabel_attribute b2', "b2.name='siteref2'", 'left');
        $this->db->join('addresslabel_attribute_value c2', "b2.id=c2.attributeid AND a.labelid=c2.labelid", 'left');
        $this->db->join('budget g', 'a.labelid=g.recordid', 'left'); 
 
        $this->db->where('d.customerid', $loggedUserData['customerid']);
        $this->db->where('((g.yearno*100)+g.monthno)>=', $FromYearMonth);
        $this->db->where('((g.yearno*100)+g.monthno)<=', $ToYearMonth);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if($loggedUserData['role'] == 'site contact')
        {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']);
            $where .= "  and  (a.sitecontactid=".$loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        if ($filter != '') {
            $this->db->where("(a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitefm LIKE '%".$this->db->escape_str($filter)."%' or c.value_string LIKE '%".$this->db->escape_str($filter)."%' or c2.value_string LIKE '%".$this->db->escape_str($filter)."%' )");
        }
     
        $this->db->group_by('a.labelid');
        if($band!=null)
        { 
            if($band == 1){
                $this->db->having('pctspend < 50'); 
            } 
            elseif($band == 2) {
                $this->db->having('pctspend>=50 and pctspend<75');
            }
            elseif($band == 3) {
                $this->db->having('pctspend>=75 and pctspend<=100');
            }
            elseif($band == 4) {
                $this->db->having('pctspend>100');
            }

        }
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );

        $this->LogClass->log('Get Budget Data Query : '. $this->db->last_query());

        return $data;
    }
    
                
    /**
    * This function use for Insert new budget
    * @param array $params - the $params is array of budget and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAnnualBudgetBySite($params)
    {
        //1 - load multiple models
        require_once('chain/InsertBudgetChain.php');
        
        //2 - initialize instances
        $InsertBudgetChain = new InsertBudgetChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Budget  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $budget_setting = $this->getBudgetSettingByCustomerid($loggedUserData['customerid']);
        $insertData = $params['insertData'];
        $budgetData = array();
 
        $financialyear = $insertData['financialyear'];
        $amount = $insertData['amount'];
        $siteid = $insertData['siteid'];
        $year=  explode('-', $financialyear);
        $FromYearMonth=$year[0];
        $ToYearMonth=$year[1];
        $FromYear=  (int)substr($FromYearMonth, 0,4);
        $FromMonth=(int)substr($FromYearMonth, 4,2);
       
            
        $totalrecord=0;
        $subworksid=0;
        $itemid=0;
        $categoryid=0;
        $typeid=0;

        if(count($budget_setting)>0){  
             if($budget_setting['ismonthly']==1){
                 $totalrecord=12;
             } elseif($budget_setting['isquarterly'] == 1){ 
                  $totalrecord=4;
             }elseif($budget_setting['isannual']==1){ 
                 $totalrecord=1;
             } 
            if($budget_setting['useworkstype']!=0){
                $type_data=$this->getBudgetOption($loggedUserData['customerid'],'subworks');
                if(count($type_data)>0)
                {
                    $subworksid=$type_data[0]['id'];
                }
            }
            if($budget_setting['useitem']!=0){
                $type_data=$this->getBudgetOption($loggedUserData['customerid'],'item');
                if(count($type_data)>0)
                {
                    $itemid=$type_data[0]['id'];
                }
            } 
            if($budget_setting['usecategory']!=0){
                $type_data=$this->getBudgetOption($loggedUserData['customerid'],'category');
                if(count($type_data)>0)
                {
                    $categoryid=$type_data[0]['id'];
                }
            } 
            $type_data=$this->getBudgetOption($loggedUserData['customerid'],'type');
            if(count($type_data)>0)
            {
                $typeid=$type_data[0]['id'];
            }
        }


        $sitedata = $this->customerClass->getCustomerSitesByRole($loggedUserData['customerid'], $loggedUserData['contactid'], $loggedUserData['email'], $loggedUserData['role'], $siteid);
        $array1 = array();
        foreach ($sitedata as $key => $value) {
            $array1[]=$value['id'];
        }

        if($siteid != 0){
            $alreadySiteBudgetdata = $this->getSiteBudgetDetail($siteid, $FromYearMonth, $ToYearMonth);
        }
        else {
             
            $alreadySiteBudgetdata = $this->getCustomerBudgetDetail($loggedUserData['customerid'], $FromYearMonth, $ToYearMonth);
        }
 
        $array2 = array();
        foreach ($alreadySiteBudgetdata as $key => $value) {
            $array2[]=$value['labelid'];
        }

        $result = array_diff($array1, $array2);
        if($totalrecord > 0){
            $bamount = $amount/$totalrecord;
            foreach ($result as $key => $value) {
                $date = $FromYear.'-'.$FromMonth. '-01';
                $date = strtotime($date);

                for($i = 1; $i <= $totalrecord; $i++){

                    $budgetData[] = array(
                        'recordid'      => $value,
                        'monthno'       => date('m', $date),
                        'yearno'        => date('Y', $date),
                        'monthsort'     => $i,
                        'subworksid'    => $subworksid,
                        'itemid'        => $itemid,
                        'typeid'        => $typeid,
                        'categoryid'    => $categoryid,
                        'amount'        => $bamount,
                        'hours'         => isset($budget_setting['showhours'])?$budget_setting['showhours']:0,
                        'createuser'    => $this->session->userdata('raptor_email'),
                        'createdate'    => date('Y-m-d H:i:s') 
                    );
                    $date=strtotime("+".(12/$totalrecord)." month", $date);
                }
            }
        }
        
        $request = array(
            'params'  => $params, 
            'userData'      => $loggedUserData, 
            'budgetData'   =>  $budgetData
        );
 
        $InsertBudgetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertBudgetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Insert new budget
    * @param array $params - the $params is array of budget and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertMonthlyBudget($params)
    {
        //1 - load multiple models
        require_once('chain/InsertBudgetChain.php');
        
        //2 - initialize instances
        $InsertBudgetChain = new InsertBudgetChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Budget  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['insertData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'      => $loggedUserData, 
            'budgetData'   =>  $insertData
        );
 
        $InsertBudgetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertBudgetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update budget
    * @param array $params - the $params is array of params and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateBudget($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateBudgetChain.php'); 
        
        //2 - initialize instances
        $UpdateBudgetChain = new UpdateBudgetChain();
 
        
        //3 - get the parts connected
   
        //4 - start the process
        $this->LogClass->log('Update Budget  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $updateData = $params['updateData'];
  
         
        $request = array(
            'params'  => $params, 
            'userData'      => $loggedUserData,  
            'updateData'    => $updateData, 
        );
        
        $UpdateBudgetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateBudgetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
                
   /**
    * This function use for Delete budget
    * @param array $params - the $params is array of budget and lcontactid(LoggedUser)
    * @return array
    */
       
    public function deleteBudget($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteBudgetChain.php');
        
        //2 - initialize instances
        $DeleteBudgetChain = new DeleteBudgetChain();
    
        //3 - get the parts connected
 
        //4 - start the process
        $this->LogClass->log('Delete Budget  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
 
        $request = array(
            'params' => $params, 
            'userData'      => $loggedUserData 
        );
 
        $DeleteBudgetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteBudgetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
   
     /**
    * This function Get a Budget Settings

    * @return result array
    */
    public function getBudgetSettings() {
         
        $this->db->select("*");
        $this->db->from('budget_setting'); 
        $this->db->where('isactive', 1);
  
        return $this->db->get()->result_array();
    }
    
     /**
    * This function Get a BudgetSetting
    * @param int $customerid
    * @return result array
    */
    public function getBudgetSettingByCustomerid($customerid) {
         
        $this->db->select("*");
        $this->db->from('budget_setting'); 
        $this->db->where('customerid', $customerid);
  
        $data = $this->db->get()->row_array();
        $totalrecord = 12;
        $Monthly = "monthly";
 
        if(count($data)>0){
            
             
            if($data['ismonthly']==1){
                $totalrecord = 12;
                $Monthly = "monthly";
            } elseif($data['isquarterly'] == 1){ 
                 $totalrecord = 4;
                 $Monthly = "quarterly";

            }elseif($data['isannual']==1){ 
                $totalrecord = 1;
                $Monthly = "annual";
            }  
            $data['totalrecord'] = $totalrecord;
            $data['budgetopt'] = $Monthly; 
        }
         
        return $data;
    }
    
   
     /**
     * This function use for get budget years
     * @param integer $customerid
     * @param integer $typeid
     * @return array
     */
    public function GetBudgetYears($customerid, $typeid = 0) {

        $this->db->select('DISTINCT yearno'); 
        $this->db->from('budget b');
        $this->db->join('addresslabel a', 'a.labelid=b.recordid', 'inner'); 
        $this->db->where('a.customerid', $customerid);
        if($typeid != NULL && $typeid!=0) {
              $this->db->where('b.typeid', $typeid);
       
        }
        $this->db->order_by('yearno', 'DESC');
        $data = $this->db->get()->result_array();
        return $data;
        
    } 
    
    /**
     * This function use for get JobsBudgetposition
     * 
     * @param integer $customerid
     * @param integer $labelid
     * @param double $threshold_pct
     * @param double $laststage
     * @return array
     */
    public function getJobsBudgetposition($customerid, $labelid, $threshold_pct, $laststage) {
         
        $jobstage = array('client_notified','cancelled');
        $this->db->select("((budget_position/budget_period)*100) as pctspend");
        $this->db->where_not_in('jobstage', $jobstage);
        $this->db->where('customerid', $customerid);
        $this->db->where('labelid', $labelid);
        $this->db->having(array('pctspend >=' => $threshold_pct, 'pctspend <' => $laststage)); 
        $this->db->from('jobs');
        $query = $this->db->get();
      
        return $query->row_array();
        
    }
    
    /**
     * This function use for get site Budget_Detail
     * 
     * @param integer $recordid
     * @param string $FromYearMonth
     * @param string $ToYearMonth
     * @return array
     */
    public function getSiteBudgetDetail($recordid, $FromYearMonth, $ToYearMonth) {

        $this->db->select('*');
        $this->db->where('recordid', $recordid);
        $this->db->where('((yearno*100)+monthno)>=',$FromYearMonth);
        $this->db->where('((yearno*100)+monthno)<=',$ToYearMonth);
        $this->db->from('budget');
        $this->db->order_by('monthsort', 'asc');
        $data = $this->db->get()->result_array();
        return $data;
        
    }
    
    /**
     * This function use for get customer Budget_Detail
     * 
     * @param integer $customerid
     * @param string $FromYearMonth
     * @param string $ToYearMonth
     * @return array
     */
    public function getCustomerBudgetDetail($customerid, $FromYearMonth, $ToYearMonth) {

        $this->db->select('g.*');
        $this->db->from('addresslabel a');
        $this->db->join('contact d', 'a.contactid = d.contactid', 'inner');
        $this->db->join('budget g', 'a.labelid=g.recordid', 'left');
        $this->db->where('a.customerid', $customerid);
        $this->db->where('((g.yearno*100)+g.monthno)>=',$FromYearMonth);
        $this->db->where('((g.yearno*100)+g.monthno)<=',$ToYearMonth);
        $this->db->order_by('a.labelid', 'asc');
        $this->db->order_by('monthsort', 'asc');
        $data = $this->db->get()->result_array();
        return $data;
        
    }
    
     /**
     * This function use for get  Budget_Detail
     * 
     * @param integer $customerid
     * @param string $FromYearMonth
     * @param string $ToYearMonth
     * @return array
     */
    public function getSiteBudgets($customerid, $FromYearMonth, $ToYearMonth, $evalfromdate, $evaltodate) {

         $sql = " SELECT a.labelid, concat(a.siteline1,' ',a.siteline2 ,' ',a.sitesuburb,' ',a.sitestate) as siteaddress, "
                 . " SUM(g.amount) as actualbudget, getSiteActualAmount(a.labelid, '". $evalfromdate."', '". $evaltodate."') AS actual "
                 . " FROM  addresslabel a "
                . " INNER JOIN contact AS d ON a.contactid = d.contactid "
                . " LEFT JOIN budget g ON a.labelid=g.recordid  "
                . " WHERE d.customerid=".$customerid." and (((g.yearno*100)+g.monthno)>= $FromYearMonth) and (((g.yearno*100)+g.monthno)<= $ToYearMonth )"
                . " GROUP BY a.labelid ";
        $query = $this->db->query($sql);
        return $query->result_array();
       
        
    }
   
    
     /**
     * This function use for get budget_threshold
     *  
     * @param integer $customerid
     * @return array
     */
    public function getBudgetThreshold($customerid) {

        $this->db->select('*');
        $this->db->from('budget_threshold');
        $this->db->where('customerid', $customerid);
        $this->db->where('isactive', 1);
        
        $query = $this->db->get();

        return $query->result_array();
    }
    
    
    /**
     * This function use for get budget_option
     * 
     * @param integer $customerid
     * @param string $type
     * @return array
     */
    public function getBudgetOption($customerid, $type = 'all') {
         
        if($type == "type"){
            $sql="SELECT bo.NAME as type,optionvalueid as id,bt.name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN budget_type bt ON optionvalueid=bt.id AND bo.`name` = 'type' WHERE bt.isactive=1 AND customerid=$customerid ";
     
        }
        elseif($type == "category")
        {
            $sql="SELECT bo.NAME as type,optionvalueid  as id,bc.name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN budget_category bc ON optionvalueid=bc.id AND bo.`name` = 'category' WHERE bc.isactive=1 AND customerid=$customerid ";
     
        }
        elseif($type == "item")
        {
            $sql="SELECT bo.NAME as type,optionvalueid  as id, bi.name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN budget_item bi ON optionvalueid=bi.id AND bo.`name` = 'item' WHERE bi.isactive=1 AND customerid=$customerid ";
     
        }
        elseif($type == "subworks")
        {
            $sql="SELECT bo.NAME as type,optionvalueid  as id,sw.se_subworks_name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN se_subworks sw ON optionvalueid=sw.id AND bo.`name` = 'subworks' WHERE sw.enabled=1 AND customerid=$customerid ";
     
        }
        else{
            $sql="SELECT bo.NAME as type,optionvalueid as id,bt.name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN budget_type bt ON optionvalueid=bt.id AND bo.`name` = 'type' WHERE bt.isactive=1 AND customerid=$customerid "
                . "UNION "
                . "SELECT bo.NAME as type,optionvalueid  as id,bc.name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN budget_category bc ON optionvalueid=bc.id AND bo.`name` = 'category' WHERE bc.isactive=1 AND customerid=$customerid "
                . "UNION "
                . "SELECT bo.NAME as type,optionvalueid  as id,bi.name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN budget_item bi ON optionvalueid=bi.id AND bo.`name` = 'item' WHERE bi.isactive=1 AND customerid=$customerid "
                . "UNION "
                . "SELECT bo.NAME as type,optionvalueid  as id,sw.se_subworks_name as name FROM budget_option bo INNER JOIN budget_option_customer boc ON bo.id=boc.optionid LEFT JOIN se_subworks sw ON optionvalueid=sw.id AND bo.`name` = 'subworks' WHERE sw.enabled=1 AND customerid=$customerid ";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /**
     * calculate_Annual_Budget for chart
     * 
     * @param integer $lcontactid
     * @param array $param
     * @param boolean $groupbyMonthyear
     * @param boolean $groupbysite
     * @param boolean $groupbyFM
     * @return array
     */
    public function getAnnualBudget($lcontactid, $param = array(), $groupbyMonthyear=false, $groupbysite=false, $groupbyFM=false)
    {
        $loggedUserData = $this->sharedClass->getLoggedUser($lcontactid);
        
        $sql = "SELECT SUM(g.amount) AS annualbudget  ";
        if($groupbyMonthyear==true){
            $sql .= " ,g.monthno ,g.yearno ";
        } 
        if($groupbysite==true){
            $sql .= " ,a.labelid,a.siteline1,a.siteline2,a.sitesuburb,a.sitestate ";
        }
        if($groupbyFM==true){
            $sql .= " ,a.contactid,d.firstname ";
        }
        $sql .= " FROM addresslabel a INNER JOIN contact AS d ON a.contactid = d.contactid   LEFT JOIN budget g ON a.labelid=g.recordid  ";
        $sql .=" WHERE d.customerid=". $loggedUserData['customerid'];

        if(isset($param['fyear'])&& $param['fyear']!="")
        {               

            $year =  explode('-', trim($param['fyear']));
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $sql .=" AND (((g.yearno*100)+g.monthno)>= $FromYearMonth) AND (((g.yearno*100)+g.monthno)<= $ToYearMonth )";

        }
        if(isset($param['fcontactid'])&& $param['fcontactid']!="")
        {
            $sql .="  and  a.contactid='".$param['fcontactid']."'";
        }
        if(isset($param['fsite'])&& $param['fsite']!="")
        {
            $sql .="  and  a.labelid='".$param['fsite']."'";
        }
        if(isset($param['state'])&& $param['state']!="")
        {
            $sql .=" and  a.sitestate='".$param['state']."'";
        }

        if($loggedUserData['role'] == 'site contact')
        {
            $sql .= "  and  (a.sitecontactid=". $loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $sql .="  and  (a.contactid=". $loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        else{

        }
        if($groupbyMonthyear==true || $groupbysite==true || $groupbyFM==true){
            $sql .=" GROUP BY ";

        }
        if($groupbyMonthyear==true){
            $sql .="  g.monthno, g.yearno ";

        }
        if($groupbysite==true){
            $sql .= " a.labelid,a.siteline1,a.siteline2,a.sitesuburb,a.sitestate ";
        }
        if($groupbyFM==true){
            $sql .= " a.contactid ";
        }
        if($groupbyMonthyear==true){

            $sql .=" ORDER BY monthsort";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
        
       
     /**
     * calculate_YTD_Budget for chart
     
     * @param integer $lcontactid
     * @param array $param
     * @return array
     */
    
    public function getYTDBudget($lcontactid, $param = array())
    {
        $loggedUserData = $this->sharedClass->getLoggedUser($lcontactid);  
       

        $sql =" SELECT SUM(g.amount) AS annualbudget  ";

        $sql .=" FROM addresslabel a INNER JOIN contact AS d ON a.contactid = d.contactid   LEFT JOIN budget g ON a.labelid=g.recordid  ";
        $sql .=" WHERE d.customerid=". $loggedUserData['customerid'];

        if(isset($param['fyear'])&& $param['fyear']!="")
        {               
            $year =  explode('-', trim($param['fyear']));
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $currentYearMonth = date('Ym');
            if($ToYearMonth>$currentYearMonth){
                $ToYearMonth=$currentYearMonth-1;
            }
            $sql .=" AND (((g.yearno*100)+g.monthno)>= $FromYearMonth) AND (((g.yearno*100)+g.monthno)<= $ToYearMonth )";
        }
        if(isset($param['fcontactid'])&& $param['fcontactid']!="")
        {
            $sql .=" AND a.contactid='".$param['fcontactid']."'";
        }
        if(isset($param['fsite'])&& $param['fsite']!="")
        {
            $sql .=" AND a.labelid='".$param['fsite']."'";
        }
        if(isset($param['state'])&& $param['state']!="")
        {
            $sql .=" AND a.sitestate='".$param['state']."'";
        }

        if($loggedUserData['role'] == 'site contact')
        {
            $sql .= " AND (a.sitecontactid=". $loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $sql .=" AND (a.contactid=". $loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        else{

        }

       $query = $this->db->query($sql);
        return $query->result_array();
            
    }
       
    /**
     * calculate_YTD_Actual for chart
     * 
  
     * @param integer $lcontactid
     * @param array $param
     * @param boolean $groupbyMonthyear
     * @param boolean $groupbysite
     * @param boolean $groupbyFM
     * @return array
     */
    public function getYTDActual($lcontactid, $param = array(), $groupbyMonthyear=false, $groupbysite=false, $groupbyFM=false)
    {
         $loggedUserData = $this->sharedClass->getLoggedUser($lcontactid);

        $sql = "SELECT SUM(s.netval) AS spendamt ";
        if($groupbyMonthyear==true){
            $sql .= " ,MONTH(s.invoicedate) as monthno , YEAR(s.invoicedate) as yearno ";
        } 
        if($groupbysite==true){
            $sql .= " ,a.labelid,a.siteline1,a.siteline2,a.sitesuburb,a.sitestate ";
        } 
        if($groupbyFM==true){
            $sql .= " ,a.contactid,d.firstname ";
        }
        $sql .=" FROM invoice_site s INNER JOIN addresslabel a ON s.labelid = a.labelid   INNER JOIN contact AS d ON a.contactid = d.contactid   ";
        $sql .=" WHERE d.customerid=". $loggedUserData['customerid'];

        if(isset($param['fyear'])&& $param['fyear']!="")
        {               

            $year =  explode('-', trim($param['fyear']));
            $FromYearMonth=$year[0];
            $ToYearMonth=$year[1];
            $FromYear=  (int)substr($FromYearMonth, 0,4);
            $FromMonth=(int)substr($FromYearMonth, 4,2);
            if($FromMonth>9){
                $fromdate=$FromYear.'-'.$FromMonth.'-01';
            }
            else{
                $fromdate=$FromYear.'-0'.$FromMonth.'-01';
            }

            $todate=strtotime("+1 Years",strtotime($fromdate));
            $todate=strtotime("-1 Days", $todate);
            $todate=date("Y-m-d", $todate);
            $sql .=" AND s.invoicedate >='".$fromdate."' and s.invoicedate <='".$todate."' ";


        }
        if(isset($param['fcontactid'])&& $param['fcontactid']!="")
        {
            $sql .="  and  a.contactid='".$param['fcontactid']."'";
        }
        if(isset($param['fsite'])&& $param['fsite']!="")
        {
            $sql .="  and  a.labelid='".$param['fsite']."'";
        }
        if(isset($param['state'])&& $param['state']!="")
        {
            $sql .=" and  a.sitestate='".$param['state']."'";
        }

        if($loggedUserData['role'] == 'site contact')
        {
            $sql .= "  and  (a.sitecontactid=". $loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $sql .="  and  (a.contactid=". $loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        else{

        }
        if($groupbyMonthyear==true || $groupbysite==true || $groupbyFM==true){
            $sql .=" GROUP BY ";
        }
        if($groupbyMonthyear==true){
            $sql .="  MONTH(s.invoicedate) , YEAR(s.invoicedate)";

        }
        if($groupbysite==true){
            $sql .= "  a.labelid,a.siteline1,a.siteline2,a.sitesuburb,a.sitestate ";
        } 
        if($groupbyFM==true){
            $sql .= " a.contactid ";
        }
        if($groupbyMonthyear==true){

            $sql .=" ORDER BY (((YEAR(s.invoicedate))*100)+MONTH(s.invoicedate)) ";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
            
    }
    
    /**
     * get Site Spend
     * 
     
     * @param integer $lcontactid
     * @param integer $FromYearMonth
     * @param integer $ToYearMonth
     * @param date $evalfromdate
     * @param date $evaltodate
     * @param array $param
     * @return array 
     */
    public function getSiteSpend($lcontactid, $FromYearMonth, $ToYearMonth, $evalfromdate, $evaltodate, $param = array())
    {
        $loggedUserData = $this->sharedClass->getLoggedUser($lcontactid);
        
        $sql = " SELECT a.labelid, concat(a.siteline1,' ',a.siteline2 ,' ',a.sitesuburb,' ',a.sitestate) as siteaddress, "
                . " SUM(g.amount) as actualbudget, getSiteActualAmount(a.labelid, '". $evalfromdate."', '". $evaltodate."') AS actual, "
                . "((getSiteActualAmount(a.labelid, '$evalfromdate','$evaltodate') / SUM(g.amount)) * 100) AS pctspend  "
                . " FROM  addresslabel a "
                . " INNER JOIN contact AS d ON a.contactid = d.contactid "
                . " LEFT JOIN budget g ON a.labelid=g.recordid  "
                . " WHERE d.customerid=".$loggedUserData['customerid']." "
                . " and (((g.yearno*100)+g.monthno)>= $FromYearMonth) "
                . " and (((g.yearno*100)+g.monthno)<= $ToYearMonth )";
            
        if(isset($param['fsite'])&& $param['fsite']!="")
        {
            $sql .="  and  a.labelid='".$param['fsite']."'";
        }
        if(isset($param['fcontactid'])&& $param['fcontactid']!="")
        {
            $sql .=" and  a.contactid='".$param['fcontactid']."'";
        }

        if($loggedUserData['role'] == 'site contact')
        {
            $sql .= "  and  (a.sitecontactid=". $loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $sql .="  and  (a.contactid=". $loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        else{

        }

        $sql .= " GROUP BY a.labelid ";
        if(isset($param['exclude0site'])&& $param['exclude0site'] == "1")
        {
             $sql .=" HAVING  pctspend>0";
        }
        if(isset($param['orderby'])&& $param['orderby'] != "")
        {
            $sql .= " Order BY " . $param['orderby'];
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
     /**
    * @desc This function Get Budget Categories
    * @param $active - 0/1
    * @return array - 
    */
    public function getBudgetCategories($active = 1) {
         
        $this->db->select('*');
        $this->db->from('budget_category');
        if ($active != NULL) {
            $this->db->where('isactive', $active);
        }
        $this->db->order_by('sortorder asc');
        $query = $this->db->get();
        return $query->result_array(); 
    }
    
    /**
    * @desc This function Get Budget Categories
    * @param $active - 0/1
    * @return array - 
    */
    public function getBudgetItems($active = 1) {
         
        $this->db->select('*');
        $this->db->from('budget_item');
        if ($active != NULL) {
            $this->db->where('isactive', $active);
        }
        $this->db->order_by('sortorder asc');
        $query = $this->db->get();
        return $query->result_array(); 
    }
    
    /**
    * @desc This function Get Budget Widget caption
    * @param int $customerid
    * @return array - 
    */
    public function getBudgetWidgetCaption($customerid) {
        
        $sql = 'SELECT Concat(‘Budgets – ‘,bt.name) FROM budget_setting bs INNER JOIN budget_type bt ON bs.budget_typeid=bt.id WHERE customerid= :customerid';
        $this->db->select(array("CONCAT('Budgets – ',bt.name) AS caption"));
        $this->db->from("budget_setting bs");
        $this->db->join('budget_type bt', 'bs.budget_typeid=bt.id', 'inner');
        $this->db->where('bs.isactive', 1);
        $this->db->where('bt.code', 'GLCODE');
        $this->db->where('customerid', $customerid);
        $query = $this->db->get();
        return $query->row_array(); 
    }
    
    /**
    * @desc This function Get Gl Code
    * @param int $id
    * @return array - 
    */
    public function getGlcodeById($id) {
        
        $this->db->select("id, CONCAT(accountname,' (',accountcode,')') as name, CONCAT(accountcode,' (',accountname,')') as glcode"); 
        $this->db->from('customer_glchart');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array(); 
    }
    
    /**
    * @desc This function Get Budget Widget data
    *
    * @return array - 
    */
    public function getBudgetWidgetData($customerid, $month, $year, $jobid, $glcodeid, $glcode) {
        
        
        $budgetData = array(
            'invoiced' => 0,
            'workinprogress' => 0,
            'amount' => 0,
            'budget' => 0
        );
        
        //Bottom bar : Green = Invoiced.
        $sql = "SELECT IFNULL(SUM(NetVAL),0) AS Invoiced"
                . " FROM invoice"
                . " WHERE GLCODe = ‘ :selected_glcode AND MONTH(invoicedate) = :month"
                . " AND YEAR(INVOICEDATE) = :year AND customerid=:customerid";
        
        if($glcode != '') {
            $this->db->select(array("IFNULL(SUM(netval),0) AS invoiced")); 
            $this->db->from('invoice');
            if($glcode != '') {
                $this->db->where('glCode', $glcode);
            }
            $this->db->where('MONTH(invoicedate)', $month);
            $this->db->where('YEAR(invoicedate)', $year);
            $this->db->where('customerid', $customerid);
            $query = $this->db->get();
            $data = $query->row_array();
            if(count($data) > 0) {
                $budgetData['invoiced'] = (double)$data['invoiced'];
            }
        }
        
        
        //Second from bottom : Orange = Work in Progress
        $sql = "SELECT IFNULL(SUM(notexceed),0)"
                . " FROM jobs"
                . " WHERE custglchartid=:selected_glchartid AND MONTH(duedate) = :month"
                . " AND YEAR(duedate) = :year"
                . " AND jobstage NOT IN ('cancelled','declined','hold','wait_client_quote_resp')"
                . " AND IFNULL(quotestatus,'') NOT IN ('pending_submission','pending_approval')"
                . " AND IFNULL(custinvoiceno,0) = 0 AND customerid= :customerid";
        
        
        if($glcodeid != '') {
            $this->db->select(array("IFNULL(SUM(notexceed),0) as workinprogress")); 
            $this->db->from('jobs');
            if($glcodeid != '') {
                $this->db->where('custglchartid', $glcodeid);
            }
            $this->db->where('MONTH(duedate)', $month);
            $this->db->where('YEAR(duedate)', $year);
            $this->db->where("jobstage NOT IN ('cancelled','declined','hold','wait_client_quote_resp')");
            $this->db->where("IFNULL(quotestatus,'') NOT IN ('pending_submission','pending_approval')");
            $this->db->where('IFNULL(custinvoiceno,0)', 0);
            $this->db->where('customerid', $customerid);
            $query = $this->db->get();
            $data = $query->row_array();
            if(count($data) > 0) {
                $budgetData['workinprogress'] = (double)$data['workinprogress'];
            }
        }
        
        
        //Third from bottom : Blue = Value of the selected job or quote that will be added.
        $sql = "SELECT IF(IFNULL(quoterqd,'')='on',estimatedsell,notexceed) AS amount"
                . " FROM jobs WHERE jobid = :selected_jobid";
        
        if($jobid != '') {
            $this->db->select(array("IF(IFNULL(quoterqd,'')='on',estimatedsell,notexceed) AS amount")); 
            $this->db->from('jobs');
            $this->db->where('jobid', $jobid);
            $query = $this->db->get();
            $data = $query->row_array();
            if(count($data) > 0) {
                $budgetData['amount'] = (double)$data['amount'];
            }
        }
        
        //Full bar : XXXX – Budget = value of budget for the month. Show black border with white background
        $sql = "SELECT amount FROM budget b"
                . " INNER JOIN budget_setting bs ON b.budget_settingid=bs.id"
                . " WHERE monthno=:month AND yearno=:year AND glcodeid= :selected_glcodeid"
                . " AND bs.customerid=:customerid";
        
        if($glcodeid != '') {
            $this->db->select("amount"); 
            $this->db->from('budget b');
            $this->db->join('budget_setting bs', 'b.budget_settingid=bs.id', 'inner');
            $this->db->where('monthno', $month);
            $this->db->where('yearno', $year);
            if($glcodeid != '') {
                $this->db->where('glcodeid', $glcodeid);
            }
            $this->db->where('bs.customerid', $customerid);
            $query = $this->db->get();
            $data = $query->row_array();
            if(count($data) > 0) {
                $budgetData['budget'] = (double)$data['budget'];
            }
        }
        
        return $budgetData;
    }
    
    /**
    * This function use for get Budget Data by gl code
    * 
     * @param integer $contactid - logged user contact id
     * @param integer $FromYearMonth - formated yearmonth
     * @param integer $ToYearMonth - formated yearmonth
     * @param date $fromdate
     * @param date $todate
     * @param integer $band
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getBudgetsDataByGlCode($contactid, $FromYearMonth, $ToYearMonth, $fromdate, $todate, $band, $size, $start, $field, $order, $filter, $params) {
 
        $sql = "SELECT b.id,g.accountcode, g.accountname,"
                . "DATE(CONCAT(bs.startyear,'-',bs.startmonth,'-1')) AS startdate,"
                . " DATE_ADD(DATE_ADD(DATE(CONCAT(bs.startyear,'-',bs.startmonth,'-1')), INTERVAL 12 MONTH), INTERVAL -1 DAY) AS enddate,"
                . "SUM(amount) AS budget,getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode) AS Actual,"
                . " SUM(amount)-getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode)  AS remaining,"
                . "IF (SUM(Amount)=0,0,getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode)/SUM( Amount)*100) AS pctspend,"
                . "MAX(b.modifydate) AS lasupdated FROM budget b"
                . " INNER JOIN budget_setting bs ON bs.id=b.budget_settingid"
                . " INNER JOIN customer_glchart g ON b.glcodeid=g.id"
                . " WHERE  bs.customerid=11165  GROUP BY g.accountcode";
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
         
        
        $this->db->select(array("b.id,g.accountcode,g.accountname,"
                . " DATE(CONCAT(bs.startyear,'-',bs.startmonth,'-1')) AS startdate,"
                . " DATE_ADD(DATE_ADD(DATE(CONCAT(bs.startyear,'-',bs.startmonth,'-1')), INTERVAL 12 MONTH), INTERVAL -1 DAY) AS enddate,"
                . " SUM(amount) AS annualbudget, getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode) AS actual,"
                . " SUM(amount)-getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode)  AS remaining,"
                . " IF(SUM(Amount)=0,0,getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode)/SUM( amount)*100) AS pctspend,"
                . "MAX(b.modifydate) AS lastupdated"));
        
        $this->db->from('budget b');
        $this->db->join('budget_setting bs', 'bs.id=b.budget_settingid', 'inner');
        $this->db->join('customer_glchart g', "b.glcodeid=g.id", 'inner');
 
        $this->db->where('bs.customerid', $loggedUserData['customerid']);
        $this->db->where('((b.yearno*100)+b.monthno)>=', $FromYearMonth);
        $this->db->where('((b.yearno*100)+b.monthno)<=', $ToYearMonth);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        /*if($loggedUserData['role'] == 'site contact')
        {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']);
            $where .= "  and  (a.sitecontactid=".$loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }*/
        
        if ($filter != '') {
            $this->db->where("(g.accountcode LIKE '%".$this->db->escape_str($filter)."%' or g.accountname LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('g.accountcode');
        if($band != null)
        {
             
            if($band == 1){
                $this->db->having('pctspend < 50'); 
            } 
            elseif($band == 2) {
                $this->db->having('pctspend>=50 and pctspend<75');
            }
            elseif($band == 3) {
                $this->db->having('pctspend>=75 and pctspend<=100');
            }
            elseif($band == 4) {
                $this->db->having('pctspend>100');
            }

        }
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("b.id, b.glcodeid, CONCAT(g.accountcode,' (',g.accountname,')') as glcode, g.accountcode, g.accountname,"
                . " DATE(CONCAT(bs.startyear,'-',bs.startmonth,'-1')) AS startdate,"
                . " DATE_ADD(DATE_ADD(DATE(CONCAT(bs.startyear,'-',bs.startmonth,'-1')), INTERVAL 12 MONTH), INTERVAL -1 DAY) AS enddate,"
                . " SUM(amount) AS annualbudget, getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode) AS actual,"
                . " SUM(amount)-getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode)  AS remaining,"
                . " IF(SUM(Amount)=0,0,getActualSaleByGLCode(bs.customerid,bs.startyear,bs.startmonth,12,g.accountcode)/SUM( amount)*100) AS pctspend,"
                . "MAX(b.modifydate) AS lastupdated"));
        
        $this->db->from('budget b');
        $this->db->join('budget_setting bs', 'bs.id=b.budget_settingid', 'inner');
        $this->db->join('customer_glchart g', "b.glcodeid=g.id", 'inner');
 
        $this->db->where('bs.customerid', $loggedUserData['customerid']);
        $this->db->where('((b.yearno*100)+b.monthno)>=', $FromYearMonth);
        $this->db->where('((b.yearno*100)+b.monthno)<=', $ToYearMonth);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        /*if($loggedUserData['role'] == 'site contact')
        {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']);
            $where .= "  and  (a.sitecontactid=".$loggedUserData['contactid'].") ";
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }*/
        
        if ($filter != '') {
            $this->db->where("(g.accountcode LIKE '%".$this->db->escape_str($filter)."%' or g.accountname LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('g.accountcode');
        if($band != null)
        {
             
            if($band == 1){
                $this->db->having('pctspend < 50'); 
            } 
            elseif($band == 2) {
                $this->db->having('pctspend>=50 and pctspend<75');
            }
            elseif($band == 3) {
                $this->db->having('pctspend>=75 and pctspend<=100');
            }
            elseif($band == 4) {
                $this->db->having('pctspend>100');
            }

        }
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );

        $this->LogClass->log('Get Budget Data by Gl Code Query : '. $this->db->last_query());

        return $data;
    }
    
    /**
     * This function use for get glcode Budget_Detail
     * 
     * @param integer $glcodeid
     * @param string $FromYearMonth
     * @param string $ToYearMonth
     * @return array
     */
    public function getGlCodeBudgetDetail($glcodeid, $FromYearMonth, $ToYearMonth) {

        $this->db->select('*');
        $this->db->where('glcodeid', $glcodeid);
        $this->db->where('((yearno*100)+monthno)>=',$FromYearMonth);
        $this->db->where('((yearno*100)+monthno)<=',$ToYearMonth);
        $this->db->from('budget');
        $this->db->order_by('monthsort', 'asc');
        $data = $this->db->get()->result_array();
        return $data;
        
    }
    
    /**
    * This function use for Insert new budget
    * @param array $params - the $params is array of budget and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAnnualBudgetByGlCode($params)
    {
        //1 - load multiple models
        require_once('chain/InsertBudgetChain.php');
        
        //2 - initialize instances
        $InsertBudgetChain = new InsertBudgetChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Budget  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $budget_setting = $this->getBudgetSettingByCustomerid($loggedUserData['customerid']);
        $insertData = $params['insertData'];
        $budgetData = array();
 
        $financialyear = $insertData['financialyear'];
        $amount = $insertData['amount'];
        $glcodeid = $insertData['glcodeid'];
        $year=  explode('-', $financialyear);
        $FromYearMonth=$year[0];
        $ToYearMonth=$year[1];
        $FromYear=  (int)substr($FromYearMonth, 0,4);
        $FromMonth=(int)substr($FromYearMonth, 4,2);
       
            
        $totalrecord=0;
        $subworksid=0;
        $itemid=0;
        $categoryid=0;
        $typeid=0;
        $budgetsettingid=0;

        if(count($budget_setting)>0){  
             if($budget_setting['ismonthly']==1){
                 $totalrecord=12;
             } elseif($budget_setting['isquarterly'] == 1){ 
                  $totalrecord=4;
             }elseif($budget_setting['isannual']==1){ 
                 $totalrecord=1;
             }
             $budgetsettingid =  $budget_setting['id'];
        }
        
        $glCodeData = $this->customerclass->getGlCodeByParams(array('customerid'=>$loggedUserData['customerid'], 'accounttype'=>'E', 'id'=>$glcodeid));
        $labelid = 0;
        if(count($glCodeData)) {
            $labelid = $glCodeData['labelid'];
        }

        if($totalrecord > 0){
            $bamount = $amount/$totalrecord;
            $date = $FromYear.'-'.$FromMonth. '-01';
            $date = strtotime($date);

            for($i = 1; $i <= $totalrecord; $i++){

                $budgetData[] = array(
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
                    'hours'             => isset($budget_setting['showhours'])?$budget_setting['showhours']:0,
                    'createuser'        => $this->session->userdata('raptor_email'),
                    'createdate'        => date('Y-m-d H:i:s') 
                );
                $date=strtotime("+".(12/$totalrecord)." month", $date);
            }
        }
        
        $request = array(
            'params'  => $params, 
            'userData'      => $loggedUserData, 
            'budgetData'   =>  $budgetData
        );
 
        $InsertBudgetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertBudgetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
}




/* End of file BudgetClass.php */