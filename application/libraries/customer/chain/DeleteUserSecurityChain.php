<?php 
/**
 * Delete User Security Libraries Class
 *
 * This is a Delete User Security class for delete user security
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete User Security Libraries Class
 *
 * This is a Delete User Security class for delete user security
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          DeleteUserSecurityChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteUserSecurityChain extends AbstractCustomerChain
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
    * @param array $request - the request is array of required parameter for delete user security
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteUserSecurityChain');
         
        $params = $request['params'];
 
        $this->db->where('id', $params['id']);
        $this->db->delete('cp_contactsecurity'); 
        
        $logClass->log('Delete User Security Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteUserSecurityChain.php */