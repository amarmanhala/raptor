<?php 
/**
 * Edit Log Chain Libraries Class
 *
 * This is a EditLogChain class for create a new records in tig_editlog
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractSharedChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Edit Log Chain Libraries Class
 *
 * This is a EditLogChain class for create a new records in editlog Table
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            shared/chain
 * @filesource          EditLogChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class EditLogChain extends AbstractSharedChain
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
    * This function use for create Edit Log
      * 
    * @param array $request - the request is array of required parameter for creating Edit Log Records
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'EditLogChain');
        
        $editLogData = $request['editLogData'];
        if (count($editLogData)>0) {
            $this->db->insert_batch('editlog', $editLogData);
            $logClass->log('Insert Edit Log Query : '. $this->db->last_query());
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;
    }

}

/* End of file EditLogChain.php */