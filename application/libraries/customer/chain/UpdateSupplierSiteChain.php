<?php 
/**
 * UpdateSupplierSiteChain Libraries Class
 *
 * This is a Suppliersite class for update customer
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateSupplierSiteChain Libraries Class
 *
 * This is a Cost Centre class for update customer
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateSupplierSiteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateSupplierSiteChain extends AbstractCustomerChain{
 
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
            
        $logClass= new LogClass('jobtracker', 'UpdateSupplierSiteChain');
         
        $params = $request['params'];

        $updateSiteData = $request['updateSiteData']; 
      
        $this->db->where('id', $params['siteid']);
        $this->db->update('cp_supplier_address', $updateSiteData); 
        
        $logClass->log('Update Supplier Site Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateSupplierSiteChain.php */