<?php 
/**
 * Delete DeleteCustomerPurchaseOrderChain Libraries Class
 *
 * This is a CDeleteCustomerPurchaseOrderChain class for Delete Customer Purchase Order
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Cost Centre Value Libraries Class
 *
 * This is a DeleteCustomerPurchaseOrderChain class for delete Delete Customer Purchase Order
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          DeleteCustomerPurchaseOrderChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteCustomerPurchaseOrderChain extends AbstractPurchaseOrderChain
{
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
    *  
    */
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }

     /**
    * This function use for Delete Customer Purchase Order 
      * 
    * @param array $request - the request is array of required parameter for delete attribute value
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteCustomerPurchaseOrderChain');
         
        $params = $request['params'];
 
        $this->db->where('id', $params['customer_po_id']);
        $this->db->delete('customer_purchaseorder'); 
        
        $logClass->log('Delete Customer Purchase Order Chain Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteCustomerPurchaseOrderChain.php */