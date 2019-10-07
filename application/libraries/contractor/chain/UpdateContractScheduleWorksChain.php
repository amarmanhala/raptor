<?php 
/**
 * UpdateContractScheduleWorksChain Libraries Class
 *
 * This is a Contract Schedule class for update Contract Schedule
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateContractScheduleWorksChain Libraries Class
 *
 * This is a Contract Schedule class for update Contract Schedule
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          UpdateContractScheduleWorksChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateContractScheduleWorksChain extends AbstractContractorChain{
 
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
            
        $logClass= new LogClass('jobtracker', 'UpdateContractScheduleWorksChain');
         
    
        $updateScheduleWorkData = $request['updateScheduleWorkData']; 
        
        if (count($updateScheduleWorkData)>0) {
            $this->db->update_batch('con_schedule', $updateScheduleWorkData, 'id');
            $logClass->log('Update Contract Schedule Work Query : '. $this->db->last_query());
        }
         
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateContractScheduleWorksChain.php */