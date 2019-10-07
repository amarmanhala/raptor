<?php 
/**
 * Delete Announcement Libraries Class
 *
 * This is a Announcement class for delete Announcement
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Announcement Libraries Class
 *
 * This is a Announcement class for delete Announcement
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Admin/chain
 * @filesource          DeleteAnnouncementChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteAnnouncementChain extends AbstractAdminChain
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
    * This function use for delete Announcement
      * 
    * @param array $request - the request is array of required parameter for delete Announcement
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteAnnouncementChain');
         
        $params = $request['params'];
 
        $this->db->where('id', $params['announcementid']);
        $this->db->delete('cp_message'); 
        
        $logClass->log('Delete Announcement Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteAnnouncementChain.php */