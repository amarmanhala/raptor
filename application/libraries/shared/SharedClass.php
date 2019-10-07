<?php 
/**
 * Shared Libraries Class
 *
 * This is a Shared class for Shared Opration in full project
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');

/**
 * Shared Libraries Class
 *
 * This is a Shared class for Shared Opration in full project
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            shared
 * @filesource          SharedClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class SharedClass extends MY_Model{

    /**
    * Log class 
    * 
    * @var class
    */
    private $LogClass;
    
    /**
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'SharedClass');
    }
 
    /**
     * 
     * get Side bar menu according to user access level
     * 
     * @param integer $customerid
     * @param string $role
     * @return type
     */
    function getNavigation($customerid, $role) {
      
        $this->db->select('cp_module.*');
        $this->db->join('cp_module_access', 'cp_module.id = cp_module_access.moduleid');
        $this->db->where('cp_module_access.customerid', $customerid);
        $this->db->where('cp_module.isactive', '1');
        if(strtolower($role)=='sitefm')
        {
            $this->db->where('cp_module_access.fmaccess', '1');
        }
        elseif(strtolower($role)=='master')
        {
            $this->db->where('cp_module_access.masteraccess', '1');
        }
        else{
            $this->db->where('cp_module_access.sitecontactaccess', '1');
        }
        $this->db->from('cp_module');
        $this->db->order_by('cp_module.sortorder', 'asc');
        $navigation = $this->db->get()->result_array();
        
        
        return $navigation;
    }
    
    
    /**
    * This function use for getting Edit Logs
     * 
    * @param string $table - 
    * @param integer $recordid -  
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @param array $params it is use for external filter
    * @return array 
    */
    public function getEditLogs($table, $recordid, $size, $start, $field, $order, $params = array()) {

        $this->db->select("editdate");
        $this->db->from('editlog');
        $this->db->where('recordid', $recordid);
        $this->db->where('tablename', $table);
        
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
        $this->db->select(array("e.*, CONCAT(c.firstname,' ',c.surname) as editedby"));
        $this->db->from('editlog e');
        $this->db->join('contact c', 'e.userid = c.email', 'left');
        $this->db->where('e.recordid', $recordid);
        $this->db->where('e.tablename', $table);
        
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
        
        $this->LogClass->log('Get Edit Logs Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * This function Get fieldnames
    * @param integer $recordid - $recordid of edited record
    * @param string $table
    * @return array
    */
    public function getEditLogFieldNames($recordid, $table) {
        
        $this->db->select("fieldname, CONCAT(fieldname,'(',COUNT(*),')') as count");
        $this->db->where('tablename', $table);
        $this->db->where('recordid', $recordid);
        $this->db->group_by('fieldname');
        $this->db->from('editlog');
        $query = $this->db->get();

        return $query->result_array();
    }
    
     /**
    * This function use for create new email log
     * 
    * @param array $params - the $params is array of email data and contactid(LoggedUser)
    * @return array
    */
    public function createEmailLog($params)
    {
        //1 - load multiple models
        
        require_once('chain/EmailChain.php');
        
       
         //2 - initialize instances
        $EmailChain = new EmailChain();
       
        //3 - get the parts connected 
         
        
         //4 - start the process
        $loggedUserData = $this->getLoggedUser($params['logged_contactid']);
        
        //Create etp_job data array
       
        $emailData = array();
        $emailData[] = $params['emailData'];
        
        $request = array(
            'params'     => $params,
            'userData'   => $loggedUserData,
            'emailData'  => $emailData
        );
 
        $EmailChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $EmailChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
     
    /**
    * This function use for createItem
     * 
    * @param array $params - the params is array of rfqno and contactid(LoggedUser)
    * $params = array(
                    'contactid' => $this->data['loggeduser']->contactid, 
                    'name' => $this->input->post('name'), 
                    'description' => $this->input->post('description'), 
                    'item_type_id' => $this->input->post('item_type_id'), 
                    'baseprice' => $this->input->post('baseprice'), 
                    'uom' => $this->input->post('uom')
                    );
    * @return array
    */
    public function createItem($params)
    {
        //1 - load multiple models
       
        require_once('chain/CreateItemChain.php');
     
         //2 - initialize instances
        $CreateItemChain = new CreateItemChain();
   

        //3 - get the parts connected
        //$UpdateQuoteItemChain->setSuccessor($InsertQuotePhotoChain);
       
          
         //4 - start the process
        $this->LogClass->log('Create Se Item : ');
        $this->LogClass->log($params);
        $loggedUserData= $this->getLoggedUser($params['logged_contactid']);
       
        $quoteItemData = array(
                        'name' => $params['name'], 
                        'description' => $params['description'], 
                        'item_type_id' => $params['item_type_id'], 
                        'baseprice' => $params['baseprice'], 
                        'taxrate' => $params['taxrate'], 
                        'uom' => $params['uom'], 
                        'ownercustid' => $loggedUserData['customerid'], 
                        'createdby' => $loggedUserData['email'], 
                        'isactive' => 1, 
                        'createdate' => date('Y-m-d H:i:s')
                        );
        
       
        
        $request = array(
            'params' => $params, 
            'userData' => $loggedUserData, 
            'itemdata' => $quoteItemData, 
      
        );
 
        $CreateItemChain->handleRequest($request);
      
        //5 - get inserted id values
        $returnValue = $CreateItemChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * This function get system variables
    * @param string $varid 
    * @return string
    */
    public function getSysValue($varid)
    {
        $value = '';
        $this->db ->select('varid, vvalue')->where('varid', $varid);
        $query = $this->db->get('systemvariables');

        if ($query->num_rows() == 1) {
            $result = $query->row();
            $value = $result->vvalue;
        }   
        return $value;
    }
      
     /**
    * @desc This function Get Job Types
    * @param $active - 0/1
    * @return array - 
    */
    public function getAccountTypes($active = 1) {
         
        
        $this->db->select('*');
        $this->db->from('account_type');
        if ($active != NULL) {
            $this->db->where('isactive', $active);
        }
        $this->db->order_by('sortorder asc');
        $query = $this->db->get();
        return $query->result_array(); 
    }
    
     /**
    * @desc This function Get Job Types
    * @param $active - 0/1
    * @return array - 
    */
    public function getJobTypes($active = 1) {
         
        
        $this->db->select('*');
        $this->db->from('jobtype');
        if ($active != NULL) {
            $this->db->where('isactive', $active);
        }
        $this->db->order_by('sortorder asc');
        $query = $this->db->get();
        return $query->result_array(); 
    }
    
    /**
    * @desc This function Get Trades
    * @param $active - 0/1
    * @return array - 
    */
    public function getTrades($active = 1) {
            
        $this->db->select('*');
        $this->db->from('se_trade');
        if ($active != NULL) {
            $this->db->where('enabled', $active);
        }
        $this->db->order_by('se_trade_name asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
     /**
    * @desc This function Get Works data
    * @param $active - 0/1
    * @return array - 
    */
    public function getWorks($active = 1) {
         
        $this->db->select('*');
        $this->db->from('se_works');
        if ($active != NULL) {
            $this->db->where('enabled', $active);
        }
        $this->db->order_by('se_works_name asc');
        $query = $this->db->get();
        return $query->result_array();
	 
    }
    
    /**
    * @desc This function Get Works data
    * @param $active - 0/1
    * @return array - 
    */
    public function getSubWorks($active = 1) {
         
        $this->db->select('*');
        $this->db->from('se_subworks');
        if ($active != NULL) {
            $this->db->where('enabled', $active);
        }
        $this->db->order_by('se_subworks_name asc');
        $query = $this->db->get();
        return $query->result_array(); 
    }
    
    /**
    * @desc This function Get Works data for seleted trade
    * @param $tradeid - trade id
    * @return array - 
    */
    public function getTradeWork($tradeid) {

        $this->db->select("w.id, w.se_works_name");
        $this->db->from('se_works w');
        $this->db->join('se_trade_works tw', 'tw.se_works_id = w.id', 'inner');
        $this->db->where('w.enabled', 1);
        $this->db->where('tw.enabled', 1);
        $this->db->where('tw.se_trade_id', $tradeid);
        return $this->db->get()->result_array();

    }
       
    /**
    * @desc This function Get subWorks data for seleted work
    * @param $worksid - work id
    * @return array - 
    */
    public function getWorkSubWork($worksid) {

          
        $this->db->select("sw.id, sw.se_subworks_name");
        $this->db->from('se_subworks sw');
        $this->db->join('se_works_subworks wsw', 'wsw.se_subworks_id = sw.id', 'inner');
        $this->db->where('sw.enabled', 1);
        $this->db->where('wsw.enabled', 1);
        $this->db->where('wsw.se_works_id', $worksid);

        return $this->db->get()->result_array();

    }
    
    /**
     * get Contact Functional Security Access
     * 
     * @param integer $contactid
     * @param string $functionname
     * @return boolean
     */
    public function getFunctionalSecurityAccess($contactid, $functionname) {
         
        $this->db->select('cs.hasaccess');
        $this->db->from('cp_contactsecurity cs');
        $this->db->join('cp_contactsecurityfunction csf', 'cs.functionid=csf.id', 'Inner');
        $this->db->where('csf.functionname', $functionname);
        $this->db->where('cs.contactid', $contactid);
        $this->db->where('csf.isactive', 1); 
        $resultSet = $this->db->get()->result_array();

        if (count($resultSet) > 0 && $resultSet[0]['hasaccess'])
        {
                return $resultSet[0]['hasaccess'];
        }


        return TRUE;

    }
      
    /**
     * get customer Rule Value 
     * @param integer $customerid
     * @param string $role
     * @param string $rulename
     * @return mixed
     */
    public function getRuleValue($customerid, $role, $rulename) {
        
        $this->db->select('crc.customer_rule_id, crc.rule_value, cr.rulename, cr.valuetype');
        $this->db->from('customer_rule_customer crc');
        $this->db->join('customer_rulename cr', 'crc.customer_rule_id=cr.rulename_id', 'left');
        $this->db->where('crc.customerid', $customerid);
        $this->db->where('cr.rulename', $rulename);
        $this->db->where('cr.is_active', '1');
        if(strtolower($role)=='sitefm') {
            $this->db->where('crc.is_sitefm', '1');
        }
        elseif(strtolower($role)=='master') {
            $this->db->where('crc.is_master', '1');
        }
        else{
            $this->db->where('crc.is_sitecontact', '1');
        }

        $resultSet = $this->db->get()->row_array();
        
        if (count($resultSet) > 0) {   
            
            if($resultSet['valuetype'] == 'B' || $resultSet['valuetype'] == 'N'){
                return (int)$resultSet['rule_value'];
            }
            else{
                return $resultSet['rule_value'];
            }
        }
        else{
            $this->db->select('cr.rulename_id, cr.default_rule_value, cr.rulename, cr.valuetype');
            $this->db->from('customer_rulename cr');  
            $this->db->where('cr.rulename', $rulename);
            $this->db->where('cr.is_active', '1');
            if(strtolower($role)=='sitefm') {
                $this->db->where('cr.default_is_sitefm', '1');
            }
            elseif(strtolower($role)=='master') {
                $this->db->where('cr.default_is_master', '1');
            }
            else{
                $this->db->where('cr.default_is_sitecontact', '1');
            }
            $resultSet = $this->db->get()->row_array();
            if (count($resultSet) > 0) { 
            
                if($resultSet['valuetype'] == 'B' || $resultSet['valuetype'] == 'N'){
                    return (int)$resultSet['default_rule_value'];
                }
                else{
                    return $resultSet['default_rule_value'];
                }
              
            }
        }
         
        return FALSE;

    }
    
    /**
     * get customer Rules 
     * @param integer $customerid
     * @param string $role
     * @return array
     */
    public function getCustomerRules($customerid, $role)
    {
        $rules = array();

        $this->db->select('crc.customer_rule_id as rulename_id, crc.rule_value, cr.rulename, cr.valuetype');
        $this->db->from('customer_rule_customer crc');
        $this->db->join('customer_rulename cr', 'crc.customer_rule_id=cr.rulename_id', 'left');
        $this->db->where('crc.customerid', $customerid);
        $this->db->where('cr.is_active', '1');
         
        if(strtolower($role)=='sitefm')
        { 
            $this->db->where('crc.is_sitefm', '1');
        }
        elseif(strtolower($role)=='master')
        {
            $this->db->where('crc.is_master', '1');
        }
        else{
            $this->db->where('crc.is_sitecontact', '1');
        }

        $result = $this->db->get()->result_array();
         
        
        //$this->LogClass->log('Get Customer Rules Data Query : '. $this->db->last_query());
        
        $this->db->select('cr.rulename_id, cr.default_rule_value as rule_value, cr.rulename, cr.valuetype');
        $this->db->from('customer_rulename cr');   
        $this->db->where('cr.is_active', '1');
        $this->db->where('cr.rulename_id NOT IN(SELECT customer_rule_id FROM customer_rule_customer Where customerid='.$customerid.')');
        if(strtolower($role)=='sitefm') {
            $this->db->where('cr.default_is_sitefm', '1');
        }
        elseif(strtolower($role)=='master') {
            $this->db->where('cr.default_is_master', '1');
        }
        else{
            $this->db->where('cr.default_is_sitecontact', '1');
        }
        
        $result1 = $this->db->get()->result_array();
 
        //$this->LogClass->log('Get Default Customer Rules Data Query : '. $this->db->last_query());
        
        
        foreach ($result as $row){
            $rules[$row['rulename']] = $row['rule_value'];
        }
        foreach ($result1 as $row){
            if($row['rule_value'] != NULL && !isset($rules[$row['rulename']])){
                $rules[$row['rulename']] = $row['rule_value'];
            }
        }
        
        return $rules;
    }
    
     
    
    /**
    * This function Get states abbreviation list
    * @param integer $active - 0/1
    * @return array - 
    */
    public function getStates($active = NULL) {

         
        $this->db->select('abbreviation');
        $this->db->from('state');
        if ($active != NULL) {
            $this->db->where('status', $active);
        }
        $this->db->order_by('abbreviation asc');
        $query = $this->db->get();
        return $query->result_array();
        
    }
     
    /**
    * This function Get suburb/postcode list for autocomplete
    * @param string $search - search keyword from autocomplete field
    * @param string $type - search type city/postcode
    * @return array - 
    */
    public function getSuburbPostCode($search, $type) {

        
        
            
        if ($type == 'city') {
            $sql ="SELECT postcode.*,CONCAT('<b>',postcode.suburb,'</b><br>',postcode.state,' ',postcode.postcode) as displaytext, (SELECT a.territory FROM postcodeterritory a, territory b WHERE a.suburb = postcode.suburb AND a.state = postcode.state AND a.territory = b.territorydesc AND a.territory NOT IN (SELECT c.parent FROM territory c WHERE c.parent=a.territory)  limit 0,1) as territory FROM postcode WHERE postcode.suburb LIKE '%".$this->db->escape_str($search)."%'  Order By postcode.suburb limit 0,20";
            //$sql ="SELECT postcode.*,CONCAT('<b>',postcode.suburb,'</b><br>',postcode.state,' ',postcode.postcode) as displaytext, (SELECT territory FROM postcodeterritory WHERE suburb =postcode.suburb and state = postcode.state and postcode = postcode.postcode limit 0,1) as territory FROM postcode WHERE postcode.suburb LIKE '%".$this->db->escape_str($search)."%'  Order By postcode.suburb limit 0,20";
        }
        
        if ($type == 'postcode') {
            $sql = "SELECT distinct postcode FROM postcode WHERE postcode LIKE '".$this->db->escape_str($search)."%' Order By postcode.postcode  limit 0,20";
        }

        $query = $this->db->query($sql);
         return $query->result_array();
       
    }
    
     
    /**
    * This function Get territory
    *  
    * @return array - 
    */
    public function getTerritory() {
        $sql = "SELECT distinct territorydesc from territory";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    
    /**
    * This function Get territory for selected suburb,state,postcode
    * @param string $suburb - suburb
    * @param string $state - state
    * @param string $postcode - postcode
    * @return string - territory name
    */
    public function getLowestTerritory($suburb, $state, $postcode) {

        $this->db->select('territory');
        $this->db->from('postcodeterritory');
        $this->db->where('suburb', $suburb);
        $this->db->where('state', $state);
        $this->db->where('postcode', $postcode);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
            $result = $query->row();
            return $result->territory;
        }
        else
        {

            return '';
        }
    }
     
    
    /**
     * get branding image for particular parameter
     * 
    * @param string $brandLocationCode
     * @param string $brandTypeCode

     * @return array
     */
    public function getBrandingImage($brandLocationCode, $brandTypeCode) { 
         
        $this->db->select("documentid, docformat");
        $this->db->from('branding b');
        $this->db->join('brandtype bt', 'bt.id = b.brandtypeid', 'inner');
        $this->db->join('brand_location bl', 'bl.id=b.brandlocationid', 'inner');
          
        $this->db->where('b.isdeleted', 0);
        $this->db->where('bt.code', $brandTypeCode);
        $this->db->where('bl.code', $brandLocationCode);
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Branding Image : '. $this->db->last_query());
        //aklog('Get Branding Image : '. $this->db->last_query());
        return $data;
    }
    
    
    /**
     * get branding image for particular parameter
     * 
    * @param string $brandLocationCode
     * @param string $brandTypeCode

     * @return array
     */
    public function getBrandingImageByCustomerid($customerid, $brandLocationCode, $brandTypeCode) { 
         
        $this->db->select("b.id, documentid, docformat");
        $this->db->from('branding b');
        $this->db->join('brandtype bt', 'bt.id = b.brandtypeid', 'inner');
        $this->db->join('brand_location bl', 'bl.id=b.brandlocationid', 'inner');
        $this->db->where('b.customerid', $customerid);
        $this->db->where('b.isdeleted', 0);
        $this->db->where('bt.code', $brandTypeCode);
        $this->db->where('bl.code', $brandLocationCode);
        $data = $this->db->get()->row_array();
        
        $this->LogClass->log('Get Branding Image : '. $this->db->last_query());
        
        return $data;
    }
    
    
     /**
    * @desc This function inserting New Branding
    * @param array $brandingData
    * @return array - 
    */
    public function insertBranding($brandingData) {

         //var_dump($data);
        $this->db->insert('branding', $brandingData);
        return $this->db->insert_id();
      
     
    }
 
    /**
    * @desc This function update Branding
    * @param int $id
    * @param array $brandingData
    * @return array - 
    */
    public function updateBranding($id, $brandingData) {

        $this->db->where('id',$id);
        $this->db->update('branding', $brandingData);
      
    }
    
    /**
    * @desc This function  delete Branding
    * @param int $id
    * @return array - 
    */
    public function softDeleteBranding($id) {

        $this->db->where('id',$id);
        $this->db->update('branding',array('isdeleted'=>1));
     
    }
    
    /**
    * @desc This function  delete Branding
    * @param int $id
    * @return array - 
    */
    public function deleteBranding($id) {

        $this->db->where('id',$id);
        $this->db->delete('branding');
     
    }
    
    /**
     * get global setting value for particular setting name
     * 
     * @param string $type
     * @return mixed
     */
    public function getSettingValue($type) {

        $this->db->select('*');
        $this->db->where('type', $type);
     
        $this->db->from('settings');
        $query = $this->db->get();

        $data= $query->row_array();
        if ($data) {
            if(isset($data['value'])){
                return $data['value'];
            }
            else{
               return ''; 
            }
             
        }
        else {
            return '';
        }
        
        
    }
    
     /**
    * This function use for get proiority
    * @param string $rulename
    * @return array
    */     
    public function getEmailRuleByRuleName($rulename) 
    {
        $this->db->select("*");
        $this->db->from('emailrule');
        $this->db->where('rulename', $rulename);
        return  $this->db->get()->row_array();
        
    }
    
    /**
     * 
     * get email subject for particular rule
     * 
     * @param string $rulename
     * @return string
     */
    public function getEmailSubject($rulename)
    {
        $this->db->select("emailsubject");
        $this->db->from('emailrule');
        $this->db->where('rulename', $rulename);
        $data= $this->db->get()->row_array(); 
        $subject = '';
        if (count($data)>0) {
            $subject = $data['emailsubject'];
        }
        return $subject;  
    }

      
    /**
     * 
     * get email message body for particular rule
     * 
     * @param string $rulename
     * @param array $placeholders
     * @return string
     */
    public function getEmailBody($rulename, $placeholders = array())
    {
        $this->db->select("emailbody");
        $this->db->from('emailrule');
        $this->db->where('rulename', $rulename);
        $data= $this->db->get()->row_array(); 
         
        $template = '';
        if (count($data)>0) {
            $template = $data['emailbody'];
        }

        //extract header
        $header="";
        $ipos1 = strpos($template,"<header>") + strlen("<header>");
        $ipos2 = strpos($template,"</header>");
        if ($ipos1>0 && $ipos2>0) {
          $header = substr($template, $ipos1, $ipos2-$ipos1);
        }

        //extract repeating row portion from the template
        $rowpattern = "";
        $ipos1 = strpos($template,"<each>") + strlen("<each>");
        $ipos2 = strpos($template,"</each>");
        if (strpos($template,"<each>") > 0) {
            $rowpattern = substr($template, $ipos1, $ipos2-$ipos1);
        }

        //extract footer and append
        $footer = "";
        $ipos1 = strpos($template,"<footer>") + strlen("<footer>");
        $ipos2 = strpos($template,"</footer>");
        if ($ipos1>0 && $ipos2>0) {
          $footer = nl2br(substr($template, $ipos1, $ipos2-$ipos1));
          $footer = str_replace("!=!","=", $footer);

        }

        $message = $header."</br>";
        if ($rowpattern != "") {
            $message = $message.$rowpattern;
        } else {
           $message = $message.$template; 
        }
        $message = $message.$footer;

        foreach ($placeholders as $key=> $value) {
            $message =str_replace('<'.$key.'>', $value, $message);
            $message =str_replace($key, $value, $message);
        }

        return $message;
    }
    
   
    /**
    * This function Get a logged user detail by contact id
    * @param integer $contactid - the contact id by logged user
    * @return array - contact data Array
    */
    public function getLoggedUser($contactid) { 
        $this->db->select("contactid, customerid, contactid as id, raptorpassword, email, role, iscpmaster, active, last_login, firstname, surname, bossid, israptoradmin");
        $this->db->from('contact');
        $this->db->where('contactid', $contactid);
        $data= $this->db->get()->row_array();
        if(count($data)>0){
            if($data['iscpmaster'] == NULL || $data['iscpmaster']!=1)
            {
                if($data['role'] == NULL || $data['role'] == "")
                {
                    $data['role'] = 'site contact';
                }			

            }			
            else { 
                $data['role'] ='master';
            }
        }
        //$this->LogClass->log('Get Logged user detail Query : '. $this->db->last_query());
        return $data;
        
    }
    
    /**
     * getSubordinateEmails
     * @param string $email
     * @return string
     */
    public function getSubordinateEmails($email){
                                                	 
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
    * This function Get Menu Counter Value
    * @param interger $contactid - Logged User contactid
    * @return array - 
    */
    public function getMenuCounterValue($contactid) {
         
       
      
        
        
        $loggedUserData = $this->getLoggedUser($contactid);
        $ContactRules = $this->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
        $customerid = $loggedUserData['customerid'];
      
        $invoicewhr = '';
        if($loggedUserData['role'] != 'master') {
            if ((isset($ContactRules["show_invoice_by_fm_in_clientportal"]) && $ContactRules["show_invoice_by_fm_in_clientportal"] == "1")) {
                $invoicewhr = " and  j.sitefmemail='". $loggedUserData['email']."' "; 
            }
        }
        
        $jobwhr = '';
        if($loggedUserData['role'] == 'site contact') {
            $jobwhr = " and  a.sitecontactid='". $loggedUserData['contactid']."' "; 
        }
        elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->getSubordinateEmails($loggedUserData['email']);
            $jobwhr = " and   (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))"; 
        }
        
        $counterValue = array();
        $sql = " SELECT 'potentialjobs' AS `MEASURE`, COUNT(joblead_id) as counter FROM joblead jl INNER JOIN contact c ON jl.sitefmemail=c.email WHERE c.customerid=".$loggedUserData['customerid']." AND approvaldate IS NULL AND declineddate IS NULL AND IFNULL(jl.sitefmemail,'') != ''";
        $sql .= " UNION ";
        $sql .= " SELECT 'unapproveinvoices' AS `MEASURE`, count(i.invoiceno) as counter FROM invoice i INNER JOIN jobs j ON j.jobid=i.jobid INNER JOIN invoicelines l ON l.invoiceno=i.invoiceno INNER JOIN addresslabel a ON j.labelid=a.labelid WHERE i.customerid = ".$loggedUserData['customerid']." AND invdoctype = 'INVOICE' AND isfinalised = 1 AND isreversed =0 AND i.approvaldate IS NULL AND (i.completed != 'on' or i.completed is NULL) $invoicewhr";
        $sql .= " UNION ";
        $sql .= " SELECT 'unapprovejobs' AS `MEASURE`, COUNT(*) AS counter FROM jobs j LEFT JOIN jobstage js ON j.jobstage = js.jobstagedesc INNER JOIN addresslabel a ON j.labelid=a.labelid LEFT JOIN contact c ON a.contactid=c.contactid  WHERE j.customerid = ".$loggedUserData['customerid']." AND (j.quoterqd is null or j.quoterqd != 'on') AND j.leaddate > '2010-07-01' AND j.jobstage IN('portal_await_approval', 'Waiting_Client_Instructions') $jobwhr";
        $sql .= " UNION ";
        $sql .= " SELECT 'wiatingvariationjobs' AS `MEASURE`, COUNT(*) AS counter FROM jobs j LEFT JOIN jobstage js ON j.jobstage = js.jobstagedesc LEFT JOIN addresslabel a ON j.labelid=a.labelid LEFT JOIN contact c ON a.contactid=c.contactid LEFT JOIN contact jc ON j.contactid=jc.contactid LEFT JOIN customer_glchart cg ON j.custglchartid=cg.id WHERE j.customerid = ".$loggedUserData['customerid']." AND j.jobstage NOT IN('cancelled', 'hold', 'declined') AND j.leaddate > '2010-07-01' AND variationstage = 'variation_request_sent' $jobwhr";
        $sql .= " UNION ";
        $sql .= " SELECT 'unapprovequotes' AS `MEASURE`, COUNT(*) AS counter FROM jobs j LEFT JOIN jobstage js ON j.jobstage = js.jobstagedesc LEFT JOIN addresslabel a ON j.labelid=a.labelid LEFT JOIN contact c ON a.contactid=c.contactid  WHERE j.customerid = ".$loggedUserData['customerid']." AND (j.quoterqd = 'on') AND lcase(j.quotestatus)='pending_approval' AND j.leaddate > '2010-07-01' AND j.jobstage IN('wait_client_quote_resp', 'wait_client_qte_resp') $jobwhr";
        $sql .= " UNION ";
        $sql .= " SELECT 'unapprovewojobs' AS `MEASURE`, COUNT(*) AS counter FROM jobs j LEFT JOIN jobstage js ON j.jobstage = js.jobstagedesc INNER JOIN addresslabel a ON j.labelid=a.labelid LEFT JOIN contact c ON a.contactid=c.contactid  WHERE j.customerid = ".$loggedUserData['customerid']." AND j.leaddate > '2010-07-01' AND j.custordref='' AND (j.quoterqd is null or j.quoterqd !='on') AND (j.jobstage='Waiting_Client_Instructions' OR variationstage = 'variation_request_sent') $jobwhr";
        $sql .= " UNION ";
        $sql .= " SELECT 'jobdocuments' AS `MEASURE`, COUNT(*) AS counter  FROM document d INNER JOIN docfolder df ON d.doctype=df.caption INNER JOIN jobs j ON d.xrefid=j.jobid LEFT JOIN addresslabel a ON j.labelid=a.labelid WHERE j.customerid = ".$loggedUserData['customerid']." AND xreftable = 'jobs' AND df.is_raptor_active=1 and (d.doctype not in(select doctype from cp_jobdoc_exclusion Where isexcluded=1 and customerid=$customerid)) $jobwhr";
        $sql .= " UNION ";
        $sql .= " SELECT 'customerdocuments' AS `MEASURE`, COUNT(*) AS counter  FROM document d  INNER JOIN docfolder df ON d.doctype=df.caption  WHERE d.xrefid = ".$loggedUserData['customerid']." AND xreftable = 'customer'  AND df.is_raptor_active=1 ";
        $sql .= " UNION ";
        $sql .= " SELECT 'contactcounts' AS `MEASURE`, COUNT(*) AS counter  FROM contact c  WHERE c.customerid = ".$loggedUserData['customerid']."";
        $sql .= " UNION ";
        $sql .= " SELECT 'siteaddresscount' AS `MEASURE`, COUNT(*) AS counter  FROM addresslabel a  WHERE a.customerid = ".$loggedUserData['customerid']."";
        $sql .= " UNION ";
        $sql .= " SELECT 'addressattrcounts' AS `MEASURE`, COUNT(*) AS counter  FROM addresslabel_attribute aa INNER JOIN addresslabel_attribute_type aat ON aa.attributetypeid = aat.id WHERE customerid IN(0, ".$loggedUserData['customerid'].")";
        $sql .= " UNION ";
        $sql .= " SELECT 'suppliercounts' AS `MEASURE`, COUNT(*) AS counter  FROM customer s  WHERE s.ownercustomerid = ".$loggedUserData['customerid']."";
        $sql .= " UNION ";
        $sql .= " SELECT 'glcodecounts' AS `MEASURE`, COUNT(*) AS counter  FROM customer_glchart gl INNER JOIN account_type at ON gl.accounttype=at.code WHERE gl.customerid = ".$loggedUserData['customerid']."";
        $sql .= " UNION ";
        $sql .= " SELECT 'costcentrecounts' AS `MEASURE`, COUNT(*) AS counter  FROM customer_costcentre  WHERE customerid = ".$loggedUserData['customerid']."";
 
        $result = $this->db->query($sql)->result_array();

        foreach ($result as $row){
                $counterValue[$row['MEASURE']] = $row['counter'];
        }
        return $counterValue;
       
    }
    
    /**
    * This function Get access levels
    * 
    * @return array
    */
    public function getRole() {

        $this->db->select('role');
        $this->db->from('cp_accesslevel');
        
        
        $query = $this->db->get();

        return $query->result_array();
    }
    
    
    /**
    * This function Get functions for logged user customerid
    * 
    * @return array
    */
    public function getFunctions($customerid) {

        $this->db->select('functionname');
        $this->db->from('cp_contactsecurityfunction csf');
        $this->db->join('cp_module m', 'csf.moduleid = m.id', 'inner');
        $this->db->join('cp_module_access ma', 'ma.moduleid=m.id', 'inner');
        $this->db->where('csf.isactive', 1);
        $this->db->where('m.isactive', 1);
        $this->db->where('(ma.sitecontactaccess=1 OR ma.fmaccess=1 OR ma.masteraccess=1)');
        $this->db->where('ma.customerid', $customerid);
        $this->db->order_by('functionname', 'asc');
       
        $query = $this->db->get();

        return $query->result_array();
    }
	
	/**
     * 
     * get brand location by code
     * 
     * @param string $code
     * @return array
     */
    public function getBrandLocationByCode($code)
    {
        $this->db->select("*");
        $this->db->from('brand_location');
        $this->db->where('code', $code);
        $data= $this->db->get()->row_array(); 
        return $data;  
    }
	
	/**
     * 
     * get brand location by code
     * 
     * @param string $code
     * @return array
     */
    public function getBrandTypeByCode($code)
    {
        $this->db->select("*");
        $this->db->from('brandtype');
        $this->db->where('code', $code);
        $data= $this->db->get()->row_array(); 
        return $data;  
    }
     
     
    /* get Announcement
    * 
    * @param integer $contactid
    * @return array
    */
    public function getAnnouncement($contactid) { 
         
        $sql = "SELECT * FROM cp_message WHERE isactive=1 AND browser='' AND activationdate <= NOW() AND id NOT IN (SELECT messageid FROM cp_messagecontact WHERE contactid = $contactid)";
        $data = $this->db->query($sql)->row_array();
        $this->LogClass->log('Get Announcement : '. $this->db->last_query());
        
        return $data;
    }
    
    /* get Browser Announcement
    * 
    * @param integer $contactid
    * @param string $browser
    * @return array
    */
    public function getBrowserAnnouncement($contactid, $browser) { 
         
        $sql = "SELECT * FROM cp_message WHERE isactive=1 AND browser='$browser' AND activationdate <= NOW() AND id NOT IN (SELECT messageid FROM cp_messagecontact WHERE contactid = $contactid) ORDER BY browser_version";
        $data = $this->db->query($sql)->result_array();
        $this->LogClass->log('Get Announcement : '. $this->db->last_query());
        
        return $data;
    }
    
    /**
    * Insert MessaGE CONTACT
    * 
    * @param array $messagedata
    * @return integer
    */
    public function InsertMessageContact($messagedata) { 
         
        $messagedata['datedismissed'] = date('Y-m-d H:i:s');
        $this->db->insert('cp_messagecontact', $messagedata);
        return $this->db->insert_id();
      
    }
    
    /**
    * Delete MessaGE CONTACT
    * 
    * @param array $messagedata

    */
    public function DeleteMessageContact($messagedata) { 
         
        $this->db->where($messagedata);
        $this->db->delete('cp_messagecontact');
     
      
    }
    
    /**
     * 
     * get Side bar admin menu according to user access level
     * @return type
     */
 
     function getAdminNavigation() {
      
        $this->db->select('cp_admin_module.*');
        $this->db->from('cp_admin_module');
        $this->db->where('cp_admin_module.isactive', '1');
        $this->db->order_by('cp_admin_module.sortorder', 'asc');
        $navigation = $this->db->get()->result_array();
        
        
        return $navigation;
    }
    
   
    
     /**
    * @desc This function Get marketing content
    *
    * @return array - 
    */
    public function getMarketingData($customerid) { 
        //SELECT content,dwelltime from cp_marketing where isactive=1
        $this->db->select("*");
        $this->db->from('cp_marketing');
        $this->db->where('isactive', 1);
        $this->db->where_in('customerid', array(0, $customerid));
        $this->db->order_by('id');
        $data = $this->db->get()->result_array();
        
        $position = '3';
        $content = array();
        $i = -1;
        foreach($data as $key=>$value) {
            $value['position'] = $position;
            $content[$i][] = $value;
        }
        return $content;
    }
    
    /**
    * @desc This function process marketing content and send to controller
    * $params - array of parameters 
    * @return array - 
    */
    public function getMarketingContent($params) { 
        
        $response = array(
            array(
                'position' => 3,
                'content' => '',
                'dwelltime' => 0,
                'n' => 0
            )
        );
        
        $content = $this->getMarketingData($params['customerid']);
        
        $i=0;
        foreach($content as $key=>$value) {
            
            foreach($value as $key1=>$value1) {
                  
                if($value1['position'] == 3) {
                    if($i==0) {
                        $first = $value1['id'];
                        $response[0]['content'] = $value1['content'];
                        $response[0]['dwelltime'] = $value1['dwelltime'];
                        $response[0]['n'] = $value1['id'];
                    }
                    $i++;
                    if($value1['id'] > $params['positionThird']) {
                        $first = $value1['id'];
                        $response[0]['content'] = $value1['content'];
                        $response[0]['dwelltime'] = $value1['dwelltime'];
                        $response[0]['n'] = $value1['id'];
                        break;
                    }
                }
            }
        }
        return $response;
    }
    
    /**
    * @desc This function Get marketing messages
    *
    * @return array - 
    */
    public function getMarketingMessages() { 

        $this->db->select("caption, content");
        $this->db->from('cp_message');
        $this->db->where('isactive', 1);
        $this->db->where('ispersistent', 1);
        $this->db->order_by('id');
        return $this->db->get()->result_array();
    }
    
    public function etpSettings($setting_name, $supplierid) {

         $this->db->select('s.*');
         $this->db->where('s.setting_name', $setting_name);
         $this->db->where('s.supplierid', $supplierid);
         $this->db->from('etp_setting s');
         //$this->db->join('etp_settingname sn', 's.setting_name =sn.name');
         $this->db->join('etp_setting_type t', 't.id =s.setting_typeid', 'left');
         $data = $this->db->get()->row();
         if ($data) {
              return $data->setting_value;
         }
         else{

             $this->db->select('s.*');
             $this->db->where('s.setting_name', $setting_name);
             $this->db->where('s.supplierid', '0');
             $this->db->from('etp_setting s');
             //$this->db->join('etp_settingname sn', 's.setting_name =sn.name');
             $this->db->join('etp_setting_type t', 't.id =s.setting_typeid', 'left');
             $data = $this->db->get()->row();
             if ($data) {
                  return $data->setting_value;
             }
             else{


                 return false;
             }
         }
    }
    
     /**
    * @desc This function get settings for logged user
    * @param integer $contactid - logged contactid
    * @param string $code - setting code
    * @return array - 
    */
    public function getSettings($contactid, $code) {
        $this->db->select('*');
        $this->db->where('contactid', $contactid);
        $this->db->where('code', $code);
        $this->db->from('etp_user_preference');
        $query = $this->db->get();

        return $query->row_array();
    }
    
    /**
    * @desc This function insert/update settings for logged user
    * @param integer $contactid - logged contactid
    * @param string $code - setting code
    * @param string $value - setting code value
    * @return array - 
    */
    public function saveSettings($contactid, $code, $value) {
        $data = $this->getSettings($contactid, $code);
        if (count($data)==0) {
            $insert = array(
                         'contactid'=> $contactid,
                         'code'=> $code,
                         'data'=> $value
                      );
            $this->db->insert('etp_user_preference', $insert);
        } else {
            $update = array(
                         'data'=> $value
                      );

            $this->db->where(array('contactid'=> $contactid, 'code'=> $code));
            $this->db->update('etp_user_preference', $update);
        }
    }
}

/* End of file SharedClass.php */