<?php 
/**
 * InsertContractScheduleSitesChain Libraries Class
 *
 * This is a Cost Centre Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertContractScheduleSitesChain Libraries Class
 *
 * This is a Cost Centre Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          InsertContractScheduleSitesChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertContractScheduleSitesChain extends AbstractContractorChain
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
        $logClass= new LogClass('jobtracker', 'InsertContractScheduleSitesChain');
   
        
        $insertScheduleAddressData = $request['insertScheduleAddressData'];
        if (count($insertScheduleAddressData)>0) {
            $this->db->insert_batch('con_schedule_address', $insertScheduleAddressData);
            $logClass->log('Insert Contract Schedule Site Query : '. $this->db->last_query());
        }
         
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertContractScheduleSitesChain.php */