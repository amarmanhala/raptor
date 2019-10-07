<?php 
/**
 * InsertCustomerChain Libraries Class
 *
 * This is a customer Chain class for create a new record in customer
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertCustomerChain Libraries Class
 *
 * This is a customer Chain class for create a new record in customer
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertCustomerChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class InsertCustomerChain extends AbstractCustomerChain
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
    * This function use for create customer records
      * 
    * @param array $request - the request is array of required parameter for create customer record
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertCustomerChain');
        
        $insertCustomerData = $request['insertCustomerData'];
        
        $this->db->insert('customer', $insertCustomerData);
        $customerid =  $this->db->insert_id();
        $request['insertCustomerData']['customerid'] = $customerid;
      
        
        $request["customerid"] = $customerid;
        $logClass->log('Insert customer Query : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertCustomerChain.php */