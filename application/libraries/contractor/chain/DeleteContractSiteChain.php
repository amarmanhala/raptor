<?php 
/**
 * Delete ContractSite Value Libraries Class
 *
 * This is a ContractSite Value class for delete in ContractSite
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete ContractSite Value Libraries Class
 *
 * This is a ContractSite Value class for delete ContractSite
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          DeleteContractSiteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteContractSiteChain extends AbstractContractorChain
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
    * This function use for delete Contract Site
      * 
    * @param array $request - the request is array of required parameter for delete Contract Site
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteContractSiteChain');
         
        
        $params = $request['params'];
        
        
 
        $this->db->where('id', $params['siteid']);
        $this->db->where('contractid', $params['contractid']);
        $this->db->delete('con_address'); 
        
        $logClass->log('Delete Contract Site Query : '. $this->db->last_query());
         
        $oldContractSiteData = $request['oldContractSiteData'];
        $siteAddressGroupids = $request['siteAddressGroupids'];
        if(count($siteAddressGroupids)>0){
            
            
            $this->db->where_in('groupid', $siteAddressGroupids);
            $this->db->where('labelid', $oldContractSiteData['labelid']);
            $this->db->delete('address_groupmember');
        }
 
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteContractSiteChain.php */