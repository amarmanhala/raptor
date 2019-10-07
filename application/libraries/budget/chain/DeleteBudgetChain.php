<?php 
/**
 * Delete Budget Chain Libraries Class
 *
 * This is a Budget Chain class for delete in Budget
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractBudgetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Budget Chain Libraries Class
 *
 * This is a Budget Chain class for delete in Budget
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Budget/chain
 * @filesource          DeleteBudgetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteBudgetChain extends AbstractBudgetChain
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
    * This function use for delete Budget
      * 
    * @param array $request - the request is array of required parameter for delete Budget
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteBudgetChain');
         
        $params = $request['params'];
        if(isset($params['id']) || isset($params['recordid'])  || isset($params['glcodeid'])){
            
            if(isset($params['glcodeid']) && $params['glcodeid'] != ''){
                $this->db->where('glcodeid', $params['glcodeid']);
                $this->db->where('((yearno*100)+monthno)>=', $params['FromYearMonth']);
                $this->db->where('((yearno*100)+monthno)<=', $params['ToYearMonth']);
            }
            if(isset($params['id']) && $params['id'] != ''){
                $this->db->where('id', $params['id']);
            }
            if(isset($params['recordid']) && $params['recordid'] != ''){
                $this->db->where('recordid', $params['recordid']);
                $this->db->where('((yearno*100)+monthno)>=', $params['FromYearMonth']);
                $this->db->where('((yearno*100)+monthno)<=', $params['ToYearMonth']);
            }
            $this->db->delete('budget'); 
            $logClass->log('Delete Budget Query : '. $this->db->last_query());
        }
      
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;
    }

}

/* End of file DeleteContactChain.php */