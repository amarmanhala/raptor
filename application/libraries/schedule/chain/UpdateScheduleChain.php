<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('AbstractScheduleChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Project: ETP Falcon
 * Package: CI
 * Subpackage: Libraries\Job\Chain
 * File: UpdateScheduleChain.php
 * Description: This is a UpdateScheduleChain class for update etp_diary
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */

class UpdateScheduleChain extends AbstractScheduleChain
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
        
        $logClass= new LogClass('jobtracker', 'UpdateScheduleChain');
        
        $scheduleParams = $request['scheduleParams'];
        
        $updateScheduleData = $request['updateScheduleData'];
        if(isset($request['customWhere'])) {
            $this->db->where($request['customWhere']);
        } else {
            $this->db->where('apptid', $scheduleParams['apptid']);
        }
        
        $this->db->update('etp_diary', $updateScheduleData); 
        
        $logClass->log('Update etp_diary Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;
       
    }
}


/* End of file UpdateScheduleChain.php */