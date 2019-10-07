<?php 
/**
 * PurchaseOrder Libraries Class
 *
 * This is a PurchaseOrder class for PurchaseOrder Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../job/JobClass.php');
require_once( __DIR__.'/../customer/CustomerClass.php');   
 
/**
 * PurchaseOrder Libraries Class
 *
 * This is a PurchaseOrder class for PurchaseOrder Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder
 * @filesource          PurchaseOrderClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class PurchaseOrderClass extends MY_Model
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
    * Job class 
    * 
    * @var class
    */
    private $jobClass;
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
         $this->jobClass = new JobClass();
        $this->customerClass = new CustomerClass();
        
    }
    
     
      /**
    * This function use for Update PO
    * @param array $params - the params is array of PO detail and contactid(LoggedUser)
    * @return array
    */
    public function updatePurchaseOrder($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdatePurchaseOrderChain.php');
        require_once( __DIR__.'/../shared/chain/EditLogChain.php'); 
        
         //2 - initialize instances
        $UpdatePurchaseOrderChain = new UpdatePurchaseOrderChain();
        $EditLogChain = new EditLogChain();
       
        //3 - get the parts connected
        $UpdatePurchaseOrderChain->setSuccessor($EditLogChain);
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $poData = $this->getPurchaseOrderByPoref($params['poref']);
        //Create etp_job data array
        $updatePOData = $params['updatePOData'];
       
        $editLogData=array();
        foreach ($updatePOData as $key => $value) {
            if (trim($poData[$key]) != trim($value)) {

                $editLogData[] = array(
                    'tablename' => 'purchaseorders' , 
                    'recordid'  => $params['poref'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'contactid' => $loggedUserData['contactid'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $poData[$key], 
                    'newvalue'  => $value
                );
            }
        }
        
         
        $request = array(
            'params'        => $params,
            'userData'      => $loggedUserData,
            'updatePOData'  => $updatePOData,
            'editLogData'   => $editLogData
        );
 
        $UpdatePurchaseOrderChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    
    
    /**
    * This function Get jobdata based on array of where conditions
    * @param array $queryParams - parameters for select query
    * @return array 
    */
    public function getPurchaseOrdersDataByParams($queryParams) {
        
        $this->db->select('*');
        $this->db->where($queryParams);
        $this->db->from('purchaseorders');
        $data = $this->db->get()->result_array();
        return $data;
    }
    
    
    /**
    * This function Get a po detail
    * @param int $poref - the name of poref for getting particular new job data
    * @return array 
    */
    public function getPurchaseOrderByPoref($poref) { 
         
	if (! isset($poref) ){ 
            throw new exception("purchaseorder ref value must be supplied.");
	}
	 
  
         $this->db->select("po.*, s.companyname as suppliername, s.phone as supplierphone, s.email as supplieremail, c.companyname, c.email as companyemail, j.labelid, j.siteline1, j.siteline2, j.siteline3, j.sitesuburb, j.sitestate, j.sitepostcode, j.jobdescription, j.sitecontact, j.sitephone, j.siteemail, j.customerid, j.quoterqd, a.latitude_decimal, a.longitude_decimal");
                
        $this->db->from('purchaseorders po');
        $this->db->join('jobs j', 'po.jobid =j.jobid', 'inner');
        $this->db->join('customer c', 'j.customerid =c.customerid','inner');
        $this->db->join('addresslabel a', 'a.labelid =j.labelid', 'left'); 
        $this->db->join('customer s', 'po.supplierid =s.customerid','left');
        $this->db->where('po.poref', $poref);
        $data = $this->db->get()->row_array();
       
        $this->LogClass->log('Get Purchase order Data Query : '. $this->db->last_query());
         
        return $data;
    }
    
     /**
    * This function Get a po detail
    * @param int $jobid - the name of Jobid for getting particular new job data
    * @return array 
    */
    public function getPurchaseOrderByJobid($jobid) { 
         
        $this->db->select(array("po.*, s.companyname as suppliername, s.phone as supplierphone, s.email as supplieremail, c.companyname, c.email as companyemail, j.labelid, j.siteline1, j.siteline2, j.siteline3, j.sitesuburb, j.sitestate, j.sitepostcode, j.jobdescription, j.sitecontact, j.sitephone, j.siteemail, j.customerid, j.quoterqd, a.latitude_decimal, a.longitude_decimal, GROUP_CONCAT(TRIM(CONCAT(con.firstname,' ',con.surname))) AS primarycontact"));
                
        $this->db->from('purchaseorders po');
        $this->db->join('jobs j', 'po.jobid =j.jobid', 'inner');
        $this->db->join('customer c', 'j.customerid =c.customerid','inner');
        $this->db->join('addresslabel a', 'a.labelid =j.labelid', 'left'); 
        $this->db->join('customer s', 'po.supplierid =s.customerid','left');
        $this->db->join('contact con', 's.customerid=con.customerid and con.primarycontact=1', 'left');
        $this->db->where('po.jobid', $jobid);
        $this->db->group_by('s.customerid');
        $data = $this->db->get()->row_array();
       
        $this->LogClass->log('Get Purchase order Data Query : '. $this->db->last_query());
         
        return $data;
    } 
    
    /**
    * This function Get PO list for autocomplete
    * @param string $search - search keyword from autocomplete field
    * @param integer $supplierid - Optional
    * @param integer $jobid - Optional
    * @return array - 
    */
    public function getPorefSearch($search, $supplierid = '', $jobid ='') {
       
        $this->db->select("poref");
        $this->db->from('purchaseorders');
        $this->db->where("(poref LIKE '%".$this->db->escape_str($search)."%')");
         
        if($supplierid != '' && $supplierid != NULL){
            $this->db->where('supplierid', $supplierid);
        }
        if($jobid != '' && $jobid != NULL){
            $this->db->where('jobid', $jobid);
        }
        
        $this->db->order_by('poref');
        $this->db->limit(20);
        $query = $this->db->get();

        return $query->result_array();
       
    }
    
    /**
    * This function Get a JobStatus id from job Code
    * @param string $code - the name of code for gatting particular id
    * @return integer
    */
    public function getPurchaseOrderStatusID($code) { 
        
        $this->db->select("id");
        $this->db->from('purchaseorder_status');
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
     * This function Get a purchaseorder status id and name from purchaseorder_status with given parameters
     * @param string $code
     * @return array 
     */
    public function getPurchaseOrderStatus($code = NULL) {
        
        $this->db->select('*');
        $this->db->where(array('isactive' => 1));
        if ($code != NULL) {
           $this->db->where('LCASE(code)', strtolower($code));
        }
        $this->db->order_by('sortorder asc');
        $this->db->from('purchaseorder_status');
        $query = $this->db->get();

        return $query->result_array();
    }
     
     
     /**
    * This function use for create PO PDF
     * 
    * @param array $params - the params is array of PO detail and contactid(LoggedUser)
    * @return array
    */
    public function createPDF($params) {
        
         
        //1 - load multiple models
        require_once('chain/GeneratePDFChain.php');
      
         //2 - initialize instances
        $GeneratePDFChain = new GeneratePDFChain();
             
        //3 - get the parts connected 
               
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
       
        $purchaseOrderData = $this->getPurchaseOrderByPoref($params['poref']);
        $jobData = $this->jobClass->getJobById($purchaseOrderData['jobid']); 
  
        if(count($jobData)>0){
         
            $jobData['siteaddress'] = str_replace('<br>', ', ', $jobData['site']); 
        }
        
          
        $request = array(
            'params'              => $params,
            'jobData'               => $jobData,
            'purchaseOrderData'     => $purchaseOrderData,
            'userData'              => $loggedUserData
        );
        
        
        $GeneratePDFChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $GeneratePDFChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
       
    }
    
    //Customer Purchase Orders
    
    /**
    * This function use for getting Customers Purchase Orders
    * @param integer $customerid - Looged User 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param date $fromdate - it is use for external filters 
    * @param date $todate - it is use for external filters 
    * @param string $filter - it is use for external filters 
    * @param array $params - it is use for external filters 
    * @return array 
    */
    public function getCustomerPurchaseOrders($customerid, $size, $start, $field, $order, $fromdate, $todate, $filter, $params) {
 
        $this->db->select("cpo.id");
        $this->db->from('customer_purchaseorder cpo');
        $this->db->join('customer_purchaseorder_status s', 'cpo.status_id=s.id', 'INNER');
        $this->db->where('cpo.customerid', $customerid);
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
        if($fromdate != '') {
            $this->db->where('DATE(cpo.fromdate) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(cpo.fromdate) <=', $todate);
        }
        if ($filter != '') {
            $this->db->where("(cpo.ponumber LIKE '%".$this->db->escape_str($filter)."%')");
        }
          
        $trows = $this->db->count_all_results();
                             
        $this->db->select(array("cpo.*, (cpo.amount_ex_tax - cpo.amount_used) as amount_remaining, s.name as status, s.code as statuscode"));
        $this->db->from('customer_purchaseorder cpo');
        $this->db->join('customer_purchaseorder_status s', 'cpo.status_id=s.id', 'INNER');
        $this->db->where('cpo.customerid', $customerid);
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
        if($fromdate != '') {
            $this->db->where('DATE(cpo.fromdate) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(cpo.fromdate) <=', $todate);
        }
        if ($filter != '') {
            $this->db->where("(cpo.ponumber LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL) {
            $this->db->limit($size, $start);
        }
        
       
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
    * This function use for Insert Customer Purchase Order
    * @param array $params - the $params is array of PurchaseOrder and contactid(LoggedUser)
    * @return array
    */
       
    public function insertCustomerPurchaseOrder($params)
    {
        //1 - load multiple models
        require_once('chain/InsertCustomerPurchaseOrderChain.php');
        
        //2 - initialize instances
        $InsertCustomerPurchaseOrderChain = new InsertCustomerPurchaseOrderChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Customer Purchase Order  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertCustomerPOData = $params['insertCustomerPOData'];
        
                            
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData, 
            'insertCustomerPOData'   => $insertCustomerPOData 
        );
 
        $InsertCustomerPurchaseOrderChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertCustomerPurchaseOrderChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update Customer Purchase Order
    * @param array $params - the params is array of Customer Purchase Order detail and contactid(LoggedUser)
    * @return array
    */
    public function updateCustomerPurchaseOrder($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateCustomerPurchaseOrderChain.php');  
        
         //2 - initialize instances
        $UpdateCustomerPurchaseOrderChain = new UpdateCustomerPurchaseOrderChain(); 
        
        //3 - get the parts connected 
                            
         
        //4 - start the process
        $this->LogClass->log('Delete Customer Purchase Order : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
       
        
        $updateCustomerPOData = $params['updateCustomerPOData']; 
        
         
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'updateCustomerPOData'=> $updateCustomerPOData 
        );
                            
        $UpdateCustomerPurchaseOrderChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateCustomerPurchaseOrderChain -> returnValue;          
        
                            

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for Delete Customer Purchase Order
    * @param array $params - the $params is array of Customer Purchase Order and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteCustomerPurchaseOrder($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteCustomerPurchaseOrderChain.php');
        
        //2 - initialize instances
        $DeleteCustomerPurchaseOrderChain = new DeleteCustomerPurchaseOrderChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Customer Purchase Order : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteCustomerPurchaseOrderChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteCustomerPurchaseOrderChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for getting Customers Purchase Orders unallocated Jobs
    * @param integer $customerid - Looged User 
    * @param string $leaddate - selected date
   * @param array $jobids - selected jobids
    * @return array 
    */
    public function getCustomerPOUnAllocatedJobs($customerid, $leaddate, $jobids = NULL) {
 
   
        $this->db->select(array("j.jobid, j.quoterqd, j.custordref, j.custordref2, j.leaddate, j.jcompletedate, j.sitestate, j.sitesuburb, gl.accountname, a.siteref, js.portaldesc, IF(IFNULL(custinvoiceno,0)=0,IF(j.quoterqd='on',j.estimatedsell,j.notexceed),0) AS wip, IF(IFNULL(custinvoiceno,0)>0,i.netval,0) AS invoiced, j.custinvoiceno"));
        $this->db->from('jobs j'); 
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->join('customer_glchart gl', 'j.custglchartid=gl.id', 'LEFT');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'INNER');
        $this->db->join('invoice i', 'i.invoiceno=j.custinvoiceno', 'LEFT');
        $this->db->where("j.jobstage NOT IN ('cancelled','declined')");
        $this->db->where('j.customerid', $customerid); 
        if($leaddate != '') {
            $this->db->where('DATE(j.leaddate) >=', $leaddate);
        }
        if($jobids != NULL) {
            $this->db->where_in('j.jobid', $jobids);
        }
        $this->db->where("j.jobid NOT IN (select jobid from customer_purchaseorder_job)");
        $data =  $this->db->get()->result_array();
        
        return $data; 
    }
    
    /**
    * This function use for getting Customers Purchase Orders Jobs
    * @param integer $customerid - Looged User 
    * @param string $ponumber - selected PO Number
   * @param array $jobids - selected jobids
    * @return array 
    */
    public function getCustomerPOAllocatedJobs($customerid, $ponumber, $jobids = NULL) {
 
   
        $this->db->select(array("poj.ponumber, poj.jobid, j.quoterqd, j.custordref, j.custordref2, j.leaddate, j.duedate, j.sitestate, j.sitesuburb, gl.accountname, a.siteref, js.portaldesc, IF(IFNULL(custinvoiceno,0)=0,IF(j.quoterqd='on',j.estimatedsell,j.notexceed),0) AS wip, IF(IFNULL(custinvoiceno,0)>0,i.netval,0) AS invoiced, j.custinvoiceno"));
        $this->db->from('customer_purchaseorder_job poj');
        $this->db->join('jobs j', 'poj.jobid=j.jobid', 'INNER');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->join('customer_glchart gl', 'j.custglchartid=gl.id', 'left');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'INNER');
        $this->db->join('invoice i', 'i.invoiceno=j.custinvoiceno', 'LEFT');
        $this->db->where("j.jobstage NOT IN ('cancelled','declined')");
        $this->db->where('poj.customerid', $customerid);
        $this->db->where('poj.ponumber', $ponumber);
        if($jobids != NULL) {
            $this->db->where_in('j.jobid', $jobids);
        }
        
        $data =  $this->db->get()->result_array();
        
        return $data;
    }
    
     /**
    * This function use for Insert Customer Purchase Order Job
    * @param array $params - the $params is array of PurchaseOrder and contactid(LoggedUser)
    * @return array
    */
       
    public function insertCustomerPurchaseOrderJob($params)
    {
        //1 - load multiple models
        require_once('chain/InsertCustomerPurchaseOrderJobChain.php');
        
        //2 - initialize instances
        $InsertCustomerPurchaseOrderJobChain = new InsertCustomerPurchaseOrderJobChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Customer Purchase Order Jobs  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        $insertCustomerPOData = $params['insertCustomerPOJobData'];
        
                            
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData, 
            'insertCustomerPOJobData'   => $insertCustomerPOData 
        );
 
        $InsertCustomerPurchaseOrderJobChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertCustomerPurchaseOrderJobChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete Customer Purchase Order Job
    * @param array $params - the $params is array of Customer Purchase Order and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteCustomerPurchaseOrderJob($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteCustomerPurchaseOrderJobChain.php');
        
        //2 - initialize instances
        $DeleteCustomerPurchaseOrderJobChain = new DeleteCustomerPurchaseOrderJobChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Customer Purchase Order : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteCustomerPurchaseOrderJobChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteCustomerPurchaseOrderJobChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function Get a CustomerPO data from contact with given parameters
    * @param integer $id 
    * @return array 
    */
    public function getCustomerPOById($id) {
             
        $this->db->select(array("cpo.*, (cpo.amount_ex_tax - cpo.amount_used) as amount_remaining, s.name as status, s.code as statuscode"));
        $this->db->from('customer_purchaseorder cpo');
        $this->db->join('customer_purchaseorder_status s', 'cpo.status_id=s.id', 'INNER');
        $this->db->where('cpo.id', $id);
        
	$data = $this->db->get()->row_array();
        
        return $data;
        
    }
    
    /**
    * This function Get a CustomerPO data from contact with given parameters
    * @param string $ponumber 
    * @return array 
    */
    public function getCustomerPOByPONumber($ponumber) {
             
        $this->db->select(array("cpo.*, (cpo.amount_ex_tax - cpo.amount_used) as amount_remaining, s.name as status, s.code as statuscode"));
        $this->db->from('customer_purchaseorder cpo');
        $this->db->join('customer_purchaseorder_status s', 'cpo.status_id=s.id', 'INNER');
        $this->db->where('cpo.ponumber', $ponumber);
        
	$data = $this->db->get()->row_array();
        
        return $data;
        
    }
    
    /**
    * This function Get a CustomerPO data
    * @param integer $customerid 
    * @param string $statuscode 
    * @return array 
    */
    public function getCustomerPOs($customerid, $statuscode = '') {
             
        $this->db->select(array("cpo.*, (cpo.amount_ex_tax - cpo.amount_used) as amount_remaining, s.name as status, s.code as statuscode"));
        $this->db->from('customer_purchaseorder cpo');
        $this->db->join('customer_purchaseorder_status s', 'cpo.status_id=s.id', 'INNER');
        $this->db->where('cpo.customerid', $customerid);
        if($statuscode!=''){
            $this->db->where('s.code', $statuscode);
        }
        
	$data = $this->db->get()->result_array();
        
        return $data;
        
    }
    
     /**
    * This function CustomerPONumber to customer or not
    * @param integer $customerid 
    * @param integer $ponumber
    * @param integer $customer_po_id
    * @return array 
    */
    public function checkCustomerPONumber($customerid, $ponumber, $customer_po_id = 0) {
        
        $this->db->select(array("cpo.*, (cpo.amount_ex_tax - cpo.amount_used) as amount_remaining, s.name as status, s.code as statuscode"));
        $this->db->from('customer_purchaseorder cpo');
        $this->db->join('customer_purchaseorder_status s', 'cpo.status_id=s.id', 'INNER');
        $this->db->where('cpo.customerid', $customerid);
        $this->db->where('cpo.ponumber', $ponumber); 
        if($customer_po_id != 0 && $customer_po_id != NULL && $customer_po_id != ''){
            $this->db->where('cpo.id !=', $customer_po_id); 
        }
        $data = $this->db->get()->row_array();
        if(count($data)>0){
            return true;
        }   
        
         return false;
    }
    
    
    /**
    * This function use for getting Customers Purchase Orders Value
    * @param integer $customerid - Looged User 
    * @param string $fromdate - selected from Date
   * @param string $todate - selected to Date
   * @param string $glcode - selected $glcode
    * @return array 
    */
    public function getCustomerPOValues($customerid, $fromdate, $todate, $glcode = '') {
 
        $this->db->select(array("sum(cpo.amount_ex_tax) as povalue"));
        $this->db->from('customer_purchaseorder cpo'); 
        $this->db->where('cpo.customerid', $customerid);
        
        if($fromdate != '') {
            $this->db->where('DATE(cpo.fromdate) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(cpo.fromdate) <=', $todate);
        }
        if($glcode != '') {
            $this->db->where('cpo.glcode', $glcode);
        }
        $this->db->group_by('cpo.customerid');
        
        $data = $this->db->get()->row();
  
        if ($data) {
            return $data->povalue;
        }
        else{
            return 0;
        }
    }
    
   /**
    * This function use for getting Customers Purchase Orders Spend Value
    * @param integer $customerid - Looged User 
    * @param string $fromdate - selected from Date
   * @param string $todate - selected to Date
   * @param string $glcode - selected $glcode
    * @return array 
    */
    public function getCustomerPOSpendValue($customerid, $fromdate, $todate, $glcode = '') {
 
   
        $this->db->select(array("IF(IFNULL(custinvoiceno,0)=0,IF(j.quoterqd='on',j.estimatedsell,j.notexceed),0) AS wip, IF(IFNULL(custinvoiceno,0)>0,i.netval,0) AS invoiced"));
        $this->db->from('customer_purchaseorder_job poj');
        $this->db->join('jobs j', 'poj.jobid=j.jobid', 'INNER');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->join('customer_glchart gl', 'j.custglchartid=gl.id', 'left');
        $this->db->join('jobstage js', 'j.jobstage = js.jobstagedesc', 'INNER');
        $this->db->join('invoice i', 'i.invoiceno=j.custinvoiceno', 'LEFT');
        $this->db->where("j.jobstage NOT IN ('cancelled','declined')");
        $this->db->where('poj.customerid', $customerid);
         if($fromdate != '') {
            $this->db->where('DATE(j.leaddate) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(j.leaddate) <=', $todate);
        }
        if($glcode != '') {
            $this->db->where('gl.accountname', $glcode);
        }
        
        $data =  $this->db->get()->result_array();
        $amount = 0;
        if ($data) {
            foreach ($data as $key => $value) {
                $amount = $amount + $value['wip'] + $value['invoiced'];
            }
        }
        return $amount;
    }
    
     /**
     * This function Get a Customer purchaseorder status
     * @param int $isactive
     * @return array 
     */
    public function getCustomerPurchaseOrderStatus($isactive = 1) {
        
        $this->db->select('*');
        $this->db->from('customer_purchaseorder_status');
        $this->db->where(array('isactive' => $isactive));
         
        $this->db->order_by('sortorder asc');
       
        $query = $this->db->get();

        return $query->result_array();
    }
     
    /**
    * This function Get a JobStatus id from job Code
    * @param string $code - the name of code for gatting particular id
    * @return integer
    */
    public function getPCustomerurchaseOrderStatusID($code) { 
        
        $this->db->select("id");
        $this->db->from('customer_purchaseorder_status');
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
    * This function use for getting Customers Total remaining exc. WIP
    * @param integer $customerid - Looged User  
    * @return array 
    */
    public function getCustomerTotalRemainingExcWip($customerid) {
        $this->db->select(array("(sum(amount_ex_tax)- sum(amount_invoiced)) as totalremain"));
        $this->db->from('customer_purchaseorder'); 
        $this->db->where('customerid', $customerid);
      
        $this->db->group_by('customerid');
        
        $data = $this->db->get()->row();
  
        if ($data) {
            return (float)$data->totalremain;
        }
        else{
            return 0;
        }
    }
    
    /**
    * This function use for getting Customers Total remaining inc. WIP
    * @param integer $customerid - Looged User  
    * @return array 
    */
    public function getCustomerTotalRemainingIncWip($customerid) {
        $this->db->select(array("(sum(amount_ex_tax)- (sum(amount_invoiced)+sum(amount_wip))) as totalremain"));
        $this->db->from('customer_purchaseorder'); 
        $this->db->where('customerid', $customerid);
      
        $this->db->group_by('customerid');
        
        $data = $this->db->get()->row();
  
        if ($data) {
            return (float)$data->totalremain;
        }
        else{
            return 0;
        }
    }
    
    /**
    * This function use for getting Customers POTotalInvoicedAmount
    * @param integer $customerid - Looged User  
     * @param string $ponumber
    * @return array 
    */
    public function getCustomerPOTotalInvoicedAmount($customerid, $ponumber) {
        
        $this->db->select(array("SUM(i.netval) as totalinvoiced"));
          $this->db->from('customer_purchaseorder_job p');
        $this->db->join('jobs j', 'p.jobid=j.jobid', 'INNER');
        $this->db->join('invoice i', 'j.custinvoiceno=i.invoiceno', 'INNER');
        
        $this->db->where('p.customerid', $customerid);
       $this->db->where('p.ponumber', $ponumber);
        
        $data = $this->db->get()->row();
  
        if ($data) {
            return (float)$data->totalinvoiced;
        }
        else{
            return 0;
        }
    }
    
    /**
    * This function use for getting Customers POTotalWIPAmount
    * @param integer $customerid - Looged User  
     * @param string $ponumber
    * @return array 
    */
    public function getCustomerPOTotalWIPAmount($customerid, $ponumber) { 
        $this->db->select(array("SUM(j.notexceed) as totalwip"));
          $this->db->from('customer_purchaseorder_job p');
        $this->db->join('jobs j', 'p.jobid=j.jobid', 'INNER'); 
        $this->db->where('IFNULL(j.custinvoiceno,0)<=0');
        $this->db->where('p.customerid', $customerid);
       $this->db->where('p.ponumber', $ponumber);
        
        $data = $this->db->get()->row();
  
        if ($data) {
            return (float)$data->totalwip;
        }
        else{
            return 0;
        }
    }
   
}


/* End of file PurchaseOrderClass.php */