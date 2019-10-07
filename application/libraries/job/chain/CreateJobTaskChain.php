<?php 
/**
 * CreateJobTaskChain Libraries Class
 *
 * This is a Job Chain Class use for create new task
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractJobChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * CreateJobTaskChain Libraries Class
 *
 * This is a Job Chain Class use for create new task
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            job/chain
 * @filesource          CreateJobTaskChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class CreateJobTaskChain extends AbstractJobChain
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
    */
    public function setSuccessor($nextService)
    {
        $this->successor = $nextService;
    }
 
    /**
    * This function use for create new task
     * 
    * @param array $request - the request is array of required parameter for creating job task
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'CreateJobTaskChain');
        
        $jobTaskData = $request['jobTaskData'];
        $params = $request['params'];
       
        $this->db->insert('task', $jobTaskData);
         
        $id = $this->db->insert_id();
        $logClass->log('Insert job task Query : '. $this->db->last_query());
        $request["taskid"] = $id;

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file CreateJobTaskChain.php */