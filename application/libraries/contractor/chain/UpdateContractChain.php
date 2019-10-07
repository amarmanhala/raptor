<?php 
/**
 * UpdateContractChain Libraries Class
 *
 * This is a UpdateContractChain class for update Contract
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateContractChain Libraries Class
 *
 * This is a UpdateContractChain class for update Contract
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          UpdateContractChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateContractChain extends AbstractContractorChain{
 
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
    * This function use for update Contract
     * 
    * @param array $request - the request is array of required parameter for update Contract
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateContractChain');
         
        $customerParams = $request['params'];

        $updateData = $request['updateContractData'];  
        $this->db->where('id', $customerParams['contractid']);
        $this->db->update('con_contract', $updateData); 
        
        $logClass->log('Update Contract Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateContractChain.php */