<?php 
/**
 * Admin Libraries Class
 *
 * This is a admin class for Admin Opration in full project
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');   

/**
 * Admin Libraries Class
 *
 * This is a Admin class for Shared Opration in full project
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Admin
 * @filesource          AdminClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class AdminClass extends MY_Model{

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
        $this->LogClass= new LogClass('jobtracker', 'AdminClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
  
    /**
    * This function use for getting Announcements
     * 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param array $params it is use for external filter
    * @return array 
    */
    public function getAnnouncements($size, $start, $field, $order, $params = array()) {

        $this->db->select("id");
        $this->db->from('cp_message');
       
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
        
        $trows = $this->db->count_all_results();
            //CONCAT(c.firstname,' ',c.surname)
        $this->db->select('*');
        $this->db->from('cp_message');
      
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
        
        $this->LogClass->log('Get Announcements Query : '. $this->db->last_query());
        return $data;
    }
    
    
    /**
    * This function use for Update Announcement
    * @param array $params - the $params is array of Announcement and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertAnnouncement($params)
    {
        //1 - load multiple models
        require_once('chain/InsertAnnouncementChain.php'); 
        
        //2 - initialize instances
        $InsertAnnouncementChain = new InsertAnnouncementChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert Announcement  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertData'];
 
         
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertAnnouncementData'  => $insertData 
        );
        
        $InsertAnnouncementChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertAnnouncementChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Announcement
    * @param array $params - the $params is array of Announcement and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateAnnouncement($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateAnnouncementChain.php'); 
        
        //2 - initialize instances
        $UpdateAnnouncementChain = new UpdateAnnouncementChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update Announcement  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateData = $params['updateData'];
 
         
         
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData,  
            'updateAnnouncementData'  => $updateData 
        );
        
        $UpdateAnnouncementChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateAnnouncementChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
      /**
    * This function use for Delete User Security
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteAnnouncement($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteAnnouncementChain.php');
        
        //2 - initialize instances
        $DeleteAnnouncementChain = new DeleteAnnouncementChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Announcement  : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteAnnouncementChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteAnnouncementChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /* get Announcement
    * 
    * @param string $browser
    * @param integer $browser_version
    * @param integer $id
    * @return array
    */
    public function getActiveAnnouncement($browser, $browser_version, $id = NULL) { 
         
        $this->db->select('*');
        $this->db->from('cp_message');
        if($id != NULL){
            $this->db->where('id !=', $id);
        }
        $this->db->where('browser', $browser);
        $this->db->where('browser_version', $browser_version);
        $this->db->where('isactive', 1);
        $this->db->where('ispersistent', 0);
        
        $data = $this->db->get()->row_array(); 
        $this->LogClass->log('Get Announcement : '. $this->db->last_query());
        
        return $data;
    }
    
    /* get Announcement
    * 
    * @param integer $id
    * @return array
    */
    public function getAnnouncementById($id) { 
         
        $this->db->select('*');
        $this->db->from('cp_message');
        $this->db->where('id', $id);
  
        $data = $this->db->get()->row_array();
          
        $this->LogClass->log('Get Announcement : '. $this->db->last_query());
        
        return $data;
    }
    
    /* get Site Module
    * 
    * @param integer $contactid
    * @return array
    */
    public function getSiteModule() { 
         
        $this->db->select("*");
        $this->db->from('cp_site');
        $data = $this->db->get()->row_array();
        
        
        return $data;
    }

    
    
     /**
    * This function use for Update SiteModule
    * @param array $params - the $params is array of SiteModule and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertSiteModule($params)
    {
        //1 - load multiple models
        require_once('chain/InsertSiteModuleChain.php'); 
        
        //2 - initialize instances
        $InsertSiteModuleChain = new InsertSiteModuleChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert SiteModule  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertData'];
 
         
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertSiteModuleData'  => $insertData 
        );
        
        $InsertSiteModuleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertSiteModuleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update SiteModule
    * @param array $params - the $params is array of SiteModule and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateSiteModule($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateSiteModuleChain.php'); 
        
        //2 - initialize instances
        $UpdateSiteModuleChain = new UpdateSiteModuleChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update SiteModule  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateData = $params['updateData'];
 
         
         
        $request = array(
            'params'            => $params, 
            'userData'          => $loggedUserData,  
            'updateSiteModuleData'  => $updateData 
        );
        
        $UpdateSiteModuleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateSiteModuleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function use for getting Admin Activity log
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param date $fromdate - fromdate
    * @param date $todate - todate
    * @param array $params - it is use external filters  
    * @return array 
    */
    
    public function getActivityLog($size, $start, $field, $order, $fromdate, $todate, $params = array()) {
        
        $this->db->select("l.id");
        $this->db->from('loginlog l');
        $this->db->join('contact c', 'l.userid=c.contactid', 'INNER');
        $this->db->join('customer cu', 'c.customerid=cu.customerid', 'INNER');
       
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
            $this->db->where('DATE(l.login) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(l.login) <=', $todate);
        }
        
        $trows = $this->db->get()->num_rows();
            
        $this->db->select("c.customerid, cu.companyname, c.contactid, c.firstname, username, login, success, ipaddress");
        $this->db->from('loginlog l');
        $this->db->join('contact c', 'l.userid=c.contactid', 'INNER');
        $this->db->join('customer cu', 'c.customerid=cu.customerid', 'INNER');
       
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
            $this->db->where('DATE(l.login) >=', $fromdate);
        }
        
        if($todate != '') {
            $this->db->where('DATE(l.login) <=', $todate);
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
        
        $this->LogClass->log('Get Activity Log Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
    
     /**
    * This function use for getting Helps
     * 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param array $params it is use for external filter
    * @return array 
    */
    public function getHelps($size, $start, $field, $order, $params = array()) {

        $this->db->select("id");
        $this->db->from('cp_help');
       
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
        
        $trows = $this->db->count_all_results(); 
        
        
        $this->db->select('*');
        $this->db->from('cp_help');
      
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
        
        $this->LogClass->log('Get Helps Query : '. $this->db->last_query());
        return $data;
    }
    
    
    /**
    * This function use for Update Help
    * @param array $params - the $params is array of Help and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertHelp($params)
    {
        //1 - load multiple models
        require_once('chain/InsertHelpChain.php'); 
        
        //2 - initialize instances
        $InsertHelpChain = new InsertHelpChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert Help  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertHelpData = $params['insertHelpData'];
  
        $request = array(
            'params'          => $params, 
            'userData'        => $loggedUserData,  
            'insertHelpData'  => $insertHelpData 
        );
        
        $InsertHelpChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertHelpChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update Help
    * @param array $params - the $params is array of Help and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateHelp($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateHelpChain.php'); 
        
        //2 - initialize instances
        $UpdateHelpChain = new UpdateHelpChain(); 
        
        //3 - get the parts connected 
         
        //4 - start the process
        $this->LogClass->log('Update Help : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateHelpData = $params['updateHelpData'];
 
         
         
        $request = array(
            'params'          => $params, 
            'userData'        => $loggedUserData,  
            'updateHelpData'  => $updateHelpData 
        );
        
        $UpdateHelpChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateHelpChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
      /**
    * This function use for Delete Help
    * @param array $params - the $params is array of Help and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteHelp($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteHelpChain.php');
        
        //2 - initialize instances
        $DeleteHelpChain = new DeleteHelpChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Help  : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteHelpChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteHelpChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
     /**
    * This function use for Update HelpLink
    * @param array $params - the $params is array of HelpLink and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertHelpLink($params)
    {
        //1 - load multiple models
        require_once('chain/InsertHelpLinkChain.php'); 
        
        //2 - initialize instances
        $InsertHelpLinkChain = new InsertHelpLinkChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert HelpLink  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $helpLinkData = $params['helpLinkData'];
  
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData,  
            'helpLinkData'  => $helpLinkData 
        );
        
        $InsertHelpLinkChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertHelpLinkChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     /**
    * This function use for Update HelpLink
    * @param array $params - the $params is array of HelpLink and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateHelpLink($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateHelpLinkChain.php'); 
        
        //2 - initialize instances
        $UpdateHelpLinkChain = new UpdateHelpLinkChain(); 
        
        //3 - get the parts connected 
         
        //4 - start the process
        $this->LogClass->log('Update HelpLink : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $helpLinkData = $params['helpLinkData'];
  
        $request = array(
            'params'          => $params, 
            'userData'        => $loggedUserData,  
            'helpLinkData'    => $helpLinkData 
        );
        
        $UpdateHelpLinkChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateHelpLinkChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
      /**
    * This function use for Delete HelpLink
    * @param array $params - the $params is array of HelpLink and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteHelpLink($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteHelpLinkChain.php');
        
        //2 - initialize instances
        $DeleteHelpLinkChain = new DeleteHelpLinkChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete HelpLink  : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteHelpLinkChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteHelpLinkChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * get Help By Route
    * 
    * @param string $route
    * @return array
    */
    public function getHelpByRoute($route) { 
         
        $this->db->select("*");
        $this->db->from('cp_help');
        $this->db->where('route', $route);
        $this->db->where('isactive', 1);
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Help : '. $this->db->last_query());
        
        return $data;
    }
    
    /**
    * get Help By ID
    * 
    * @param integer $id
    * @return array
    */
    public function getHelpById($id) { 
         
        $this->db->select("*");
        $this->db->from('cp_help');
        $this->db->where('id', $id);
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Help : '. $this->db->last_query());
        
        return $data;
    }
    
    /**
    * get Help Link By helpID
    * 
    * @param integer $helpid
    * @return array
    */
    public function getHelpLinks($helpid) { 
         
        $this->db->select("*");
        $this->db->from('cp_help_link');
        $this->db->where('helpid', $helpid);
        $this->db->where('isactive', 1);
        $this->db->order_by('sortorder', 'asc');
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get Help Links : '. $this->db->last_query());
        
        return $data;
    }
    
     
    /**
    * Submit Help Feedback
    * 
    * @param array $feedbackdata
    * @return integer
    */
    public function SubmitHelpFeedback($feedbackdata) { 
         
         
        $this->db->insert('cp_help_feedback', $feedbackdata);
        return $this->db->insert_id();
      
    }
    
    
    /**
    * This function use for getting  Menu Module
 
    * @return array 
    */
    
    public function getMenuModule() {
         
        $sql = "SELECT m.*, m2.name AS parent, CONCAT( IFNULL(m2.name,''), IF(m2.name IS NULL, '',' > '), IFNULL(m.name,'')) AS module, 0 as visible, IF(m.url2 IS NULL, m.url1,CONCAT(m.url1,'/',m.url2)) AS route "
            . " FROM cp_module m LEFT JOIN cp_module m2 ON m.parentid=m2.id "
            . " ORDER BY m.sortorder";
         
        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        return $data;
        
    }
    
    
    /**
    * This function use for Update MenuModule
    * @param array $params - the $params is array of MenuModule and lcontactid(LoggedUser)
    * @return array
    */
       
    public function insertMenuModule($params)
    {
        //1 - load multiple models
        require_once('chain/InsertMenuModuleChain.php'); 
        
        //2 - initialize instances
        $InsertMenuModuleChain = new InsertMenuModuleChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Insert MenuModule  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $insertData = $params['insertMenuModuleData'];
 
         
         
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'insertMenuModuleData'  => $insertData 
        );
        
        $InsertMenuModuleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $InsertMenuModuleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
     /**
    * This function use for Update SiteModule
    * @param array $params - the $params is array of SiteModule and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateMenuModule($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateMenuModuleChain.php'); 
        
        //2 - initialize instances
        $UpdateMenuModuleChain = new UpdateMenuModuleChain(); 
        
        //3 - get the parts connected 
        
         
        //4 - start the process
        $this->LogClass->log('Update Menu Module  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateMenuModuleData = $params['updateMenuModuleData'];
 
          
        $request = array(
            'params'                => $params, 
            'userData'              => $loggedUserData,  
            'updateMenuModuleData'  => $updateMenuModuleData 
        );
        
        $UpdateMenuModuleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateMenuModuleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * delete Contact Module access
    * 
    * @param integer

    */
    public function deleteContactModule($contactid) { 
         
        $this->db->where('contactid',$contactid);
        $this->db->delete('cp_contact_module_access');
     
      
    }
    
    /**
    * Insert Contact Module access
    * 
    * @param array $updateData
     
    */
    public function InsertContactModule($updateData) { 
         
        $this->db->insert_batch('cp_contact_module_access', $updateData);
      
    }
    
    /**
    * This function use for getting Customer Summary
     * 
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param array $params it is use for external filter
    * @return array 
    */
    public function getCustomerSummary($size, $start, $field, $order, $params = array()) {

       
       
        $this->db->select("c.customerid");
        $this->db->from('customer c');
        $this->db->join('cp_module_access cma', 'cma.customerid=c.customerid', 'INNER');
        
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
        
        $trows = $this->db->count_all_results();
  
        $this->db->select(array("c.customerid, companyname, COUNT(*) AS modulecount, (SELECT COUNT(*) FROM contact WHERE active=1 AND customerid = c.customerid) AS activecontacts, (SELECT COUNT(*) FROM contact WHERE customerid = c.customerid) AS contacts, c.lastcplogin, IF(c.cpstatus=1,'Active','Inactive') AS status, c.cpstatusnote"));
         $this->db->from('customer c');
        $this->db->join('cp_module_access cma', 'cma.customerid=c.customerid', 'INNER');
      
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
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        }
        $this->db->group_by('c.customerid');
        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );
        
        $this->LogClass->log('Get Customer Summary Query : '. $this->db->last_query());
        return $data;
    }
    
     /**
    * This function use for assign Customer ClientPortal
    * @param integer $customerid 
    * @return array
    */
       
    public function assignCustomerClientPortal($customerid)
    {
         
        $moduleData = $this->getMenuModule(); 
      
        $updateData=array();
        foreach ($moduleData as $key => $value) {
            $updateData[] = array(  
                'customerid'=> $customerid, 
                'moduleid'  => $value['id'],
                'sitecontactaccess'  => $value['sitecontactaccess'],
                'fmaccess'  => $value['fmaccess'],
                'masteraccess'  => $value['masteraccess']
                
            );
            
        }
         
        if(count($updateData)>0){
            $this->db->insert_batch('cp_module_access', $updateData);
        }
    }
    
    /**
    * This function use for getting keywords
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
    public function getKeywords($size, $start, $field, $order, $filter, $params = array()) {
        
        $sql = "SELECT kwsw.id,t.id AS tradeid,w.id AS worksid, sw.id AS subworksid,t.se_trade_name,w.se_works_name,"
                . "se_subworks_name,word,weighting FROM se_keyword_subworks kwsw"
                . " INNER JOIN se_keyword kw ON kw.id = kwsw.keywordid"
                . " INNER JOIN se_subworks sw ON sw.id = kwsw.subworksid"
                . " INNER JOIN se_works_subworks wsw ON wsw.se_subworks_id = sw.id"
                . " INNER JOIN se_works w ON wsw.se_works_id=w.id"
                . " INNER JOIN se_trade_works tw ON w.id=tw.se_works_id"
                . " INNER JOIN se_trade t ON tw.se_trade_id=t.id"
                . " WHERE kw.enabled=1 AND sw.enabled=1 AND t.enabled=1 AND w.enabled=1";
        
        $this->db->select("kwsw.id");
        $this->db->from('se_keyword_subworks kwsw');
        $this->db->join('se_keyword kw', 'kw.id = kwsw.keywordid', 'inner');
        $this->db->join('se_subworks sw', 'sw.id = kwsw.subworksid', 'inner');
        $this->db->join('se_works_subworks wsw', 'wsw.se_subworks_id = sw.id', 'inner');
        $this->db->join('se_works w', 'wsw.se_works_id=w.id', 'inner');
        $this->db->join('se_trade_works tw', 'w.id=tw.se_works_id', 'inner');
        $this->db->join('se_trade t', 'tw.se_trade_id=t.id', 'inner');
        $this->db->where('kw.enabled', 1);
        $this->db->where('sw.enabled', 1);
        $this->db->where('t.enabled', 1);
        $this->db->where('w.enabled', 1);
        
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
            $this->db->where("(kw.word LIKE '%".$this->db->escape_str($filter)."%')");
        }
        
        $trows = $this->db->count_all_results();
 
        $this->db->select("kwsw.id, t.id AS tradeid, w.id AS worksid, sw.id AS subworksid, t.se_trade_name, w.se_works_name, se_subworks_name, kw.word, weighting");
        $this->db->from('se_keyword_subworks kwsw');
        $this->db->join('se_keyword kw', 'kw.id = kwsw.keywordid', 'inner');
        $this->db->join('se_subworks sw', 'sw.id = kwsw.subworksid', 'inner');
        $this->db->join('se_works_subworks wsw', 'wsw.se_subworks_id = sw.id', 'inner');
        $this->db->join('se_works w', 'wsw.se_works_id=w.id', 'inner');
        $this->db->join('se_trade_works tw', 'w.id=tw.se_works_id', 'inner');
        $this->db->join('se_trade t', 'tw.se_trade_id=t.id', 'inner');
        $this->db->where('kw.enabled', 1);
        $this->db->where('sw.enabled', 1);
        $this->db->where('t.enabled', 1);
        $this->db->where('w.enabled', 1);

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
            $this->db->where("(kw.word LIKE '%".$this->db->escape_str($filter)."%')");
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
    * This function use for Update Keyword works
    * @param array $params - the $params is array of keyword weighting and lcontactid(LoggedUser)
    * @return array
    */
       
    public function updateKeywordWorks($params)
    {
        //1 - load multiple models
        require_once('chain/UpdateKeywordWorksChain.php'); 
        
        //2 - initialize instances
        $UpdateKeywordWorksChain = new UpdateKeywordWorksChain(); 
        
        //3 - get the parts connected 
        
        
        //4 - start the process
        $this->LogClass->log('Update Keyword Works  : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->sharedClass->getLoggedUser($params['logged_contactid']);
         
        $updateData = $params['updateData'];
 
         
         
        $request = array(
            'params'        => $params, 
            'userData'      => $loggedUserData,  
            'updateData'    => $updateData 
        );
        
        $UpdateKeywordWorksChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateKeywordWorksChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
      /**
    * This function use for Delete keyword works
    * @param array $params - the $params is array of user security and contactid(LoggedUser)
    * @return array
    */
       
    public function deleteKeywordWorks($params)
    {
        //1 - load multiple models
        require_once('chain/DeleteKeywordWorksChain.php');
        
        //2 - initialize instances
        $DeleteKeywordWorksChain = new DeleteKeywordWorksChain();
        
        //3 - get the parts connected
        
        
        //4 - start the process
        $this->LogClass->log('Delete Announcement  : ');
        $this->LogClass->log($params);
         
        $request = array(
            'params' => $params
        );
        
        $DeleteKeywordWorksChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $DeleteKeywordWorksChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
}

/* End of file AdminClass.php */