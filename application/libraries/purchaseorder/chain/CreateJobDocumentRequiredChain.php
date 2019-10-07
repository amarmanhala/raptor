<?php 
/**
 * CreateJobDocumentRequiredChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for create  job request Document
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * CreateJobDocumentRequiredChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for create  job request Document
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          CreateJobDocumentRequiredChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class CreateJobDocumentRequiredChain extends AbstractPurchaseOrderChain
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
    */
    public function setSuccessor($nextService)
    {
        $this->successor=$nextService;
    }
 
    /**
    * This function use for create Job document required for po
     * 
    * @param array $request - the request is array of required parameter for creating JDR Document
    * @return array
    */
    public function handleRequest($request)
    {
        
       $logClass= new LogClass('jobtracker', 'CreateJobDocumentRequiredChain');
        
        if(isset($request['requiredJobDocuments'])){
            $requiredJobDocuments = $request['requiredJobDocuments'];
            if (count($requiredJobDocuments)>0) {
                $this->db->insert_batch('job_document_required', $requiredJobDocuments);
                $logClass->log('Insert job_document_required Query : '. $this->db->last_query());
            }
        }
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
 	
    }
	
}


/* End of file CreateJobDocumentRequiredChain.php */