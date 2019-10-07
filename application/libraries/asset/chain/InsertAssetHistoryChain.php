<?php 
/**
 * InsertAssetHistoryChain Libraries Class
 *
 * This is a Asset Chain Class use for create new Asset
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractAssetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertAssetHistoryChain Libraries Class
 *
 * This is a Asset Chain Class use for create new Asset
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset/chain
 * @filesource          InsertAssetHistoryChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertAssetHistoryChain extends AbstractAssetChain
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
    * This function use for create new Asset History
     * 
    * @param array $request - the request is array of required parameter for creating Asset History
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertAssetHistoryChain');
    
        $insertData = $request['insertData']; 
       
        $this->db->insert('asset_history', $insertData);
        $id =  $this->db->insert_id();
        
        $request["asset_history_id"] = $id;
        $logClass->log('Insert asset History Query : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertAssetHistoryChain.php */