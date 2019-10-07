<?php 
/**
 * InsertAddressAttributeChain Libraries Class
 *
 * This is a Address Attribute Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertAddressAttributeChain Libraries Class
 *
 * This is a Address Attribute Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertAddressAttributeChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertAddressAttributeChain extends AbstractCustomerChain
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
    * This function use for create Address Attribute
      * 
    * @param array $request - the request is array of required parameter for creating Address Attribute
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertAddressAttributeChain');
        
        $attributeData = $request['attributeData'];
        
        $this->db->insert('addresslabel_attribute', $attributeData);
        $id =  $this->db->insert_id();
        $request["addressattributeid"] = $id;

        $logClass->log('Insert Address Attribute Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertAddressAttributeChain.php */