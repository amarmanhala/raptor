<?php 
/**
 * InsertQuoteItemChain Libraries Class
 *
 *  This is a Quote Chain class for create new Quote Item Record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractQuoteChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertQuoteItemChain Libraries Class
 *
 *  This is a Quote Chain class for create new Quote Item Record
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          InsertQuoteItemChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertQuoteItemChain extends AbstractQuoteChain
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
    * This function use for create quote item line record
      * 
    * @param array $request - the request is array of required parameter for creating Quote Item
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertQuoteItemChain');
        
        $quoteItemData = $request['quoteItemData'];
        //Getting Max Sortorder
        $max_record = $this->db->select_max('linenum')->where('rfqno', $quoteItemData['rfqno'])->get('rfq_line')->row_array();
        $quoteItemData['linenum'] =(int)$max_record['linenum']+1;     
        $this->db->insert('rfq_line', $quoteItemData);
        $id =  $this->db->insert_id();
        
        $request["id"] = $id;
        $logClass->log('Insert Quote Item Line Query : '. $this->db->last_query());
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertQuoteItemChain.php */