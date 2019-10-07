<?php 
/**
 * Contractor Libraries Class
 *
 * This is a Contractor class for Contractor Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  

/**
 * Contractor Libraries Class
 *
 * This is a Contractor class for Contractor Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Contractor
 * @filesource          ContractorClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 class ContractorClass extends MY_Model
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
        $this->LogClass= new LogClass('jobtracker', 'ContractorClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
  
    
     /**
    * This function use for getting Customers List
    * @param integer $customerid - Logged User customerid
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getContracts($customerid, $size, $start, $field, $order, $filter, $params) {

         
        
        $this->db->select("c.id");
                            
        $this->db->from('con_contract c');
        $this->db->join('con_contract_type t', 't.id=c.contracttypeid', 'INNER');
        //$this->db->join('con_address ca', 'ca.contractid=c.id', 'Left'); 
        $this->db->where('c.customerid', $customerid);
        //$this->db->where('t.status', 1);
         
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
            $this->db->where("(c.name LIKE '%".$this->db->escape_str($filter)."%' or t.name LIKE '%".$this->db->escape_str($filter)."%' or c.contractref LIKE '%".$this->db->escape_str($filter)."%')");
        }
       
        $trows = $this->db->count_all_results();
                            
                            
        $this->db->select(array("c.id, c.name, t.name as typename, c.contractref, c.startdate, c.enddate, COUNT(ca.id) AS sitecount, c.status"));
        $this->db->from('con_contract c');
        $this->db->join('con_contract_type t', 't.id=c.contracttypeid', 'INNER');
        $this->db->join('con_address ca', 'ca.contractid=c.id', 'Left'); 
        $this->db->where('c.customerid', $customerid);
        //$this->db->where('t.status', 1);
       
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
            $this->db->where("(c.name LIKE '%".$this->db->escape_str($filter)."%' or t.name LIKE '%".$this->db->escape_str($filter)."%' or c.contractref LIKE '%".$this->db->escape_str($filter)."%')");
        }
         
        $this->db->group_by('c.id');
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        if($size != NULL) {
            $this->db->limit($size, $start);
        }
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Contracts Data Query : '. $this->db->last_query());
        
        return $data;
    }
    
    /**
    * This function use for Insert new Supplier
    * @param array $params - the $params is array of supplierdata and contactid(LoggedUser)
    * @return array
    */
       
    public function insertContract($params)
    {
        //1 - load multiple models
        require_once('chain/InsertContractChain.php');
        
        //2 - initialize instances
        $InsertContractChain = new InsertContractChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Contract  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertContractData = $params['insertContractData'];
        
                            
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData, 
            'insertContractData'    => $insertContractData 
        );
 
        $InsertContractChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertContractChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for create Batch Invoices
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function updateContract($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContractChain.php'); 
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
         //2 - initialize instances
        $UpdateContractChain = new UpdateContractChain(); 
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected 
       $UpdateContractChain->setSuccessor($EditLogChain); 
                            
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $contractData = $this->getContractById($params['contractid']);
        
        $updateContractData = $params['updateContractData']; 
        
        $editLogData=array();
        foreach ($updateContractData as $key => $value) {
            if (trim($contractData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'con_contract' , 
                    'recordid'  => $params['contractid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $contractData[$key], 
                    'newvalue'  => $value
                );
            }
        }
         
        $request = array(
            'params'                => $params,
            'userData'              => $loggedUserData,
            'updateContractData'    => $updateContractData, 
            'editLogData'           => $editLogData
        );
                            
        $UpdateContractChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;          
        
                            

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete Supplier
    * @param array $params - the $params is array of Supplier and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteContract($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteContractChain.php');
        
        //2 - initialize instances
        $DeleteContractChain = new DeleteContractChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Contract : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteContractChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteContractChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for getting Site Addresses
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getContractSites($customerid, $contractid, $size, $start, $field, $order, $filter = '', $params = array()) {
        
        $this->db->select("ca.id");
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner');
        //$this->db->join('contact cfm', 'a.contactid = cfm.contactid', 'left');
        //$this->db->join('contact csite', 'a.contactid = csite.contactid', 'left');
        //$this->db->where('a.customerid', $customerid);
        $this->db->where('ca.contractid', $contractid);
       
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(a.siteref LIKE '%".$this->db->escape_str($filter)."%' or a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitepostcode LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
            
        $this->db->select(array("ca.id, ca.contractid, ca.isactive, a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, a.latitude_decimal, a.longitude_decimal, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as site, CONCAT(IFNULL(a.siteline1,''),'<br>', IFNULL(a.siteline2,''),'<br>',IFNULL(a.sitesuburb,''),' ',IFNULL(a.sitestate,''),' ',IFNULL(a.sitepostcode,'')) as address"));
        //$this->db->select('ca.*, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, a.latitude_decimal, a.longitude_decimal');
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner');
        //$this->db->join('contact cfm', 'a.contactid = cfm.contactid', 'left');
        //$this->db->join('contact csite', 'a.contactid = csite.contactid', 'left');
        //$this->db->where('a.customerid', $customerid);
        $this->db->where('ca.contractid', $contractid);
        
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(a.siteref LIKE '%".$this->db->escape_str($filter)."%' or a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitepostcode LIKE '%".$this->db->escape_str($filter)."%')");
        }
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Contract Sites Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
     /**
    * This function use for Insert Contract Site
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertContractSite($params) {
        
        //1 - load multiple models
        require_once('chain/InsertContractSiteChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $InsertContractSiteChain = new InsertContractSiteChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $InsertContractSiteChain->setSuccessor($ContractAuditLogChain); 
          
        //4 - start the process
        $this->LogClass->log('Insert Contract Site  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertConSiteData'];
        
        $conAuditLogData = array();
        
        $conAuditLogData[] = array(
            'contractid' => $insertData['contractid'], 
            'dateadded'  => date('Y-m-d H:i:s'), 
            'contactid'  => $loggedUserData['contactid'], 
            'tablename'  => 'con_address' , 
            'fieldname'  => 'labelid', 
            'oldvalue'   => 'Added', 
            'newvalue'   => $insertData['labelid']
        );
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertConSiteData'     => $insertData,
            'conAuditLogData'       => $conAuditLogData 
        );
        
        if(isset($params['addressGroupData'])){
            $request['addressGroupData'] = $params['addressGroupData'];
        }
        
        $InsertContractSiteChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Contract Site
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateContractSite($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContractSiteChain.php'); 
         require_once('chain/ContractAuditLogChain.php');
        //2 - initialize instances
        $UpdateContractSiteChain = new UpdateContractSiteChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        //3 - get the parts connected 
        $UpdateContractSiteChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Update Contract Site : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $oldContractSiteData = $this->getContractSiteById($params['siteid']);
        $siteAddressGroupids = $this->getSiteAddressGroupids($loggedUserData['customerid'], $oldContractSiteData['labelid']);
                            
        $updateSiteData = $params['updateConSiteData'];
             
        $conAuditLogData=array();
        foreach ($updateSiteData as $key => $value) {
            if (trim($oldContractSiteData[$key]) != trim($value)) {

                $conAuditLogData[] = array(
                    'contractid' => $params['contractid'], 
                    'dateadded'  => date('Y-m-d H:i:s'), 
                    'contactid'  => $loggedUserData['contactid'], 
                    'tablename'  => 'con_address' , 
                    'fieldname'  => $key, 
                    'oldvalue'   => $oldContractSiteData[$key], 
                    'newvalue'   => $value
                );
            }
        }
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'updateConSiteData'     => $updateSiteData,
            'oldContractSiteData'   => $oldContractSiteData,
            'siteAddressGroupids'   => $siteAddressGroupids,
            'conAuditLogData'       => $conAuditLogData
        );
        if(isset($params['addressGroupData'])){
            $request['addressGroupData'] = $params['addressGroupData'];
        }
        $UpdateContractSiteChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
        
        
    }
    
    /**
    * This function use for Delete Contract Site
    * @param array $params - the $params is array of Address and contactid(LoggedUser)
    * @return array
    */
    public function deleteContractSite($params) {
        
        //1 - load multiple models
        require_once('chain/DeleteContractSiteChain.php');
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $DeleteContractSiteChain = new DeleteContractSiteChain();
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected
        $DeleteContractSiteChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Delete Contract site : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
                            
        $oldContractSiteData = $this->getContractSiteById($params['siteid']);
        $siteAddressGroupids = $this->getSiteAddressGroupids($loggedUserData['customerid'], $oldContractSiteData['labelid']);
        
        $conAuditLogData=array();
        $conAuditLogData[] = array(
            'contractid' => $params['contractid'], 
            'dateadded'  => date('Y-m-d H:i:s'), 
            'contactid'  => $loggedUserData['contactid'], 
            'tablename'  => 'con_address' , 
            'fieldname'  => 'labelid', 
            'oldvalue'   => $oldContractSiteData['labelid'], 
            'newvalue'   => 'Delete'
        );
       
        $request = array(
            'params'                => $params,
            'userData'              => $loggedUserData,  
            'oldContractSiteData'   => $oldContractSiteData,
            'siteAddressGroupids'   => $siteAddressGroupids,
            'conAuditLogData'       => $conAuditLogData
        );
        
        $DeleteContractSiteChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue;
        
    }
    
    
    
     /**
    * This function Check Site is already allocate to contract or not
    * @param integer $contractid 
    * @param integer $labelid
    * @param integer $siteid
    * @return array 
    */
    public function checkContractSite($contractid, $labelid, $siteid = 0) {
        
        $this->db->select('*');
        $this->db->from('con_address');
        $this->db->where('contractid', $contractid);
        $this->db->where('labelid', $labelid); 
	if($siteid != 0 && $siteid != NULL && $siteid != ''){
            $this->db->where('id !=', $siteid); 
        }
	$data = $this->db->get()->row_array();
        if(count($data)>0){
            return true;
        }   
        
         return false;
    }
    
    
    /**
    * This function use for getting contract Schedule
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getContractSchedule($customerid, $contractid, $size, $start, $field, $order, $fromdate, $todate, $filter = '', $params = array()) {
        
        $this->db->select("csd.id");
         $this->db->from('con_schedule_def csd');
        $this->db->join('con_contract_season s', 'csd.seasonid=s.id', 'left');
        $this->db->join('se_subworks sw', 'csd.subworksid=sw.id', 'left'); 
        //$this->db->where('a.customerid', $customerid);
        //$this->db->where('s.status', 1);
        //$this->db->where('sw.enabled', 1);
        $this->db->where('contractid', $contractid);
       
        foreach ($params as $fn=> $fv) {
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
        if($fromdate != '') {
            $this->db->where('DATE(csd.season_start_date) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(csd.season_start_date) <=', $todate);
        }
        if ($filter != '') {
            $this->db->where("(csd.name LIKE '%".$this->db->escape_str($filter)."%' or s.name LIKE '%".$this->db->escape_str($filter)."%' or sw.se_subworks_name LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
            
        $this->db->select(array("csd.*, s.name AS season, sw.se_subworks_name AS works, CASE WHEN frequency_period = 'M' THEN 'Month' WHEN frequency_period= 'W' THEN 'Week' WHEN frequency_period= 'D' THEN 'Day' END AS period"));
        $this->db->from('con_schedule_def csd');
        $this->db->join('con_contract_season s', 'csd.seasonid=s.id', 'left');
        $this->db->join('se_subworks sw', 'csd.subworksid=sw.id', 'left'); 
        //$this->db->where('a.customerid', $customerid);
        //$this->db->where('s.status', 1);
        //$this->db->where('sw.enabled', 1);
        $this->db->where('contractid', $contractid);
                            
        foreach ($params as $fn=> $fv) {
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
        if($fromdate != '') {
            $this->db->where('DATE(csd.season_start_date) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(csd.season_start_date) <=', $todate);
        }
        if ($filter != '') {
            $this->db->where("(csd.name LIKE '%".$this->db->escape_str($filter)."%' or s.name LIKE '%".$this->db->escape_str($filter)."%' or sw.se_subworks_name LIKE '%".$this->db->escape_str($filter)."%')");
        }
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Contract Sites Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
    /**
    * This function use for Insert Contract Site
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertContractSchedule($params) {
        
        //1 - load multiple models
        require_once('chain/InsertContractScheduleChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $InsertContractScheduleChain = new InsertContractScheduleChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $InsertContractScheduleChain->setSuccessor($ContractAuditLogChain); 
          
        //4 - start the process
        $this->LogClass->log('Insert Contract Schedule  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertConScheduleData'];
        
        $conAuditLogData = array();
        
                            
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertConScheduleData' => $insertData,
            'conAuditLogData'       => $conAuditLogData 
        );
        
                            
        $InsertContractScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Contract Site
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateContractSchedule($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContractScheduleChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
         
        //2 - initialize instances
        $UpdateContractScheduleChain = new UpdateContractScheduleChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $UpdateContractScheduleChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Update Contract Schedule : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $oldContractScheduleData = $this->getContractScheduleById($params['scheduleid']);
                            
        $updateScheduleData = $params['updateConScheduleData'];
             
        $conAuditLogData=array();
        foreach ($updateScheduleData as $key => $value) {
            if (trim($oldContractScheduleData[$key]) != trim($value)) {

                $conAuditLogData[] = array(
                    'contractid' => $params['contractid'], 
                    'dateadded'  => date('Y-m-d H:i:s'), 
                    'contactid'  => $loggedUserData['contactid'], 
                    'tablename'  => 'con_schedule_def', 
                    'fieldname'  => $key, 
                    'oldvalue'   => $oldContractScheduleData[$key], 
                    'newvalue'   => $value
                );
            }
        }
         
        $request = array(
            'params'                    => $params, 
            'userData'                  => $loggedUserData,  
            'updateConScheduleData'     => $updateScheduleData,
            'oldContractScheduleData'   => $oldContractScheduleData, 
            'conAuditLogData'           => $conAuditLogData
        );
                            
        $UpdateContractScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
        
        
    }
    
    /**
    * This function use for Delete Contract Site
    * @param array $params - the $params is array of Address and contactid(LoggedUser)
    * @return array
    */
    public function deleteContractSchedule($params) {
        
        //1 - load multiple models
        require_once('chain/DeleteContractScheduleChain.php');
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $DeleteContractScheduleChain = new DeleteContractScheduleChain();
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected
        $DeleteContractScheduleChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Delete Contract Schedule : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
                            
        $oldContractScheduleData = $this->getContractScheduleById($params['scheduleid']);
                            
        $conAuditLogData=array();
                            
        $request = array(
            'params'                    => $params,
            'userData'                  => $loggedUserData,  
            'oldContractScheduleData'   => $oldContractScheduleData, 
            'conAuditLogData'           => $conAuditLogData
        );
        
        $DeleteContractScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue;
        
    }
    
      
    /**
    * This function use for getting contract Sites
    * @param integer $contractid - contractid for selected contract
    * @param string $state - filter state   
    * @return array 
    */
    
    public function getContractSiteAddresses($contractid, $state = '') {
        
        $this->db->select(array("a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as site"));
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner'); 
        $this->db->where('ca.contractid', $contractid);
        $this->db->where('ca.isactive', 1);               
        if ($state != '') {
            $this->db->where('a.sitestate', $state);  
        }
         
        return $this->db->get()->result_array();
    }
    
    
    /**
    * This function use for getting contract Schedule Sites
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $scheduleid - scheduleid for selected Schedule for contract
    * @param string $state - filter state   
    * @return array 
    */
    
    public function getContractScheduleSites($customerid, $contractid, $scheduleid, $state = '') {
        
        $this->db->select(array("a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as site"));
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner'); 
        $this->db->where('ca.contractid', $contractid);
        $this->db->where('ca.isactive', 1);               
        if ($state != '') {
            $this->db->where('a.sitestate', $state);  
        }
         
        $allContractSite = $this->db->get()->result_array();
         
        $this->db->select(array("a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as site"));
        $this->db->from('con_schedule_address csa');
        $this->db->join('addresslabel a', 'csa.labelid=a.labelid', 'inner'); 
        $this->db->where('csa.schedule_def_id', $scheduleid);
        if ($state != '') {
            $this->db->where('a.sitestate', $state);  
        }
         
        $selectedSite = $this->db->get()->result_array();  
        
        $this->db->select(array("a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as site"));
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner'); 
        $this->db->where('ca.contractid', $contractid);
        $this->db->where('ca.labelid NOT IN (SELECT labelid FROM con_schedule_address WHERE schedule_def_id='. $scheduleid.')');
        $this->db->where('ca.isactive', 1);               
        if ($state != '') {
            $this->db->where('a.sitestate', $state);  
        }
         
         
        $availableSite = $this->db->get()->result_array();
                            
         
        $data = array(
            'allContractSite' => $allContractSite, 
            'selectedSite' => $selectedSite, 
            'availableSite' => $availableSite
        );
                            
        
        return $data;
        
    }
    
    
    /**
    * This function use for Insert Contract Schedule Site
    * @param array $params - the $params is array of ScheduleSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertContractScheduleSites($params) {
        
        //1 - load multiple models
        require_once('chain/InsertContractScheduleSitesChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $InsertContractScheduleSitesChain = new InsertContractScheduleSitesChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $InsertContractScheduleSitesChain->setSuccessor($ContractAuditLogChain); 
          
        //4 - start the process
        $this->LogClass->log('Insert Contract Schedule Sites : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertScheduleAddressData = $params['insertScheduleAddressData'];
        
        $conAuditLogData = array();
                            
        $request = array(
            'params'                    => $params, 
            'userData'                  => $loggedUserData,  
            'insertScheduleAddressData' => $insertScheduleAddressData,
            'conAuditLogData'           => $conAuditLogData 
        );
        
                            
        $InsertContractScheduleSitesChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
                            
    
    /**
    * This function use for Delete Contract Schedule Site
    * @param array $params - the $params is array of ScheduleAddress and contactid(LoggedUser)
    * @return array
    */
    public function deleteContractScheduleSites($params) {
        
        //1 - load multiple models
        require_once('chain/DeleteContractScheduleSitesChain.php');
        require_once('chain/ContractAuditLogChain.php');                    
        
        //2 - initialize instances
        $DeleteContractScheduleSitesChain = new DeleteContractScheduleSitesChain();
        $ContractAuditLogChain = new ContractAuditLogChain();                    
        
        //3 - get the parts connected
        $DeleteContractScheduleSitesChain->setSuccessor($ContractAuditLogChain);                     
        
        //4 - start the process
        $this->LogClass->log('Delete Contract Schedule Site : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $deleteScheduleAddressData = $params['deleteScheduleAddressData'];
        $conAuditLogData=array();                   
        $request = array(
            'params'                    => $params,
            'userData'                  => $loggedUserData,  
            'deleteScheduleAddressData' => $deleteScheduleAddressData,
            'conAuditLogData'           => $conAuditLogData
        );
        
        $DeleteContractScheduleSitesChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue;
        
    }
    
    
    /**
    * This function use for Insert Contract Customer Site Order Ref
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertConCustomerSiteOrderRef($params) {
        
        //1 - load multiple models
        require_once('chain/InsertConCustomerSiteOrderRefChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $InsertConCustomerSiteOrderRefChain = new InsertConCustomerSiteOrderRefChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $InsertConCustomerSiteOrderRefChain->setSuccessor($ContractAuditLogChain); 
          
        //4 - start the process
        $this->LogClass->log('Insert Con Customer SIte Order Ref  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertCustomerSiteOrderData'];
        
        $conAuditLogData = array();
                            
        $request = array(
            'params'                      => $params, 
            'userData'                    => $loggedUserData,  
            'insertCustomerSiteOrderData' => $insertData,
            'conAuditLogData'             => $conAuditLogData 
        );
        
                            
        $InsertConCustomerSiteOrderRefChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Contract Contract Customer Site Order Ref
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateConCustomerSiteOrderRef($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateConCustomerSiteOrderRefChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
         
        //2 - initialize instances
        $UpdateConCustomerSiteOrderRefChain = new UpdateConCustomerSiteOrderRefChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $UpdateConCustomerSiteOrderRefChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Update Con Customer Site Order Ref : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
                            
                            
        $updateScheduleData = $params['updateCustomerSiteOrderData'];
             
        $conAuditLogData=array();
                            
        $request = array(
            'params'                    => $params, 
            'userData'                  => $loggedUserData,  
            'updateCustomerSiteOrderData'     => $updateScheduleData, 
            'conAuditLogData'           => $conAuditLogData
        );
                            
        $UpdateConCustomerSiteOrderRefChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
        
        
    }
    
    /**
    * This function Get a contract data from contact with given parameters
    * @param integer $contractid  
    * @return array 
    */
    public function getConCustomerSiteOrderRefData($contractid, $labelid, $month, $year, $serviceid = 0) {
             
                            
        $this->db->select('*');
        $this->db->from('con_customer_order_reference');
        $this->db->where('contractid', $contractid);
        $this->db->where('labelid', $labelid);
        $this->db->where('monthofyear', $month);
        $this->db->where('year', $year);
        if($serviceid != 0 && $serviceid != NULL && $serviceid != ''){
            $this->db->where('contract_service_id', $serviceid);
        }
        $data = $this->db->get()->row_array();
        return $data;
        
    }
    
    /**
    * This function Get a contract data from contact with given parameters
    * @param integer $contractid  
    * @return array 
    */
    public function getContractSiteLabelids($contractid) {
             
        $labelids = array();                     
        $this->db->select('labelid');
        $this->db->from('con_address');
        $this->db->where('contractid', $contractid);
        $data =  $this->db->get()->result_array();
        foreach ($data as $key => $value) {
            $labelids[] = $value['labelid'];
        }           
        return $labelids;
        
    }
    
    
    public function getContractSiteSuburb($contractid) {
                            
            
        $this->db->select("a.sitesuburb, count(a.sitesuburb) as count");
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner'); 
                            
        $this->db->where('ca.contractid', $contractid);
        $this->db->group_by('a.sitesuburb');
        $data =  $this->db->get()->result_array();
                            
        return $data;
        
    }
    
    
    /**
    * This function Get a contract data from contact with given parameters
    * @param integer $customerid 
    * @param integer $labelid 
    * @return array 
    */
    public function getSiteAddressGroupids($customerid, $labelid) {
             
       $groupids = array();                     
        $this->db->select('agm.*');
        $this->db->from('address_groupmember agm');
        $this->db->join('con_addressgroup cag', 'agm.groupid=cag.id', 'inner');
        $this->db->where('cag.customer_id', $customerid);
        $this->db->where('agm.labelid', $labelid);
        $data =  $this->db->get()->result_array();
        foreach ($data as $key => $value) {
            $groupids[] = $value['groupid'];
        }           
        return $groupids;
        
    }
    
    /**
    * This function Get a contract data from contact with given parameters
    * @param integer $siteid 
    * @return array 
    */
    public function getContractSiteById($siteid) {
                            
        
        $this->db->select(array("ca.*, a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, a.latitude_decimal, a.longitude_decimal, CONCAT(IFNULL(a.siteline1,''),'<br>', IFNULL(a.siteline2,''),'<br>',IFNULL(a.sitesuburb,''),' ',IFNULL(a.sitestate,''),' ',IFNULL(a.sitepostcode,'')) as site"));
        $this->db->from('con_address ca');
        $this->db->join('addresslabel a', 'ca.labelid=a.labelid', 'inner');
        $this->db->where('ca.id', $siteid);
     
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contract Site Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function Get a contract data from contact with given parameters
    * @param integer $scheduleid 
    * @return array 
    */
    public function getContractScheduleById($scheduleid) {
                            
        
        $this->db->select(array("csd.*, s.name AS season, sw.se_subworks_name AS works, CASE WHEN frequency_period = 'M' THEN 'Month' WHEN frequency_period= 'W' THEN 'Week' WHEN frequency_period= 'D' THEN 'Day' END AS period"));
        $this->db->from('con_schedule_def csd');
        $this->db->join('con_contract_season s', 'csd.seasonid=s.id', 'left');
        $this->db->join('se_subworks sw', 'csd.subworksid=sw.id', 'left');  
        $this->db->where('csd.id', $scheduleid);
                            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contract Schedule Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function Get a contract data from contact with given parameters
    * @param integer $contractid 
    * @return array 
    */
    public function getContractById($contractid) {
           
	if (! isset($contractid) ){
            throw new exception('contractid value must be supplied.');
        }
        
        $this->db->select('c.*, t.name as typename, con.firstname, con.phone');
        $this->db->from('con_contract c');
        $this->db->join('con_contract_type t', 't.id=c.contracttypeid', 'INNER');
        $this->db->join('contact con', 'c.managerid=con.contactid', 'Left'); 
        $this->db->where('c.id', $contractid);
     
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contract Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * @desc This function Get service types
    * 
     * @param integer $contractid
    * @return array - 
    */
    public function getContractSubWorks($contractid) { 
                            
        
        $this->db->select('sw.id, se_subworks_name');
        $this->db->from('se_subworks sw'); 
        $this->db->join('con_service_type_subworks csts', 'csts.subworks_id=sw.id', 'INNER');
        $this->db->join('con_service_type cst', 'cst.id=csts.service_type_id', 'INNER');
        $this->db->where('cst.isactive', 1);
        $this->db->where('sw.enabled', 1);
        $this->db->where('csts.isactive', 1);
        $this->db->where('cst.contractid', $contractid);
        $this->db->order_by('se_subworks_name asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * This function Get Schedule data
    *  @param integer $scheduleid - scheduleid 
    * 
    * @return array
    */
    public function getContractScheduleWorksByScheduleid($contractid, $scheduleid, $labelid = NULL) {
        
        $this->db->select("s.labelid, s.id, s.servicetypeid, color, ss.textcolor, siteref, sitesuburb, sitestate, startdate, enddate, ss.code, t.name, s.statusid, t.icon, ss.name AS statusname");
        $this->db->from('addresslabel a');
        $this->db->join('con_schedule s', 's.labelid=a.labelid', 'inner');
        $this->db->join('con_service_type t', 's.servicetypeid=t.id', 'inner');
        $this->db->join('con_service_status ss', 's.statusid=ss.id', 'inner');
        $this->db->where('s.contractid', $contractid);
        $this->db->where('s.schedule_def_id', $scheduleid);
        if($labelid != NULL && $labelid != 0 && $labelid != ''){
            $this->db->where('s.labelid', $labelid);
        }
        $this->db->order_by('s.startdate asc');           
        //return $this->db->get_compiled_select();
        return $this->db->get()->result_array();
    }
   
    /**
    * This function Get Schedule data
    *  @param integer $scheduleid - scheduleid
    *  @param date $startdate - startdate
    *  @param date $enddate - enddate 
    * 
    * @return array
    */
    public function getContractScheduleWorksByDate($contractid, $scheduleid, $startdate, $enddate) {
        
        $this->db->select("s.labelid, s.id, s.servicetypeid, color, ss.textcolor, siteref, sitesuburb, sitestate, startdate, enddate, ss.code, t.name, s.statusid, t.icon, ss.name AS statusname");
        $this->db->from('addresslabel a');
        $this->db->join('con_schedule s', 's.labelid=a.labelid', 'inner');
        $this->db->join('con_service_type t', 's.servicetypeid=t.id', 'inner');
        $this->db->join('con_service_status ss', 's.statusid=ss.id', 'inner');
        $this->db->where('s.contractid', $contractid);
        $this->db->where('s.schedule_def_id', $scheduleid);
        $this->db->where('s.statusid', 1);  
        $this->db->where("(DATE(s.startdate) BETWEEN '".$startdate."' and '".$enddate."')");
        //return $this->db->get_compiled_select();
        return $this->db->get()->result_array();
    }
    
    
    /**
    * This function use for Insert Contract Schedule Works
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertContractScheduleWorks($params) {
        
        //1 - load multiple models
        require_once('chain/InsertContractScheduleWorksChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $InsertContractScheduleWorksChain = new InsertContractScheduleWorksChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $InsertContractScheduleWorksChain->setSuccessor($ContractAuditLogChain); 
          
        //4 - start the process
        $this->LogClass->log('Insert Contract Schedule Work : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertScheduleWorkData = $params['insertScheduleWorkData'];
        
        $conAuditLogData = array();
        
                            
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertScheduleWorkData' => $insertScheduleWorkData,
            'conAuditLogData'       => $conAuditLogData 
        );
        
                            
        $InsertContractScheduleWorksChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Contract Site
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateContractScheduleWorks($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContractScheduleWorksChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
         
        //2 - initialize instances
        $UpdateContractScheduleWorksChain = new UpdateContractScheduleWorksChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $UpdateContractScheduleWorksChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Update Contract Schedule Work: ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
                            
                            
        $updateScheduleWorkData = $params['updateScheduleWorkData'];
             
        $conAuditLogData=array();
                            
         
        $request = array(
            'params'                    => $params, 
            'userData'                  => $loggedUserData,  
            'updateScheduleWorkData'    => $updateScheduleWorkData, 
            'conAuditLogData'           => $conAuditLogData
        );
                            
        $UpdateContractScheduleWorksChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
        
        
    }
    
    /**
    * This function use for Delete Contract Schedule Site
    * @param array $params - the $params is array of ScheduleAddress and contactid(LoggedUser)
    * @return array
    */
    public function deleteContractScheduleWorks($params) {
        
        //1 - load multiple models
        require_once('chain/DeleteContractScheduleWorksChain.php');
        require_once('chain/ContractAuditLogChain.php');                    
        
        //2 - initialize instances
        $DeleteContractScheduleWorksChain = new DeleteContractScheduleWorksChain();
        $ContractAuditLogChain = new ContractAuditLogChain();                    
        
        //3 - get the parts connected
        $DeleteContractScheduleWorksChain->setSuccessor($ContractAuditLogChain);                     
        
        //4 - start the process
        $this->LogClass->log('Delete Contract Schedule Works : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
                            
        $conAuditLogData=array();                   
        $request = array(
            'params'                    => $params,
            'userData'                  => $loggedUserData,   
            'conAuditLogData'           => $conAuditLogData
        );
        
        $DeleteContractScheduleWorksChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue;
        
    }
    
    
    /**
    * This function use for Insert new Supplier
    * @param array $params - the $params is array of supplierdata and contactid(LoggedUser)
    * @return array
    */
       
    public function insertConParentJob($params)
    {
        //1 - load multiple models
        require_once('chain/InsertConParentJobChain.php');
        
        //2 - initialize instances
        $InsertConParentJobChain = new InsertConParentJobChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Contract  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertParentJobData = $params['insertParentJobData'];
        
                            
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData, 
            'insertParentJobData'   => $insertParentJobData 
        );
 
        $InsertConParentJobChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertConParentJobChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * @desc This function update ConParentJob
    * @param int $id
    * @param array $updateData
    * @return array - 
    */
    public function updateConParentJob($id, $updateData) {

        $this->db->where('id',$id);
        $this->db->update('con_parentjob_id', $updateData);
      
    }
    
    /**
    * This function use for getting Site Parent Job Orders
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getSiteParentJobOrders($customerid, $contractid, $size, $start, $field, $order, $filter = '', $params = array()) {
                            
        $this->db->select("pj.id");
        $this->db->from('con_parentjob_id pj');
        $this->db->join('jobs j', 'pj.parentjobid = j.jobid', 'inner');
        $this->db->join('addresslabel a', 'j.labelid = a.labelid', 'inner'); 
        //$this->db->where('a.customerid', $customerid);
        $this->db->where('pj.contractid', $contractid);
       
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(a.siteref LIKE '%".$this->db->escape_str($filter)."%' or a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
            
        $this->db->select("pj.id, a.labelid, a.siteref, a.siteline2, a.sitesuburb, a.sitestate, pj.custordref, pj.custordref2, pj.custordref3");
        $this->db->from('con_parentjob_id pj');
        $this->db->join('jobs j', 'pj.parentjobid = j.jobid', 'inner');
        $this->db->join('addresslabel a', 'j.labelid = a.labelid', 'inner'); 
        //$this->db->where('a.customerid', $customerid);
        $this->db->where('pj.contractid', $contractid);
        
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(a.siteref LIKE '%".$this->db->escape_str($filter)."%' or a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data'  => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Site Parent Job Order Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for getting Site Orders
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getSiteOrders($customerid, $contractid, $size, $start, $field, $order, $filter = '', $params = array()) {
                            
        $this->db->select("cor.id");
        $this->db->from('con_customer_order_reference cor');
        $this->db->join('con_contract_service cs', 'cor.contract_service_id=cs.id', 'left'); 
        //$this->db->where('a.customerid', $customerid);
        $this->db->where('cor.contractid', $contractid);
        $this->db->where('cs.status', 1);
        $this->db->where('cor.status', 1);
        
        foreach ($params as $fn=> $fv) {
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
                            
        
        $trows = $this->db->count_all_results();
            
        $this->db->select("cor.id, labelid, cs.name, scheduledate, customer_order_reference1, customer_order_reference2, customer_order_reference3, orderamount");
        $this->db->from('con_customer_order_reference cor');
        $this->db->join('con_contract_service cs', 'cor.contract_service_id=cs.id', 'left'); 
        //$this->db->where('a.customerid', $customerid);
        $this->db->where('cor.contractid', $contractid);
        $this->db->where('cs.status', 1);
        $this->db->where('cor.status', 1);
        
        foreach ($params as $fn=> $fv) {
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
                            
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data'  => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Site Order Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
     /**
    * This function use for getting get Contract Technicians
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getContractTechnicians($customerid, $contractid, $size, $start, $field, $order, $filter = '', $params = array()) {
        
        $sql = "SELECT id,userid,firstname AS contact,normal_rate,weekah_rate,saturday_rate,sunday_rate,pubhol_rate,startdate,enddate,isactive "
                . "FROM con_technician t "
                . "LEFT JOIN contact c ON t.contactid=c.contactid "
                . "WHERE contractid =  contractid";
        
        $this->db->select("t.id");
        $this->db->from('con_technician t');
        $this->db->join('contact c', 't.contactid=c.contactid', 'left'); 
        $this->db->where('t.contractid', $contractid);
       
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(t.userid LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
            
       
        $this->db->select('id, userid, firstname AS contact, normal_rate, weekah_rate, saturday_rate, sunday_rate, pubhol_rate, startdate, enddate, isactive');
        $this->db->from('con_technician t');
        $this->db->join('contact c', 't.contactid=c.contactid', 'left'); 
        $this->db->where('t.contractid', $contractid);
        
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(t.userid LIKE '%".$this->db->escape_str($filter)."%')");
        }
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Contract Technician Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
     /**
    * This function use for Insert Contract Technician
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertContractTechnician($params) {
        
        //1 - load multiple models
        require_once('chain/InsertContractTechnicianChain.php'); 
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $InsertContractTechnicianChain = new InsertContractTechnicianChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected 
        $InsertContractTechnicianChain->setSuccessor($ContractAuditLogChain); 
          
        //4 - start the process
        $this->LogClass->log('Insert Contract Technician  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertConTechnicianData'];
        
        $conAuditLogData = array();
        
        $conAuditLogData[] = array(
            'contractid' => $insertData['contractid'], 
            'dateadded'  => date('Y-m-d H:i:s'), 
            'contactid'  => $loggedUserData['contactid'], 
            'tablename'  => 'con_technician' , 
            'fieldname'  => 'userid', 
            'oldvalue'   => 'Added', 
            'newvalue'   => $insertData['userid']
        );
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertConTechnicianData'     => $insertData,
            'conAuditLogData'       => $conAuditLogData 
        );
        
                            
        
        $InsertContractTechnicianChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Contract Technician
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateContractTechnician($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContractTechnicianChain.php'); 
         require_once('chain/ContractAuditLogChain.php');
        //2 - initialize instances
        $UpdateContractTechnicianChain = new UpdateContractTechnicianChain(); 
        $ContractAuditLogChain = new ContractAuditLogChain();
        //3 - get the parts connected 
        $UpdateContractTechnicianChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Update Contract Technician : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $oldContractSiteData = $this->getContractTechnicianById($params['contechnicianid']);
                            
        $updateSiteData = $params['updateConTechnicianData'];
             
        $conAuditLogData=array();
        foreach ($updateSiteData as $key => $value) {
            if (trim($oldContractSiteData[$key]) != trim($value)) {

                $conAuditLogData[] = array(
                    'contractid' => $params['contractid'], 
                    'dateadded'  => date('Y-m-d H:i:s'), 
                    'contactid'  => $loggedUserData['contactid'], 
                    'tablename'  => 'con_technician' , 
                    'fieldname'  => $key, 
                    'oldvalue'   => $oldContractSiteData[$key], 
                    'newvalue'   => $value
                );
            }
        }
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'updateConTechnicianData'     => $updateSiteData,
            'oldContractSiteData'   => $oldContractSiteData, 
            'conAuditLogData'       => $conAuditLogData
        );
                            
        $UpdateContractTechnicianChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
        
        
    }
    
    /**
    * This function use for Delete Contract Technician
    * @param array $params - the $params is array of Address and contactid(LoggedUser)
    * @return array
    */
    public function deleteContractTechnician($params) {
        
        //1 - load multiple models
        require_once('chain/DeleteContractTechnicianChain.php');
        require_once('chain/ContractAuditLogChain.php');
        
        //2 - initialize instances
        $DeleteContractTechnicianChain = new DeleteContractTechnicianChain();
        $ContractAuditLogChain = new ContractAuditLogChain();
        
        //3 - get the parts connected
        $DeleteContractTechnicianChain->setSuccessor($ContractAuditLogChain); 
        
        //4 - start the process
        $this->LogClass->log('Delete Contract Technician : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
                            
        $oldContractSiteData = $this->getContractTechnicianById($params['contechnicianid']);
                            
        $conAuditLogData=array();
        $conAuditLogData[] = array(
            'contractid' => $params['contractid'], 
            'dateadded'  => date('Y-m-d H:i:s'), 
            'contactid'  => $loggedUserData['contactid'], 
            'tablename'  => 'con_technician' , 
            'fieldname'  => 'userid', 
            'oldvalue'   => $oldContractSiteData['userid'], 
            'newvalue'   => 'Delete'
        );
       
        $request = array(
            'params'                => $params,
            'userData'              => $loggedUserData,  
            'oldContractSiteData'   => $oldContractSiteData, 
            'conAuditLogData'       => $conAuditLogData
        );
        
        $DeleteContractTechnicianChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $ContractAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue;
        
    }
    
	 
    /**
    * This function Get a contract Technicians
    * @param integer $contractid 
    * @return array 
    */
    public function getActiveContractTechnicians($contractid) {
                            
         $this->db->select('t.*, c.firstname AS contact');
        $this->db->from('con_technician t');
        $this->db->join('contact c', 't.contactid=c.contactid', 'left');  
        $this->db->where('t.isactive', 1);
        $this->db->where('t.contractid', $contractid);
	            
	$data = $this->db->get()->result_array();
                            
        
        return $data;
        
    }
    
    
     
     /**
    * This function use for getting get Contracted Hours 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC 
    * @return array 
    */
    
    public function getContractedHoursData($size, $start, $field, $order) {
        
        $sql = "SELECT id,userid,firstname AS contact,normal_rate,weekah_rate,saturday_rate,sunday_rate,pubhol_rate,startdate,enddate,isactive "
                . "FROM con_technician t "
                . "LEFT JOIN contact c ON t.contactid=c.contactid "
                . "WHERE contractid =  contractid";
        
        $this->db->select("id");
        $this->db->from('con_contracted_hours '); 
                            
        $trows = $this->db->count_all_results();
            
       
        $this->db->select('*');
        $this->db->from('con_contracted_hours');
                            
                            
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Contracted Hours Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
     /**
    * This function use for Insert Contract Technician
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
    public function insertContractedHours($params) {
        
        //1 - load multiple models
        require_once('chain/InsertContractedHoursChain.php'); 
        
        //2 - initialize instances
        $InsertContractedHoursChain = new InsertContractedHoursChain(); 
                            
        
        //3 - get the parts connected  
          
        //4 - start the process
        $this->LogClass->log('Insert Contracted Hours  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertContractedHoursData'];
        
                            
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertContractedHoursData'     => $insertData 
        );
        
                            
        
        $InsertContractedHoursChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertContractedHoursChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Contract Technician
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateContractedHours($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContractedHoursChain.php');  
        //2 - initialize instances
        $UpdateContractedHoursChain = new UpdateContractedHoursChain();  
        //3 - get the parts connected 
                            
        
        //4 - start the process
        $this->LogClass->log('Update Contracted Hours : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
                            
        $updateData = $params['updateContractedHoursData'];
                            
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'updateContractedHoursData'     => $updateData 
        );
                            
        $UpdateContractedHoursChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateContractedHoursChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
        
        
    }
    
      /**
    * This function Get a contract Contracted Hours data from contact with given parameters
    * @param integer $contractid 
    * @return array 
    */
    public function getConContractedHours($contractid) {
                            
        $this->db->select('h.*');
        $this->db->from('con_contracted_hours h');
        $this->db->join('con_contract c', 'c.contracted_hoursid=h.id', 'inner');  
        $this->db->where('c.id', $contractid);
        $data = $this->db->get()->row_array();
        return $data;
        
    }
    
    /**
    * This function Get a Contracted Hours data from contact with given parameters
 
    * @return array 
    */
    public function getContractedHours() {
                            
        $this->db->select('*');
        $this->db->from('con_contracted_hours'); 
        $this->db->where('isactive', 1);
         $this->db->order_by('sortorder', 'asc');
        $data = $this->db->get()->result_array();
        return $data;
        
    }
              
     /**
    * This function Get a Contracted Hours Detail data from contact with given parameters
 
    * @return array 
    */
    public function getContractedHoursDetail($id) {
                            
        $this->db->select('*');
        $this->db->from('con_contracted_hours'); 
        $this->db->where('id', $id); 
        $data = $this->db->get()->row_array();
        return $data;
        
    }
    /**
    * This function Get a contract Technician data from contact with given parameters
    * @param integer $id 
    * @return array 
    */
    public function getContractTechnicianById($id) {
                            
         $this->db->select('t.*, c.firstname AS contact');
        $this->db->from('con_technician t');
        $this->db->join('contact c', 't.contactid=c.contactid', 'left');  
        $this->db->where('t.id', $id);
     
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contract Technician Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     /**
    * This function Check Tech is already allocate to contract or not
    * @param integer $contractid 
    * @param integer $userid
    * @param integer $contechnicianid
    * @return array 
    */
    public function checkContractTechnician($contractid, $userid, $contechnicianid = 0) {
        
        $this->db->select('*');
        $this->db->from('con_technician');
        $this->db->where('contractid', $contractid);
        $this->db->where('userid', $userid); 
		if($contechnicianid != 0 && $contechnicianid != NULL && $contechnicianid != ''){
            $this->db->where('id !=', $contechnicianid); 
        }
		$data = $this->db->get()->row_array();
        if(count($data)>0){
            return true;
        }   
        
         return false;
    }
    
      /**
    * This function use for getting get Contract Parent Jobs
    * @param integer $customerid - customerid for logged customer
    * @param integer $contractid - contractid for selected contract
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getContractParentJobs($customerid, $contractid, $size, $start, $field, $order, $filter = '', $params = array()) {
        
        $sql = "SELECT pj.id,parentjobid,monthofyear,YEAR,pj.custordref,NAME AS service,j.custordref,j.custordref2,j.custordref3,j.leaddate "
                . "FROM con_parentjob_id pj "
                . "INNER JOIN con_contract_service cs ON pj.contract_service_id=cs.id"
                . "LEFT JOIN jobs j ON pj.parentjobid=j.jobid"
                . "WHERE pj.status=1 AND pj.contractid = :contractid";
        
        $this->db->select("pj.id");
        $this->db->from('con_parentjob_id pj');
        $this->db->join('con_contract_service cs', 'pj.contract_service_id=cs.id', 'INNER'); 
        $this->db->join('jobs j', 'pj.parentjobid=j.jobid', 'left'); 
        $this->db->where('pj.contractid', $contractid);
        $this->db->where('pj.status', 1);
       
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(pj.custordref LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
            
       
        $this->db->select('pj.id, parentjobid, pj.contractid, monthofyear, YEAR as year, pj.custordref, NAME AS service, j.custordref, j.custordref2, j.custordref3, j.leaddate');
        $this->db->from('con_parentjob_id pj');
        $this->db->join('con_contract_service cs', 'pj.contract_service_id=cs.id', 'INNER'); 
        $this->db->join('jobs j', 'pj.parentjobid=j.jobid', 'left'); 
        $this->db->where('pj.contractid', $contractid);
        $this->db->where('pj.status', 1);
        
        foreach ($params as $fn=> $fv) {
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
            $this->db->where("(pj.custordref LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref2 LIKE '%".$this->db->escape_str($filter)."%' or pj.custordref3 LIKE '%".$this->db->escape_str($filter)."%')");
        }
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
         
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
       
        $this->LogClass->log('Get Contract Parent Job Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
     
    /**
    * This function Get Service
    *  @param integer $contractid - $contractid
    *  @param integer $monthofyear
    *  @param integer $year
    * 
    * @return array
    */
    public function getContParentJobId($contractid, $monthofyear, $year) {
    
        $jobid = 0;
        $this->db->select('parentjobid');
        $this->db->from('con_parentjob_id'); 
        $this->db->where('contractid', $contractid);
        $this->db->where('monthofyear', $monthofyear);
        $this->db->where('year', $year);
        $data = $this->db->get()->row_array();
        if(count($data)>0){
            $jobid = $data['parentjobid'];
        }
        
        return $jobid;
        
    }
    
    /**
    * This function Get Service
    *  @param integer $contractid - $contractid
    
    * 
    * @return array
    */
    public function getContParentJobRuleDefinition($contractid) {
        
        
          $sql = "SELECT pjm.code, pjm.name AS parentjob_method, wom1.code, wom1.name AS ordref1_method, wom2.code, wom2.name AS ordref2_method, wom3.code, wom3.name AS ordref3_method, r.estimated_sell, r.internal_buffer, r.initial_jobstage, r.is_chargeable, r.create_materials_job, r.create_safetysheet_jobs  "
                  . "FROM con_jobcreation_rule r"
                  . "LEFT JOIN con_workorder_method wom1 ON wom1.id=r.ordref1_method"
                  . "LEFT JOIN con_workorder_method wom2 ON wom2.id=r.ordref2_method"
                  . "LEFT JOIN con_workorder_method wom3 ON wom3.id=r.ordref3_method"
                  . "LEFT JOIN  con_parentjobid_method pjm ON r.parentjob_method=pjm.id "
                  . "WHERE wom1.isactive=1 "
                  . "AND wom2.isactive=1 "
                  . "AND joblevel=2 "
                  . "AND contractid= :contractid";
    
        
         $this->db->select('pjm.code, pjm.name AS parentjob_method, r.contract_service_id, cs.name as con_service, wom1.code as ordref1_code, wom1.name AS ordref1_method, wom2.code as ordref2_code, wom2.name AS ordref2_method, wom3.code as ordref3_code, wom3.name AS ordref3_method, r.estimated_sell, r.internal_buffer, r.initial_jobstage, r.is_chargeable, r.create_materials_job, r.create_safetysheet_jobs');
        $this->db->from('con_jobcreation_rule r');
        $this->db->join('con_workorder_method wom1', 'wom1.id=r.ordref1_method', 'LEFT');
        $this->db->join('con_workorder_method wom2', 'wom2.id=r.ordref2_method', 'LEFT');
        $this->db->join('con_workorder_method wom3', 'wom1.id=r.ordref3_method', 'LEFT');
        $this->db->join('con_parentjobid_method pjm', 'r.parentjob_method=pjm.id', 'LEFT');
        $this->db->join('con_contract_service cs', 'r.contract_service_id=cs.id', 'LEFT');
        
        $this->db->where('contractid', $contractid);
        $this->db->where('joblevel', 2);
        $this->db->where('wom1.isactive', 1);   
        $this->db->where('wom2.isactive', 1);    
        return $this->db->get()->row_array();
    }
    
      /**
    * This function getContractParentJobValues
    *  @param integer $contractid - $contractid
    
    * @return array
    */
    public function getContractParentJobValues($contractid) {
       
        $this->db->select('j.customerid, parentjobid, j.labelid, j.jobdescription, j.contactid, j.sitefm, j.siteaddress, j.siteline1, j.siteline2, j.sitesuburb, j.sitestate, j.sitepostcode, j.territory');
        $this->db->from('con_contract c');
        $this->db->join('jobs j', 'c.parentjobid=j.jobid', 'INNER'); 
       $this->db->where('id', $contractid);    
        return $this->db->get()->row_array();
    }
    
	 /**
    * This function Get JobStages
  
    * @return array
    */
    public function getJobStages() {
        
                            
        
        $this->db->select('jobstagedesc');
        $this->db->from('jobstage'); 
        $this->db->where('contract_initialstage', 1); 
        return $this->db->get()->result_array();
    }
        
    
    /**
    * This function Get Service
    *  @param integer $contractid - $contractid
    
    * 
    * @return array
    */
    public function getContractServices($contractid) {
        
        
        $sql = ' SELECT cs.id,cs.name FROM con_contract_service cs 
                INNER JOIN con_contract_program cp ON cs.contract_program_id=cp.id 
                WHERE cp.status=1 AND cs.status=1 AND cp.contractid= :contractid';
        
        $this->db->select('cs.id, cs.name');
        $this->db->from('con_contract_service cs');
        $this->db->join('con_contract_program cp', 'cs.contract_program_id=cp.id', 'inner');
                            
        $this->db->where('cp.contractid', $contractid);
        $this->db->where('cp.status', 1);
        $this->db->where('cs.status', 1);   
        //return $this->db->get_compiled_select();
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get UserTechnicians
    *
    * @return array - 
    */
    public function getUserTechnicians() { 
        
                            
        $this->db->select('userid');
        $this->db->from('users'); 
        $this->db->where('jtlogin', 1);
         $this->db->where("IFNULL(inactive,'') != 'on'");
        $this->db->order_by('userid asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get service types
    *
    * @return array - 
    */
    public function getContractSeasons() { 
        
        $this->db->select('id, name');
        $this->db->from('con_contract_season'); 
        $this->db->where('status', 1);
        $this->db->order_by('sortorder asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get service types
    *
    * @return array - 
    */
    public function getContractTypes() { 
        
        $this->db->select('id, name');
        $this->db->from('con_contract_type'); 
        $this->db->where('status', 1);
        $this->db->order_by('name asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get address group
    *
     * @param integer $customerid
    * @return array - 
    */
    public function getContractAddressGroups($customerid) { 
        
        $this->db->select('id, name');
        $this->db->from('con_addressgroup'); 
        $this->db->where('status', 1);
        $this->db->where('customer_id', $customerid);
        $this->db->order_by('name asc');
        return $this->db->get()->result_array();
    }
    
    //My Contract Map
    
     public function getContractSiteStatus() {   
         
        $this->db->select('*');
        $this->db->from('gmap_contract_site_status');
        $this->db->where('status', '1');
        $this->db->order_by('name', 'asc');
        return $this->db->get()->result_array();
       
    }
    
    public function getContractServiceStandardByServiceId($serviceId) {   
         
        $this->db->select(array("*, CONCAT(code, ': ', name) as label"));
        $this->db->from('con_service_standard');
        $this->db->where('status', '1');
        $this->db->where('contract_service_id', $serviceId);
        $this->db->order_by('code', 'asc');
        return $this->db->get()->result_array();
       
    }
    
    
     public function getLabelIdInProgramMonth($customerid, $programMonth){
                
        $this->db->select(array('DISTINCT(e.labelid) as labelid'));
        $this->db->from('con_contract_program a'); 
        $this->db->join('con_contract_service b', 'a.id=b.contract_program_id');
        $this->db->join('con_service_standard c', 'b.id=c.contract_service_id');
        $this->db->join('con_service_standard_month d', 'c.id=d.contract_service_standard_id');
        $this->db->join('con_addresslabel_service_standard e', 'c.id=e.contract_service_standard_id');
        $this->db->where('a.customerid', $customerid);
        $this->db->where('d.half_month', $programMonth); 
        $query = $this->db->get();
                            
        $labelids = array();
        foreach ($query->result_array() as $index=>$row){
                
            $labelids[] = $row['labelid'];
        } 

        return $labelids;
    }
    
    public function getContractParentJob($customerid, $programMonth){
        
        $this->db->select(array('GROUP_CONCAT(parentjobid) as parentjobids'));
        $this->db->from('con_parentjob_id'); 
        $this->db->where('customerid', $customerid);
        $this->db->where('monthofyear', $programMonth); 
        $this->db->group_by('customerid');
        $query = $this->db->get();
                            

        if ($query->num_rows() > 0) {
            $parentJobIds = $query->row()->parentjobids;
        } else {
            $parentJobIds = "0";
        }

        return $parentJobIds;
    }
      
    public function getLabelIdByAddressLabelAttribute($customerid, $addressLabelAttributeIds){
        
        $this->db->select(array('GROUP_CONCAT(DISTINCT a.labelid) as labelids'));
        $this->db->from('addresslabel_attribute_value a'); 
        $this->db->join('addresslabel b', 'a.labelid=b.labelid'); 
        $this->db->where('b.customerid', $customerid);
        $this->db->where_in('a.attributeid', $addressLabelAttributeIds); 
        $this->db->group_by('b.customerid');
        $query = $this->db->get();
         
        if ($query->num_rows() > 0) {
            return $query->row()->labelids;
        } else {
            return  "0";
        }
                    
           
    }
     
    public function getHighlightedAttributes($labelid){
        
        $this->db->select(array('GROUP_CONCAT(c.name) as attributes'));
        $this->db->from('addresslabel a'); 
        $this->db->join('addresslabel_attribute_value b', 'a.labelid=b.labelid');
        $this->db->join('addresslabel_attribute c', 'c.id=b.attributeid');
                            
        $this->db->where('c.highlighted', 1);
        $this->db->where_in('a.labelid', $labelid); 
        $this->db->group_by('a.labelid');
        $query = $this->db->get();
                            
        if($query->num_rows() > 0){
            return  explode(",", $query->row()->attributes);
        } else {
            return array();
        }
    }
    
    public function getContractServiceStandard($contract_service_id, $labelid){
                            
        $this->db->select(array("e.code as service_code,  CONCAT(e.code, ': ', e.name) as service_standard"));
        $this->db->from('con_addresslabel_service_standard d'); 
        $this->db->join('con_service_standard e', 'e.id=contract_service_standard_id');
        $this->db->where('e.contract_service_id', $contract_service_id);                
                            
        $this->db->where('d.labelid', $labelid); 
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getContractsMapData($querytype, $selectedSite, $sortOrder, $customerid, $programMonth, $siteFMIds, $contractsitestatusIds, $contractGroundServicesIds, $contractPestServicesIds, $addressLabelAttributeIds, $labelIds) {
            
                
        // show labelids searched or whether sites due on contact
        $visitingLabelIds = $labelIds;
        if (count($labelIds)==0) { 
            $visitingLabelIds = $this->getLabelIdInProgramMonth($customerid, $programMonth);
        }
                            
        $parentJobIds = $this->getContractParentJob($customerid, $programMonth/2);
            
         $sql = "SELECT 
            NULL as sitenum,
            a2.jobid as jobid2,
            a3.apptid,
            a3.userid,
            DATE_FORMAT(a3.dte,'%d %b %y') as diary_date,
            '' as bodyContent4,
            a3.completed,
            IF(a.labelid IN (" .implode(",", $selectedSite). ") ,'checked', '') AS checked, ";
            if(count($visitingLabelIds) == 0){
                $sql .= " 'blue' as iconcolor,  1 as progress_status, "; 
            }
            else{
                $sql .= " CASE WHEN a.labelid IN (" .implode(",", $visitingLabelIds). ") THEN 
                    (CASE WHEN a2.jobid IS NULL THEN 'green'
                    ELSE 
                            (CASE WHEN a2.jobid IS NOT NULL AND a3.completed='on' THEN 'black'
                            ELSE 'red' 
                            END)
                    END)
                ELSE 'blue' 
                END as iconcolor,
                CASE WHEN a.labelid IN (" .implode(",", $visitingLabelIds). ") THEN 
                        (CASE WHEN a2.jobid IS NULL THEN 4
                        ELSE 
                                (CASE WHEN a2.jobid IS NOT NULL AND a3.apptid IS NOT NULL AND a3.completed!='on' THEN 3
                                ELSE 2 END)
                        END)
                ELSE 1 END as progress_status, "; 
            }
               
            $sql .= " a.labelid, 
            CONCAT('<strong>FM</strong> ', a4.firstname) as bodyContent,
            c9.value_string as BUDescription,
            REPLACE(c9.value_string, '\'', '&quot;') as windowHeading,
            a.sitesuburb,
            CASE $querytype
                WHEN 0 THEN CONCAT_WS('', '<input type=\"checkbox\" class=\"pull-right\" value=\"\" onclick=\"checkChange(this);\" target=\"#listcheck', a.labelid, '\" id=\"mapcheck', a.labelid, '\" >')
                WHEN 1 THEN NULL
                ELSE NULL
            END as formContent,
            TRIM(TRAILING '0' FROM a.latitude_decimal) as latitude,
            TRIM(TRAILING '0' FROM a.longitude_decimal) longitude,
            '' as markerlabel "; 
            
            $sql .= "FROM addresslabel a
                    LEFT JOIN jobs a2 ON (a.labelid=a2.labelid AND a2.parentid IN ($parentJobIds))
                    LEFT JOIN diary a3 ON (a2.jobid=a3.jobid AND a3.activity_id !=4)  
                    LEFT JOIN contact a4 ON (a.contactid=a4.contactid)
                    JOIN con_contract_program b ON (a.customerid=b.customerid) 
                    JOIN con_contract_service c ON (b.id=c.contract_program_id)
                    JOIN con_service_standard d ON (c.id=d.contract_service_id)
                    JOIN con_addresslabel_service_standard e ON (d.id=e.contract_service_standard_id AND a.labelid=e.labelid)
                    JOIN con_service_standard_month f ON (d.id=f.contract_service_standard_id)
                    JOIN addresslabel_attribute b9 ON (b9.name='BU Description')
                    JOIN addresslabel_attribute_value c9 ON (b9.id=c9.attributeid AND a.labelid=c9.labelid) ";
            
            $sql .= "WHERE a.customerid= $customerid ";
            
            // filter by labelids
            if(count($labelIds)>0){ 
                $sql .= " AND a.labelid IN (" .implode(",", $labelIds). ") ";
            }
            
            // filter by addresslabelattribute
            if(count($addressLabelAttributeIds)>0){ 
                $sql .= " AND a.labelid IN (" . $this->getLabelIdByAddressLabelAttribute($customerid, $addressLabelAttributeIds) . ") ";
            }
            
            // filter by contractsitestatusIds
            if(count($contractsitestatusIds)>0){ 
                $sql .= " AND (";
                foreach ($contractsitestatusIds as $key=>$value) { 
                    if($key > 0) $sql .= " OR "; 
                        
                        switch ($value) {
                            case 1:
                                //Required not scheduled
                                if(count($visitingLabelIds) == 0){
                                    $sql .= " (a2.jobid IS NULL) ";
                                }
                                else{
                                    $sql .= " (a.labelid IN (" .implode(",", $visitingLabelIds). ") AND a2.jobid IS NULL) ";
                                }
                                
                                break;
                            case 2:
                                //Scheduled not attended
                                if(count($visitingLabelIds) == 0){
                                    $sql .= " (a2.jobid IS NOT NULL AND a3.apptid IS NOT NULL AND a3.completed!='on') ";
                                }
                                else{
                                    $sql .= " (a.labelid IN (" .implode(",", $visitingLabelIds). ") AND a2.jobid IS NOT NULL AND a3.apptid IS NOT NULL AND a3.completed!='on') ";
                                }
                                
                                break;
                            case 3:
                                //Scheduled & attended
                                if(count($visitingLabelIds) == 0){
                                    $sql .= " (a2.jobid IS NOT NULL AND a3.apptid IS NOT NULL AND a3.completed='on') ";
                                }
                                else{
                                   $sql .= " (a.labelid IN (" .implode(",", $visitingLabelIds). ") AND a2.jobid IS NOT NULL AND a3.apptid IS NOT NULL AND a3.completed='on') ";
                                }
                                
                                break;
                            case 4:
                                //Not required
                                if(count($visitingLabelIds) == 0){
                                     $sql .= " (a.labelid NOT IN (0) ) ";
                                }
                                else{
                                    $sql .= " (a.labelid NOT IN (" .implode(",", $visitingLabelIds). ") ) ";
                                }
                               
                                break;
                        }
                        
                }
               $sql .= ") "; 
            }
            
            if(count($contractGroundServicesIds)>0 || count($contractPestServicesIds)>0){ 
                            
                $idlist = "";
                if (count($contractGroundServicesIds) > 0) {
                    $idlist .= implode(",", $contractGroundServicesIds);
                }

                if(count($contractPestServicesIds)>0){ 
                    if ($idlist != "") {
                        $idlist .= ",";
                    }
                    $idlist .= implode(",", $contractPestServicesIds);
                }
                
                $sql .= " AND d.id IN ($idlist)";
            }
            
                            
            if (count($siteFMIds) > 0) { 
                $sql .= " AND (";
                foreach ($siteFMIds as $key=>$value) {    
                    if ($key > 0) {
                        $sql .= " OR ";
                    }
                    $sql .= " a.contactid='" . $value . "'";
                }
                $sql .= ") "; 
            }
            
                            
            $sql .= " GROUP BY a.labelid ";
            
            switch ($sortOrder) {
                case 0:
                    $sql .= " ORDER BY progress_status DESC, a.latitude_decimal DESC;";
                    break;
                case 1:
                    $sql .= " ORDER BY progress_status DESC, a.latitude_decimal DESC;";
                    break;
                case 2:
                    $sql .= " ORDER BY BUDescription ASC;";
                    break;
                case 3:
                    $sql .= " ORDER BY a.latitude_decimal DESC;";
                    break;
                case 4:
                    $sql .= " ORDER BY a.latitude_decimal ASC;";
                    break;
                case 5:
                    $sql .= " ORDER BY a.longitude_decimal DESC;";
                    break;
                case 6:
                    $sql .= " ORDER BY a.longitude_decimal ASC;";
                    break;
            }
            
            //$sql ="SELECT NULL as sitenum, a2.jobid as jobid2, a3.apptid, a3.userid, DATE_FORMAT(a3.dte,'%d %b %y') as diary_date, '' as bodyContent4, a3.completed, IF(a.labelid IN (0) ,'checked', '') AS checked, 'blue' as iconcolor, 1 as progress_status, a.labelid, CONCAT('FM ', a4.firstname) as bodyContent, '' as BUDescription, '' as windowHeading, a.sitesuburb, CASE 1 WHEN 0 THEN CONCAT_WS('', '') WHEN 1 THEN NULL ELSE NULL END as formContent, TRIM(TRAILING '0' FROM a.latitude_decimal) as latitude, TRIM(TRAILING '0' FROM a.longitude_decimal) longitude, '' as markerlabel FROM addresslabel a LEFT JOIN jobs a2 ON (a.labelid=a2.labelid ) LEFT JOIN diary a3 ON (a2.jobid=a3.jobid AND a3.activity_id !=4) LEFT JOIN contact a4 ON (a.contactid=a4.contactid) JOIN con_contract_program b ON (a.customerid=b.customerid) JOIN con_contract_service c ON (b.id=c.contract_program_id) JOIN con_service_standard d ON (c.id=d.contract_service_id) JOIN con_addresslabel_service_standard e ON (d.id=e.contract_service_standard_id AND a.labelid=e.labelid) JOIN con_service_standard_month f ON (d.id=f.contract_service_standard_id) WHERE a.customerid= 7781 GROUP BY a.labelid ORDER BY a.latitude_decimal DESC";
            $query = $this->db->query($sql);
            $data = $query->result_array();
                
            $this->LogClass->log('Get My Contracts Query : '. $this->db->last_query());
            
            foreach ($data as $index=>$row){
                $data[$index]['sitenum'] = $index+1;
                
                $gm_service_standard = $this->getContractServiceStandard(1, $row['labelid']);
                $pest_service_standard = $this->getContractServiceStandard(2, $row['labelid']);
                
                if( empty($gm_service_standard)){
                    $data[$index]['GroundsMaintenanceServiceStandard'] = '';
                    $data[$index]['bodyContent2'] = '<strong>Grounds Maintenace</strong> NA';
                }else{
                    $data[$index]['GroundsMaintenanceServiceStandard'] = $gm_service_standard['service_code'];
                    $data[$index]['bodyContent2'] = '<strong>Grounds Maintenace</strong> '. $gm_service_standard['service_standard'];
                }    
                
                if( empty($pest_service_standard)){
                    $data[$index]['PestControlServiceStandard'] = ''; 
                    $data[$index]['bodyContent3'] = '<strong>Pest Control</strong> NA';
                }else{
                    $data[$index]['PestControlServiceStandard'] = $pest_service_standard['service_code']; 
                    $data[$index]['bodyContent3'] = '<strong>Pest Control</strong> ' . $pest_service_standard['service_standard']; 
                }
                
                if(!is_null($data[$index]['jobid2'])){
                    //echo 'joid2=' . $data[$index]['jobid2'] . "<br>";
                    if ($data[$index]['completed'] == 'on') {
                        $data[$index]['bodyContent4'] = '<strong>Date Attended</strong> ' . $data[$index]['diary_date'] . ' <strong>Tech</strong> ' . $data[$index]['userid'];
                    } else {
                        $data[$index]['bodyContent4'] = '<strong>Date Scheduled</strong> ' . $data[$index]['diary_date'] . ' &nbsp; <strong>Tech</strong> ' . $data[$index]['userid'];
                    }
                } //else   $data[$index]['bodyContent4'] = '';
                
                //$data[$index]['attributes'] = array('4WD', 'RideOn');
                //$this->get_highlighted_attributes($data[$index]['labelid']);
                
                $data[$index]['attributes'] = $this->getHighlightedAttributes($data[$index]['labelid']);
                
            }
            
            return $data;
    }
    
   
    //scheduled work
	/**
    * This function Get Contracts
    *  @param integer $customerid - logged user customerid
    * 
    * @return array
    */
    public function getCustomerContracts($customerid) {
        
        $this->db->select('id, name');
        $this->db->where('status', 1);
        $this->db->where('customerid', $customerid);
        $this->db->from('con_contract');
        $this->db->order_by('name asc');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * @desc This function Get service types
    *
    * @return array - 
    */
    public function getServiceTypes($customerid = NULL) { 
                
        $this->db->select('id, name');
        $this->db->from('con_service_type');
        if($customerid != NULL){
            $this->db->where('customerid', $customerid);
        }
        $this->db->where('isactive', 1);
        $this->db->order_by('name asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get Service status
    *
    * @return array - 
    */
    public function getServiceStatus() { 
                
        $this->db->select('*');
        $this->db->from('con_service_status');
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get parentjobid method
    *
    * @return array - 
    */
    public function getParentJobidMethods() { 
                
        $this->db->select('*');
        $this->db->from('con_parentjobid_method');
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get Biiling Methods
    *
    * @return array - 
    */
    public function getBiilingMethods() { 
                
        $this->db->select('*');
        $this->db->from('con_billing_method');
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder asc');
        return $this->db->get()->result_array();
    }
    
    
     /**
    * @desc This function Get Biiling Methods
    *
    * @return array - 
    */
    public function getWorkOrderMethods() { 
                
        $this->db->select('*');
        $this->db->from('con_workorder_method');
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * This function Get sites
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $show - it is use for data show as day/week/month
    * @param array $params - it is use for external filters 
    *  
    * @return array
    */
    public function getSites($size, $start, $field, $order, $show, $params) {
           
        $sql = "SELECT DISTINCT s.labelid, siteref,";
        if($show != '') {
            $sql = $sql. " sitesuburb AS ".$show."_sitesuburb,";
        } else {
            $sql = $sql. " sitesuburb,";
        }
        $sql = $sql. " sitestate FROM addresslabel a INNER JOIN con_schedule s ON s.labelid=a.labelid
                INNER JOIN con_service_type t ON s.servicetypeid=t.id
		INNER JOIN con_service_status ss ON s.statusid=ss.id";
        
        $extrawhere = '';
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $extrawhere .= " and (" . $fn ." in ('". implode("','", $fv) ."'))";
                }
            }
            else {
                if ($fv != '') {
                    $extrawhere .= " and (" . $fn ." ='".$fv ."')";
                }
            }
        }
        
        if(count($params) > 0) {
            if(trim(substr($extrawhere, 0, 5)) == 'and') {
                $extrawhere = substr($extrawhere, 4, strlen($extrawhere));
            }
            $sql = $sql. " WHERE ".$extrawhere;
        }
        
        
	//$sql = $sql." ORDER BY a.sitestate, a.siteref";	   
        $query = $this->db->query($sql);
        $trows = count($query->result_array());
        
        $sql = "SELECT DISTINCT s.labelid, siteref,";
        if($show != '') {
            $sql = $sql. " sitesuburb AS ".$show."_sitesuburb,";
        } else {
            $sql = $sql. " sitesuburb,";
        }
        $sql = $sql. " sitestate FROM addresslabel a INNER JOIN con_schedule s ON s.labelid=a.labelid
                INNER JOIN con_service_type t ON s.servicetypeid=t.id
		INNER JOIN con_service_status ss ON s.statusid=ss.id WHERE a.labelid!=0";
        
        $extrawhere = '';
        foreach ($params as $fn => $fv) {
            if (is_array($fv)) {
                if (count($fv) > 0) {
                    $extrawhere .= " and (" . $fn ." in ('". implode("','", $fv) ."'))";
                }
            }
            else {
                if ($fv != '') {
                    $extrawhere .= " and (" . $fn ." ='".$fv ."')";
                }
            }
        }
        
        $sql = $sql.$extrawhere;
        
        if ($field != NULL) {
            $sql .=" ORDER BY $field $order";
        } else {
            $sql .=" ORDER BY a.sitestate, a.siteref";
        }
          
        if($size != NULL) {
            $sql .=" LIMIT $start, $size";
        }
        
        $query = $this->db->query($sql);
        
        $data = array(
            'trows' => $trows, 
            'data' => $query->result_array()
        );
        
        return $data;
    }
    
    /**
    * This function Get site data
    *  @param integer $contractid - contractid
    *  @param date $startdate - startdate
    *  @param date $enddate - enddate
    *  @param array $labelids - array of labelids
    * 
    * @return array
    */
    public function getSiteData($contractid, $startdate, $enddate, $labelids) {
        
        $this->db->select("s.labelid, s.id, s.servicetypeid, color, ss.textcolor, siteref, sitesuburb, sitestate, startdate, enddate, ss.code, t.name, s.statusid, t.icon, ss.name AS statusname");
        $this->db->from('addresslabel a');
        $this->db->join('con_schedule s', 's.labelid=a.labelid', 'inner');
        $this->db->join('con_service_type t', 's.servicetypeid=t.id', 'inner');
        $this->db->join('con_service_status ss', 's.statusid=ss.id', 'inner');
        $this->db->where_in('s.labelid', $labelids);
        if($contractid != '') {
            $this->db->where('s.contractid', $contractid);
        }
        $this->db->where("(((s.startdate BETWEEN '".$startdate."' and '".$enddate."') OR (s.enddate BETWEEN '".$startdate."' and '".$enddate."')) OR (('".$startdate."' BETWEEN s.startdate and s.enddate) OR ('".$enddate."' BETWEEN s.startdate and s.enddate)))");
        //return $this->db->get_compiled_select();
        return $this->db->get()->result_array();
    }
	
	/**
    * This function Get a Technicians
    *  @param integer $customerid - customerid
    *  @param date $fromdate - fromdate
    *  @param date $todate - todate
    * @return array 
    */
    public function getTechnicians($customerid, $fromdate, $todate) {
        
        $sql = "SELECT DISTINCT CONCAT(s.companyname,' (',d.userid,')') AS technician FROM diary d"
                . " INNER JOIN jobs j ON j.jobid=d.jobid"
                . " INNER JOIN users u ON d.userid=u.userid"
                . " INNER JOIN customer s ON u.email=s.customerid"
                . " WHERE d.dte >= :fromdate and d.dte <= :toddate AND"
                . " j.customerid= :customerid AND IFNULL(j.quoterqd,'') <> 'on' ORDER BY technician";
        
        $this->db->select(array("DISTINCT CONCAT(s.companyname,' (',d.userid,')') AS technician, d.userid"));
        $this->db->from('diary d');
        $this->db->join('jobs j', 'j.jobid=d.jobid', 'inner');
        $this->db->join('users u', 'd.userid=u.userid', 'inner');
        $this->db->join('customer s', 'u.email=s.customerid', 'inner');
        $this->db->where("IFNULL(j.quoterqd,'') <> 'on'");
        $this->db->where('j.customerid', $customerid);
        $this->db->where('d.dte >=', $fromdate);
        $this->db->where('d.dte <=', $todate);
        $this->db->order_by('technician');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
    * This function Get a Technicians
    *  @param integer $customerid - customerid
    *  @param date $fromdate - fromdate
    *  @param date $todate - todate
    *  @param date $state - state
    * @return array 
    */
    public function getLabourDashboardSites($customerid, $fromdate, $todate, $state = NULL) {
        
        $sql = "SELECT DISTINCT a.labelid,CONCAT(a.sitesuburb,' (',a.siteline2,')') AS site"
                . " FROM addresslabel a"
                . " INNER JOIN jobs j ON j.labelid=a.labelid"
                . " INNER JOIN diary d ON d.jobid=j.jobid"
                . " WHERE d.dte>= :fromdate AND d.dte <= :todate AND a.sitestate = :state"
                . " AND j.customerid= :customerid ORDER BY a.sitesuburb";
        
        $this->db->select(array("DISTINCT a.labelid, CONCAT(a.sitesuburb,' (',a.siteline2,')') AS site"));
        $this->db->from('addresslabel a');
        $this->db->join('jobs j', 'j.labelid=a.labelid', 'inner');
        $this->db->join('diary d', 'd.jobid=j.jobid', 'inner');
        if($state != NULL) {
            $this->db->where('a.sitestate', $state);  
        }
        $this->db->where('j.customerid', $customerid);
        $this->db->where('d.dte >=', $fromdate);
        $this->db->where('d.dte <=', $todate);
        $this->db->order_by('a.sitesuburb');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
     /**
    * This function use for getting Customers List
    * @param integer $customerid - Logged User customerid
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param date $fromdate - fromdate
    * @param date $todate - todate
    * @param string $groupby - it is use As By Site|By Job|By Technician
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getLabourDashboardData($customerid, $size, $start, $field, $order, $fromdate, $todate, $groupby, $params) {

         $sql = "SELECT d.apptid,j.contractid,j.iscontract,d.jobid,a.labelid,a.siteline2,a.siteref,a.sitestate AS state,"
                 . " c.firstname AS FM, CONCAT(s.companyname,' (',d.userid,')') AS technician, d.duration AS hours,"
                 . " te.lm_te_bill_rate AS rate, d.duration*te.lm_te_bill_rate AS billamt,j.materialcosts"
                 . " FROM diary d"
                 . " INNER JOIN jobs j ON d.jobid=j.jobid"
                 . " INNER JOIN lm_timeentry te ON d.apptid=te.lm_te_apptid"
                 . " INNER JOIN users u ON d.userid=u.userid"
                 . " INNER JOIN customer s ON u.email=s.customerid"
                 . " LEFT JOIN con_technician t ON d.userid=t.userid"
                 . " LEFT JOIN addresslabel a ON j.labelid=a.labelid"
                 . " LEFT JOIN contact c ON j.contactid=c.contactid"
                 . " WHERE d.dte >=  :fromdate AND d.dte <= :todate"
                 . " AND d.completed = 'on' AND IFNULL(j.recurring,'') != 'on'"
                 . " AND IFNULL(j.quoterqd,'') != 'on'"
                 . " AND j.customerid= :customerid"
                 . " GROUP BY d.userid, d.jobid";
        
        $this->db->select("d.apptid");
        $this->db->from('diary d');
        
        $this->db->join('jobs j', 'd.jobid=j.jobid', 'inner');
        $this->db->join('lm_timeentry te', 'd.apptid=te.lm_te_apptid', 'inner');
        $this->db->join('users u', 'd.userid=u.userid', 'inner');
        $this->db->join('customer s', 'u.email=s.customerid', 'inner');
        $this->db->join('con_technician t', 'd.userid=t.userid', 'left');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left');
        $this->db->join('contact c', 'j.contactid=c.contactid', 'left');

         $this->db->where('j.customerid', $customerid);
        $this->db->where('d.completed', 'on');
        $this->db->where("IFNULL(j.recurring,'') != 'on'");
        $this->db->where("IFNULL(j.quoterqd,'') != 'on'");
        $this->db->where('d.dte >=', $fromdate);
        $this->db->where('d.dte <=', $todate);
         
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
        
        if($groupby == 'bysite') {
            $this->db->group_by('a.labelid');
        } else if($groupby == 'byjob') {
            $this->db->group_by('d.jobid');
        } else if($groupby == 'bytech') {
            $this->db->group_by('d.userid');
        }
        
       
        $trows = count($this->db->get()->result_array());
                            
                            
        $this->db->select(array("d.apptid, j.contractid, j.iscontract, d.jobid, COUNT(d.jobid) AS jobs, a.labelid, a.siteline2, a.siteref,"
            . " a.sitestate AS state, c.firstname AS fm, CONCAT(s.companyname,' (',d.userid,')') AS technician,"
            . " d.duration AS hours, te.lm_te_bill_rate AS rate, d.duration*te.lm_te_bill_rate AS billamt, j.materialcosts"));
        
        $this->db->from('diary d');
        
        $this->db->join('jobs j', 'd.jobid=j.jobid', 'inner');
        $this->db->join('lm_timeentry te', 'd.apptid=te.lm_te_apptid', 'inner');
        $this->db->join('users u', 'd.userid=u.userid', 'inner');
        $this->db->join('customer s', 'u.email=s.customerid', 'inner');
        $this->db->join('con_technician t', 'd.userid=t.userid', 'left');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'left');
        $this->db->join('contact c', 'j.contactid=c.contactid', 'left');

         $this->db->where('j.customerid', $customerid);
        $this->db->where('d.completed', 'on');
        $this->db->where("IFNULL(j.recurring,'') != 'on'");
        $this->db->where("IFNULL(j.quoterqd,'') != 'on'");
        $this->db->where('d.dte >=', $fromdate);
        $this->db->where('d.dte <=', $todate);
       
         
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
        
        if($groupby == 'bysite') {
            $this->db->group_by('a.labelid');
        } else if($groupby == 'byjob') {
            $this->db->group_by('d.jobid');
        } else if($groupby == 'bytech') {
            $this->db->group_by('d.userid');
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        
        if($size != NULL) {
            $this->db->limit($size, $start);
        }
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Labour Dashboard Grid Data Query : '. $this->db->last_query());
        
        return $data;
    }
    
    /**
    * @desc This function Get Labour Dashboard Contracts
    * @param integer $customerid - Logged User customerid
    * 
    * @return array - 
    */
    public function getDashboardContracts($customerid) { 
        
        $sql = "SELECT id,name FROM con_contract WHERE customerid=$customerid";
//        
//        $sql = "SELECT id, name FROM con_contract cc"
//                . " INNER JOIN jobs j ON cc.id=j.contractid"
//                . " WHERE  j.iscontract='on'"
//                . " UNION SELECT id, name FROM con_contract cc"
//                . " LEFT JOIN jobs j ON cc.id=j.contractid"
//                . " WHERE j.contractid=0 OR j.contractid IS NULL";
//        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
     
}


/* End of file ContractorClass.php */