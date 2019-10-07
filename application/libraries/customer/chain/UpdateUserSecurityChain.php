<?php 
/**
 * Update User Security Chain Libraries Class
 *
 * This is a UpdateUserSecurityChain class for update User Security
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateUserSecurityChain Libraries Class
 *
 * This is a UpdateUserSecurityChain class for update User Security
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateUserSecurityChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateUserSecurityChain extends AbstractCustomerChain {
 
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
    * This function use for update user security
     * 
    * @param array $request - the request is array of required parameter for update user security
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateUserSecurityChain');
         
        $params = $request['params'];
        $updateData = $request['updateData'];
 
        //$this->db->where('id', $params['id']);
        //$this->db->update('cp_contactsecurity', $updateData); 
        
        if (count($updateData)>0) {
            $this->db->update_batch('cp_contactsecurity', $updateData, 'id');
            $logClass->log('Update User Security Query : '. $this->db->last_query());
        }
        
        //$logClass->log('Update User Security Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateUserSecurityChain.php */