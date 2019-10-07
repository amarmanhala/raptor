<?php 
/**
 * InsertQuotePhotoChain Libraries Class
 *
 *  This is a Quote Chain class for create new Quote Image Record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractQuoteChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertQuotePhotoChain Libraries Class
 *
 *  This is a Quote Chain class for create new Quote Image Record
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          InsertQuotePhotoChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertQuotePhotoChain extends AbstractQuoteChain
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
    * This function use for create quote photo records
      * 
    * @param array $request - the request is array of required parameter for creating Quote Photo
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertQuotePhotoChain');
        
        $quotePhotoData = $request['quotePhotoData'];
        if (isset($request['documentid'])) {
            $quotePhotoData['documentid'] = $request['documentid'];
        } 
        
        //Getting Max Sortorder
        $max_record = $this->db->select_max('sortorder')->where('rfqno', $quotePhotoData['rfqno'])->get('rfq_image')->row_array();
        $quotePhotoData['sortorder'] =(int)$max_record['sortorder']+1;
    
        
        $this->db->insert('rfq_image', $quotePhotoData);
        $id =  $this->db->insert_id();
        
        $request["rfq_imageid"] = $id;
        $logClass->log('Insert Quote Photo Query : '. $this->db->last_query());
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertQuotePhotoChain.php */