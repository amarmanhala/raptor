<?php 
/**
 * UpdateContractScheduleChain Libraries Class
 *
 * This is a Contract Schedule class for update Contract Schedule
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateContractScheduleChain Libraries Class
 *
 * This is a Contract Schedule class for update Contract Schedule
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          UpdateContractScheduleChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateContractScheduleChain extends AbstractContractorChain{
 
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
    * This function use for update customer
     * 
    * @param array $request - the request is array of required parameter for update customer
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateContractScheduleChain');
         
        $params = $request['params']; 
        $updateScheduleData = $request['updateConScheduleData']; 
        
        $this->db->where('id', $params['scheduleid']);
        $this->db->where('contractid',   $params['contractid']);
        $this->db->update('con_schedule_def', $updateScheduleData); 
        
        $logClass->log('Update Contract Schedule Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateContractScheduleChain.php */