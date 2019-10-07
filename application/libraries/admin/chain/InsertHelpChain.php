<?php 
/**
 * InsertHelpChain Libraries Class
 *
 * This is a Help Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertHelpChain Libraries Class
 *
 * This is a Help Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          InsertHelpChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertHelpChain extends AbstractAdminChain
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
        $logClass= new LogClass('jobtracker', 'InsertHelpChain');
        
        $insertGLCodeData = $request['insertHelpData'];
        
        $this->db->insert('cp_help', $insertGLCodeData);
        $id =  $this->db->insert_id();
        $request["helpid"] = $id;

        $logClass->log('Insert Help Query : '. $this->db->last_query());

        $helpLinkData = array();
        if(isset($request['helpLinkData'])){
             $helpLinkData = $request['helpLinkData'];
        }
       
        if (count($helpLinkData)>0) {
            foreach ($helpLinkData as $key => $value) {
                $helpLinkData[$key]['helpid'] = $id;
            }
            $this->db->insert_batch('cp_help_link', $helpLinkData);
            $logClass->log('Insert helpLink : '. $this->db->last_query());
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertHelpChain.php */