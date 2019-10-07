<?php 
/**
 * Invoice Libraries Class
 *
 * This is a Invoice class for Invoice Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../job/JobClass.php');
require_once( __DIR__.'/../customer/CustomerClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
 

/**
 * Invoice Libraries Class
 *
 * This is a Invoice class for Invoice Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Invoice
 * @filesource          InvoiceClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InvoiceClass extends MY_Model{

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
    * Job class 
    * 
    * @var class
    */
    private $jobClass;
   
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
        $this->LogClass= new LogClass('jobtracker', 'InvoiceClass');
        $this->sharedClass = new SharedClass();
        $this->jobClass = new JobClass();
        $this->customerClass = new CustomerClass();
    }
    
    /**
    * This function use for get Finalised Invoices
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getFinalisedInvoices($contactid, $size, $start, $field, $order, $filter, $params) {
 
      
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
      
        $this->db->select('i.invoiceno');
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner');
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 0);
        $this->db->where('isreversed', 0);
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('i.invoiceno');
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("i.invoiceno, i.glCode, i.invoicedate, j.custordref, j.custordref2, j.custordref3,"
            . " SUM(l.netval) AS netval, SUM(l.taxval) AS taxval, SUM(l.grossval) AS Invoiced, IFNULL((SUM(l.grossval)-SUM(r.rvalue)),0) AS balance,"
            . " i.approvaldate, i.esentdate, j.sitefm, j.sitestate, a.siteref, j.jobid, j.siteline2, j.sitesuburb, j.sitepostcode , j.jobdescription"));
         $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner');
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised',0);
        $this->db->where('isreversed', 0);
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $this->db->group_by('i.invoiceno');
        
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

        $this->LogClass->log('Get Finalised Invoices Data Query : '. $this->db->last_query());
		//aklog('Get Finalised Invoices Data Query : '. $this->db->last_query());
        return $data;
    }
     
    /**
    * This function use for get FM Approval Invoices
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getFMApprovalInvoices($contactid, $size, $start, $field, $order, $filter, $params) {
 
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
       
        
        $this->db->select('i.invoiceno');
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 1);
        $this->db->where('isreversed', 0);
        $this->db->where("i.approvaldate IS NULL");
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('i.invoiceno');
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("i.invoiceno, i.glCode, i.invoicedate, j.custordref, j.custordref2, j.custordref3, SUM(l.netval) AS netval,"
            . " SUM(l.grossval) AS Invoiced, i.approvaldate, i.approvedby, i.esentdate, j.sitefm, j.sitesuburb, a.siteref, j.jobid"));
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 1);
        $this->db->where('isreversed', 0);
        $this->db->where("i.approvaldate IS NULL");
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        $this->db->group_by('i.invoiceno');
        
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

        $this->LogClass->log('Get FM Approval Invoices Data Query : '. $this->db->last_query());

        return $data;
    }
    
    
     /**
    * This function use for get Final Approval Invoices
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getFinalApprovalInvoices($contactid, $size, $start, $field, $order, $filter, $params) {
 
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
       
        
        $this->db->select('i.invoiceno');
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 1);
        $this->db->where('isreversed', 0);
        if (isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1") {
          
            $this->db->where("i.finalapprovaldate IS NULL AND i.approvaldate IS NOT NULL");
        } else {
            $this->db->where("i.approvaldate IS NULL");
        }
        
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('i.invoiceno');
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("i.invoiceno, i.glCode, i.invoicedate, j.custordref, j.custordref2, j.custordref3, SUM(l.netval) AS netval,"
            . " SUM(l.grossval) AS Invoiced, i.approvaldate, i.approvedby, i.esentdate, j.sitefm, j.sitesuburb, a.siteref, j.jobid"));
       $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 1);
        $this->db->where('isreversed', 0);
        if (isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1") {
          
            $this->db->where("i.finalapprovaldate IS NULL AND i.approvaldate IS NOT NULL");
        } else {
            $this->db->where("i.approvaldate IS NULL");
        }
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        $this->db->group_by('i.invoiceno');
        
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

        $this->LogClass->log('Get Final Approval Invoices Data Query : '. $this->db->last_query());

        return $data;
    }
    
    
     /**
    * This function use for get Open Invoices
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getOpenInvoices($contactid, $size, $start, $field, $order, $filter, $params) {
 
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
       
 
        $this->db->select('i.invoiceno');
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 1);
        $this->db->where('isreversed', 0);
        if (isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1") {
          
            $this->db->where("i.approvaldate IS NOT NULL and i.finalapprovaldate IS NOT NULL");
        } else {
            $this->db->where("i.approvaldate IS NOT NULL");
        }
        
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('i.invoiceno');
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("i.invoiceno, i.glCode, i.invoicedate, j.custordref, SUM(l.netval) AS netval, SUM(l.grossval) AS Invoiced,"
            . " IFNULL((SUM(l.grossval)-SUM(r.rvalue)),0) AS balance, i.approvaldate, i.esentdate, j.sitefm, j.sitestate, a.siteref, j.jobid"));
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isfinalised', 1);
        $this->db->where('isreversed', 0);
        if (isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1") {
          
            $this->db->where("i.approvaldate IS NOT NULL and i.finalapprovaldate IS NOT NULL");
        } else {
            $this->db->where("i.approvaldate IS NOT NULL");
        }
        
        $this->db->where("(i.completed != 'on' or i.completed is NULL)");
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        $this->db->group_by('i.invoiceno');
        
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

        $this->LogClass->log('Get Open Invoices Data Query : '. $this->db->last_query());

        return $data;
    }
    
    
    /**
    * This function use for get History Invoices
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getHistoryInvoices($contactid, $size, $start, $field, $order, $filter, $params) {
 
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        
        $this->db->select('i.invoiceno');
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('completed', 'on');
        $this->db->where('isreversed', 0);
       
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('i.invoiceno');
        
        $trows = count($this->db->get()->result_array());
 
        $this->db->select(array("i.invoiceno, i.glCode, i.invoicedate, MAX(r.rdate) AS paymentdate, DATEDIFF(MAX(r.rdate),i.invoicedate) AS days,"
                    . " j.custordref, j.custordref2, j.custordref3, SUM(l.netval) AS Net, SUM(l.taxval) AS GST, SUM(l.grossval) AS Invoiced,"
                    . " i.approvaldate, i.approvedby, i.esentdate, j.sitefm, j.sitestate, a.siteref, j.jobid"));
       $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner'); 
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('completed', 'on');
        $this->db->where('isreversed', 0);
       
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
       
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        if ($filter != '') {
            $this->db->where("(i.invoiceno LIKE '%".$this->db->escape_str($filter)."%' or i.glCode LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.sitefm LIKE '%".$this->db->escape_str($filter)."%' or j.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or j.custordref LIKE '%".$this->db->escape_str($filter)."%' or j.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or j.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        $this->db->group_by('i.invoiceno');
        
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

        $this->LogClass->log('Get History Invoices Data Query : '. $this->db->last_query());

        return $data;
    }
    
     /**
    * This function use for get History Invoices
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return array
     */
    public function getBatchHistory($contactid, $size, $start, $field, $order, $filter, $params) {
 
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
         
        $this->db->select('id');
        $this->db->from('invoice_batch');
 
        $this->db->where('customerid', $loggedUserData['customerid']);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
            $this->db->where("(custbatchid LIKE '%".$this->db->escape_str($filter)."%' or batchdate LIKE '%".$this->db->escape_str($filter)."%' or createdby LIKE '%".$this->db->escape_str($filter)."%' or recipients LIKE '%".$this->db->escape_str($filter)."%' or totalvalue LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
       
        $trows = count($this->db->get()->result_array());
 
        $this->db->select('id, custbatchid, batchdate, createdby, recipients, esentdate, invoicecount, totalvalue');
        $this->db->from('invoice_batch');
 
        $this->db->where('customerid', $loggedUserData['customerid']);
       
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        if ($filter != '') {
            $this->db->where("(custbatchid LIKE '%".$this->db->escape_str($filter)."%' or batchdate LIKE '%".$this->db->escape_str($filter)."%' or createdby LIKE '%".$this->db->escape_str($filter)."%' or recipients LIKE '%".$this->db->escape_str($filter)."%' or totalvalue LIKE '%".$this->db->escape_str($filter)."%')");
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

        $this->LogClass->log('Get Batch History Data Query : '. $this->db->last_query());

        return $data;
    }
    
    /**
    * This function use for update Invoice
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function updateInvoice($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateInvoiceChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        
         //2 - initialize instances
        $UpdateInvoiceChain = new UpdateInvoiceChain();
        $EditLogChain = new EditLogChain();
       
        //3 - get the parts connected
        $UpdateInvoiceChain->setSuccessor($EditLogChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $invoiceData = $this->getInvoiceById($params['invoiceno'], $loggedUserData['customerid']);
        $updateInvoiceData = array ();
        $updateData = $params['updateData'];
       
        $editLogData=array();
        foreach ($updateData as $key => $value) {
            if (trim($invoiceData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'invoice' , 
                    'recordid'  => $params['invoiceno'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $invoiceData[$key], 
                    'newvalue'  => $value
                );
            }
        }
        
        $updateData['invoiceno'] = $params['invoiceno'];
        $updateInvoiceData[] =  $updateData;
        
        $request = array(
            'params'     => $params,
            'userData'          => $loggedUserData, 
            'updateInvoiceData' => $updateInvoiceData,
            'editLogData'       => $editLogData
        );
 
        $UpdateInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Invoice Approval
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function invoiceApproval($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateInvoiceChain.php'); 
        require_once( __DIR__.'/../shared/chain/InsertCashNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateInvoiceChain = new UpdateInvoiceChain(); 
        $InsertCashNoteChain = new InsertCashNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateInvoiceChain->setSuccessor($InsertCashNoteChain);
        $InsertCashNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        
        $invoicenos = $params['invoicenos'];
        $expectval = $params['expectval'];
        $estPayDate = $params['estPayDate']; 
        $updateInvoiceData = array();
        $emailData = array();
        
        if(!is_array($invoicenos)){
            $invoicenos = array($invoicenos);
        }
        
        $updateData = array();
        
        if (isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1") {
            $updateData = array(
                'finalapprovaldate' => date('Y-m-d H:i:s'), 
                'finalapprovedby'   => $loggedUserData['email'],
                'estPayDate'        => $estPayDate
            );
        } else {
            $updateData = array(
                'approvaldate'  => date('Y-m-d H:i:s'), 
                'approvedby'    => $loggedUserData['email'],
                'estPayDate'    => $estPayDate
            );
        }
 

        foreach ($invoicenos as $key => $value) {
            $updateData['invoiceno'] = $value;
            
            if(isset($params['auto_send_approved_invoice']) && $params['auto_send_approved_invoice']){
                //$invoiceData = $this->getInvoiceById($value, $loggedUserData['customerid']);
                $recipients = $this->customerClass->getAccountsEmail($loggedUserData['customerid'], 'invoice');
                $docA = array();
                $doc = array();
                $doc['fname'] = 'invoice_' . $value . '.pdf';
                $doc['dname'] = 'invoice_' . $value . '.pdf';
                $doc['relpath'] = $this->config->item('invoicedir');
                $docA[] = $doc;
                
                $emailData[]  = array(
                    'recipient'   => $recipients, 
                    'cc'          => "accounts@dcfm.com.au,".$loggedUserData['email'], 
                    'customerid'  => $loggedUserData['customerid'], 
                    'subject'     => "Invoice $value from DCFM",
                    'message'     => "Please find invoice $value attached from DCFM, Approved by ".$loggedUserData['email'],
                    'docsA'       => $docA,
                    'replyto'     => "dcfm@dcfm.com.au",
                    'sender'      => "dcfm@dcfm.com.au"
                );
                
                $updateData['esent'] = 'on';
                $updateData['esentdate'] = date("Y-m-d H:i:s");
            }
            
            $updateInvoiceData[] = $updateData;

        }
        

        $invx = implode(", ", $invoicenos);
        $notes = "invoices $invx were set with expected paydate of $estPayDate by user via client portal";

        $casNoteData = array(
            'customerid'    => $loggedUserData['customerid'],
            'expectval'     => $expectval,
            'notes'         => $notes,
            'date'          => date('Y-m-d H:i:s'),
            'userid'        => "portal - " . $loggedUserData['email']
        );

        
       
        $emailData[]  = array(
              'recipient'   => 'accounts@dcfm.com.au', 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $loggedUserData['customerid'], 
              'subject'     => "Portal invoice approvals from " . $loggedUserData['email'],
              'message'     => $notes,

        );
        
        $request = array(
            'params'     => $params,
            'userData'          => $loggedUserData, 
            'updateInvoiceData' => $updateInvoiceData,
            'casNoteData'       => $casNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Invoice Approval
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function invoiceFMApproval($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateInvoiceChain.php'); 
        require_once( __DIR__.'/../shared/chain/InsertCashNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateInvoiceChain = new UpdateInvoiceChain(); 
        $InsertCashNoteChain = new InsertCashNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateInvoiceChain->setSuccessor($InsertCashNoteChain);
        $InsertCashNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $invoicenos = $params['invoicenos'];
        $expectval = $params['expectval']; 
        $updateInvoiceData = array();
        $emailData = array();  
        if(!is_array($invoicenos)){
            $invoicenos = array($invoicenos);
        }
        $updateData = array(
            'approvaldate'  => date('Y-m-d H:i:s'), 
            'approvedby'    => $loggedUserData['email'] 
        );
       
        foreach ($invoicenos as $key => $value) {
            $updateData['invoiceno'] = $value;
            
            if(isset($params['auto_send_approved_invoice']) && $params['auto_send_approved_invoice']){
                //$invoiceData = $this->getInvoiceById($value, $loggedUserData['customerid']);
                $recipients = $this->customerClass->getAccountsEmail($loggedUserData['customerid'], 'invoice');
                $docA = array();
                $doc = array();
                $doc['fname'] = 'invoice_' . $value . '.pdf';
                $doc['dname'] = 'invoice_' . $value . '.pdf';
                $doc['relpath'] = $this->config->item('invoicedir');
                $docA[] = $doc;
                
                $emailData[]  = array(
                    'recipient'   => $recipients, 
                    'cc'          => "accounts@dcfm.com.au,".$loggedUserData['email'], 
                    'customerid'  => $loggedUserData['customerid'], 
                    'subject'     => "Invoice $value from DCFM",
                    'message'     => "Please find invoice $value attached from DCFM, Approved by ".$loggedUserData['email'],
                    'docsA'       => $docA,
                    'replyto'     => "dcfm@dcfm.com.au",
                    'sender'      => "dcfm@dcfm.com.au"
                );
                
                $updateData['esent'] = 'on';
                $updateData['esentdate'] = date("Y-m-d H:i:s");
            }
            
            $updateInvoiceData[] = $updateData;

        }
        

        $invx = implode(", ", $invoicenos); 
        $notes = "invoices $invx were approved by user via client portal";
        $casNoteData = array(
            'customerid'    => $loggedUserData['customerid'],
            'expectval'     => $expectval,
            'notes'         => $notes,
            'date'          => date('Y-m-d H:i:s'),
            'userid'        => "portal - " . $loggedUserData['email']
        );

        
        
        $emailData[]  = array(
              'recipient'   => 'accounts@dcfm.com.au', 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $loggedUserData['customerid'], 
              'subject'     => "Portal invoice approvals from " . $loggedUserData['email'],
              'message'     => $notes,

        );
        
        $request = array(
            'params'     => $params,
            'userData'          => $loggedUserData, 
            'updateInvoiceData' => $updateInvoiceData,
            'casNoteData'       => $casNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for Invoice Finalised
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function invoiceFinalisedApprove($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateInvoiceChain.php'); 
        require_once( __DIR__.'/../shared/chain/InsertCashNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateInvoiceChain = new UpdateInvoiceChain(); 
        $InsertCashNoteChain = new InsertCashNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected
        $UpdateInvoiceChain->setSuccessor($InsertCashNoteChain);
        $InsertCashNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $invoicenos = $params['invoicenos'];
        $expectval = $params['expectval']; 
        $updateInvoiceData = array();
        $emailData = array();  
        if(!is_array($invoicenos)){
            $invoicenos = array($invoicenos);
        }
        $updateData = array(
            'isFinalised'   => '1',
            'approvaldate'  => date('Y-m-d H:i:s'), 
            'approvedby'    => $loggedUserData['email'] 
        );
       
        foreach ($invoicenos as $key => $value) {
            $updateData['invoiceno'] = $value;
            
            if(isset($params['auto_send_approved_invoice']) && $params['auto_send_approved_invoice']){
                //$invoiceData = $this->getInvoiceById($value, $loggedUserData['customerid']);
                $recipients = $this->customerClass->getAccountsEmail($loggedUserData['customerid'], 'invoice');
                $docA = array();
                $doc = array();
                $doc['fname'] = 'invoice_' . $value . '.pdf';
                $doc['dname'] = 'invoice_' . $value . '.pdf';
                $doc['relpath'] = $this->config->item('invoicedir');
                $docA[] = $doc;
                
                $emailData[]  = array(
                    'recipient'   => $recipients, 
                    'cc'          => "accounts@dcfm.com.au,".$loggedUserData['email'], 
                    'customerid'  => $loggedUserData['customerid'], 
                    'subject'     => "Invoice $value from DCFM",
                    'message'     => "Please find invoice $value attached from DCFM, Approved by ".$loggedUserData['email'],
                    'docsA'       => $docA,
                    'replyto'     => "dcfm@dcfm.com.au",
                    'sender'      => "dcfm@dcfm.com.au"
                );
                
                $updateData['esent'] = 'on';
                $updateData['esentdate'] = date("Y-m-d H:i:s");
            }
            
            $updateInvoiceData[] = $updateData;

        }
        

        $invx = implode(", ", $invoicenos); 
        $notes = "invoices $invx were approved by user via client portal";
        $casNoteData = array(
            'customerid'    => $loggedUserData['customerid'],
            'expectval'     => $expectval,
            'notes'         => $notes,
            'date'          => date('Y-m-d H:i:s'),
            'userid'        => "portal - " . $loggedUserData['email']
        );

        
        
        $emailData[]  = array(
              'recipient'   => 'accounts@dcfm.com.au', 
              'cc'          => "dcfm@dcfm.com.au", 
              'customerid'  => $loggedUserData['customerid'], 
              'subject'     => "Portal invoice approvals from " . $loggedUserData['email'],
              'message'     => $notes,

        );
        
        $request = array(
            'params'     => $params,
            'userData'          => $loggedUserData, 
            'updateInvoiceData' => $updateInvoiceData,
            'casNoteData'       => $casNoteData,
            'emailData'         => $emailData
        );
 
        $UpdateInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Invoice Finalised
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function invoiceFinalised($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateInvoiceChain.php');  
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances
        $UpdateInvoiceChain = new UpdateInvoiceChain();  
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected 
        $UpdateInvoiceChain->setSuccessor($EmailChain);
         
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $invoicenos = $params['invoicenos']; 
        $updateInvoiceData = array();
        $emailData = array();  
         
        if(!is_array($invoicenos)){
            $invoicenos = array($invoicenos);
        }
        
        $updateData = array('isFinalised' => '1');
       
        foreach ($invoicenos as $key => $value) {
            $updateData['invoiceno'] = $value;
           
            if(isset($params['auto_send_finalised_invoice']) && $params['auto_send_finalised_invoice']){
                //$invoiceData = $this->getInvoiceById($value, $loggedUserData['customerid']);
                $recipients = $this->customerClass->getAccountsEmail($loggedUserData['customerid'], 'invoice');
                $docA = array();
                $doc = array();
                $doc['fname'] = 'invoice_' . $value . '.pdf';
                $doc['dname'] = 'invoice_' . $value . '.pdf';
                $doc['relpath'] = $this->config->item('invoicedir');
                $docA[] = $doc;
                
                $emailData[]  = array(
                    'recipient'   => $recipients, 
                    'cc'          => "accounts@dcfm.com.au,".$loggedUserData['email'], 
                    'customerid'  => $loggedUserData['customerid'], 
                    'subject'     => "Invoice $value from DCFM",
                    'message'     => "Please find invoice $value attached from DCFM, finalised by ".$loggedUserData['email'],
                    'docsA'       => $docA,
                    'replyto'     => "dcfm@dcfm.com.au",
                    'sender'      => "dcfm@dcfm.com.au"
                );
                
                $updateData['esent'] = 'on';
                $updateData['esentdate'] = date("Y-m-d H:i:s");
            }
            $updateInvoiceData[] = $updateData;
        }
         
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData, 
            'updateInvoiceData' => $updateInvoiceData,
            'emailData'         => $emailData
        );
 
        $UpdateInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateInvoiceChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Send Invoice Query
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function invoiceQuery($params)
    {
        //1 - load multiple models
        
        require_once( __DIR__.'/../shared/chain/InsertCashNoteChain.php'); 
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        
         //2 - initialize instances  
        $InsertCashNoteChain = new InsertCashNoteChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected 
        $InsertCashNoteChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $recipients = $params['recipients'];
        $subject = $params['subject'];
        $message = $params['message']; 
       
        $casNoteData = array(
            'customerid'    => $loggedUserData['customerid'],
            'contactid'     => $loggedUserData['contactid'],
            'notes'         => $subject,
            'emailnotes'    => $message,
            'date'          => date('Y-m-d H:i:s'),
            'userid'        => "portal - " . $loggedUserData['email']
        );

        
        $emailData = array();
        $emailData[]  = array(
            'recipient'   => $recipients, 
            'cc'          => "dcfm@dcfm.com.au", 
            'customerid'  => $loggedUserData['customerid'], 
            'subject'     => $subject,
            'message'     => $message,
            'replyto'     => "dcfm@dcfm.com.au",
            'sender'      => "dcfm@dcfm.com.au"

        );
        
        $request = array(
            'params'     => $params,
            'userData'          => $loggedUserData,  
            'casNoteData'       => $casNoteData,
            'emailData'         => $emailData
        );
 
        $InsertCashNoteChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
      /**
    * This function use for create Batch Invoices
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function createBatchInvoice($params)
    {
        //1 - load multiple models
        require_once('chain/InsertBatchInvoiceChain.php');
        require_once('chain/UpdateInvoiceChain.php');
        require_once( __DIR__.'/../customer/chain/UpdateCustomerChain.php'); 
        
         //2 - initialize instances
        $InsertBatchInvoiceChain = new InsertBatchInvoiceChain();  
        $UpdateInvoiceChain = new UpdateInvoiceChain();  
        $UpdateCustomerChain = new UpdateCustomerChain();  
        
        //3 - get the parts connected 
        $InsertBatchInvoiceChain->setSuccessor($UpdateInvoiceChain);
        $UpdateInvoiceChain->setSuccessor($UpdateCustomerChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        
        $invoicenos = $params['invoicenos']; 
        $updateInvoiceData = array();
        $batchLineData = array();  
         
        $customerData = $this->customerClass->getCustomerById($loggedUserData['customerid']);
        $batch_invoice_cc = $this->sharedClass->getSettingValue('batch_invoice_cc');

        $invoicetotal = 0.00;

        $selectedInvoiceData = $this->getInvoices($invoicenos, $loggedUserData['customerid']);

        foreach ($selectedInvoiceData as $key => $value) {
            $invoicetotal = $invoicetotal+ $value['Invoiced'];

        }

        $custbatchid = (int)$customerData['invbatchid']+1;

        $batchData = array(
            'custbatchid'   => $custbatchid,
            'batchdate'     => date('Y-m-d H:i:s', time()),
            'recipients'    => isset($ContactRules["batchinvoice_recipients"]) ? trim($ContactRules["batchinvoice_recipients"]) : '',
            'ccs'           => $batch_invoice_cc,
            'customerid '   => $loggedUserData['customerid'],
            'createdby '    => $loggedUserData['email'],
            'invoicecount'  => count($invoicenos),
            'totalvalue'    => $invoicetotal
             
        );
        
        foreach ($invoicenos as $key => $value) {
           
            $updateInvoiceData[] = array( 
                    'invoiceno' => $value
            );
            $batchLineData[] =  array( 
                    'invoiceno' => $value
            );
        }
        
        
        $params['customerid'] =  $loggedUserData['customerid'];

        $updateCustomerData = array(
            'invbatchid'   => $custbatchid
        );
        
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'batchData'         => $batchData,
            'batchLineData'     => $batchLineData,
            'updateInvoiceData' => $updateInvoiceData, 
            'updateCustomerData'=> $updateCustomerData,
        );
 
        $InsertBatchInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateCustomerChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Invoice Finalised
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function updateBatchInvoice($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateBatchInvoiceChain.php');  
        
         //2 - initialize instances
        $UpdateBatchInvoiceChain = new UpdateBatchInvoiceChain();  
        
        //3 - get the parts connected 
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        
        $updateBatchData = $params['updateBatchData'];
         
        $request = array(
            'params'     => $params,
            'userData'          => $loggedUserData, 
            'updateBatchData'   => $updateBatchData 
        );
 
        $UpdateBatchInvoiceChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateBatchInvoiceChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Send Invoice Query
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function batchInvoiceEmail($params)
    {
        //1 - load multiple models
        require_once('chain/GenerateBatchPDFChain.php');
        require_once( __DIR__.'/../shared/chain/EmailChain.php');
        

        //2 - initialize instances
        $GeneratePDFChain = new GenerateBatchPDFChain();
        $EmailChain = new EmailChain();
        
        //3 - get the parts connected 
        $GeneratePDFChain->setSuccessor($EmailChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $batchData = $this->getBatchById($params['batchid'], $loggedUserData['customerid']); 
        $batchInvoiceData = $this->getBatchInvoice($params['batchid']);
        $recipients = $params['recipients'];
        $subject = 'batch invoice';
        $message = 'batch invoice';
        
        
        $doc = array();
        $doc['fname'] = 'batch_invoice_' . $params['batchid'] . '.pdf';
        $doc['dname'] = 'batch_invoice_' . $params['batchid'] . '.pdf';
        $doc['relpath'] = $this->config->item('invoicedir');
        $docA[] = $doc;
        $doc['fname'] = "batch_invoice_".$params['batchid'].".xls";
        $doc['dname'] = "batch_invoice_".$params['batchid'].".xls";
        $doc['relpath'] = $this->config->item('invoicedir');
        $docA[] = $doc;
        
        $emailData = array();
        $emailData[]  = array(
            'recipient'   => $recipients, 
            'cc'          => "dcfm@dcfm.com.au", 
            'customerid'  => $loggedUserData['customerid'], 
            'subject'     => $subject,
            'message'     => $message,
            'docsA'       => $docA,
            'replyto'     => "dcfm@dcfm.com.au" 

        );
        
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData,  
            'batchData'         => $batchData, 
            'batchInvoiceData'  => $batchInvoiceData,
            'emailData'         => $emailData
        );
 
        $GeneratePDFChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a Invoice Detail By Invoice No
       * 
    * @param integer $invoiceno - the invoiceno for selected invoice
    * @param integer $customerid - the customerid for logged customer
    * @return array
    */
    public function getInvoiceById($invoiceno, $customerid) { 
        
        $this->db->select("i.*, j.jobid, j.custordref, j.custordref2, j.parentid, j.custordref3, SUM(l.netval) AS netval, SUM(l.taxval) AS taxval, SUM(l.grossval) AS invoiced, c.firstname, c.surname, j.sitefm, j.sitestate, a.siteref, j.siteline1, j.siteline2, j.sitesuburb, j.sitepostcode, j.jobdescription");
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', 'l.invoiceno=i.invoiceno', 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner');
        $this->db->join('contact c', 'i.contactid=c.contactid', 'left');
        if($customerid!=0 && $customerid != NULL){
            $this->db->where('j.customerid', $customerid);
        }
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where_in('i.invoiceno', $invoiceno);
        $this->db->group_by('i.invoiceno');
        $invoiceData = $this->db->get()->row_array();
    
         
        $this->LogClass->log('Get Invoice Detail Query : '. $this->db->last_query());
        return $invoiceData;
    }
  
    /**
    * This function Get a Invoice Detail By Job Id
       * 
    * @param integer $jobid - the jobid for selected invoice
    * @return array
    */
    public function getInvoiceByJobId($jobid) { 
        
        $this->db->select("i.*, c.companyname, c.mobile");
        $this->db->from('invoice i'); 
        $this->db->join('customer c', 'i.customerid =c.customerid', 'inner');
        $this->db->join('jobs j', 'i.jobid =j.jobid', 'left'); 
 
        $this->db->where('i.jobid', $jobid);
        
     
        $data = $this->db->get()->row_array();
         
        $this->LogClass->log('Get Invoice Detail Query : '. $this->db->last_query());
        return $data;
    }
      
    /**
    * @desc This function Get a invoice Items Line with given parameters
    * @param integer $invoiceno - the $invoiceno is idantity of particular invoice line
    * @return array
    */
    public function getInvoiceItemLines($invoiceno) { 
          
        $this->db->select(array("j.jcompletedate ,j.jobid ,j.invoicedescription ,j.primarytrade AS works,j.custordref,IF(IFNULL(a.siteref,'')='',a.siteline2,a.siteref) AS site,j.sitestate ,i2.netval AS amount"));
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.parentid=i.jobid', 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner');
        $this->db->join('invoice i2', 'i2.jobid=j.jobid', 'inner');
        $this->db->where('i.invoiceno', $invoiceno);
      
        $invoicelineData = $this->db->get()->result_array();
          
        $this->LogClass->log('Get Invoice Item Line Query : '. $this->db->last_query());
        return $invoicelineData;
    }
    
    
    /**
    * @desc This function Get a invoice Line with given parameters
    * @param integer $invoiceno - the $invoiceno is idantity of particular invoice line
    * @return array
    */
    public function getInvoiceLines($invoiceno) { 
          
        $this->db->select('invoiceno, linec, chargetype, chargedesc, price, prodid, qty, taxval, netval, grossval, jobid');
        $this->db->from('invoicelines');
        $this->db->where('invoiceno', $invoiceno);
      
        $invoicelineData = $this->db->get()->result_array();
         
        
        $this->LogClass->log('Get Invoice Line Query : '. $this->db->last_query());
        return $invoicelineData;
    }
      
     /**
     * get Invoice Options
     * @param string $state
     * @param string $role
     * @return array
     */
    public function getInvoiceOptions($state)
    {
        $options = array();
 
        $this->db->select("option_value, io.name");
        $this->db->from('invoice_option_value iov');
        $this->db->join('invoice_option io', 'io.id=iov.invoice_option_id', 'INNER');
        $this->db->where('state', $state);
        

        $result = $this->db->get()->result_array();

        foreach ($result as $row){
                $options[$row['name']] = $row['option_value'];
        }
        return $options;
    }
    
    
     /**
    * @desc This function Get a invoices
    * @param array $invoicenos 
    * @param integer $customerid 
    * @return array
    */
    public function getInvoices($invoicenos, $customerid) { 
          
        $this->db->select("j.jobid ,i.invoiceno,i.invoicedate,j.custordref,j.custordref2,j.parentid,j.custordref3,i.netval,SUM(l.grossval ) AS Invoiced, i.esentdate");
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner');
        $this->db->join('invoicelines l', 'l.invoiceno=i.invoiceno', 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner');
        $this->db->where('j.customerid', $customerid);
        $this->db->where('invdoctype', 'INVOICE');
        $this->db->where('isreversed', '0');
        $this->db->where('isfinalised', '1');
        $this->db->where_in('i.invoiceno', $invoicenos);
        $this->db->group_by('i.invoiceno');
      
        $invoices = $this->db->get()->result_array();
         
        
        $this->LogClass->log('Get Invoices Query : '. $this->db->last_query());
        return $invoices;
    }
     
    public function getBudgetSpendData($customerid, $type, $fromdate, $todate, $approvals) {
        
        $approved = array();
        $unapproved = array();
        $quoted = array();
        $openwo = array();
        $budget = array();
        
        if($type == 'glcode') {
            
            //Approved and Unapproved
            if($approvals['singleLevelApporval'] == true) {
                $sql = "SELECT SUM(netval), glCode FROM invoice WHERE customerid=$customerid AND invoicedate >= '".$fromdate."' AND invoicedate <= '".$fromdate."' AND  approvaldate IS NULL GROUP BY glcode";
                $query = $this->db->query($sql);
                $unapproved = $query->result_array();
                
                $sql = "SELECT SUM(netval), glCode FROM invoice WHERE customerid=$customerid AND invoicedate >= '".$fromdate."' AND invoicedate <= '".$fromdate."' AND  approvaldate IS NOT NULL GROUP BY glcode";
                $query = $this->db->query($sql);
                $approved = $query->result_array();
            }    

            if($approvals['twoLevelApporval'] == true) {
                $sql = "SELECT SUM(netval), glCode FROM invoice WHERE customerid=$customerid AND invoicedate >= '".$fromdate."' AND invoicedate <= '".$fromdate."' AND  (approvaldate IS NULL && finalapprovaldate IS NULL) GROUP BY glcode";
                $query = $this->db->query($sql);
                $unapproved = $query->result_array();
                
                $sql = "SELECT SUM(netval), glCode FROM invoice WHERE customerid=$customerid AND invoicedate >= '".$fromdate."' AND invoicedate <= '".$fromdate."' AND  (approvaldate IS NOT NULL && finalapprovaldate IS NOT NULL) GROUP BY glcode";
                $query = $this->db->query($sql);
                $approved = $query->result_array();
            }
            
            //quoted
            $sql = "SELECT SUM(j.estimatedsell) AS netval, 'TOTAL ONLY' AS glcode FROM jobs j WHERE j.customerid=$customerid AND actualsales=0 AND quoterqd='on' AND jobstage NOT IN ('cancelled', 'declined') AND j.quotestatus = 'accepted'";
            $query = $this->db->query($sql);
            $quoted = $query->result_array();
            
            //open WO
            $sql = "SELECT SUM(IF(j.estimatedsell>0, j.estimatedsell, j.notexceed)) AS netval, 'TOTAL ONLY' AS glcode FROM jobs j INNER JOIN addresslabel a ON a.labelid=j.labelid WHERE j.customerid=$customerid AND actualsales=0 AND IFNULL(quoterqd,'') !='on' AND jobstage NOT IN ('cancelled', 'declined') AND IFNULL(nonchargeable, '') != 'on'";
            $query = $this->db->query($sql);
            $openwo = $query->result_array();
            
            //budget
            $FromYearMonth = date('Ym', strtotime($fromdate));
            $ToYearMonth = date('Ym', strtotime($todate));
            $sql = "SELECT SUM(b.amount) AS netval, gl.accountname AS glcode FROM budget b INNER JOIN budget_setting bs ON bs.id=b.budget_settingid LEFT JOIN customer_glchart gl ON b.glcodeid=gl.id WHERE bs.customerid= $customerid AND (((b.yearno*100)+b.monthno) BETWEEN $FromYearMonth and $ToYearMonth)";
            $query = $this->db->query($sql);
            $budget = $query->result_array();
            
     
        } else {
            
            if($approvals['singleLevelApporval'] == true) {
                $sql = "SELECT SUM(i.netval), a.siteref FROM invoice i INNER JOIN jobs j ON i.jobid=j.jobid INNER JOIN addresslabel a ON j.labelid=a.labelid WHERE i.customerid=$customerid AND i.invoicedate >= '".$fromdate."' AND i.invoicedate <= '".$fromdate."' AND i.approvaldate IS NULL GROUP BY j.labelid";
                $query = $this->db->query($sql);
                $unapproved = $query->result_array();
                
                $sql = "SELECT SUM(i.netval), a.siteref FROM invoice i INNER JOIN jobs j ON i.jobid=j.jobid INNER JOIN addresslabel a ON j.labelid=a.labelid WHERE i.customerid=$customerid AND i.invoicedate >= '".$fromdate."' AND i.invoicedate <= '".$fromdate."' AND i.approvaldate IS NOT NULL GROUP BY j.labelid";
                $query = $this->db->query($sql);
                $approved = $query->result_array();
            }    

            if($approvals['twoLevelApporval'] == true) {
                $sql = "SELECT SUM(i.netval), a.siteref FROM invoice i INNER JOIN jobs j ON i.jobid=j.jobid INNER JOIN addresslabel a ON j.labelid=a.labelid WHERE i.customerid=$customerid AND i.invoicedate >= '".$fromdate."' AND i.invoicedate <= '".$fromdate."' AND (i.approvaldate IS NULL && i.finalapprovaldate IS NULL) GROUP BY j.labelid";
                $query = $this->db->query($sql);
                $unapproved = $query->result_array();
                
                $sql = "SELECT SUM(i.netval), a.siteref FROM invoice i INNER JOIN jobs j ON i.jobid=j.jobid INNER JOIN addresslabel a ON j.labelid=a.labelid WHERE i.customerid=$customerid AND i.invoicedate >= '".$fromdate."' AND i.invoicedate <= '".$fromdate."' AND (i.approvaldate IS NOT NULL && i.finalapprovaldate IS NOT NULL) GROUP BY j.labelid";
                $query = $this->db->query($sql);
                $approved = $query->result_array();
            }
            
            //quoted
            $sql = "SELECT SUM(j.estimatedsell) AS netval, siteref FROM jobs j INNER JOIN addresslabel a ON a.labelid=j.labelid WHERE j.customerid=$customerid AND actualsales=0 AND quoterqd='on' AND jobstage NOT IN ('cancelled', 'declined') AND j.quotestatus = 'accepted' GROUP BY a.siteref";
            $query = $this->db->query($sql);
            $quoted = $query->result_array();
            
            //open WO
            $sql = "SELECT SUM(IF(j.estimatedsell>0, j.estimatedsell, j.notexceed)) AS netval, siteref FROM jobs j INNER JOIN addresslabel a ON a.labelid=j.labelid WHERE j.customerid=$customerid AND actualsales=0 AND IFNULL(quoterqd, '') !='on' AND jobstage NOT IN ('cancelled', 'declined') AND IFNULL(nonchargeable, '') != 'on' GROUP BY a.siteref";
            $query = $this->db->query($sql);
            $openwo = $query->result_array();
            
            //budget
            $FromYearMonth = date('Ym', strtotime($fromdate));
            $ToYearMonth = date('Ym', strtotime($todate));
            $sql = "SELECT SUM(b.amount) AS netval, a.siteref FROM budget b INNER JOIN budget_setting bs ON bs.id=b.budget_settingid LEFT JOIN addresslabel a  ON b.recordid=a.labelid WHERE  bs.customerid= $customerid AND (((b.yearno*100)+b.monthno) BETWEEN $FromYearMonth and $ToYearMonth)";
            $query = $this->db->query($sql);
            $budget = $query->result_array();
        }

        $data = array(
            'openwo'     => $openwo,
            'quoted'     => $quoted,
            'unapproved' => $unapproved,
            'approved'   => $approved,
            'budget'     => $budget
        );
        
        return $data;
    }
     
    
    /**
    * This function Get a Invoice Detail By Job Id
       * 
    * @param integer $batchid - the $batchid for selected batch
    * @param integer $customerid - the customerid for logged customer
    * @return array
    */
    public function getBatchById($batchid, $customerid) { 
        
        $this->db->select("*");
        $this->db->from('invoice_batch'); 
        $this->db->where('id', $batchid);
        $this->db->where('customerid', $customerid);
         
        $data = $this->db->get()->row_array();
         
        $this->LogClass->log('Get Invoice Batch Query : '. $this->db->last_query());
        return $data;
    }
      
    /**
    * @desc This function Get a batch invoices Line with given parameters
    * @param integer $batchid - the $batchid is idantity of particular batch invoice
    * @return array
    */
    public function getBatchInvoice($batchid) { 
          
        $this->db->select(array("bl.invoiceno, i.invoicedate, j.custordref, j.custordref2, j.custordref3, i.jobid,"
                . " SUM(l.grossval) AS amount, j.sitefm, i.approvedby, i.approvaldate, j.sitesuburb,"
                . " j.sitestate, a.siteref, i.costcentre, i.glcode"));
        $this->db->from('invoice_batchline bl');
        $this->db->join('invoice_batch b', 'b.id=bl.batchid', 'inner');
        $this->db->join('invoice i', 'bl.invoiceno=i.invoiceno', 'inner');
        $this->db->join('invoicelines l', 'i.invoiceno=l.invoiceno', 'INNER');
        $this->db->join('jobs j', 'i.jobid=j.jobid', 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->where('b.id', $batchid);
        $this->db->group_by('bl.invoiceno');
      
        $invoicelineData = $this->db->get()->result_array();
          
        $this->LogClass->log('Get Batch Invoice Query : '. $this->db->last_query());
        return $invoicelineData;
    }
     
    
    
    /**
    * This function Generated Invoice PDF 
     * 
    * @param string $params - the $params is array of invoice detail and contactid(LoggedUser)
    * @return pdf
    */
    public function createInvoicePDF($params) {
        
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
      
        //1 - load multiple models
        if (isset($ContactRules["batch_invoicing"]) && $ContactRules["batch_invoicing"] == "1") {
             require_once('chain/GenerateBatchInvoicePDFChain.php'); 
           
            //2 - initialize instances
            $GeneratePDFChain = new GenerateBatchInvoicePDFChain();
        }
        else{
            require_once('chain/GenerateStandardInvoicePDFChain.php');
            
            //2 - initialize instances
            $GeneratePDFChain = new GenerateStandardInvoicePDFChain();
        }
        
        //3 - get the parts connected 
         
        //4 - start the process
 
        $invoiceData = $this->getInvoiceById($params['invoiceno'], $loggedUserData['customerid']); 
       
        if (isset($ContactRules["batch_invoicing"]) && $ContactRules["batch_invoicing"] == "1") {
            $invoiceLineData = $this->getInvoiceItemLines($params['invoiceno']); 
        }
        else{
            $invoiceLineData = $this->getInvoiceLines($params['invoiceno']);
        }
        
        if(count($invoiceData)>0){
            $invoiceOptions = $this->getInvoiceOptions($invoiceData['sitestate']);
        }
        $request = array(
            'params'             => $params,
            'invoiceData'           => $invoiceData, 
            'invoiceLineData'       => $invoiceLineData,
            'userData'              => $loggedUserData,
            'invoiceOptions'        => $invoiceOptions
            
        );
 
        $GeneratePDFChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $GeneratePDFChain -> returnValue;

        //6 - return the result object
        return $returnValue;        
    }
    
     
    /**
    * This function Generated Invoice PDF 
     * 
    * @param string $params - the $params is array of invoice detail and contactid(LoggedUser)
    * @return pdf
    */
    public function createBatchPDF($params) {
         
        //1 - load multiple models
        require_once('chain/GenerateBatchPDFChain.php');

        //2 - initialize instances
        $GeneratePDFChain = new GenerateBatchPDFChain();
        
        //3 - get the parts connected 
         
        //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $batchData = $this->getBatchById($params['batchid'], $loggedUserData['customerid']); 
        $batchInvoiceData = $this->getBatchInvoice($params['batchid']);
         
        $request = array(
            'params'             => $params,
            'batchData'             => $batchData, 
            'batchInvoiceData'      => $batchInvoiceData,
            'userData'              => $loggedUserData
            
        );
 
        $GeneratePDFChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $GeneratePDFChain -> returnValue;

        //6 - return the result object
        return $returnValue;        
    }
    
    
    
     /**
     * This function Get a Invoice count with given parameters
     * 
     * @param integer $contactid
     * @return array
     */
    public function getInvoiceCountByStage($contactid) {
         
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $ContactRules = $this->sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
       
        $sql = 'CASE ';
        if ((isset($ContactRules["show_invoicefinalize_tab_in_clientportal"]) && $ContactRules["show_invoicefinalize_tab_in_clientportal"] == "1")){ 
            $sql = $sql . " WHEN ((isfinalised = 0) AND (i.completed != 'on' or i.completed is NULL)) THEN 'To Be Finalised' ";
        }  
        if ((isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1")){   
            $sql = $sql . " WHEN ((isfinalised = 1) AND (i.approvaldate IS NULL) AND (i.completed != 'on' or i.completed is NULL)) THEN 'For FM Approval' "
            . " WHEN ((isfinalised = 1) AND (i.finalapprovaldate IS NULL AND i.approvaldate IS NOT NULL) AND (i.completed != 'on' or i.completed is NULL)) THEN 'For Final Approval' "
            . " WHEN ((isfinalised = 1) AND (i.finalapprovaldate IS NOT NULL AND i.approvaldate IS NOT NULL) AND (i.completed != 'on' or i.completed is NULL)) THEN 'Open Invoices' ";
        }   else { 
             $sql = $sql . " WHEN ((isfinalised = 1) AND i.approvaldate IS NULL AND (i.completed != 'on' or i.completed is NULL)) THEN 'For Approval' "
              . " WHEN ((isfinalised = 1) AND i.approvaldate IS NOT NULL AND (i.completed != 'on' or i.completed is NULL)) THEN 'Open Invoices' ";
        } 
        if ((isset($ContactRules["show_invoice_history_tab_in_clientportal"]) && $ContactRules["show_invoice_history_tab_in_clientportal"] == "1")){   
            $sql = $sql . " WHEN (i.completed = 'on') THEN 'Invoice History' ";
        }
       $sql = $sql . " END ";
        
       
       $sql1 = 'CASE ';
        if ((isset($ContactRules["show_invoicefinalize_tab_in_clientportal"]) && $ContactRules["show_invoicefinalize_tab_in_clientportal"] == "1")){ 
            $sql1 = $sql1 . " WHEN ((isfinalised = 0) AND (i.completed != 'on' or i.completed is NULL)) THEN 1 ";
        }  
        if ((isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1")){   
            $sql1 = $sql1 . " WHEN ((isfinalised = 1) AND (i.approvaldate IS NULL) AND (i.completed != 'on' or i.completed is NULL)) THEN 2 "
            . " WHEN ((isfinalised = 1) AND (i.finalapprovaldate IS NULL AND i.approvaldate IS NOT NULL) AND (i.completed != 'on' or i.completed is NULL)) THEN 3 "
            . " WHEN ((isfinalised = 1) AND (i.finalapprovaldate IS NOT NULL AND i.approvaldate IS NOT NULL) AND (i.completed != 'on' or i.completed is NULL)) THEN 4 ";
        }   else { 
             $sql1 = $sql1 . " WHEN ((isfinalised = 1) AND i.approvaldate IS NULL AND (i.completed != 'on' or i.completed is NULL)) THEN 2 "
              . " WHEN ((isfinalised = 1) AND i.approvaldate IS NOT NULL AND (i.completed != 'on' or i.completed is NULL)) THEN 4 ";
        } 
        if ((isset($ContactRules["show_invoice_history_tab_in_clientportal"]) && $ContactRules["show_invoice_history_tab_in_clientportal"] == "1")){   
            $sql1 = $sql1 . " WHEN (i.completed = 'on') THEN 5 ";
        }
       $sql1 = $sql1 . " END ";
        
        $this->db->select(array("COUNT(i.invoiceno) AS count, $sql AS invstatus, $sql1 AS sortorder"));
        $this->db->from('invoice i');
        $this->db->join('jobs j', 'j.jobid=i.jobid', 'inner'); 
        $this->db->join('invoicelines l', "l.invoiceno=i.invoiceno", 'inner');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'inner');
        $this->db->join('invreceipts r', "i.invoiceno=r.invoiceno", 'left');
        $this->db->where('i.customerid', $loggedUserData['customerid']);
        $this->db->where('invdoctype', 'INVOICE');
         $this->db->where('isreversed', 0);
        if($loggedUserData['role'] != 'master')
        {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $this->db->where('j.sitefmemail', $loggedUserData['email']); 
            }
        }
        
        $this->db->group_by("invstatus"); 
        $this->db->order_by("sortorder"); 
        $this->db->having('invstatus IS NOT NULL'); 
        $data = $this->db->get()->result_array();
       
	    //$this->logit("Invoice Grid");
	    //$this->logit($this->db->last_query());
        return $data;
         
    }
	
	   	/*function logit($string){
		
	  //return false;
	  $file = 'C:/temp/raptortest.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
 	  fclose($fh);
	}*/
}


/* End of file InvoiceClass.php */