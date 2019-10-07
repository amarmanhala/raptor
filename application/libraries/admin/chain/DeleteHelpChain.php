<?php 
/**
 * Delete Help Libraries Class
 *
 * This is a Help class for delete Help
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Help Libraries Class
 *
 * This is a Help class for delete Help
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Admin/chain
 * @filesource          DeleteHelpChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteHelpChain extends AbstractAdminChain
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
    * This function use for delete Help
      * 
    * @param array $request - the request is array of required parameter for delete Help
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteHelpChain');
         
        $params = $request['params'];
 
        $this->db->where('helpid', $params['helpid']);
        $this->db->delete('cp_help_link'); 
        $logClass->log('Delete Help Link Query : '. $this->db->last_query());
        
        $this->db->where('id', $params['helpid']);
        $this->db->delete('cp_help'); 
        
        $logClass->log('Delete Help Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteHelpChain.php */