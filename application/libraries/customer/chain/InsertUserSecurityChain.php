<?php 
/**
 * Insert User Security Chain Libraries Class
 *
 * This is a InsertUserSecurityChain class for insert User Security
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertUserSecurityChain Libraries Class
 *
 * This is a InsertUserSecurityChain class for insert User Security
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertUserSecurityChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class InsertUserSecurityChain extends AbstractCustomerChain {
 
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
    * This function use for insert user security
     * 
    * @param array $request - the request is array of required parameter for insert user security
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'InsertUserSecurityChain');
         
        $params = $request['params'];
        $insertData = $request['insertData'];
        
        if (count($insertData)>0) {
            $this->db->insert_batch('cp_contactsecurity', $insertData);
            $logClass->log('Insert User Security Query : '. $this->db->last_query());
        }
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertUserSecurityChain.php */