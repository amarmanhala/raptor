<?php 
/**
 * InsertBatchInvoiceChain Libraries Class
 *
 * This is a Invoice Chain class for create a new batch invoice
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractInvoiceChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertBatchInvoiceChain Libraries Class
 *
 * This is a Invoice Chain class for create a new batch invoice
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Invoice/chain
 * @filesource          InsertBatchInvoiceChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertBatchInvoiceChain extends AbstractInvoiceChain
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
    * This function use for create address record
      * 
    * @param array $request - the request is array of required parameter for creating address
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertBatchInvoiceChain');
        
        $batchData = $request['batchData'];
        $batchLineData = $request['batchLineData'];
        $updateInvoiceData = $request['updateInvoiceData'];
        
        $this->db->insert('invoice_batch', $batchData);
        $batchid =  $this->db->insert_id();
        
        $request["batchid"] = $batchid;
        $logClass->log('Insert invoice batch Query : '. $this->db->last_query());
        
        foreach ($batchLineData as $key => $value) {
            $batchLineData[$key]['batchid'] = $batchid;
        }
        
        foreach ($updateInvoiceData as $key => $value) {
            $updateInvoiceData[$key]['batchid'] = $batchid;
        }
        
         
        if (count($batchLineData)>0) {
            $this->db->insert_batch('invoice_batchline', $batchLineData);
            $logClass->log('Insert invoice batchline Query : '. $this->db->last_query());
        }
        
        $request['updateInvoiceData'] = $updateInvoiceData;
       

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertBatchInvoiceChain.php */