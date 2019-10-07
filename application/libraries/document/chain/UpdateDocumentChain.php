<?php 
/**
 * UpdateDocumentChain Libraries Class
 *
 * This is a UpdateDocumentChain class for update document
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractDocumentChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateDocumentChain Libraries Class
 *
 * This is a UpdateDocumentChain class for update document
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Document/chain
 * @filesource          UpdateDocumentChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateDocumentChain extends AbstractDocumentChain{
 
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
    * This function use for update document record
     * 
    * @param array $request - the request is array of required parameter for update document data
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateDocumentChain');
         
        $documentData = $request['documentData'];
        $params = $request['params'];
        
        $this->db->where('documentid', $params['documentid']);
        $this->db->update('document', $documentData); 
        
        $logClass->log('Update Document Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
        
    }
}


/* End of file UpdateDocumentChain.php */