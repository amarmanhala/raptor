<?php 
/**
 * Delete Address Attribute Libraries Class
 *
 * This is a Delete Address Attribute class for delete in address attribute value
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Address Attribute Value Libraries Class
 *
 * This is a Delete Address Attribute class for delete address attribute value
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          DeleteAddressAttributeChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteAddressAttributeChain extends AbstractCustomerChain
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
        $logClass= new LogClass('jobtracker', 'DeleteAddressAttributeChain');
         
       
        $params = $request['params'];
       
        $this->db->where('id', $params['addressattributeid']); 
        $this->db->delete('addresslabel_attribute'); 
        
        $logClass->log('Delete Address Attribute Value Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteAddressAttributeChain.php */