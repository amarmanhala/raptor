<?php 
/**
 * Delete Contract Chain Libraries Class
 *
 * This is a Contract Chain class for delete in Contract
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Contract Chain Libraries Class
 *
 * This is a Contract Chain class for delete in Contract
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          DeleteContractChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContractChain extends AbstractContractorChain
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
    * This function use for delete Contract
      * 
    * @param array $request - the request is array of required parameter for delete Contract
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContractChain');
         
         $params = $request['params'];
 
        $this->db->where('id', $params['contractid']);
        $this->db->delete('con_contract'); 
         
        
        $logClass->log('Delete Contract Query : '. $this->db->last_query());
        
        $this->db->where('contractid', $params['contractid']);
        $this->db->delete('con_address');
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContractChain.php */