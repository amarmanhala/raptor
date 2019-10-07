<?php 
/**
 * InsertMenuModuleChain Libraries Class
 *
 * This is a MenuModule Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertMenuModuleChain Libraries Class
 *
 * This is a MenuModule Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          InsertMenuModuleChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertMenuModuleChain extends AbstractAdminChain
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
    * This function use for create Announcement
      * 
    * @param array $request - the request is array of required parameter for creating MenuModule
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertMenuModuleChain');
        
        $insertMenuModuleData = $request['insertMenuModuleData'];
        
        $this->db->insert('cp_module', $insertMenuModuleData);
        $id =  $this->db->insert_id();
        $request["menuid"] = $id;

        $logClass->log('Insert MenuModule Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertMenuModuleChain.php */