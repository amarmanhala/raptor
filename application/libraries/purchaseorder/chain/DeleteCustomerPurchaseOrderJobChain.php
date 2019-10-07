<?php 
/**
 * Delete DeleteCustomerPurchaseOrderJobChain Libraries Class
 *
 * This is a CDeleteCustomerPurchaseOrderJobChain class for Delete Customer Purchase Order
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Cost Centre Value Libraries Class
 *
 * This is a DeleteCustomerPurchaseOrderJobChain class for delete Delete Customer Purchase Order
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          DeleteCustomerPurchaseOrderJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteCustomerPurchaseOrderJobChain extends AbstractPurchaseOrderChain
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
        $logClass= new LogClass('jobtracker', 'DeleteCustomerPurchaseOrderJobChain');
         
        $params = $request['params'];
       
        if(isset($params['id'])){
            if(is_array($params['id'])){
                $this->db->where_in('id', $params['id']);
            }
            else{
                $this->db->where('id', $params['id']);
            }
        }
        if(isset($params['jobid'])){
            if(is_array($params['jobid'])){
                $this->db->where_in('jobid', $params['jobid']);
            }
            else{
                $this->db->where('jobid', $params['jobid']);
            }
        }
        $this->db->where('ponumber', $params['ponumber']);
        $this->db->delete('customer_purchaseorder_job'); 
        
        $logClass->log('Delete Customer Purchase Order Chain Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteCustomerPurchaseOrderJobChain.php */