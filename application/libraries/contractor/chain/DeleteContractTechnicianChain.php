<?php 
/**
 * Delete ContractTechnician Value Libraries Class
 *
 * This is a ContractTechnician Value class for delete in ContractTechnician
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete ContractTechnician Value Libraries Class
 *
 * This is a ContractTechnician Value class for delete ContractTechnician
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          DeleteContractTechnicianChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContractTechnicianChain extends AbstractContractorChain
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
    * This function use for delete Contract Technician
      * 
    * @param array $request - the request is array of required parameter for delete Contract Technician
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContractTechnicianChain');
         
        
        $params = $request['params'];
        
        
 
        $this->db->where('id', $params['contechnicianid']);
        //$this->db->where('contractid', $params['contractid']);
        $this->db->delete('con_technician'); 
        
        $logClass->log('Delete Contract Technician Query : '. $this->db->last_query());
         
        
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContractTechnicianChain.php */