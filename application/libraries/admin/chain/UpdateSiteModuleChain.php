<?php 
/**
 * UpdateSiteModuleChain Libraries Class
 *
 * This is a SiteModule class for update SiteModule
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateSiteModuleChain Libraries Class
 *
 * This is a Announcement class for update SiteModule
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          UpdateSiteModuleChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateSiteModuleChain extends AbstractAdminChain{
 
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
    * This function use for update Announcement
     * 
    * @param array $request - the request is array of required parameter for update Announcement
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateSiteModuleChain');
         
        $params = $request['params'];
 
        $updateData = $request['updateSiteModuleData']; 
      
        $this->db->where('id', $params['siteid']);
        $this->db->update('cp_site', $updateData); 
        
        $logClass->log('Update SiteModule Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateSiteModuleChain.php */