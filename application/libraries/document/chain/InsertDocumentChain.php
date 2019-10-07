<?php 
/**
 * InsertDocumentChain Libraries Class
 *
 * This is a Document Chain class for create a new record in document
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractDocumentChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertDocumentChain Libraries Class
 *
 * This is a Document Chain class for create a new record in document
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Document/chain
 * @filesource          InsertDocumentChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertDocumentChain extends AbstractDocumentChain
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
    * This function use for create document record
      * 
    * @param array $request - the request is array of required parameter for creating document
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertDocumentChain');
        
        $documentData = $request['documentData'];
             
        $this->db->insert('document', $documentData);
        $id =  $this->db->insert_id();
        
        $request["documentid"] = $id;
        $logClass->log('Insert Document Query : '. $this->db->last_query());
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
        
        //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertDocumentChain.php */