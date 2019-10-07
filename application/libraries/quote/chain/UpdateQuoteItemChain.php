<?php 
/**
 * UpdateQuoteItemChain Libraries Class
 *
 *  This is a Quote Chain class for update Quote Item Record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractQuoteChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateQuoteItemChain Libraries Class
 *
 *  This is a Quote Chain class for update Quote Item Record
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          UpdateQuoteItemChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class UpdateQuoteItemChain extends AbstractQuoteChain{
 
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
    * This function use for update quote Line
     * 
    * @param array $request - the request is array of required parameter for update quote Item
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateQuoteItemChain');
        $params = $request['params'];
        $quoteItemData = $request['quoteItemData'];
        $this->db->where('rfqno', $params['rfqno']);
        $this->db->where('id', $params['quoteitemid']);
        $this->db->update('rfq_line', $quoteItemData); 
        
        $logClass->log('Update rfq_line Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

          //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateQuoteItemChain.php */