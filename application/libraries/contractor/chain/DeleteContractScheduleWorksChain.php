<?php 
/**
 * Delete Contract Schedule Works Value Libraries Class
 *
 * This is a Contract Schedule Works Value class for delete in Contract Schedule Works
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Contract Schedule Works Value Libraries Class
 *
 * This is a Contract Schedule Works Value class for delete Contract Schedule Works
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          DeleteContractScheduleWorksChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContractScheduleWorksChain extends AbstractContractorChain
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
    * This function use for delete Contract Schedule Works
      * 
    * @param array $request - the request is array of required parameter for delete Contract Schedule Works
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContractScheduleWorksChain');
         
        
        $params = $request['params'];
         
        $this->db->where("(DATE(startdate) BETWEEN '".$params['fromdate']."' and '".$params['todate']."')");
        $this->db->where('schedule_def_id', $params['scheduleid']);
        $this->db->where('contractid', $params['contractid']);
        $this->db->delete('con_schedule'); 
        
        $logClass->log('Delete Contract Schedule Work Query : '. $this->db->last_query());
         
        
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContractScheduleWorksChain.php */