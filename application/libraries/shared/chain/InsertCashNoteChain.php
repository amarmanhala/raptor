<?php 
/**
 * InsertCashNoteChain Libraries Class
 *
 * This is a Cash Note class for create a new record in cash Note
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractSharedChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertCashNoteChain Libraries Class
 *
 * This is a Cash Note class for create a new record in cash Note
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            shared/chain
 * @filesource          InsertCashNoteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertCashNoteChain extends AbstractSharedChain
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
    * This function use for create note record
      * 
    * @param array $request - the request is array of required parameter for creating note
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertCashNoteChain');
        
        $noteData = $request['casNoteData'];
        
        $this->db->insert('cashnote', $noteData);
        $id =  $this->db->insert_id();
        $request["noteid"] = $id;

        $logClass->log('Insert Cash Note Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertCashNoteChain.php */