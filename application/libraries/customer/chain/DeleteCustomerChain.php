<?php 
/**
 * Delete Customer Chain Libraries Class
 *
 * This is a Customer Chain class for delete in Customer
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Customer Chain Libraries Class
 *
 * This is a Customer Chain class for delete in Customer
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          DeleteCustomerChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteCustomerChain extends AbstractCustomerChain
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
    * This function use for delete Customer
      * 
    * @param array $request - the request is array of required parameter for delete Customer
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteCustomerChain');
         
         $params = $request['params'];
 
        $this->db->where('customerid', $params['customerid']);
        $this->db->delete('customer'); 
         
        
        $logClass->log('Delete customer Query : '. $this->db->last_query());
        
        $this->db->where('customerid', $params['customerid']);
        $this->db->delete('contact');
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteCustomerChain.php */