<?php 
/**
 * UpdateCustomerPurchaseOrderChain Libraries Class
 *
 * This is a CustomerPurchaseOrder class for update CustomerPurchaseOrder
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateCustomerPurchaseOrderChain Libraries Class
 *
 * This is a CustomerPurchaseOrder class for update CustomerPurchaseOrder
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          UpdateCustomerPurchaseOrderChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateCustomerPurchaseOrderChain extends AbstractPurchaseOrderChain{
 
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
    * This function use for update Customer Purchase Order
     * 
    * @param array $request - the request is array of required parameter for update customer_purchaseorder
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateCustomerPurchaseOrderChain');
         
        $params = $request['params'];

        $updateData = $request['updateCustomerPOData']; 
      
        $this->db->where('id', $params['customer_po_id']);
        $this->db->update('customer_purchaseorder', $updateData); 
        
        $logClass->log('Update ccustomer_purchaseorder Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateCustomerPurchaseOrderChain.php */