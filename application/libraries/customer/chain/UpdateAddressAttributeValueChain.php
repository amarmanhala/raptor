<?php 
/**
 * Update Address Attribute Value Chain Libraries Class
 *
 * This is a UpdateAddressAttributeValueChain class for update Address Attribute Value
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * updateAddressAttributeValueChain Libraries Class
 *
 * This is a updateAddressAttributeValueChain class for update Address Attribute Value
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateAddressAttributeValueChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class updateAddressAttributeValueChain extends AbstractCustomerChain {
 
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
            
        $logClass= new LogClass('jobtracker', 'updateAddressAttributeValueChain');
         
        $attributeParams = $request['attributeParams'];
        $updateData = $request['updateData'];
 
        $this->db->where('id', $attributeParams['id']);
        $this->db->update('addresslabel_attribute_value', $updateData); 
        
        $logClass->log('Update Address Attribute Value Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file updateAddressAttributeValueChain.php */