<?php 
/**
 * Create Address Attribute Chain Libraries Class
 *
 * This is a Create Address Attribute Chain class for create a new Address Attribute 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Create Address Attribute Chain Libraries Class
 *
 * This is a Create Address Attribute Chain class for create address attribute
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          createAddressAttributeChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class CreateAddressAttributeChain extends AbstractCustomerChain
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
        $logClass= new LogClass('jobtracker', 'CreateAddressAttributeChain');
        
        $attributeData = $request['attributeData'];
        $attributeParams = $request['attributeParams'];
        
        $this->db->insert('addresslabel_attribute', $attributeData);
        $id =  $this->db->insert_id();
        $request["id"] = $id;
        
        $customerAttributedData = array(
            'attributeid'   => $id,
            'customerid'    => $attributeParams['customerid'],
            'status'        => 1
        );
        
        $this->db->insert('addresslabelattribute_customer', $customerAttributedData);
        $id =  $this->db->insert_id();
        $request["customerattributeid"] = $id;

        $logClass->log('Create Address Attribute Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file CreateAddressAttributeChain.php */