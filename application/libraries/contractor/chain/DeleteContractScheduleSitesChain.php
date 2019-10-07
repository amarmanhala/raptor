<?php 
/**
 * Delete ContractSchedule Site Value Libraries Class
 *
 * This is a ContractSchedule Site Value class for delete in ContractSchedule Site
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete ContractSchedule Site Value Libraries Class
 *
 * This is a ContractSchedule Site Value class for delete ContractSchedule Site
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          DeleteContractScheduleSitesChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContractScheduleSitesChain extends AbstractContractorChain
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
    * This function use for delete Contract Schedule Site
      * 
    * @param array $request - the request is array of required parameter for delete Contract Schedule Site
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContractScheduleSitesChain');
         
         $deleteScheduleAddressData = $request['deleteScheduleAddressData'];
        if (count($deleteScheduleAddressData)>0) {
            foreach ($deleteScheduleAddressData as $key => $value) {
                $this->db->delete('con_schedule_address', $value); 
            }
            
        }
       
        $logClass->log('Delete Contract Schedule Query : '. $this->db->last_query());
      
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContractScheduleSitesChain.php */