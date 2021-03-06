<?php 
/**
 * UpdateContractedHoursChain Libraries Class
 *
 * This is a Contract Technician class for update Contract Technician
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateContractedHoursChain Libraries Class
 *
 * This is a Contract Technician class for update Contract Technician
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          UpdateContractedHoursChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateContractedHoursChain extends AbstractContractorChain{
 
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
    * This function use for update Technician
     * 
    * @param array $request - the request is array of required parameter for update Technician
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateContractedHoursChain');
         
        $params = $request['params']; 
        $updateScheduleData = $request['updateContractedHoursData']; 
        
        $this->db->where('id', $params['contractedhoursid']); 
        $this->db->update('con_contracted_hours', $updateScheduleData); 
        
        $logClass->log('Update Contracted Hours Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateContractedHoursChain.php */