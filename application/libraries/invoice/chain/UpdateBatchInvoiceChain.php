<?php 
/**
 * UpdateBatchInvoiceChain Libraries Class
 *
 * This is a invoice chain class for Update invoice batch
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractInvoiceChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateBatchInvoiceChain Libraries Class
 *
 * This is a invoice chain class for Update invoice batch
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            invoice/chain
 * @filesource          UpdateBatchInvoiceChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateBatchInvoiceChain extends AbstractInvoiceChain{
 
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
    * This function use for Update invoice batch
     * 
    * @param array $request - the request is array of required parameter for Update invoice batch
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateBatchInvoiceChain');
         
        $params = $request['params'];
        $updateBatchData = $request['updateBatchData'];
         
 
        $this->db->where('id', $params['batchid']);
        $this->db->update('invoice_batch', $updateBatchData); 
        
        $logClass->log('Update invoice batch Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateBatchInvoiceChain.php */