<?php 
/**
 * UpdatePurchaseOrderChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for update purchase order
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdatePurchaseOrderChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for update purchase order
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          UpdatePurchaseOrderChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class UpdatePurchaseOrderChain extends AbstractPurchaseOrderChain
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
    */
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }
 
    /**
    * This function use for update purchase order
     * 
    * @param array $request - the request is array of required parameter for updating PO
    * @return array
    */
    public function handleRequest($request)
    {
        $params = $request['params'];
        
        $updatePOData = $request['updatePOData'];
        
        $this->db->where('poref', $params['poref']);
        $this->db->update('purchaseorders', $updatePOData);
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdatePurchaseOrderChain.php */