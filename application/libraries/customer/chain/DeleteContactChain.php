<?php 
/**
 * Delete Contact Chain Libraries Class
 *
 * This is a Contact Chain class for delete in contact
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Contact Chain Libraries Class
 *
 * This is a Contact Chain class for delete in contact
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          DeleteContactChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContactChain extends AbstractCustomerChain
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
    * @param array $request - the request is array of required parameter for delete contact
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContactChain');
         
        $params = $request['params'];
 
        $this->db->where('contactid', $params['contactid']);
        $this->db->delete('contact'); 
         
        
        $logClass->log('Delete Contact Query : '. $this->db->last_query());
        
        $this->db->where('contactid', $params['contactid']);
        $this->db->delete('groupmembers');
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContactChain.php */