<?php 
/**
 * Audit Log Chain Libraries Class
 *
 * This is a ContractAuditLogChain class for create a new records in con_auditlog
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Audit Log Chain Libraries Class
 *
 * This is a ContractAuditLogChain class for create a new records in con_auditlog Table
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          ContractAuditLogChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class ContractAuditLogChain extends AbstractContractorChain
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
    * This function use for create Con Audit Log
      * 
    * @param array $request - the request is array of required parameter for creating Audit Log Records
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'ContractAuditLogChain');
        
        $conAuditLogData = $request['conAuditLogData'];
        if (count($conAuditLogData)>0) {
            $this->db->insert_batch('con_auditlog', $conAuditLogData);
            $logClass->log('Insert Contract Audit Log Query : '. $this->db->last_query());
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
    }

}

/* End of file ContractAuditLogChain.php */