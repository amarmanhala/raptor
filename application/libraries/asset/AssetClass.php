<?php 
/**
 * Asset Libraries Class
 *
 * This is a Asset class for Asset Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  

/**
 * Asset Libraries Class
 *
 * This is a Asset class for Asset Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset
 * @filesource          AssetClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 class AssetClass extends MY_Model
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
        $this->LogClass= new LogClass('jobtracker', 'AssetClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
       
    
    /**
    * This function use for get Asset Data
    * 
    * @param integer $customerid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getAssetData($customerid, $size, $start, $field, $order, $filter, $params) {
 
         
        
        $this->db->select('a.assetid');
        $this->db->from('asset a');
        $this->db->join('asset_location al', "al.asset_location_id = a.location_id", 'left');
        $this->db->join('addresslabel ad', 'al.labelid = ad.labelid', 'left');
        $this->db->join('asset_contract ac', 'a.assetid = ac.assetid', 'left');
        
        $this->db->where('a.customerid', $customerid); 
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
            $this->db->where("(ad.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or ad.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or ad.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or ad.sitestate LIKE '%".$this->db->escape_str($filter)."%' or al.location LIKE '%".$this->db->escape_str($filter)."%' or a.category_name LIKE '%".$this->db->escape_str($filter)."%' or a.manufacturer LIKE '%".$this->db->escape_str($filter)."%' or a.model LIKE '%".$this->db->escape_str($filter)."%' or a.service_tag LIKE '%".$this->db->escape_str($filter)."%')");
        }
     
        $this->db->group_by('a.assetid');
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select("a.assetid, ad.siteline2, ad.sitesuburb, ad.sitestate, al.location, a.category_name, a.manufacturer, a.model, a.service_tag, a.purchase_date, a.location_text, a.sublocation_text, a.serial_no, a.client_asset_id, a.last_service_date");
        
        
        $this->db->from('asset a');
        $this->db->join('asset_location al', "al.asset_location_id = a.location_id", 'left');
        $this->db->join('addresslabel ad', 'al.labelid = ad.labelid', 'left');
        $this->db->join('asset_contract ac', 'a.assetid = ac.assetid', 'left');
        $this->db->where('a.customerid', $customerid); 
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
            $this->db->where("(ad.siteline1 LIKE '%".$this->db->escape_str($filter)."%' or ad.siteline2 LIKE '%".$this->db->escape_str($filter)."%' or ad.sitesuburb LIKE '%".$this->db->escape_str($filter)."%' or ad.sitestate LIKE '%".$this->db->escape_str($filter)."%' or al.location LIKE '%".$this->db->escape_str($filter)."%' or a.category_name LIKE '%".$this->db->escape_str($filter)."%' or a.manufacturer LIKE '%".$this->db->escape_str($filter)."%' or a.model LIKE '%".$this->db->escape_str($filter)."%' or a.service_tag LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        if($size != NULL){
            $this->db->limit($size, $start);
        }
        
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        $this->db->group_by('a.assetid');
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );

        $this->LogClass->log('Get Asset Data Query : '. $this->db->last_query());

        return $data;
    }
    
     
    
    /**
    * This function use for get Asset History
    * 
    * @param integer $customerid - logged user contact id
    * @param integer $assetid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getAssetHistoryData($customerid, $assetid, $size, $start, $field, $order, $filter, $params) {
 
         
        
        $this->db->select('ah.asset_history_id');
        $this->db->from('asset_history ah');
        $this->db->join('asset_activity ac', 'ac.asset_activity_id=ah.activity_category', 'left');
        $this->db->where('ah.asset_id', $assetid);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
            
        }
     
  
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select('ah.jobid, ah.poref, ah.activity_date, ah.activity_description, ah.activity_category, ac.activity_name, ah.activity_date');
       
        $this->db->from('asset_history ah');
        $this->db->join('asset_activity ac', 'ac.asset_activity_id=ah.activity_category', 'left');
        $this->db->where('ah.asset_id', $assetid);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
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

        $this->LogClass->log('Get Asset History Data Query : '. $this->db->last_query());

        return $data;
    }
   
    
    /**
    * This function use for get Asset Documents
    * 
    * @param integer $customerid - logged user contact id
    * @param integer $assetid - logged user contact id
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param string $filter - it is use for like condition 
    * @param array $params - it is use for external filters 
     * @return type
     */
    public function getAssetDocumentData($customerid, $assetid, $size, $start, $field, $order, $filter, $params) {
 
         
        
        $this->db->select('ad.documentid');
        $this->db->from('asset_document ad');
        $this->db->join('document d', 'ad.documentid=d.documentid', 'inner');
        $this->db->where('assetid', $assetid);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
            
        }
     
  
         
        $trows = count($this->db->get()->result_array());
 
        $this->db->select('ad.assetid, d.documentid, doctype ,documentdesc, docname, docformat, docnote, dateadded ,userid, filesize, approved');
        $this->db->from('asset_document ad');
        $this->db->join('document d', 'ad.documentid=d.documentid', 'inner');
        $this->db->where('assetid', $assetid);
        foreach ($params as $fn=> $fv) {
            if ($fv != '') {
                $this->db->where($fn, $fv);
            }
        }
        
        if ($filter != '') {
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

        $this->LogClass->log('Get Asset Document Data Query : '. $this->db->last_query());

        return $data;
    }
     
    
    /**
    * This function use for Insert new budget
    * @param array $params - the params is array of asset data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAsset($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAssetChain.php');
        
        //2 - initialize instances
        $InsertAssetChain = new InsertAssetChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Budget  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['insertData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData, 
            'assetData'   =>  $insertData
        );
 
        $InsertAssetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAssetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update budget
    * @param array $params - the $params is array of asset data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateAsset($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateAssetChain.php'); 
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
        //2 - initialize instances
        $UpdateAssetChain = new UpdateAssetChain();
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected
         $UpdateAssetChain->setSuccessor($EditLogChain); 
         
        //4 - start the process
        $this->LogClass->log('Update Asset  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $assetData = $this->getAssetById($params['assetid']); 
        $updateData = $params['updateData'];
  
        $editLogData=array();
        foreach ($updateData as $key => $value) {
            if (trim($assetData[$key]) != trim($value)) {
 
                $editLogData[] = array(
                    'tablename' => 'asset' , 
                    'recordid'  => $params['assetid'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $assetData[$key], 
                    'newvalue'  => $value
                );
            }
        } 
        
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData,  
            'assetData'    => $updateData, 
            'editLogData'   => $editLogData
        );
        
        $UpdateAssetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
                
   /**
    * This function use for Delete budget
    * @param array $params - the $params is array of assetid and lcontactid(LoggedUser)
    * @return array
    */
       
    public function deleteAsset($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteAssetChain.php');
        
        //2 - initialize instances
        $DeleteAssetChain = new DeleteAssetChain();
    
        //3 - get the parts connected
 
        //4 - start the process
        $this->LogClass->log('Delete Asset  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
 
        $request = array(
            'params' => $params, 
            'userData'    => $loggedUserData 
        );
 
        $DeleteAssetChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteAssetChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
   
    
    /**
    * This function use for Insert new AssetHistory
    * @param array $params - the params is array of asset History data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAssetHistory($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAssetHistoryChain.php');
        
        //2 - initialize instances
        $InsertAssetHistoryChain = new InsertAssetHistoryChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert History  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['insertData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData, 
            'insertData'   =>  $insertData
        );
 
        $InsertAssetHistoryChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAssetHistoryChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for Insert new Asset Document
    * @param array $params - the params is array of asset Document data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAssetDocument($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAssetDocumentChain.php');
        
        //2 - initialize instances
        $InsertAssetDocumentChain = new InsertAssetDocumentChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Asset Document  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['insertData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData, 
            'insertData'   =>  $insertData
        );
 
        $InsertAssetDocumentChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAssetDocumentChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
     
    /**
    * This function use for Insert new budget
    * @param array $params - the params is array of asset data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAssetLocation($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAssetLocationChain.php');
        
        //2 - initialize instances
        $InsertAssetLocationChain = new InsertAssetLocationChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Asset Location  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['insertLocationData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData, 
            'insertLocationData'   =>  $insertData
        );
 
        $InsertAssetLocationChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAssetLocationChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update budget
    * @param array $params - the $params is array of asset data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateAssetLocation($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateAssetLocationChain.php'); 
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
        //2 - initialize instances
        $UpdateAssetLocationChain = new UpdateAssetLocationChain();
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected
         $UpdateAssetLocationChain->setSuccessor($EditLogChain); 
         
        //4 - start the process
        $this->LogClass->log('Update Asset Location : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $assetData = $this->getAssetLocationById($params['asset_location_id']); 
        $updateData = $params['updateLocationData'];
  
        $editLogData=array();
        foreach ($updateData as $key => $value) {
            if (trim($assetData[$key]) != trim($value)) {
 
                $editLogData[] = array(
                    'tablename' => 'asset_location' , 
                    'recordid'  => $params['asset_location_id'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $assetData[$key], 
                    'newvalue'  => $value
                );
            }
        } 
        
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData,  
            'updateLocationData'    => $updateData, 
            'editLogData'   => $editLogData
        );
        
        $UpdateAssetLocationChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Insert new budget
    * @param array $params - the params is array of asset data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAssetSubLocation($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAssetSubLocationChain.php');
        
        //2 - initialize instances
        $InsertAssetSubLocationChain = new InsertAssetSubLocationChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert Asset Sub Location  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['insertSubLocationData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData, 
            'insertSubLocationData'   =>  $insertData
        );
 
        $InsertAssetSubLocationChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAssetSubLocationChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * This function use for Update budget
    * @param array $params - the $params is array of asset data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateAssetSubLocation($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateAssetSubLocationChain.php'); 
        require_once( __DIR__.'/../shared/chain/EditLogChain.php');
        
        //2 - initialize instances
        $UpdateAssetSubLocationChain = new UpdateAssetSubLocationChain();
        $EditLogChain = new EditLogChain();
        
        //3 - get the parts connected
         $UpdateAssetSubLocationChain->setSuccessor($EditLogChain); 
         
        //4 - start the process
        $this->LogClass->log('Update Asset Sub Location : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $assetData = $this->getAssetSubLocationById($params['asset_sublocation_id']); 
        $updateData = $params['updateSubLocationData'];
  
        $editLogData=array();
        foreach ($updateData as $key => $value) {
            if (trim($assetData[$key]) != trim($value)) {
 
                $editLogData[] = array(
                    'tablename' => 'asset_sublocation' , 
                    'recordid'  => $params['asset_sublocation_id'], 
                    'editdate'  => date('Y-m-d H:i:s'), 
                    'userid'    => $loggedUserData['email'], 
                    'fieldname' => $key, 
                    'oldvalue'  => $assetData[$key], 
                    'newvalue'  => $value
                );
            }
        } 
        
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData,  
            'updateSubLocationData'    => $updateData, 
            'editLogData'   => $editLogData
        );
        
        $UpdateAssetSubLocationChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $EditLogChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Insert new AssetHistory
    * @param array $params - the params is array of asset History data and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAssetService($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAssetServiceChain.php');
        
        //2 - initialize instances
        $InsertAssetServiceChain = new InsertAssetServiceChain();

        //3 - get the parts connected
         
        //4 - start the process
        $this->LogClass->log('Insert History  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']); 
        $insertData = $params['assetServiceData'];
      
       
        $request = array(
            'params'  => $params, 
            'userData'     => $loggedUserData, 
            'assetServiceData'   =>  $insertData
        );
 
        $InsertAssetServiceChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAssetServiceChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function Get a Asset data
    * @param integer $assetid - the name of $assetid for getting particular asset data
    * @return array 
    */
    public function getAssetById($assetid) {
            
       
        if (! isset($assetid) ){
            throw new exception('assetid value must be supplied.');
        }
  
        $this->db->select('a.*, ad.siteline2, ad.sitesuburb, ad.sitestate, al.location, ac.companyname');
        $this->db->from('asset a ');
        $this->db->join('asset_location al', 'a.location_id=al.asset_location_id', 'left');
        $this->db->join('asset_sublocation ab', 'a.sublocation_id=ab.asset_sublocation_id', 'left');
        $this->db->join('addresslabel ad', 'a.labelid=ad.labelid', 'left');
        $this->db->join('customer ac', 'a.customerid = ac.customerid', 'left');
        $this->db->where('a.assetid', $assetid);
         
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get asset Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     
    /**
    * This function Get a asset list
    * @param array $queryParams - parameters for select query
    * @return array 
    */
    public function getAssetByParams($queryParams) {

        $this->db->select('*');
        $this->db->from('asset');
        $this->db->where($queryParams);  
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * get Asset Doc Type
     * 
     * @return array
     */
    public function getAssetDocType() {
        
        $this->db->select('*');
        
        $this->db->from('asset_doctype');
        $this->db->order_by('asset_doctype asc');
        $query = $this->db->get();
 
        return $query->result_array();
     
    }
    
    /**
     * get Frequency
     * 
     * @return array
     */
    public function getFrequency() {
        
        $this->db->select('*');
        $this->db->where('isactive', 1);
        
        $this->db->from('frequency');
        $this->db->order_by('description asc');
        $query = $this->db->get();
 
        return $query->result_array();
       
    }
    
    /**
     * get Asset Categories
     * 
     * @param integer $categoryid
     * @return array
     */
    public function getAssetCategories($categoryid = NULL) {

        $this->db->select('*');
        $this->db->where('is_active', 1);
        if($categoryid!=NULL){
             $this->db->where('asset_category_id', $categoryid);
          
        }
        $this->db->from('asset_category');
        $this->db->order_by('category_name asc');
        $query = $this->db->get();
 
        return $query->result_array();
    }
    
    /**
     * get Asset Categories
     * 
     * @param integer $customerid
     * @return array
     */
    public function getCategories($customerid = NULL) {

        
        //SELECT DISTINCT ac.asset_category_id,ac.category_name 
        //FROM asset_category ac 
        //INNER JOIN asset a ON a.category_id=ac.asset_category_id 
        //WHERE ac.is_active=1 AND a.customerid= :customerid ORDER BY ac.category_name
        $this->db->select('ac.asset_category_id, ac.category_name');
        $this->db->from('asset_category ac');
        $this->db->join('asset a', 'a.category_id=ac.asset_category_id', 'INNER');    
        $this->db->where('ac.is_active', 1);
        $this->db->where('a.customerid', $customerid); 
        
        $this->db->order_by('ac.category_name asc');
        $this->db->group_by('ac.asset_category_id');
        $query = $this->db->get();
 
        return $query->result_array();
    }
    
    
    /**
     * get OHSRisk
     * @return array
     */
    public function getAssetDepmethods() {
        
        
        $this->db->select('*');
        
        $this->db->from('asset_depmethod');
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder asc');
        $query = $this->db->get();
 
        return $query->result_array();
    
    } 
    
    /**
     * get OHSRisk
     * @return array
     */
    public function getOHSRisk() {
        
        
        $this->db->select('*');
        
        $this->db->from('ohsrisk');
        $this->db->order_by('risklevel asc');
        $query = $this->db->get();
 
        return $query->result_array();
    
    } 
    
    /**
     * get Manufacturers
     * @param string $q
     * @return array
     */
    public function getManufacturers($q=NULL) {

        $this->db->select(array('manufacturer'));
        if($q!=NULL && $q!=""){
            $this->db->like('manufacturer', $this->db->escape_str($q)); 
        }
        $this->db->from('asset');
        $this->db->group_by('manufacturer');
        $this->db->order_by('manufacturer asc');
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
		 
   /**
    * get JobID
    * @param integer $customerid
    * @param string $q
    * @param integer $jobid
    * @return array
    */
    public function getJobID($customerid, $q=NULL, $jobid=NULL) {
             
        $this->db->select('jobid');
        $this->db->from('jobs');
        $this->db->where('customerid', $customerid);
        if($q!=NULL && $q!=""){
            $this->db->like('jobid', $this->db->escape_str($q)); 
            //$names = array('client_notified','cancelled','declined');
            //$this->db->where_not_in('jobstage', $names);
        }
        if($jobid!=NULL && $jobid!=""){
            $this->db->where('jobid', $jobid); 
        }
         
        $this->db->order_by('jobid asc');
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
    
    /**
     * get POref
     * @param integer $jobid
     * @param string $q
     * @param integer $poref
     * @return array
     */
    public function getPOref($jobid, $q=NULL, $poref=NULL) {
             
        $this->db->select('poref');
        $this->db->from('purchaseorders');
        //$this->db->where('customerid', $customerid);
        $this->db->where('jobid', $jobid);
        if($q!=NULL && $q!=""){
            $this->db->like('poref', $this->db->escape_str($q)); 
        }
        if($poref!=NULL && $poref!=""){
            $this->db->where('poref', $poref); 
        }
         $this->db->order_by('poref asc');
         
        $query = $this->db->get();
        $re= $query->result_array();
        
        return $re;
        
    }
 
    /**
     * get Site Address
     * 
     * @param integer $customerid
     * @param string $q
     * @return array
     */
    public function getSiteAddress($customerid, $q=NULL) {
             
        $this->db->select(array("distinct CONCAT(siteline2,' ',sitesuburb,' ', sitestate) as address, labelid, latitude_decimal, longitude_decimal"));
        $this->db->from('addresslabel');
        $this->db->where('customerid', $customerid);
        if($q!=NULL && $q!=""){
            $this->db->where("(siteline2 LIKE '%".$this->db->escape_str($q)."%' OR sitesuburb LIKE '%".$this->db->escape_str($q)."%' OR sitestate LIKE '%".$this->db->escape_str($q)."%')");
     
        }
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
    
    /**
     * get Asset Locations
     * 
     * @param integer $labelid
     * @param string $q
     * @return array
     */
    public function getAssetLocations($labelid, $q=NULL) {
             
        $this->db->select('location, asset_location_id, latitude_decimal, longitude_decimal');
        $this->db->from('asset_location');
        //$this->db->where('customerid', $customerid);
        $this->db->where('labelid', $labelid);
        $this->db->where('is_active', 1);
        if($q!=NULL && $q!=""){
            $this->db->like('location', $this->db->escape_str($q)); 
        }
         $this->db->order_by('location','asc');
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
    
    /**
    * This function Get a Asset Location
    * @param integer $asset_location_id
    * @return array 
    */
    public function getAssetLocationById($asset_location_id) {
            
        
  
        $this->db->select('a.*, ad.siteline2, ad.sitesuburb, ad.sitestate');
        $this->db->from('asset_location a '); 
        $this->db->join('addresslabel ad', 'a.labelid=ad.labelid', 'Inner'); 
        $this->db->where('a.asset_location_id', $asset_location_id);
         
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get asset Location Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
     * get Asset sub Locations
     * 
     * @param integer $locationid
     * @param string $q
     * @return array
     */
    public function getAssetSubLocations($locationid, $q=NULL) {
             
        $this->db->select('sublocation, asset_sublocation_id');
        $this->db->from('asset_sublocation');
        //$this->db->where('customerid', $customerid);
        $this->db->where('location_id', $locationid);
        if($q!=NULL && $q!=""){
            $this->db->like('sublocation', $this->db->escape_str($q)); 
        }
         $this->db->order_by('sublocation','asc');
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
    
    /**
    * This function Get a Asset Location
    * @param integer $asset_sublocation_id
    * @return array 
    */
    public function getAssetSubLocationById($asset_sublocation_id) {
            
        
  
        $this->db->select('b.*, a.location, ad.siteline2, ad.sitesuburb, ad.sitestate');
        $this->db->from('asset_sublocation b'); 
        $this->db->join('asset_location a', 'a.asset_location_id=b.location_id', 'Inner');
        $this->db->join('addresslabel ad', 'a.labelid=ad.labelid', 'Inner'); 
        $this->db->where('b.asset_sublocation_id', $asset_sublocation_id);
         
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get asset Sub Location Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
    /**
     * get Asset Conditions
     * @param intger $customerid
     * @return array
     */
    public function getAssetConditions($customerid) {
             
        $this->db->select('condition');
        $this->db->from('asset_condition');
        $this->db->where('customerid', $customerid);
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder','asc');
        $query = $this->db->get();
        if($query->num_rows() == 0)
        {
            $this->db->select('condition');
            $this->db->from('asset_condition');
            $this->db->where('customerid', 0);
            $this->db->where('isactive', 1);
            
            $this->db->order_by('sortorder','asc');
            $query = $this->db->get();
        }
        
         return $query->result_array();
    
    }
  
    /**
     * get Desire Conditions
     * 
     * @param integer $customerid
     * @param string $q
     * @return array
     */
    public function getDesireConditions($customerid, $q = NULL) {
       
        $this->db->select('condition');
        $this->db->from('asset_condition');
        $this->db->where('customerid', $customerid);
        $this->db->where('isactive', 1);
        if($q!=NULL && $q!=""){
            $this->db->like('condition', $this->db->escape_str($q)); 
        }
         $this->db->order_by('sortorder','asc');
        $query = $this->db->get();
 
        return $query->result_array();
    
    }
     
 
    /**
     * get Asset History
     * 
     * @param integer $assetid
     * @param integer $history_id
     * @return array
     */
    public function getAssetHistory($assetid, $history_id=null) {
             
        $this->db->select('ah.jobid, ah.poref, ah.activity_date, ah.activity_description, ah.activity_category, ac.activity_name, ah.activity_date');
        $this->db->from('asset_history ah');
        $this->db->join('asset_activity ac', 'ac.asset_activity_id=ah.activity_category', 'left');
        $this->db->where('ah.asset_id', $assetid);
 
        if($history_id!=NULL && $history_id!=""){
             $this->db->where('ah.asset_history_id', $history_id);
        }
     
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
    
    /**
     * get Activity Category
     * 
     * @param integer $assetid
     * @return array
     */
    public function getActivityCategory($assetid) {
             
        $this->db->select('aa.asset_activity_id, aa.activity_name');
        $this->db->from('asset_activity aa');
        //$this->db->join('asset_cat_act aca', 'aca.activity_id = aa.asset_activity_id', 'inner');
        //$this->db->join('asset a', 'a.category_id=aca.category_id', 'inner');
        $this->db->where('aa.is_active', '1');
        //$this->db->where('aca.is_active', '1');
        //$this->db->where('a.assetid', $assetid);
        $query = $this->db->get();
 
        return $query->result_array();
        
    }
    
    /**
     * get Asset Document
     * 
     * @param integer $assetid
     * @param integer $document_id
     * @return array
     */
    public function getAssetDocument($assetid, $document_id=null) {
             
        $this->db->select('ad.assetid, d.documentid, doctype ,documentdesc, docname, docformat, docnote, dateadded ,userid, filesize, approved');
        $this->db->from('asset_document ad');
        $this->db->join('document d', 'ad.documentid=d.documentid', 'inner');
        $this->db->where('assetid', $assetid);
        
        if($document_id!=NULL && $document_id!=""){
             $this->db->where('d.documentid', $document_id);
        }
        $query = $this->db->get();
 
        return $query->result_array();
       
    }
    
    
     /**
    * This function use for get site lookup data
    * @param integer $customerid - customerid of selected customer
    * @param integer $category_id
    * @param string $filter - str use for for like query
    * @return array
    */
    public function getAssetSearch($customerid, $category_id = NULL, $filter = '') {
		 
        $this->db->select(array("a.*, CONCAT(a.location_text, '-', a.category_name, ' (', IFNULL(a.client_asset_id,''), ')') AS asset"));
        //$this->db->select(array("a.*, CONCAT( a.location_text, '-', a.category_name) AS assettext"));
        $this->db->from('asset a');
        //$this->db->join('asset_location al', "al.asset_location_id = a.location_id", 'left');
        //$this->db->join('addresslabel ad', 'al.labelid = ad.labelid', 'left');
        $this->db->where('a.customerid', $customerid); 
        if ($category_id != NULL && $category_id != 0) {
            $this->db->where('a.category_id', $category_id); 
        }
        if ($filter != '') {
             //$this->db->where('a.client_asset_id IS NOT NULL');
            
             $this->db->where("(a.category_name LIKE '%".$this->db->escape_str($filter)."%' or a.serial_no LIKE '%".$this->db->escape_str($filter)."%' or a.model LIKE '%".$this->db->escape_str($filter)."%' or a.service_tag LIKE '%".$this->db->escape_str($filter)."%' or a.location_text LIKE '%".$this->db->escape_str($filter)."%' or a.manufacturer LIKE '%".$this->db->escape_str($filter)."%' or a.description  LIKE '%".$this->db->escape_str($filter)."%')");
        }
       
         
        $data =  $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $data;
    }
    
   
     /**
     * get Checklists
     * @return array
     */
    public function getChecklists() {
         
//        $this->db->select('*');
//        $this->db->from('zeb_htmlform_formtype');
//        $this->db->where('isactive', 1);
//        $this->db->order_by('formname asc');
//        $query = $this->db->get();
        
        $this->db->select(array("st.documentid as checklistid, IFNULL(docnote,docname) as checklist"));
        $this->db->from('scribe_template st');
        $this->db->join('document d', "st.documentid=d.documentid", 'INNER');
        $this->db->where('doctype', 'JIRD - SWMS Checklist');
        $this->db->order_by('checklist asc');
        $query = $this->db->get();
 
        return $query->result_array();
    
    } 
    
    
    /**
     * get asset activities
     * @return array
     */
    public function getAssetActivities() {
         
        $this->db->select('*');
        $this->db->from('asset_activity');
        $this->db->where('is_active', 1);
        $this->db->order_by('activity_name asc');
        $query = $this->db->get();
 
        return $query->result_array();
    
    } 
     /**
     * get Job List For Asset Service
     * @return array
     */
    public function getJoblists($customerid) {
         
       $excludeJobStages = array('cancelled', 'declined', 'client_notified');
        
        $this->db->select(array("jobid, CONCAT(jobid,' (',custordref,') ',sitesuburb) AS job"));
        $this->db->from('jobs');
        $this->db->where('customerid', $customerid);
        $this->db->where_not_in('jobstage', $excludeJobStages);
        $this->db->order_by('jobid DESC');
        $query = $this->db->get();
 
        return $query->result_array();
    
    } 
 
    /**
    * @desc This function update asset_service
    * @param int $jobid
    * @param string $tempjobid
    * @return  
    */
    public function updateAssetServiceJobId($jobid, $tempjobid) {

        $updatedata = array(
            'jobid' => $jobid
        );
        
        $this->db->where('tempjobid',$tempjobid);
        $this->db->update('asset_service', $updatedata);
      
    }
    
	 /**
    * This function Check  Asset Service is already allocate to job or not
    * @param integer $assetid 
    * @param integer $servicetypeid
    * @param integer $checklistid
     * @param integer $activityid
     * @param integer $jobid
    * @return array 
    */
    public function checkAssetService($assetid, $servicetypeid, $checklistid, $activityid, $jobid) {
        
       if($jobid == NULL){
            return false;
       }
        
        $this->db->select('*');
        $this->db->from('asset_service');
        $this->db->where('assetid', $assetid);
        $this->db->where('jobid', $jobid); 
        $this->db->where('checklistid', $checklistid);
        $this->db->where('activityid', $activityid); 
        $this->db->where('servicetypeid', $servicetypeid); 
		$data = $this->db->get()->row_array();
        if(count($data)>0){
            return true;
        }   
        
         return false;
    }
	
}


/* End of file AssetClass.php */