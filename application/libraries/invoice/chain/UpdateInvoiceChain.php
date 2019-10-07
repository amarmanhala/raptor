<?php 
/**
 * UpdateInvoiceChain Libraries Class
 *
 * This is a Invoice Chain Class use for update invoice
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractInvoiceChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateInvoiceChain Libraries Class
 *
 * This is a Invoice Chain Class use for update invoice
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            invoice/chain
 * @filesource          UpdateInvoiceChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class UpdateInvoiceChain extends AbstractInvoiceChain{
 
    
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
    * This function use for update invoice
     * 
    * @param array $request - the request is array of required parameter for update update invoice
     * 
    * @return array
    */
    public function handleRequest($request)
    {
             
        $logClass= new LogClass('jobtracker', 'UpdateInvoiceChain');
        
        $updateInvoiceData = $request['updateInvoiceData'];
        if (count($updateInvoiceData)>0) {
            $this->db->update_batch('invoice', $updateInvoiceData, 'invoiceno');
            $logClass->log('Update Invoice Query : '. $this->db->last_query());
        }
      
      
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;


    }
}


/* End of file UpdateInvoiceChain.php */