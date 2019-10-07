<?php 
/**
 * InsertConParentJobChain Libraries Class
 *
 * This is a Contract Chain class for create a new record in Contract
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertConParentJobChain Libraries Class
 *
 * This is a Contract Chain class for create a new record in Contract
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          InsertConParentJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class InsertConParentJobChain extends AbstractContractorChain
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
    * This function use for create Contract records
      * 
    * @param array $request - the request is array of required parameter for create Contract record
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertConParentJobChain');
        
        $insertParentJobData = $request['insertParentJobData'];
        
        $this->db->insert('con_parentjob_id', $insertParentJobData); 
         
        $logClass->log('Insert parentjob_id Query : '. $this->db->last_query());
         
        if ($this->successor != NULL) {
            $this->successor->handleRequest($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertConParentJobChain.php */