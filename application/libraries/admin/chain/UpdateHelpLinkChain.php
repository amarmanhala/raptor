<?php 
/**
 * UpdateHelpLinkChain Libraries Class
 *
 * This is a Help Link class for update Help Link
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateHelpChain Libraries Class
 *
 * This is a Help class for update Help Link
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          UpdateHelpLinkChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateHelpLinkChain extends AbstractAdminChain{
 
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
    * This function use for update Help Link
     *  
    * @param array $request - the request is array of required parameter for update Help Link
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateHelpLinkChain');
         
        $params = $request['params'];

        $helpLinkData = $request['helpLinkData'];
      
        $this->db->where('id', $params['helplinkid']);
        $this->db->update('cp_help_link', $helpLinkData); 
        
        $logClass->log('Update Help Link Query : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateHelpLinkChain.php */