<?php 
/**
 * UpdateQuoteChain Libraries Class
 *
 *  This is a Quote Chain class for update Quote Record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractQuoteChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateQuoteChain Libraries Class
 *
 *  This is a Quote Chain class for update Quote Record
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          UpdateQuoteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateQuoteChain extends AbstractQuoteChain{
 
    
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
    * This function use for update Quote
     * 
    * @param array $request - the request is array of required parameter for Update Quote
    * @return array
    */
    public function handleRequest($request)
    {
             
        $logClass= new LogClass('jobtracker', 'UpdateQuoteChain');
        
        $updateparams = $request['updateparams'];
        $params = $request['params'];
        $this->db->where('rfqno', $params['rfqno']);
        $this->db->update('rfq', $updateparams); 
       
        $logClass->log('Update Quote Query : '. $this->db->last_query());
      
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

          //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateQuoteChain.php */