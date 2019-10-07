<?php 
/**
 * DeleteDocumentChain Libraries Class
 *
 * This is a Document Chain class for delete document
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractDocumentChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * DeleteDocumentChain Libraries Class
 *
 * This is a Document Chain class for delete document
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Document/chain
 * @filesource          DeleteDocumentChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteDocumentChain extends AbstractDocumentChain
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
    * This function use for delete document
      * 
    * @param array $request - the request is array of required parameter for delete document
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteDocumentChain');
          
        $params = $request['params'];
        
        $this->db->where('documentid', $params['documentid']);
    
        $this->db->delete('document'); 
         
        
        $logClass->log('Delete Document Query : '. $this->db->last_query());
        
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file DeleteDocumentChain.php */