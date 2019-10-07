<?php 
/**
 * Update Address Attribute Chain Libraries Class
 *
 * This is a Update Address Attribute Chain class for update Address Attribute
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Update Address Attribute Chain Libraries Class
 *
 * This is a Update Address Attribute Chain class for update Address Attribute
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateAddressAttributeChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateAddressAttributeChain extends AbstractCustomerChain {
 
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
            
        $logClass= new LogClass('jobtracker', 'UpdateAddressAttributeChain');
         
        $params = $request['params'];
      
        $attributeData = $request['attributeData'];
 
        $this->db->where('id', $params['addressattributeid']);
        $this->db->update('addresslabel_attribute', $attributeData); 
        
        $logClass->log('Update Address Attribute Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file updateAddressAttributeChain.php */