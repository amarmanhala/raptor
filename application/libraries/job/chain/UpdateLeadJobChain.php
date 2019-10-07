<?php 
/**
 * UpdateLeadJobChain Libraries Class
 *
 * This is a Job Chain Class use for update Lead job
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractJobChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateLeadJobChain Libraries Class
 *
 * This is a Job Chain Class use for update Lead job
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            job/chain
 * @filesource          UpdateLeadJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class UpdateLeadJobChain extends AbstractJobChain{
 
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
    * This function use for update jobs
     * 
    * @param array $request - the request is array of required parameter for update job
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateLeadJobChain');
     
        $params = $request['params'];
       
        if(isset($params['joblead_id']) && isset($request['updateJobLeadData'])){
            $updateJobData = $request['updateJobLeadData'];
            $this->db->where('joblead_id', $params['joblead_id']);
            $this->db->update('joblead', $updateJobData); 

            $logClass->log('Update joblead Query : '. $this->db->last_query());
        }
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateLeadJobChain.php */