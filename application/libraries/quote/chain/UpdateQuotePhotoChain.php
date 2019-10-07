<?php 
/**
 * UpdateQuotePhotoChain Libraries Class
 *
 *  This is a Quote Chain class for update Quote Image Record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractQuoteChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateQuotePhotoChain Libraries Class
 *
 *  This is a Quote Chain class for update Quote Image Record
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          UpdateQuotePhotoChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateQuotePhotoChain extends AbstractQuoteChain{
 
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
    * This function use for update quote Photo
     * 
    * @param array $request - the request is array of required parameter for update quote Photo
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateQuotePhotoChain');
        $params = $request['params'];
        $quotePhotoData = $request['quotePhotoData'];
        $this->db->where('rfqno', $params['rfqno']);
        $this->db->where('id', $params['id']);
        $this->db->update('rfq_image', $quotePhotoData); 
         
        
        $logClass->log('Update rfq_image Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

          //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateQuotePhotoChain.php */