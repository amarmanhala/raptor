<?php 
/**
 * InsertContactChain Libraries Class
 *
 * This is a Contact Chain class for create a new record in contact
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertContactChain Libraries Class
 *
 * This is a Contact Chain class for create a new record in contact
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertContactChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class InsertContactChain extends AbstractCustomerChain
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
    * This function use for create contact records
      * 
    * @param array $request - the request is array of required parameter for create contact record
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertContactChain');
        
        $contactData = $request['insertContactData'];
        
        $this->db->insert('contact', $contactData);
        $contactid =  $this->db->insert_id();
        $request['contactData']['contactid'] = $contactid;
      
        
        $request["contactid"] = $contactid;
        $logClass->log('Insert Contact Query : '. $this->db->last_query());
        
        $mailGroupData = array();
        if(isset($request['mailGroupData'])){
             $mailGroupData = $request['mailGroupData'];
        }
       
        if (count($mailGroupData)>0) {
            foreach ($mailGroupData as $key => $value) {
                $mailGroupData[$key]['contactid'] = $contactid;
            }
            $this->db->insert_batch('groupmembers', $mailGroupData);
            $logClass->log('Insert groupmembers : '. $this->db->last_query());
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertContactChain.php */