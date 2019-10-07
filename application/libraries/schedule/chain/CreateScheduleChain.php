<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('AbstractScheduleChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Project: ETP Falcon
 * Package: CI
 * Subpackage: Libraries\Job\Chain
 * File: CreateScheduleChain.php
 * Description: This is a CreateScheduleChain class for create new schedule
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */

class CreateScheduleChain extends AbstractScheduleChain
{
  
    private $successor;
  
    /**
    * @desc This function set Successor for next Service class which one execute after the handleRequest
    * @param Class $nextService - class for execute  
    */
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }
 
    /**
    * @desc This function use for create new Schedule
    * @param array $request - the request is array of required parameter for creating schedule job
    * @return bool -  success or failure
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'CreateScheduleChain');
         
       	$scheduleData = $request['scheduleData'];	
        $this->db->insert('etp_diary', $scheduleData);

        $request["diaryId"] = $this->db->insert_id();
       
        $logClass->log('Create Etp_diary Query : '. $this->db->last_query());
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

			
    }
}


/* End of file CreateScheduleChain.php */