<?php 
/**
 * UpdateBudgetChain Libraries Class
 *
 * This is a Budget Chain Class use for update Budget
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractBudgetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateBudgetChain Libraries Class
 *
 * This is a Budget Chain Class use for update Budget
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Budget/chain
 * @filesource          UpdateBudgetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class UpdateBudgetChain extends AbstractBudgetChain{
 
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
    * This function use for update Budget
     * 
    * @param array $request - the request is array of required parameter for update Budget
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateBudgetChain');
 
        $updateData = $request['updateData'];
        
        if (count($updateData)>0) {
            $this->db->update_batch('budget', $updateData, 'id');
            $logClass->log('Update User Budget Query : '. $this->db->last_query());
        }
       
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateBudgetChain.php */