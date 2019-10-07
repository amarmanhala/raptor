<?php 
/**
 * InsertAssetLocationChain Libraries Class
 *
 * This is a Asset Chain Class use for create new Asset
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractAssetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertAssetLocationChain Libraries Class
 *
 * This is a Asset Chain Class use for create new Asset
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset/chain
 * @filesource          InsertAssetLocationChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertAssetLocationChain extends AbstractAssetChain
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
    * This function use for create new Asset
     * 
    * @param array $request - the request is array of required parameter for creating Asset
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertAssetLocationChain');
      
        $assetData = $request['insertLocationData']; 
       
        $this->db->insert('asset_location', $assetData);
        $id =  $this->db->insert_id();
        
        $request["asset_location_id"] = $id;
        $logClass->log('Insert asset Location Query : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertAssetLocationChain.php */