<?php 
/**
 * UpdateAddressChain Libraries Class
 *
 * This is a UpdateAddressChain class for update customer Address
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateAddressChain Libraries Class
 *
 * This is a UpdateAddressChain class for update customer Address
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateAddressChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateAddressChain extends AbstractCustomerChain{
 
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
    * This function use for update address
     * 
    * @param array $request - the request is array of required parameter for update address
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateAddressChain');
         
        $addressParams = $request['addressParams'];
        $updateData = $request['updateData'];
        
        $updateData['editdate'] = date('Y-m-d H:i:s', time());
 
        $this->db->where('labelid', $addressParams['labelid']);
        $this->db->update('addresslabel', $updateData); 
        
        $logClass->log('Update Address Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateAddressChain.php */