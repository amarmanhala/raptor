<?php 
/**
 * DeleteQuotePhotoChain Libraries Class
 *
 *  This is a Quote Chain class for delete Quote Image
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractQuoteChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * DeleteQuotePhotoChain Libraries Class
 *
 *  This is a Quote Chain class for delete Quote Image
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          DeleteQuotePhotoChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteQuotePhotoChain extends AbstractQuoteChain
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
    * This function use for Delete Quote Photo
      * 
    * @param array $request - the request is array of required parameter for Delete Quote Photo
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteQuotePhotoChain');
  
        $params = $request['params'];
        
        $this->db->where('rfqno', $params['rfqno']);
        $this->db->where('id', $params['id']);
        $this->db->delete('rfq_image'); 
         
        
        $logClass->log('Delete Quote Photo Query : '. $this->db->last_query());
        
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file DeleteQuotePhotoChain.php */