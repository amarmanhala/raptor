<?php 
/**
 * Customer Libraries Class
 *
 * This is a Customer class for Customer Opration   
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php'); 

/**
 * Customer Libraries Class
 *
 * This is a Customer class for Customer Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer
 * @filesource          CustomerClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class CustomerClass extends MY_Model {
   
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
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'CustomerClass');
        $this->sharedClass = new SharedClass();
    }

    
    /**
    * This function use for getting Customers List
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getCustomers($size, $start, $field, $order, $filter, $params) {

        $this->db->select("s.customerid");
                            
        $this->db->from('customer s');
        $this->db->join('se_trade t', 's.tradeid=t.id', 'left');
        $this->db->join('cp_supplier_type st', 's.typeid=st.id', 'left');
        //$this->db->join('contact c', 's.customerid=c.customerid and c.primarycontact=1', 'left');
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
            $this->db->where("(s.companyname LIKE '%".$this->db->escape_str($filter)."%' or s.tradingname LIKE '%".$this->db->escape_str($filter)."%' or s.email LIKE '%".$this->db->escape_str($filter)."%' or s.mailsuburb LIKE '%".$this->db->escape_str($filter)."%' or s.postcode LIKE '%".$this->db->escape_str($filter)."%' or s.state LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $this->db->group_by('s.customerid');
        $trows = $this->db->count_all_results();
                             
        $this->db->select(array("s.customerid, companyname, st.name as typename, st.code as typecode, t.se_trade_name, s.phone, s.email, s.shipsuburb, s.shipstate, GROUP_CONCAT(TRIM(CONCAT(c.firstname,' ',c.surname))) AS primarycontact, GROUP_CONCAT(c.contactid) AS primarycontactid, s.isactive, s.hasetpaccess, s.currentbalance"));
        $this->db->from('customer s');
        $this->db->join('se_trade t', 's.tradeid=t.id', 'left');
        $this->db->join('cp_supplier_type st', 's.typeid=st.id', 'left');
        $this->db->join('contact c', 's.customerid=c.customerid and c.primarycontact=1', 'left');
        
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
            $this->db->where("(s.companyname LIKE '%".$this->db->escape_str($filter)."%' or s.tradingname LIKE '%".$this->db->escape_str($filter)."%' or s.email LIKE '%".$this->db->escape_str($filter)."%' or s.mailsuburb LIKE '%".$this->db->escape_str($filter)."%' or s.postcode LIKE '%".$this->db->escape_str($filter)."%' or s.state LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL) {
            $this->db->limit($size, $start);
        }
        
        $this->db->group_by('s.customerid');
        if ($field != '') {
            $this->db->order_by($field, $order);
        }

        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Customer Data Query : '. $this->db->last_query());
        
        return $data;
    }
    
     /**
    * This function use for Insert new Supplier
    * @param array $params - the $params is array of supplierdata and contactid(LoggedUser)
    * @return array
    */
       
    public function insertCustomer($params)
    {
        //1 - load multiple models
        require_once('chain/InsertCustomerChain.php');
        
        //2 - initialize instances
        $InsertCustomerChain = new InsertCustomerChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Customer  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertCustomerData = $params['insertCustomerData'];
        
                            
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData, 
            'insertCustomerData'   => $insertCustomerData 
        );
 
        $InsertCustomerChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertCustomerChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for create Batch Invoices
    * @param array $params - the params is array of Invoice detail and contactid(LoggedUser)
    * @return array
    */
    public function updateCustomer($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateCustomerChain.php'); 
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
         //2 - initialize instances
        $UpdateCustomerChain = new UpdateCustomerChain(); 
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected 
       $UpdateCustomerChain->setSuccessor($EditLogChain); 
                            
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $customerData = $this->getCustomerById($params['customerid']);
        
        $updateCustomerData = $params['updateCustomerData']; 
        
        $editLogData=array();
        foreach ($updateCustomerData as $key => $value) {
            if (trim($customerData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'customer', 
                    'recordid'  => $params['customerid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $customerData[$key], 
                    'newvalue'  => $value
                );
            }
        }
         
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'updateCustomerData'=> $updateCustomerData, 
            'editLogData'   => $editLogData
        );
                            
        $UpdateCustomerChain->handleRequest($request);

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
       
    public function deleteCustomer($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteCustomerChain.php');
        
        //2 - initialize instances
        $DeleteCustomerChain = new DeleteCustomerChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Customer : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteCustomerChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteCustomerChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for getting Contact
    * @param integer $customerid - customerid for selected customer
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @param string $extraWhere - it is use for Extra Where
    * @return array 
    */
    public function getContacts($customerid, $size, $start, $field, $order, $filter, $params = array(), $extraWhere = '') {
        
      
        $this->db->select("c.contactid");
        $this->db->from('contact c');
        $this->db->join('contact c2', 'c.bossid=c2.contactid', 'left');
        $this->db->join('se_trade t', 'c.tradeid=t.id', 'left');
        $this->db->where('c.customerid', $customerid);
        
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
        if ($extraWhere != '') {
            $this->db->where($extraWhere);
        }
        if ($filter != '') {
            $this->db->where("(c.firstname LIKE '%".$this->db->escape_str($filter)."%' or c.surname LIKE '%".$this->db->escape_str($filter)."%' or c.suburb LIKE '%".$this->db->escape_str($filter)."%' or c.state LIKE '%".$this->db->escape_str($filter)."%' or c.postcode LIKE '%".$this->db->escape_str($filter)."%' or c.email LIKE '%".$this->db->escape_str($filter)."%' or c.mobile LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
 
        $this->db->select(array("c.*, TRIM(CONCAT(c.firstname,' ',c.surname)) as contactname, TRIM(CONCAT(c2.firstname,' ',c2.surname)) AS reportsto, IF(c.active=1,'Active',IF(c.cp_invitesendtime IS NULL, 'Inactive','Invited')) AS status, t.se_trade_name AS trade"));
        $this->db->from('contact c'); 
        $this->db->join('contact c2', 'c.bossid=c2.contactid', 'left');
        $this->db->join('se_trade t', 'c.tradeid=t.id', 'left');
        $this->db->where('c.customerid', $customerid);

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
        if ($extraWhere != '') {
            $this->db->where($extraWhere);
        }
       if ($filter != '') {
            $this->db->where("(c.firstname LIKE '%".$this->db->escape_str($filter)."%' or c.surname LIKE '%".$this->db->escape_str($filter)."%' or c.suburb LIKE '%".$this->db->escape_str($filter)."%' or c.state LIKE '%".$this->db->escape_str($filter)."%' or c.postcode LIKE '%".$this->db->escape_str($filter)."%' or c.email LIKE '%".$this->db->escape_str($filter)."%' or c.mobile LIKE '%".$this->db->escape_str($filter)."%')");
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
        
        $this->LogClass->log('Get Contact Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
    /**
    * This function use for Insert new contact
    * @param array $params - the $contactParams is array of contactdata and contactid(LoggedUser)
    * @return array
    */
       
    public function insertContact($params)
    {
        //1 - load multiple models
        require_once('chain/InsertContactChain.php');
        
        //2 - initialize instances
        $InsertContactChain = new InsertContactChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Contact  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertContactData = $params['insertContactData'];
        $insertContactData['addedby'] = $params['logged_contactid'];
        $mailGroupData = array();
        if(isset($params['mailGroupData'])){
             $mailGroupData = $params['mailGroupData'];
        }
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData, 
            'insertContactData' => $insertContactData,
            'mailGroupData'     => $mailGroupData
        );
 
        $InsertContactChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertContactChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update contact
    * @param array $params - the $contactParams is array of contactdata and contactid(LoggedUser)
    * @return array
    */
       
    public function updateContact($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateContactChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
        //2 - initialize instances
        $UpdateContactChain = new UpdateContactChain();
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected
        $UpdateContactChain->setSuccessor($EditLogChain); 
        
        
        //4 - start the process
        $this->LogClass->log('Update Contact  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $contactData = $this->getContactById($params['contactid']); 
        
        $updateContactData = $params['updateContactData'];
       
 
        $editLogData=array();
        foreach ($updateContactData as $key => $value) {
            if (trim($contactData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'contact' , 
                    'recordid'  => $params['contactid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $contactData[$key], 
                    'newvalue'  => $value
                );
            }
        }
         
        
        $updateContactData['editedby'] = $params['logged_contactid'];
        
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData, 
            'contactData'       => $contactData,
            'updateContactData' => $updateContactData, 
            'editLogData'       => $editLogData
        );
        if(isset($params['mailGroupData'])){
            $request['mailGroupData'] = $params['mailGroupData'];
        }
        $UpdateContactChain->handleRequest($request);

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
       
    public function deleteContact($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteContactChain.php');
        
        //2 - initialize instances
        $DeleteContactChain = new DeleteContactChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Supplier : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteContactChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteContactChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function for update primary contact
    
    * @param integer $customerid - customerid of edited contact
    * @param integer $contactid - contactid of edited contact
    * @return boolean
    */
    public function updatePrimaryContact($customerid, $contactid) {
        $this->updateRecords(array("customerid" => $customerid), array('primarycontact' => 0), 'contact');
        $this->updateRecords(array("contactid" => $contactid), array('primarycontact' => 1), 'contact');
        return TRUE;
    }
    
    /**
    * This function for update primary contact
    
    * @param integer $customerid - customerid of edited contact
    * @param integer $contactid - contactid of edited contact
    * @return boolean
    */
    public function updateETPAccessLevelContact($customerid, $contactid) {
        $this->updateRecords(array("customerid" => $customerid), array('etp_accesslevel' => 2), 'contact');
        $this->updateRecords(array("contactid" => $contactid), array('etp_accesslevel' => 3), 'contact');
        return TRUE;
    }
    
    
    /**
    * This function use for getting Site Addresses
    * @param integer $customerid - customerid for selected customer
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getSiteAddresses($customerid, $size, $start, $field, $order, $filter = '', $params = array()) {
        
        $this->db->select("a.labelid");
        $this->db->from('addresslabel a');
        $this->db->join('contact cfm', 'a.contactid = cfm.contactid', 'left');
        $this->db->join('contact csite', 'a.contactid = csite.contactid', 'left');
        $this->db->where('a.customerid', $customerid);
        
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
            $this->db->where("(a.siteref LIKE '%".$this->db->escape_str($filter)."%' or a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or CONCAT(cfm.firstname, ' ', cfm.surname) LIKE '%".$this->db->escape_str($filter)."%' or CONCAT(csite.firstname, ' ', csite.surname) LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
            
        $this->db->select(array("a.*, TRIM(CONCAT(cfm.firstname,' ',cfm.surname)) AS sitefm, TRIM(CONCAT(csite.firstname,' ',csite.surname)) AS sitecontact, CONCAT(IFNULL(a.siteline1,''),'<br>', IFNULL(a.siteline2,''),'<br>',IFNULL(a.sitesuburb,''),' ',IFNULL(a.sitestate,''),' ',IFNULL(a.sitepostcode,'')) as site"));
        $this->db->from('addresslabel a');
        $this->db->join('contact cfm', 'a.contactid = cfm.contactid', 'left');
        $this->db->join('contact csite', 'a.sitecontactid = csite.contactid', 'left');
        $this->db->where('a.customerid', $customerid);
        
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
            $this->db->where("(a.siteref LIKE '%".$this->db->escape_str($filter)."%' or a.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or a.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or a.sitestate LIKE '%".$this->db->escape_str($filter)."%' or a.sitepostcode LIKE '%".$this->db->escape_str($filter)."%' or CONCAT(cfm.firstname, ' ', cfm.surname) LIKE '%".$this->db->escape_str($filter)."%' or CONCAT(csite.firstname, ' ', csite.surname) LIKE '%".$this->db->escape_str($filter)."%')");
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
        
        $this->LogClass->log('Get Customer Addresses Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
     
    
     /**
    * This function use for Insert new address
    * @param array $params - the $params is array of addressdata and contactid(LoggedUser)
    * @return array
    */
       
    public function insertAddress($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAddressChain.php');
        
        //2 - initialize instances
        $InsertAddressChain = new InsertAddressChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Address  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertAddressData = $params['insertAddressData'];
        
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData, 
            'insertAddressData' => $insertAddressData
        );
 
        $InsertAddressChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAddressChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update address
    * @param array $addressParams - the $addressParams is array of addressdata and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateAddress($addressParams)
    {
        //1 - load multiple models
        require_once('chain/UpdateAddressChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
        //2 - initialize instances
        $UpdateAddressChain = new UpdateAddressChain();
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected
        $UpdateAddressChain->setSuccessor($EditLogChain); 
        
        
        //4 - start the process
        $this->LogClass->log('Update Address  : ');
        $this->LogClass->log($addressParams);
        $loggedUserData= $this->sharedClass->getLoggedUser($addressParams['logged_contactid']);
        $addressData = $this->getAddressById($addressParams['labelid']); 
        $updateData = $addressParams['updateData'];
 
        $editLogData=array();
        foreach ($updateData as $key => $value) {
            if (trim($addressData[$key]) != trim($value)) {
 
                $editLogData[] = array(
                    'tablename' => 'addresslabel' , 
                    'recordid'  => $addressParams['labelid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid' => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $addressData[$key], 
                    'newvalue'  => $value
                );
            }
        }
         
        $request = array(
            'addressParams' => $addressParams, 
            'userData'      => $loggedUserData, 
            'addressData'   => $addressData,
            'updateData'    => $updateData,
            'editLogData'   => $editLogData
        );
        
        $UpdateAddressChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
   
    
     /**
    * This function Get a address data
    * @param integer $labelid - the name of $labelid for getting particular address data
    * @return array 
    */
    public function getAddressById($labelid) {
         
        if (! isset($labelid) ){
            throw new exception('labelid value must be supplied.');
        }

        $this->db->select('a.*, c.phone, c.mobile, c.email');
               $this->db->where('a.labelid', $labelid);
               $this->db->join('contact c', 'c.contactid = a.contactid', 'left');
               $this->db->from('addresslabel a');

        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get address Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     /**
    * This function Get a address data
    * @param integer $customerid - customerid for logged customer
    * @param integer $siteref - the name of $labelid for getting particular address data
    * @return array 
    */
    public function getAddressBySiteRef($customerid, $siteref) {
         
      
        if($siteref != ''){
            $this->db->select('a.*, c.phone, c.mobile, c.email');
            $this->db->from('addresslabel a');
            $this->db->join('contact c', 'c.contactid = a.contactid', 'left');
            $this->db->where('a.siteref', $siteref);
            $this->db->where('a.customerid', $customerid); 

            $data = $this->db->get()->row_array();

            $this->LogClass->log('Get address Data Query : '. $this->db->last_query());
        }
        else{
            $data = array();
        }
        return $data;
        
    } 
    /**
    * This function Get a Address list
    * @param array $queryParams - parameters for select query
    * @return array 
    */
    public function getAddressByParams($queryParams) {

        $this->db->select('*');
        $this->db->from('addresslabel');
        $this->db->where($queryParams);  
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
     /**
    * This function use for getting Site Addresses
    * @param integer $customerid - customerid for logged customer
    * @param integer $supplierid - supplierid for selected supplier
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getSupplierSites($customerid, $supplierid, $size, $start, $field, $order, $filter = '', $params = array()) {
        
        $this->db->select("sa.id");
        $this->db->from('cp_supplier_address sa');
        $this->db->join('addresslabel a', 'sa.labelid=a.labelid', 'inner');
        $this->db->join('contact cfm', 'a.contactid = cfm.contactid', 'left');
        $this->db->join('contact csite', 'a.contactid = csite.contactid', 'left');
        $this->db->where('a.customerid', $customerid);
        $this->db->where('sa.supplierid', $supplierid);
       
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
            
        $this->db->select(array("sa.*, a.labelid, a.siteref, a.siteline2 ,a.sitesuburb, a.sitestate, a.sitepostcode, TRIM(CONCAT(cfm.firstname,' ',cfm.surname)) AS sitefm, TRIM(CONCAT(csite.firstname,' ',csite.surname)) AS sitecontact, a.latitude_decimal, a.longitude_decimal, CONCAT(IFNULL(a.siteline1,''),'<br>', IFNULL(a.siteline2,''),'<br>',IFNULL(a.sitesuburb,''),' ',IFNULL(a.sitestate,''),' ',IFNULL(a.sitepostcode,'')) as site"));
        $this->db->from('cp_supplier_address sa');
        $this->db->join('addresslabel a', 'sa.labelid=a.labelid', 'inner');
        $this->db->join('contact cfm', 'a.contactid = cfm.contactid', 'left');
        $this->db->join('contact csite', 'a.contactid = csite.contactid', 'left');
        $this->db->where('a.customerid', $customerid);
        $this->db->where('sa.supplierid', $supplierid);
        
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
       
        $this->LogClass->log('Get Supplier Sites Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
     /**
    * This function use for Insert Supplier Site
    * @param array $params - the $params is array of SupplierSite Data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertSupplierSite($params)
    {
        //1 - load multiple models
        require_once('chain/InsertSupplierSiteChain.php'); 
        
        //2 - initialize instances
        $InsertSupplierSiteChain = new InsertSupplierSiteChain(); 
        
        //3 - get the parts connected 
         
        //4 - start the process
        $this->LogClass->log('Insert Site  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertSiteData'];
  
         
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData,  
            'insertSiteData' => $insertData 
        );
        
        $InsertSupplierSiteChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertSupplierSiteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Supplier Site
    * @param array $params - the $params is array of site and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateSupplierSite($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateSupplierSiteChain.php'); 
        
        //2 - initialize instances
        $UpdateSupplierSiteChain = new UpdateSupplierSiteChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update Supplier Site : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateSiteData = $params['updateSiteData'];
                            
         
        $request = array(
            'params'        => $params, 
            'userData'       => $loggedUserData,  
            'updateSiteData' => $updateSiteData 
        );
        
        $UpdateSupplierSiteChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateSupplierSiteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete SupplierSite
    * @param array $params - the $params is array of Address and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteSupplierSite($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteSupplierSiteChain.php');
        
        //2 - initialize instances
        $DeleteSupplierSiteChain = new DeleteSupplierSiteChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Supplier site : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteSupplierSiteChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteSupplierSiteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    
     /**
    * This function Get a Address attribute data from addresslabel_attribute with given parameters
    * @param integer $supplierid 
    * @param integer $labelid
    * @param integer $siteid
    * @return array 
    */
    public function checkSupplierSite($supplierid, $labelid, $siteid = 0) {
        
        $this->db->select('*');
        $this->db->from('cp_supplier_address');
        $this->db->where('supplierid', $supplierid);
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
    * This function Get a contract data from contact with given parameters
    * @param integer $supplierid  
    * @return array 
    */
    public function getSupplierSiteLabelids($supplierid) {
             
        $labelids = array();                     
        $this->db->select('labelid');
        $this->db->from('cp_supplier_address');
        $this->db->where('supplierid', $supplierid);
        $data =  $this->db->get()->result_array();
        foreach ($data as $key => $value) {
            $labelids[] = $value['labelid'];
        }           
        return $labelids;
        
    }
    
    /**
    * This function use for getting Address Attributes Values
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getAddressAttributesValues($size, $start, $field, $order, $params) {
        
        $sql= "SELECT NAME,IF(value_int IS NULL,value_string,value_int) AS VALUE,aav.status"
                . " FROM addresslabel_attribute_value aav"
                . " INNER JOIN `addresslabel_attribute` aa ON aa.id=aav.`attributeid`"
                . " WHERE labelid = :labelid ORDER BY aa.name ";
        
        $this->db->select("aav.id");
        $this->db->from('addresslabel_attribute_value aav');
        $this->db->join('addresslabel_attribute aa', 'aa.id=aav.attributeid', 'inner');
        
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
            
        $this->db->select(array("aav.id, aa.name, IF(aav.value_int IS NULL, value_string, value_int) AS value, IF(aav.value_int IS NULL, 'string', 'int') AS type, aav.status, aav.labelid"));
        $this->db->from('addresslabel_attribute_value aav');
        $this->db->join('addresslabel_attribute aa', 'aa.id=aav.attributeid', 'inner');
        
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
            'data' => $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Site Address Attributes Values  Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
  
    /**
    * This function use for create address attribute value
    * @param array $attributeParams - the $attributeParams is array of attribute data and contactid(LoggedUser)
    * @return array
    */
       
    public function createAddressAttributeValue($attributeParams)
    {
        //1 - load multiple models
        require_once('chain/CreateAddressAttributeValueChain.php');
        
        //2 - initialize instances
        $CreateAddressAttributeValueChain = new CreateAddressAttributeValueChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Create Address Attribute Value : ');
        $this->LogClass->log($attributeParams);
        $loggedUserData= $this->sharedClass->getLoggedUser($attributeParams['logged_contactid']);
        
        $attributeData = $attributeParams['attributeData'];
        
        $request = array(
            'attributeParams' => $attributeParams, 
            'userData'      => $loggedUserData, 
            'attributeData'   => $attributeData
        );
 
        $CreateAddressAttributeValueChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $CreateAddressAttributeValueChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update Address Attribute Value
    * @param array $attributeParams - the $attributeParams is array of attribute data and contactid(LoggedUser)
    * @return array
    */
       
    public function updateAddressAttributeValue($attributeParams)
    {
        //1 - load multiple models
        require_once('chain/updateAddressAttributeValueChain.php');
        
        //2 - initialize instances
        $updateAddressAttributeValueChain = new updateAddressAttributeValueChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Update Address Attribute Value  : ');
        $this->LogClass->log($attributeParams);
        
        $updateData = $attributeParams['updateData'];
         
        $request = array(
            'attributeParams' => $attributeParams, 
            'updateData'    => $updateData
        );
        
        $updateAddressAttributeValueChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $updateAddressAttributeValueChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete Address Attribute Value
    * @param array $attributeParams - the $attributeParams is array of attribute data and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteAddressAttributeValue($attributeParams)
    {
        //1 - load multiple models
        require_once('chain/deleteAddressAttributeValueChain.php');
        
        //2 - initialize instances
        $deleteAddressAttributeValueChain = new deleteAddressAttributeValueChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Address Attribute Value  : ');
        $this->LogClass->log($attributeParams);
         
        $request = array(
            'attributeParams' => $attributeParams
        );
        
        $deleteAddressAttributeValueChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $deleteAddressAttributeValueChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a Address attribute data from addresslabel_attribute with given parameters
    * @param integer $attributeid
    * @param integer $labelid
    * @return array 
    */
    public function getAddressAttributeValueByAttributeId($attributeid, $labelid = 0) {
        
        $this->db->select('*');
        $this->db->from('addresslabel_attribute_value');
        $this->db->where('attributeid', $attributeid);
 
	if($labelid != NULL && $labelid != 0){
            $this->db->where('labelid', $labelid);
        }
        
	$data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get Address Attribute Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for Address Attributes
    * @param integer $customerid - customerid for selected customer
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getAddressAttributes($customerid, $size, $start, $field, $order, $filter = '', $params = array()) {
        
        $customerids = array(0, $customerid);
        $this->db->select('aa.id');
        $this->db->from('addresslabel_attribute aa');
        $this->db->join('addresslabel_attribute_type aat', 'aa.attributetypeid = aat.id', 'inner');
        
        $this->db->where_in('customerid', $customerids);
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
            $this->db->where("(aa.name LIKE '%".$this->db->escape_str($filter)."%' or aa.caption LIKE '%".$this->db->escape_str($filter)."%')");
        }
        $trows = $this->db->count_all_results();
            
        $this->db->select('aa.*, aat.name AS type');
        $this->db->from('addresslabel_attribute aa');
        $this->db->join('addresslabel_attribute_type aat', 'aa.attributetypeid = aat.id', 'inner');
        
        $this->db->where_in('customerid', $customerids);
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
            $this->db->where("(aa.name LIKE '%".$this->db->escape_str($filter)."%' or aa.caption LIKE '%".$this->db->escape_str($filter)."%')");
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
        
        $this->LogClass->log('Get Address Attributes Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     /**
    * This function use for Insert Address Attribute
    * @param array $params - the $params is array of Attribute Data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAddressAttribute($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAddressAttributeChain.php'); 
        
        //2 - initialize instances
        $InsertAddressAttributeChain = new InsertAddressAttributeChain(); 
        
        //3 - get the parts connected 
         
        //4 - start the process
        $this->LogClass->log('Insert Address Attribute  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['attributeData'];
  
         
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData,  
            'attributeData' => $insertData 
        );
        
        $InsertAddressAttributeChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAddressAttributeChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update GLCodes
    * @param array $params - the $params is array of GlCode and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateAddressAttribute($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateAddressAttributeChain.php'); 
        
        //2 - initialize instances
        $UpdateAddressAttributeChain = new UpdateAddressAttributeChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update Address Attribute : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateData = $params['attributeData'];
 
         
         
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData,  
            'attributeData' => $updateData 
        );
        
        $UpdateAddressAttributeChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateAddressAttributeChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete Address Attribute
    * @param array $params - the $params is array of Address Attribute and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteAddressAttribute($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteAddressAttributeChain.php');
        
        //2 - initialize instances
        $DeleteAddressAttributeChain = new DeleteAddressAttributeChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Address Attribute : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteAddressAttributeChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteAddressAttributeChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
   
    /**
    * This function Get a Address attribute data from addresslabel_attribute with given parameters
    * @param integer $customerid - the name of $customerid for logged user
    * @param integer $id
    * @return array 
    */
    public function getAddressAttributeById($customerid, $id) {
        
        $this->db->select('*');
        $this->db->from('addresslabel_attribute');
        $this->db->where('customerid', $customerid);
        $this->db->where('id', $id); 
	 
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Address Attribute Query : '. $this->db->last_query());
        
        return $data;
        
    }
   
   
    /**
    * This function Get a Address attribute data from addresslabel_attribute with given parameters
    * @param integer $customerid - the name of $customerid for logged user
    * @param string $name
    * @param integer $id
    * @return array 
    */
    public function checkAddressAttributeName($customerid, $name, $id =0 ) {
        
        $this->db->select('*');
        $this->db->from('addresslabel_attribute');
        $this->db->where('customerid', $customerid);
        $this->db->where('name', $name);
        if($id != 0 && $id != NULL){
            $this->db->where('id!=', $id); 
        }
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Address Attribute Query : '. $this->db->last_query());
        
        return $data;
        
    }
   
    /**
    * This function Get a customer data from customer with given parameters
    * @param integer $customerid - the name of $customerid for getting particular customer data
    * @return array 
    */
    public function getCustomerById($customerid) {
          
        if (! isset($customerid) ){
            throw new exception('customerid value must be supplied.');
        }
        
        $this->db->select(array("s.*, st.name as typename, st.code as typecode, t.se_trade_name, GROUP_CONCAT(TRIM(CONCAT(c.firstname,' ',c.surname))) AS primarycontact"));
        $this->db->from('customer s');
        $this->db->join('se_trade t', 's.tradeid=t.id', 'left');
        $this->db->join('cp_supplier_type st', 's.typeid=st.id', 'left');
        $this->db->join('contact c', 's.customerid=c.customerid and c.primarycontact=1', 'left');
        $this->db->where('s.customerid', $customerid); 
        $this->db->group_by('s.customerid');
                            
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Customer Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function Get a contact data from contact with given parameters
    * @param integer $contactid - the name of $contactid for getting particular contact data
    * @return array 
    */
    public function getContactById($contactid) {
           
	if (! isset($contactid) ){
            throw new exception('contactid value must be supplied.');
        }
  
	$this->db->select(array("c.*, CONCAT(c.firstname,' ',c.surname) as contactname"));
        $this->db->where('c.contactid', $contactid); 
        $this->db->from('contact c');
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contact Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
   
    
    /**
    * This function Get a contact data from contact with given parameters
    * @param integer $customerid - the name of $customerid for getting particular customer data
    * @param string $name - the name of $email for getting particular contact data
    * @return array 
    */
    public function getContactByName($customerid, $name) {
            
        
	 
	$this->db->select("*");
        $this->db->from('contact'); 
 	//$this->db->where("firstname='".$name."' or TRIM(CONCAT(firstname,' ',surname))='".$name."'");
        $this->db->where('firstname', $name);
        $this->db->where('customerid', $customerid); 
	
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contact Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    /**
    * This function Get a contact data from contact with given parameters
    * @param string $email - the name of $email for getting particular contact data
    * @return array 
    */
    public function getContactByEmail($email) {
            
        if (! isset($email) ){
            throw new exception('email value must be supplied.');
        }
	 
	$this->db->select("*");
 	$this->db->where('email', $email);
	$this->db->from('contact'); 
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get contact Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     
    /**
    * This function Get a contact list
    * @param array $queryParams - parameters for select query
    * @param mixed $fields - parameters for select query
    * @return array 
    */
    public function getContactsByParams($queryParams, $fields = '*') {

        $this->db->select($fields);
        $this->db->from('contact');
        $this->db->where($queryParams);  
        $this->db->order_by('firstname asc'); 
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    /**
    * This function Get sitefm list for autocomplete
    * 
    * @param integer $customerid selected customer ID
    * @param string $search - search keyword from autocomplete field
    * @return array - 
    */
    public function getCustomerSiteContact($customerid, $search = '') {
        
        $sql = "SELECT contactid, CONCAT(firstname,' ',surname) as sitecontact, phone, mobile, email "
                . " from contact "
                . " WHERE customerid=$customerid AND "
                . " role='site contact'";
        if($search != '') {
            $sql = $sql. " and CONCAT(firstname,' ',surname) like '%".$search."%' ";
        }
        $sql = $sql. " order by CONCAT(firstname,' ',surname)";
        $query = $this->db->query($sql);
        return $query->result_array();
       
    }
   
    /**
    * This function Get sitefm list for autocomplete
     * 
    * @param integer $customerid selected Customer Id
    * @param string $search - search keyword from autocomplete field
    * @return array - 
    */
   public function getCustomerSiteFM($customerid, $search = '') {
       
        $sql = "SELECT contactid, CONCAT(firstname,' ',surname) as sitefm, phone, mobile, email "
                . " from contact "
                . " WHERE customerid=$customerid AND "
                . " role='sitefm'";
        if($search != '') {
            $sql = $sql. " and CONCAT(firstname,' ',surname) like '%".$this->db->escape_str($search)."%' ";
        }
        $sql = $sql. " order by CONCAT(firstname,' ',surname)";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
   
     /**
    * This function Get sitefm list for autocomplete
     * 
    * @param integer $customerid selected Customer Id
    * @param string $search - search keyword from autocomplete field
    * @return array - 
    */
   public function getCustomerAddressLabelSiteFM($customerid, $search = '') {
        
        $sql = "SELECT DISTINCT a.contactid, CONCAT(b.firstname,' ',b.surname) as sitefm, b.phone, b.mobile, b.email "
                . " FROM addresslabel a LEFT JOIN contact b ON (a.contactid=b.contactid) "
                . " WHERE a.customerid=$customerid AND a.contactid > 0";
        if($search != '') {
            $sql = $sql. " and CONCAT(b.firstname,' ',b.surname) like '%".$this->db->escape_str($search)."%' ";
        }
        $sql = $sql. " order by CONCAT(b.firstname,' ',b.surname)";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /**
     * This function Get a allocates
     * @param integer $customerid - customerid
     * @param integer $not_contactid - contactid
     * @param string $search - search text
     * @return array
    */
    public function getCustomerContacts($customerid, $not_contactid = 0, $search = '') {

        $this->db->select(array("c.contactid, c.email, c.firstname, c.surname, CONCAT(c.firstname,' ',c.surname) as contactname, CONCAT(c.firstname,' ',c.surname) AS name, cust.companyname, c.position, c.phone, c.mobile"));
        $this->db->where('c.customerid', $customerid);
        
        
        if($not_contactid != 0 || $not_contactid != NULL){
             $this->db->where('c.contactid!=', $not_contactid);
        }
        if($search != '' || $search != NULL){
            $this->db->like("CONCAT(c.firstname, ' ', c.surname)", $this->db->escape_str($search)); 
        }
        $this->db->from('contact c');
        $this->db->join('customer cust', 'c.customerid =cust.customerid', 'inner');
        $this->db->order_by('c.firstname, c.surname');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    
    /**
     * This function Get Org Contacts
     * @param integer $customerid - customerid 
     * @return array
    */
    public function getOrgContacts($customerid) {

        $this->db->select(array("c.contactid, c.email, c.firstname, c.surname, CONCAT(c.firstname,' ',c.surname) as contactname, cust.companyname, c.position, c.phone, c.mobile, c.role, c.bossid, c.orgchart_color"));
        $this->db->where('c.customerid', $customerid);
        $this->db->where('c.show_on_orgchart', 1);
        $this->db->where('c.active', 1);
  
        $this->db->from('contact c');
        $this->db->join('customer cust', 'c.customerid =cust.customerid', 'inner');
        $this->db->order_by('c.firstname, c.surname');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    
    /**
    * This function use for get site lookup data
    * @param integer $customerid - customerid of selected customer
    * @param string $search - str use for for like query
    * @param string $contactemail
    * @return array
    */
    public function getCustomerSiteAddress($customerid, $search = '', $contactemail = '', $show_custom_attributes = FALSE, $sql_w = '', $show_activeonly = FALSE) {
		 
        
        $sql= "select a.*, con.mobile as sitefmmobile, con.phone as sitefmphone, If(con.mobile!= '',con.mobile,con.phone) as sitefmph, con.email as sitefmemail, TRIM(CONCAT(con.firstname, ' ',con.surname)) as sitefm, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as site, CONCAT(IFNULL(a.siteline1,''),'<br>', IFNULL(a.siteline2,''),'<br>',IFNULL(a.sitesuburb,''),' ',IFNULL(a.sitestate,''),' ',IFNULL(a.sitepostcode,'')) as address";
        if ($show_custom_attributes)
        {

            $sql .= ", CONCAT(c.value_int, ' ', c2.value_int, ' ', c3.value_string) as BE_BU_BUDescription, "
                . " c4.value_string as FunctionalLocation, c.value_int as BE, c2.value_int as BU, " 
                . " c3.value_string as BUDescription, c5.value_string AS siteref1, c6.value_string AS siteref2";
        }
        
        $sql .= " from addresslabel as a "
                . " left outer join contact as con on a.contactid = con.contactid ";
        
        if ($show_custom_attributes)
        {
		 
            $sql .= " LEFT JOIN addresslabel_attribute b ON (b.name='Building Entities') ";
            $sql .= " LEFT JOIN addresslabel_attribute_value c ON (b.id=c.attributeid AND a.labelid=c.labelid) ";
            $sql .= " LEFT JOIN addresslabel_attribute b2 ON (b2.name='Building Units') ";
            $sql .= " LEFT JOIN addresslabel_attribute_value c2 ON (b2.id=c2.attributeid AND a.labelid=c2.labelid) ";
            $sql .= " LEFT JOIN addresslabel_attribute b3 ON (b3.name='BU Description') ";
            $sql .= " LEFT JOIN addresslabel_attribute_value c3 ON (b3.id=c3.attributeid AND a.labelid=c3.labelid) ";
            $sql .= " LEFT JOIN addresslabel_attribute b4 ON (b4.name='Functional Location') ";
            $sql .= " LEFT JOIN addresslabel_attribute_value c4 ON (b4.id=c4.attributeid AND a.labelid=c4.labelid) " 
                 . "  LEFT JOIN addresslabel_attribute b5 ON (b5.name='siteref1') "
                 . "  LEFT JOIN addresslabel_attribute_value c5 ON (b5.id=c5.attributeid AND a.labelid=c5.labelid) "
                 . "  LEFT JOIN addresslabel_attribute b6 ON (b6.name='siteref2') "
                 . "  LEFT JOIN addresslabel_attribute_value c6 ON (b6.id=c6.attributeid AND a.labelid=c6.labelid) "; 
	}
        
        $sql .= " where a.siteline1 != '' and a.customerid = $customerid  ";
        
        if($search != '' || $search != NULL){
        
            $sql .= " AND (a.siteline1 like '%".$this->db->escape_str($search)."%' or a.siteline2 like '%".$this->db->escape_str($search)."%' or a.sitesuburb like '%".$this->db->escape_str($search)."%' or a.sitestate like '%".$this->db->escape_str($search)."%' or a.sitepostcode like '%".$this->db->escape_str($search)."%' )  ";
        }
        if($contactemail!="")
        {
            $sql .= "  and con.email='".$contactemail."' ";
        }
        if($show_activeonly){
            $sql .= "  and a.isactive=1 ";
        }
        $sql .= $sql_w;
        $sql .= " order by siteline1,sitestate,sitesuburb";
        
        if($search != '' || $search != NULL){
        
            $sql .= "  LIMIT 15 ";
        }
        $query = $this->db->query($sql);

        return $query->result_array();
    }
    
    /**
    * This function formatted addess 
    * @param integer $labelid - 
    * @return string - address
    */
    public function getFormattedSiteAddress($labelid) {
        $sql = "SELECT CONCAT(IFNULL(siteline1,''),'<br>', IFNULL(siteline2,''),'<br>',IFNULL(sitesuburb,''),' ',IFNULL(sitestate,''),' ',IFNULL(sitepostcode,'')) AS address
                   FROM addresslabel WHERE labelid = $labelid";
        $query = $this->db->query($sql);
        $address = $query->row_array();
        if(count($address) > 0) {
            $address = $address['address'];
        } else {
            $address = '';
        }
        return $address;
    }
    
     /**
     * getSubordinateEmails
     * @param string $email
     * @return string
     */
    public function getSubordinateEmails($email){
         $email = addslashes( $email);                                     	 
        $sql="SELECT getSubordinateEmails('$email') as subordinate_emails;";
        $query = $this->db->query($sql);
        $result=$query->row();
        $subordinate_emails= $result->subordinate_emails;

        $sql2 = "SELECT a.email FROM contact a INNER JOIN contact b ON a.contactid=b.impersonate 
                                    WHERE b.email= '$email'";
        $query = $this->db->query($sql2);
        if($query->num_rows() > 0)
        {
            $result=$query->row();
            $impersonate_email =  $result->email;
            $sql="SELECT getSubordinateEmails('$impersonate_email') as subordinate_emails;";
            $query = $this->db->query($sql);
            $result=$query->row();
            $impersonate_sub_emails= $result->subordinate_emails;

            if($subordinate_emails == "x" && $impersonate_subordinate_emails != "x"){
                $subordinate_emails = $impersonate_sub_emails;
            }
        }
        return $subordinate_emails; 

    }
   
    /**
     * get SiteFM By SiteContact email
     * @param string $sitecontact_email
     * @return array
     */
    public function getSiteFMBySiteContact($sitecontact_email){

        $this->db->select('b.labelid, c.contactid as fmcontactid, c.email as fmemail');
        $this->db->from('contact a'); 
        $this->db->join('addresslabel b', 'a.contactid=b.sitecontactid', 'inner');
        $this->db->join('contact c', 'b.contactid=c.contactid', 'inner');
        $this->db->where('a.email', $sitecontact_email); 
       
        return $this->db->get()->row();
         

    }
     
    /**
     * get Customer Sites By Role
     * @param int $customerid
     * @param int $contactid
     * @param string $uemails
     * @param string $role
     * @param int $siteid
     * @return array
     */
    public function getCustomerSitesByRole($customerid, $contactid, $uemails, $role, $siteid=0, $states = NULL) {

         $subordinate_emails = $this->getSubordinateEmails($uemails);
        $this->db->select(array("a.labelid as id,concat(a.siteline1,' ',a.siteline2,' ',a.sitesuburb,' ',a.sitestate) as name"));
        $this->db->from('addresslabel a');  
        $this->db->join('contact d', 'a.contactid=d.contactid', 'inner');
        $this->db->where('d.customerid', $customerid); 
       if($role == 'site contact')
        {
           $this->db->where('a.sitecontactid', $contactid); 
           
        }
        elseif ($role == 'sitefm') {
           
            $this->db->where(" (a.contactid=$contactid or FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        if($states != NULL)
        {
            if(is_array($states))
            {
                $this->db->where_in('a.sitestate', $states); 
               
            }
            else{
                $this->db->where('a.sitestate', $states); 
             
            }
        
        }
        if($siteid != 0)
        {
            $this->db->where('a.labelid', $siteid); 
         
        }
        return $this->db->get()->result_array();
        
        
    }
    
    /**
     * This function use for get self_subordinate_contact
     * 
     * @param integer $customerid
     * @param integer $contactid
     * @param string $subordinate_emails
     * @return array
     */
    public function getSelfSubordinateContact($customerid, $contactid, $subordinate_emails) {

        $this->db->select('contactid, firstname');
        $this->db->from('contact'); 
        $this->db->where('customerid', $customerid);
        $this->db->where("(contactid=$contactid or FIND_IN_SET(email, '".$this->db->escape_str($subordinate_emails)."'))");
       
        return $this->db->get()->result_array();
 
    }
    
    /**
     * get Customer Email Rule Field Value
     * @param int $customerid
     * @param string $eventcode
     * @param string $fieldname
     * @return string
     */
    public function getCustomerEmailRuleFieldValue($customerid, $eventcode, $fieldname){

        $this->db->select($fieldname);
        $this->db->from('customer_emailrule'); 
        $this->db->where('customerid', $customerid);
        $this->db->where('event_code', $eventcode);
        $this->db->where('is_active', 1);
        $result = $this->db->get()->row_array();
 
        if(count($result)>0){
            return $result[$fieldname];
        }
        else{
            return '';
        }
    }
   
     /**
     * get Contacts By MailGroup
     * @param int $customerid
     * @param string $mailgroupdesc
     * @return array
     */
    public function getContactsByMailGroup($customerid, $mailgroupdesc){

        $this->db->select("c.email");
        $this->db->from('groupmembers gm ');
        $this->db->join('contact c', 'gm.contactid=c.contactid', 'inner');
        $this->db->where('c.customerid', $customerid);
        $this->db->where('mailgroupdesc', $mailgroupdesc);
        $recipients = $this->db->get()->result_array();
        
        return $recipients;
    }
    
    /**
    * This function use for get Customer proiority
    * @param integer $customerid 
     * @return array
    */
 
    public function getCustomerPriority($customerid) 
    {
        
        $this->db->select("*");
        $this->db->from('custpriority');
        $this->db->where('customer_id', $customerid);
        $this->db->where('status', 1);
         
        $this->db->order_by('sort_order', 'asc');
       
        $query = $this->db->get();
	 
		
        if($query->num_rows() == 0)
        {
            
            $this->db->select("*");
            $this->db->from('custpriority');
            $this->db->where('customer_id', 0);
            $this->db->where('status', 1);
            
            $this->db->order_by('sort_order', 'asc');
            $query = $this->db->get();
            
        }
         
         return $query->result_array();
         
        

    }
    
     /**
    * This function use for get Customer proiority detail
    * @param integer $customerid
    * @param integer $days_offset - days_offset
     * @return array
    */
    public function getCustomerPriorityDetail($customerid, $days_offset) 
    {
        
        $this->db->select("*");
        $this->db->from('custpriority');
        $this->db->where('customer_id', $customerid);
        $this->db->where('status', 1);
        $this->db->where('days_offset', $days_offset);
        $this->db->order_by('sort_order', 'asc');
       
        $query = $this->db->get();
		 
		
        if($query->num_rows() == 0)
        {
            
            $this->db->select("*");
            $this->db->from('custpriority');
            $this->db->where('customer_id', 0);
            $this->db->where('status', 1);
            $this->db->where('days_offset', $days_offset);
            $this->db->order_by('sort_order', 'asc');
            $query = $this->db->get();
            
        }
         
        return $query->row_array();
         
        

    }
     
     /**
     * This function use for get joblimit
     * 
     * @param integer $customerid
     * @return array
     */
    public function getCustomerJobLimit($customerid) 
    {
        $this->db->select("id, joblimit");
        $this->db->from('custjoblimit');
        $this->db->where('customer_id', $customerid);
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder', 'asc');
       
        $query = $this->db->get();
        if($query->num_rows() == 0)
        {
            
            $this->db->select("id, joblimit");
            $this->db->from('custjoblimit');
            $this->db->where('customer_id', 0);
            $this->db->where('isactive', 1);
            $this->db->order_by('sortorder', 'asc');
            $query = $this->db->get();
            
        }
         
        return $query->result_array();
        
       
    }
    
     
    
    /**
    *  get customer glcode
    * @param int $customerid - customer id for gatting glcode
     * @param string $accounttype
    * @return array - glcode array
    */
    public function getCustomerGLChart($customerid, $accounttype = '') {
        
        $this->db->select("id, accountcode, accountname, accountcode as expenseaccount, CONCAT(accountname,' (',accountcode,')') as name, CONCAT(accountcode,' (',accountname,')') as glcode"); 
        $this->db->from('customer_glchart');
        $this->db->where('customerid', $customerid);
        if($accounttype != ''){
            $this->db->where('accounttype', $accounttype);
        }
        $this->db->order_by('glcode');
        $query = $this->db->get();

        return $query->result_array();
        
    }
    
     /**
     * 
     * @param type $customerid
     * @param type $email
     * @param type $name
     * @param type $ph
     * @return type
     */
    public function getFindContactId($customerid, $email, $name, $ph) {
        
        $contactid = 0; 
        $this->db->select("contactid"); 
        $this->db->from('contact c');
        $this->db->where('customerid', $customerid);
        $this->db->where("(c.email LIKE '%".$this->db->escape_str($email)."%' or firstname LIKE '%".$this->db->escape_str($name)."%' or c.mobile LIKE '%".$this->db->escape_str($ph)."%'  or c.phone LIKE '%".$this->db->escape_str($ph)."%')");
         
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $data = $query->row_array();
            
            $contactid = $data['contactid'];
            
        }
        return $contactid;
    }
    
    
    /**
    * This function Get mailgroups
    * 
    * @return array
    */
    public function mailGroups($customerid) {

        $this->db->select('*');
        $this->db->where_in('customerid', array(0, $customerid));
        $this->db->where('mailgroupdesc IS NOT NULL');
        $this->db->where('mailgroupdesc!=', '');
        $this->db->from('mailgroup');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get contacts role
    * 
    * @return array
    */
    public function getContactRoles($customerid) {

        $this->db->select(array("DISTINCT role, COUNT(role) AS count"));
        $this->db->from('contact c'); 
        $this->db->where('c.customerid', $customerid);
 
        $this->db->group_by('role'); 
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get contacts role
    * 
    * @return array
    */
    public function getContactRole() {

        $this->db->select('*');
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder');
        $this->db->from('contact_role');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get a Supplier Types
   
    * @return array 
    */
    public function getSupplierTypes() {

        $this->db->select('*');
        $this->db->from('cp_supplier_type');
        $this->db->where('isactive',1); 
        $this->db->order_by('sortorder');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
    * This function Get Address attributes
    * 
    * @return array
    */
    public function getAttributes($customerid) {
        
        $sql = 'SELECT aa.id,aa.name,aa.status,aat.`name`  FROM `addresslabel_attribute` aa'
                . '   INNER JOIN addresslabelattribute_customer aac ON aac.`attributeid`=aa.`id`'
                . '  INNER JOIN `addresslabel_attribute_type` aat ON aa.`attributetypeid`=aat.id'
                . ' WHERE aac.customerid = :customerid ORDER BY aa.name ';
        $this->db->select('aa.id, aa.name, aa.status, aat.name as type');
        $this->db->where('aac.customerid', $customerid);
        $this->db->join('addresslabelattribute_customer aac', 'aac.attributeid=aa.id', 'inner');
        $this->db->join('addresslabel_attribute_type aat', 'aa.attributetypeid=aat.id', 'inner');
        $this->db->from('addresslabel_attribute aa');
        $this->db->order_by('aa.name');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get Address attributes
    * 
    * @return array
    */
    public function getAttributeTypes() {
        
        $sql = 'SELECT caption FROM `addresslabel_attribute_type` WHERE STATUS=1';
        $this->db->select('id, name');
        $this->db->where('status', 1);
        $this->db->from('addresslabel_attribute_type');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get Organisation contact list 
    * 
    * @return array
    */
    public function getOrganisationContacts($customerid) {

        $this->db->select(array("TRIM(CONCAT(c.firstname,' ',c.surname)) as name, c.contactid, c.email, cu.companyname, c.position"));
        $this->db->from('contact c');
        $this->db->join('customer cu', 'c.customerid =cu.customerid', 'Inner');
        $this->db->where('c.email IS NOT NULL');
        $this->db->where('c.active', 1);
        $this->db->where('c.customerid', $customerid); 
        $this->db->order_by('c.firstname, c.surname');

        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get Organisation contact list 
    * 
    * @return array
    */
    public function getAccountsEmail($customerid, $mailgroupdesc = 'invoice') {

        $emails = array();
        $this->db->select('c.email');
        $this->db->from('contact c');
        $this->db->join('groupmembers gm', 'c.contactid = gm.contactid', 'Inner');
        if($mailgroupdesc != NULL){
            $this->db->where('mailgroupdesc', $mailgroupdesc);
        }
        else{
            $this->db->where("(c.primarycontact =1 or c.iscpmaster = 1 or c.role ='master')");
        }                  
        $this->db->where('c.email IS NOT NULL');
        $this->db->where('c.customerid', $customerid);
         
        $result = $this->db->get()->result_array(); 
        foreach ($result as $row){
                $emails[] = $row['rule_value'];
        }
        return $emails; 
    }
    
    
   
    
    /**
    * This function use for create address attribute
    * @param array $attributeParams - the $attributeParams is array of attribute data and contactid(LoggedUser)
    * @return array
    */
       
    public function createAddressAttribute($attributeParams)
    {
        //1 - load multiple models
        require_once('chain/CreateAddressAttributeChain.php');
        
        //2 - initialize instances
        $CreateAddressAttributeChain = new CreateAddressAttributeChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Create Address Attribute  : ');
        $this->LogClass->log($attributeParams);
        $loggedUserData= $this->sharedClass->getLoggedUser($attributeParams['logged_contactid']);
        
        $attributeData = $attributeParams['attributeData'];
        
        $request = array(
            'attributeParams' => $attributeParams, 
            'userData'      => $loggedUserData, 
            'attributeData'   => $attributeData
        );
 
        $CreateAddressAttributeChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $CreateAddressAttributeChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    
    /**
    * This function Gets suppliers from cp_allocate table to allow user to allocate directly with given parameters
    * @param integer $customerid  
    * @return array 
    */
    public function getSupplierAllocations($customerid) {
            
        if (! isset($customerid) ){
            throw new exception('supplier Id value must be supplied.');
        }
	
	 
 	  $this->db->select("supplierid,companyname");
        $this->db->from('cp_allocate cp');
		$this->db->join('customer c', 'cp.supplierid=c.customerid', 'inner');
	    $this->db->where('cp.customerid', $customerid);
        $this->db->where('cp.isactive', 1);
	    $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get allocated supplier Data Query : '. $this->db->last_query());
		$this->LogClass->log(print_r($data,1));
		
		$rdata = array("0" => "DCFM");
        foreach($data as $key=>$row){
        	$rdata[$row['supplierid']] = $row['companyname'];
        }
		
		$this->LogClass->log(print_r($rdata,1));
        return $rdata;
        
    }
    
    
    
     /**
    * This function use for getting GL Codes
    * @param integer $customerid - customerid for selected customer
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    public function getGLCodes($customerid, $size, $start, $field, $order, $filter, $params = array()) {
        
         
        $this->db->select("gl.id");
        $this->db->from('customer_glchart gl');
        $this->db->join('account_type at', 'gl.accounttype=at.code', 'Inner');
        $this->db->join('addresslabel al', 'gl.labelid=al.labelid', 'left');
        $this->db->join('asset a', 'gl.assetid=a.assetid', 'left');
        $this->db->join('asset_category ac', 'gl.asset_categoryid=ac.asset_category_id', 'left');
        $this->db->join('jobtype jt', 'gl.jobtypeid=jt.id', 'left');
        $this->db->join('budget_category bc', 'gl.budget_categoryid = bc.id', 'left');
        $this->db->join('budget_item bi', 'gl.budget_itemid = bi.id', 'left');
        $this->db->join('se_trade t', 'gl.se_tradeid = t.id', 'left');
        $this->db->join('se_works w', 'gl.se_worksid = w.id', 'left');
        $this->db->join('se_subworks sw', 'gl.se_subworksid = sw.id', 'left');
        $this->db->where('gl.customerid', $customerid);
        
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
            $this->db->where("(a.serial_no LIKE '%".$this->db->escape_str($filter)."%' or a.model LIKE '%".$this->db->escape_str($filter)."%' or a.service_tag LIKE '%".$this->db->escape_str($filter)."%' or a.location_text LIKE '%".$this->db->escape_str($filter)."%' or a.manufacturer LIKE '%".$this->db->escape_str($filter)."%' or a.description  LIKE '%".$this->db->escape_str($filter)."%')");
            
        }
        
        $trows = $this->db->count_all_results();
        
        $this->db->select(array("gl.*, at.name AS accountt, "
                . " IF(gl.labelid=0,'All',TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate))) AS address, "
                . " IF(gl.assetid=0,'All', CONCAT(a.location_text, '-', a.category_name, ' (', IFNULL(a.client_asset_id,''), ')')) AS asset, "
                . " IF(gl.asset_categoryid=0,'All',ac.category_name) AS assetcategory, "
                . " IF(gl.jobtypeid=0,'All',jt.name) AS jobtype, "
                . " IF(gl.budget_categoryid=0,'All',bc.name) AS budgetcategory, "
                . " IF(gl.budget_itemid=0,'All',bi.name) AS budgetitem, "
                . " IF(gl.se_tradeid=0,'All',t.se_trade_name) AS trade, "
                . " IF(gl.se_worksid=0,'All',w.se_works_name) AS works, "
                . " IF(gl.se_subworksid=0,'All',sw.se_subworks_name) AS subworks"));
        $this->db->from('customer_glchart gl');
        $this->db->join('account_type at', 'gl.accounttype=at.code', 'Inner');
        $this->db->join('addresslabel al', 'gl.labelid=al.labelid', 'left');
        $this->db->join('asset a', 'gl.assetid=a.assetid', 'left');
        $this->db->join('asset_category ac', 'gl.asset_categoryid=ac.asset_category_id', 'left');
        $this->db->join('jobtype jt', 'gl.jobtypeid=jt.id', 'left');
        $this->db->join('budget_category bc', 'gl.budget_categoryid = bc.id', 'left');
        $this->db->join('budget_item bi', 'gl.budget_itemid = bi.id', 'left');
        $this->db->join('se_trade t', 'gl.se_tradeid = t.id', 'left');
        $this->db->join('se_works w', 'gl.se_worksid = w.id', 'left');
        $this->db->join('se_subworks sw', 'gl.se_subworksid = sw.id', 'left');
        $this->db->where('gl.customerid', $customerid);

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
            $this->db->where("(a.serial_no LIKE '%".$this->db->escape_str($filter)."%' or a.model LIKE '%".$this->db->escape_str($filter)."%' or a.service_tag LIKE '%".$this->db->escape_str($filter)."%' or a.location_text LIKE '%".$this->db->escape_str($filter)."%' or a.manufacturer LIKE '%".$this->db->escape_str($filter)."%' or a.description  LIKE '%".$this->db->escape_str($filter)."%')");
            
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
        
        $this->LogClass->log('Get GL Codes Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     /**
    * This function use for Update GLCodes
    * @param array $params - the $params is array of GlCode and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertGLCodes($params)
    {
        //1 - load multiple models
        require_once('chain/InsertGLCodeChain.php'); 
        
        //2 - initialize instances
        $InsertGLCodeChain = new InsertGLCodeChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert GL Code  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertData'];
 
         
         
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData,  
            'insertGLCodeData'  => $insertData 
        );
        
        $InsertGLCodeChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertGLCodeChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update GLCodes
    * @param array $params - the $params is array of GlCode and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateGLCodes($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateGLCodeChain.php'); 
        
        //2 - initialize instances
        $UpdateGLCodeChain = new UpdateGLCodeChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update GL Code  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateData = $params['updateData'];
 
         
         
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData,  
            'updateGLCodeData'  => $updateData 
        );
        
        $UpdateGLCodeChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateGLCodeChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a contact data from contact with given parameters
    * @param integer $customerid - the name of $customerid for logged user
    * @param string $accountcode - the name of accountcode
    * @return array 
    */
    public function getGLCodeByCode($customerid, $accountcode) {
            
        if (! isset($accountcode) ){
            throw new exception('account code value must be supplied.');
        }
	 
         $this->db->select(array("gl.*, at.name AS accountt, "
                . " IF(gl.labelid=0,'All',TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate))) AS address, "
                . " IF(gl.assetid=0,'All',a.client_asset_id) AS asset, "
                . " IF(gl.asset_categoryid=0,'All',ac.category_name) AS assetcategory, "
                . " IF(gl.jobtypeid=0,'All',jt.name) AS jobtype, "
                . " IF(gl.budget_categoryid=0,'All',bc.name) AS budgetcategory, "
                . " IF(gl.budget_itemid=0,'All',bi.name) AS budgetitem, "
                . " IF(gl.se_tradeid=0,'All',t.se_trade_name) AS trade, "
                . " IF(gl.se_worksid=0,'All',w.se_works_name) AS works, "
                . " IF(gl.se_subworksid=0,'All',sw.se_subworks_name) AS subworks"));
        $this->db->from('customer_glchart gl');
        $this->db->join('account_type at', 'gl.accounttype=at.code', 'Inner');
        $this->db->join('addresslabel al', 'gl.labelid=al.labelid', 'left');
        $this->db->join('asset a', 'gl.assetid=a.assetid', 'left');
        $this->db->join('asset_category ac', 'gl.asset_categoryid=ac.asset_category_id', 'left');
        $this->db->join('jobtype jt', 'gl.jobtypeid=jt.id', 'left');
        $this->db->join('budget_category bc', 'gl.budget_categoryid = bc.id', 'left');
        $this->db->join('budget_item bi', 'gl.budget_itemid = bi.id', 'left');
        $this->db->join('se_trade t', 'gl.se_tradeid = t.id', 'left');
        $this->db->join('se_works w', 'gl.se_worksid = w.id', 'left');
        $this->db->join('se_subworks sw', 'gl.se_subworksid = sw.id', 'left');
        $this->db->where('gl.customerid', $customerid);
        $this->db->where('gl.accountcode', $accountcode); 
	 
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get GL Code Query : '. $this->db->last_query());
        
        return $data;
        
    }
	
	/**
    * This function use for getting contact security functions
    * @param integer $customerid - customerid for selected customer
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getUserFunctions($customerid, $size, $start, $field, $order, $params = array()) {

        $this->db->select("cs.id");
        $this->db->from('cp_contactsecurity cs');
        $this->db->join('contact c', 'cs.contactid=c.contactid', 'inner');
        $this->db->join('cp_contactsecurityfunction csf', 'cs.functionid=csf.id', 'inner');
        $this->db->where('csf.isactive', 1);
        $this->db->where('c.customerid', $customerid);
        
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
        
        $trows = count($this->db->get()->result_array());
            
        $this->db->select('cs.id, c.firstname, c.role, csf.functionname, csf.description, cs.hasaccess as isactive, cs.createdate');
        $this->db->from('cp_contactsecurity cs');
        $this->db->join('contact c', 'cs.contactid=c.contactid', 'inner');
        $this->db->join('cp_contactsecurityfunction csf', 'cs.functionid=csf.id', 'inner');
        $this->db->where('csf.isactive', 1);
        $this->db->where('c.customerid', $customerid);
        
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
            'data' => $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Contact Security Functions Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for getting user security Data for export
    * 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getUserSecurityForExcel($params) {
        
        $this->db->select('cs.id, c.firstname, c.role, csf.functionname, csf.description, cs.hasaccess as isactive');
        $this->db->from('cp_contactsecurity cs');
        $this->db->join('contact c', 'cs.contactid=c.contactid', 'inner');
        $this->db->join('cp_contactsecurityfunction csf', 'cs.functionid=csf.id', 'inner');
        $this->db->where('csf.isactive', 1);
        
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

        $this->db->order_by('cs.createdate', 'desc');
        $data =  $this->db->get()->result_array();
        $this->LogClass->log('Get User Security For Excel Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * This function use for Update User Security
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function updateUserSecurity($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateUserSecurityChain.php');
        require_once('chain/UserSecurityAuditLogChain.php');
        
        //2 - initialize instances
        $UpdateUserSecurityChain = new UpdateUserSecurityChain();
        $UserSecurityAuditLogChain = new UserSecurityAuditLogChain();
        
        //3 - get the parts connected
        $UpdateUserSecurityChain->setSuccessor($UserSecurityAuditLogChain);
        
        //4 - start the process
        $this->LogClass->log('Update User Security  : ');
        $this->LogClass->log($params);

        $updateData = $params['updateData'];
        
        $auditLogData = array();
        foreach($updateData as $value) {
            $userFunctionData = $this->getUserFunctionById($value['id']);
            $auditLogData[] = array(
                'contactid'     => $userFunctionData['contactid'],
                'functionid'    => $userFunctionData['functionid'],
                'oldvalue'      => $userFunctionData['hasaccess'],
                'newvalue'      => $value['hasaccess'],
                'addedby'       => $params['logged_contactid'],
                'dateadded'     => date('Y-m-d H:i:s', time())      
            );
        }
         
        $request = array(
            'params'        => $params, 
            'updateData'    => $updateData,
            'auditLogData'  => $auditLogData
        );
        
        $UpdateUserSecurityChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UserSecurityAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete User Security
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteUserSecurity($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteUserSecurityChain.php');
        
        //2 - initialize instances
        $DeleteUserSecurityChain = new DeleteUserSecurityChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete User Security  : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteUserSecurityChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteUserSecurityChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for getting contact security audit log
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param date $fromdate - fromdate
    * @param date $todate - todate
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getAuditLog($size, $start, $field, $order, $fromdate, $todate, $params = array()) {

        $this->db->select("al.id");
        $this->db->from('cp_contactsecurity_auditlog al');
        $this->db->join('contact c1', 'al.contactid=c1.contactid', 'left');
        $this->db->join('contact c2', 'al.addedby=c2.contactid', 'left');
        $this->db->join('cp_contactsecurityfunction csf', 'al.functionid=csf.id', 'left');
        
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
            $this->db->where('DATE(al.dateadded) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(al.dateadded) <=', $todate);
        }
        
        $trows = count($this->db->get()->result_array());
            
        $this->db->select("al.dateadded, c1.firstname, csf.functionname, csf.description, c1.role, IF(oldvalue IS NULL, 'Not Specified', IF(oldvalue=0, 'No Access', 'Has Access')) AS oldvalue, IF(newvalue IS NULL, 'Not Specified', IF(newvalue=0, 'No Access', 'Has Access')) AS newvalue, c2.firstname AS editedby");
        $this->db->from('cp_contactsecurity_auditlog al');
        $this->db->join('contact c1', 'al.contactid=c1.contactid', 'left');
        $this->db->join('contact c2', 'al.addedby=c2.contactid', 'left');
        $this->db->join('cp_contactsecurityfunction csf', 'al.functionid=csf.id', 'left');
        
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
            $this->db->where('DATE(al.dateadded) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(al.dateadded) <=', $todate);
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
        
        $this->LogClass->log('Get Contact Security Audit Log Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for getting contact security audit log Data for export
    * 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getAuditLogForExcel($params, $fromdate, $todate) {
        
        $this->db->select("al.dateadded, c1.firstname, csf.functionname, c1.role, csf.description, IF(oldvalue IS NULL, 'Not Specified', IF(oldvalue=0, 'No Access', 'Has Access')) AS oldvalue, IF(newvalue IS NULL, 'Not Specified', IF(newvalue=0, 'No Access', 'Has Access')) AS newvalue, c2.firstname AS editedby");
        $this->db->from('cp_contactsecurity_auditlog al');
        $this->db->join('contact c1', 'al.contactid=c1.contactid', 'left');
        $this->db->join('contact c2', 'al.addedby=c2.contactid', 'left');
        $this->db->join('cp_contactsecurityfunction csf', 'al.functionid=csf.id', 'left');
        
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
            $this->db->where('DATE(al.dateadded) <=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(al.dateadded) >=', $todate);
        }

        $this->db->order_by('al.dateadded', 'desc');
        $data =  $this->db->get()->result_array();
        $this->LogClass->log('Get Contact Security Audit Log For Excel Query : '. $this->db->last_query());
        return $data;
    }
    
     /**
    * This function Get a user function
    * @param integer $id - the name of $id for getting particular User Function data
    * @return array 
    */
    public function getUserFunctionById($id) {
            
        if (! isset($id) ){
            throw new exception('id value must be supplied.');
        }
 

        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->from('cp_contactsecurity');

        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get User Function Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for getting contact security for contactid
    * 
    * @param array $params - it is use for request data and logged user contactid 
    * @return array 
    */
    public function getContactSecurityData($params) {
        
        //LHS grid No access
        $sql = 'SELECT m.name, csf.id, csf.functionname, csf.description FROM cp_contactsecurity cs'
                . ' INNER JOIN cp_contactsecurityfunction csf ON cs.`functionid` = csf.`id`'
                . ' INNER JOIN cp_module m ON csf.`moduleid`=m.id'
                . ' WHERE  hasaccess=1 AND m.isactive=1 AND csf.isactive=1 '
                . ' AND moduleid IN (SELECT moduleid FROM cp_module_access WHERE customerid = '.$params['customerid'].' AND '.$params['roleaccess'].'=1)'
                . ' GROUP BY csf.id';
        
        $sql = 'SELECT m.name, csf.id, csf.functionname, csf.description FROM cp_contactsecurityfunction csf '
                . ' INNER JOIN cp_module m ON csf.`moduleid`=m.id'
                . ' WHERE  m.isactive=1 AND csf.isactive=1 '
                . ' AND moduleid IN (SELECT moduleid FROM cp_module_access WHERE customerid = '.$params['customerid'].' AND '.$params['roleaccess'].'=1)'
                . ' AND csf.id NOT IN (SELECT functionid FROM cp_contactsecurity WHERE  hasaccess=1 AND contactid = '.$params['contactid'].')'
                . ' GROUP BY csf.id';
        
    
        $query = $this->db->query($sql);
        $noaccessData = $query->result_array();

        //RHS grid has access
        $sql = 'SELECT m.name, csf.id, csf.functionname, csf.description FROM cp_contactsecurity cs'
                . ' INNER JOIN cp_contactsecurityfunction csf ON cs.`functionid` = csf.`id`'
                . ' INNER JOIN cp_module m ON csf.`moduleid`=m.id'
                . ' INNER JOIN contact c ON cs.`contactid` = c.contactid'
                . ' WHERE hasaccess=1 AND m.isactive=1 AND csf.isactive=1 AND c.contactid='.$params['contactid'].' AND'
                . ' moduleid IN (SELECT moduleid FROM cp_module_access WHERE customerid = c.customerid AND '.$params['roleaccess'].'=1)';
        
        $query = $this->db->query($sql);
        $hasaccessData = $query->result_array();
        
        $this->LogClass->log('Get Contact Security Data Query : '. $this->db->last_query());
        
        
        $data = array(
            'noaccess'  => $noaccessData,
            'hasaccess' => $hasaccessData
        );
        
        return $data;
    }
    
    /**
    * This function use for Insert User Security
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function insertUserSecurity($params)
    {
        //1 - load multiple models
        require_once('chain/InsertUserSecurityChain.php');
        require_once('chain/UserSecurityAuditLogChain.php');
        
        //2 - initialize instances
        $InsertUserSecurityChain = new InsertUserSecurityChain();
        $UserSecurityAuditLogChain = new UserSecurityAuditLogChain();
        
        //3 - get the parts connected
        $InsertUserSecurityChain->setSuccessor($UserSecurityAuditLogChain);
        
        //4 - start the process
        $this->LogClass->log('Insert User Security  : ');
        $this->LogClass->log($params);

        $insertData = $params['insertData'];
        
        $auditLogData = array();
        foreach($insertData as $value) {
            $auditLogData[] = array(
                'contactid'     => $value['contactid'],
                'functionid'    => $value['functionid'],
                'oldvalue'      => NULL,
                'newvalue'      => 1,
                'addedby'       => $params['logged_contactid'],
                'dateadded'     => date('Y-m-d H:i:s', time())      
            );
        }
         
        $request = array(
            'params'        => $params, 
            'insertData'    => $insertData,
            'auditLogData'  => $auditLogData
        );
        
        $InsertUserSecurityChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UserSecurityAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a user functions by params
    * @param integer $params - params
    * @param integer $fields - fields
    * @return array 
    */
    public function getUserFunctionByParams($params, $fields='*') {
            
        $this->db->select($fields);
        $this->db->from('cp_contactsecurity');
        $this->db->where($params);  
        $query = $this->db->get();
        return $query->result_array();
        
    }
    
    /**
    * This function use for getting GL Codes
    * @param integer $customerid - customerid for selected customer
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use external filters  
    * @return array 
    */
    public function getCostCentres($customerid, $size, $start, $field, $order, $filter, $params = array()) {
        
         
        $this->db->select("id");
        $this->db->from('customer_costcentre');
        $this->db->where('customerid', $customerid);
        
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
            $this->db->where("(costcentre LIKE '%".$this->db->escape_str($filter)."%' or description LIKE '%".$this->db->escape_str($filter)."%')");
            
        }
        
        $trows = $this->db->count_all_results();
        
        $this->db->select('*');
        $this->db->from('customer_costcentre');
        $this->db->where('customerid', $customerid);

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
            $this->db->where("(costcentre LIKE '%".$this->db->escape_str($filter)."%' or description LIKE '%".$this->db->escape_str($filter)."%')");
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
        
        $this->LogClass->log('Get Cost Centre Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     /**
    * This function use for Update GLCodes
    * @param array $params - the $params is array of GlCode and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertCostCentre($params)
    {
        //1 - load multiple models
        require_once('chain/InsertCostCentreChain.php'); 
        
        //2 - initialize instances
        $InsertCostCentreChain = new InsertCostCentreChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert Cost Centre  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertData'];
 
         
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertCostCentreData'  => $insertData 
        );
        
        $InsertCostCentreChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertCostCentreChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update GLCodes
    * @param array $params - the $params is array of GlCode and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateCostCentre($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateCostCentreChain.php'); 
        
        //2 - initialize instances
        $UpdateCostCentreChain = new UpdateCostCentreChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update Cost Centre  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateData = $params['updateData'];
 
         
         
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData,  
            'updateCostCentreData'  => $updateData 
        );
        
        $UpdateCostCentreChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateCostCentreChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
      /**
    * This function use for Delete User Security
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteCostCentre($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteCostCentreChain.php');
        
        //2 - initialize instances
        $DeleteCostCentreChain = new DeleteCostCentreChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete User Security  : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteCostCentreChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteCostCentreChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a Cost Centre data from contact with given parameters
    * @param integer $customerid - the name of $customerid for logged user
    * @param string $costcentre
    * @return array 
    */
    public function checkCostCentre($customerid, $costcentre) {
            
        $result = array(
            'asset'    => 0,
            'invoice'  => 0
        );
  
        $this->db->select('*');
        $this->db->from('asset');
        $this->db->where('customerid', $customerid);
        $this->db->where('costcentre', $costcentre); 
	$result['asset'] = $this->db->count_all_results();
        
        $this->db->select('*');
        $this->db->from('invoice');
        $this->db->where('customerid', $customerid);
        $this->db->where('costcentre', $costcentre); 
	$result['invoice'] = $this->db->count_all_results();
        
        
        return $result;
        
    }
    
    
    /**
    * This function Get a Cost Centre data from contact with given parameters
    * @param integer $customerid - the name of $customerid for logged user
    * @param integer $id
    * @return array 
    */
    public function getCostCentreById($customerid, $id) {
        
        $this->db->select('*');
        $this->db->from('customer_costcentre');
        $this->db->where('customerid', $customerid);
        $this->db->where('id', $id); 
	$data = $this->db->get()->row_array();
        $this->LogClass->log('Get Cost Centre Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function Get a Cost Centre data from contact with given parameters
    * @param integer $customerid - the name of $customerid for logged user
    * @param string $costcentre - the name of costcentre
    * @return array 
    */
    public function getCostCentreByCode($customerid, $costcentre) {
           
        if (! isset($costcentre) ){
            throw new exception('cost Centre value must be supplied.');
        }
        
        $this->db->select('*');
        $this->db->from('customer_costcentre');
        $this->db->where('customerid', $customerid);
        $this->db->where('costcentre', $costcentre); 
	 
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Cost Centre Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
  
    
    /**
    * This function Get a Cost Centre data
    * @param integer $customerid - the name of $customerid for logged user

    * @return array 
    */
    public function getCustomerCostCentre($customerid) {
        
        $this->db->select('*');
        $this->db->from('customer_costcentre');
        $this->db->where('customerid', $customerid);
        $this->db->where('isactive', 1); 
        $this->db->order_by('costcentre ASC');
	$data = $this->db->get()->result_array();
        $this->LogClass->log('Get Cost Centre Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for get customer rulenames
    * @param string $rulename
    * @return array
    */     
    public function getCustomerRuleNames() 
    {
        $sql = 'SELECT caption, rulename FROM customer_rulename WHERE isclientportal=1 AND is_active=1 ORDER BY caption';
        $query = $this->db->query($sql);
        return  $query->result_array();
    }
    
    /**
    * This function use for getting portal settings audit log
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param date $fromdate - fromdate
    * @param date $todate - todate
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getPortalAuditLog($size, $start, $field, $order, $fromdate, $todate, $params = array()) {
        
        $sql = "SELECT pal.dateadded,caption AS setting,oldvalue,newvalue,c.firstname AS editedby"
                . " FROM `cp_portalsetup_auditlog` pal"
                . " INNER JOIN `customer_rulename` cr ON pal.ruleid=cr.`rulename_id`"
                . "LEFT JOIN contact c ON pal.addedby=c.contactid"
                . "WHERE pal.customerid= :customerid ORDER BY dateadded DESC";
        
        $this->db->select("pal.id");
        $this->db->from('cp_portalsetup_auditlog pal');
        $this->db->join('customer_rulename cr', 'pal.ruleid=cr.rulename_id', 'inner');
        $this->db->join('contact c', 'pal.addedby=c.contactid', 'left');
        
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
            $this->db->where('DATE(pal.dateadded) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(pal.dateadded) <=', $todate);
        }
        
        $trows = count($this->db->get()->result_array());
            
        $this->db->select("pal.id, pal.dateadded, cr.caption AS setting, pal.oldvalue, pal.newvalue, c.firstname AS editedby");
        $this->db->from('cp_portalsetup_auditlog pal');
        $this->db->join('customer_rulename cr', 'pal.ruleid=cr.rulename_id', 'inner');
        $this->db->join('contact c', 'pal.addedby=c.contactid', 'left');
        
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
            $this->db->where('DATE(pal.dateadded) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(pal.dateadded) <=', $todate);
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
        
        $this->LogClass->log('Get Portal Settings Audit Log Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
    
    /**
    * This function use for getting portal settings
    * @param integer $customerid - loggeduser customerid 
     * @param string $search
    * @return array 
    */
    
    public function getPortalSettings($customerid, $search = NULL) {
        
        
        $sql = "SELECT rulename_id, caption, valuetype, "
            . " default_rule_value, default_is_sitecontact, default_is_sitefm, default_is_master, "
            . " (SELECT rule_value FROM customer_rule_customer WHERE customer_rule_id= rulename_id AND customerid=$customerid) AS value, "
            . " (SELECT is_sitecontact FROM customer_rule_customer WHERE customer_rule_id= rulename_id AND customerid=$customerid) AS is_sitecontact, "
            . " (SELECT is_sitefm FROM customer_rule_customer WHERE customer_rule_id= rulename_id AND customerid=$customerid) AS is_sitefm, "
            . " (SELECT is_master FROM customer_rule_customer WHERE customer_rule_id= rulename_id AND customerid=$customerid) AS is_master "
            . " FROM customer_rulename cr WHERE isclientportal=1 AND is_active=1 ";
        
        if($search != '' || $search != NULL){
            $sql .= " AND (cr.caption like '%".$this->db->escape_str($search)."%' or cr.rulename like '%".$this->db->escape_str($search)."%')";
        }
        $sql .= " ORDER BY valuetype, caption ";
//      $sql = "SELECT crc.crc_id, cr.caption, cr.valuetype, crc.rule_value AS value FROM customer_rule_customer crc"
//                . " INNER JOIN customer_rulename cr ON crc.customer_rule_id=cr.rulename_id"
//                . " WHERE crc.customerid=$customerid AND cr.isclientportal=1 AND cr.is_active=1 ORDER BY cr.valuetype, cr.caption";
//        
        $query = $this->db->query($sql);
        
        $data = $query->result_array();
         
        $this->LogClass->log('Get Portal Settings Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
    * This function use for Insert Portal Settings
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function insertPortalSettings($params)
    {
        //1 - load multiple models
        require_once('chain/InsertPortalSettingChain.php'); 
        
        //2 - initialize instances
        $InsertPortalSettingChain = new InsertPortalSettingChain(); 
        
        //3 - get the parts connected 
        
        //4 - start the process
        $this->LogClass->log('Insert User Security  : ');
        $this->LogClass->log($params);

        $insertData = $params['insertData'];
       
        $request = array(
            'params'        => $params, 
            'insertData'    => $insertData 
        );
        
        $InsertPortalSettingChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertPortalSettingChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update Portal Settings
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function updatePortalSettings($params)
    {
        //1 - load multiple models
        require_once('chain/UpdatePortalSettingChain.php');
        require_once('chain/PortalSettingAuditLogChain.php');
        
        //2 - initialize instances
        $UpdatePortalSettingChain = new UpdatePortalSettingChain();
        $PortalSettingAuditLogChain = new PortalSettingAuditLogChain();
        
        //3 - get the parts connected
        $UpdatePortalSettingChain->setSuccessor($PortalSettingAuditLogChain);
        
        //4 - start the process
        $this->LogClass->log('Update User Security  : ');
        $this->LogClass->log($params);

        $updateData = $params['updateData'];
        
        $portalData = $this->getPortalSettingsByRuleID($params['customerid'], $params['rulename_id']);
        
        $auditLogData = array();
        
        if($portalData['rule_value'] != $updateData['rule_value']) {
            $auditLogData = array(
                'customerid'    => $portalData['customerid'],
                'ruleid'        => $portalData['customer_rule_id'],
                'oldvalue'      => $portalData['rule_value'],
                'newvalue'      => $updateData['rule_value'],
                'addedby'       => $params['logged_contactid'],
                'dateadded'     => date('Y-m-d H:i:s', time())      
            );
        }
        $request = array(
            'params'        => $params, 
            'updateData'    => $updateData,
            'auditLogData'  => $auditLogData
        );
        
        $UpdatePortalSettingChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $PortalSettingAuditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a portal setup data
    * @param integer $customerid - loggeduser customerid 
    * @param integer $customer_rule_id - customer rule id
    * @return array 
    */
    public function getPortalSettingsByRuleID($customerid, $customer_rule_id) {
            
        
	$this->db->select("*");
        $this->db->where('customer_rule_id', $customer_rule_id); 
        $this->db->where('customerid', $customerid); 
        $this->db->from('customer_rule_customer');
	            
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get portal data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    
    /**
    * This function Get customet list for autocomplete
    * @param string $search - search keyword from autocomplete field
    * @param string $custtype 
    * @return array - 
    */
    public function getCustomerSearch($search, $custtype = '') {
        
         
        $this->db->select("customerid, companyname, custtype");
        $this->db->like("companyname", $this->db->escape_str($search)); 
        
        if($custtype != '' && $custtype != NULL){
            $this->db->where('LOWER(custtype)', $custtype);
        }
        $this->db->from('customer'); 
        $this->db->order_by('companyname');
        $this->db->limit(20, 0);
        $query = $this->db->get();

        return $query->result_array();
       
    }
    
    /**
    * This function use for getting Menu Module
    * @param integer $customerid 
    * @return array 
    */
    
    public function getCustomerMenuModule($customerid) {
        
        $sql = "SELECT m.id, CONCAT( IFNULL(m2.name,''), IF(m2.name IS NULL, '',' > '), IFNULL(m.name,'')) AS module, 0 as visible "
            . " FROM cp_module m "
            . " INNER JOIN cp_module_access ma ON m.id=ma.moduleid "
            . " LEFT JOIN cp_module m2 ON m.parentid=m2.id "
            . " WHERE ma.customerid=$customerid "
            . " ORDER BY m.sortorder";
         
        $query = $this->db->query($sql);
        $data = $query->result_array();
          
        
        return $data;
        
    }
    
    
    /**
     * 
     * get menu module for contact
     * @param integer $contactid
     * @return array
     */
    function getContactMenuModule($contactid) {
      
        $this->db->select('cpm.*');
        $this->db->from('cp_module cpm');
        $this->db->join('cp_contact_module_access cpcma', 'cpm.id = cpcma.moduleid');
        $this->db->where('cpcma.contactid', $contactid);
        $this->db->where('cpm.isactive', '1');
        $this->db->where('cpcma.isactive', '1');
        $this->db->order_by('cpm.sortorder', 'asc');
        $navigation = $this->db->get()->result_array();
        return $navigation;
        
    }
    
    /**
    * @desc This function get Customer Suppliers
    * @param integer $customerid 
    * @return array - 
    */
    public function getCustomerSuppliers($customerid) {
        
                            
        $this->db->select(array("s.*, st.name as typename, st.code as typecode, t.se_trade_name, GROUP_CONCAT(TRIM(CONCAT(c.firstname,' ',c.surname))) AS primarycontact"));
        $this->db->from('customer s');
        $this->db->join('se_trade t', 's.tradeid=t.id', 'left');
        $this->db->join('cp_supplier_type st', 's.typeid=st.id', 'left');
        $this->db->join('contact c', 's.customerid=c.customerid and c.primarycontact=1', 'left');
        $this->db->where('ownercustomerid', $customerid);
        $this->db->order_by('companyname asc');
        $this->db->group_by('s.customerid');
        $query = $this->db->get();
        $data =  $query->result_array(); 
                            
        return $data;
    }
    
    /**
    * @desc This function get Customer Suppliers
    * @param integer $customerid
    * @param integer $labelid 
    * @return array - 
    */
    public function getCustomerSiteLandlords($customerid, $labelid) {
         
        $this->db->select('c.*, sa.supplierid, sa.isactive');
        $this->db->from('cp_supplier_address sa');
        $this->db->join('customer c', 'sa.supplierid = c.customerid', 'inner');
        $this->db->where('c.ownercustomerid', $customerid );
        $this->db->where('sa.labelid', $labelid );
        $this->db->order_by('companyname asc');
        $query = $this->db->get();
        return $query->result_array();
        
    }
   
    /**
    * @desc This function get Customer Contracts
    * @param integer $customerid 
    * @return array - 
    */
    public function getCustomerRequireDocs($customerid) {
         
        $this->db->select('rcd.*, d.docformat');
        $this->db->from('rule_categories AS rc');
        $this->db->join('rules_categories_docs rcd', 'rcd.cat_id = rc.id', 'inner');
        $this->db->join('document d', 'rcd.documentid = d.documentid', 'left');
        $this->db->where('rc.customerid', $customerid);
        //$this->db->where('required_complete', 1 ); 
        $rules = $this->db->get()->result_array();
        
        return $rules;
                            
    }
    
    /**
    * @desc This function Get customer sites
    *
    * @return array - 
    */
    public function getCustomerSites($customerid, $excludelabelids = array(), $states = NULL) { 
 
        $this->db->select(array("labelid, siteline2, sitesuburb, sitestate, latitude_decimal, longitude_decimal, IF(siteref IS NULL, sitesuburb, CONCAT(sitesuburb, '(',siteref,')')) AS site, TRIM(CONCAT(IFNULL(siteref,''),' ',siteline2,' ',sitesuburb,' ',sitestate)) as address"));
        $this->db->from('addresslabel');
        $this->db->where('customerid', $customerid);
        if(count($excludelabelids)>0){
            $this->db->where_not_in('labelid', $excludelabelids);
        }
        if($states != NULL && $states != ''){
            $this->db->where('sitestate', $states);
        }
        $this->db->order_by('site asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * This function Get a Gl Code list
    * @param array $queryParams - parameters for select query
    * @return array 
    */
    public function getGlCodeByParams($queryParams) {

        $this->db->select('*');
        $this->db->from('customer_glchart');
        $this->db->where($queryParams);  
        $query = $this->db->get();
        return $query->row_array();
    }
        
 }


/* End of file CustomerClass.php */