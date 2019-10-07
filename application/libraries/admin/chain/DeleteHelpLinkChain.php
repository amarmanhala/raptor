<?php 
/**
 * Delete Help Link Libraries Class
 *
 * This is a Help Link class for delete Help Link
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Help Link Libraries Class
 *
 * This is a Help Link class for delete Help Link
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Admin/chain
 * @filesource          DeleteHelpLinkChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteHelpLinkChain extends AbstractAdminChain
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
        $logClass= new LogClass('jobtracker', 'DeleteHelpLinkChain');
         
        $params = $request['params'];
 
        $this->db->where('id', $params['helplinkid']);
        $this->db->delete('cp_help_link'); 
        $logClass->log('Delete Help Link Query : '. $this->db->last_query());
        
       
        $logClass->log('Delete Help Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteHelpLinkChain.php */