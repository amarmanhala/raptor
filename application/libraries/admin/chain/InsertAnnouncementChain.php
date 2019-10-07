<?php 
/**
 * InsertAnnouncementChain Libraries Class
 *
 * This is a Announcement Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertAnnouncementChain Libraries Class
 *
 * This is a Announcement Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          InsertAnnouncementChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertAnnouncementChain extends AbstractAdminChain
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
    * @param array $request - the request is array of required parameter for creating Announcement
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertAnnouncementChain');
        
        $insertGLCodeData = $request['insertAnnouncementData'];
        
        $this->db->insert('cp_message', $insertGLCodeData);
        $id =  $this->db->insert_id();
        $request["announcementid"] = $id;

        $logClass->log('Insert Announcement Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertAnnouncementChain.php */