<?php 
/**
 * JobNote  Chain Libraries Class
 *
 * This is a Job Note Chain class for create a new record in job note table
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractSharedChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * JobNote Chain Libraries Class
 *
 * This is a Job Note Chain class for create a new record in job note table
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            shared/chain
 * @filesource          JobNoteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

 
class JobNoteChain extends AbstractSharedChain
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
    * This function use for create new Job Note
    * @param array $request - the request is array of required parameter for creating Job Note
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {

        $logClass= new LogClass('jobtracker', 'JobNoteChain');
        $jobNoteData = $request['jobNoteData'];
             
        $this->db->insert('jobnote', $jobNoteData);
        $request["jobnoteid"] =  $this->db->insert_id();
        $logClass->log('Insert Job Note Query : '. $this->db->last_query());
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
        
          //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file JobNoteChain.php */