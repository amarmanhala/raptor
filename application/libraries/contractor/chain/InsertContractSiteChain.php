<?php 
/**
 * InsertContractSiteChain Libraries Class
 *
 * This is a Cost Centre Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractContractorChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertContractSiteChain Libraries Class
 *
 * This is a Cost Centre Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            contractor/chain
 * @filesource          InsertContractSiteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertContractSiteChain extends AbstractContractorChain
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
    * This function use for create address record
      * 
    * @param array $request - the request is array of required parameter for creating address
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertContractSiteChain');
        
        $insertSiteData = $request['insertConSiteData'];
        
        $this->db->insert('con_address', $insertSiteData);
        $id =  $this->db->insert_id();
        $request["siteid"] = $id;

        $logClass->log('Insert Contract Site Query : '. $this->db->last_query());

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
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertContractSiteChain.php */