<?php 
/**
 * UpdateHelpChain Libraries Class
 *
 * This is a Help class for update Help
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateHelpChain Libraries Class
 *
 * This is a Help class for update Help
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          UpdateHelpChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateHelpChain extends AbstractAdminChain{
 
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
    * This function use for update Help
     * 
    * @param array $request - the request is array of required parameter for update Help
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateHelpChain');
         
        $params = $request['params'];

        $updateData = $request['updateHelpData']; 
      
        $this->db->where('id', $params['helpid']);
        $this->db->update('cp_help', $updateData); 
        
        $logClass->log('Update Help Query : '. $this->db->last_query());
       
        $helpLinkData = array();
        if(isset($request['helpLinkData'])){
            //$this->db->where('helpid', $params['helpid']);
            //$this->db->delete('cp_help_link'); 
            $helpLinkData = $request['helpLinkData'];
        }
       
        if (count($helpLinkData)>0) {
            foreach ($helpLinkData as $key => $value) {
                $helpLinkData[$key]['helpid'] = $params['helpid'];
            }
            $this->db->insert_batch('cp_help_link', $helpLinkData);
            $logClass->log('Insert helpLink : '. $this->db->last_query());
        }
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateHelpChain.php */