<?php 
/**
 * Insert Customer PO Jobs Chain Libraries Class
 *
 * This is a InsertCustomerPurchaseOrderJobChain class for insert Customer PO Jobs
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertCustomerPurchaseOrderJobChain Libraries Class
 *
 * This is a InsertCustomerPurchaseOrderJobChain class for insert Customer PO Jobs
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          InsertCustomerPurchaseOrderJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class InsertCustomerPurchaseOrderJobChain extends AbstractPurchaseOrderChain {
 
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
    * This function use for insert Customer PO Jobs
     * 
    * @param array $request - the request is array of required parameter for insert user security
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'InsertCustomerPurchaseOrderJobChain');
         
        
        $insertData = $request['insertCustomerPOJobData'];
        
        if (count($insertData)>0) {
            $this->db->insert_batch('customer_purchaseorder_job', $insertData);
            $logClass->log('Insert Customer PO Jobs Query : '. $this->db->last_query());
        }
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertCustomerPurchaseOrderJobChain.php */