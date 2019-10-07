<?php 
/**
 * Document Libraries Class
 *
 * This is a Document class for Document Opration   
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php'); 
require_once( __DIR__.'/../customer/CustomerClass.php');  
/**
 * Document Libraries Class
 *
 * This is a Document class for Document Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer
 * @filesource          DocumentClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DocumentClass extends MY_Model {
   
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
        $this->LogClass= new LogClass('jobtracker', 'DocumentClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
 
     /**
    * This function use for get Job Documents for customer
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getJobDocumentsData($contactid, $size, $start, $field, $order, $filter, $params) {
 
       // $exclude = array('Cost Sheets','Supplier Invoices','Supplier Quote','Tech Quote');
        $exclude = array();
     
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $exclusionJD = $this->getCustomerJobDocExclusions($loggedUserData['customerid']);
        foreach ($exclusionJD as $key => $value) {
            if($value['isexcluded'] ==1){
                $exclude[] = $value['doctype'];
            }
        }
        
        $this->db->select('d.documentid');
        
        $this->db->from('document d'); 
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');//New
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
        
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where('xreftable', 'jobs'); 
		
        $this->db->where('df.is_raptor_active',1);
        if(count($exclude)>0){
            $this->db->where_not_in('doctype',$exclude);
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
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
            
        }
        else{

        }
        
        if ($filter != '') {
            $this->db->where("(d.documentdesc LIKE '%".$this->db->escape_str($filter)."%' or d.documentid LIKE '%".$this->db->escape_str($filter)."%' or d.doctype LIKE '%".$this->db->escape_str($filter)."%'  or d.docname LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%'  or j.custordref LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('d.documentid');
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("d.xrefid, d.documentid, d.dateadded, d.doctype, d.documentdesc, d.docname, d.docformat, d.filesize, j.jobid, j.custordref, j.sitesuburb, j.sitestate, j.sitefm, j.territory, j.jobdescription");
        
        $this->db->from('document d');
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');//New
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
        
        $this->db->where('j.customerid', $loggedUserData['customerid']);
        $this->db->where('xreftable', 'jobs'); 
		
        $this->db->where('df.is_raptor_active',1);
        if(count($exclude)>0){
            $this->db->where_not_in('doctype',$exclude);
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
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        if ($filter != '') {
            $this->db->where("(d.documentdesc LIKE '%".$this->db->escape_str($filter)."%' or d.documentid LIKE '%".$this->db->escape_str($filter)."%' or d.doctype LIKE '%".$this->db->escape_str($filter)."%'  or d.docname LIKE '%".$this->db->escape_str($filter)."%' or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%'  or j.custordref LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('d.documentid');
        
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

        $this->LogClass->log('Get Job Documents Data Query : '. $this->db->last_query());

        return $data;
    }
    
     /**
    * This function use for get Customer Documents for customer
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getCustomerDocumentsData($contactid, $size, $start, $field, $order, $filter, $params) {
 
      
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        
        $this->db->select('d.documentid');
        
        $this->db->from('document d');  
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');//New
        $this->db->where('d.xrefid', $loggedUserData['customerid']);
        $this->db->where('xreftable', 'customer'); 
        $this->db->where('df.is_raptor_active',1);
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
            $this->db->where("(d.documentdesc LIKE '%".$this->db->escape_str($filter)."%' or d.documentid LIKE '%".$this->db->escape_str($filter)."%' or d.doctype LIKE '%".$this->db->escape_str($filter)."%'  or d.docname LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('d.documentid');
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("d.xrefid, d.documentid, d.dateadded, d.doctype, d.documentdesc, d.docname, d.docformat, d.filesize");
        
        $this->db->from('document d');  
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');//New
        $this->db->where('d.xrefid', $loggedUserData['customerid']);
        $this->db->where('xreftable', 'customer'); 
        $this->db->where('df.is_raptor_active',1);
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
            $this->db->where("(d.documentdesc LIKE '%".$this->db->escape_str($filter)."%' or d.documentid LIKE '%".$this->db->escape_str($filter)."%' or d.doctype LIKE '%".$this->db->escape_str($filter)."%'  or d.docname LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('d.documentid');
        
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

        $this->LogClass->log('Get Customer Documents Data Query : '. $this->db->last_query());

        return $data;
    }
    
     /**
    * This function use for get Asset Documents for customer
    * 
    * @param integer $contactid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getAssetDocumentsData($contactid, $size, $start, $field, $order, $filter, $params) {
 
       // $exclude = array('Cost Sheets','Supplier Invoices','Supplier Quote','Tech Quote');
        $exclude = array();
     
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        $exclusionJD = $this->getCustomerJobDocExclusions($loggedUserData['customerid']);
        foreach ($exclusionJD as $key => $value) {
            if($value['isexcluded'] ==1){
                $exclude[] = $value['doctype'];
            }
        }
        
        $this->db->select('d.documentid');
        
        $this->db->from('document d'); 
        $this->db->join('asset_doctype dt', 'd.doctype=dt.asset_doctype', 'inner');//New
        $this->db->join('asset at', 'd.assetid=at.assetid', 'inner');
        $this->db->join('addresslabel a', "at.labelid=a.labelid", 'left');
        $this->db->join('asset_category ac', 'at.category_id=ac.asset_category_id', 'left');
        
        $this->db->where('at.customerid', $loggedUserData['customerid']);
        $this->db->where('xreftable', 'asset'); 

        if(count($exclude)>0){
            $this->db->where_not_in('doctype',$exclude);
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
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
            
        }
        else{

        }
        
        if ($filter != '') {
            $this->db->where("(d.documentdesc LIKE '%".$this->db->escape_str($filter)."%' or d.documentid LIKE '%".$this->db->escape_str($filter)."%' or d.doctype LIKE '%".$this->db->escape_str($filter)."%'  or d.docname LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%')"); // or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%'  or j.custordref LIKE '%".$this->db->escape_str($filter)."%
        }
     
        $this->db->group_by('d.documentid');
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("d.xrefid, d.documentid, d.dateadded, d.doctype, d.documentdesc, d.docname, d.docformat, d.filesize, a.sitesuburb, at.assetid, ac.category_name, client_asset_id, service_tag"); //, j.jobid, j.custordref, j.sitesuburb, j.sitestate, j.sitefm, j.territory, j.jobdescription
        
        $this->db->from('document d');
        $this->db->join('asset_doctype dt', 'd.doctype=dt.asset_doctype', 'inner');//New
        $this->db->join('asset at', 'd.assetid=at.assetid', 'inner');
        $this->db->join('addresslabel a', "at.labelid=a.labelid", 'left');
        $this->db->join('asset_category ac', 'at.category_id=ac.asset_category_id', 'left');
        
        $this->db->where('at.customerid', $loggedUserData['customerid']);
        $this->db->where('xreftable', 'asset'); 

        if(count($exclude)>0){
            $this->db->where_not_in('doctype',$exclude);
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
        
        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        else{

        }
        
        if ($filter != '') {
            $this->db->where("(d.documentdesc LIKE '%".$this->db->escape_str($filter)."%' or d.documentid LIKE '%".$this->db->escape_str($filter)."%' or d.doctype LIKE '%".$this->db->escape_str($filter)."%'  or d.docname LIKE '%".$this->db->escape_str($filter)."%' or a.sitesuburb LIKE '%".$this->db->escape_str($filter)."%')"); // or j.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or j.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or j.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or j.sitestate LIKE '%".$this->db->escape_str($filter)."%' or j.jobdescription LIKE '%".$this->db->escape_str($filter)."%'  or j.custordref LIKE '%".$this->db->escape_str($filter)."%
        }
     
        $this->db->group_by('d.documentid');
        
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

        $this->LogClass->log('Get Asset Documents Data Query : '. $this->db->last_query());

        return $data;
    }
    
    /**
    * This function use for create Documents
    * @param array $params - the params is array of Documents detail and contactid(LoggedUser)
    * @return array
    */
    public function createDocument($params)
    {
        //1 - load multiple models
       
        require_once('chain/InsertDocumentChain.php');
               
         //2 - initialize instances
        $InsertDocumentChain = new InsertDocumentChain();
       
        //3 - get the parts connected
        
         //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        //Create document data array
        $documentData = $params['documentData'];
        $documentData['userid'] = $loggedUserData['email'];
        $documentData['dateadded'] = date('Y-m-d H:i:s', time());
        $documentData['modifiedby'] = $loggedUserData['email'];
        $documentData['datemodified'] = date('Y-m-d H:i:s', time());
       
        $request = array(
            'params'    => $params,
            'userData'          => $loggedUserData,
            'documentData'      => $documentData 
            
        );
 
        $InsertDocumentChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $InsertDocumentChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for create Documents
    * @param array $params - the params is array of Documents detail and contactid(LoggedUser)
    * @return array
    */
    public function updateDocument($params)
    {
        //1 - load multiple models
       
        require_once('chain/UpdateDocumentChain.php');
               
        //2 - initialize instances
        $UpdateDocumentChain = new UpdateDocumentChain();
       
        //3 - get the parts connected
        
        //4 - start the process
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        
        //Create document data array
        $documentData = $params['documentData'];
        $documentData['modifiedby'] = $loggedUserData['email'];
        $documentData['datemodified'] = date('Y-m-d H:i:s', time());
       
        $request = array(
            'params'            => $params,
            'userData'          => $loggedUserData,
            'documentData'      => $documentData 
            
        );
 
        $UpdateDocumentChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateDocumentChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Delete document
     * 
    * @param array $params - the $params is array of documentdata and lcontactid(LoggedUser)
    * @return array
    */
       
    public function deleteDocument($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteDocumentChain.php');
        
        
         //2 - initialize instances
        $DeleteDocumentChain = new DeleteDocumentChain();
     
        //3 - get the parts connected
 
         //4 - start the process
        $this->LogClass->log('Delete Document  : ');
        $this->LogClass->log($params);
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $request = array(
            'params' => $params,
            'userData'      => $loggedUserData
        );
 
        $DeleteDocumentChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteDocumentChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update document
    * @param array $params - the $params is array of documentdata and lcontactid(LoggedUser)
     * @return array
    */
       
    public function softDeleteDocument($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateDocumentChain.php');
        
        
         //2 - initialize instances
        $UpdateDocumentChain = new UpdateDocumentChain();
       
        //3 - get the parts connected
        
         //4 - start the process
        $this->LogClass->log('Soft Delete Document : ');
        $this->LogClass->log($params);
        
        $documentData = array(
            'isdeleted' => 1,
            'datemodified' => date('Y-m-d H:i:s', time())
        );
         
        $request = array(
            'params' => $params,
            'documentData'   => $documentData
        );
 
        $UpdateDocumentChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateDocumentChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    
     /**
    * This function retrieved document data
      * 
    * @param integer $documentId - the id of the document
    * @return array
    */
    public function getDocumentById($documentId){ 
      
	if (! isset($documentId) ){
            throw new exception('DocumentId value must be supplied.');
        }
	 
        $this->db->select("*");
        $this->db->from("document");  
  	$this->db->where('documentid', $documentId);
	           
	$data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get document Data Query : '. $this->db->last_query());
        
        return $data;
    }
    
    /**
    * This function Get document names list for autocomplete
     * 
    * @param string $search - search keyword from autocomplete field
    * @param string $type - search Type
    * @return array 
    */
    public function getDocumentSearch($search, $type = 'name') {
        
        $sql = "SELECT documentid, docname from document ";
        if($type == 'id'){
            $sql .= " WHERE documentid like '%".$this->db->escape_str($search)."%'";
        }
        else{
            $sql .= " WHERE docname like '%".$this->db->escape_str($search)."%'";
        }
            
        $sql .= " limit 0, 20";
        if($type == 'xrefid'){
              $sql = "SELECT distinct xrefid from document WHERE xrefid like '%".$this->db->escape_str($search)."%' limit 0, 20";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }
     
     
   
    /**
    * This function Get document formats
    *  
    * @return array - 
    */
    public function getDocumentFormats() {
        
        $sql = "SELECT distinct docformat from document ORDER BY docformat";
       
        $query = $this->db->query($sql);
        return $query->result_array();
        
       
    }
    
    /**
    * This function Get document formats
    *  
    * @param integer $customerid Description
    * @return array - 
    */
    public function getCustomerJobDocExclusions($customerid) {
        
        
        $this->db->select('*');
        $this->db->from('cp_jobdoc_exclusion');  
        $this->db->where('customerid', $customerid);
          
        $this->db->order_by('doctype asc');
  
        $query = $this->db->get();
 
        return $query->result_array();
         
        
       
    }
    
    /**
    * This function Get Customer document Type
    *  
    * @return array
    */
    public function getCustomerDocumentTypes($customerid) {
         
        $this->db->select('doctype, COUNT(*) as count');
        $this->db->from('document d'); 
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');//New
        $this->db->where('xreftable', 'customer');
        $this->db->where('xrefid', $customerid);
         $this->db->where('df.is_raptor_active',1);
        $this->db->group_by('doctype');
        $this->db->order_by('doctype asc');
  
        $query = $this->db->get();
 
        return $query->result_array();
    }
    
    /**
    * This function Get Job document Type
    *  
    * @return array
    */
    public function getJobDocumentTypes($customerid) {
        
        
        //$exclude = array('Cost Sheets','Supplier Invoices','Supplier Quote','Tech Quote');
        $exclude = array();
      
        $exclusionJD = $this->getCustomerJobDocExclusions($customerid);
        foreach ($exclusionJD as $key => $value) {
            if($value['isexcluded'] ==1){
                $exclude[] = $value['doctype'];
            }
        }
        
        
   
        $this->db->select('d.doctype, COUNT(*) as count');
        $this->db->from('document d');
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');//New
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->where('xreftable', 'jobs');
        $this->db->where('j.customerid', $customerid);
        $this->db->where('df.is_raptor_active',1);
         if(count($exclude)>0){
            $this->db->where_not_in('doctype',$exclude);
        }
	
        $this->db->group_by('doctype');
        $this->db->order_by('doctype asc');
          
        $query = $this->db->get();

        return $query->result_array();
    }
    
     /**
    * This function Get Job document Count By Month
    *  
    * @return array
    */
    public function getCustomerMonthCounts($customerid) {
         
        $this->db->select(array("DATE_FORMAT(dateadded,'%Y-%m') as month_added, COUNT(*) as count"));
        $this->db->from('document'); 
        $this->db->where('xreftable', 'customer');
        $this->db->where('xrefid', $customerid);
        $this->db->group_by('month_added'); 
          
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get Job document Count By Month
    *  
    * @return array
    */
    public function getJobMonthCounts($customerid) {
         
        $this->db->select(array("DATE_FORMAT(dateadded,'%Y-%m') as month_added, COUNT(*) as count"));
        $this->db->from('document d');
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->where('xreftable', 'jobs');
        $this->db->where('j.customerid', $customerid);
        $this->db->group_by('month_added'); 
          
        $query = $this->db->get();

        return $query->result_array();
    }
  
    /**
    * This function Get document Type
    *  
    * @return array
    */
    public function getDocumentTypes($xreftable = NULL) {
         
        $this->db->select('*');
        $this->db->from('doctype'); 
        if($xreftable != NULL) {
            $this->db->where('xreftable', $xreftable);
        }
        $this->db->order_by('doctype asc');
        
        $query = $this->db->get();

        return $query->result_array();
    }
     
    
    /**
    * This function Get document types
    * @param integer $id
    * @return array - 
    */
    public function getDocumentTypeByID($id) {
        
        $this->db->select('*');
        $this->db->from('doctype');
        $this->db->where('id',$id);     
       
        $query = $this->db->get();
       
        return $query->row_array();
    }
   
    /**
    * This function use for get Job Documents for job id
    * 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $jobid - jobid
    * @param array $contactid - contactid of logged user
    * @return array
    */
    public function getJobDocumentsByJobId($size, $start, $field, $order, $jobid, $contactid) {
 
        $sql = "SELECT * FROM document WHERE xrefid=:jobid AND xreftable='jobs' "
                . "AND docname NOT LIKE '%cost%' AND docname NOT LIKE '%tender%' "
                . "AND docname NOT LIKE '%quot%' AND (docfolder IS NULL OR docfolder NOT LIKE '%cost%' "
                . "AND docfolder NOT LIKE '%tender%' AND docfolder NOT LIKE '%quot%' "
                . "AND docfolder NOT LIKE '%supplier invoice%‘ AND NOT (docfolder LIKE '%report%' "
                . "AND docformat LIKE 'pdf') AND docfolder NOT LIKE '%image%' AND docfolder NOT LIKE '%photo%')"
                . " AND doctype!='images‘ AND IF(isapprovalrequired=1,approved,'') IS NOT NULL ORDER BY doctype,dateadded ";
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        
        $this->db->select('documentid');
        $this->db->from('document d');
        $this->db->where('d.xrefid', $jobid);
        $this->db->where('d.xreftable', 'jobs');
        $this->db->where('d.doctype!=', 'images');
        $this->db->where(array('IF(d.isapprovalrequired=1, "approved", "") IS NOT NULL'));
        $this->db->where(array("(d.doctype NOT LIKE '%cost%' AND d.doctype NOT LIKE '%tender%' AND d.doctype NOT LIKE '%quot%' AND (d.docfolder IS NULL OR d.docfolder NOT LIKE '%cost%' AND d.docfolder NOT LIKE '%tender%' AND d.docfolder NOT LIKE '%quot%' AND d.docfolder NOT LIKE '%supplier invoice%' AND NOT (d.docfolder LIKE '%report%' AND d.docformat LIKE 'pdf') AND d.docfolder NOT LIKE '%image%' AND d.docfolder NOT LIKE '%photo%'))"));

        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
       
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("d.*");
        $this->db->from('document d');
        $this->db->where('d.xrefid', $jobid);
        $this->db->where('d.xreftable', 'jobs');
        $this->db->where('d.doctype!=', 'images');
        $this->db->where(array('IF(d.isapprovalrequired=1, "approved", "") IS NOT NULL'));
        $this->db->where("(d.doctype NOT LIKE '%cost%' AND d.doctype NOT LIKE '%tender%' AND d.doctype NOT LIKE '%quot%' AND (d.docfolder IS NULL OR d.docfolder NOT LIKE '%cost%' AND d.docfolder NOT LIKE '%tender%' AND d.docfolder NOT LIKE '%quot%' AND d.docfolder NOT LIKE '%supplier invoice%' AND NOT (d.docfolder LIKE '%report%' AND d.docformat LIKE 'pdf') AND d.docfolder NOT LIKE '%image%' AND d.docfolder NOT LIKE '%photo%'))");

        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
        
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

        $this->LogClass->log('Get Job Documents Data Query : '. $this->db->last_query());
     	//aklog('Get Job documents Data Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * @desc This function Get document folders

    * @return array 
    */
    public function getDocFolder($type = '') {
        
        $this->db->select('*');
        $this->db->from('docfolder');
        $this->db->where('is_raptor_active', 1);
        if($type != '') {
            if($type == 'doc') {
                $this->db->where("caption NOT LIKE '%photo%'");
            } else {
                 $this->db->where("caption LIKE '%photo%'");
            }
        }
        
        $this->db->order_by('caption', 'asc');
        return $this->db->get()->result_array();
    }
    
   /**
    * @desc This function Get a Job Images for selected job
    * @param  integer $jobid - Job Id
    * @return array 
    */
    public function documentImages($jobid, $contactid) {
        
        $sql = "SELECT * FROM document WHERE xrefid=:jobid AND docname NOT LIKE '%cost%' AND docname"
                . " NOT LIKE '%tender%' AND docname NOT LIKE '%quot%'"
                . " AND (docfolder LIKE '%image%' OR docfolder LIKE '%photo%' OR docfolder IS NULL) AND IF(isapprovalrequired=1,approved,'') IS NOT NULL";
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        
        $this->db->select('d.*');
        $this->db->from('document d');
        $this->db->where('d.xrefid', $jobid);
        $this->db->where('d.xreftable', 'jobs');
        $this->db->where(array('IF(d.isapprovalrequired=1, "approved", "") IS NOT NULL'));
        $this->db->where("(d.docname NOT LIKE '%cost%' AND d.docname NOT LIKE '%tender%' AND d.docname NOT LIKE '%quot%' AND (d.docfolder IS NULL OR d.docfolder LIKE '%image%' OR d.docfolder LIKE '%photo%'))");

        if($loggedUserData['role'] == 'site contact') {
            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
        }
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->join('addresslabel a', "j.labelid=a.labelid", 'left');
        $data = $this->db->get()->result_array();
        $this->LogClass->log('Get Job Images Data Query : '. $this->db->last_query());
        //aklog('Get Job Images Data Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * This function use for get Job Reports for job id
    * 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $jobid - jobid 
    * @return array
    */
    public function getJobReports($size, $start, $field, $order, $jobid) {
 
        $sql = "select * from document where xrefid=$jobid and xreftable='jobs' and docfolder = 'Reports' and approved=1 ";
        
        $repfolders = array('Report','Reports','Inspections');
        $this->db->select('documentid');
        $this->db->from('document');
        $this->db->where('xrefid', $jobid);
        $this->db->where('xreftable', 'jobs');
        $this->db->where_in('docfolder', $repfolders);
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("*");
        $this->db->from('document');
        $this->db->where('xrefid', $jobid);
        $this->db->where('xreftable', 'jobs');
        $this->db->where_in('docfolder', $repfolders);
        
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

        $this->LogClass->log('Get Job Reports Data Query : '. $this->db->last_query());

        return $data;
    }
    
    /**
    * This function Get job documents for email dialog
    * @param integer $id
    * @return array - 
    */

    public function getJobDocuments($jobid) {
        
        $sql = "SELECT documentid,doctype,documentdesc,dateadded FROM document"
                . " WHERE  xreftable = 'jobs' AND approved IS  NULL AND xrefid= :jobid"
                . " ORDER BY doctype,dateadded ";
        
        $this->db->select('documentid, doctype, documentdesc, dateadded, docformat, filesize, docname');
        $this->db->from('document');
        $this->db->where('xreftable', 'jobs');
        $this->db->where('xrefid', $jobid);
        //$this->db->where('approved IS NULL', null, false); ANK
        $this->db->order_by('dateadded desc, doctype asc');
       
        $query = $this->db->get();
       
   		//aklog('GetDocumentss Data Query : '. $this->db->last_query());

        return $query->result_array();
    }
    
    /**
    * This function Get sites
    *  
    * @param integer $customerid 
    * @return array - 
    */
    public function getSites($customerid) {
        
        $this->db->select(array("labelid, concat(siteline2,' ',sitesuburb) AS site"));
        $this->db->from('addresslabel');  
        $this->db->where('customerid', $customerid);
  
        $query = $this->db->get();
 
        return $query->result_array();
    }
    
    /**
    * This function Get Asset document Type
    *  
    * @return array
    */
    public function getAssetDocumentTypes() {
        
        $sql = "Select asset_doctype AS doctype from asset_doctype";
        $this->db->select('asset_doctype AS doctype');
        $this->db->from('asset_doctype');
          
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function Get Asset Category
    *  
    * @return array
    */
    public function getAssetCategory() {
        
        $sql = "SELECT asset_category_id,category_name FROM asset_category WHERE is_active=1 ORDER BY category_name";
        $this->db->select('asset_category_id, category_name');
        $this->db->from('asset_category');
        $this->db->where('is_active', 1);
        $this->db->order_by('category_name asc');
          
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
    * This function use for get Jobs for Editable Reports
    * @param integer $customerid - customerid
    * @param integer $jobid - jobid
    * 
    * @return array
    */
    public function getJobs($customerid, $jobid=null) {
 
        $sql = "SELECT DISTINCT xrefid FROM document d"
                . " INNER JOIN jobs j ON d.xrefid=j.jobid"
                . " INNER JOIN docfolder df ON d.doctype=df.caption"
                . " WHERE xreftable = 'jobs'  AND df.is_editable=1 AND j.customerid= :customerid";

        $this->db->select(array("d.xrefid AS id, d.xrefid AS name, CONCAT(j.siteline2,' ',j.sitesuburb,' ',j.sitestate,' ', j.sitepostcode) AS address"));
        $this->db->distinct();
        $this->db->from('document d');
        $this->db->join('jobs j', 'd.xrefid=j.jobid', 'inner');
        $this->db->join('docfolder df', 'd.doctype=df.caption', 'inner');
        $this->db->where('xreftable', 'jobs');
        $this->db->where('df.is_editable', 1);
        $this->db->where('j.customerid', $customerid);
        if($jobid != null) {
            $this->db->where('j.jobid', $jobid);
        }
         
        return $this->db->get()->result_array();
    }
   
}

/* End of file DocumentClass.php */