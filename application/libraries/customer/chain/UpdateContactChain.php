<?php 
/**
 * UpdateContactChain Libraries Class
 *
 * This is a UpdateContactChain class for update contact
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateContactChain Libraries Class
 *
 * This is a UpdateContactChain class for update contact
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateContactChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateContactChain extends AbstractCustomerChain{
 
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
    * This function use for update Contact
    * @param array $request - the request is array of required parameter for contact data
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateContactChain');
         
        $params = $request['params'];
        $updateContactData = $request['updateContactData'];
        
        $updateContactData['editdate'] = date('Y-m-d H:i:s', time());
 
        $this->db->where('contactid', $params['contactid']);
        $this->db->update('contact', $updateContactData); 
        
        $logClass->log('Update Contact Query : '. $this->db->last_query());
       
        
        $mailGroupData = array();
        if(isset($request['mailGroupData'])){
            $this->db->where('contactid', $params['contactid']);
            $this->db->delete('groupmembers');
            $mailGroupData = $request['mailGroupData'];
        }
       
        if (count($mailGroupData)>0) {
            $this->db->insert_batch('groupmembers', $mailGroupData);
            $logClass->log('Update groupmembers : '. $this->db->last_query());
        }
        
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

          //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateContactChain.php */