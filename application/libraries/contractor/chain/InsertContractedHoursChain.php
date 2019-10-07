<?php 
/**
 * InsertContractedHoursChain Libraries Class
 *
 * This is a Technician Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertContractedHoursChain Libraries Class
 *
 * This is a Technician Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          InsertContractedHoursChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertContractedHoursChain extends AbstractContractorChain
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
        $logClass= new LogClass('jobtracker', 'InsertContractedHoursChain');
        
        $insertScheduleData = $request['insertContractedHoursData'];
        
        $this->db->insert('con_contracted_hours', $insertScheduleData);
        $id =  $this->db->insert_id();
        $request["contractedhoursid"] = $id;

        $logClass->log('Insert Contracted Hours Query : '. $this->db->last_query());

        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertContractedHoursChain.php */