<?php 
/**
 * UpdateMenuModuleChain Libraries Class
 *
 * This is a Menu Module class for update Menu Module
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateMenuModuleChain Libraries Class
 *
 * This is a Menu Module class for update Menu Module
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          UpdateMenuModuleChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateMenuModuleChain extends AbstractAdminChain{
 
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
    * This function use for update Menu Module
     * 
    * @param array $request - the request is array of required parameter for update Menu Module
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateMenuModuleChain');
         
        $params = $request['params'];
 
        $updateData = $request['updateMenuModuleData']; 
      
        $this->db->where('id', $params['menuid']);
        $this->db->update('cp_module', $updateData); 
        
        $logClass->log('Update SiteModule Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateMenuModuleChain.php */