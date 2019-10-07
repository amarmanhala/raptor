<?php 
/**
 * InsertHelpLinkChain Libraries Class
 *
 * This is a Help Link Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertHelpLinkChain Libraries Class
 *
 * This is a Help Link Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          InsertHelpLinkChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertHelpLinkChain extends AbstractAdminChain
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
    * This function use for create Help
      * 
    * @param array $request - the request is array of required parameter for creating Help
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertHelpLinkChain');
       
        
        $helpLinkData = $request['helpLinkData'];
        $this->db->insert('cp_help_link', $helpLinkData);
        $id =  $this->db->insert_id();
        $request["helplinkid"] = $id;
        
        $logClass->log('Insert helpLink : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertHelpLinkChain.php */