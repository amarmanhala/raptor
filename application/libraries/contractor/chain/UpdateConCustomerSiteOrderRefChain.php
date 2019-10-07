<?php 
/**
 * UpdateConCustomerSiteOrderRefChain Libraries Class
 *
 * This is a Con Customer Site Order Ref class for update Contract Schedule
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateConCustomerSiteOrderRefChain Libraries Class
 *
 * This is a Con Customer Site Order Ref class for update Contract Schedule
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          UpdateConCustomerSiteOrderRefChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateConCustomerSiteOrderRefChain extends AbstractContractorChain{
 
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
    * This function use for update customer
     * 
    * @param array $request - the request is array of required parameter for update customer
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateConCustomerSiteOrderRefChain');
         
    
        $updateCustomerSiteOrderData = $request['updateCustomerSiteOrderData']; 
        
        if (count($updateCustomerSiteOrderData)>0) {
            $this->db->update_batch('con_customer_order_reference', $updateCustomerSiteOrderData, 'id');
            $logClass->log('Update Con Customer Site Order Ref Query : '. $this->db->last_query());
        }
         
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateConCustomerSiteOrderRefChain.php */