<?php 
/**
 * Create Portal Settings Audit Log Chain Libraries Class
 *
 * This is a Create Portal Settings Audit Log Chain class for create a new Portal Settings Audit Log 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Create Portal Settings Audit Log Chain Libraries Class
 *
 * This is a Create Portal Settings Audit Log Chain class for create audit log
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          PortalSettingAuditLogChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class PortalSettingAuditLogChain extends AbstractCustomerChain
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
    * @param array $request - the request is array of required parameter for audit log
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'PortalSettingAuditLogChain');
        
        $auditLogData = $request['auditLogData'];
        
        if (count($auditLogData)>0) {
            $this->db->insert('cp_portalsetup_auditlog', $auditLogData);
            $logClass->log('Insert Portal Setup Audit Log Query : '. $this->db->last_query());
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}

/* End of file PortalSettingAuditLogChain.php */