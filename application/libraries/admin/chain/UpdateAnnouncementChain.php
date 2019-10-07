<?php 
/**
 * UpdateAnnouncementChain Libraries Class
 *
 * This is a Announcement class for update Announcement
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateAnnouncementChain Libraries Class
 *
 * This is a Announcement class for update Announcement
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          UpdateAnnouncementChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateAnnouncementChain extends AbstractAdminChain{
 
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
            
        $logClass= new LogClass('jobtracker', 'UpdateAnnouncementChain');
         
        $params = $request['params'];

        $updateData = $request['updateAnnouncementData']; 
      
        $this->db->where('id', $params['announcementid']);
        $this->db->update('cp_message', $updateData); 
        
        $logClass->log('Update Announcement Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateAnnouncementChain.php */