<?php 
/**
 * Insert Portal Settings Chain Libraries Class
 *
 * This is a InsertPortalSettingChain class for update Portal Settings
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertPortalSettingChain Libraries Class
 *
 * This is a InsertPortalSettingChain class for update Portal Settings
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertPortalSettingChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class InsertPortalSettingChain extends AbstractCustomerChain {
 
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
    * This function use for insert Portal Setting Chain
     * 
    * @param array $request - the request is array of required parameter for update user security
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'InsertPortalSettingChain');
      
        $insertData = $request['insertData'];
        
        $this->db->insert('customer_rule_customer', $insertData);
        $id =  $this->db->insert_id();
        $request["crc_id"] = $id;

        $logClass->log('Insert Portal Settings Query : '. $this->db->last_query());
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertPortalSettingChain.php */