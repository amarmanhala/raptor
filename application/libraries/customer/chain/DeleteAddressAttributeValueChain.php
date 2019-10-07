<?php 
/**
 * Delete Address Attribute Value Libraries Class
 *
 * This is a Address Attribute Value class for delete in address attribute value
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Address Attribute Value Libraries Class
 *
 * This is a Address Attribute Value class for delete address attribute value
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          DeleteAddressAttributeValueChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteAddressAttributeValueChain extends AbstractCustomerChain
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
    * This function use for delete contact
      * 
    * @param array $request - the request is array of required parameter for delete attribute value
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteAddressAttributeValueChain');
         
        $attributeParams = $request['attributeParams'];
 
        $this->db->where('id', $attributeParams['id']);
        $this->db->delete('addresslabel_attribute_value'); 
        
        $logClass->log('Delete Address Attribute Value Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteAddressAttributeValueChain.php */