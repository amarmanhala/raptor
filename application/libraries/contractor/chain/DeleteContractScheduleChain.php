<?php 
/**
 * Delete ContractSchedule Value Libraries Class
 *
 * This is a ContractSchedule Value class for delete in ContractSchedule
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete ContractSchedule Value Libraries Class
 *
 * This is a ContractSchedule Value class for delete ContractSchedule
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          DeleteContractScheduleChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContractScheduleChain extends AbstractContractorChain
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
    * This function use for delete Contract Site
      * 
    * @param array $request - the request is array of required parameter for delete Contract Site
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContractScheduleChain');
         
        
        $params = $request['params'];
        
        
 
        $this->db->where('id', $params['scheduleid']);
        $this->db->where('contractid', $params['contractid']);
        $this->db->delete('con_schedule_def'); 
        
        $logClass->log('Delete Contract Schedule Query : '. $this->db->last_query());
         
        
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContractScheduleChain.php */