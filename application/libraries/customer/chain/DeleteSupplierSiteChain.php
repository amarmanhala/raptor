<?php 
/**
 * Delete SupplierSite Value Libraries Class
 *
 * This is a SupplierSite Value class for delete in address attribute value
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete SupplierSite Value Libraries Class
 *
 * This is a SupplierSite Value class for delete address attribute value
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          DeleteSupplierSiteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteSupplierSiteChain extends AbstractCustomerChain
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
        $logClass= new LogClass('jobtracker', 'DeleteSupplierSiteChain');
         
        $params = $request['params'];
 
        $this->db->where('id', $params['siteid']);
        $this->db->delete('cp_supplier_address'); 
        
        $logClass->log('Delete SupplierSite Value Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteSupplierSiteChain.php */