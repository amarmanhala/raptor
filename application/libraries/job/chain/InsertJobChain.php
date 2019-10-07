<?php 
/**
 * InsertJobChain Libraries Class
 *
 * This is a Job Chain Class use for create new job
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractJobChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertJobChain Libraries Class
 *
 * This is a Job Chain Class use for create new job
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            job/chain
 * @filesource          InsertJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertJobChain extends AbstractJobChain
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
    * This function use for create new job
     * 
    * @param array $request - the request is array of required parameter for creating job
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertJobChain');
        
        $jobData = $request['jobData'];
        $params = $request['params'];
        
        $ContactRules = $request['ContactRules'];
        
        $this->db->insert('jobs', $jobData);
         
        $jobid = $this->db->insert_id();
        $logClass->log('Insert job Query : '. $this->db->last_query());
        $request["jobid"] = $jobid;
        
        if (isset($ContactRules["auto_create_custordref_in_client_portal"]) && $ContactRules["auto_create_custordref_in_client_portal"] == 1){
            $update_data =  array('custordref'=>$jobid);
            $this->db->where('jobid', $jobid);
            $this->db->update('jobs', $update_data);  
            $logClass->log('Update auto_create_custordref_in_client_portal Query : '. $this->db->last_query());
        }
         
        if( isset($request['emailData'])){
            foreach ($request['emailData'] as $key => $value) {
                $subject = $request['emailData'][$key]['subject'];
                $message = $request['emailData'][$key]['message'];
                
                $subject = str_replace("<job>", $request['jobid'], $subject);
                $message =str_replace('<job>', $request['jobid'], $message);
               
                $request['emailData'][$key]['subject'] = $subject;
                $request['emailData'][$key]['message'] = $message;
            }
         
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertJobChain.php */