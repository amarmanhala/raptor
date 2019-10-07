<?php 
/**
 * InsertBudgetChain Libraries Class
 *
 * This is a Budget Chain Class use for create new Budget
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractBudgetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertBudgetChain Libraries Class
 *
 * This is a Budget Chain Class use for create new Budget
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Budget/chain
 * @filesource          InsertBudgetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertBudgetChain extends AbstractBudgetChain
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
    */
    public function setSuccessor($nextService)
    {
        $this->successor = $nextService;
    }
 
    /**
    * This function use for create new Budget
     * 
    * @param array $request - the request is array of required parameter for creating Budget
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertBudgetChain');
        $params = $request['params'];
        $budgetData = $request['budgetData']; 
        if(count($budgetData)>0){
            $this->db->insert_batch('budget', $budgetData);

            $logClass->log('Insert budget Query : '. $this->db->last_query());
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertBudgetChain.php */