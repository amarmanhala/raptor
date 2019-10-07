<?php 
/**
 * UpdateContractSiteChain Libraries Class
 *
 * This is a Contract Site class for update Contract Site
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateContractSiteChain Libraries Class
 *
 * This is a Contract Site class for update Contract Site
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          UpdateContractSiteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateContractSiteChain extends AbstractContractorChain{
 
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
    * This function use for update customer
     * 
    * @param array $request - the request is array of required parameter for update customer
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateContractSiteChain');
         
        $params = $request['params'];
     
        $updateSiteData = $request['updateConSiteData']; 
    
        
        
        $this->db->where('id', $params['siteid']);
        $this->db->where('contractid',   $params['contractid']);
        $this->db->update('con_address', $updateSiteData); 
        
        $logClass->log('Update Contract Site Query : '. $this->db->last_query());
       
        $oldContractSiteData = $request['oldContractSiteData'];
        
        $siteAddressGroupids = $request['siteAddressGroupids'];
        
        if(count($siteAddressGroupids)>0){
            $this->db->where_in('groupid', $siteAddressGroupids);
            $this->db->where('labelid', $oldContractSiteData['labelid']);
            $this->db->delete('address_groupmember');
        }
        
        $addressGroupData = array();
        if(isset($request['addressGroupData'])){
             
            $addressGroupData = $request['addressGroupData'];
        }
       
        if (count($addressGroupData)>0) {
            
            foreach ($addressGroupData as $key => $value) {
                $this->db->where('groupid', $value['groupid']);
                $this->db->where('labelid', $value['labelid']);
                $this->db->delete('address_groupmember');
            }
            
            $this->db->insert_batch('address_groupmember', $addressGroupData);
            $logClass->log('Insert address_groupmember : '. $this->db->last_query());
            
        }
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateContractSiteChain.php */