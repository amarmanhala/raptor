<?php 
/**
 * Create User Security Audit Log Chain Libraries Class
 *
 * This is a Create User Security Audit Log Chain class for create a new User Security Audit Log 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Create User Security Audit Log Chain Libraries Class
 *
 * This is a Create User Security Audit Log Chain class for create address attribute
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UserSecurityAuditLogChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UserSecurityAuditLogChain extends AbstractCustomerChain
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
    * This function use for create address record
      * 
    * @param array $request - the request is array of required parameter for creating address
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'UserSecurityAuditLogChain');
        
        $auditLogData = $request['auditLogData'];
        
        if (count($auditLogData)>0) {
            $this->db->insert_batch('cp_contactsecurity_auditlog', $auditLogData);
            $logClass->log('Insert Contact Security Audit Log Query : '. $this->db->last_query());
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}

/* End of file UserSecurityAuditLogChain.php */