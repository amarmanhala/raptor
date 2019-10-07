<?php 
/**
 * Create Address Attribute Value  Chain Libraries Class
 *
 * This is a Create Address Attribute Value  Chain class for create address attribute value 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Create Address Attribute Value Chain Libraries Class
 *
 * This is a Create Address Attribute Value Chain class for create address attribute value
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          CreateAddressAttributeValueChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class CreateAddressAttributeValueChain extends AbstractCustomerChain
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
    * This function use for create address record
      * 
    * @param array $request - the request is array of required parameter for creating address
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'CreateAddressAttributeValueChain');
        
        $attributeData = $request['attributeData'];
        
        $this->db->insert('addresslabel_attribute_value', $attributeData);
        $id =  $this->db->insert_id();
        $request["id"] = $id;

        $logClass->log('Create Address Attribute Value Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file CreateAddressAttributeValueChain.php */