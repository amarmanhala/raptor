<?php 
/**
 * Update Portal Settings Chain Libraries Class
 *
 * This is a UpdatePortalSettingChain class for update Portal Settings
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdatePortalSettingChain Libraries Class
 *
 * This is a UpdatePortalSettingChain class for update Portal Settings
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdatePortalSettingChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdatePortalSettingChain extends AbstractCustomerChain {
 
    /**
    * next chain class 
      * 
    * @var class
    *  
    */
    private $successor;

    /**
    * This function set Successor for next Service class which one execute after the handleRequest
     * 
    * @param Class $nextService - class for execute  
    */
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }
 
    /**
    * This function use for update user security
     * 
    * @param array $request - the request is array of required parameter for update user security
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdatePortalSettingChain');
         
        $params = $request['params'];
        $updateData = $request['updateData'];
 
        $this->db->where('customer_rule_id', $params['rulename_id']);
        $this->db->where('customerid', $params['customerid']);
        $this->db->update('customer_rule_customer', $updateData); 
        
        $logClass->log('Update Portal Settings Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdatePortalSettingChain.php */