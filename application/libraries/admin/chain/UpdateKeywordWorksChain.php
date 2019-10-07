<?php 
/**
 * UpdateKeywordWorksChain Libraries Class
 *
 * This is a Announcement class for update Announcement
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateKeywordWorksChain Libraries Class
 *
 * This is a Announcement class for update Announcement
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            admin/chain
 * @filesource          UpdateKeywordWorksChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class UpdateKeywordWorksChain extends AbstractAdminChain{
 
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
    * This function use for update Announcement
     * 
    * @param array $request - the request is array of required parameter for update Announcement
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateKeywordWorksChain');
         
        $params = $request['params'];

        $updateData = $request['updateData']; 
      
        $this->db->where('id', $params['id']);
        $this->db->update('se_keyword_subworks', $updateData); 
        
        $logClass->log('Update Keyword works Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateKeywordWorksChain.php */