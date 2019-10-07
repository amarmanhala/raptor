<?php 
/**
 * Job Libraries Class
 *
 * This is a Job class for Job Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  

/**
 * Job Libraries Class
 *
 * This is a Invoice class for Job Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Job
 * @filesource          JobClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 class JobClass extends MY_Model
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
        $this->LogClass= new LogClass('jobtracker', 'JobClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
   	/*function logit($string){
		
	  //return false;
	  $file = 'C:/temp/raptortest.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
 	  fclose($fh);
	} */ 
    
    /**
    * This function use for getting Waiting Approval Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getWaitingApprovalJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        
        $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions');        
        
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');      
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
        $this->db->where("(j.quoterqd is null or j.quoterqd !='on')"); 
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        $this->db->where('j.leaddate >','2010-07-01');  
        
        if (isset($ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"]) && $ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"] == 1){
            $keepdays =isset($ContactRules["declined_quote_keep_days_in_client_portal"]) ? $ContactRules["declined_quote_keep_days_in_client_portal"]:NULL;
                
            $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions', 'declined');   
            if($keepdays != NULL){
                $this->db->where('DATEDIFF(CURRENT_DATE,j.leaddate) <', $keepdays); 
            } 
        }
        else{
            //$this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')");    
        }
        $this->db->where_in('j.jobstage', $jobstages);
                
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());
                
        
        $this->db->select(array("j.*, CONCAT(cg.accountcode,' (',cg.accountname,')') AS glcode, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');
        $this->db->join('customer_glchart cg', 'j.custglchartid=cg.id', 'left');
                
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
        $this->db->where("(j.quoterqd is null or j.quoterqd !='on')"); 
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        $this->db->where('j.leaddate >','2010-07-01');  
        
        if (isset($ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"]) && $ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"] == 1){
            $keepdays =isset($ContactRules["declined_quote_keep_days_in_client_portal"]) ? $ContactRules["declined_quote_keep_days_in_client_portal"]:NULL;
                
            $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions', 'declined');   
            if($keepdays != NULL){
                $this->db->where('DATEDIFF(CURRENT_DATE,j.leaddate) <', $keepdays); 
            } 
        }
        else{
            //$this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')");    
        }
        $this->db->where_in('j.jobstage', $jobstages);
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Waiting Approval Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
    /**
    * This function use for getting Waiting DCFM Review Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getWaitingDCFMReviewJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $jobStages = array('portal', 'next_allocate', 'info_pending', 'approved_from_portal');        
        
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
        $this->db->where_in('j.jobstage', $jobStages);
        $this->db->where('j.leaddate >','2010-07-01'); 
        $this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
        $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, CONCAT(cg.accountcode,' (',cg.accountname,')') AS glcode, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->join('customer_glchart cg', 'j.custglchartid=cg.id', 'left');
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
        $this->db->where_in('j.jobstage', $jobStages);
        $this->db->where('j.leaddate >','2010-07-01'); 
        $this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
        $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Waiting DCFM Review Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
     /**
    * This function use for getting In Progress Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters  
    * @return array 
    */
    public function getInProgressJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
  
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->join('purchaseorders po', 'j.jobid = po.jobid', 'left'); //New
        $this->db->join('customer cus', 'po.supplierid = cus.customerid', 'left'); //New
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        
                
        $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions');
        $this->db->where("js.portaldesc IN ('Job In Progress','Job Completed Waiting Client Notification')");
        //$this->db->where('js.portaldesc', 'Job In Progress');
        $this->db->where('j.leaddate >','2010-07-01'); 
        //$this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
        $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
                
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, CONCAT(cg.accountcode,' (',cg.accountname,')') AS glcode, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site, if(j.cpallocated= 'O', 'DCFM', cus.companyname) as supplier"));
        
        $this->db->from('jobs j'); 
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->join('purchaseorders po', 'j.jobid = po.jobid', 'left'); //New
        $this->db->join('customer cus', 'po.supplierid = cus.customerid', 'left'); //New
        $this->db->join('customer_glchart cg', 'j.custglchartid=cg.id', 'left');
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions');
        $this->db->where("js.portaldesc IN ('Job In Progress','Job Completed Waiting Client Notification')");
        //$this->db->where('js.portaldesc', 'Job In Progress');
        $this->db->where('j.leaddate >','2010-07-01'); 
        //$this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
        $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
          foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        $this->db->group_by('j.jobid');
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get In Progress Jobs Data Query : '. $this->db->last_query());
 		
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    

    
     /**
    * This function use for getting Waiting Variation Approval Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getWaitingVariationApprovalJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $excludeJobStage = array('cancelled', 'hold', 'declined');
        
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where_not_in('j.jobstage', $excludeJobStage);
        $this->db->where('j.leaddate >','2010-07-01'); 
        $this->db->where('variationstage','variation_request_sent');   
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, CONCAT(cg.accountcode,' (',cg.accountname,')') AS glcode, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left');
        $this->db->join('customer_glchart cg', 'j.custglchartid=cg.id', 'left');
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where_not_in('j.jobstage', $excludeJobStage);
        $this->db->where('j.leaddate >','2010-07-01'); 
        $this->db->where('variationstage','variation_request_sent');  
       
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get In Waiting Variation Approval Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
    /**
    * This function use for getting Completed Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getCompletedJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->join('purchaseorders po', 'j.jobid = po.jobid', 'left'); //New
        $this->db->join('customer cus', 'po.supplierid = cus.customerid', 'left'); //New
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('js.portaldesc', 'Job Completed');
        $this->db->where('j.leaddate >','2010-07-01');  
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
                
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, CONCAT(cg.accountcode,' (',cg.accountname,')') AS glcode, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site, if(j.cpallocated= 'O', 'DCFM', cus.companyname) as supplier"));
        
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->join('purchaseorders po', 'j.jobid = po.jobid', 'left'); //New
        $this->db->join('customer cus', 'po.supplierid = cus.customerid', 'left'); //New
        $this->db->join('customer_glchart cg', 'j.custglchartid=cg.id', 'left');
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('js.portaldesc', 'Job Completed');
        $this->db->where('j.leaddate >','2010-07-01');  
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        $this->db->group_by('j.jobid');
        $data = $this->db->get()->result_array();
        
         $this->LogClass->log('Get In Completed Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
    
    /**
    * This function use for getting Completed Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getOnHoldJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left');
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('j.jobstage', 'hold');
        $this->db->where('j.leaddate >','2010-07-01');  
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where('j.jobstage', 'hold');
        $this->db->where('j.leaddate >','2010-07-01');  
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get In Completed Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
     /**
    * This function use for getting Jobs List data for selected jobstage
     * 
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @param array $customwheres - it is use for external filters 
    * @return array 
    */
    public function getSearchJobs($contactid, $size, $start, $field, $order, $filter = '', $params = array(), $customwheres = array()) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        if (isset($ContactRules["show_jobs_on_hold"]) && $ContactRules["show_jobs_on_hold"] == 1){
            $excludeStages = array('cancelled', 'declined'); //, 'client_notified'
        }
        else{
            $excludeStages = array('cancelled', 'hold', 'declined'); //, 'client_notified'
        }
        
        $excludeqStages = array('pending_submission', 'pending_approval');
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left'); 
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
        $this->db->where_not_in('j.jobstage', $excludeStages); 
        $this->db->where("IFNULL(j.quotestatus,'') not in ('pending_approval')"); //'pending_submission',	
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        foreach ($customwheres as $fn => $fv) {
            $this->db->where($fv);
        } 
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());
        
        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
                
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');   
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where_not_in('j.jobstage', $excludeStages); 
        $this->db->where("IFNULL(j.quotestatus,'') not in ('pending_approval')"); //'pending_submission',	
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
        foreach ($customwheres as $fn => $fv) {
            $this->db->where($fv);
        }        
                
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Jobs Data By Stage Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
                
    /**
    * This function use for getting Pending Approval Quote List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getPendingApprovalQuotes($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
              
                
        $jobStage = array('wait_client_quote_resp', 'wait_client_qte_resp');
    
        
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where("j.quoterqd", 'on'); 
        $this->db->where("lcase(j.quotestatus)", 'pending_approval'); 
        $this->db->where_in('j.jobstage', $jobStage);
        
        //$this->db->where("((lcase(j.quotestatus)!='accepted') and (lcase(j.quotestatus)!='declined') and (lcase(j.quotestatus)!='pending_submission') and j.quotestatus is null)");  
        //$this->db->where("(portaldesc='Waiting Client Quote Approval' or jobstage='wait_client_qte_resp' or jobstage='wait_client_quote_resp')");  
        $this->db->where('j.leaddate >','2010-07-01');    
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
         $this->db->where("j.quoterqd", 'on'); 
        $this->db->where("lcase(j.quotestatus)", 'pending_approval'); 
        $this->db->where_in('j.jobstage', $jobStage);
       // $this->db->where("((lcase(j.quotestatus)!='accepted') and (lcase(j.quotestatus)!='declined') and (lcase(j.quotestatus)!='pending_submission') or j.quotestatus is null)");  
       // $this->db->where("(portaldesc='Waiting Client Quote Approval' or jobstage='wait_client_qte_resp' or jobstage='wait_client_quote_resp')");  
        $this->db->where('j.leaddate >','2010-07-01');     
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Pending Approval Quotes Data Query : '. $this->db->last_query());
        //aklog('Get Pending Approval Quotes Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
                
    /**
    * This function use for getting In Progress Quote List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getInProgressQuotes($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
              
        $excludeJobStage = array('client_notified');
        $quotestatus = array('accepted','approved_from_portal');
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where("j.quoterqd", 'on'); 
        $this->db->where_in('lcase(j.quotestatus)',$quotestatus);  
        $this->db->where_not_in('j.jobstage', $excludeJobStage);
        $this->db->where('j.leaddate >','2010-07-01');    
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where("j.quoterqd", 'on'); 
        $this->db->where_in('lcase(j.quotestatus)',$quotestatus);   
        $this->db->where_not_in('j.jobstage', $excludeJobStage);
        $this->db->where('j.leaddate >','2010-07-01');  
        
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get In Progress Quotes Data Query : '. $this->db->last_query());
        //aklog('Get In Progress Quotes Data Query : '. $this->db->last_query());
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
    /**
    * This function use for getting Waiting DCFM Review Quotes List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getWaitingDCFMReviewQuotes($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $jobStage = array('next_sendquote', 'waiting_rfq_response','portal','waiting_jrq_response','internal_incomplete', 'portal_await_approval');
                
        
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
                
        $this->db->where("j.quoterqd", 'on'); 
        $this->db->where("lcase(j.quotestatus)", 'pending_submission'); 
        $this->db->where_in("jobstage",$jobStage);  
        $this->db->where('j.leaddate >','2010-07-01');    
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site, INSTR(j.jobdescription,'SCOPE OF WORK'),
                            IF(INSTR(j.jobdescription,'EXCLUSIONS')>0, CONCAT(LEFT(j.jobdescription,INSTR(j.jobdescription,'EXCLUSIONS')-1), 'QUOTATION NOT FINALISED'), j.jobdescription) AS jobdescription"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');  
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);  
        $this->db->where("j.quoterqd", 'on'); 
        $this->db->where("lcase(j.quotestatus)", 'pending_submission'); 
        $this->db->where_in("jobstage",$jobStage);    
        $this->db->where('j.leaddate >','2010-07-01');     
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Waiting DCFM Review Quotes Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
		
		//$this->logit('waiting DCFM Review Quots');
		//$this->logit($this->db->last_query() );
        return $result;
    }
       
    
     /**
    * This function use for getting Review Waiting WO Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getReviewWaitingWOJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where("j.custordref",''); 
        $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
        $this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')"); 
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        $this->db->where('j.leaddate >','2010-07-01');  
                
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

                
        
        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where("j.custordref",''); 
        $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
        $this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')"); 
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        $this->db->where('j.leaddate >','2010-07-01');  
                
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('get Review Waiting WO Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }            
    
    /**
    * This function use for getting Waiting WO Approval History Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getWaitingWOApprovalHistoryJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where("jobConvertedDate IS NOT NULL"); 
        $this->db->where("j.jobstage",'portal'); 
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        $this->db->where('j.leaddate >','2010-07-01');  
                
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

                
        
        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where("jobConvertedDate IS NOT NULL"); 
        $this->db->where("j.jobstage",'portal');  
         
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        $this->db->where('j.leaddate >','2010-07-01');  
                
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('get Waiting WO Approval History Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    } 
    
     /**
    * This function use for getting Waiting WO Decline History Jobs List data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getWaitingWODeclineHistoryJobs($contactid, $size, $start, $field, $order, $filter, $params) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        $this->db->select("j.jobid");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where("leadDeclinedDate IS NOT NULL"); 
        $this->db->where("j.jobstage",'declined');     
        
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }

        $this->db->where('j.leaddate >','2010-07-01');  
                
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = count($this->db->get()->result_array());

                
        
        $this->db->select(array("j.*, if(portaldesc!='', portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where("leadDeclinedDate IS NOT NULL"); 
        $this->db->where("j.jobstage",'declined');
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        $this->db->where('j.leaddate >','2010-07-01');  
                
        
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $this->db->where_in($fn, $fv);
                }
            }
            else {
                if ($fv != '') {
                    $this->db->where($fn, $fv);
                }
            }
        }
                
        
        if ($filter != '') {
            $this->db->where("(j.jobid LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.jobstage LIKE '%".$this->db->escape_str($filter)."%'  or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('get Waiting WO Decline History Jobs Data Query : '. $this->db->last_query());
        
        $result = array(
            'trows' => $trows,
            'data'  => $data
        );
        return $result;
    }
    
     /**
    * This function use for get Waiting Approval Lead Jobs
    * 
    * @param integer $customerid - logged user customerid id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getWaitingApprovalLeadJobs($customerid, $size, $start, $field, $order, $filter, $params) {
 
                
        $this->db->select('jl.joblead_id');
        $this->db->from('joblead jl');
        $this->db->join('contact c', 'jl.sitefmemail=c.email', 'inner');
        $this->db->join('se_works w', "w.id=jl.works_type_id", 'left');
        $this->db->where('c.customerid', $customerid);
        $this->db->where("jl.approvaldate IS NULL AND jl.declineddate IS NULL");
        $this->db->where("IFNULL(jl.sitefmemail,'') != ''");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        if ($filter != '') {
            $this->db->where("(jl.sitefmemail LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or jl.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or jl.sitestate LIKE '%".$this->db->escape_str($filter)."%' or jl.sitefm LIKE '%".$this->db->escape_str($filter)."%' or jl.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
                
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("jl.joblead_id, jl.jobleaddescription, siteline2, sitesuburb, sitestate, leaduserid, leaddate, labour_estimate, material_estimate, material_estimate_value, w.se_works_name");
        $this->db->from('joblead jl');
        $this->db->join('contact c', 'jl.sitefmemail=c.email', 'inner');
        $this->db->join('se_works w', "w.id=jl.works_type_id", 'left');
        $this->db->where('c.customerid', $customerid);
        $this->db->where("jl.approvaldate IS NULL AND jl.declineddate IS NULL");
        $this->db->where("IFNULL(jl.sitefmemail,'') != ''");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        if ($filter != '') {
            $this->db->where("(jl.sitefmemail LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or jl.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or jl.sitestate LIKE '%".$this->db->escape_str($filter)."%' or jl.sitefm LIKE '%".$this->db->escape_str($filter)."%' or jl.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
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

        $this->LogClass->log('Get Waiting Approval Lead Jobs Data Query : '. $this->db->last_query());

        return $data;
    }
    
     /**
    * This function use for get Declined Lead Jobs
    * 
    * @param integer $customerid - logged user customerid id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getDeclinedLeadJobs($customerid, $size, $start, $field, $order, $filter, $params) {
 
                
        $this->db->select('jl.joblead_id');
        $this->db->from('joblead jl');
        $this->db->join('contact c', 'jl.sitefmemail=c.email', 'inner');
        $this->db->join('se_works w', "w.id=jl.works_type_id", 'left');
        $this->db->where('c.customerid', $customerid);
        $this->db->where("jl.approvaldate IS NULL AND jl.declineddate IS NOT NULL");
        $this->db->where("IFNULL(jl.sitefmemail,'') != ''");
         
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
         if ($filter != '') {
            $this->db->where("(jl.sitefmemail LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or jl.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or jl.sitestate LIKE '%".$this->db->escape_str($filter)."%' or jl.sitefm LIKE '%".$this->db->escape_str($filter)."%' or jl.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
                
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("jl.joblead_id, jl.jobleaddescription, siteline2, sitesuburb, sitestate, leaduserid, leaddate, labour_estimate, material_estimate, material_estimate_value, w.se_works_name");
        $this->db->from('joblead jl');
        $this->db->join('contact c', 'jl.sitefmemail=c.email', 'inner');
        $this->db->join('se_works w', "w.id=jl.works_type_id", 'left');
        $this->db->where('c.customerid', $customerid);
        $this->db->where("jl.approvaldate IS NULL AND jl.declineddate IS NOT NULL");
        $this->db->where("IFNULL(jl.sitefmemail,'') != ''");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        if ($filter != '') {
            $this->db->where("(jl.sitefmemail LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or jl.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or jl.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or jl.sitestate LIKE '%".$this->db->escape_str($filter)."%' or jl.sitefm LIKE '%".$this->db->escape_str($filter)."%' or jl.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or jl.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
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

        $this->LogClass->log('Get Declined Lead Jobs Data Query : '. $this->db->last_query());

        return $data;
    }
    
    /**
    * This function use for create new job
    * @param array $params - the params is array of job detail and contactid(LoggedUser)
    * @return array
    */
    public function createJob($params)
    {
        //1 - load multiple models
       
        require_once('chain/InsertJobChain.php');
        require_once('chain/InsertParentJobChain.php');
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $InsertJobChain = new InsertJobChain();
        $InsertParentJobChain = new InsertParentJobChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $InsertJobChain->setSuccessor($InsertParentJobChain);
        $InsertParentJobChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        //Create etp_job data array
        $jobData = $params['jobData'];  
        
        $jobData['parentid'] = false;
        
        if (isset($ContactRules["use_contract_parent_job"]) && $ContactRules["use_contract_parent_job"] == 1){
            $addresslabeldetails=$this->customerClass->getAddressById($jobData['labelid']);
            $contactidforparentjob=0;
            if (isset($ContactRules["contract_parent_job_by_fm"]) && $ContactRules["contract_parent_job_by_fm"] != 0){
                 $contactidforparentjob= isset($addresslabeldetails['contactid'])? $addresslabeldetails['contactid'] : 0;
            }
            $parentjobid = $this->getParentJobByMonthYear(date('m'), date('Y'), $loggedUserData['customerid'], $contactidforparentjob);
            $jobData['parentid'] = $parentjobid;
             
        }
        
        $emailData = array();
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'JOBREQ'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'JOBREQ'),
              'message'     => $this->getEmailBody($jobData, 'JOBREQ'),

        );
                
        
        $request = array(
            'params'    => $params,
            'userData'   => $loggedUserData,
            'jobData'    => $jobData,
            'emailData'  => $emailData,
            'ContactRules' =>$ContactRules
        );
 
                
        $InsertJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for create new job
    * @param array $params - the params is array of job detail and contactid(LoggedUser)
    * @return array
    */
    public function updateJob($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain();
        $EditLogChain = new EditLogChain();
       
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($EditLogChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);
        //Create etp_job data array
        $updateJobData = $params['updateData'];
       
        $editLogData=array();
        foreach ($updateJobData as $key => $value) {
            if (trim($jobData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'jobs' , 
                    'recordid'  => $params['jobid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $jobData[$key], 
                    'newvalue'  => $value
                );
            }
        }
        
         
        $request = array(
            'params'     => $params,
            'userData'      => $loggedUserData,
            'jobData'       => $jobData,
            'updateJobData' => $updateJobData,
            'editLogData'   => $editLogData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for Job Approval
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function approveJob($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'jobstage'        => 'portal',
            'jobapprovaldate' => date('Y-m-d H:i:s'),
            'jobapprovalby'    => $loggedUserData['email'] 
        );
        
        
        //Create job notes data array
        $jobNoteData = array( 
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting Client approval Job approved in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
            'recipient'   => $this->getRecipients($jobData, 'JOBAPP'), 
            'cc'          => "dcfm@dcfm.com.au", 
            'customerid'  => $jobData['customerid'], 
            'subject'     => $this->getEmailSubject($jobData, 'JOBAPP'),
            'message'     => $this->getEmailBody($jobData, 'JOBAPP')

        );        
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Job Decline
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function declineJob($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'jobstage' => 'declined',
            'closed' => 'on'
        );
        
        
        //Create job notes data array
        $jobNoteData = array(
            //'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting Client approval Job declined in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'JOBDEC'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'JOBDEC'),
              'message'     => $this->getEmailBody($jobData, 'JOBDEC'),

        );        
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for requestQuote
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function requestQuote($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'jobstage'      => 'portal',
            'quoterqd'      => 'on',
            'quotestatus'   => 'pending_submission'
        );
        
        
        //Create job notes data array
        $jobNoteData = array(
            //'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting Client approval Job quote requested in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'QUOTEREQ'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'QUOTEREQ'),
              'message'     => $this->getEmailBody($jobData, 'QUOTEREQ'),

        );        
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
     /**
    * This function use for Job Approval
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function approveJobVariation($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $EditLogChain = new EditLogChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($EditLogChain);
        $EditLogChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'variationstage' => 'variation_approved',
            'vapprovaldate'  => date("Y-m-d H:i:s"), 
            'variationapprovalby'=> $loggedUserData['email'],
            'vapprovalref'   => $params['notes'],
            'duedate'       => $params['duedate'],
            'duetime'       => $params['duetime'],
            'custordref'    => $params['custordref'],
            'custordref2'   => $params['custordref2'],
            'custordref3'   => $params['custordref3'],
            'jobstage'      => 'Approved_from_portal'
        
        );
        
        $editLogData=array();
        foreach ($updateJobData as $key => $value) {
            if (trim($jobData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'jobs' , 
                    'recordid'  => $params['jobid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $jobData[$key], 
                    'newvalue'  => $value
                );
            }
        }       
        
        //Create job notes data array
        $jobNoteData = array(
           // 'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Variation approved from portal by '. $loggedUserData["email"]. ' '. $params['notes'],
            'ntype'      => 'Variation Approval',
            'notetype'   => 'Client',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
              'recipient'   => 'dcfm@dcfm.com.au"',  
              'customerid'  => $jobData['customerid'], 
              'subject'     => 'Variation with Job ID '.$params["jobid"].' has been approved from the portal.',
              'message'     => 'Variation with Job ID '.$params['jobid'].' has been approved from the portal on '.date("d/m/y : H:i:s", time()).' by '.$loggedUserData['email'].'.'
        ); 
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'VARIATIONAPP'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'VARIATIONAPP'),
              'message'     => $this->getEmailBody($jobData, 'VARIATIONAPP'),

        );        
          
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'editLogData'       => $editLogData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Job Decline
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function declineJobVariation($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain();
        $EditLogChain = new EditLogChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($EditLogChain);
        $EditLogChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'variationstage' => 'Variation_declined', 
            'jobstage' => 'declined',
            'closed' => 'on'
        );
        
        $this->db->select("COUNT(*) as extcount");
        $this->db->from('purchaseorders po'); 
        $this->db->join('customer c', 'po.supplierid =c.customerid', 'inner');
        $this->db->where('jobid', $params['jobid']);
        $this->db->where('c.origin', 'external'); 
        $results = $this->db->get()->row_array();
        if(count($results)==0){
            if($results['extcount']==0){
                $updateJobData['jobstage'] = 'Internal_Incomplete ';
            }
            else{
                $updateJobData['jobstage'] = 'External_Incomplete';
            }
        }
                
        
        $preVarSuccess = $this->preVarExceedValue($params['jobid']);
		
        $pvsuccess = $preVarSuccess['success'];
        $preExceed = $preVarSuccess['preExceed'];
        $preBuffer = $preVarSuccess['preBuffer'];

        if ($pvsuccess){
            $updateJobData['notexceed'] = $preExceed;
            $updateJobData['dcfmbuffer'] = $preBuffer;
        }
                
	
        $editLogData=array();
        foreach ($updateJobData as $key => $value) {
            if (trim($jobData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'jobs' , 
                    'recordid'  => $params['jobid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $jobData[$key], 
                    'newvalue'  => $value
                );
            }
        }      
        
        $declinenote = "Variation stage set: Variation_declined from Client Portal at ".date("d/m/y : H:i:s", time())." by ". $loggedUserData['email'] .'\n'. $params['notes'];
                
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'variation',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => $declinenote,
            'reason'      => $params["reason"],
            'ntype'      => 'variation',
            'notetype'   => 'internal',
            'userid'     => $loggedUserData['email']
        );
        
        $emailData = array();
        //Email servicedesk to advise Techs
        $emailData[]  = array(
              'recipient'   => 'service1@dcfm.com.au"',  
              'customerid'  => $jobData['customerid'], 
              'subject'     => 'Variation has been declined from the client portal by '.$loggedUserData['email'].' on DCFM Job No '.$params["jobid"],
              'message'     => 'Please advise Technicians to discontinue further work on DCFM Job No '.$params['jobid'].' as our request for variation has been declined on '.date("d/m/y : H:i:s", time()).' by '.$loggedUserData['email'].'.'
        ); 
        
//        $emailData[]  = array(
//              'recipient'   => $this->getRecipients($jobData, 'QUOTEDEC'), 
//              'cc'          => "dcfm@dcfm.com.au", 
//              'customerid'  => $jobData['customerid'], 
//              'subject'     => $this->getEmailSubject($jobData, 'QUOTEDEC'),
//              'message'     => $this->getEmailBody($jobData, 'QUOTEDEC'),
//
//        );  
        
//        //email RFQ Recipients
//	$emailedsuppliers = array();
//	$this->db->select("rfqno, email, supplierid");
//        $this->db->from('rfq'); 
//        $this->db->join('customer c', 'rfq.supplierid =c.customerid', 'inner');
//        $this->db->where('jobid', $params['jobid']);
//        $this->db->where("IFNULL(email,'') != ''");
//   
//        $results = $this->db->get()->result_array();	
//        foreach ($results as $row) {
//            $emailedsuppliers[] = $row['supplierid'];
//            $emailData[]  = array(
//              'recipient'   => $row['email'], 
//              'cc'          => "dcfm@dcfm.com.au", 
//              'customerid'  => $row['supplierid'], 
//              'subject'     => 'Please discontinue further work on DCFM Job No '. $params['jobid'],
//              'message'     => 'Please discontinue further work on DCFM Job No '. $params['jobid'] .' as our request for variation for not approved.',
//
//            );    
//        }
//        
//        //email JRQ Recipients 
//	$this->db->select("poref, email, supplierid");
//        $this->db->from('purchaseorders po'); 
//        $this->db->join('customer c', 'po.supplierid =c.customerid', 'inner');
//        $this->db->where('jobid', $params['jobid']);
//        $this->db->where("IFNULL(email,'') != ''");
//   
//        $results = $this->db->get()->result_array();	
//        foreach ($results as $row) {
//            if (! array_key_exists($row['supplierid'], $emailedsuppliers)) {
//                $emailedsuppliers[] = $row['supplierid'];
//                $emailData[]  = array(
//                  'recipient'   => $row['email'], 
//                  'cc'          => "dcfm@dcfm.com.au", 
//                  'customerid'  => $row['supplierid'], 
//                  'subject'     => 'Please discontinue further work on DCFM Job No '. $params['jobid'],
//                  'message'     => 'Please discontinue further work on DCFM Job No '. $params['jobid'] .' as our request for variation for not approved.',
//
//                ); 
//            }
//        }  
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'editLogData'       => $editLogData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
     /**
    * This function use for Quote Approval
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function approveQuote($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $EditLogChain = new EditLogChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($EditLogChain);
        $EditLogChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'quotestatus'       => 'Approved_from_portal',
            'duedate'           => $params['duedate'],
            'duetime'           => $params['duetime'],
            'custordref'        => $params['custordref'],
            'custordref2'       => $params['custordref2'],
            'custordref3'       => $params['custordref3'],
            'jobstage'          => 'Approved_from_portal',
            'qdateaccepted'     => date('Y-m-d H:i:s'),
            'quoteapprovalby'   => $loggedUserData['email'] 
        
        );
        
        $internalduedate =  strtotime("-".$jobData["jdaysbuffer"]." day", strtotime($updateJobData['duedate']));
                
        $updateJobData["internalduedate"] = date('Y-m-d',$internalduedate);
        
        $editLogData=array();
        foreach ($updateJobData as $key => $value) {
            if (trim($jobData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'jobs' , 
                    'recordid'  => $params['jobid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $jobData[$key], 
                    'newvalue'  => $value
                );
            }
        }       
        
        //Create job notes data array
        $jobNoteData = array(
           // 'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Quote approved in portal by '. $loggedUserData["email"]. ' '. $params['notes'],
            'ntype'      => 'quote approval',
            'notetype'   => 'client',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
              'recipient'   => 'dcfm@dcfm.com.au"',  
              'customerid'  => $jobData['customerid'], 
              'subject'     => 'Quote with Job ID '.$params["jobid"].' has been approved from the portal.',
              'message'     => 'Quote with Job ID '.$params['jobid'].' has been approved from the portal on '.date("d/m/y : H:i:s", time()).' by '.$loggedUserData['email'].'.'
        ); 
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'QUOTEAPP'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'QUOTEAPP'),
              'message'     => $this->getEmailBody($jobData, 'QUOTEAPP'),

        );        
          
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'editLogData'       => $editLogData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Job Decline
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function declineQuote($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain();
        $EditLogChain = new EditLogChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($EditLogChain);
        $EditLogChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'quotestatus' => 'declined', 
            'jobstage' => 'declined' 
        );
                
	
        $editLogData=array();
        foreach ($updateJobData as $key => $value) {
            if (trim($jobData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'jobs' , 
                    'recordid'  => $params['jobid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $jobData[$key], 
                    'newvalue'  => $value
                );
            }
        }      
        
                
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'variation',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => $params['notes'],
            'reason'      => $params["reason"],
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'],
            'contactid'  => $loggedUserData['contactid']
        );
        
        $emailData = array();
        //Email servicedesk to advise Techs
        $emailData[]  = array(
              'recipient'   => 'service1@dcfm.com.au"',  
              'customerid'  => $jobData['customerid'], 
              'subject'     => 'Quote with Job ID '.$params['jobid'].' has been declined from the portal.',
              'message'     => "Quote with Job ID ".$params['jobid']." has been declined from the portal on ".date("d/m/y : H:i:s", time()).".<br><br>Quote declined Reason: ".$params["reason"]."<br><br>Note: ".$params["notes"]
        ); 
        
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'QUOTEDEC'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'QUOTEDEC'),
              'message'     => $this->getEmailBody($jobData, 'QUOTEDEC'),

        );  
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'editLogData'       => $editLogData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
     /**
    * This function use for Job Approval
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function approveWOJob($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');  
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $JobNoteChain = new JobNoteChain(); 
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain); 
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'jobstage'          => 'portal',
            'jobConvertedDate'  => date('Y-m-d H:i:s'),
            'jobConvertedBy'  => $loggedUserData['email'] 
        );
        if($jobData['variationstage'] == 'variation_request_sent'){
            $updateJobData['variationstage'] = 'variation_approved';
            $updateJobData['vapprovaldate'] = date('Y-m-d H:i:s');
            $updateJobData['vapprovalref'] = 'Approved from portal';
        }
          
        
        //Create job notes data array
        $jobNoteData = array(
           // 'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting WO job approved in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
                
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData 
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Job Decline
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function declineWOJob($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $JobNoteChain = new JobNoteChain(); 
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain); 
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'jobstage' => 'declined',
            'leadDeclinedDate'  => date('Y-m-d H:i:s'),
            'leadDeclinedBy'  => $loggedUserData['email']
        );
        
        
        //Create job notes data array
        $jobNoteData = array(
            //'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting WO job declined in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
                
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData 
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for requestQuote
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function requestQuoteWOJobs($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain(); 
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $jobData = $this->getJobById($params['jobid']);        
                
        $updateJobData = array(
            'jobstage'      => 'portal',
            'quoterqd'      => 'on',
            'quotestatus'   => 'pending_submission',
            'jobConvertedDate'  => date('Y-m-d H:i:s'),
            'jobConvertedBy'  => $loggedUserData['email'] 
        );
            
                
        //Create job notes data array
        $jobNoteData = array(
            //'origin'     => 'raptor',
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting WO job quote requested in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
        
        $emailData = array();
        $emailData[]  = array(
              'recipient'   => $this->getRecipients($jobData, 'QUOTEREQ'), 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $jobData['customerid'], 
              'subject'     => $this->getEmailSubject($jobData, 'QUOTEREQ'),
              'message'     => $this->getEmailBody($jobData, 'QUOTEREQ'),

        );        
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for create new job
    * @param array $params - the params is array of job detail and contactid(LoggedUser)
    * @return array
    */
    public function updateLeadJob($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateLeadJobChain.php'); 
        
         //2 - initialize instances
        $UpdateLeadJobChain = new UpdateLeadJobChain(); 
       
        //3 - get the parts connected 
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
                
        //Create etp_job data array
        $updateJobLeadData = $params['updateData'];
                
         
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData, 
            'updateJobLeadData' => $updateJobLeadData 
        );
 
        $UpdateLeadJobChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateLeadJobChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    
     /**
    * This function use for Job Approval
    * @param array $params - the params is array of Job detail and contactid(LoggedUser)
    * @return array
    */
    public function allocateJob($params)
    {
        //1 - load multiple models
                
        require_once('chain/UpdateJobChain.php');
        require_once(__DIR__.'/../purchaseorder/chain/InsertPurchaseOrderChain.php');
        require_once(__DIR__.'/../purchaseorder/chain/CreateJobDocumentRequiredChain.php');
        require_once(__DIR__.'/../purchaseorder/chain/GeneratePDFChain.php');
        require_once(__DIR__.'/../document/chain/InsertDocumentChain.php');
        require_once(__DIR__.'/../purchaseorder/chain/CopyJobRequestDocumentChain.php');
        require_once('chain/InsertETPJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
        
        //2 - initialize instances
        $UpdateJobChain = new UpdateJobChain();
        $InsertPurchaseOrderChain = new InsertPurchaseOrderChain(); 
        $CreateJobDocumentRequiredChain = new CreateJobDocumentRequiredChain(); 
        $InsertETPJobChain = new InsertETPJobChain();
        $GeneratePDFChain = new GeneratePDFChain();
        $CopyJobRequestDocumentChain = new CopyJobRequestDocumentChain();
        $InsertDocumentChain = new InsertDocumentChain();
        $JobNoteChain = new JobNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateJobChain->setSuccessor($JobNoteChain);
        $JobNoteChain->setSuccessor($EmailChain);
        
        //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $jobData = $this->getJobById($params['jobid']);        
             
        
        if($params['allocateto'] == 'Landlord'){
            $updateJobData = array(
                'jobstage'      => 'landlord_allocated',
                'cpallocated'   => 'L',
                'jobapprovaldate' => date('Y-m-d H:i:s'),
                'jobapprovalby'    => $loggedUserData['email'] 
            );
        }
        elseif($params['allocateto'] == 'Supplier'){
            $updateJobData = array(
                'jobstage'      => 'direct_allocated',
                'cpallocated'   => 'S',
                'jobapprovaldate' => date('Y-m-d H:i:s'),
                'jobapprovalby'    => $loggedUserData['email'] 
            );
        }
        elseif($params['allocateto'] == 'Internal'){
            $updateJobData = array(
                'jobstage'      => 'client_allocated',
                'cpallocated'   => 'I',
                'jobapprovaldate' => date('Y-m-d H:i:s'),
                'jobapprovalby'    => $loggedUserData['email'] 
            );
        }
        else{
            $updateJobData = array(
                'jobstage'      => 'approved_from_portal',
                'cpallocated'   => 'O',
                'jobapprovaldate' => date('Y-m-d H:i:s'),
                'jobapprovalby'    => $loggedUserData['email'] 
            );
        }
        $emailData = array();  
        //Create job notes data array
        $jobNoteData = array( 
            'jobid'      => $params["jobid"],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Waiting Client approval Job Allocated to '.$params['allocateto'].' in portal by '.$loggedUserData["email"]. ' on '.date('Y-m-d H:i:s'),
            'ntype'      => 'portal',
            'notetype'   => 'portal',
            'userid'     => $loggedUserData['email'] 
        );
        
       
        $emailData[]  = array(
            'recipient'   => $this->getRecipients($jobData, 'RAPTOR_NEWJOB_CLIENT'), 
            'cc'          => "dcfm@dcfm.com.au", 
            'customerid'  => $jobData['customerid'], 
            'subject'     => $this->getEmailSubject($jobData, 'RAPTOR_NEWJOB_CLIENT'),
            'message'     => $this->getEmailBody($jobData, 'RAPTOR_NEWJOB_CLIENT') 
        );       
        
        if($params['allocateto'] == 'Landlord' || $params['allocateto'] == 'Supplier' || $params['allocateto'] == 'Internal'){
            $supplierData = $this->customerClass->getCustomerById($params['supplierid']);
            $UpdateJobChain->setSuccessor($InsertPurchaseOrderChain);
            $InsertPurchaseOrderChain->setSuccessor($CreateJobDocumentRequiredChain);
            $CreateJobDocumentRequiredChain->setSuccessor($GeneratePDFChain);
            $GeneratePDFChain->setSuccessor($InsertDocumentChain);
            $InsertDocumentChain->setSuccessor($CopyJobRequestDocumentChain); 
            $CopyJobRequestDocumentChain->setSuccessor($JobNoteChain);
            
                
            $purchaseOrderData = array(
                'supplierid'        => $params['supplierid'],
                'statusid'          => 10,
                'podate'            => date('Y-m-d H:i:s'),
                'attendbydate'      => $jobData['jrespintdate'],
                'attendbytime'      => $jobData['jrespinttime'],
                'completebydate'    => $jobData['duedate'],
                'completebytime'    => $jobData['duetime'],
                'jobid'             => $jobData['jobid'], 
                'userid'            => $loggedUserData['email'],
                'polimit'           => $jobData['notexceed'],
                'ownercustid'       => $jobData['customerid']
            );

            
            $documentData = array(
                'documentdesc'  => 'Job Request <poref>',
                'doctype'       => 'Client Order',
                'docformat'     => 'pdf',
                'dateadded'     => date('Y-m-d H:i:s',time()),
                'userid'        => $loggedUserData['email'],
                'xrefid'        => $jobData['jobid'],
                'xreftable'     => 'jobs',
                'docname'       => 'Jrq_poref.pdf',
                'pdate'         => date('Y-m-d H:i:s',time()),
                'origin'        => 'Client Portal',
                'docfolder'     => 'Client Order',
                'filesize'      => 200
            );
            
            
                
            $docsA = array();
            $rules = $this->customerClass->getCustomerRequireDocs($jobData['customerid']);
            $requiredJobDocuments = array();
            $document_root = $_SERVER['DOCUMENT_ROOT'];
            
            $rest = substr($document_root, -1);
            if (ctype_alpha($rest)) {
                $document_root = $document_root.'/';
            }
            
            $targetdir = $this->config->item('document_dir');
            if(!$targetdir){
                $targetdir = $document_root.'infomaniacDocs/jobdocs';
            }
            
            foreach ($rules as $rule) {
                
                $requiredJobDocuments[] = array(   
                    "documentid"            => $rule['documentid'],
                    "jobid"                 => $jobData['jobid'],
                    "required_start"        => $rule['required_start'],
                    "required_complete"     => $rule['required_complete'],
                    "poref"                 => 0,
                    "from_category_rule"    => $rule['cat_id'],
                    "dont_send"             => $rule['dont_send'],
                    'create_date'           => date('Y-m-d H:i:s', time())
                );
                if($rule['docformat'] == NULL || $rule['docformat'] == ''){
                    $rule['docformat'] = 'pdf';
                }
                
                $doc = array();
                $doc['fname'] = $rule['documentid'].'.'.$rule['docformat'];
                $doc['dname'] = $rule['documentid'].'.'.$rule['docformat'];
                $doc['relpath'] = $targetdir;
                $docsA[] = $doc;
                
            }
                
            $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
            if(isset($ContactRules['internal_allocate_auto_accept']) && $ContactRules['internal_allocate_auto_accept'] == 1 && $params['allocateto'] == 'Internal'){  
                $purchaseOrderData['statusid'] = 20;
                $purchaseOrderData['responded'] = 'on';
                $purchaseOrderData['accepted'] = 'on';
                $purchaseOrderData['responsedate'] = date('Y-m-d H:i:s', time());
                $purchaseOrderData['acceptdate'] = date('Y-m-d H:i:s', time());
                
                
                $CopyJobRequestDocumentChain->setSuccessor($InsertETPJobChain);
                
                $InsertETPJobChain->setSuccessor($JobNoteChain);

                //Create etp_job data array
                $etpJobData = array(
                    'jobnumber'         => $this->generateETPJobNo($params['supplierid']),
                    'supplierid'        => $params['supplierid'],
                    'customerid'        => $jobData['customerid'],
                    'ownerjobid'        => $jobData['jobid'],
                    'ownerporef'        => 0,//This will update after create PO
                    'custordref'        => $jobData['custordref'],
                    'custordref2'       => $jobData['custordref2'],
                    'custordref3'       => $jobData['custordref3'],
                    'labelid'           => $jobData['labelid'],
                    'siteline1'         => $jobData['siteline1'],
                    'siteline2'         => $jobData['siteline2'],
                    'siteline3'         => $jobData['siteline3'],
                    'sitesuburb'        => $jobData['sitesuburb'],
                    'sitestate'         => $jobData['sitestate'],
                    'sitepostcode'      => $jobData['sitepostcode'],
                    'sitecontact'       => $jobData['sitecontact'],
                    'sitephone'         => $jobData['sitephone'],
                    'siteemail'         => $jobData['siteemail'],
                    'jobdescription'    => $jobData['jobdescription'],
                    'quoterqd'          => intval($jobData['quoterqd']),
                    'leaddate'          => $jobData['leaddate'],
                    'duedate'           => $jobData['duedate'], 
                    'statusid'          => $this->getETPJobStatusID('UNALLOC'),
                );
                
            } 
                
            $emailSubject = 'Works Order / DCFM Job No: '.$jobData['jobid'].' ('.$jobData['sitesuburb'].')';
            $emailBody1 = 'Works Order / DCFM Job No: '.$jobData['jobid'].' ('.$jobData['sitesuburb'].')';
            $emailRuleData = $this->sharedclass->getEmailRuleByRuleName('RAPTOR_NEWJOB_SUPPLIER');
            if(count($emailRuleData) > 0) {
                
                $emailSubject = $emailRuleData['emailsubject'];
                $emailBody1 = $emailRuleData['emailbody'];
                
            }
            $emailData[]  = array(
                'recipient'   => $supplierData['email'], 
                'cc'          => "dcfm@dcfm.com.au", 
                'customerid'  => $jobData['customerid'], 
                'subject'     => $emailSubject,
                'message'     => $emailBody1,
                'docsA'       => $docsA
            );
             
            
            
        }
                
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'jobData'           => $jobData,
            'updateJobData'     => $updateJobData,
            'jobNoteData'       => $jobNoteData,
            'emailData'         => $emailData
        );
        
        if($params['allocateto'] == 'Landlord' || $params['allocateto'] == 'Supplier' || $params['allocateto'] == 'Internal'){
            $request['purchaseOrderData'] = $purchaseOrderData;
            $request['documentData'] = $documentData;
            $request['requiredJobDocuments'] = $requiredJobDocuments;
            if(isset($ContactRules['internal_allocate_auto_accept']) && $ContactRules['internal_allocate_auto_accept'] == 1 && $params['allocateto'] == 'Internal'){  
                $request['etpJobData'] = $etpJobData;
            }
        }
        $UpdateJobChain->handleRequest($request);
                
        //5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function Get jobdata based on array of where conditions
    * @param array $queryParams - parameters for select query
    * @return array 
    */
    public function getJobsDataByParams($queryParams) {
        
        $this->db->select('*');
        $this->db->where($queryParams);
        $this->db->from('jobs');
        $data = $this->db->get()->result_array();
        return $data;
    }
    
    
   
    /**
    * This function Get a TIG job data from etp_job with given parameters
    * @param int $jobid - the name of jobid for getting particular job data
    * @return result array - array(array(id =>1235, firstname =>'name'))
    */
    public function getJobById($jobid, $customerid = '') {
            
                
        if (! isset($jobid) ){
            throw new exception('Jobid value must be supplied.');
        }
        $this->db->select(array("j.*, c.companyname, cg.id AS glcodeid, CONCAT(cg.accountcode,' (',cg.accountname,')') AS glcode, IF(j.quoterqd='on','Yes','No') AS quoted, if(js.portaldesc!='', js.portaldesc, jobstage) as portaldesc, concat(j.siteline1,'<br>',j.siteline2,'<br>',j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as site"));
        $this->db->from('jobs j');
        $this->db->join('customer c', 'j.customerid =c.customerid', 'inner');
        $this->db->join('jobstage js', 'j.jobstage=js.jobstagedesc', 'left');
        $this->db->join('customer_glchart cg', 'j.custglchartid=cg.id', 'left');
        $this->db->where('j.jobid', $jobid);
        if($customerid!=''){
            $this->db->where('j.customerid', $customerid);
        }
        return $this->db->get()->row_array();
    }
   
    /**
    * This function Get Job Id list for autocomplete
    * @param string $search - search keyword from autocomplete field
    * @param integer $supplierid - Optional
    * @param integer $jobid - Optional
    * @return array - 
    */
    public function getJobSearch($search, $customerid = '') {
        
        $this->db->select("jobid");
        $this->db->from('job'); 
        $this->db->like("jobid", $this->db->escape_str($search)); 
        
        if($customerid != '' && $customerid != NULL){
            $this->db->where('customerid', $customerid);
        }
       
        
        $this->db->order_by('jobid');
        $this->db->limit(20);
        $query = $this->db->get();

        return $query->result_array();
       
    }
    
    
     /**
    * This function Get a Lead job data from joblead with given parameters
    * @param int $jobleadid - the name of jobleadid for getting particular joblead data
    * @return array
    */
    public function getLeadJobById($jobleadid) {
                
        if (! isset($jobleadid) ){
            throw new exception('Jobleadid value must be supplied.');
        }
        $this->db->select("j.*");
        $this->db->from('joblead j'); 
        $this->db->where('j.joblead_id', $jobleadid);
                
        return $this->db->get()->row_array();
    }
     /**
     * getParentJob_ByMonthYear
     * @param integer $month
     * @param integer $year
     * @param integer $customerid
     * @param integer $contactid
     * @return integer
     */
    public function getParentJobByMonthYear ($month, $year, $customerid=0, $contactid=0) 
    {
      
        $sql = "SELECT parentjobid FROM con_parentjob_id WHERE monthofyear=$month and year=$year ";
        if($customerid!=0){
            $sql =$sql . " and customerid=$customerid";
        }
        if($contactid!=0){
            $sql =$sql . " and contactid=$contactid";
        }
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            $result=$query->row();
           return $result->parentjobid;
        }
        else
        {
            
            return false;
        }
       
    }
    
    /**
    * This function Get a job status id and name from etp_jobstatus with given parameters
    * @return array
    */
    public function getJobStageForSearch() {
        $this->db->select('portaldesc');
        $this->db->from('jobstage');
        $this->db->where('inportalsearch', 1);
        $this->db->where('portaldesc IS NOT NULL');
        $this->db->order_by('portaldesc','asc');
        $this->db->group_by('portaldesc');
        $query = $this->db->get();

       return $query->result_array();
    }
                
    
    /**
    * This function Get a job status id and name from etp_jobstatus with given parameters
    * @return array
    */
    public function getJobStatus() {
       $this->db->select('jobstatusid, jobstatusdesc');
        $this->db->from('jobstatus');
       $query = $this->db->get();

       return $query->result_array();
    }
     
    /**
    * this function use for get email Recipients
     * 
     * @param string $jobrow
     * @param string $eventcode
     * @return string
     */
    private function getRecipients($jobrow, $eventcode){
      
        $recipients = array();
  	$customerid = $jobrow['customerid'];
        $notify_roledata=$this->getRecords('notify_role',array('customerid'=>$customerid,'event_code'=>$eventcode,'is_active'=>'1'),'customer_emailrule');
                
	foreach($notify_roledata as $key=>$value) { 
                
            $recip_role = $value['notify_role'];
            switch ($recip_role) {
		case "sitefm":
			$recipient = $jobrow['sitefmemail'];
		break;	
		case "site_contact":
                    $addresslabeldetails=$this->customerClass->getAddressById($jobrow['labelid']);
                    $recipient = isset($addresslabeldetails["siteemail"]) ? $addresslabeldetails["siteemail"]:'';
                
  		break;				
            }	
                
	  $recipients[] = $recipient;	
	}
        if(count($recipients) == 0){
            $recipients[] = $jobrow['sitefmemail'];
        }
        
        
        return $recipients;	
    }
  
    /**
    * this function use for get email subject
    * 
    * @param string $jobrow
    * @param string $eventcode
    * @return string
    */        
    private function getEmailSubject($jobrow, $eventcode){
                
                
         $addresslabeldetails=$this->getRecord('siteline1',array('labelid'=>$jobrow['labelid']),'addresslabel');
         $site = isset($addresslabeldetails["siteline1"]) ? $addresslabeldetails["siteline1"]:'';
         $subject = $this->customerClass->getCustomerEmailRuleFieldValue($jobrow['customerid'], $eventcode,"email_subject");
                
         $subject = str_replace("<custref>", $jobrow['custordref'], $subject);
         $subject = str_replace("<site>", $site, $subject);

         return $subject;
    }
    
  
    /**
     * this function use for get email body
     * 
     * @param string $jobrow
     * @param string $eventcode
     * @return string
     */
    private function getEmailBody($jobrow, $eventcode){
                
	$template = $this->customerClass->getCustomerEmailRuleFieldValue($jobrow['customerid'], $eventcode,"email_body");
                
        //extract header
	$header="<font face='Verdana'>";
        $ipos1 = strpos($template,"<header>") + strlen("<header>");
        $ipos2 = strpos($template,"</header>");
        if ($ipos1>0 && $ipos2>0){
            $header .= substr($template, $ipos1, $ipos2-$ipos1);
        }
    	
	//Build Job Details
	$details = "";
	$iposj = strpos($template,"<Job Details>");
	
	if ($iposj>0) {
	  	
	  $site    = $jobrow['siteline2']." ".$jobrow['sitesuburb']." ".$jobrow['sitestate'];
	  $custref = $jobrow['custordref'];
	  $custref2 = $jobrow['custordref2'];
	  $custref3 = $jobrow['custordref3'];
	  $leaddate = $jobrow['leaddate'];
	  $priority = $jobrow['priority'];
	  $duedate = $jobrow['duedate'];
	  $duetime = $jobrow['duetime'];
 
	  $manager = $jobrow['sitefm'];
	  $descrip = $jobrow['jobdescription'];
	   
	  		
	  $details .="<table>";
	  $details .="<tr><td>Site:</td><td></td><td>$site</td></tr>";
	  $details .="<tr><td>Manager:</td><td></td><td>$manager</td></tr>";	
	  $details .="<tr><td>Order Ref:</td><td></td><td>$custref</td></tr>";
	  
	  if ($custref2 != ""){
	    $details .="<tr><td>Order Ref 2:</td><td></td><td>$custref2</td></tr>";
	  }	
	  if ($custref3 != ""){
	    $details .="<tr><td>Order Ref 3:</td><td></td><td>$custref3</td></tr>";
	  }	
	  $details .="<tr><td>Log date:</td><td></td><td>$leaddate</td></tr>";	
	  $details .="<tr><td>Priority:</td><td></td><td>$priority</td></tr>";	
	  $details .="<tr><td>Due Date:</td><td></td><td>$duedate</td></tr>";	
	  $details .="<tr><td>Due Time:</td><td></td><td>$duetime</td></tr>";	
  	  $details .="<tr><td>Job Description:</td><td></td><td>$descrip</td></tr>";	
	  $details .="</table>";
	  	
		
	}
	
        //extract footer and append
	$footer = "";
	$ipos1 = strpos($template,"<footer>") + strlen("<footer>");
	$ipos2 = strpos($template,"</footer>");
	if ($ipos1>0 && $ipos2>0){
	  $footer = nl2br(substr($template, $ipos1, $ipos2-$ipos1));
	  $footer = str_replace("!=!","=", $footer);
	  
	}
	
	$message = $header."</br>";
	
	$message = $message."<p>".$details."</p>";

	$message = $message.$footer;
	
	$stdfooter = "<table><tr><td colspan=2><b>Team DCFM!</b></td></tr>";
	$stdfooter .= "<tr><td>Ph:</td><td>02 9460 7676</td></tr>";
	$stdfooter .= "<tr><td>Fax:</td><td>02 9460 8913</td></tr>";
	$stdfooter .= "<tr><td>Email:</td><td>dcfm@dcfm.com.au</td></tr>";
	$stdfooter .= "<tr><td colspan=2>DCFM Australia Pty Ltd</td></tr>";
	$stdfooter .= "<tr><td colspan=2>ABN 69 122487 076</td></tr></table?";
			 
	$message .= $stdfooter;
                
	return $message;
	
    }
    
    /**
    * This function Get Variation Decline Reasons data
    * @param integer $customerid - 
    * @return array - 
    */
    
    public function getVariationDeclineReasons($customerid = NULL) {
        
        $customerids = array(0);
        if ($customerid != NULL) {
            $customerids = array(0,$customerid); 
        }
        
        $this->db->select('*');
        $this->db->where('isactive', 1);
        $this->db->where_in('customerid', $customerids);                
        $this->db->from('variation_decline_reason');
        $this->db->order_by('sortorder asc');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    
    
     /**
    * This function use for getting sitefm List with jobs counts data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param string $type - for getting data limited  
    * @return array 
    */
    public function getJobCountBySitefm($contactid, $type) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        if($type == 'waitingapproval'){
            $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        }
        
        $this->db->select(array("j.sitefm, a.contactid, COUNT(jobid) AS count"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');   
                
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
         
        $this->db->where('j.leaddate >','2010-07-01'); 
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
     
        if($type == 'waitingapproval'){
                
            $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions');   
            $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
            if (isset($ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"]) && $ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"] == 1){
                $keepdays =isset($ContactRules["declined_quote_keep_days_in_client_portal"]) ? $ContactRules["declined_quote_keep_days_in_client_portal"]:NULL;

                $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions', 'declined');   
                if($keepdays != NULL){
                    $this->db->where('DATEDIFF(CURRENT_DATE,j.leaddate) <', $keepdays); 
                } 
            }
            else{
                //$this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')");    
            }
            $this->db->where_in('j.jobstage', $jobstages);
        }
        elseif ($type == 'waitingdcfmreview') {
            
            $jobStages = array('portal', 'next_allocate', 'info_pending', 'approved_from_portal');        
            $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
            $this->db->where_in('j.jobstage', $jobStages);
            $this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
            $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        }
        elseif ($type == 'inprogress') {
            
            $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
            $this->db->where('js.portaldesc', 'Job In Progress');
            $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
                
        }
        elseif ($type == 'waitingvariationapproval') {
            
            $excludeJobStage = array('cancelled', 'hold', 'declined');
            $this->db->where_not_in('j.jobstage', $excludeJobStage);
            $this->db->where('variationstage','variation_request_sent');  
        }
        elseif ($type == 'completed') {
            
            $this->db->where('js.portaldesc', 'Job Completed');
        }
        elseif ($type == 'onhold') {
            
            $this->db->where('j.jobstage', 'hold');
        }
        elseif ($type == 'inprogressquote') {
            $excludeJobStage = array('client_notified');
            $quotestatus = array('accepted','approved_from_portal');

            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where_in('lcase(j.quotestatus)',$quotestatus);  
            $this->db->where_not_in('j.jobstage', $excludeJobStage);
                
        }
        elseif ($type == 'pendingapprovalquote') {
             $jobStage = array('wait_client_quote_resp', 'wait_client_qte_resp');
                
            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where("lcase(j.quotestatus)", 'pending_approval'); 
            $this->db->where_in('j.jobstage', $jobStage);
              
        }
        elseif ($type == 'waitingdcfmreviewquote') {
            $jobStage = array('next_sendquote', 'waiting_rfq_response');
                
            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where("lcase(j.quotestatus)", 'pending_submission'); 
            $this->db->where_in("jobstage",$jobStage); 
                
              
        }
        elseif ($type == 'reviewwaitingwo') {
            $this->db->where("j.custordref",''); 
            $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
            $this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')"); 
        
        }  
        elseif ($type == 'waitingwoapprovalhistory') {
            $this->db->where("jobConvertedDate IS NOT NULL"); 
            $this->db->where("j.jobstage",'portal'); 
        } 
        elseif ($type == 'waitingwodeclinehistory') { 
            $this->db->where("leadDeclinedDate IS NOT NULL"); 
            $this->db->where("j.jobstage",'declined');
        } 
        $this->db->group_by("j.sitefm"); 
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Jobs Count With sitefm Data Query : '. $this->db->last_query());
                
        return $data;
    }
                
    /**
    * This function use for getting Suburb List with jobs counts data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param string $type - for getting data limited  
    * @return array 
    */
    public function getJobCountBySupplier($contactid, $type) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        if($type == 'waitingapproval'){
            $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        }
        $this->db->select(array("po.supplierid, cus.companyname, COUNT(po.jobid) AS count"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');   
        $this->db->join('purchaseorders po', 'j.jobid = po.jobid', 'left'); //New
        $this->db->join('customer cus', 'po.supplierid = cus.customerid', 'left'); //New
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
         
        $this->db->where('j.leaddate >','2010-07-01'); 
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
     
        if($type == 'waitingapproval'){
                
            $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions');   
            $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
            if (isset($ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"]) && $ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"] == 1){
                $keepdays =isset($ContactRules["declined_quote_keep_days_in_client_portal"]) ? $ContactRules["declined_quote_keep_days_in_client_portal"]:NULL;

                $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions', 'declined');   
                if($keepdays != NULL){
                    $this->db->where('DATEDIFF(CURRENT_DATE,j.leaddate) <', $keepdays); 
                } 
            }
            else{
                //$this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')");    
            }
            $this->db->where_in('j.jobstage', $jobstages);
        }
        elseif ($type == 'waitingdcfmreview') {
            
            $jobStages = array('portal', 'next_allocate', 'info_pending', 'approved_from_portal');        
            $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
            $this->db->where_in('j.jobstage', $jobStages);
            $this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
            $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        }
        elseif ($type == 'inprogress') {
            
            $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
            $this->db->where('js.portaldesc', 'Job In Progress');
            $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
                
        }
        elseif ($type == 'waitingvariationapproval') {
            
            $excludeJobStage = array('cancelled', 'hold', 'declined');
            $this->db->where_not_in('j.jobstage', $excludeJobStage);
            $this->db->where('variationstage','variation_request_sent');  
        }
        elseif ($type == 'completed') {
            
            $this->db->where('js.portaldesc', 'Job Completed');
        }
        elseif ($type == 'onhold') {
            
            $this->db->where('j.jobstage', 'hold');
        }
        
        elseif ($type == 'inprogressquote') {
            
                   
            $excludeJobStage = array('client_notified');
            $quotestatus = array('accepted','approved_from_portal');

            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where_in('lcase(j.quotestatus)',$quotestatus);  
            $this->db->where_not_in('j.jobstage', $excludeJobStage);
                
                
        }
        elseif ($type == 'pendingapprovalquote') {
            $jobStage = array('wait_client_quote_resp', 'wait_client_qte_resp');
                
            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where("lcase(j.quotestatus)", 'pending_approval'); 
            $this->db->where_in('j.jobstage', $jobStage);
                
        }
        elseif ($type == 'waitingdcfmreviewquote') {
            $jobStage = array('next_sendquote', 'waiting_rfq_response');
                
            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where("lcase(j.quotestatus)", 'pending_submission'); 
            $this->db->where_in("jobstage",$jobStage); 
              
        }  
        elseif ($type == 'reviewwaitingwo') {
            $this->db->where("j.custordref",''); 
            $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
            $this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')"); 
        
        }  
        elseif ($type == 'waitingwoapprovalhistory') {
            $this->db->where("jobConvertedDate IS NOT NULL"); 
            $this->db->where("j.jobstage",'portal');  
        } 
        elseif ($type == 'waitingwodeclinehistory') {
            $this->db->where("leadDeclinedDate IS NOT NULL"); 
            $this->db->where("j.jobstage",'declined');
        } 
        
        $this->db->where("po.supplierid !=",'0');
        $this->db->group_by("po.supplierid"); 
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Jobs Count With Suburb Data Query : '. $this->db->last_query());
                
        return $data;
    }
    
    /**
    * This function use for getting Suburb List with jobs counts data for logged contact
    * @param integer $contactid - contactid for logged contact
    * @param string $type - for getting data limited  
    * @return array 
    */
    public function getJobCountBySuburb($contactid, $type) {
            
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
        if($type == 'waitingapproval'){
            $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        }
        $this->db->select(array("j.sitesuburb as suburb, COUNT(jobid) AS count"));
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left');   
                
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
         
        $this->db->where('j.leaddate >','2010-07-01'); 
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
     
        if($type == 'waitingapproval'){
                
            $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions');   
            $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
            if (isset($ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"]) && $ContactRules["show_declined_quotes_in_approval_tab_in_client_portal"] == 1){
                $keepdays =isset($ContactRules["declined_quote_keep_days_in_client_portal"]) ? $ContactRules["declined_quote_keep_days_in_client_portal"]:NULL;

                $jobstages = array('portal_await_approval', 'Waiting_Client_Instructions', 'declined');   
                if($keepdays != NULL){
                    $this->db->where('DATEDIFF(CURRENT_DATE,j.leaddate) <', $keepdays); 
                } 
            }
            else{
                //$this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')");    
            }
            $this->db->where_in('j.jobstage', $jobstages);
        }
        elseif ($type == 'waitingdcfmreview') {
            
            $jobStages = array('portal', 'next_allocate', 'info_pending', 'approved_from_portal');        
            $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
            $this->db->where_in('j.jobstage', $jobStages);
            $this->db->where("IFNULL(variationstage,'') != 'variation_request_sent'"); 
            $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
        }
        elseif ($type == 'inprogress') {
            
            $this->db->where('j.jobstage !=', 'Waiting_Client_Instructions'); 
            $this->db->where('js.portaldesc', 'Job In Progress');
            $this->db->where("IFNULL(j.quotestatus,'') != 'pending_submission'"); 
                
        }
        elseif ($type == 'waitingvariationapproval') {
            
            $excludeJobStage = array('cancelled', 'hold', 'declined');
            $this->db->where_not_in('j.jobstage', $excludeJobStage);
            $this->db->where('variationstage','variation_request_sent');  
        }
        elseif ($type == 'completed') {
            
            $this->db->where('js.portaldesc', 'Job Completed');
        }
        elseif ($type == 'onhold') {
            
            $this->db->where('j.jobstage', 'hold');
        }
        
        elseif ($type == 'inprogressquote') {
            
                   
            $excludeJobStage = array('client_notified');
            $quotestatus = array('accepted','approved_from_portal');

            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where_in('lcase(j.quotestatus)',$quotestatus);  
            $this->db->where_not_in('j.jobstage', $excludeJobStage);
                
                
        }
        elseif ($type == 'pendingapprovalquote') {
            $jobStage = array('wait_client_quote_resp', 'wait_client_qte_resp');
                
            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where("lcase(j.quotestatus)", 'pending_approval'); 
            $this->db->where_in('j.jobstage', $jobStage);
                
        }
        elseif ($type == 'waitingdcfmreviewquote') {
            $jobStage = array('next_sendquote', 'waiting_rfq_response');
                
            $this->db->where("j.quoterqd", 'on'); 
            $this->db->where("lcase(j.quotestatus)", 'pending_submission'); 
            $this->db->where_in("jobstage",$jobStage); 
              
        }  
        elseif ($type == 'reviewwaitingwo') {
            $this->db->where("j.custordref",''); 
            $this->db->where("(j.quoterqd is null or j.quoterqd !='on')");
            $this->db->where("(j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent')"); 
        
        }  
        elseif ($type == 'waitingwoapprovalhistory') {
            $this->db->where("jobConvertedDate IS NOT NULL"); 
            $this->db->where("j.jobstage",'portal');  
        } 
        elseif ($type == 'waitingwodeclinehistory') {
            $this->db->where("leadDeclinedDate IS NOT NULL"); 
            $this->db->where("j.jobstage",'declined');
        } 
        
        
        $this->db->group_by("j.sitesuburb"); 
        $data = $this->db->get()->result_array();
         
        $this->LogClass->log('Get Jobs Count With Suburb Data Query : '. $this->db->last_query());
                
        return $data;
    }
    
    //Dashboard Jobs Work Start
     /**
     * This function Get a job count with given parameters
     * 
     * @param integer $contactid
     * @return array
     */
    public function getJobCountByStage($contactid) {
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        if (isset($ContactRules["show_jobs_on_hold"]) && $ContactRules["show_jobs_on_hold"] == 1){
            $excludeStages = array('cancelled', 'client_notified', 'declined');
        }
        else{
            $excludeStages = array('cancelled', 'hold', 'client_notified', 'declined');
        }
        
        //$hassitelogin = $this->sharedClass->getRuleValue($loggedUserData['customerid'], $loggedUserData['role'],'show_job_approval_tab_in_client_portal');
                
        $this->db->select(array("DISTINCT if(portaldesc!='', portaldesc, jobstage) as stage, COUNT(jobid) AS count"));//"js.name AS stage, js.id as statusid, (select COUNT(*) from etp_job j where j.supplierid =$customerid AND j.isinternal =0 and j.statusid =js.id GROUP BY j.statusid) as count");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left'); 
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
                
        
        $excludeqStages = array('pending_submission','pending_approval');
        
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where_not_in('j.jobstage', $excludeStages); 
        $qwhere = "IFNULL(j.quotestatus,'') not in ('pending_submission','pending_approval')";
        $this->db->where($qwhere); 		
        
        //if($hassitelogin == 1){
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        //}       
        $this->db->group_by("stage"); 
        $data = $this->db->get()->result_array();
        $this->LogClass->log('Get Jobs Count By Stage Data Query : '. $this->db->last_query());
                
        return $data;
         
    }
    
                
     /**
     * This function Get a Quote count with given parameters
     * 
     * @param integer $contactid
     * @return array
     */
    public function getQuoteCountByStage($contactid) {
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
                
//        $this->db->select(array("distinct 0 as fakeg, j.customerid, count(j.jobid) as count, 
//            sum(if((quotestatus = 'pending_approval' AND jobstage IN ('wait_client_quote_resp','wait_client_qte_resp')),1,0))as pending_appro, 
//            sum(if((quoterqd = 'on' AND quotestatus IN ('accepted','approved_from_portal') AND jobstage NOT IN ('client_notified'))=0,1,0))as inprogress, 
//            sum(if((quoterqd = 'on' AND (quotestatus = 'pending_submission') AND jobstage IN ('next_sendquote','waiting_rfq_response')),1,0))as waitingdcfm"));
        
//         $this->db->select(array("COUNT(jobid) AS count, "
//            . "CASE "
//            . " WHEN (quoterqd = 'on' AND quotestatus = 'pending_approval' AND jobstage IN ('wait_client_quote_resp','wait_client_qte_resp')) THEN 'Pending Approval' "
//            . " WHEN (quoterqd = 'on' AND quotestatus IN ('accepted','approved_from_portal') AND jobstage NOT IN ('client_notified')) THEN 'In Progress' "
//            . " WHEN (quoterqd = 'on' AND (quotestatus = 'pending_submission') AND jobstage IN ('portal','next_sendquote','waiting_rfq_response','waiting_jrq_response')) THEN 'Pending Submission' "
//            . "  END AS QStatus, "
//            . "CASE "
//            . " WHEN (quoterqd = 'on' AND quotestatus = 'pending_approval' AND jobstage IN ('wait_client_quote_resp','wait_client_qte_resp')) THEN 1 "
//            . " WHEN (quoterqd = 'on' AND quotestatus IN ('accepted','approved_from_portal') AND jobstage NOT IN ('client_notified')) THEN 2 "
//            . " WHEN (quoterqd = 'on' AND (quotestatus = 'pending_submission') AND jobstage IN ('portal','next_sendquote','waiting_rfq_response','waiting_jrq_response')) THEN 3 "
//            . "  END AS sortorder"));
                
             
        
        $this->db->select(array("COUNT(jobid) AS count, "
            . "CASE "
            . " WHEN (quoterqd = 'on' AND lcase(j.quotestatus) = 'pending_approval' AND jobstage IN ('wait_client_quote_resp','wait_client_qte_resp')) THEN 'Pending Approval' "
            . " WHEN (quoterqd = 'on' AND (lcase(j.quotestatus) = 'pending_submission') AND jobstage IN ('portal','next_sendquote','waiting_rfq_response','waiting_jrq_response','internal_incomplete')) THEN 'Pending Submission' "
            . "  END AS QStatus, "
            . "CASE "
            . " WHEN (quoterqd = 'on' AND lcase(j.quotestatus) = 'pending_approval' AND jobstage IN ('wait_client_quote_resp','wait_client_qte_resp')) THEN 1 "
            . " WHEN (quoterqd = 'on' AND (lcase(j.quotestatus) = 'pending_submission') AND jobstage IN ('portal','next_sendquote','waiting_rfq_response','waiting_jrq_response','internal_incomplete')) THEN 3 "
            . "  END AS sortorder"));
        $this->db->from('jobs j');  
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left');  
        $this->db->where('j.customerid', $loggedUserData['customerid']);
                
        //if($hassitelogin == 1){
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        //}   
        
        $this->db->group_by("QStatus"); 
        $this->db->order_by("sortorder"); 
        $this->db->having('QStatus IS NOT NULL'); 
		
        $data = $this->db->get()->result_array();
              
		//$this->logit('Quote grid');
		//$this->logit($this->db->last_query());			  
        return $data;
         
    }
    
    /**
     * 
     * @param type $jobid
     * @return int
     */
    public function preVarExceedValue($jobid){
                
	$result = array();
	
	#determine earliest ntype variation jobnote
	#then find closest editlog record on notexceed - take old value if < current notexceed.
	$this->db->select("min(pdate) as mpdate");
        $this->db->from('jobnote'); 
        $this->db->where('jobid', $jobid);
        $this->db->where('ntype', 'variation');
        $this->db->group_by('jobid'); 
        $results = $this->db->get()->row_array();
        if(count($results)==0){
            $result['success'] = FALSE;
            $result['preExceed']=0;
            $result['preBuffer']=0;
            return $result;
        }
                
	
	$mpdate = $results['mpdate'];
                
	$this->db->select("oldvalue");
        $this->db->from('editlog'); 
        $this->db->where('recordid', $jobid);
        $this->db->where('fieldname', 'notexceed');
        $this->db->where('pdate >=', $mpdate);
        $this->db->order_by('pdate');   
        $nxresults = $this->db->get()->row_array();
        if(count($nxresults)==0){
            $result['success'] = FALSE;
            $result['preExceed']=0;
            $result['preBuffer']=0;
            return $result;
        }
        $preExceed = $nxresults['oldvalue'];
        
        $this->db->select("oldvalue");
        $this->db->from('editlog'); 
        $this->db->where('recordid', $jobid);
        $this->db->where('fieldname', 'dcfmbufferv');
        $this->db->where('pdate >=', $mpdate);
        $this->db->order_by('pdate');   
        $nxresults = $this->db->get()->row_array();
        if(count($nxresults)==0){
            $result['success'] = FALSE;
            $result['preExceed']=0;
            $result['preBuffer']=0;
            return $result;
        }
        $preBuffer = $nxresults['oldvalue'];      
                
		
	$result['success'] = TRUE;
	$result['preExceed']=$preExceed;
	$result['preBuffer']=$preBuffer;
	return $result;

    }
    
    //Dashboard Jobs Work Start
     /**
     * This function Get a job count with given parameters
     * 
     * @param integer $contactid
     * @param date $fromdate
     * @param date $todate
     * @param integer $manager
     * @param string $state
     * @param integer $labelid
     * @return array
     */
    public function getJobStageCount($contactid, $fromdate = NULL, $todate = NULL, $manager = NULL, $state = NULL, $labelid =NULL ) {
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        //$hassitelogin = $this->sharedClass->getRuleValue($loggedUserData['customerid'], $loggedUserData['role'],'show_job_approval_tab_in_client_portal');
                
        $this->db->select(array("DISTINCT (IF(portaldesc IS NULL,'other',portaldesc)) AS stage, COUNT(jobid) AS count"));//"js.name AS stage, js.id as statusid, (select COUNT(*) from etp_job j where j.supplierid =$customerid AND j.isinternal =0 and j.statusid =js.id GROUP BY j.statusid) as count");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left'); 
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
                
        
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        
        if($fromdate != NULL && $fromdate != ''){
            $this->db->where('j.leaddate >=', $fromdate);
        }
        if($todate != NULL && $todate != ''){
            $this->db->where('j.leaddate <=', $todate);
        }
                
                
        if($manager != NULL && $manager != '' && $manager != 0){
            $this->db->where('a.contactid', $manager);
        }
        
        if($state != NULL && $state != ''){
            $this->db->where('j.sitestate', $state);
        }
        
        if($labelid != NULL && $labelid != '' && $labelid != 0){
            
            $this->db->where('j.labelid', $labelid);
        }
        
        //if($hassitelogin == 1){
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        //}       
        $this->db->group_by("portaldesc"); 
        $data = $this->db->get()->result_array();
        
        return $data;
         
    }
    
    
     /**
     * This function Get a job Completion Chart data with given parameters
     * 
     * @param integer $contactid
     * @param date $fromdate
     * @param date $todate
     * @param integer $manager
     * @param string $state
     * @param integer $labelid
     * @return array
     */
    public function getJobCompletionChartData($contactid, $fromdate, $todate, $manager = NULL, $state = NULL, $labelid =NULL ) {
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        //$hassitelogin = $this->sharedClass->getRuleValue($loggedUserData['customerid'], $loggedUserData['role'],'show_job_approval_tab_in_client_portal');
       
                
        $this->db->select(array("distinct 0 as fakeg, j.customerid, count(j.jobid) as totaljobs, 
            sum(if(datediff(jcompletedate,duedate)<0,1,0))as early, 
            sum(if(datediff(jcompletedate,duedate)=0,1,0))as ontime, 
            sum(if(datediff(jcompletedate,duedate)>0 and datediff(jcompletedate,duedate)<=7,1,0))as L7days,
	sum(if(datediff(jcompletedate,duedate)>7,1,0))as g7days"));
        $this->db->from('jobs j');  
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left'); 
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where('j.leaddate >=', $fromdate);
        $this->db->where('j.leaddate <=', $todate);
                
        if($manager != NULL && $manager != '' && $manager != 0){
            $this->db->where('a.contactid', $manager);
        }
        
        if($state != NULL && $state != ''){
            $this->db->where('j.sitestate', $state);
        }
        
        if($labelid != NULL && $labelid != '' && $labelid != 0){
            
            $this->db->where('j.labelid', $labelid);
        }
        //if($hassitelogin == 1){
            
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        //}
        $this->db->group_by("fakeg"); 
        $data = $this->db->get()->row_array();
        
        return $data;
         
    }
    
     /**
     * This function Get a job Attendance Chart data with given parameters
     * 
     * @param integer $contactid
     * @param date $fromdate
     * @param date $todate
     * @param integer $manager
     * @param string $state
     * @param integer $labelid
     * @return array
     */
    public function getJobAttendanceChartData($contactid, $fromdate, $todate, $manager = NULL, $state = NULL, $labelid =NULL ) {
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        //$hassitelogin = $this->sharedClass->getRuleValue($loggedUserData['customerid'], $loggedUserData['role'],'show_job_approval_tab_in_client_portal');
       
                
        $this->db->select(array("distinct 0 as fakeg, j.customerid, count(j.jobid) as totaljobs, 
            sum(if(datediff(jrespdate,responseduedate)<0,1,0))as early, 
            sum(if(datediff(jrespdate,responseduedate)=0,1,0))as ontime, 
            sum(if(datediff(jrespdate,responseduedate)>0 and datediff(jrespdate,responseduedate)<=7,1,0))as L7days,
	sum(if(datediff(jrespdate,responseduedate)>7,1,0))as g7days"));
        $this->db->from('jobs j');  
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left'); 
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where('j.leaddate >=', $fromdate);
        $this->db->where('j.leaddate <=', $todate);
                
        if($manager != NULL && $manager != '' && $manager != 0){
            $this->db->where('a.contactid', $manager);
        }
        
        if($state != NULL && $state != ''){
            $this->db->where('j.sitestate', $state);
        }
        
        if($labelid != NULL && $labelid != '' && $labelid != 0){
            $this->db->where('j.labelid', $labelid);
        }
        //if($hassitelogin == 1){
            
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        //}
        $this->db->group_by("fakeg"); 
        $data = $this->db->get()->row_array();
        
        return $data;
         
    }
    
    
    /**
     * This function Get a monthly job count with given parameters
     * 
     * @param integer $contactid
     * @param date $fromdate
     * @param date $todate
     * @param integer $manager
     * @param string $state
     * @param integer $labelid
     * @return array
     */
    public function getMonthlyJobCounts($contactid, $fromdate, $todate, $manager = NULL, $state = NULL, $labelid =NULL ) {
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        //$hassitelogin = $this->sharedClass->getRuleValue($loggedUserData['customerid'], $loggedUserData['role'],'show_job_approval_tab_in_client_portal');
                
        $this->db->select(array("MONTH(j.leaddate) as month, MONTHNAME(j.leaddate) as monthname, YEAR(j.leaddate) as year, COUNT(jobid) AS count"));//"js.name AS stage, js.id as statusid, (select COUNT(*) from etp_job j where j.supplierid =$customerid AND j.isinternal =0 and j.statusid =js.id GROUP BY j.statusid) as count");
        $this->db->from('jobs j');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'left'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left'); 
        $this->db->join('contact c', 'a.contactid=c.contactid', 'left'); 
        $this->db->join('contact jc', 'j.contactid=jc.contactid', 'left'); 
                
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where('j.leaddate >=', $fromdate);
        $this->db->where('j.leaddate <=', $todate);
                
        if($manager != NULL && $manager != '' && $manager != 0){
            $this->db->where('a.contactid', $manager);
        }
        
        if($state != NULL && $state != ''){
            $this->db->where('j.sitestate', $state);
        }
        
        if($labelid != NULL && $labelid != '' && $labelid != 0){
            
            $this->db->where('j.labelid', $labelid);
        }
        //if($hassitelogin == 1){
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        //}
        
        $this->db->group_by("month(j.leaddate), year(j.leaddate)"); 
        $this->db->order_by("j.leaddate"); 
        $data = $this->db->get()->result_array();
        
        return $data;
         
    }
    
      /**
    * @desc This function use for getting Job Notes
    * @param integer $customerid - customerid for logged user customer
    * @param integer $jobid - jobid 
    * @param array $noteTypes - Note Type array
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @return array 
    */
    
    public function getJobNotes($customerid, $jobid, $noteTypes, $size, $start, $field, $order) {

         
        $this->db->select("jobnoteid");
        $this->db->from('jobnote');
        $this->db->where('jobid', $jobid);
        //$this->db->where('supplierid', $customerid);
        $this->db->where_in('notetype', $noteTypes);

        $trows = count($this->db->get()->result_array());

        $this->db->select("jobnoteid, notes, notetype, date");
        $this->db->from('jobnote');
        $this->db->where('jobid', $jobid);
        //$this->db->where('supplierid', $customerid);
        $this->db->where_in('notetype', $noteTypes);
        
        if($size != NULL) {
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        
        $data = array(
            'trows'=> $trows,
            'data'=> $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Job Notes Data Query : '. $this->db->last_query());
		//aklog('Get Job Notes Data Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * This function Get a Lead job data from joblead with given parameters
    * @param int $jobleadid - the name of jobleadid for getting particular joblead data
    * @return array
    */
    public function getJobNoteById($jobnoteid) {
                
        if (! isset($jobnoteid) ){
            throw new exception('Jobnoteid value must be supplied.');
        }
        $this->db->select("*");
        $this->db->from('jobnote'); 
        $this->db->where('jobnoteid', $jobnoteid);
                
        return $this->db->get()->row_array();
    }
    
    /**
    * @desc This function use for create new job notes
    * @param array $jobParams - the jobParams is array of job notes detail and contactid(LoggedUser)
    * @return bool -  success or failure
    */
    public function createJobNote($jobNoteParams)
    {
        //1 - load multiple models
       
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');
               
         //2 - initialize instances
        $JobNoteChain = new JobNoteChain();
       
        //3 - get the parts connected
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($jobNoteParams['contactid']);
        
        //Create job notes data array
        $jobNoteData = $jobNoteParams['jobNoteData'];

        $request = array(
            'jobParams'  => $jobNoteParams,
            'userData'   => $loggedUserData,
            'jobNoteData' => $jobNoteData 
            
        );
 
        $JobNoteChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for getting Job Notes
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - field name for order by data
    * @param string $order - its require when you use $field param
    * @param integer $jobid - jobid 
    * @param integer $customerid - customerid for logged user customer
    * @param integer $contactid - contactid for logged user
    * @return array 
    */
    public function getJobTasks($size, $start, $field, $order, $jobid, $customerid, $contactid) {
        
        $sql = 'SELECT * FROM task t INNER JOIN contact c ON c.email = t.allocatedto WHERE jobid=:jobid'
                . ' AND c.contactid=:contactid ORDER BY tdate DESC ';
        $sql = 'SELECT * FROM task t INNER JOIN contact c ON c.email = t.allocatedto WHERE jobid=:jobid'
                . ' AND c.customerid=:customerid ORDER BY tdate DESC ';
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        
        $this->db->select("t.taskid");
		$this->db->distinct();
        $this->db->from('task t');
        $this->db->where('t.jobid', $jobid);
        $this->db->where('t.customerid', $customerid);
          
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.contactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        
        $this->db->join('contact c', 'c.email = t.allocatedto', 'inner'); 
        $this->db->join('jobs j', 't.jobid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
        $trows = count($this->db->get()->result_array());

        $this->db->select("t.*");
		$this->db->distinct();
        $this->db->from('task t');
        $this->db->where('t.jobid', $jobid);
        $this->db->where('t.customerid', $customerid);
          
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.contactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        
        $this->db->join('contact c', 'c.email = t.allocatedto', 'inner'); 
        $this->db->join('jobs j', 't.jobid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
        
        $this->db->limit($size, $start);
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        $data = array(
            'trows'=> $trows,
            'data'=> $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Job Tasks Data Query : '. $this->db->last_query());
		//aklog('Get Job Tasks Data Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * This function Generated Job PDF 
     * 
    * @param string $pdfParams - the $pdfParams is array of job detail and contactid(LoggedUser)
    * @return pdf
    */
    public function createPDF($pdfParams) {
        
        
        //1 - load multiple models
        require_once('chain/GeneratePDFChain.php');
        require_once( __DIR__.'/../document/DocumentClass.php');
      
         //2 - initialize instances
        $GeneratePDFChain = new GeneratePDFChain();
        $documentclass = new DocumentClass();
      
       
        //3 - get the parts connected 
               
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($pdfParams['logged_contactid']);
        $jobData = $this->getJobById($pdfParams['jobid']);
        $documentData = $documentclass->getJobDocumentsByJobId('', '', '', '', $jobData['jobid'], $loggedUserData['contactid']);
        $imagesData = $documentclass->documentImages($jobData['jobid'], $loggedUserData['contactid']);
        
        $noteTypes = array('client', 'completion', 'internal.extend', 'portal');
        $notesData = $this->getJobNotes($loggedUserData['customerid'], $jobData['jobid'], $noteTypes, '', '', '', '');
          
        $request = array(
            'pdfParams'     => $pdfParams,
            'jobData'       => $jobData,
            'documentData'  => $documentData,
            'imagesData'    => $imagesData,
            'notesData'     => $notesData,
            'userData'      => $loggedUserData
        );
 
        $GeneratePDFChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $GeneratePDFChain -> returnValue;

        //6 - return the result object
        return $returnValue;        
    }
    
    /**
    * @desc This function use for create new job task
    * @param array $params - the params is array of job task data detail and contactid(LoggedUser)
    * @return bool -  success or failure
    */
    public function createJobTask($params)
    {
        //1 - load multiple models
        require_once('chain/CreateJobTaskChain.php');
               
         //2 - initialize instances
        $CreateJobTaskChain = new CreateJobTaskChain();
       
        //3 - get the parts connected
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['contactid']);
        
        //Create job notes data array
        $jobTaskData = $params['jobTaskData'];

        $request = array(
            'params'      => $params,
            'userData'    => $loggedUserData,
            'jobTaskData' => $jobTaskData 
        );
 
        $CreateJobTaskChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $CreateJobTaskChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a job task data
    * @param int $taskid - taskid for getting particular job task data
    * @return array
    */
    public function getJobTaskById($taskid) {
                
        if (! isset($taskid) ){
            throw new exception('Job task id value must be supplied.');
        }
        $this->db->select("*");
        $this->db->from('task'); 
        $this->db->where('taskid', $taskid);
                
        return $this->db->get()->row_array();
    }
    
    
    /**
    * @desc This function Generated JOB No by etp_setting
    * @param string $supplierid - the $supplierid is use for gatting job_numberpattern from etp_setting
    * @return string -  JOB no
    */
 
    public function generateETPJobNo($supplierid) { 
       
    
        $jobNo="";
        $numberpattern ="000000";
        $job_numberpattern = $this->sharedClass->etpSettings('job_numberpattern', $supplierid);
        if (!$job_numberpattern) {
            $job_numberpattern = $this->sharedClass->etpSettings('default_job_numberpattern', 0);
        }
        if ($job_numberpattern) {
            $patternarray=  explode('{', $job_numberpattern);
            $jobNo= $patternarray[0];
            $numberpattern ="";
            if (count($patternarray)>1) {
                $numberpattern=  str_replace('}', '',  $patternarray[1]);
            }
        }
        $lenth=  strlen($numberpattern);
        $job_currentcount = $this->sharedClass->etpSettings('job_currentcount', $supplierid);
        $number=(int)$numberpattern + (int)$job_currentcount+1;
        $jobNo .= sprintf("%0".$lenth."d", $number);
        
        return $jobNo;
        
    }
    
     /**
    * @desc This function Get a JobStatus id from job Code
    * @param string $code - the name of code for gatting particular id
    * @return integer - etp_jobstatus->id
    */
    public function getETPJobStatusID($code) { 
        
        $this->db->select("id");
        $this->db->from('etp_jobstatus');
        $this->db->where('code', $code);
        $data = $this->db->get()->row();
 
        if ($data) {
            return $data->id;
        }
        else{
            return 0;
        }
    }
    
    /**
    * @desc This function Get a job status id and name from etp_jobstatus with given parameters
    * @param string $code - the name of code for getting particular id and name
    * @return array - array(id =>3, name =>'status')
    */
    public function getETPJobStatusByName($code = NULL) {
        $this->db->select('id, name');
        $this->db->where(array('isactive'=>1));
        if ($code!= NULL) {
           $this->db->where('LCASE(code)', strtolower($code));
        }
        $this->db->order_by('sortorder asc');
        $this->db->from('etp_jobstatus');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    
    //Tech Map WOrks
    
    /**
    * @desc This function getJobs will return list of jobs array from the jobs table with conditions matching for customerid, contactid, subordinateemails, fromdate, todate
    * @param  $contactId, $status $state, $fromDate, $toDate, $adhoc, $contract used to filter job lists
    * @return array 
    */
    public function getTechMapJobs($contactId, $status = NULL, $state = NULL, $fromDate = NULL, $toDate = NULL, $adhoc = NULL, $contract = NULL){
			
        $loggedUserData = $this->sharedClass->getLoggedUser($contactId);
        
        $this->db->select("j.jobid, j.jobstage, 
                    CASE
                    WHEN LCASE(j.jobstage) = 'client_notified' AND jcompletedate>0 THEN jcompletedate
                    WHEN LCASE(j.jobstage) = 'client_notified' AND jcompletedate=0 AND d.dte IS NOT NULL THEN d.dte
                    WHEN LCASE(j.jobstage) IN ('external_incomplete','waiting_jrq_response') THEN IFNULL(jrespdate,internalduedate)
                    WHEN LCASE(j.jobstage) = 'internal_incomplete' THEN d.dte 
                    WHEN LCASE(j.jobstage) = 'next_allocate' AND d.apptid IS NULL THEN leaddate
                    WHEN  d.apptid IS NOT NULL THEN d.dte
                    END AS attenddate,  
                    jcompletedate AS completiondate, duedate, duetime, d.dte,j.internalduedate,
                    CASE 
                    WHEN LCASE(j.jobstage) = 'cancelled' THEN 'Cancelled' 
                    WHEN LCASE(j.jobstage) = 'client_notified' THEN 'Completed'
                    WHEN LCASE(j.jobstage) NOT IN ('cancelled', 'declined', 'hold', 'client_notified') AND duedate < NOW() THEN 'Overdue' 
                    WHEN LCASE(j.jobstage) IN ('internal_incomplete', 'external_incomplete') THEN 'In Progress' 
                    WHEN LCASE(j.jobstage) = 'next_allocate' AND d.apptid IS NULL THEN 'Unsheduled'
                    WHEN LCASE(j.jobstage) = 'next_allocate' AND d.apptid IS NOT NULL THEN 'Scheduled'  
                    END AS STATUS, 
                    j.siteline2 AS address, j.siteline1, j.sitesuburb AS suburb, j.sitestate AS state, j.sitepostcode AS postcode, a.latitude_decimal, a.longitude_decimal, j.userid, j.custordref, j.jobdescription");
                
        $this->db->from('jobs j');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'inner');
        $this->db->join('diary d', "j.jobid=d.jobid", 'left');
                
        $this->db->where('j.customerid', $loggedUserData['customerid']); 
                
                
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
                
        //get jobs only if  lat and long is not empty
        $this->db->where('a.latitude_decimal is NOT NULL', NULL, FALSE);
        $this->db->where('a.latitude_decimal !=', 0);
                
        if(!empty($status)){
            $this->db->having('STATUS', $status); 
        }
        
        // if state is selected
        if(!empty($state)){
            $this->db->where('j.sitestate', $state);
        }
                
        // if adhoc selected
        if(!empty($adhoc)){
            $where = " IFNULL ((IFNULL(j.recurring,'') = 'on' OR j.contractid>0),0) = '0' ";
            //$this->db->where("IFNULL(j.iscontract,'') ! = 'on'");
            $this->db->where("j.iscontract != 'on' ");
        }
        //if contract option selected
        if(!empty($contract)){
            $where = " IFNULL ((IFNULL(j.recurring,'') = 'on' OR j.contractid>0),0) = '1' ";
            $this->db->where("j.iscontract = 'on'");
        }
       
        // if from date selected
        if(!empty($fromDate)){ 
            $this->db->having('attenddate >=', $fromDate); 
            //$this->db->where('j.leaddate >=', $fromDate);
        }
        // if to date selected
        if(!empty($toDate)){ 
            $this->db->having('attenddate <=', $toDate); 
            //$this->db->where('j.duedate <=', $toDate);
        }
        
        $this->db->group_by('j.jobid');
        
        $query = $this->db->get();
        
        $data =  $query->result_array();
        
        $this->LogClass->log('Get Tech Map Jobs Query : '. $this->db->last_query());
                
        
        return $data;

		
    }
	 
    /**
     * @desc This function getUserLatestDairyEntry gives a user latest dairy entry having inprogress status on or completed on with lat, long not null
     * @param  $userId,$checkCompleted 
     * @return array 
     */

    public function getUserLatestDairyEntry($userId,$checkCompleted = NULL){

        
        
        $this->db->select('dte,start,end,inprogress,completed,d.jobid,lm_te_activity_id,a.latitude_decimal,a.longitude_decimal');
        $this->db->from('diary d');
        $this->db->join('lm_timeentry te', 'te.lm_te_apptid = d.apptid', 'inner');
        $this->db->join('jobs j', 'd.jobid = j.jobid', 'left');
        $this->db->join('addresslabel a', 'j.labelid = a.labelid', 'left');
        $this->db->where('d.userid', $userId);
        $this->db->where('inprogress', 'on');
        if($checkCompleted != NULL){
                $this->db->or_where('completed', 'on');
        }
        // Filter if dairy lat long empty NULL or 0 
        $this->db->where('a.latitude_decimal is NOT NULL', NULL, FALSE);
        $this->db->where('a.latitude_decimal !=', 0);
        $this->db->order_by("dte", "desc"); 
        $this->db->order_by("start", "desc"); 
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();

    }
    /**
     * @desc This function getCurrentTechStatus returns status as (Working, Travelling, Onsite,Offline) of user
     * @param integer $userId - parameter is the id of user for which we need to get status.
     * @return string
     */
    public function getCurrentTechStatus($userId){
                
        
        $this->db->select('dte, start, end, inprogress, lm_te_activity_id, jobid');
        $this->db->from('diary d');
        $this->db->join('lm_timeentry te', 'te.lm_te_apptid = d.apptid', 'inner');
        $this->db->where('userid', $userId);
        $this->db->where('inprogress', 'on');
        $this->db->order_by("dte", "desc"); 
        $this->db->order_by("start", "desc"); 
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row_array();
       
        
        $status = '';
        
        //   $row =  $this->getUserLatestDairyEntry($userId);
        /* Check the return data by function for status with the inprogress field  */
        if(count($row)>0){
            if($row['inprogress'] == 'on' && $row['lm_te_activity_id'] == NULL){
                $status = 'Working';
            }elseif($row['inprogress'] == 'on' && ($row['lm_te_activity_id'] >= 4 && $row['lm_te_activity_id'] <= 29) ){
                $status = 'Travelling';
            }else{
                $jobid = $row['jobid'];
                $this->db->select("isTechOnsite('$userId','$jobid') as status");
                $query = $this->db->get();
                $row =  $query->row_array();
                if($row['status'] == 1){
                    $status = 'Onsite';
                }else{
                    $this->db->select("getOnlineStatus('$userId') as status");
                    $query = $this->db->get();
                    $row =  $query->row_array();
                    $status = $row['status'];
                }
            }
        }
        else{
            $this->db->select("getOnlineStatus('$userId') as status");
            $query = $this->db->get();
            $row =  $query->row_array();
            $status = $row['status'];
        }
        //return tech status
        return $status;

    }
    
    /**
     * This function getUserLatestLogin gives a user latest login detail from table dbversion_users_login_history as row array
     * @param integer $userId - parameter is the id of user for which we will get login detail.
     * @return array row 
     */
    public function getUserLatestLogin($userId){
        
        $this->db->select('accessdate, latitude_decimal, longitude_decimal');
        $this->db->from('dbversion_users_login_history');
        $this->db->where('userid',$userId);
        $this->db->order_by("historyid", "desc"); 
        $this->db->limit(1);
        $query = $this->db->get();
        //return tech latest login data as array
        return $query->row_array();
                
    }
    
    /**
     * @desc This function get User Location
     * @param integer $userId - 
     * @return array row 
     */
    public function getUserLocation($userId){
        
        $this->db->select('latitude_decimal, longitude_decimal');
        $this->db->from('customer c');
        $this->db->join('users u', 'u.email=c.customerid', 'inner');
        $this->db->where('u.userid', $userId);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * @desc This function getEstimatedArrival gives estimated arrival time of tech to latest working site or completed site
     * @param integer $userId 
     * @return datetime 
     */
    public function getEstimatedArrival($userId){
        
        // get the tech latest login location from dbversion_users_login_history table
        $techLocationRow = $this->getUserLatestLogin($userId);
                
        // get user latest dairy login location
        $techJobRow =  $this->getUserLatestDairyEntry($userId,$checkCompleted = 'yes');
                
        /* get travel time between the latest login and latest diary login
           if the diary latest login  has a valid latitude and longitude calculate
           using google distance matrix api
        */
        
        if(!empty($techJobRow)) { // && !empty($techJobRow['latitude_decimal'])
            $date = new DateTime();
            $departureTimeInSeconds  = $date->getTimestamp();
            $departureTimeInSeconds = $departureTimeInSeconds + 60;
            $distanceMatrixApi = $this->config->item('google_maps_distance_matrix_api');
                
            //$distanceMatrixApi = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=';
            // pass latitude, longitude and departure time to google distance matrix api
                
            //$distanceMatrixApi .= $techLocationRow['latitude_decimal'].",".$techLocationRow['longitude_decimal']."&destinations=".
            //$techJobRow['latitude_decimal'].",".$techJobRow['longitude_decimal']."&departure_time=".$departureTimeInSeconds.
            //"&traffic_model=best_guess&key=".$this->config->item('google_api_key');
            
            $distanceMatrixApi .= $techLocationRow['latitude_decimal'].",".$techLocationRow['longitude_decimal']."&destinations=".
            $techJobRow['latitude_decimal'].",".$techJobRow['longitude_decimal']."&departure_time=".$departureTimeInSeconds.
            "&traffic_model=best_guess&key=".$this->config->item('google_api_key');
                
            $json = file_get_contents($distanceMatrixApi);
            $response = json_decode($json, TRUE);
            /* 
             * Check if the google api return valid response, if its not valid,
             * there is issue in the diary lat lon or tech latest login location lat long
            */
            if(isset($response['rows'][0]['elements'][0]['duration']['value'])){
                $travelTimeInSeconds = $response['rows'][0]['elements'][0]['duration']['value'];
                // add the travel time to the current time, which will give site arrival time in seconds
                $date = new DateTime();
                $currentTimeInSeconds  = $date->getTimestamp();
                $timeToArriveSite = $travelTimeInSeconds + $currentTimeInSeconds;
                $date = new DateTime("@$timeToArriveSite");
                return  $date->format('H:i:s');
            }else{
                return NULL;
            }

        }else{
            // return  Null on empty job diary entry
            return NULL;
        }
    }
	
    
    /**
     * @desc This function get User Location
     * @param integer $userId - 
     * @param array $jobids - 
     * @return array row 
     */
    public function getUserDairyJobs($userId, $jobids){
        
        $this->db->select('d.jobid, d.dte, d.start, d.end, j.siteline2, j.sitesuburb, j.sitestate, j.sitepostcode, a.latitude_decimal,a.longitude_decimal');
        $this->db->from('diary d');
        $this->db->join('jobs j', 'd.jobid = j.jobid', 'Inner');
        $this->db->join('addresslabel a', 'j.labelid = a.labelid', 'left');
        $this->db->where('d.userid', $userId);
        $this->db->where_in('d.jobid', $jobids);
        $query = $this->db->get();
        return $query->result_array();
    }
	
    /**
     * This function getJobsTecList gives distinct tech list for selected jobs with the status, estimated arrival time, status and jobs list
     * @param  integer $jobs 
     * @return array 
    */

    public function getJobsTecList($jobs = NULL){
        
        $userIds = array_column($jobs, 'userid');
        $jobIds = array_column($jobs, 'jobid');
        
        $userIds = array_unique($userIds);
        $userIds = array_filter($userIds);
                
        $techs = array();
        foreach($userIds as $userId){
           

            $this->db->select("s.firstname, s.surname, s.mobile, s.*,CONCAT((s.firstname),(' '),(s.surname),(' '),('('),(s.mobile),(')')) AS NAME");
            $this->db->from('customer s');
            $this->db->join('users u', 's.customerid = u.email', 'inner');
            $this->db->where('u.userid', $userId);
            $techNameRow = $this->db->get()->row_array();
            
            $techLocationRow = $this->getUserLatestLogin($userId); 
            $techStatus = $this->getCurrentTechStatus($userId);
            $arrivalTime = $this->getEstimatedArrival($userId);
            if(count($techLocationRow > 0)) {
                $timestamp = strtotime($techLocationRow['accessdate']);
                $cDate = strtotime(date('Y-m-d H:i:s', time()));

                //Getting the value of old date + 24 hours
                $oldDate = $timestamp + 86400; // 86400 seconds in 24 hrs

                if($oldDate > $cDate)
                {

                }
                else
                {
                    $userLocationRow = $this->getUserLocation($userId);
                    if(count($userLocationRow) > 0) {
                        $techLocationRow['latitude_decimal'] = $userLocationRow['latitude_decimal'];
                        $techLocationRow['longitude_decimal'] = $userLocationRow['longitude_decimal'];
                    }
                }
            }
            
            $techJobs = $this->getUserDairyJobs($userId, $jobIds);
//            foreach($jobs as $job){
//                if($job['userid'] == $userId){
//                    $this->db->select('dte,start');
//                    $this->db->from('diary');
//                    $this->db->where('jobid',$job['jobid']);
//                    $query = $this->db->get();
//                    $diaryRow = $query->row_array();
//                    if(empty($diaryRow)){
//                        $techJobs[] = array(
//                            'siteline2'=>$job['address'],
//                            'sitesuburb'=>$job['suburb'],
//                            'jobid'=>$job['jobid'],
//                            'dte'=>'',
//                            'start'=>''
//                        );
//                    }else{
//                        $techJobs[] = array(
//                            'siteline2'=>$job['address'],
//                            'sitesuburb'=>$job['suburb'],
//                            'jobid'=>$job['jobid'],
//                            'dte'=>$diaryRow['dte'],
//                            'start'=>$diaryRow['start']
//                        );
//                    }
//
//                }
//            }
            
            /* Create array and assign tech detail */
            $techs[] = array(
                    'techstatus'        => $techStatus,
                    'arrivaltime'       => $arrivalTime,
                    'userid'            => $userId,
                    'NAME'              => isset($techNameRow['NAME']) ? $techNameRow['NAME'] : $userId,
                    'accessdate'        => isset($techLocationRow['accessdate']) ? $techLocationRow['accessdate'] : '',
                    'latitude_decimal'  => isset($techLocationRow['latitude_decimal']) ? $techLocationRow['latitude_decimal'] : '',
                    'longitude_decimal' => isset($techLocationRow['longitude_decimal']) ? $techLocationRow['longitude_decimal'] : '',
                    'jobs'              => $techJobs

            );

        }
        return $techs;

    }
		 
}


/* End of file JobClass.php */