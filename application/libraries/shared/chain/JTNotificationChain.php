<?php 
/**
 * JT Notification Chain Libraries Class
 *
 * This is a Job Tracker Notification Chain class, using this class records will be inserted into the jt_notification table.
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractSharedChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * JT Notification Chain Libraries Class
 *
 * This is a Job Tracker Notification Chain class, using this class records will be inserted into the jt_notification table.
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            shared/chain
 * @filesource          JTNotificationChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class JTNotificationChain extends AbstractSharedChain
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
    * This function use for create new Job Tracker Notification
     * 
    * @param array $request - the request is array of required parameter for creating JT Notification
    * @return integer -  
    */
    public function handleRequest($request)
    {
 
        $logClass= new LogClass('jobtracker', 'JTNotificationChain');
        $jtNotificationData = $request['jtNotificationData'];
   
        $this->db->insert('jt_notification', $jtNotificationData);
        $request["jt_notification_id"] =  $this->db->insert_id();
 
        $logClass->log('Insert JT Notification Query : '. $this->db->last_query());
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

           //it should be at the last part of chain
        $this -> returnValue = $request;

    }
     
    

}

/* End of file JTNotificationChain.php */